<?php

namespace App\Models;

use CodeIgniter\Model;

class GroupsUsersModel extends Model
{
    
    protected $table            = 'groups_users';
    protected $returnType       = 'object';
    protected $allowedFields    = ['groups_id', 'users_id'];

    /**
     * Método que recupera os grupos de acesso do usuário informado
     * Utilizado no Controller Users
     * 
     * @param integer $users_id
     * @param integer $count_page
     * @return array|null
     */
    public function getGroupsUsers(int $user_id, $count_page)
    {
        $atrib = [
            'groups_users.id AS main_id',
            'groups.id AS group_id',
            'groups.groupname',
            'groups.description',
        ];

        return $this->select($atrib)
                    ->join('groups', 'groups.id = groups_users.groups_id')
                    ->join('users', 'users.id = groups_users.users_id')
                    ->where('groups_users.users_id', $user_id)
                    ->groupBy('groups.groupname')
                    ->paginate($count_page);
    }
    
    public function userInGroup(int $group_id, int $user_id){
        return $this->where('groups_id', $group_id)
                    ->where('users_id', $user_id)
                    ->first();
    }

}
