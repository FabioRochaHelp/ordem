<?php

namespace App\Models;

use CodeIgniter\Model;

class GroupsPermissionsModel extends Model
{
    protected $table            = 'groupspermissions';

    protected $returnType       = 'object';
    protected $allowedFields    = ['groups_id', 'permissions_id'];
    
}
