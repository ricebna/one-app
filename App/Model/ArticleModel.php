<?php

namespace App\Model;

class ArticleModel extends BasicModel
{

    CONST TABLE = 'article';

    public function beforeInsert($model, & $data)
    {
//        $data['create_time'] = time();
//        $data['update_time'] = time();
    }

    public function getList(){
        return $this->findAll()->toArray();
    }
}