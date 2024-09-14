<?php namespace App\Models;

use CodeIgniter\Model;

class CommentReplyLogModel extends Model{

    protected $table = 'commentreplylog';
    protected $primaryKey = 'id';
    protected $allowedFields = ['comment_id','updated_by','updated_at','status'];

    public function selectData( $table , $where = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where);
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResult();


    }
}