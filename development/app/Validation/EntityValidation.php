<?php
namespace App\Validation;

use App\Models\EntityAdding;
use App\Models\UserModel;
use PhpParser\Builder\Class_;

Class EntityValidation {

    public function checkManagerAvailable(string $str, string $fields, array $data){
        $model = new UserModel();
        $employeno = $model->where('EmpNo',$data['manager'])
                            ->first();
            
        if(!$employeno)
        return false;   
    }
    public function checkAssistManagerAvailable(string $str, string $fields, array $data){
        $model = new UserModel();
        $employeno = $model->where('EmpNo',$data['assistantmanager'])
                            ->first();
            
        if(!$employeno)
        return false;   
    }
    public function entityAvailability(string $str, string $fields, array $data){
        $model = new EntityAdding();
        $entityid = $model->where('entityid',$data['entityid'])
                            ->first();
            
        if($entityid)
        return false;   
    }

    public function alreadyManager(string $str, string $fields, array $data){
        $model = new EntityAdding();
        $entityid = $model->where('manager',$data['manager'])
                            ->first();
            
        if($entityid)
        return false;   
    }
    public function alreadyAssistManager(string $str, string $fields, array $data){
        $model = new EntityAdding();
        $entityid = $model->where('assistantmanager',$data['assistantmanager'])
                            ->first();
            
        if($entityid)
        return false;   
    }
    public function alreadyManagerAsAssisnt(string $str, string $fields, array $data){
        $model = new EntityAdding();
        $entityid = $model->where('assistantmanager',$data['manager'])
                            ->first();
            
        if($entityid)
        return false;   
    }
    public function alreadyAssisntAsManager(string $str, string $fields, array $data){
        $model = new EntityAdding();
        $entityid = $model->where('manager',$data['assistantmanager'])
                            ->first();
            
        if($entityid)
        return false;   
    }
    public function entityNameExist(string $str, string $fields, array $data){
        $model = new EntityAdding();
        $entityid = $model->where('entityname',$data['entityname'])
                            ->first();
            
        if($entityid)
        return false;   
    }

}