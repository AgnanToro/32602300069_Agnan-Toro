<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateBudgetTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nominal' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('budget');
    }

    public function down()
    {
        $this->forge->dropTable('budget');
    }
}
