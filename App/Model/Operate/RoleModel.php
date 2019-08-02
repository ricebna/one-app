<?php

namespace App\Model\Operate;

use App\Model\BasicModel;
use One\Database\Mysql\Model;

class RoleModel extends BasicModel
{
    CONST TABLE = 't_operate_role';
    protected $_connection = 'd_hnb';

    public function consistentList(){
        $result = $this->findAll()->toArray();
        $list = [];
        foreach ($result as $value){
            $list[] = [
                'id' => $value['id'],
                'name' => $value['desc'],
            ];
        }
        return $list;
    }
}