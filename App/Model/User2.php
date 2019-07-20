<?php

namespace App\Model;

use One\Database\Mysql\Model;

class User2 extends Model
{
    CONST TABLE = 'user';
    protected $_connection = "test2";
}