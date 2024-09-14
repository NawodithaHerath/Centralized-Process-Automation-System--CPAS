<?php namespace App\Models;

use CodeIgniter\Model;

class EntityAdding extends Model{

    protected $table = 'entity';
    protected $primaryKey = 'id';
    protected $allowedFields = ['entityid','entitytype','entityname','manager','assistantmanager','created_by',];

    public function selectData($where = array()){
        $builder = $this->db->table('entity');
        $builder->select("*");
        $builder->where($where);
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResult();

    }

    public function selectDatajoin(){
        $builder = $this->db->table('entity');
        $builder->select("*");
        $builder->join('employee', 'employee.EmpNo = entity.manager');
        // $builder->join('auditcreation','auditcreation.auditid = auditteammember.auditid');
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        // // die();
        return $query->getResultArray();
    }
}