<?php namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class CustomModel{

    protected $db;

    public function __construct(ConnectionInterface &$db){
        $this->db =&  $db;
    }

    function all(){
       return $this->db->table('auditcreation')->get()->getResult();
    }

    
    function where(){
        return $this->db->table('posts')
                        ->where(['post_id >'=> 90])
                        ->where(['post_id <'=> 95])
                        ->orderBy('post_id' , 'acs')
                        ->get()
                        ->getResultArray();
    }

    function joinauditcreation(){
        return $this->db->table('auditcreation')
                        ->join('entity','auditcreation.entityid = entity.entityid')
                        ->join('checklist','auditcreation.checklist_id = checklist.checklist_id','inner')
                        ->join('employee','auditcreation.reviewer = employee.EmpNo','inner')
                        // ->orderBy('entityid','acs')
                        ->get()
                        ->getResultArray();
    }
    function joinmulty($table1,$table2){
        return $this->db->table($table1)
                        ->join($table2,'auditcreation.entityid = entity.entityid')
                        // ->join('checklist','auditcreation.checklist_id = checklist.checklist_id','inner')
                        // ->orderBy('entityid','acs')
                        ->get()
                        ->getResultArray();
    }

    function joinauditiddetails($auditid){

        return $this->db->table('auditcreation')
                        ->where(['auditid'=> $auditid])
                        ->join('entity','auditcreation.entityid = entity.entityid')
                        ->join('checklist','auditcreation.checklist_id = checklist.checklist_id','inner')
                        ->join('employee','auditcreation.reviewer = employee.EmpNo','inner')
                        // ->orderBy('entityid','acs')
                        ->limit(1)
                        ->get()
                        ->getResultArray();
    }
}