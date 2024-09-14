<?php namespace App\Models;

use CodeIgniter\Model;

class AuditReplyingModel extends Model{

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


    public function allAuditNeedReplying( $table , $where1 = array(),$where2 = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where1);
        $builder->where($where2);
        $builder->groupStart();
        $builder->where('status','started');
        $builder->orWhere('status','replied');
        $builder->groupEnd();
        // $builder->join('entity', 'entity.entityid = auditcreation.entityid');
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResult();

    }

    public function allCommentNeedReplying( $table , $where1 = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where1);
        $builder->groupStart();
        $builder->where('status','started');
        $builder->orWhere('status','replied');
        $builder->groupEnd();
        $builder->join('entity', 'entity.entityid = auditcreation.entityid');
        $query = $builder->get();
        return $query->getResultArray();
        // // die();
        // return $query->getResult();
    }

    public function auditongoingcommentreplyassignonly ($where1 = array(),$where2 = array()){
        $builder = $this->db->table('comments');
        $builder->select("*");
        $builder->where($where1);
        $builder->where($where2);
        $builder->groupStart();
        $builder->where('status','Forwarded to branch');
        $builder->orWhere('status','Recieved from branch');
        $builder->groupEnd();
        $builder->join('managementreply', 'comments.comment_id = managementreply.comment_id');
        $builder->join('employee', 'employee.EmpNo = managementreply.responseofficer');
        $builder->orderBy('comments.targetdate','ASC');
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResultArray();

    }

    public function allAuditReplyingCompleted( $table , $where1 = array(),$where2 = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where1);
        $builder->where($where2);
        $builder->groupStart();
        $builder->where('status','completed');
        $builder->groupEnd();
        // $builder->join('entity', 'entity.entityid = auditcreation.entityid');
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResult();

    }

    public function allCommentReplyCompleted( $table , $where1 = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where1);
        $builder->groupStart();
        $builder->where('status','completed');
        $builder->groupEnd();
        $builder->join('entity', 'entity.entityid = auditcreation.entityid');
        $query = $builder->get();
        return $query->getResultArray();
        // // die();
        // return $query->getResult();
    }

}
