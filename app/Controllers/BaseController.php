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

use App\Models\AdminModel;
use App\Models\AffiliateModel;
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
	protected $commission;
	protected $product;
	protected $product_plan;
	protected $validation;

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
		$this->commission = new CommissionModel();
		$this->product = new ProductModel();
		$this->product_plan = new ProductPlanModel();
		$this->validation = \Config\Services::validation();
	}

	protected function jwt($user, $session): string {
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
}
