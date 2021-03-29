<?php namespace App\Controllers;

class Commission extends BaseController {

  function __construct() {
    $this->decode_token();
  }

	function get_commissions() {
		if ($this->is_admin_session()) {
			$current_generation = $this->commission->where('current_gen', 1)->first();
			$commissions = $this->commission->findAll();
			$payload = [
				'current_generation' => $current_generation,
				'commissions' => $commissions
			];
			return $this->respond($payload);
		} else {
			return $this->failUnauthorized();
		}
	}

	function set_current_generation($commission_id) {
		if ($this->is_admin_session()) {
			if ($this->commission->find($commission_id)) {
				$current_generation = $this->commission->where('current_gen', 1)->first();
				$commission_data = [
					'commission_id' => $current_generation['commission_id'],
					'current_gen' => 0
				];
				try {
					$this->commission->save($commission_data);
					$commission_data['commission_id'] = $commission_id;
					$commission_data['current_gen'] = 1;
					$this->commission->save($commission_data);
					$current_generation = $this->commission->where('current_gen', 1)->first();
					$commissions = $this->commission->findAll();
					$payload = [
						'current_generation' => $current_generation,
						'commissions' => $commissions
					];
					return $this->respond($payload);
				} catch (\Exception $ex) {
					return false;
				}
			} else {
				return $this->failNotFound('Commission not found');
			}
		} else {
			return $this->failUnauthorized();
		}
	}

	function edit_commissions () {
		if ($this->is_admin_session()) {
			$commission_id = $this->request->getPost('commission_id');
			if ($this->commission->find($commission_id)) {
				$commission_data = [
					'commission_id' => $commission_id,
					'gen_1' => $this->request->getPost('gen_1'),
					'gen_2' => $this->request->getPost('gen_2')
				];
				$num_gens = $this->request->getPost('num_gens');
				switch ($num_gens) {
					case 2:
						$commission_data['gen_3'] = $this->request->getPost('gen_3');
						break;
					case 3:
						$commission_data['gen_3'] = $this->request->getPost('gen_3');
						$commission_data['gen_4'] = $this->request->getPost('gen_4');
						break;
					case 4:
						$commission_data['gen_3'] = $this->request->getPost('gen_3');
						$commission_data['gen_4'] = $this->request->getPost('gen_4');
						$commission_data['gen_5'] = $this->request->getPost('gen_5');
						break;
				}
				try {
					$this->commission->save($commission_data);
					$current_generation = $this->commission->where('current_gen', 1)->first();
					$commissions = $this->commission->findAll();
					$payload = [
						'current_generation' => $current_generation,
						'commissions' => $commissions
					];
					return $this->respond($payload);
				} catch (\Exception $ex) {
					return false;
				}
			} else {
				return $this->failNotFound('Commission not found');
			}
		} else {
			return $this->failUnauthorized();
		}
	}

	function get_plan_commissions() {
		if ($this->is_admin_session() || $this->is_affiliate_session()) {
			$current_generation = $this->commission->where('current_gen', 1)->first();
			$product_plans = $this->product_plan->findAll();
			$plan_commissions = [];
			foreach ($product_plans as $product_plan) {
				$product = $this->product->where('product_id', $product_plan['product_id'])->first();
				$product_plan['product_name'] = $product['name'];
				$product_plan['gen_1'] = ((int)$current_generation['gen_1'] / 100) * (int)$product_plan['plan_commission'];
				$product_plan['gen_2'] = ((int)$current_generation['gen_2'] / 100) * (int)$product_plan['plan_commission'];
				$product_plan['gen_3'] = ((int)$current_generation['gen_3'] / 100) * (int)$product_plan['plan_commission'];
				$product_plan['gen_4'] = ((int)$current_generation['gen_4'] / 100) * (int)$product_plan['plan_commission'];
				$product_plan['gen_5'] = ((int)$current_generation['gen_5'] / 100) * (int)$product_plan['plan_commission'];
				array_push($plan_commissions, $product_plan);
			}
			$payload = [
				'current_generation' => $current_generation,
				'plan_commissions' => $plan_commissions
			];
			return $this->respond($payload);
		} else {
			return $this->failUnauthorized();
		}
	}
}