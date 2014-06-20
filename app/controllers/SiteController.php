<?php
class SiteController extends CController
{
    public function actionIndex()
    {
        //data for template
        $data = array(
            'all' => $this->stats(FundaFilter::ID_AMSTERDAM_ALL),
            'garden' => $this->stats(FundaFilter::ID_AMSTERDAM_GARDEN),
        );

        $this->render('index', $data);
    }

    /**
     * Retrieve data from DB
     */
    private function stats($filter_id)
    {
        $sql = 'SELECT a.*, COUNT(r.id) AS cnt
            FROM funda_page_realty_link fprl
            JOIN realty r ON r.id = fprl.realty_id
            JOIN agent a ON a.id = r.agent_id
            WHERE d_funda_filter_id = ' . $filter_id . '
            GROUP BY a.id
            ORDER BY cnt DESC
            LIMIT 10';
        $rows = Yii::app()
            ->db
            ->cache(300) //cache for 5 minutes
            ->createCommand($sql)
            ->queryAll();

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
