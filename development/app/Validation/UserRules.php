<?php
namespace App\Validation;

use App\Models\UserModel;
use PhpParser\Builder\Class_;

Class UserRules {
    public function validateUser(string $str, string $fields, array $data){
        $model = new UserModel();
        $user = $model->where('Email',$data['Email'])
                            ->first();
            
        if(!$user)
        return false;
        
        return password_verify($data["Password"],$user['Password']);
    }

}