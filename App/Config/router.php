<?php

/**
 * 路由设置
 */

use One\Http\Router;

Router::group([

], function () {
    Router::get('/', \App\Controllers\IndexController::class . '@index');
    Router::get('/rpc', \App\Controllers\IndexController::class . '@rpc');
    Router::get('/checkService', \App\Controllers\IndexController::class . '@checkService');
});

