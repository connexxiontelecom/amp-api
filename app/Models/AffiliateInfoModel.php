<?php namespace App\Models;
use CodeIgniter\Model;

class AffiliateInfoModel extends Model {
	protected $table = 'affiliate_info';
	protected $allowedFields = ['affiliate_id', 'phone', 'dob', 'gender', 'address', 'country'];
	protected $primaryKey = 'affiliate_info_id';
}