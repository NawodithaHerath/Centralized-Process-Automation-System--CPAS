<?php namespace App\Models;

use CodeIgniter\Model;

class AuditTeamMember extends Model{

    protected $table = 'auditteammember';
    protected $primaryKey = 'id';
    protected $allowedFields = ['auditassignedid','empno','auditid','created_by'];


    public function selectDatajoin($where){
        $builder = $this->db->table('auditteammember');
        $builder->select("*");
        $builder->where(['auditid'=>$where] );
        $builder->join('employee', 'employee.EmpNo = auditteammember.empno');
        // $builder->join('auditcreation','auditcreation.auditid = auditteammember.auditid');
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResultArray();
    }

    public function auditteamassignment( $table,$year){
        $builder = $this->db->table($table);
        $builder->select('auditcreation.auditid, entity.entityname, auditcreation.examinstartdate, employee.FirstName,employee.LastName,employee.Email,auditteammember.auditassignedid');
        $builder->like('auditassignedid',$year,'both');
        $builder->join('employee', 'employee.EmpNo = auditteammember.empno');
        // $builder->where($where);
        $builder->join('auditcreation', 'auditteammember.auditid = auditcreation.auditid');
        $builder->join('entity', 'entity.entityid = auditcreation.entityid');
        $query = $builder->get();
        return $query->getResult();
    }

    public function auditteamassignmentFilter( $table,$where1 = array()){
        $builder = $this->db->table($table);
        $builder->select('auditcreation.auditid, entity.entityname, auditcreation.examinstartdate, employee.FirstName,employee.LastName,employee.Email,auditteammember.auditassignedid');
        $builder->where($where1);
        $builder->join('employee', 'employee.EmpNo = auditteammember.empno');
        // $builder->where($where);
        $builder->join('auditcreation', 'auditteammember.auditid = auditcreation.auditid');
        $builder->join('entity', 'entity.entityid = auditcreation.entityid');
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResult();
    }

    public function auditteamassignmentflteremployee( $table){
        $builder = $this->db->table($table);
        $builder->select('auditteammember.empno,employee.FirstName,employee.LastName');
        $builder->join('employee','employee.EmpNo = auditteammember.empno');
        $builder->distinct();
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResultArray();
    }
    

    public function auditteamassignmentExcel( $table,$year){
        $builder = $this->db->table($table);
        $builder->select('auditcreation.auditid, entity.entityname, auditcreation.examinstartdate, employee.FirstName,employee.LastName,employee.Email,auditteammember.auditassignedid');
        $builder->like('auditassignedid',$year,'both');
        $builder->join('employee', 'employee.EmpNo = auditteammember.empno');
        // $builder->where($where);
        $builder->join('auditcreation', 'auditteammember.auditid = auditcreation.auditid');
        $builder->join('entity', 'entity.entityid = auditcreation.entityid');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function auditteamassignmentFilterExcel( $table,$where1 = array()){
        $builder = $this->db->table($table);
        $builder->select('auditcreation.auditid, entity.entityname, auditcreation.examinstartdate, employee.FirstName,employee.LastName,employee.Email,auditteammember.auditassignedid');
        $builder->where($where1);
        $builder->join('employee', 'employee.EmpNo = auditteammember.empno');
        // $builder->where($where);
        $builder->join('auditcreation', 'auditteammember.auditid = auditcreation.auditid');
        $builder->join('entity', 'entity.entityid = auditcreation.entityid');
        $query = $builder->get();
        return $query->getResultArray();
    }
}

