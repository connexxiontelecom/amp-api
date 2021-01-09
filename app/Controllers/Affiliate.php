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

	function get_downstream_affiliates($affiliate_id) {
		$affiliates = $this->affiliate->where('upstream_affiliate_id', $affiliate_id)->findAll();
		return $this->respond($affiliates);
	}

	function update_account() {
		$this->validation->setRules([
			'affiliate_id' => 'required',
			'email' => 'required',
			'firstname' => 'required',
			'lastname' => 'required',
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
					$user = $this->_get_affiliate($this->request->getPost('affiliate_id'));
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

	function update_info() {
		$this->validation->setRules([
			'affiliate_info_id' => 'required',
			'affiliate_id' => 'required',
			'phone' => 'required',
			'dob' => 'required',
			'gender' => 'required',
			'address' => 'required',
			'country' => 'required'
		]);
		if ($this->validation->withRequest($this->request)->run()) {
			$affiliate_info = $this->affiliate_info->find($this->request->getPost('affiliate_info_id'));
			$affiliate_info_data = [
				'phone' => $this->request->getPost('phone'),
				'dob' => $this->request->getPost('dob'),
				'gender' => $this->request->getPost('gender'),
				'address' => $this->request->getPost('address'),
				'country' => $this->request->getPost('country'),
			];
			if ($affiliate_info) {
				// update if it exists
				$affiliate_info_data['affiliate_info_id'] = $this->request->getPost('affiliate_info_id');
			} else {
				// create a new one if it doesn't
				$affiliate_info_data['affiliate_id'] = $this->request->getPost('affiliate_id');
			}
			try {
				$save = $this->affiliate_info->save($affiliate_info_data);
			} catch (\Exception $ex) {
				return $this->fail($ex->getMessage());
			}
			if ($save) {
				$user = $this->_get_affiliate($this->request->getPost('affiliate_id'));
				if ($user) {
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
					return $this->failNotFound('User account not found');
				}
			} else {
				return $this->fail('User information could not be updated');
			}
		} else {
			return $this->fail($this->validation->getErrors());
		}
	}

	function update_bank() {
		$this->validation->setRules([
			'bank_id' => 'required',
			'affiliate_id' => 'required',
			'bank_name' => 'required',
			'bank_acc_name' => 'required',
			'bank_acc_number' => 'required',
		]);
		if ($this->validation->withRequest($this->request)->run()) {
			$bank = $this->bank->find($this->request->getPost('bank_id'));
			$bank_data = [
				'bank_name' => $this->request->getPost('bank_name'),
				'bank_acc_name' => $this->request->getPost('bank_acc_name'),
				'bank_acc_number' => $this->request->getPost('bank_acc_number'),
			];
			if ($bank) {
				// update if it exists
				$bank_data['bank_id'] = $this->request->getPost('bank_id');
			} else {
				// create a new one if it doesn't
				$bank_data['affiliate_id'] = $this->request->getPost('affiliate_id');
			}
			try {
				$save = $this->bank->save($bank_data);
			} catch (\Exception $ex) {
				return $this->fail($ex->getMessage());
			}
			if ($save) {
				$user = $this->_get_affiliate($this->request->getPost('affiliate_id'));
				if ($user) {
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
					return $this->failNotFound('User account not found');
				}
			} else {
				return $this->fail('User bank information could not be updated');
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

	function add_downstream_affiliate() {
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
							$affiliates = $this->affiliate->where('upstream_affiliate_id', $this->request->getPost('upstream_affiliate_id'))->findAll();
							return $this->respond($affiliates);
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
		$affiliate = $this->_get_affiliate($affiliate_id);
		if ($affiliate) {
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
				$affiliate = $this->_get_affiliate($affiliate_id);
				return $this->respond($affiliate);
			} else {
				return $this->fail('Affiliate account status could not be changed');
			}
		} else {
			return $this->failNotFound('Affiliate was not found');
		}
	}

	function update_affiliate_account() {
		$this->validation->setRules([
			'affiliate_id' => 'required',
			'email' => 'required',
			'firstname' => 'required',
			'lastname' => 'required',
		]);
		if ($this->validation->withRequest($this->request)->run()) {
			$affiliate = $this->affiliate->find($this->request->getPost('affiliate_id'));
			if ($affiliate) {
				if ($affiliate['email'] == $this->request->getPost('email') || !$this->check_email_exists($this->request->getPost('email'))) {
					// if not trying to change email or trying to change email and the email is not found
					$affiliate_account = [
						'affiliate_id' => $this->request->getPost('affiliate_id'),
						'email' => $this->request->getPost('email'),
						'firstname' => $this->request->getPost('firstname'),
						'lastname' => $this->request->getPost('lastname'),
						'upstream_affiliate_id' => $this->request->getPost('upstream_affiliate_id')
					];
					try {
						$save = $this->affiliate->save($affiliate_account);
					} catch (\Exception $ex) {
						return $this->fail($ex->getMessage());
					}
					if ($save) {
						$affiliate = $this->_get_affiliate($this->request->getPost('affiliate_id'));
						if ($affiliate) {
							return $this->respond($affiliate);
						} else {
							return $this->failNotFound('Affiliate account not found');
						}
					} else {
						return $this->fail('Affiliate account could not be updated');
					}
				} else {
					return $this->fail('Account with that email already exists');
				}
			} else {
				return $this->failNotFound('Affiliate account not found');
			}
		} else {
			return $this->fail($this->validation->getErrors());
		}
	}

	function update_affiliate_info() {
		$this->validation->setRules([
			'affiliate_info_id' => 'required',
			'affiliate_id' => 'required',
			'phone' => 'required',
			'dob' => 'required',
			'gender' => 'required',
			'address' => 'required',
			'country' => 'required'
		]);
		if ($this->validation->withRequest($this->request)->run()) {
			$affiliate_info = $this->affiliate_info->find($this->request->getPost('affiliate_info_id'));
			$affiliate_info_data = [
				'phone' => $this->request->getPost('phone'),
				'dob' => $this->request->getPost('dob'),
				'gender' => $this->request->getPost('gender'),
				'address' => $this->request->getPost('address'),
				'country' => $this->request->getPost('country'),
			];
			if ($affiliate_info) {
				// update if it exists
				$affiliate_info_data['affiliate_info_id'] = $this->request->getPost('affiliate_info_id');
			} else {
				// create a new one if it doesn't
				$affiliate_info_data['affiliate_id'] = $this->request->getPost('affiliate_id');
			}
			try {
				$save = $this->affiliate_info->save($affiliate_info_data);
			} catch (\Exception $ex) {
				return $this->fail($ex->getMessage());
			}
			if ($save) {
				$affiliate = $this->_get_affiliate($this->request->getPost('affiliate_id'));
				if ($affiliate) {
					return $this->respond($affiliate);
				} else {
					return $this->failNotFound('Affiliate account not found');
				}
			} else {
				return $this->fail('Affiliate account information could not be updated');
			}
		} else {
			return $this->fail($this->validation->getErrors());
		}
	}

	function update_affiliate_bank() {
		$this->validation->setRules([
			'bank_id' => 'required',
			'affiliate_id' => 'required',
			'bank_name' => 'required',
			'bank_acc_name' => 'required',
			'bank_acc_number' => 'required',
		]);
		if ($this->validation->withRequest($this->request)->run()) {
			$bank = $this->bank->find($this->request->getPost('bank_id'));
			$bank_data = [
				'bank_name' => $this->request->getPost('bank_name'),
				'bank_acc_name' => $this->request->getPost('bank_acc_name'),
				'bank_acc_number' => $this->request->getPost('bank_acc_number'),
			];
			if ($bank) {
				// update if it exists
				$bank_data['bank_id'] = $this->request->getPost('bank_id');
			} else {
				// create a new one if it doesn't
				$bank_data['affiliate_id'] = $this->request->getPost('affiliate_id');
			}
			try {
				$save = $this->bank->save($bank_data);
			} catch (\Exception $ex) {
				return $this->fail($ex->getMessage());
			}
			if ($save) {
				$affiliate = $this->_get_affiliate($this->request->getPost('affiliate_id'));
				if ($affiliate) {
					return $this->respond($affiliate);
				} else {
					return $this->failNotFound('Affiliate account not found');
				}
			} else {
				return $this->fail('Affiliate account information could not be updated');
			}
		} else {
			return $this->fail($this->validation->getErrors());
		}
	}

	private function _get_affiliate($affiliate_id) {
		$affiliate = $this->affiliate->find($affiliate_id);
		if ($affiliate) {
			$upstream_affiliate = $this->affiliate->where('affiliate_id', $affiliate['upstream_affiliate_id'])->first();
			$affiliate_info = $this->affiliate_info->where('affiliate_id', $affiliate['affiliate_id'])->first();
			$bank = $this->bank->where('affiliate_id', $affiliate['affiliate_id'])->first();
			$affiliate['password'] = '';
			$affiliate['upstream_affiliate'] = $upstream_affiliate;
			$affiliate['affiliate_info'] = $affiliate_info;
			$affiliate['bank'] = $bank;
			return $affiliate;
		} else {
			return false;
		}
	}
}