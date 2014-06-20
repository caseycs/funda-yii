<?php
$yiit = __DIR__ . '/../../vendor/yiisoft/yii/framework/yiit.php';
$config = __DIR__ . '/../config/test.php';

require $yiit;
require __DIR__ . '/ReflectionHelper.php';
require __DIR__ . '/WebTestCase.php';

Yii::createWebApplication($config);

//prepend YII autoloader with Composer one - hurra, now we can use PSR autoload!
spl_autoload_unregister(array('YiiBase', 'autoload'));
require __DIR__ . '/../../vendor/autoload.php';
spl_autoload_register(array('YiiBase', 'autoload'));
