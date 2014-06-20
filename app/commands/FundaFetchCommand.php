<?php
class FundaFetchCommand extends CConsoleCommand
{
    //update pages once per x seconds
    public $page_expire;

    //requests per minute limit - may be lower then actuall value, to split it between different scripts
    public $rpm_limit;

    //how many pages to process before exit - to prevent memory leaks
    public $pages_limit;

    /* @var FundaClient */
    private $FundaClient;

    /* @var CMemCache */
    private $CMemCache;

    /* @var CDbConnection */
    private $DB;

    public function init()
    {
        if ($this->rpm_limit < 2) throw new LogicException('rpm_limit lower then 2');
        if ($this->pages_limit < 1) throw new LogicException('pages_limit lower then 1');

        //for code complete
        $this->CMemCache = Yii::app()->cache;
        $this->FundaClient = Yii::app()->fundaClient;
        $this->DB = Yii::app()->db;
    }

    public function run($args)
    {
        Yii::log('FundaFetch start');

        if ($this->initialFetchPagesCount()) {
            $this->updatePages();
        }

        Yii::log('FundaFetch finish');

        return 0;
    }

    /**
     * initial fetch pages count - if script executes first time or new filter added
     */
    private function initialFetchPagesCount()
    {
        foreach (FundaFilter::model()->findAll('fetch_time IS NULL') as $FundaFilter) {
            Yii::log('Initial fill pages: FundaFilter #' . $FundaFilter->id);

            if (!$this->isFundaRequestAvaliable()) {
                Yii::log('Funda rpm limit reached, exit now');

                return false;
            }
            $result = $this->fetchPage($FundaFilter, 1);
            $this->storePages($FundaFilter, $result);
        }

        return true;
    }

    /**
     * Store pages count and create pages rows in DB
     */
    private function storePages(FundaFilter $FundaFilter, array $result)
    {
        if (!isset($result['Paging']['AantalPaginas'])) {
            throw new \Exception('Unexpected Funda output - paging');
        }

        // actually $pages_count is very often incorrect - and realle there are less pages
        // but we take this value and perform some blank requests to make our code bulletproof
        $pages_count = $result['Paging']['AantalPaginas'];

        for ($i = 1; $i <= $pages_count; $i ++) {
            $pages = $FundaFilter->fundaPages(array('condition' => 'number=' . $i));
            if (empty($pages)) {
                //create new one
                $FundaPage = new FundaPage();
                $FundaPage->funda_filter_id = $FundaFilter->id;
                $FundaPage->number = $i;
                if (!$FundaPage->save()) {
                    throw new \Exception('FundaPage save fail');
                }
            }
        }

        //remove all orphan pages
        //rows in funda_page_realty_link will be removed by mysql because of constraint key
        $condition = 'funda_filter_id=:funda_filter_id AND number > :number';
        $params = array('funda_filter_id' => $FundaFilter->id, 'number' => $pages_count);
        FundaPage::model()->deleteAll($condition, $params);

        //process current page
        $FundaFilter->fetch_time = new CDbExpression('NOW()');
        if (!$FundaFilter->save()) {
            throw new \Exception('FundaFilter save fail');
        }
    }

    /**
     * Update pages according to $this->page_expire
     */
    private function updatePages()
    {
        $pages_left = $this->pages_limit;
        while ($pages_left > 0) {
            $filter = array(
                'condition' =>
                    't.fetch_time IS NULL '
                    . 'OR t.fetch_time < NOW() - INTERVAL ' . $this->page_expire . ' SECOND',
                'order' => 't.fetch_time DESC',
                'limit' => 1,
            );
            //we use with() to prevent sql query on $FundaPage->fundaFilter
            /* @var FundaPage $FundaPage */
            $FundaPage = FundaPage::model()->with('fundaFilter')->find($filter);
            if (!$FundaPage) {
                Yii::log('No pages to update');
                break;
            }

            $FundaFilter = $FundaPage->fundaFilter;

            Yii::log('Updating FundaFilter #' . $FundaFilter->id . ', page №' . $FundaPage->number);

            if (!$this->isFundaRequestAvaliable()) {
                Yii::log('Funda rpm limit reached, exit now');
                break;
            }
            $result = $this->fetchPage($FundaFilter, $FundaPage->number);

            //for the 1st page also update pages count
            if ($FundaPage->number === '1') {
                $this->storePages($FundaFilter, $result);
            }
            $this->updatePage($FundaPage, $result);

            $pages_left --;
        }
    }

    /**
     * Store data from Funda json to DB
     */
    private function updatePage(FundaPage $FundaPage, array $result)
    {
        if (!isset($result['Objects']) || !is_array($result['Objects'])) {
            throw new \Exception('Unexpected Funda output - objects');
        }

        $realties_ids = array();

        foreach ($result['Objects'] as $realty) {
            if (!array_key_exists('GlobalId', $realty)
                || !array_key_exists('MakelaarId', $realty)
                || !array_key_exists('MakelaarNaam', $realty)
            ) {
                throw new \Exception('Unexpected Funda output - realty');
            }

            //agent
            $Agent = Agent::model()->findByAttributes(array('MakelaarId' => $realty['MakelaarId']));
            if (!$Agent) {
                $Agent = new Agent;
                $Agent->MakelaarId = (int) $realty['MakelaarId'];
                $Agent->MakelaarNaam = $realty['MakelaarNaam'];
            }
            $Agent->update_time = new CDbExpression('NOW()');
            $Agent->save();

            //realty
            $Realty = Realty::model()->findByAttributes(array('GlobalId' => $realty['GlobalId']));
            if (!$Realty) {
                $Realty = new Realty;
                $Realty->GlobalId = $realty['GlobalId'];
                $Realty->agent_id = $Agent->id;
            }
            $Realty->update_time = new CDbExpression('NOW()');
            $Realty->save();

            $realties_ids[] = $Realty->id;

            //insert/update funda_page_realty_link
            $command = $this->DB->createCommand('INSERT INTO funda_page_realty_link
                SET
                    d_funda_filter_id = ' . $FundaPage->fundaFilter->id . ',
                    funda_page_id = ' . $FundaPage->id . ',
                    realty_id = ' . $Realty->id . '
                ON DUPLICATE KEY UPDATE funda_page_id = ' . $FundaPage->id);
            //Exception will be dropped here by CDbCommand
            $command->execute();
        }

        //remove orphan rowsin funda_page_realty_link
        $condition = 'd_funda_filter_id = ' . $FundaPage->fundaFilter->id
            . ' AND funda_page_id = ' . $FundaPage->id;
        if ($realties_ids) {
            $condition .= ' AND realty_id NOT IN(' . join(',', $realties_ids) . ')';
        }
        //Exception will be also dropped here
        $this->DB->createCommand()->delete('funda_page_realty_link', $condition);

        //finally update FundaPage
        $FundaPage->fetch_time = new CDbExpression('NOW()');
        $FundaPage->save();
    }

    /**
     * To control how much requests per second this script makes to Funda we use 2 keys in memcache
     * This is useful if we have few scripts, that use one API and they should split its RPM limit
     */
    private function isFundaRequestAvaliable()
    {
        $start = $this->CMemCache->get(MemCacheKeys::FUNDA_FETCH_REQUESTS_START);
        if (!$start || $start < time() - 60) {
            $this->CMemCache->set(MemCacheKeys::FUNDA_FETCH_REQUESTS_START, time());
            $this->CMemCache->set(MemCacheKeys::FUNDA_FETCH_REQUESTS_MADE, 1);
            Yii::log('Funda requests made set to 1');

            return true;
        }

        $made = $this->CMemCache->get(MemCacheKeys::FUNDA_FETCH_REQUESTS_MADE);
        if (!$made) {
            $this->CMemCache->set(MemCacheKeys::FUNDA_FETCH_REQUESTS_MADE, 1);
            Yii::log('Funda requests made set to 1');

            return true;
        } elseif ($made >= $this->rpm_limit) {
            Yii::log('Funda requests limit reached');

            return false;
        } else {
            $this->CMemCache->memCache->increment(MemCacheKeys::FUNDA_FETCH_REQUESTS_MADE, 1);
            Yii::log('Funda requests left: ' . ($this->rpm_limit - --$made));

            return true;
        }
    }

    /**
     * Transform FundaFilter to FundaClient request params and perform request
     */
    private function fetchPage(FundaFilter $FundaFilter, $page)
    {
        $options = array();
        if ($FundaFilter->is_garden) {
            $options[] = FundaClient::OPTION_GARDEN;
        }

        $result = $this->FundaClient->fetch(
            $FundaFilter->location,
            $FundaFilter->type,
            $options,
            $page
        );

        return $result;
    }
}
