<?php namespace App\Controllers;

class ProductSale extends BaseController {

  function all_product_sales() {
    if ($this->is_admin_session() || $this->is_affiliate_session()) {
      $product_sales = $this->product_sale->findAll();
      $payload = [];
      foreach ($product_sales as $product_sale) {
        $affiliate = $this->affiliate->where('ref_code', $product_sale['referral_code'])->first();
        $product = $this->product->find($product_sale['product_id']);
        $product_sale['affiliate'] = $affiliate;
        $product_sale['product'] = $product;
        array_push($payload, $product_sale);
      }
      return $this->respond($payload);
    } else {
      return $this->failUnauthorized();
    }
  }

  function affiliate_product_sales($affiliate_ref_code) {
    if ($this->is_admin_session() || $this->is_affiliate_session()) {
      $affiliate = $this->affiliate->where('ref_code', $affiliate_ref_code)->first();
      if ($affiliate) {
        $product_sales = $this->product_sale->where('referral_code', $affiliate_ref_code)->findAll();
        $payload = [];
        foreach ($product_sales as $product_sale) {
          $product = $this->product->find($product_sale['product_id']);
          $product_sale['affiliate'] = $affiliate;
          $product_sale['product'] = $product;
          array_push($payload, $product_sale);
        }
        return $this->respond($payload);
      } else {
        return $this->fail('Affiliate with that referral code does not exist');
      }
    } else {
      return $this->failUnauthorized();
    }
  }

  function add_product_sale() {
    $this->validation->setRules([
      'product_id' => 'required',
      'referral_code' => 'required',
      'amount' => 'required',
      'company_name' => 'required',
      'contact_email' => 'required|valid_email',
      'month' => 'required',
      'year' => 'required',
    ]);
    if ($this->validation->withRequest($this->request)->run()) {
      $product = $this->product->find($this->request->getVar('product_id'));
      $affiliate = $this->affiliate->where('ref_code', $this->request->getVar('referral_code'))->first();
      if ($product && $affiliate) {
        $product_sale_data = [
          'referral_code' => $this->request->getVar('referral_code'),
          'product_id' => $this->request->getVar('product_id'),
          'amount' => $this->request->getVar('amount'),
          'company_name' => $this->request->getVar('company_name'),
          'contact_email' => $this->request->getVar('contact_email'),
          'status' => 0,
          'month' => $this->request->getVar('month'),
          'year' => $this->request->getVar('year'),
        ];
        $saved = $this->product_sale->save($product_sale_data);
        if ($saved) {
          // do stuff including sending email to affiliate
          return $this->respondCreated();
        } else {
          // send error message (possibly email to affiliate)
          return $this->fail('An error occurred while saving the product sale');
        }
      } else {
        return $this->fail('The product or the affiliate marketer does not exist on AMP');
      }
    } else {
      return $this->fail($this->validation->getErrors());
    }
  }
}