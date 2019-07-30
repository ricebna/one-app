<?php

define('_APP_PATH_', __DIR__);

define('_APP_PATH_VIEW_', __DIR__ . '/View');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Utility/helper.php';
require __DIR__ . '/../vendor/lizhichao/one/src/run.php';
require __DIR__ . '/config.php';

try {
    \One\Http\Router::loadRouter();
    $req = new \One\Http\Request();
    $res = new \One\Http\Response($req);

    $router = new \One\Http\Router();
    list($req->class, $req->method, $mids, $action, $req->args) = $router->explain($req->method(), $req->uri(), $req, $res);
    $f = $router->getExecAction($mids, $action, $res);
    echo $f();
} catch (\One\Exceptions\HttpException $e) {
    echo \One\Exceptions\Handler::render($e);
} catch (Throwable $e) {
    error_report($e);
    $msg = sprintf("%s in %s:%s ", $e->getMessage(), $e->getFile(), $e->getLine());
    if ($e instanceof \One\Database\Mysql\DbException) {
        $msg = "Db Error. {$e->getMessage()}";
    }
    echo \One\Exceptions\Handler::render(new \One\Exceptions\HttpException($res, $msg, $e->getCode()));
}

