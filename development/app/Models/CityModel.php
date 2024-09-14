<?php namespace App\Models;

use CodeIgniter\Model;

class CityModel extends Model{

    protected $table = 'city';
    protected $primaryKey = 'city_id';
    protected $allowedFields = ['state_id','city_name'];
}