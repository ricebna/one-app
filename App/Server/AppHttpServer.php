<?php
/**
 * Created by PhpStorm.
 * User: tanszhe
 * Date: 2018/8/24
 * Time: 下午4:26
 */

namespace App\Server;


use App\GlobalData\Client;
use GuzzleHttp\Promise\Coroutine;
use One\Http\Router;
use One\Swoole\Server\HttpServer;
use WG\WaitGroup;

class AppHttpServer extends HttpServer
{

    public $consul_service_info = [];

    /**
     * @var Client
     */
    public $client;

    public function __construct(\swoole_server $server, array $conf)
    {
        parent::__construct($server, $conf);
        //$this->client = new Client();
        \Swoole\Runtime::enableCoroutine(false, SWOOLE_HOOK_BLOCKING_FUNCTION);
        $http_port = config('protocol.server.port');
        $ports = config('protocol.add_listener');
        $rpc_http_port = $ports[0]['port'];
        $rpc_tcp_port = $ports[1]['port'];
        $lan_ip = getenv('host_ip');
        $service_ids = [uniqid(), uniqid(), uniqid(), uniqid()];
        $check = "http://$lan_ip:$http_port/checkService/";
        $this->consul_service_info = [
            $service_ids[0] => ['service_id' => $service_ids[0], 'check' => $check . $service_ids[0], 'ip' => $lan_ip, 'port' => $rpc_http_port, 'tag' => 'rpc_http', ],
            $service_ids[1] => ['service_id' => $service_ids[1], 'check' => $check . $service_ids[1], 'ip' => $lan_ip, 'port' => $rpc_tcp_port, 'tag' => 'rpc_tcp', ],
            $service_ids[2] => ['service_id' => $service_ids[2], 'check' => $check . $service_ids[2], 'ip' => $lan_ip, 'port' => $rpc_http_port, 'tag' => 'rpc_container_http',],
            $service_ids[3] => ['service_id' => $service_ids[3], 'check' => $check . $service_ids[3], 'ip' => $lan_ip, 'port' => $rpc_tcp_port, 'tag' => 'rpc_container_tcp',]
        ];
    }

    public function onRequest(\swoole_http_request $request, \swoole_http_response $response)
    {
        $this->httpRouter($request,$response);
    }

//    public function onStart(\swoole_server $server)
//    {
//
//    }

    public function onShutdown(\swoole_server $server)
    {
        parent::onShutdown($server);
        if($this->consul_service_info){
            $opts = array('http' => array('method'  => 'PUT',));
            $context  = stream_context_create($opts);
            foreach ($this->consul_service_info as $info){
                file_get_contents('http://consul.client:8520/v1/agent/service/deregister/'. $info['service_id'], false, $context);
            }
        }
    }

    public function onWorkerStart(\swoole_server $server, $worker_id)
    {
        if($worker_id == 1){
            $this->registerService();
            \Swoole\Runtime::enableCoroutine(true, SWOOLE_HOOK_BLOCKING_FUNCTION);
        }
        parent::onWorkerStart($server, $worker_id);
        Router::clearCache();
        require _APP_PATH_ ."/config.php";
    }

    protected function registerService(){
        foreach ($this->consul_service_info as $k => $info){
            $service = [
                'ID'                => $info['service_id'],
                'Name'              => config('consul.service_name'),
                'Tags'              => [
                    '统一后台账号管理系统', $info['tag']
                ],
                'Address'           => $info['ip'],
                'Port'              => $info['port'],
                'Meta'              => [
                    'version' => '1.0'
                ],
                'EnableTagOverride' => false,
                'Weights'           => [
                    'Passing' => 10,
                    'Warning' => 1
                ],
                "Check" =>["DeregisterCriticalServiceAfter"=>"90m", "HTTP"=>$info['check'], "Interval"=>"10s",/*"TTL"=>"15s",*/],
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
            file_get_contents('http://consul.client:8520/v1/agent/service/register', false, $context);
        }
    }
}