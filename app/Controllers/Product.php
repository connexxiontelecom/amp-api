<?php namespace App\Controllers;


class Product extends BaseController {

  function __construct() {
    $this->decode_token();
  }

	function all_products() {
		if ($this->is_admin_session() || $this->is_affiliate_session()) {
			$products = $this->product->findAll();
			return $this->respond($products);
		} else {
			return $this->failUnauthorized();
		}
	}

	function get_product($product_id) {
		if ($this->is_admin_session() || $this->is_affiliate_session()) {
			$product = $this->product->find($product_id);
			if ($product) {
				$plans = $this->product_plan->where('product_id', $product_id)->findAll();
				$payload = [
					'product' => $product,
					'plans' => $plans
				];
				return $this->respond($payload);
			} else {
				return $this->failNotFound('Product was not found');
			}
		} else {
			return $this->failUnauthorized();
		}
	}

	function add_product() {
		if ($this->is_admin_session()) {
			$this->validation->setRules([
				'name' => 'required',
				'category' => 'required',
				'logo' => [
					'uploaded[logo]',
					'mime_in[logo,image/jpg,image/jpeg,image/gif,image/png]',
					'max_size[logo,2097152]'
				]
			]);
			if ($this->validation->withRequest($this->request)->run()) {
				if (!$this->check_product_name_exists($this->request->getPost('name'))) {
					$logo = $this->request->getFile('logo');
					$logo->move(ROOTPATH.'public/uploads/products');
					$product = [
						'name' => $this->request->getPost('name'),
						'url' => $this->request->getPost('url'),
						'category' => $this->request->getPost('category'),
						'logo' => $logo->getClientName(),
						'description' => $this->request->getPost('description'),
					];
					try {
						$save = $this->product->save($product);
					} catch (\Exception $ex) {
						return $this->fail($ex->getMessage());
					}
					if ($save) {
						$products = $this->product->findAll();
						return $this->respond($products);
					} else {
						return $this->fail('Product could not be added');
					}
				} else {
					return $this->failResourceExists('Product with that name already exists');
				}
			} else {
				return $this->fail('Form validation failed. Please confirm logo was uploaded correctly');
			}
		} else {
			return $this->failUnauthorized();
		}
	}

	function update_product() {
		if ($this->is_admin_session()) {
			$this->validation->setRules([
				'product_id' => 'required',
				'name' => 'required',
				'category' => 'required',
			]);
			if ($this->validation->withRequest($this->request)->run()) {
				$product = $this->check_product_name_exists($this->request->getPost('name'));
				if ($product) {
					if($product['product_id'] != $this->request->getPost('product_id')) {
						return $this->failResourceExists('Product with that name already exists');
					}
				}
				$product = [
					'product_id' => $this->request->getPost('product_id'),
					'name' => $this->request->getPost('name'),
					'url' => $this->request->getPost('url'),
					'category' => $this->request->getPost('category'),
					'description' => $this->request->getPost('description'),
				];
				try {
					$save = $this->product->save($product);
				} catch (\Exception $ex) {
					return $this->fail($ex->getMessage());
				}
				if ($save) {
					$product = $this->product->find($this->request->getPost('product_id'));
					if ($product) {
						$plans = $this->product_plan->where('product_id', $this->request->getPost('product_id'))->findAll();
						$payload = [
							'product' => $product,
							'plans' => $plans
						];
						return $this->respond($payload);
					} else {
						return $this->fail('Product was not found');
					}
				} else {
					return $this->fail('Product could not be updated');
				}
			} else {
				return $this->fail($this->validation->getErrors());
			}
		} else {
			return $this->failUnauthorized();
		}
	}

	function update_logo() {
		if ($this->is_admin_session()) {
			$this->validation->setRules([
				'product_id' => 'required',
				'logo' => [
					'uploaded[logo]',
					'mime_in[logo,image/jpg,image/jpeg,image/gif,image/png]',
					'max_size[logo,2097152]'
				]
			]);
			if ($this->validation->withRequest($this->request)->run()) {
				$product = $this->product->find($this->request->getPost('product_id'));
				if ($product) {
					if ($product['logo']) {
						unlink(ROOTPATH.'public/uploads/products/'.$product['logo']);
					}
					$logo = $this->request->getFile('logo');
					$logo->move(ROOTPATH.'public/uploads/products');
					$product = [
						'product_id' => $this->request->getPost('product_id'),
						'logo' => $logo->getClientName(),
					];
					try {
						$save = $this->product->save($product);
					} catch (\Exception $ex) {
						return $this->fail($ex->getMessage());
					}
					if ($save) {
						$product = $this->product->find($this->request->getPost('product_id'));
						$plans = $this->product_plan->where('product_id', $this->request->getPost('product_id'))->findAll();
						$payload = [
							'product' => $product,
							'plans' => $plans
						];
						return $this->respond($payload);
					} else {
						return $this->fail('Product could not be added');
					}
				} else {
					return $this->failNotFound('Product was not found');
				}
			} else {
				return $this->fail('Form validation failed. Please confirm logo was uploaded correctly');
			}
		} else {
			return $this->failUnauthorized();
		}
	}

	function remove_logo() {
		if ($this->is_admin_session()) {
			$this->validation->setRules([
				'product_id' => 'required',
			]);
			if ($this->validation->withRequest($this->request)->run()) {
				$product = $this->product->find($this->request->getPost('product_id'));
				if ($product) {
					unlink(ROOTPATH.'public/uploads/products/'.$product['logo']);
					$product = [
						'product_id' => $this->request->getPost('product_id'),
						'logo' => '',
					];
					try {
						$save = $this->product->save($product);
					} catch (\Exception $ex) {
						return $this->fail($ex->getMessage());
					}
					if ($save) {
						$product = $this->product->find($this->request->getPost('product_id'));
						$plans = $this->product_plan->where('product_id', $this->request->getPost('product_id'))->findAll();
						$payload = [
							'product' => $product,
							'plans' => $plans
						];
						return $this->respond($payload);
					} else {
						return $this->fail('Product logo could not be deleted');
					}
				} else {
					return $this->failNotFound('Product was not found');
				}
			} else {
				return $this->fail($this->validation->getErrors());
			}
		} else {
			return $this->failUnauthorized();
		}
	}

	function add_plan() {
		if ($this->is_admin_session()) {
			$this->validation->setRules([
				'product_id' => 'required',
				'plan_name' => 'required',
				'plan_price' => 'required',
				'plan_link' => 'required',
				'plan_commission' => 'required',
				'plan_slug' => 'required'
			]);
			if ($this->validation->withRequest($this->request)->run()) {
				$product_id = $this->request->getPost('product_id');
				$plan_name = $this->request->getPost('plan_name');
				$plan_price = $this->request->getPost('plan_price');
				$plan_link = $this->request->getPost('plan_link');
				$plan_commission = $this->request->getPost('plan_commission');
				$plan_slug = $this->request->getPost('plan_slug');
				$product = $this->product->find($product_id);
				if ($product) {
					$plan_data = [
						'product_id' => $product_id,
						'plan_name' => $plan_name,
						'plan_price' => $plan_price,
						'plan_link' => $plan_link,
						'plan_commission' => $plan_commission,
						'plan_slug' => $plan_slug
					];
					try {
						$save = $this->product_plan->save($plan_data);
					} catch (\Exception $ex) {
						return $this->fail($ex->getMessage());
					}
					if ($save) {
						$plans = $this->product_plan->where('product_id', $product_id)->findAll();
						$this->increment_num_plans($product_id, count($plans));
						$payload = [
							'product' => $product,
							'plans' => $plans
						];
						return $this->respond($payload);
					} else {
						return $this->fail('Product plan could not be added');
					}
				} else {
					return $this->failNotFound('Product not found');
				}
			} else {
				return $this->fail($this->validation->getErrors());
			}
		} else {
			return $this->failUnauthorized();
		}
	}

	function edit_plan() {
		if ($this->is_admin_session()) {
			$this->validation->setRules([
				'product_id' => 'required',
				'product_plan_id' => 'required',
				'plan_name' => 'required',
				'plan_price' => 'required',
				'plan_link' => 'required',
				'plan_commission' => 'required',
				'plan_slug' => 'required',
			]);
			if ($this->validation->withRequest($this->request)->run()) {
				$product = $this->product->find($this->request->getPost('product_id'));
				if ($product) {
					$plan_data = [
						'product_id' => $this->request->getPost('product_id'),
						'product_plan_id' => $this->request->getPost('product_plan_id'),
						'plan_name' => $this->request->getPost('plan_name'),
						'plan_price' => $this->request->getPost('plan_price'),
						'plan_link' => $this->request->getPost('plan_link'),
						'plan_commission' => $this->request->getPost('plan_commission'),
						'plan_slug' => $this->request->getPost('plan_slug')
					];
					try {
						$save = $this->product_plan->save($plan_data);
					} catch (\Exception $ex) {
						return $this->fail($ex->getMessage());
					}
					if ($save) {
						$plans = $this->product_plan->where('product_id', $this->request->getPost('product_id'))->findAll();
						$this->increment_num_plans($this->request->getPost('product_id'), count($plans));
						$payload = [
							'product' => $product,
							'plans' => $plans
						];
						return $this->respond($payload);
					} else {
						return $this->fail('Product plan could not be updated');
					}
				} else {
					return $this->failNotFound('Product not found');
				}
			} else {
				return $this->fail($this->validation->getErrors());
			}
		} else {
			return $this->failUnauthorized();
		}
	}

	function delete_plan() {
		if ($this->is_admin_session()) {
			$this->validation->setRules([
				'product_id' => 'required',
				'product_plan_id' => 'required',
			]);
			if ($this->validation->withRequest($this->request)->run()) {
				$plan = $this->product_plan->find($this->request->getPost('product_plan_id'));
				$product = $this->product->find($this->request->getPost('product_id'));
				if ($plan) {
					$this->product_plan->delete($this->request->getPost('product_plan_id'));
					$plans = $this->product_plan->where('product_id', $this->request->getPost('product_id'))->findAll();
					$this->increment_num_plans($this->request->getPost('product_id'), count($plans));
					$payload = [
						'product' => $product,
						'plans' => $plans
					];
					return $this->respond($payload);
				} else {
					return $this->failNotFound('Plan not found');
				}
			} else {
				return $this->fail($this->validation->getErrors());
			}
		} else {
			return $this->failUnauthorized();
		}
	}

	function toggle_product_status($product_id) {
		if ($this->is_admin_session()) {
			$product = $this->product->find($product_id);
			if ($product) {
				$product['status'] == 1 ? $product['status'] = 0 : $product['status'] = 1;
				try {
					$save = $this->product->save($product);
				} catch (\Exception $ex) {
					return $this->fail($ex->getMessage());
				}
				if ($save) {
					$product = $this->product->find($product_id);
					if ($product) {
						$plans = $this->product_plan->where('product_id', $product_id)->findAll();
						$payload = [
							'product' => $product,
							'plans' => $plans
						];
						return $this->respond($payload);
					} else {
						return $this->fail('Product was not found');
					}
				} else {
					return $this->fail('Product status could not be changed');
				}
			} else {
				return $this->failNotFound('Affiliate was not found');
			}
		} else {
			return $this->failUnauthorized();
		}
	}

	private function increment_num_plans($product_id, $num_plans): bool {
		$product_data = [
			'product_id' => $product_id,
			'num_plans' => $num_plans
		];
		try {
			return $this->product->save($product_data);
		} catch (\Exception $ex) {
			return false;
		}
	}

}