<?php namespace App\Models;
use CodeIgniter\Model;

class ProductSaleModel extends Model {
  protected $table = 'product_sale';
  protected $allowedFields = ['referral_code', 'amount', 'product_id', 'company_name', 'contact_email', 'month', 'year'];
  protected $primaryKey = 'product_sale_id';
}