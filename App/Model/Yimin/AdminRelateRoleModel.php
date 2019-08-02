<?php

namespace App\Model\Yimin;

use App\Model\BasicModel;
use One\Database\Mysql\Model;

class AdminRelateRoleModel extends BasicModel
{
    CONST TABLE = 't_acl_role_user';
    protected $_connection = 'd_crm2';
}