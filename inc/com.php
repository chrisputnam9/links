<?php

// Constants

// - Directories
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

define('DIR_ROOT', realpath(__DIR__ . DS . '..'));

define('DIR_INC', __DIR__);
define('DIR_COM', DIR_INC . DS . 'com');
define('DIR_LOG', DIR_INC . DS . 'log');

define('DIR_PUB', DIR_ROOT . DS . 'pub');

// Configuration
require_once(DIR_INC . DS . 'config-default.php');
require_once(DIR_INC . DS . 'config.php');

// Run Time Settings
date_default_timezone_set(Config::$timezone);

// Run Time Globals
class Globals {
    static $run_time, $run_time_decimal, $run_stamp;
};
$microtime = explode(' ', microtime());
$decimal = explode('.', $microtime[0]);
Globals::$run_time = (int) $microtime[1];
Globals::$run_time_decimal = (int) $decimal[1];
Globals::$run_stamp = date('Y-m-d_H.i.s', Globals::$run_time) . '.' . Globals::$run_time_decimal;

require_once(DIR_COM . DS . 'functions.php');
require_once(DIR_COM . DS . 'logger.php');
