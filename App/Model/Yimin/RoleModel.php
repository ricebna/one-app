<?php

namespace App\Model\Yimin;

use App\Model\BasicModel;
use One\Database\Mysql\Model;

class RoleModel extends BasicModel
{
    CONST TABLE = 't_acl_role';
    protected $_connection = 'd_crm2';

    public function consistentList(){
        $result = $this->findAll()->toArray();
        $list = [];
        foreach ($result as $value){
            $list[] = [
                'id' => $value['f_roleid'],
                'name' => $value['f_name'],
            ];
        }
        return $list;
    }
}