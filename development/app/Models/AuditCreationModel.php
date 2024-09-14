<?php namespace App\Models;

use CodeIgniter\Model;

class AuditCreationModel extends Model{

    protected $table = 'auditcreation';
    protected $primaryKey = 'id';
    protected $allowedFields = ['auditid','audityear','examinstartdate','examinendtdate','coveredstartdate','coveredenddate','audit_category','checklist_id','status','totalRisk','branchRiskGrade','auditstatusupdate_at','entityid','audit_type','reviewer','created_by'];

    public function selectData( $table , $where = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where);
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResult();


    }
    public function selectDataAudit( $table,$where = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where);
        $builder->join('entity', 'entity.entityid = auditcreation.entityid');
        $builder->join('employee','employee.EmpNo = auditcreation.reviewer');
        $builder->orderBy('auditid','ASC');
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResultArray();

    }

    public function selectDataAllAudit( $table){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->join('entity', 'entity.entityid = auditcreation.entityid');
        $builder->join('employee','employee.EmpNo = auditcreation.reviewer');
        $builder->orderBy('auditid','ASC');
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResultArray();

    }
    public function selectDataAllstatus( $table){
        $builder = $this->db->table($table);
        $builder->select("status");
        $builder->orderBy('status','ASC');
        $builder->distinct();
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResultArray();

    }

}