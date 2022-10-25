<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableGroupsPermissions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'groups_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
            ],
            'permissions_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
            ],
         
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('groups_id', 'groups', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('permissions_id', 'permissions', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('groups_permissions');
    }

    public function down()
    {
        $this->forge->dropTable('groups_permissions');
    }
}
