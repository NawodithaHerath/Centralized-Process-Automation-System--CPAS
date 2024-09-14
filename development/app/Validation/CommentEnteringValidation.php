<?php
namespace App\Validation;

use App\Models\CheckingItem;
use App\Models\AuditCreationModel;
use PhpParser\Builder\Class_;

Class CommentEnteringValidation {
    public function samplecountcheck(string $str, string $fields, array $data){            
        if($data['samplesize']<$data['numberofissues'])
        return false;   
    }

    public function targetdateFuturedate(string $str, string $fields, array $data){ 
        $currentDate = date("Y-m-d");
        if($data['targetdate'] < $currentDate )
        return false;   
    }



}