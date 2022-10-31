<?php

namespace App\Models;

use CodeIgniter\Model;

class GroupsPermissionsModel extends Model
{
    protected $table            = 'groups_permissions';

    protected $returnType       = 'object';
    protected $allowedFields    = ['groups_id', 'permissions_id'];

    /**
     * Método que recupera as permissões do grupo de acesso
     * 
     * @param integer $groups_id
     * @param integer $count_page
     * @return array|null
     */
    public function getPermissionsGroups(int $groups_id, $count_page)
    {
        $atrib = [
            'groups_permissions.id AS main_id',
            'groups.id AS groups_id',
            'permissions.id AS permissions_id',
            'permissions.permission',
        ];

        return $this->select($atrib)
                    ->join('groups', 'groups.id = groups_permissions.groups_id')
                    ->join('permissions', 'permissions.id = groups_permissions.permissions_id')
                    ->where('groups_permissions.groups_id', $groups_id)
                    ->groupBy('permissions.permission')
                    ->paginate($count_page);
    }
    
}
