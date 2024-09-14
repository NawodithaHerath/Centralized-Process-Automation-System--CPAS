<?php
namespace App\Validation;

use App\Models\CheckingItem;
use App\Models\AuditCreationModel;
use PhpParser\Builder\Class_;

Class CommentReplyingValidation {

    public function rectifieddatecheck(string $str, string $fields, array $data){  
        $currentDate = date("Y-m-d");
        if($data['rectifiedDate']>$currentDate)
        return false;   
    }

    public function rectificationtargetdatecheck(string $str, string $fields, array $data){ 
        $currentDate = date("Y-m-d");
        if($data['rectificationTargetDate'] < $currentDate )
        return false;   
    }
    public function rootcausecheck(string $str, string $fields, array $data){ 
        if(empty($data['HR']) && empty($data['IT']) && empty($data['Execution']) && empty($data['Process']) && empty($data['Policies']) )
        return false;   
    }

    public function rectificationstatuscheck(string $str, string $fields, array $data){ 
        
        if($data['rectification'] == 'Rectified'){
            if($data['rectifiedDate'] == "" || $data['rectificationAction']==""){
                return false; 
                }
            }elseif($data['rectification'] == 'Not Rectified'){
                if($data['rectificationTargetDate'] == "" ){
                    return false; 
                }
            }
    }


}