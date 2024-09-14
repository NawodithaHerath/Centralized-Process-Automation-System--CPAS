<?php namespace App\Models;

use CodeIgniter\Model;

class ChecklistModel extends Model{

    protected $table = 'checklist';
    protected $primaryKey = 'id';
    protected $allowedFields = ['checklist_id','audit_category','audit_type','audit_area','checklist_description','created_by','updated_by','updated_at'];

    public function selectData( $table , $where = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where);
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResult();

    }
    public function selectDatajoin( $table , $where = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where);
        $builder->join('entity', 'entity.entityid = auditcreation.entityid');
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResultArray();

    }

    public function selectDataAudit( $table , $where = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where);
        $builder->join('entity', 'entity.entityid = auditcreation.entityid');
        $builder->join('employee','employee.EmpNo = auditcreation.reviewer');
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResultArray();

    }

    public function reportAdminAllChecklist($table){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->join('employee','employee.EmpNo = checklist.created_by');
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResultArray();
    }
}