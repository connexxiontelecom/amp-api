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
use CodeIgniter\RESTful\ResourceController;
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
	protected $admin_log;
	protected $validation;
	protected $decoded_token;

	private $secret_key;
	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
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
		$this->validation = \Config\Services::validation();
		$this->secret_key = getenv('JWT_SECRET');
//		$this->decode_token();
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

	private function get_authorization_header(): string {
		$headers = array_map(function($header) {
			return $header->getValueLine();
		}, $this->request->getHeaders());
		return $headers['Authorization'];
	}

	protected function decode_token(): bool {
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
