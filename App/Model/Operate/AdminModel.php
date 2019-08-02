<?php

namespace App\Model\Operate;

use App\Model\BasicAdminModel;

class AdminModel extends BasicAdminModel
{

    CONST TABLE = 't_operate_user';
    protected $_connection = 'd_hnb';

    public function beforeInsert($model, & $data)
    {
        $data['create_time'] = time();
        $data['update_time'] = time();
    }

    public function beforeUpdate($model, & $arg)
    {
        //ddd($arg);
    }
}