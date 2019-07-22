<?php
/**
 * Created by PhpStorm.
 * User: tanszhe
 * Date: 2018/8/24
 * Time: 下午5:23
 * http,websocket,tcp 服务器配置
 */

return [
    'server'       => [
        'server_type'   => \One\Swoole\OneServer::SWOOLE_SERVER, // 主服务器类型
        'port'          => 19101,
        'action'        => \App\Server\RpcTcpServer::class,
        'mode'          => SWOOLE_PROCESS,
        'sock_type'     => SWOOLE_SOCK_TCP,
        'ip'            => '0.0.0.0',
        'pack_protocol' => \One\Protocol\Frame::class, // tcp 打包 解包协议
        'set'           => [ // set 相关配置
            'dispatch_mode'=> 2, // rpc 需要链式调用 这里必须 为 2
            'worker_num'          => 10,
            'reactor_num'         => 1,
            'open_length_check'   => 1,
            'package_length_func' => '\One\Protocol\Frame::length',
            'package_body_offset' => \One\Protocol\Frame::HEAD_LEN,
        ]
    ],
    'add_listener' => [
        [
            'port' => 19102,
            'action' => \App\Server\AppHttpPort::class,
            'type' => SWOOLE_SOCK_TCP,
            'ip' => '0.0.0.0',
            'set' => [
                'open_http_protocol' => true,
                'open_websocket_protocol' => false
            ]
        ],
        [
            'port'   => 19103,
            'action' => \App\Server\RpcHttpPort::class,
            'type'   => SWOOLE_SOCK_TCP,
            'ip'     => '0.0.0.0',
            'set'    => [
                'open_http_protocol'      => true,
                'open_websocket_protocol' => false
            ]
        ],
    ],

];
