<?php
class StdoutLogRoute extends CFileLogRoute
{
    public function init()
    {
        parent::init();
        // this makes console loggings live - so we write to our log routes every time
        // it is a bit slow, but makes easy to debug
        Yii::getLogger()->autoFlush = 1;
        Yii::getLogger()->autoDump = true;
    }

    protected function processLogs($logs)
    {
        $stdout = fopen("php://stdout", "w");

        foreach ($logs as $log) {
            $text = $this->formatLogMessage($log[0], $log[1], $log[2], $log[3]);
            fwrite($stdout, $text);
        }

        fclose($stdout);
    }
}
