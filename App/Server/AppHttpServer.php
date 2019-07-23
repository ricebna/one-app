<?php
/**
 * Created by PhpStorm.
 * User: tanszhe
 * Date: 2018/8/24
 * Time: 下午4:26
 */

namespace App\Server;


use App\GlobalData\Client;
use One\Http\Router;
use One\Swoole\Server\HttpServer;

class AppHttpServer extends HttpServer
{

    public $consul_service_id;

    /**
     * @var Client
     */
    public $client;

    public function __construct(\swoole_server $server, array $conf)
    {
        parent::__construct($server, $conf);
        //$this->client = new Client();
        $this->consul_service_id = uniqid();
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
        if($this->consul_service_id){
            $opts = array('http' => array('method'  => 'PUT',));
            $context  = stream_context_create($opts);
            file_get_contents('http://consul-client:8500/v1/agent/service/deregister/'. $this->consul_service_id, false, $context);
        }
    }

    public function onWorkerStart(\swoole_server $server, $worker_id)
    {
        if($worker_id == 1){
            $this->registerService();
        }
        parent::onWorkerStart($server, $worker_id);
        Router::clearCache();
        require _APP_PATH_ ."/config.php";
    }

    protected function registerService(){
        $address = "127.0.0.1";
        $port = 19103;
        $check_url = "http://host.docker.internal:19101/checkService?consul_service_id=$this->consul_service_id";
        $service = [
            'ID'                => $this->consul_service_id,
            'Name'              => 'super-admin',
            'Tags'              => [
                'http'
            ],
            'Address'           => $address,
            'Port'              => $port,
            'Meta'              => [
                'version' => '1.0'
            ],
            'EnableTagOverride' => false,
            'Weights'           => [
                'Passing' => 10,
                'Warning' => 1
            ],
            "Check" =>["DeregisterCriticalServiceAfter"=>"90m", "HTTP"=>$check_url, "Interval"=>"10s",/*"TTL"=>"15s",*/],
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
}