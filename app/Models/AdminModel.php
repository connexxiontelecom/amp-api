<?php namespace App\Models;
use CodeIgniter\Model;

class AdminModel extends Model {
  protected $table = 'admin';
  protected $allowedFields = ['firstname', 'lastname', 'username', 'password', 'roles', 'status'];
  protected $primaryKey = 'admin_id';
}