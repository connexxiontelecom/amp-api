<?php
namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use App\Models\AdminLogModel;
use App\Models\AdminModel;
use App\Models\AffiliateInfoModel;
use App\Models\AffiliateModel;
use App\Models\BankModel;
use App\Models\CommissionModel;
use App\Models\ProductModel;
use App\Models\ProductPlanModel;
use App\Models\ProductSaleModel;
use App\Models\VerificationModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Psr\Log\LoggerInterface;

use Firebase\JWT\JWT;

class BaseController extends ResourceController
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];
	protected $admin;
	protected $affiliate;
	protected $affiliate_info;
	protected $bank;
	protected $commission;
	protected $product;
	protected $product_plan;
	protected $product_sale;
	protected $verification;
	protected $admin_log;
	protected $validation;
	protected $decoded_token;
  protected $email;

  private $secret_key;

  /**
   * Constructor.
   * @param RequestInterface $request
   * @param ResponseInterface $response
   * @param \Psr\Log\LoggerInterface $logger
   */
	public function initController(RequestInterface $request, ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		// $this->session = \Config\Services::session();
		$this->admin = new AdminModel();
		$this->affiliate = new AffiliateModel();
		$this->affiliate_info = new AffiliateInfoModel();
		$this->bank = new BankModel();
		$this->commission = new CommissionModel();
		$this->product = new ProductModel();
		$this->product_plan = new ProductPlanModel();
		$this->admin_log = new AdminLogModel();
		$this->product_sale = new ProductSaleModel();
		$this->verification = new VerificationModel();

		$this->validation = \Config\Services::validation();
		$this->email = \Config\Services::email();

    $this->secret_key = getenv('JWT_SECRET');
		$this->decode_token();
	}

	protected function send_mail($email_data) {
		$this->email->setTo($email_data['to_email']);
		$this->email->setFrom($email_data['from_email'], $email_data['from_name']);
		$this->email->setSubject($email_data['subject']);
		$this->email->setMessage($email_data['message']);
		$sent = $this->email->send(FALSE);
		if (!$sent) {
			echo $this->email->printDebugger();
		}
		return $sent;
	}

	protected function jwt($user, $session, $permissions): string {
		$payload = [
			"iss" => "THE_CLAIM",
			"aud" => "THE_AUDIENCE",
			"iat" => time(),
			"nbf" => time(),
			"exp" => time() + 3600,
			'user' => $user,
			'session' => $session,
			'permissions' => $permissions
		];
		return JWT::encode($payload, $this->secret_key);
	}

	protected function generate_ref_code($length = 10): string {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$characters_length = strlen($characters);
		$ref_code = '';
		for ($i = 0; $i < $length; $i++) {
			$ref_code .= $characters[rand(0, $characters_length - 1)];
		}
		return $ref_code;
	}

	protected function check_username_exists($username) {
		return $this->admin->where('username', $username)->first() || $this->affiliate->where('username', $username)->first();
	}

	protected function check_email_exists($email) {
		return $this->affiliate->where('email', $email)->first();
	}

	protected function check_product_name_exists($product_name) {
		return $this->product->where('name', $product_name)->first();
	}

	protected function is_admin_session(): bool {
		if ($this->decoded_token) {
			return $this->decoded_token->session->admin;
		}
		return false;
	}

	protected function is_affiliate_session(): bool {
		if ($this->decoded_token) {
			return $this->decoded_token->session->affiliate;
		}
		return false;
	}

	protected function get_verification_code($affiliate_id, $verification_type) {
		$verification_code = bin2hex(random_bytes(8));
		$verification_data = [
			'affiliate_id' => $affiliate_id,
			'verification_type' => $verification_type,
			'verification_code' => $verification_code,
			'verification_status' => 0,
		];
		$verified = $this->verification->where([
			'affiliate_id' => $affiliate_id,
			'verification_type' => $verification_type
		])->first();
		if ($verified) {
			$verification_data['verification_id'] = $verified['verification_id'];
		}
		$this->verification->save($verification_data);
		return $verification_code;
	}


	private function get_authorization_header(): string {
	  $authorization_header = '';
		$headers = array_map(function($header) {
			return $header->getValueLine();
		}, $this->request->getHeaders());

		if (array_key_exists('Authorization', $headers)) {
		  $authorization_header = $headers['Authorization'];
    }

    return $authorization_header;
	}

	private function decode_token(): bool {
		$authorization = $this->get_authorization_header();
		if ($authorization) {
			$authorization = explode(" ", $authorization);
			$token = $authorization[1];
			try {
				$this->decoded_token = JWT::decode($token, $this->secret_key, array('HS256'));
			} catch (\Exception $e) {
				return false;
			}
		}
		return false;
	}


}
