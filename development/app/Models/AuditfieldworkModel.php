<?php namespace App\Models;

use CodeIgniter\Model;

class AuditfieldworkModel extends Model{

    protected $table = 'auditcreation';
    protected $primaryKey = 'id';
    protected $allowedFields = ['auditid','audityear','examinstartdate','examinendtdate','coveredstartdate','coveredenddate','audit_category','checklist_id','status','entityid','audit_type','reviewer','created_by'];

    public function selectData( $table , $where = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where);
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResult();

    }

    public function assignedAuditNotStart( $table , $where1 = array(),$where2 = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where1);
        $builder->where($where2);
        $builder->where('status','created');
        $builder->join('auditteammember', 'auditteammember.auditid = auditcreation.auditid');
        $builder->join('entity', 'entity.entityid = auditcreation.entityid');
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResult();

    }

    public function assignedAuditStart( $table , $where1 = array(),$where2 = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where1);
        $builder->where($where2);
        $builder->groupStart();
        $builder->where('status','started');
        $builder->groupEnd();
        // $builder->where(['status'=>'']);
        $builder->join('auditteammember', 'auditteammember.auditid = auditcreation.auditid');
        $builder->join('entity', 'entity.entityid = auditcreation.entityid');
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResult();

    }

    public function assignedAuditStartReview( $table , $where1 = array(),$where2 = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where1);
        $builder->where($where2);
        $builder->groupStart();
        $builder->where('status','started');
        $builder->groupEnd();
        // $builder->where(['status'=>'']);
        $builder->join('employee', 'employee.EmpNo = auditcreation.reviewer');
        $builder->join('entity', 'entity.entityid = auditcreation.entityid');
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResult();
    }

    public function assignedAuditReplyReview( $table , $where1 = array(),$where2 = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where1);
        $builder->where($where2);
        $builder->where('status','replied');
        // $builder->where(['status'=>'']);
        $builder->join('employee', 'employee.EmpNo = auditcreation.reviewer');
        $builder->join('entity', 'entity.entityid = auditcreation.entityid');
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResult();
    }

    public function assignedAuditReplyCommentEditAllAudit( $table , $where1 = array(),$where2 = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where1);
        $builder->where($where2);
        $builder->groupStart();
        $builder->orWhere('status','replied');
        $builder->groupEnd();
        // $builder->where(['status'=>'']);
        $builder->join('auditteammember', 'auditteammember.auditid = auditcreation.auditid');
        $builder->join('entity', 'entity.entityid = auditcreation.entityid');
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResult();
    }

    public function CompletedAudit( $table , $where1 = array(),$where2 = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where1);
        $builder->where('status','completed');
        // $builder->where(['status'=>'']);
        $builder->join('employee', 'employee.EmpNo = auditcreation.reviewer');
        $builder->join('entity', 'entity.entityid = auditcreation.entityid');
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResult();
    }

}
