<?php namespace App\Controllers;


class Product extends BaseController {

	function all_products() {
		$products = $this->product->findAll();
		return $this->respond($products);
	}

	function get_product($product_id) {
		$product = $this->product->find($product_id);
		if ($product) {
			$plans = $this->product_plan->where('product_id', $product_id)->findAll();
			$payload = [
				'product' => $product,
				'plans' => $plans
			];
			return $this->respond($payload);
		} else {
			return $this->failNotFound('Product was not found');
		}
	}

	private function increment_num_plans($product_id, $num_plans): bool {
		$product_data = [
			'product_id' => $product_id,
			'num_plans' => $num_plans
		];
		try {
			return $this->product->save($product_data);
		} catch (\Exception $ex) {
			return false;
		}
	}

	function add_plan() {
		$this->validation->setRules([
			'product_id' => 'required',
			'plan_name' => 'required',
			'plan_price' => 'required',
			'plan_link' => 'required',
			'plan_commission' => 'required'
		]);
		if ($this->validation->withRequest($this->request)->run()) {
			$product_id = $this->request->getPost('product_id');
			$plan_name = $this->request->getPost('plan_name');
			$plan_price = $this->request->getPost('plan_price');
			$plan_link = $this->request->getPost('plan_link');
			$plan_commission = $this->request->getPost('plan_commission');
			$product = $this->product->find($product_id);
			if ($product) {
				$plan_data = [
					'product_id' => $product_id,
					'plan_name' => $plan_name,
					'plan_price' => $plan_price,
					'plan_link' => $plan_link,
					'plan_commission' => $plan_commission
				];
				try {
					$save = $this->product_plan->save($plan_data);
				} catch (\Exception $ex) {
					return $this->fail($ex->getMessage());
				}
				if ($save) {
					$plans = $this->product_plan->where('product_id', $product_id)->findAll();
					$this->increment_num_plans($product_id, count($plans));
					$payload = [
						'product' => $product,
						'plans' => $plans
					];
					return $this->respond($payload);
				}
			} else {
				return $this->failNotFound('Product not found');
			}
		} else {
			return $this->fail($this->validation->getErrors());
		}
	}

}