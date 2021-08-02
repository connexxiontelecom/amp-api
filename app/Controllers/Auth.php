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
				  	$data['name'] = $this->request->getPost('firstname').' '.$this->request->getPost('lastname');
					  $data['verify_link'] =  getenv('FRONT_END').'verify-'.$this->request->getPost('verify_code');
					  $email_data['message'] = view('emails/verify-email', $data);
					  $email_data['subject'] = 'Verify your email address on AMP';
					  $email_data['from_email'] = 'support@connexxiontelecom.com';
					  $email_data['from_name'] = 'AMP | Powered by Connexxion Telecom';
					  $email_data['to_email'] = $this->request->getPost('email');
            $this->send_mail($email_data);
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
        $affiliate_data = [
          'affiliate_id' => $affiliate['affiliate_id'],
          'profile' => 1
        ];
        try {
          $save = $this->affiliate->save($affiliate_data);
        } catch (\Exception $ex) {
          return $this->fail($ex->getMessage());
        }
        if ($save) {
	        $data['name'] = $affiliate['firstname'].' '.$affiliate['lastname'];
	        $email_data['message'] = view('emails/welcome-email', $data);
	        $email_data['subject'] = 'We are pleased to welcome you to AMP';
	        $email_data['from_email'] = 'support@connexxiontelecom.com';
	        $email_data['from_name'] = 'AMP | Powered by Connexxion Telecom';
	        $email_data['to_email'] = $affiliate['email'];
          $this->send_mail($email_data);
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

  function resend_confirmation() {
    $this->validation->setRules([
      'firstname' => 'required',
      'lastname' => 'required',
      'email' => 'required',
      'verify_code' => 'required',
    ]);
    if ($this->validation->withRequest($this->request)->run()) {
    	$data['name'] = $this->request->getPost('firstname').' '.$this->request->getPost('lastname');
    	$data['verify_link'] = getenv('FRONT_END').'verify-'.$this->request->getPost('verify_code');
	    $email_data['message'] = view('emails/verify-email', $data);
	    $email_data['subject'] = 'Verify your email address on AMP';
	    $email_data['from_email'] = 'support@connexxiontelecom.com';
	    $email_data['from_name'] = 'AMP | Powered by Connexxion Telecom';
	    $email_data['to_email'] = $this->request->getPost('email');
      $this->send_mail($email_data);
      return $this->respond('Verification email resent');
    } else {
      return $this->fail($this->validation->getErrors());
    }
  }

	function send_reset_password_link() {
		$this->validation->setRules([
			'email' => 'required',
		]);
		if ($this->validation->withRequest($this->request)->run()) {
			$affiliate = $this->affiliate->where('email', $this->request->getPost('email'))->first();
			if (!$affiliate) {
				return $this->failNotFound('Affiliate with this email does not exist');
			}
			$verification_code = $this->get_verification_code($affiliate['affiliate_id'], 'reset_password');
			$data['email'] = $this->request->getPost('email');
			$data['name'] = $affiliate['firstname'].' '.$affiliate['lastname'];
			$data['reset_password_link'] = getenv('FRONT_END').'reset-password-'.$verification_code;
			$email_data['message'] = view('emails/reset-password-email', $data);
			$email_data['subject'] = 'Reset your password on AMP';
			$email_data['from_email'] = 'support@connexxiontelecom.com';
			$email_data['from_name'] = 'AMP | Powered by Connexxion Telecom';
			$email_data['to_email'] = $this->request->getPost('email');
			$this->send_mail($email_data);
			return $this->respond('Reset password link sent successfully. Please check your email.');
		} else {
			return $this->fail($this->validation->getErrors());
		}
	}

	function reset_password() {
		$this->validation->setRules([
			'verification_code' => 'required',
			'password' => 'required',
		  'confirm_password' => 'required|matches[password]'
		]);
		if ($this->validation->withRequest($this->request)->run()) {
			$verified = $this->verification->where([
				'verification_code' => $this->request->getPost('verification_code'),
				'verification_type' => 'reset_password',
				'verification_status' => 0,
			])->first();
			if ($verified) {
				$verification_data = [
					'verification_id' => $verified['verification_id'],
					'verification_status' => 1,
				];
				$this->verification->save($verification_data);
				$affiliate = $this->affiliate->find($verified['affiliate_id']);
				$affiliate_data = [
					'affiliate_id' => $affiliate['affiliate_id'],
					'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT)
				];
				if ($this->affiliate->save($affiliate_data)) {
					$data['name'] = $affiliate['firstname'].' '.$affiliate['lastname'];
					$data['login_link'] = getenv('FRONT_END').'login';
					$email_data['message'] = view('emails/password-reset-email', $data);
					$email_data['subject'] = 'Successfully reset your AMP password';
					$email_data['from_email'] = 'support@connexxiontelecom.com';
					$email_data['from_name'] = 'AMP | Powered by Connexxion Telecom';
					$email_data['to_email'] = $affiliate['email'];
					$this->send_mail($email_data);
					return $this->respond('You have successfully reset your password. Please login with your new credentials.');
				} else {
					return $this->fail('An error occurred while resetting your password');
				}
			} else {
				return $this->fail('Your verification link is not valid.');
			}
		} else {
			return $this->fail($this->validation->getErrors());
		}
	}
}