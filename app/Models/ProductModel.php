<?php namespace App\Models;
use CodeIgniter\Model;

class ProductModel extends Model {
	protected $table = 'product';
	protected $allowedFields = ['name', 'url', 'registration_link', 'category', 'description', 'num_plans', 'logo', 'status'];
	protected $primaryKey = 'product_id';

}