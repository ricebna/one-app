<?php

namespace App\Model;


class BasicAdminModel extends BasicModel
{
    const field_email = 'email';
    const field_username = 'name_en';
    const field_name = 'name_cn';
    const field_role_id = 'role_id';

    protected $consistent_field_map = [
        self::field_email => 'email',
        self::field_username => 'name_en',
        self::field_name => 'name_cn',
        self::field_role_id => 'role_id',
    ];

    /**
     * 创建账号
     * @param $data
     * @return int
     */
    public function consistentCreate($data){
        return $this->insert($this->mapFields($data));
    }

    /**
     * 更新账号
     * @param $data
     * @param $username
     * @return int
     */
    public function consistentUpdate($data, $username){
        return $this->where([$this->consistent_field_map[self::field_username] => $username])->update($this->mapFields($data));
    }

    /**
     * 取得各系统的字段映射数据
     * @param $data
     * @return array
     */
    protected function mapFields($data){
        $arr = [];
        foreach ($data as $k => $v){
            if(isset($this->consistent_field_map[$k])){
                $arr[$this->consistent_field_map[$k]] = $v;
            }
            else{
                $arr[$k] = $v;
            }
        }
        return $arr;
    }

    public function oneByUsername($username){
        return $this->where($this->consistent_field_map[self::field_username], $username)->find();
    }
}