<?php namespace App\Models;
use CodeIgniter\Model;

class AffiliateModel extends Model {
	protected $table = 'affiliate';
	protected $allowedFields = ['username', 'email', 'password', 'firstname', 'lastname', 'ref_code', 'upstream_affiliate_id', 'status', 'verify_code', 'profile'];
	protected $primaryKey = 'affiliate_id';
}