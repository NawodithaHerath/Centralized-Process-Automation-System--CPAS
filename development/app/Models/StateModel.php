<?php namespace App\Models;

use CodeIgniter\Model;

class StateModel extends Model{

    protected $table = 'state';

    protected $primaryKey = 'state_id';

    protected $allowedFields = ['country_id','state_name'];


}