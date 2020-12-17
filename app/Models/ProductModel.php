<?php namespace App\Models;
use CodeIgniter\Model;

class ProductModel extends Model {
	protected $table = 'product';
	protected $allowedFields = ['name', 'url', 'category', 'description', 'num_plans', 'logo', 'status'];
	protected $primaryKey = 'product_id';

	private $db_instance;
	private $plan_builder;

	function __construct() {
		parent::__construct();
		$this->db_instance = db_connect();
		$this->plan_builder = $this->db_instance->table('product_plan');
	}

	function get_product_plans($product_id): array {
		$this->plan_builder->where('product_id', $product_id);
		return $this->plan_builder->get()->getResultArray();
	}

	function add_product_plan($plan_data) {
		$this->plan_builder->insert($plan_data);
	}
}