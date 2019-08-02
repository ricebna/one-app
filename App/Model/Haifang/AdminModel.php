<?php

namespace App\Model\Haifang;

use App\Model\BasicAdminModel;
use App\Model\UnionAdminModel;

class AdminModel extends BasicAdminModel
{

    CONST TABLE = 't_crm_admin';
    protected $_connection = 'd_hf';

    public function beforeInsert($model, & $data)
    {
        $data['create_time'] = time();
        $data['update_time'] = time();
    }

    public function beforeUpdate($model, & $arg)
    {
        //ddd($arg);
    }

    public function consistentCreate($data){
        $res = parent::consistentCreate($data);
        $this->_consistentUpdateParentInfo($data, $res);
        return $res;
    }
    public function consistentUpdate($data, $username){
        $res = parent::consistentUpdate($data, $username);
        $this->_consistentUpdateParentInfo($data, $this->oneByUsername($username)['id']);
        return $res;
    }

    private function _consistentUpdateParentInfo($data, $id){
        AdminParentModel::where([
            'admin_id' => $id
        ])->delete();
        if($data['parent_username']){
            $parent = (new UnionAdminModel)->oneByUsername($data['parent_username']);
            $hf_parent = $this->oneByUsername(json_decode($parent['union_sync'], true)[strtolower(str_replace('App\\Model\\', '', __NAMESPACE__))]['username']);
            if(!$hf_parent){
                throw new \Exception("上级用户同步信息缺失");
            }
            AdminParentModel::insert([
                'parent_id' => $hf_parent['id'],
                'admin_id' => $id,
            ]);
        }
    }
}