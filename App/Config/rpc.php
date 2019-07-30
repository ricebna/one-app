<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/12/6
 * Time: 15:50
 */

use \One\Swoole\RpcServer;

RpcServer::add(\App\Rpc\AbcRpc::class);
RpcServer::add(\App\Rpc\HelloRpc::class);
RpcServer::add(\App\Tests\Rpc\Abc::class);