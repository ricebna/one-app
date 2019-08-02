<?php

namespace App\Model;

use One\Database\Mysql\Model;

class BasicModel extends Model
{
    public function events()
    {
        return [
            'beforeGet'    => function ($model, & $arg) {
                $this->beforeGet($model, $arg);
            },
            'afterGet'     => function (& $result, & $arg = null) {
                $this->afterGet($result, $arg);
            },
            'beforeUpdate'  => function ($model, & $arg) {
                $this->beforeUpdate($model, $arg);
            },
            'afterUpdate'   => function (& $result, & $arg = null) {
                $this->afterUpdate($result, $arg);
            },
            'beforeDelete'  => function ($model, & $arg) {
                $this->beforeDelete($model, $arg);
            },
            'afterDelete'   => function (& $result, & $arg = null) {
                $this->afterDelete($result, $arg);
            },
            'beforeInsert'  => function ($model, &$data) {
                $this->beforeInsert($model, $data);
            },
            'afterInsert'   => function (& $result, & $arg = null) {
                $this->afterInsert($result, $arg);
            },

        ];
    }

    protected function beforeGet($model, & $arg){

    }
    protected function afterGet($model, & $arg){

    }
    protected function beforeUpdate($model, & $arg){

    }
    protected function afterUpdate($model, & $arg){

    }
    protected function beforeDelete($model, & $arg){

    }
    protected function afterDelete($model, & $arg){

    }
    protected function beforeInsert($model, & $data){

    }
    protected function afterInsert($model, & $arg){

    }

    public function createOne($data){
        return $this->insert($data);
    }

    public function updateOne($data, $condition){
        return $this->where($condition)->update($data);
    }

}