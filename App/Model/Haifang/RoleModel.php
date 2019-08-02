<?php

namespace App\Model\Haifang;

use App\Model\BasicModel;
use One\Database\Mysql\Model;

class RoleModel extends BasicModel
{
    CONST TABLE = 't_crm_admin_role';
    protected $_connection = 'd_hf';

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