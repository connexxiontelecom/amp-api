<?php namespace App\Controllers;

class Auth extends BaseController {

  function login() {
  	$this->validation->setRules([
  		'username' => 'required',
		  'password' => 'required|min_length[5]'
	  ]);
  	$session = [
  		'admin' => false,
		  'affiliate' => false
	  ];
  	if ($this->validation->withRequest($this->request)->run()) {
		  $username = $this->request->getPost('username');
		  $password = $this->request->getPost('password');
		  $user = $this->admin->where('username', $username)->first();
		  if ($user && password_verify($password, $user['password'])) {
			  $session['admin'] = true;
			  $user['password'] = '';
			  $token = $this->jwt($user, $session);
			  return $this->respond($token);
		  } else {
			  $user = $this->affiliate->where('username', $username)->first();
			  if ($user && password_verify($password, $user['password'])) {
			  	$upstream_affiliate = $this->affiliate->where('affiliate_id', $user['upstream_affiliate_id'])->first();
			  	$affiliate_info = $this->affiliate_info->where('affiliate_id', $user['affiliate_id'])->first();
				  $bank = $this->bank->where('affiliate_id', $user['affiliate_id'])->first();
				  $session['affiliate'] = true;
				  $user['password'] = '';
				  $user['upstream_affiliate'] = $upstream_affiliate;
				  $user['affiliate_info'] = $affiliate_info;
				  $user['bank'] = $bank;
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
  		'firstname' => 'required',
  		'lastname' => 'required',
  		'username' => 'required',
		  'email' => 'required|valid_email',
		  'password' => 'required|min_length[5]',
		  'confirm_password' => 'required|min_length[5]|matches[password]'
	  ]);
  	if ($this->validation->withRequest($this->request)->run()) {
		  if (!$this->check_username_exists($this->request->getPost('username'))) {
		  	if (!$this->check_email_exists($this->request->getPost('email'))) {
				  $affiliate_user = [
					  'firstname' => $this->request->getPost('firstname'),
					  'lastname' => $this->request->getPost('lastname'),
					  'username' => $this->request->getPost('username'),
					  'email' => $this->request->getPost('email'),
					  'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
					  'ref_code' => $this->generate_ref_code()
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
				  return $this->fail('Account with that email already exists');
			  }
		  } else {
		  	return $this->fail('Account with that username already exists');
		  }
	  } else {
  		return $this->fail($this->validation->getErrors());
	  }
  }
}