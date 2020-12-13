<?php namespace App\Controllers;
use App\Models\AdminModel;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;

class Auth extends ResourceController {
  private $admin;
  private $validation;

  function __construct() {
    $this->admin = new AdminModel();
    $this->validation = \Config\Services::validation();
  }

  function login() {
  	$this->validation->setRules([
  		'username' => 'required',
		  'password' => 'required|min_length[5]'
	  ]);
  	if ($this->validation->withRequest($this->request)->run()) {
		  $username = $this->request->getPost('username');
		  $password = $this->request->getPost('password');
		  $user = $this->admin->where('username', $username)->first();
		  if ($user) {
			  if (password_verify($password, $user['password'])) {
				  $session = [
					  'admin'=> true
				  ];
				  $secret_key = getenv('JWT_SECRET');
				  $payload = [
					  "iss" => "THE_CLAIM",
					  "aud" => "THE_AUDIENCE",
					  "iat" => time(),
					  "nbf" => time(),
					  "exp" => time() + 3600,
					  'user' => $user,
					  'session' => $session
				  ];
				  $token = JWT::encode($payload, $secret_key);
				  return $this->respond($token);
			  } else {
				  return $this->failNotFound('Invalid username or password');
			  }
		  } else {
			  // @TODO check if an affiliate user
			  return $this->failNotFound('Invalid username or password');
		  }
	  } else {
  		return $this->fail($this->validation->getErrors());
	  }
  }
}