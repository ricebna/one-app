<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/12/6
 * Time: 15:08
 */

namespace App\Server;

use One\Swoole\Server\TcpServer;

class RpcTcpServer extends TcpServer
{
    use RpcTrait;

    protected $consul_service_id;

    public function onReceive(\swoole_server $server, $fd, $reactor_id, $data)
    {
        $str = $this->callRpc($data);
        $this->server->send($fd, $str);
    }

    public function onStart(\swoole_server $server)
    {
        parent::onStart($server);
        $port = $server->port;
        $check_url = "super-admin-app:".config("protocol.add_listener")[0]["port"];
        $this->consul_service_id = uniqid();
        $service = [
            'ID'                => $this->consul_service_id,
            'Name'              => 'super-admin',
            'Tags'              => [
                'http'
            ],
            'Address'           => '127.0.0.1',
            'Port'              => $port,
            'Meta'              => [
                'version' => '1.0'
            ],
            'EnableTagOverride' => false,
            'Weights'           => [
                'Passing' => 10,
                'Warning' => 1
            ],
            "Check" =>["DeregisterCriticalServiceAfter"=>"90m", "tcp"=>$check_url, "Interval"=>"10s",/*"TTL"=>"15s",*/],
        ];
        $postdata = json_encode($service);
        $opts = array('http' =>
            array(
                'method'  => 'PUT',
                'header'  => 'Content-Type:application/json',
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);
        file_get_contents('http://consul-client:8500/v1/agent/service/register', false, $context);
    }

    public function onShutdown(\swoole_server $server)
    {
        parent::onShutdown($server);var_dump("y",$this->consul_service_id);
        if($this->consul_service_id){
            $opts = array('http' => array('method'  => 'PUT',));
            $context  = stream_context_create($opts);
            file_get_contents('http://consul-client:8500/v1/agent/service/deregister/'. $this->consul_service_id, false, $context);
        }
    }
}