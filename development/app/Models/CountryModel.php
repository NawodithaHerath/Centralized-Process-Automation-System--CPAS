<?php namespace App\Models;

use CodeIgniter\Model;

class CountryModel extends Model{

    protected $table = 'country';
    protected $primaryKey = 'country_id';
    
    protected $allowedFields = ['country_name'];


}