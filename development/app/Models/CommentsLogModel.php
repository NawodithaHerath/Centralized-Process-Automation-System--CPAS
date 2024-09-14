<?php namespace App\Models;

use CodeIgniter\Model;

class CommentsLogModel extends Model{

    protected $table = 'commentlog';
    protected $primaryKey = 'id';
    protected $allowedFields = ['comment_id','status','updated_by','updated_at'];

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
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResultArray();

    }
}