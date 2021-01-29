<?php namespace App\Controllers;

class Admin extends BaseController {

	function all_admins() {
		if ($this->is_admin_session()) {
			$admins = $this->admin->findAll();
			return $this->respond($admins);
		} else {
			return $this->failUnauthorized();
		}
	}

	function add_admin() {
		if ($this->is_admin_session()) {
			$this->validation->setRules([
				'firstname' => 'required',
				'lastname' => 'required',
				'username' => 'required',
				'password' => 'required|min_length[5]'
			]);
			if ($this->validation->withRequest($this->request)->run()) {
				if (!$this->check_username_exists($this->request->getPost('username'))) {
					$admin_user = [
						'firstname' => $this->request->getPost('firstname'),
						'lastname' => $this->request->getPost('lastname'),
						'username' => $this->request->getPost('username'),
						'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
						'status' => $this->request->getPost('status')
					];
					try {
						$save = $this->admin->save($admin_user);
					} catch (\Exception $ex) {
						return $this->fail($ex->getMessage());
					}
					if ($save) {
						return $this->respondCreated('Admin account was created');
					} else {
						return $this->fail('Admin account could not be created');
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

	function get_admin($admin_id) {
		if ($this->is_admin_session()) {
			$admin = $this->admin->find($admin_id);
			if ($admin) {
				return $this->respond($admin);
			} else {
				return $this->failNotFound('Admin was not found');
			}
		} else {
			return $this->failUnauthorized();
		}
	}

	function update_status() {
		if ($this->is_admin_session()) {
			$this->validation->setRules([
				'admin_id' => 'required',
				'status' => 'required',
			]);
			if ($this->validation->withRequest($this->request)->run()) {
				$admin_id = $this->request->getPost('admin_id');
				$status = $this->request->getPost('status');
				$user = $this->admin->where('admin_id', $admin_id)->first();
				if ($user) {
					$admin_user = [
						'admin_id' => $admin_id,
						'status' => $status
					];
					try {
						$save = $this->admin->save($admin_user);
					} catch (\Exception $ex) {
						return $this->fail($ex->getMessage());
					}
					if ($save) {
						return $this->respondUpdated('Admin account was updated');
					} else {
						return $this->fail('Admin account could not be updated');
					}
				} else {
					return $this->failNotFound('Admin account not found');
				}
			} else {
				return $this->fail($this->validation->getErrors());
			}
		} else {
			return $this->failUnauthorized();
		}
	}
}