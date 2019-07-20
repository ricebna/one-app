<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/23
 * Time: 10:40
 */

namespace App\Tests\Rpc;


use App\Model\Goods;
use App\Model\User2;
use App\Model\User;
use One\Database\Mysql\Model;
use One\Swoole\RpcData;

/**
 * 类注释
 */
class Abc
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
        $this->user2->insert(["age" =>$age, "name" => $name]);$this->query();
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
        $res2 = $user->from("test2")->query("select name from user where id =2")->toArray(); //报找不到该表, 没有切库
        $res1 = $user->from("test2")->where("id", "=", 2)->findAll()->toArray();
        var_dump($res1,$res2);

    }

    public function join(){
        $goods = new Goods();
        $res = $goods->from("t_goods_tag t")
            ->column(['t.*'])
            ->join('t_goods_relate_tag grt', 'grt.goods_tag_uuid','t.uuid')
            ->join('t_goods g', 'g.uuid','grt.goods_uuid')
            ->where('t.category', "=", 'navi')
            ->groupBy('t.uuid')
            ->orderBy('sort asc')
            ->findAll()->toArray();
        var_dump($res);
        return $res;

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

