<?php namespace App\Models;
use CodeIgniter\Model;

class AdminLogModel extends Model {
	protected $table = 'admin_log';
	protected $allowedFields = ['admin_id', 'type', 'title', 'description'];
	protected $primaryKey = 'admin_log_id';
}