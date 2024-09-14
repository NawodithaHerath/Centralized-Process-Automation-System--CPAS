<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\AuditReplyingModel;

class CommentsModel extends Model{

    protected $table = 'comments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['comment_id','auditid','checkingitem_id','samplesize','numberofissues','sampledetails','likelihood','significance','overallrisk','commentheading','commentdetails','potentialimpact','recommendation','targetdate','status','statusupdate_at','created_by','created_at','updated_by','updated_at'];

    public function selectData( $table , $where = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where);
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResult();


    }

    public function auditongoingall ( $where1 = array()){
        $builder = $this->db->table('comments');
        $builder->select("*");
        $builder->where($where1);
        $builder->join('employee', 'employee.EmpNo = comments.created_by');
        $builder->orderBy('statusupdate_at','DESC');
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResultArray();

    }

    public function auditongoingcommentreply ( $where1 = array()){
        $builder = $this->db->table('comments');
        $builder->select("*");
        $builder->where($where1);
        $builder->where('status','Forwarded to branch');
        $builder->join('managementreply', 'comments.comment_id = managementreply.comment_id');
        $builder->orderBy('comments.targetdate','ASC');
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResultArray();

    }
    public function auditongoingcommentreplyassign ( $where1 = array()){

        // $auditid = $where1['auditid'];
        // $replyavailability = new managerReplyModel();
        // $availablereply = $replyavailability->like(['comment_id'=>$auditid])->first();

        // if(isset($availablereply)){
        //     $builder = $this->db->table('comments');
        //     $builder->select("*");
        //     $builder->where($where1);
        //     $builder->where('status','Forwarded to branch');
        //     $builder->join('managementreply', 'comments.comment_id = managementreply.comment_id');
        //     $builder->join('employee', 'employee.EmpNo = managementreply.responseofficer');
        //     $builder->orderBy('comments.targetdate','ASC');
        //     $query = $builder->get();
        //     // echo $this->db->getLastQuery();
        //     // // die();
        //     return $query->getResultArray();
        // }else{
            $builder = $this->db->table('comments');
            $builder->select("*");
            $builder->where($where1);
            $builder->where('status','Forwarded to branch');
            $builder->orderBy('comments.comment_id','ASC');
            $query = $builder->get();
            // echo $this->db->getLastQuery();
            // // die();
            return $query->getResultArray();

        // }

    }
    public function auditongoingcommentreplyassignonly ($where1 = array(),$where2 = array()){
        $builder = $this->db->table('comments');
        $builder->select("*");
        $builder->where($where1);
        $builder->where($where2);
        $builder->where('status','Forwarded to branch');
        $builder->orWhere('status','Recieved from branch');
        $builder->join('managementreply', 'comments.comment_id = managementreply.comment_id');
        $builder->join('employee', 'employee.EmpNo = managementreply.responseofficer');
        $builder->orderBy('comments.targetdate','ASC');
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResultArray();

    }

    public function auditongoingcommentreplyreview ( $where1 = array()){
        $builder = $this->db->table('comments');
            $builder->select("*");
            $builder->where($where1);
            // $builder->where('replyStatus','Forwarded to branch');
            $builder->groupStart();
            $builder->orWhere('replyStatus','Adding Reply');
            $builder->orWhere('replyStatus','Submit for review');
            $builder->orWhere('replyStatus','Reject');
            $builder->orWhere('replyStatus','Submitted to Audit');
            $builder->orWhere('replyStatus','Reviewing Reply');
            $builder->orWhere('replyStatus','Accepted management reply');
            $builder->orWhere('replyStatus','Accepted management reply by audit');
            $builder->groupEnd();
            $builder->join('managementreply', 'comments.comment_id = managementreply.comment_id');
            $builder->join('employee', 'employee.EmpNo = managementreply.responseofficer');
            $builder->orderBy('comments.comment_id','ASC');
            $query = $builder->get();
        //     // echo $this->db->getLastQuery();
        //     // // die();
            return $query->getResultArray();
    }

    public function CommentStatus( $table,$auditid){
        $builder = $this->db->table($table);
        $builder->select('comments.comment_id, comments.status');
        $builder->like('comment_id',$auditid,'both');
        // $builder->join('employee', 'employee.EmpNo = auditteammember.empno');
        // $builder->where($where);
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResultArray();
    }

    public function CommentHighCount( $table,$auditid){
        $builder = $this->db->table($table);
        $builder->select('comments.comment_id');
        $builder->where('auditid',$auditid);
        $builder->where('overallrisk','High');
        return  $builder->countAllResults();

    }

    public function CommentMediumCount( $table,$auditid){
        $builder = $this->db->table($table);
        $builder->select('comments.comment_id');
        $builder->where('auditid',$auditid);
        $builder->where('overallrisk','Medium');
        return  $builder->countAllResults();
 
    }
    public function CommentLowCount( $table,$auditid){
        $builder = $this->db->table($table);
        $builder->select('comments.comment_id');
        $builder->where('auditid',$auditid);
        $builder->where('overallrisk','Low');
        return  $builder->countAllResults();
 
    }
}