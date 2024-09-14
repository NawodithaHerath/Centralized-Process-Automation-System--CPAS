<?php
namespace App\Validation;

use App\Models\CheckingItem;
use PhpParser\Builder\Class_;

Class CheckingItemValidation {
    public function checkingItem(string $str, string $fields, array $data){
        $model = new CheckingItem();
        $firstsubarea_id = $model->where('checkingitem_id',$data['checkingitem_id'])
                            ->first();
            
        if($firstsubarea_id)
        return false;   
    }
    public function checkingItemAvailable(string $str, string $fields, array $data){
        $model = new CheckingItem();
        $firstsubarea_id = $model->where('checkingitem_id',$data['checkingitem_id'])
                            ->first();
            
        if(!$firstsubarea_id)
        return false;   
    }

}