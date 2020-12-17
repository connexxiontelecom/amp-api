<?php namespace App\Controllers;
use App\Models\AdminModel;
use CodeIgniter\RESTful\ResourceController;

class Home extends ResourceController
{
	private $admin;
	private $validation;

	function __construct() {
		$this->admin = new AdminModel();
	}

	function index() {
		$admin_accounts = $this->admin->findAll();
		$statistics = [
			'admin_accounts' => $admin_accounts
		];
	  return $this->respond($statistics);
	}

	//--------------------------------------------------------------------

}
