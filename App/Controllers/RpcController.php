<?php

namespace App\Controllers;

use App\Tests\Rpc\Abc;
use App\Tests\Rpc\AbcClient;
use App\Tests\Rpc\AbcOne;
use App\Tests\Rpc\AbcTcp;
use One\Http\Controller;

class RpcController extends Controller
{

    public function index()
    {
        $class = $this->request->get("class");
        $method = $this->request->get("method");
        $args = $this->request->get("args");
        $rpc = new $class;
        $this->response->header('Content-type', 'application/json');
        try{
            $result = call_user_func([$rpc, $method], $args);
            return json_encode(["errmsg" => "ok", "errcode" => "suc", "data" => $result]);
        }
        catch (\Throwable $exception){
            return json_encode(["errmsg" => $exception->getMessage(), "errcode" => "error", "data" => (object)[]]);
        }
    }
}




