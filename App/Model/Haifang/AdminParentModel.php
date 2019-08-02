<?php

namespace App\Model\Haifang;

use App\Model\BasicModel;
use One\Database\Mysql\Model;

class AdminParentModel extends BasicModel
{
    CONST TABLE = 't_crm_admin_parent';
    protected $_connection = 'd_hf';
}