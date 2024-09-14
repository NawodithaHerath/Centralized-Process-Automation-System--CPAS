<?php namespace App\Models;

use CodeIgniter\Model;

class CheckingItem extends Model{

    protected $table = 'checkingitem';
    protected $primaryKey = 'id';
    protected $allowedFields = ['checkingitem_id','checkingitem_description','firstsubarea_id','created_by','updated_by'];

    public function reportAdminCheckingItem($table,$where = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where);
        $builder->join('employee','employee.EmpNo = checkingitem.created_by');
        $query = $builder->get();
        //return $query->getResult();
        return $query->getResultArray();
    }
}