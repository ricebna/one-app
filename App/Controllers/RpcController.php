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
        return $this->json([$this->request->get("class"), $this->request->get("method"), $this->request->get("args")]);
    }
}




