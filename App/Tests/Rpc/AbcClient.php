<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/23
 * Time: 10:44
 */

namespace App\Tests\Rpc;


use App\Rpc\HelloRpc;
use One\Swoole\RpcClientHttp;

/**
 * Class AbcClient
 * @package App\Tests\Rpc
 * @mixin Abc
 */
class AbcClient extends RpcClientHttp
{
    protected $_rpc_server = 'http://127.0.0.1:19102';

    protected $_remote_class_name = HelloRpc::class;

}