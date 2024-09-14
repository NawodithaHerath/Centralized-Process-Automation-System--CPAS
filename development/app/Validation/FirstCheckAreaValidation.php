<?php
namespace App\Validation;

use App\Models\FirstSubCheckArea;
use PhpParser\Builder\Class_;

Class FirstCheckAreaValidation {
    public function FirstCheckArea(string $str, string $fields, array $data){
        $model = new FirstSubCheckArea();
        $firstsubarea_id = $model->where('firstsubarea_id',$data['firstsubarea_id'])
                            ->first();
            
        if($firstsubarea_id)
        return false;   
    }
    public function FirstCheckAreaAvailable(string $str, string $fields, array $data){
        $model = new FirstSubCheckArea();
        $firstsubarea_id = $model->where('firstsubarea_id',$data['firstsubarea_id'])
                            ->first();
            
        if(!$firstsubarea_id)
        return false;   
    }

}