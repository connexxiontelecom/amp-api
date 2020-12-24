<?php namespace App\Models;
use CodeIgniter\Model;

class BankModel extends Model {
	protected $table = 'bank';
	protected $allowedFields = ['affiliate_id', 'bank_name', 'bank_acc_name', 'bank_acc_number'];
	protected $primaryKey = 'bank_id';
}