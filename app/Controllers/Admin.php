<?php namespace App\Controllers;
use App\Models\AdminModel;
use CodeIgniter\RESTful\ResourceController;

class Admin extends ResourceController {
	private $admin;
	private $validation;

	function __construct() {
		$this->admin = new AdminModel();
		$this->validation = \Config\Services::validation();
	}

	function all_admins() {
		$admins = $this->admin->findAll();
		return $this->respond($admins);
	}

	function add_admin() {
		$this->validation->setRules([
			'firstname' => 'required',
			'lastname' => 'required',
			'username' => 'required',
			'password' => 'required|min_length[5]'
		]);
		if ($this->validation->withRequest($this->request)->run()) {
			$firstname = $this->request->getPost('firstname');
			$lastname = $this->request->getPost('lastname');
			$username = $this->request->getPost('username');
			$password = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);
			$status = $this->request->getPost('status');
			$user = $this->admin->where('username', $username)->first();
			if (!$user) {
				$admin_user = [
					'firstname' => $firstname,
					'lastname' => $lastname,
					'username' => $username,
					'password' => $password,
					'status' => $status
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
				return $this->fail('Admin account with that username already exists');
			}
		} else {
			return $this->fail($this->validation->getErrors());
		}
	}

	function update_status() {
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
	}
}