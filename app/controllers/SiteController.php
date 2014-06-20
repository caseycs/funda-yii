<?php
class SiteController extends CController
{
    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'

        //amsterdam-all
        $data = array(
            'all' => $this->stats(FundaFilter::ID_AMSTERDAM_ALL),
            'garden' => $this->stats(FundaFilter::ID_AMSTERDAM_GARDEN),
        );

//        d($data);

        $this->render('index', $data);
    }

    private function stats($filter_id)
    {
        //amsterdam-all
        $sql = 'SELECT a.*, COUNT(r.id) AS cnt
            FROM funda_page_realty_link fprl
            JOIN realty r ON r.id = fprl.realty_id
            JOIN agent a ON a.id = r.agent_id
            WHERE d_funda_filter_id = ' . $filter_id . '
            GROUP BY a.id
            ORDER BY cnt DESC
            LIMIT 10';
        //cache for 10 minutes
        $rows = Yii::app()->db->cache(600)->createCommand($sql)->queryAll();
        return $rows;
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }
}
