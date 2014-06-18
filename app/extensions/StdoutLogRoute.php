<?php
class StdoutLogRoute extends CFileLogRoute
{
    public function init()
    {
        parent::init();
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
