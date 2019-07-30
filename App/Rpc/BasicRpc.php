<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/23
 * Time: 10:40
 */

namespace App\Rpc;

class BasicRpc
{
    protected $transaction_model;

    /**
     * 回滚
     */
    public function rollBack(){
        $this->transaction_model->rollBack();
    }

    /**
     * 提交
     */
    public function commit(){
        $this->transaction_model->commit();
    }
}

