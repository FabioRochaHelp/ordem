<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableGroupsUsers extends Migration
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
            'users_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
            ],
         
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('groups_id', 'groups', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('users_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('groups_users');
    }

    public function down()
    {
        $this->forge->dropTable('groups_users');
    }
}
