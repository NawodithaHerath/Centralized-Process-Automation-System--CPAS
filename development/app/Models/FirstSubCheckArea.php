<?php namespace App\Models;

use CodeIgniter\Model;

class FirstSubCheckArea extends Model{

    protected $table = 'firstsubcheckarea';
    protected $primaryKey = 'id';
    protected $allowedFields = ['firstsubarea_id','firstsubarea_description','mainarea_id','created_by','updated_by'];

    public function reportAdminFirstAreaCheck($table,$where = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where);
        $builder->join('employee','employee.EmpNo = firstsubcheckarea.created_by');
        $query = $builder->get();
        //return $query->getResult();
        return $query->getResultArray();
    }

}