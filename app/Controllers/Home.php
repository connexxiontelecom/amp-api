<?php namespace App\Controllers;

class Home extends BaseController {

	function index() {
		$admin_accounts = $this->admin->findAll();
		$statistics = [
			'admin_accounts' => $admin_accounts
		];
	  return $this->respond($statistics);
	}

	//--------------------------------------------------------------------

}
