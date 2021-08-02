<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Verification extends Migration
{
	public function up()
	{
		$this->db->disableForeignKeyChecks();

		$this->forge->addField(
			[
				'verification_id' => [
					'type' => 'INT',
					'constraint' => 11,
					'auto_increment' => true,
				],
				'affiliate_id' => [
					'type' => 'INT',
				],
				'verification_type' => [
					'type' => 'TEXT',
				],
				'verification_code' => [
					'type' => 'TEXT',
				],
				'verification_status' => [
					'type' => 'TEXT',
				],
				'created_at datetime default current_timestamp',
			]
		);
		$this->forge->addKey('verification_id', true);
		$this->forge->createTable('verification');
	}

	public function down()
	{
		//
	}
}
