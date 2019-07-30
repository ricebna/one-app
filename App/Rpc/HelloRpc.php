<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/23
 * Time: 10:40
 */

namespace App\Rpc;

use App\Model\User;
use One\Swoole\RpcData;

/**
 * 类注释
 */
class HelloRpc extends BasicRpc
{
    /**
     * @var User
     */
    protected $transaction_model;

    /**
     * 测试插入两个不同数据库的表的事务表现
     * @param $age,年龄 b
     * @param $name,姓名 必须
     * @return int 自增ID
     */
    public function insert(int $age, string $name){

        //$user = (new User())->transactionId(uniqid());
        //$user->beginTransaction();
        $user = new User();
        $user->insert(["age" =>$age, "name" => $name]);
        //$this->transaction_model = $user;
        //return new RpcData($this, $age);
    }

    public function list(){
        $user = new User();
        return  $user->limit(10)->findAll()->toArray();
    }

}

