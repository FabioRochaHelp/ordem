<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTablePermissions extends Migration
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
            'permission' => [
                'type'       => 'VARCHAR',
                'constraint' => '128',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('permission');

        $this->forge->createTable('permissions');
    }

    public function down()
    {
        $this->forge->dropTable('permissions');
    }
}
