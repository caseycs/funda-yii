<?php
//router for internal php server
if (php_sapi_name() == 'cli-server' && is_file(__DIR__ . '/../public' . $_SERVER["REQUEST_URI"])) {
    return false;
}

$yii = __DIR__ . '/../vendor/yiisoft/yii/framework/yii.php';
$config = __DIR__ . '/config/test.php';

define('YII_DEBUG', true);

//for fast debug output
function d($val, $depth = 3, $die = true)
{
    $depth_old = ini_get('xdebug.var_display_max_depth');
    ini_set('xdebug.var_display_max_depth', $depth);
    var_dump($val);
    if ($die) die;
    ini_set('xdebug.var_display_max_depth', $depth_old);
}

define('YII_TRACE_LEVEL', 3);

require $yii;

$CApplication = Yii::createWebApplication($config);

//prepend YII autoloader with Composer one - hurra, now we can use PSR autoload!
spl_autoload_unregister(array('YiiBase', 'autoload'));
require __DIR__ . '/../vendor/autoload.php';
spl_autoload_register(array('YiiBase', 'autoload'));

$CApplication->run();
