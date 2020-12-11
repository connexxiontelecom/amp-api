<?php namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;

class Home extends ResourceController
{
	public function index()
	{
	  return $this->respond('Welcome to amp-api-v0');
	}

	//--------------------------------------------------------------------

}
