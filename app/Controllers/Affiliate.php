<?php namespace App\Controllers;
use App\Models\AffiliateModel;
use CodeIgniter\RESTful\ResourceController;

class Affiliate extends ResourceController {
	private $affiliate;
	private $validation;

	function __construct() {
		$this->affiliate = new AffiliateModel();
		$this->validation = \Config\Services::validation();
	}

	function all_affiliates() {
		$affiliates = $this->affiliate->findAll();
		return $this->respond($affiliates);
	}

	function update_account() {
		$this->validation->setRules([
			'affiliate_id' => 'required',
			'email' => 'required',
			'firstname' => 'required',
			'lastname' => 'required',
			'ref_code' => 'required',
		]);
		if ($this->validation->withRequest($this->request)->run()) {
			$affiliate_id = $this->request->getPost('affiliate_id');
			$email = $this->request->getPost('email');
			$firstname = $this->request->getPost('firstname');
			$lastname = $this->request->getPost('lastname');
			$ref_code = $this->request->getPost('ref_code');
			$user = $this->affiliate->where('affiliate_id', $affiliate_id)->first();
			if ($user) {
				$affiliate_user = [
					'affiliate_id' => $affiliate_id,
					'email' => $email,
					'firstname' => $firstname,
					'lastname' => $lastname,
					'ref_code' => $ref_code,
					'profile' => 1
				];
				try {
					$save = $this->affiliate->save($affiliate_user);
				} catch (\Exception $ex) {
					return $this->fail($ex->getMessage());
				}
				if ($save) {
					$user = $this->affiliate->where('affiliate_id', $affiliate_id)->first();
					$session = [
						'admin'=> false,
						'affiliate' => true,
					];
					$payload = [
						'user' => $user,
						'session' => $session
					];
					return $this->respondUpdated($payload);
				} else {
					return $this->fail('Affiliate account could not be updated');
				}
			} else {
				return $this->failNotFound('Affiliate account not found');
			}
		} else {
			return $this->fail($this->validation->getErrors());
		}
	}

}