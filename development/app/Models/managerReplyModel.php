<?php namespace App\Models;

use CodeIgniter\Model;

class managerReplyModel extends Model{

    protected $table = 'managementreply';
    protected $primaryKey = 'id';
    protected $allowedFields = ['comment_id','responseofficer','assignedDate','ManagerResponse','replyDetails','responsiblePerson','coveredenddate','rectification','rectifiedDate','rectificationAction','rectificationTargetDate','HR','IT','Execution','Process','Policies','rootcauseDetails','replyStatus','replystatusupdate_at','created_by','created_at','updated_by','updated_at'];

    public function selectData( $table , $where = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where);
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResult();
    }

    public function managmentReplyStatus( $table,$auditid){
        $builder = $this->db->table($table);
        $builder->select('managementreply.comment_id, managementreply.replyStatus');
        $builder->like('comment_id',$auditid,'both');
        // $builder->join('employee', 'employee.EmpNo = auditteammember.empno');
        // $builder->where($where);
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResultArray();
    }
    
}