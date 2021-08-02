<?php namespace App\Controllers;

class Affiliate extends BaseController {

	function all_affiliates() {
		if ($this->is_admin_session()) {
			$affiliates = $this->affiliate->findAll();
			$payload = [];
			foreach ($affiliates as $affiliate) {
				$affiliate['password'] = '';
				$upstream_affiliate = $this->affiliate->find($affiliate['upstream_affiliate_id']);
				if ($upstream_affiliate)
          $affiliate['upstream_affiliate'] = $upstream_affiliate['username'];
				array_push($payload, $affiliate);
			}
			return $this->respond($payload);
		}
		return $this->failUnauthorized();
	}

	function get_downstream_affiliates($affiliate_id) {
		if ($this->is_affiliate_session() || $this->is_admin_session()) {
			$affiliates = $this->affiliate->where('upstream_affiliate_id', $affiliate_id)->findAll();
			return $this->respond($affiliates);
		}
		return $this->failUnauthorized();
	}

	function update_account() {
		if ($this->is_affiliate_session()) {
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
		} else {
			return $this->failUnauthorized();
		}
	}

	function update_info() {
		if ($this->is_affiliate_session()) {
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
		} else {
			return $this->failUnauthorized();
		}
	}

	function update_bank() {
		if ($this->is_affiliate_session()) {
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
		} else {
			return $this->failUnauthorized();
		}
	}

  function update_image() {
    if ($this->is_affiliate_session()) {
      $this->validation->setRules([
        'affiliate_id' => 'required',
        'profile_pic' => [
          'uploaded[profile_pic]',
          'mime_in[profile_pic,image/jpg,image/jpeg,image/gif,image/png]',
          'max_size[profile_pic,2097152]'
        ]
      ]);
      if ($this->validation->withRequest($this->request)->run()) {
        $affiliate = $this->affiliate->find($this->request->getPost('affiliate_id'));
        if ($affiliate) {
          if ($affiliate['profile_pic']) {
            unlink(ROOTPATH.'public/uploads/affiliates/'.$affiliate['profile_pic']);
          }
          $profile_pic = $this->request->getFile('profile_pic');
          $profile_pic->move(ROOTPATH.'public/uploads/affiliates');
          $affiliate = [
            'affiliate_id' => $this->request->getPost('affiliate_id'),
            'profile_pic' => $profile_pic->getClientName(),
          ];
          try {
            $save = $this->affiliate->save($affiliate);
          } catch (\Exception $ex) {
            return $this->fail($ex->getMessage());
          }
          if ($save) {
            $affiliate = $this->_get_affiliate($this->request->getPost('affiliate_id'));
            $session = [
              'admin'=> false,
              'affiliate' => true,
            ];
            $payload = [
              'user' => $affiliate,
              'session' => $session
            ];
            return $this->respond($payload);
          } else {
            return $this->fail('Affiliate profile picture could not be updated');
          }
        } else {
          return $this->failNotFound('Affiliate was not found');
        }
      } else {
        return $this->fail('Form validation failed. Please confirm profile picture was uploaded correctly');
      }
    } else {
      return $this->failUnauthorized();
    }
  }

  function remove_image() {
    if ($this->is_affiliate_session()) {
      $this->validation->setRules([
        'affiliate_id' => 'required',
      ]);
      if ($this->validation->withRequest($this->request)->run()) {
        $affiliate = $this->affiliate->find($this->request->getPost('affiliate_id'));
        if ($affiliate) {
          unlink(ROOTPATH.'public/uploads/affiliates/'.$affiliate['profile_pic']);
          $affiliate = [
            '$affiliate_id' => $this->request->getPost('$affiliate_id'),
            'profile_pic' => '',
          ];
          try {
            $save = $this->affiliate->save($affiliate);
          } catch (\Exception $ex) {
            return $this->fail($ex->getMessage());
          }
          if ($save) {
            $affiliate = $this->_get_affiliate($this->request->getPost('affiliate_id'));
            $session = [
              'admin'=> false,
              'affiliate' => true,
            ];
            $payload = [
              'user' => $affiliate,
              'session' => $session
            ];
            return $this->respond($payload);
          } else {
            return $this->fail('Affiliate profile picture could not be updated');
          }
        } else {
          return $this->failNotFound('Affiliate was not found');
        }
      } else {
        return $this->fail($this->validation->getErrors());
      }
    } else {
      return $this->failUnauthorized();
    }
  }


  function add_affiliate() {
		if ($this->is_admin_session()) {
			$this->validation->setRules([
				'firstname' => 'required',
				'lastname' => 'required',
				'username' => 'required',
				'verify_code' => 'required',
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
							'verify_code' => $this->request->getPost('verify_code'),
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
								$data['name'] =  $affiliate_data['firstname'].' '.$affiliate_data['lastname'];
								$data['creator'] = 'administrator';
								$data['data'] = $affiliate_data['username'];
								$data['password'] = $this->request->getPost('password');
								$data['verify_link'] = getenv('FRONT_END').'verify-'.$this->request->getPost('verify_code');
								$email_data['subject'] = 'Verify your email address on AMP';
								$email_data['message'] = view('emails/verify-email-alt', $data);
								$email_data['from_email'] = 'support@connexxiontelecom.com';
								$email_data['from_name'] = 'AMP | Powered by Connexxion Telecom';
								$email_data['to_email'] = $affiliate_data['email'];
                $this->send_mail($email_data);
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
		} else {
			return $this->failUnauthorized();
		}
	}

	function add_downstream_affiliate() {
		if ($this->is_affiliate_session()) {
			$this->validation->setRules([
				'firstname' => 'required',
				'lastname' => 'required',
				'username' => 'required',
				'verify_code' => 'required',
				'upstream_affiliate_id' => 'required',
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
							'verify_code' => $this->request->getPost('verify_code'),
							'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
							'upstream_affiliate_id' => $this->request->getPost('upstream_affiliate_id'),
							'ref_code' => $this->generate_ref_code()
						];
						try {
							$save = $this->affiliate->save($affiliate_data);
							if ($save) {
								$data['name'] =  $affiliate_data['firstname'].' '.$affiliate_data['lastname'];
                $data['creator'] = 'affiliate';
								$data['data'] = $affiliate_data['username'];
								$data['password'] = $this->request->getPost('password');
								$data['verify_link'] = getenv('FRONT_END').'verify-'.$this->request->getPost('verify_code');
                $email_data['subject'] = 'Verify your email address on AMP';
								$email_data['message'] = view('emails/verify-email-alt', $data);
								$email_data['from_email'] = 'support@connexxiontelecom.com';
								$email_data['from_name'] = 'AMP | Powered by Connexxion Telecom';
								$email_data['to_email'] = $affiliate_data['email'];
                $this->send_mail($email_data);
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
		} else {
			return $this->failUnauthorized();
		}
	}

	function get_affiliate($affiliate_id) {
		if ($this->is_affiliate_session() || $this->is_admin_session()) {
			$affiliate = $this->_get_affiliate($affiliate_id);
			if ($affiliate) {
				$affiliate['password'] = '';
				return $this->respond($affiliate);
			} else {
				return $this->failNotFound('Affiliate was not found');
			}
		} else {
			return $this->failUnauthorized();
		}
	}

	function toggle_affiliate_status($affiliate_id) {
		if ($this->is_admin_session()) {
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
		} else {
			return $this->failUnauthorized();
		}
	}

	function update_affiliate_account() {
		if ($this->is_admin_session()) {
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
		} else {
			return $this->failUnauthorized();
		}
	}

	function update_affiliate_info() {
		if ($this->is_admin_session()) {
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
		} else {
			return $this->failUnauthorized();
		}
	}

	function update_affiliate_bank() {
		if ($this->is_admin_session()) {
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
		} else {
			return $this->failUnauthorized();
		}
	}

	function change_password() {
		if ($this->is_affiliate_session()) {
			$this->validation->setRules([
				'affiliate_id' => 'required',
				'password' => 'required',
				'new_password' => 'required|min_length[5]',
				'confirm_password' => 'required|min_length[5]|matches[new_password]'
			]);
			if ($this->validation->withRequest($this->request)->run()) {
				$affiliate = $this->affiliate->where('affiliate_id', $this->request->getPost('affiliate_id'))->first();
				if ($affiliate && password_verify($this->request->getPost('password'), $affiliate['password'])) {
					$data = [
						'affiliate_id' => $this->request->getPost('affiliate_id'),
						'password' => password_hash($this->request->getPost('new_password'), PASSWORD_BCRYPT),
					];
					try {
						$save = $this->affiliate->save($data);
					} catch (\Exception $ex) {
						return $this->fail($ex->getMessage());
					}
					if ($save) {
						return $this->respondUpdated('Affiliate password was updated. Please log back in with your new credentials');
					} else {
						return $this->fail('Affiliate password could not be updated');
					}
				} else {
					return $this->fail('Invalid password');
				}
			} else {
				return $this->fail($this->validation->getErrors());
			}
		} else {
			return $this->failUnauthorized();
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