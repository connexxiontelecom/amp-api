<?php namespace App\Controllers;

class ProductSale extends BaseController {

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
      $product = $this->product->find($this->request->getPost('product_id'));
      $affiliate = $this->affiliate->where('ref_code', $this->request->getPost('referral_code'))->first();
      if ($product && $affiliate) {
        $product_sale_data = [
          'referral_code' => $this->request->getPost('referral_code'),
          'product_id' => $this->request->getPost('product_id'),
          'amount' => $this->request->getPost('amount'),
          'company_name' => $this->request->getPost('company_name'),
          'contact_email' => $this->request->getPost('contact_email'),
          'month' => $this->request->getPost('month'),
          'year' => $this->request->getPost('year'),
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