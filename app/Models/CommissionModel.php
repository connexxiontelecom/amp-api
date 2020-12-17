<?php namespace App\Models;
use CodeIgniter\Model;

class CommissionModel extends Model {
	protected $table = 'commission';
	protected $allowedFields = ['current_gen', 'gen_1', 'gen_2', 'gen_3', 'gen_4', 'gen_5'];
	protected $primaryKey = 'commission_id';
}