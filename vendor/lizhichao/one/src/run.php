<?php
if (!defined('_APP_PATH_')) {
    exit('Please define the project path with _APP_PATH_');
}
define('_START_TIME_', microtime(true));

define('_CLI_', defined('_SHELL_') === false && php_sapi_name() === 'cli' && class_exists('\Swoole\Coroutine'));

if (!defined('_DEBUG_')) {
    define('_DEBUG_', false);
}

define('_ONE_V_', '1.7.9');

require __DIR__ . '/helper.php';
