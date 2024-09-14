<?php
namespace App\Validation;

use App\Models\CheckingItem;
use App\Models\AuditCreationModel;
use PhpParser\Builder\Class_;

Class AuditCreationValidation {
    public function auditStartDateCheck(string $str, string $fields, array $data){            
        if($data['examinstartdate']>$data['examinendtdate'])
        return false;   
    }

    public function coveredPeriodCheck(string $str, string $fields, array $data){            
        if($data['coveredstartdate']>$data['coveredenddate'])
        return false;   
    }
    public function checkAuditCreationID(string $str, string $fields, array $data){
        $model = new AuditCreationModel();
        $auditid = $model->where('auditid',$data['auditid'])
                            ->first();
            
        if($auditid)
        return false;   
    }

}