<?php
$yiit = __DIR__ . '/../../vendor/yiisoft/yii/framework/yiit.php';
$config = __DIR__ . '/../config/test.php';

require $yiit;
require __DIR__ . '/ReflectionHelper.php';
require __DIR__ . '/WebTestCase.php';

Yii::createWebApplication($config);
