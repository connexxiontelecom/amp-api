<?php namespace App\Models;
use CodeIgniter\Model;

class AffiliateModel extends Model {
	protected $table = 'affiliate';
	protected $allowedFields = ['username', 'email', 'password', 'firstname', 'lastname', 'ref_code', 'status', 'profile'];
	protected $primaryKey = 'affiliate_id';

	private $db_instance;
	private $info_builder;

	function __construct() {
		parent::__construct();
		$this->db_instance = db_connect();
		$this->info_builder = $this->db_instance->table('affiliate_info');
	}
}