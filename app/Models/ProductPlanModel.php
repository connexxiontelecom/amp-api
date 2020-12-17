<?php namespace App\Models;
use CodeIgniter\Model;

class ProductPlanModel extends Model {
	protected $table = 'product_plan';
	protected $allowedFields = ['product_id', 'plan_name', 'plan_price', 'plan_link', 'plan_commission'];
	protected $primaryKey = 'product_plan_id';
}