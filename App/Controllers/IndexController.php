<?php

namespace App\Controllers;

use One\Http\Controller;
use One\Swoole\RpcServer;

class IndexController extends Controller
{

    public function index()
    {
        $m = new \App\Model\UserModel();
        $m->flushTableInfo();
        $m->where(['name' => 'kkk'])->find();
        $m->where(['name' => 'kkk'])->find();
        $article_model = new \App\Model\ArticleModel();
        $article_model->flushTableInfo();
        $article_model->where(['id', '>', 1])->find();
        $m->flushTableInfo();//
        $m->where(['name' => 'kkk'])->find();
        return 1;
        //return $this->json($m->getList());

        $d = [
            'f_username' => 'chen-----------------',
            'f_roleid' => 'xxxd',
            'f_siteid' => 'crm2.hinabian.com',
        ];
        $m = new \App\Model\Yimin\AdminRelateRoleModel();
        $m->flushTableInfo();
        $m->insert([$d], true);
        //\App\Model\Yimin\AdminRelateRoleModel::insert($d);
        //\App\Model\UserModel::insert(['name' => 'kkk']);
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
        $res=$c->insert(2,mt_rand(1000,9999)."xx-----------------------------kk");
        //$res = $c->list();
        //$c->commit();
        return $this->json($res);
    }


    public function rpcClientHelper($type = 'tcp')
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
                $r .=  str_repeat('-', 78). "\n$doc\n\n" . str_repeat(' ', 4) . "* @method {$return} {$func->name}(";
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
            $r .= str_repeat(' ', 8) . "protected \$remote_class_name = '{$class->getName()}';\n";
            $r .= str_repeat(' ', 4) . "} \n";
            $r .= "} \n";
        }
        return $r;
    }
}




