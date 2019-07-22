<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/23
 * Time: 10:40
 */

namespace App\Tests\Rpc;


use App\Model\User2;
use App\Model\User;
use One\Database\Mysql\Model;
use One\Swoole\RpcData;

/**
 * 类注释2
 */
class Abc2
{
    private $i = 0;

    protected $_connection = "test";

    public function __construct($i = 0)
    {
        $this->i = $i;
    }

    /**
     * @var User
     */
    private $user;
    /**
     * @var Goods
     */
    private $goods;

    /**
     * 测试插入两个不同数据库的表的事务表现
     * @param $age,年龄 b
     * @param $name,姓名 必须
     * @return int 自增ID
     */
    public function insert(int $age, string $name){
        $this->user = (new User())->transactionId(uniqid());
        $this->user2 = (new User2())->transactionId(uniqid());
        $this->user->beginTransaction();
        $this->user2->beginTransaction();
        $this->user->insert(["age" =>$age, "name" => $name]);
        $this->user2->insert(["age" =>$age, "name" => $name]);
        return new RpcData($this, $age);
    }

    /**
     * 回滚
     */
    public function rollBack(){
        $this->user->rollBack();
        $this->user2->rollBack();
    }

    /**
     * 提交
     */
    public function commit(){
        $this->user->commit();
        $this->user2->commit();
    }

    public function query(){
        $user = new User();

        return $user->query("select sleep(1)");
    }

    // 加法
    public function add($v)
    {
        $this->i += $v;
        return $this;
    }

    // 减法
    public function sub($v)
    {
        $this->i -= $v;
        return $this;
    }

    // 乘法
    public function mul($v)
    {
        $this->i *= $v;
        return $this;
    }

    // 除法
    public function div($v)
    {
        $this->i /= $v;
        return $this;
    }

    // 获取结果
    public function get()
    {
        return $this->i;
    }

}

