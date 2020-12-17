<?php namespace App\Controllers;
use App\Models\CommissionModel;
use CodeIgniter\RESTful\ResourceController;

class Commission extends ResourceController {
	private $commission;

	function __construct() {
		$this->commission = new CommissionModel();
	}

	function get_commissions() {
		$current_generation = $this->commission->where('current_gen', 1)->first();
		$commissions = $this->commission->findAll();
		$payload = [
			'current_generation' => $current_generation,
			'commissions' => $commissions
		];
		return $this->respond($payload);
	}

	function set_current_generation($commission_id) {
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
	}

	function edit_commissions () {
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
	}
}