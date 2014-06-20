<?php
Yii::setPathOfAlias('mock', __DIR__ . '/../tests/mock');

return CMap::mergeArray(
    require(dirname(__FILE__) . '/main.php'),
    array(
        'components' => array(
            'fixture' => array(
                'class' => 'system.test.CDbFixtureManager',
            ),
            'db' => array(
                'connectionString' => 'mysql:host=localhost;dbname=insided_test',
                'emulatePrepare' => true,
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8',
            ),
            'cache' => null,
        ),
        // autoloading model and component classes
        'import' => array(
            'mock.*',
            'application.commands.*',
        ),
    )
);
