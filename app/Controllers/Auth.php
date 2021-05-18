<?php namespace App\Controllers;

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

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
			  $permissions = explode(',', unserialize($user['roles']));
			  $token = $this->jwt($user, $session, $permissions);
//			  $log = [
//			  	'admin_id' => $user['admin_id'],
//				  'type' => 'login',
//				  'title' => 'Successful Login',
//				  'description' => $user['username'].' successfully logged into the system'
//			  ];
//			  $this->admin_log->save($log);
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
				  $permissions = [];
				  $token = $this->jwt($user, $session, $permissions);
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
		  'verify_code' => 'required',
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
					  'verify_code' => $this->request->getPost('verify_code'),
					  'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
					  'ref_code' => $this->generate_ref_code()
				  ];
				  try {
					  $save = $this->affiliate->save($affiliate_user);
				  } catch (\Exception $ex) {
					  return $this->fail($ex->getMessage());
				  }
				  if ($save) {
            try {
              $this->email->isSMTP();
              $this->email->Host = 'connexxiontelecom.com';
              $this->email->SMTPAuth = true;
              $this->email->Port = 465;
              $this->email->Username = 'support@connexxiontelecom.com';
              $this->email->Password = 'RM*Kv7J=p=[-FUOY}6';

              $this->email->setFrom($this->from_email, $this->from_name);
              $this->email->addAddress($this->request->getPost('email'));
              $this->email->isHTML(true);
              $this->email->Subject = 'Verify your email address on AMP';
              $this->email->Body = '<p>Verification Mail is sent</p>';
              $this->email->send();

            } catch (Exception $e) {
              print_r("Message could not be sent. Mailer Error: {$this->email->ErrorInfo}");
            }
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

  function verify_account() {
    $this->validation->setRules([
      'verify_code' => 'required'
    ]);
    if ($this->validation->withRequest($this->request)->run()) {
      $affiliate = $this->affiliate->where('verify_code', $this->request->getPost('verify_code'))->first();
      if ($affiliate) {
        $payload = ['verified' => false];
        if ($affiliate['profile'] == 1) {
          return $this->respond($payload);
        }
        $affiliate = [
          'affiliate_id' => $affiliate['affiliate_id'],
          'profile' => 1
        ];
        try {
          $save = $this->affiliate->save($affiliate);
        } catch (\Exception $ex) {
          return $this->fail($ex->getMessage());
        }
        if ($save) {
          $payload['verified'] = true;
          return $this->respondUpdated($payload);
        } else {
          return $this->fail('Affiliate could not be verified');
        }
      } else {
        return $this->failNotFound('Affiliate account not found');
      }
    } else {
      return $this->fail($this->validation->getErrors());
    }
  }
}