<?php namespace App\Controllers;

class Affiliate extends BaseController {

	function all_affiliates() {
		$affiliates = $this->affiliate->findAll();
		$payload = [];
		foreach ($affiliates as $affiliate) {
			$upstream_affiliate = $this->affiliate->find($affiliate['upstream_affiliate_id']);
			$affiliate['upstream_affiliate'] = $upstream_affiliate['username'];
			array_push($payload, $affiliate);
		}
		return $this->respond($payload);
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
			$user = $this->affiliate->where('affiliate_id', $this->request->getPost('affiliate_id'))->first();
			if ($user) {
				$affiliate_user = [
					'affiliate_id' => $this->request->getPost('affiliate_id'),
					'email' => $this->request->getPost('email'),
					'firstname' => $this->request->getPost('firstname'),
					'lastname' => $this->request->getPost('lastname'),
				];
				try {
					$save = $this->affiliate->save($affiliate_user);
				} catch (\Exception $ex) {
					return $this->fail($ex->getMessage());
				}
				if ($save) {
					$user = $this->affiliate->where('affiliate_id', $this->request->getPost('affiliate_id'))->first();
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

	function add_affiliate() {
		$this->validation->setRules([
			'firstname' => 'required',
			'lastname' => 'required',
			'username' => 'required',
			'email' => 'required|valid_email',
			'password' => 'required|min_length[5]'
		]);
		if ($this->validation->withRequest($this->request)->run()) {
			if (!$this->check_username_exists($this->request->getPost('username'))) {
				if (!$this->check_email_exists($this->request->getPost('email'))) {
					$affiliate_data = [
						'firstname' => $this->request->getPost('firstname'),
						'lastname' => $this->request->getPost('lastname'),
						'username' => $this->request->getPost('username'),
						'email' => $this->request->getPost('email'),
						'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
						'upstream_affiliate_id' => $this->request->getPost('upstream_affiliate_id'),
						'ref_code' => $this->generate_ref_code()
					];
					try {
						$save = $this->affiliate->save($affiliate_data);
						if ($save) {
							$affiliates = $this->affiliate->findAll();
							$payload = [];
							foreach ($affiliates as $affiliate) {
								$upstream_affiliate = $this->affiliate->find($affiliate['upstream_affiliate_id']);
								$affiliate['upstream_affiliate'] = $upstream_affiliate['username'];
								array_push($payload, $affiliate);
							}
							return $this->respond($payload);
						} else {
							return $this->fail('Affiliate account could not be created');
						}
					} catch (\Exception $ex) {
						return $this->fail($ex->getMessage());
					}
				} else {
					return $this->fail('Account with that email already exists');
				}
			} else {
				return $this->fail('Account with that username already exists');
			}
		} else {
			return $this->fail($this->validation->getErrors());
		}
	}

	function get_affiliate($affiliate_id) {
		$affiliate = $this->affiliate->find($affiliate_id);
		if ($affiliate) {
			$upstream_affiliate = $this->affiliate->where('affiliate_id', $affiliate['upstream_affiliate_id'])->find();
			$affiliate['password'] = '';
			$affiliate['upstream_affiliate'] = $upstream_affiliate;
			return $this->respond($affiliate);
		} else {
			return $this->failNotFound('Affiliate was not found');
		}
	}

	function toggle_affiliate_status($affiliate_id) {
		$affiliate = $this->affiliate->find($affiliate_id);
		if ($affiliate) {
			$affiliate['status'] == 1 ? $affiliate['status'] = 0 : $affiliate['status'] = 1;
			try {
				$save = $this->affiliate->save($affiliate);
			} catch (\Exception $ex) {
				return $this->fail($ex->getMessage());
			}
			if ($save) {
				$affiliate = $this->affiliate->find($affiliate_id);
				$upstream_affiliate = $this->affiliate->where('affiliate_id', $affiliate['upstream_affiliate_id'])->find();
				$affiliate['password'] = '';
				$affiliate['upstream_affiliate'] = $upstream_affiliate;
				return $this->respond($affiliate);
			} else {
				return $this->fail('Affiliate account status could not be changed');
			}
		} else {
			return $this->failNotFound('Affiliate was not found');
		}
	}
}