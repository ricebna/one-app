<?php

/**
 * 路由设置
 */

use One\Http\Router;
Router::group([
    //'cache' => getenv("cache_time", 10)
], function () {
    Router::get('/', \App\Controllers\IndexController::class . '@index');
    Router::get('/index', \App\Controllers\IndexController::class . '@index');
    Router::get('/rpc-client-helper', \App\Controllers\IndexController::class . '@rpcClientHelper');
    Router::get('/test-rpc', \App\Controllers\IndexController::class . '@testRpc');
    Router::get('/checkService', \App\Controllers\IndexController::class . '@checkService');
});

