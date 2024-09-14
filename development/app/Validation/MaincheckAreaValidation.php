<?php
namespace App\Validation;

use App\Models\MainCheckArea;
use PhpParser\Builder\Class_;

Class MaincheckAreaValidation {
    public function validateMainArea(string $str, string $fields, array $data){
        $model = new MainCheckArea();
        $checklist_id = $model->where('mainarea_id',$data['mainarea_id'])
                            ->first();
            
        if($checklist_id)
        return false;   
    }
    public function validateMainAreaAvailable(string $str, string $fields, array $data){
        $model = new MainCheckArea();
        $checklist_id = $model->where('mainarea_id',$data['mainarea_id'])
                            ->first();
            
        if(!$checklist_id)
        return false;   
    }

}