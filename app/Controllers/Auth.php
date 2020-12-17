<?php namespace App\Controllers;
use App\Models\AdminModel;
use App\Models\AffiliateModel;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;

class Auth extends ResourceController {
  private $admin;
  private $affiliate;
  private $validation;

  function __construct() {
    $this->admin = new AdminModel();
    $this->affiliate = new AffiliateModel();
    $this->validation = \Config\Services::validation();
  }

  private function jwt($user, $session): string {
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
  	return JWT::encode($payload, $secret_key);
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
		  if ($user && password_verify($password, $user['password'])) {
			  $session = [
				  'admin'=> true,
				  'affiliate' => false,
			  ];
			  $token = $this->jwt($user, $session);
			  return $this->respond($token);
		  } else {
			  $user = $this->affiliate->where('username', $username)->first();
			  if ($user && password_verify($password, $user['password'])) {
				  $session = [
					  'admin' => false,
					  'affiliate' => true
				  ];
				  $token = $this->jwt($user, $session);
				  return $this->respond($token);
			  } else {
				  return $this->failNotFound('Invalid username or password');
			  }
		  }
	  } else {
  		return $this->fail($this->validation->getErrors());
	  }
  }

  function register() {
  	$this->validation->setRules([
  		'username' => 'required',
		  'email' => 'required|valid_email',
		  'password' => 'required|min_length[5]',
		  'confirm_password' => 'required|min_length[5]|matches[password]'
	  ]);
  	if ($this->validation->withRequest($this->request)->run()) {
  		$username = $this->request->getPost('username');
  		$email = $this->request->getPost('email');
		  $password = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);
		  $user = $this->affiliate->where('username', $username)->first();
		  if (!$user) {
		  	$affiliate_user = [
		  		'username' => $username,
				  'email' => $email,
				  'password' => $password
			  ];
			  try {
				  $save = $this->affiliate->save($affiliate_user);
			  } catch (\Exception $ex) {
				  return $this->fail($ex->getMessage());
			  }
			  if ($save) {
				  return $this->respondCreated('Affiliate account was created. Login to your account');
			  } else {
				  return $this->fail('Affiliate account could not be created');
			  }
		  } else {
		  	return $this->fail('Affiliate account with that username already exists');
		  }
	  } else {
  		return $this->fail($this->validation->getErrors());
	  }
  }
}