<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/23
 * Time: 10:40
 */

namespace App\Rpc;

use App\Model\Operate\AdminModel;
use One\Swoole\RpcData;

/**
 * 统一后台账号管理
 */
class AdminRpc extends BasicRpc
{

    protected $systems = [
        'haifang' => [
            'name' => '海房CRM',
            'multi_role' => false,
            'role_model' => '\\App\\Model\\Haifang\\RoleModel',
            'admin_model' => '\\App\\Model\\Haifang\\AdminModel',
        ],
        'insurance' => [
            'name' => '保险CRM',
            'multi_role' => false,
            'role_model' => '\\App\\Model\\Insurance\\RoleModel',
            'admin_model' => '\\App\\Model\\Insurance\\AdminModel',
        ],
        'operate' => [
            'name' => '运营系统',
            'multi_role' => false,
            'role_model' => '\\App\\Model\\Operate\\RoleModel',
            'admin_model' => '\\App\\Model\\Operate\\AdminModel',
        ],
        'yimin' => [
            'name' => '移民CRM',
            'multi_role' => true,
            'role_model' => '\\App\\Model\\Yimin\\RoleModel',
            'admin_model' => '\\App\\Model\\Yimin\\AdminModel',
        ],
    ];

    /**
     * 同步创建各系统用户信息
     * @param $data,统表信息
     */
    public function create(array $data){
        $admin_models = [];
        $union_sync = json_decode($data['union_sync'], true);var_dump($union_sync);
        foreach ($union_sync as $k => $v){
            $admin_model_name = $this->systems[$k]['admin_model'];
            $admin_model = new $admin_model_name();
            $admin_models[] = $admin_model;
            $admin_model->beginTransaction();
            try{
                $data['role_id'] = $v['role_id'];
                $admin_model->consistentCreate($data);
            }catch (\Throwable $e){
                foreach ($admin_models as $model){
                    $model->rollBack();
                }var_dump($e->getTraceAsString());
                throw_ee($e, '同步添加失败');
            }
        }
        foreach ($admin_models as $model){
            $model->commit();
        }
    }

    /**
     * 同步更新各系统用户信息
     * @param $data,统表信息
     */
    public function update(array $data){
        $admin = (new AdminModel())->oneByUsername($data[AdminModel::field_username]);
        if(!$admin){
            throw new \Exception('该用户不存在');
        }
        $admin_models = [];
        $union_sync = json_decode($data['union_sync'], true);
        foreach ($union_sync as $k => $v){
            $admin_model_name = $this->systems[$k]['admin_model'];
            $admin_model = new $admin_model_name();
            $admin_models[] = $admin_model;
            $admin_model->beginTransaction();
            try{
                $data['role_id'] = $v['role_id'];
                $admin_model->consistentUpdate($data, $v['username']);
            }catch (\Throwable $e){
                foreach ($admin_models as $model){
                    $model->rollBack();
                }var_dump($e->getTraceAsString());
                throw_ee($e, '同步更新失败');
            }
        }
        foreach ($admin_models as $model){
            $model->commit();
        }
    }

    /**
     * 登录校验
     * @param $sysid,系统标识符(haifang:海房,insurance:保险,operate:运营,yimin:移民)
     * @param $username,用户名
     * @param $pass,密码
     * @return array
     * 成功返回: ["code" => "ok", "data" => ["username" => "chen6"]]
     * 失败返回: ["code" => "错误码", "data" => []]
     * 错误码: ok:成功, pass-error:密码错误, disabled:账号已禁用, user-nx:账号不存在
     */
    public function verify(string $sysid, string $username, string $pass){
        $admin = (new AdminModel())->oneByUsername($username);
        $ret = ['code' => 'ok', 'data' => []];
        if(!$admin){
            $ret['code'] = 'user-nx';
            return $ret;
        }
        if($admin['is_disabled']){
            $ret['code'] = 'disabled';
            return $ret;
        }
        if(!password_verify($pass, $admin['passwd'])){
            $ret['code'] = 'pass-error';
            return $ret;
        }
        $union_sync = json_decode($admin['union_sync'], true);
        //$admin_model_name = $this->systems[$sysid]['admin_model'];
        //$admin_model = new $admin_model_name();
        //$sysadmin = $admin_model->oneByUsername($union_sync[$sysid]['role_id']['username'])->toArray();
        if(!$union_sync[$sysid]['role_id']){
            //$ret['code'] = 'disabled';
            //return $ret;
        }
        $ret['data']['username'] = $union_sync[$sysid]['username'];
        return $ret;
    }

    /**
     * 获得角色列表组, 包括所有系统
     * @return array
     */
    public function roleGroup(){
        $group = [];
        foreach ($this->systems as $k => $system){
            $role_model_name = $this->systems[$k]['role_model'];
            $role_model = new $role_model_name();
            $list = $role_model->consistentList();
            $group[$k] = [
                'name' => $system['name'],
                'multi_role' => $system['multi_role'],
                'role_list' => $list
            ];
        }
        return  $group;
    }

}

