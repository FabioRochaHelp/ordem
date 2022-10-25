<?php

namespace App\Models;

use CodeIgniter\Model;

class GroupsModel extends Model
{
    protected $table            = 'groups';
    
    protected $returnType       = 'App\Entities\Group';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'groupname',
        'description',
        'disposition'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'groupname'        => 'required|max_length[120]|is_unique[groups.groupname,id,{id}]',
        'description'     => 'required|max_length[240]',
    ];
    protected $validationMessages = [
        'groupname' => [
            'required' => 'O campo nome é obrigatório.',
            'max_length' => 'O campo nome não ser maior que 120 caractéres.',
            'is_unique' => 'Esse nome já foi escolhido. Por favor informe outro.',
        ],
        'description' => [
            'required' => 'O campo descrição é obrigatório.',
            'max_length' => 'O campo descrição não ser maior que 240 caractéres.',
        ],
    ];

}
