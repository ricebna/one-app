<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/3/20
 * Time: 17:37
 */

namespace One\Database\Mysql;

class MorphOne extends RelationMorph
{
    public function get()
    {
        return end($this->remote_type)->find();
    }

    public function merge($key)
    {
        if ($this->list_model === null) {
            $this->model->$key = $this->get();
        } else {
            $list_data = [];
            foreach ($this->remote_type as $type => $remote_model) {
                $list_data[$type] = $remote_model->findAll()->pluck($this->remote_type_id[$type], true);
            }
            foreach ($this->list_model as $val) {
                $type      = $val[$this->self_type];
                $id        = $val[$this->self_id];
                $val->$key = isset($list_data[$type][$id]) ? $list_data[$type][$id] : null;
            }
        }
        unset($this->model, $this->remote_type);
    }


}