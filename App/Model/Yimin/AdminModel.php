<?php

namespace App\Model\Yimin;

use App\Model\BasicAdminModel;

class AdminModel extends BasicAdminModel
{

    CONST TABLE = 't_user';
    protected $_connection = 'd_crm2';

    protected $consistent_field_map = [
        self::field_email => 'f_email',
        self::field_username => 'f_enname',
        self::field_name => 'f_cnname',
    ];

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
        $this->_consistentUpdateRoleInfo($data);
        return $res;
    }
    public function consistentUpdate($data, $username){
        $res = parent::consistentUpdate($data, $username);
        $this->_consistentUpdateRoleInfo($data);
        return $res;
    }
    //更新角色关联表信息
    private function _consistentUpdateRoleInfo($data){
        AdminRelateRoleModel::where('f_username', $data[self::field_username])->delete();
        if (!$data['role_id']){
            return false;
        }
        $role_ids = explode(',', $data['role_id']);
        $multi_data = [];
        foreach ($role_ids as $v){
            $multi_data[] = [
                'f_username' => $data[self::field_username],
                'f_roleid' => $v,
                'f_siteid' => 'crm2.hinabian.com',
            ];
        }
        AdminRelateRoleModel::insert($multi_data, true);
    }
}