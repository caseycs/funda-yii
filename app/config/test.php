<?php
Yii::setPathOfAlias('mock', __DIR__ . '/../tests/mock');

return CMap::mergeArray(
    require(dirname(__FILE__) . '/main.php'),
    array(
        'components' => array(
            'fixture' => array(
                'class' => 'system.test.CDbFixtureManager',
            ),
            /* uncomment the following to provide test database connection
               'db'=>array(
                   'connectionString'=>'DSN for test database',
               ),
               */
        ),
        // autoloading model and component classes
        'import' => array(
            'mock.*',
            'application.commands.*',
        ),
    )
);
