<?php

namespace App\Model;

class UserModel extends BasicModel
{

    CONST TABLE = 'user';

    public function beforeInsert($model, & $data)
    {
//        $data['create_time'] = time();
//        $data['update_time'] = time();
    }

    public function getList(){
        return $this->findAll()->toArray();
    }
}