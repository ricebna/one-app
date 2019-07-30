<?php

namespace App\Controllers;

use App\Model\User;
use App\Tests\Rpc\AbcClient;
use One\Http\Controller;
use One\Swoole\RpcServer;
use OneRpcClient\Http\App\Rpc\HelloRpc;

class IndexController extends Controller
{

    public function index()
    {
        $user = new User();
        return $this->json($user->limit(5)->findAll()->toArray());
    }

    public function checkService($consul_service_id = ""){
        if(isset($this->server->consul_service_info[$consul_service_id])){
            return "OK";
        }
        $this->response->code(400);
        return "No service $consul_service_id";
    }

    public function testRpc()
    {
        // 通过http调用
//        $abc = new AbcClient(5);
//        $result = $abc->insert(33, "cx,vnxvn");
//        $result = $abc->commit();
//        return $result;
        $c = new HelloRpc();
        //$res=$c->insert(2,mt_rand(1000,9999));
        $res = $c->list();
        //$c->commit();
        return $this->json($res);
    }


    public function rpcClientHelper($type = 'http')
    {
        $this->response->header('Content-type', 'text/plain;charset=utf-8');
        $px = "OneRpcClient\\". ucwords($type);
        $r = "<?php\n";
        foreach (RpcServer::$class as $class => $fs) {
            $class = new \ReflectionClass($class);
            $funcs = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
            // 增加类注释
            $doc = preg_replace(['/^\/\*\*/', '/\n.*?\*\//'], [""], $class->getDocComment());
            $r .= "\n/*". str_repeat('*', 97) . "*/\n\n";
            $r .= "namespace $px\\{$class->getNamespaceName()} {\n\n";
            $r .= str_repeat(' ', 3) . "/**\n";
            $r .= $doc . "\n";
            $methods = array_keys($fs);
            foreach ($funcs as $func) {
                if (!isset($fs['*']) && !in_array($func->name, $methods)) {
                    continue;
                }
                if(in_array($func->name, ['rollBack', 'commit'])){
                    continue;
                }
                // 增加方法注释
                $doc = preg_replace(['/^\/\*\*/', '/\n.*?\*\//'], [""], $func->getDocComment());
                preg_match('/@return ([a-zA-Z]+).*? */', $doc, $return_match);
                if(isset($return_match[1])){
                    $return = $return_match[1];
                }
                else{
                    $return = $func->getReturnType() ? $func->getReturnType() : 'mixed';
                }
                $r .=  str_repeat('-', 63).$doc. "\n" . str_repeat(' ', 4) . "* @method {$return} {$func->name}(";
                $params = [];
                foreach ($func->getParameters() as $param) {
                    if ($param->getType()) {
                        $params[] = $param->getType() . ' $' . $param->getName();
                    } else {
                        $params[] = '$' . $param->getName();
                    }
                }
                $r .= implode(',', $params) . ")";
                if ($func->isStatic()) {
                    $r .= ' static';
                }
                $r .= "\n\n";
            }
            $name = str_replace($class->getNamespaceName() . '\\', '', $class->getName());
            $r    .= str_repeat(' ', 4) . "*/\n";
            if ($type == 'http') {
                $r .= str_repeat(' ', 4) . "class {$name} extends \\OneRpcClient\\RpcClientHttp { \n";
            } else {
                $r .= str_repeat(' ', 4) . "class {$name} extends \\OneRpcClient\\RpcClientTcp { \n";
            }
            $r .= str_repeat(' ', 8) . "protected \$service_name = '". config('consul.service_name') ."';\n";
            $r .= str_repeat(' ', 8) . "protected \$_remote_class_name = '{$class->getName()}';\n";
            $r .= str_repeat(' ', 4) . "} \n";
            $r .= "} \n";
        }
        return $r;
    }
}




