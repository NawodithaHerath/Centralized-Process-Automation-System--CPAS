<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model{

    protected $table = 'employee';
    protected $allowedFields = ['FirstName','LastName','Email','Password','userclass','updated_at'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    protected function beforeInsert(array $data){
        $data = $this->passwordHash($data);
        return $data;
    }
    protected function beforeUpdate(array $data){
        $data = $this->passwordHash($data);
        return $data;
    }
    protected function passwordHash(array $data){
        if(isset($data['data']['Password']))
        $data['data']['Password'] =  password_hash($data['data']['Password'], PASSWORD_DEFAULT);
        return $data;

    }
}