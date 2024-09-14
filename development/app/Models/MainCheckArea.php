<?php namespace App\Models;

use CodeIgniter\Model;

class MainCheckArea extends Model{

    protected $table = 'maincheckarea';
    protected $primaryKey = 'id';
    protected $allowedFields = ['mainarea_id','mainarea_description','checklist_id','created_by','updated_by','created_at'];


    public function reportAdminMainCheck($table,$where = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where);
        $builder->join('employee','employee.EmpNo = maincheckarea.created_by');
        $query = $builder->get();
        //return $query->getResult();
        return $query->getResultArray();
    }

    public function reportAdminMainCheckentirechecklist($table,$where = array()){
        $builder = $this->db->table($table);
        $builder->select("*");
        $builder->where($where);
        $builder->join('firstsubcheckarea','firstsubcheckarea.mainarea_id = maincheckarea.mainarea_id','left outer');
        $builder->join('checkingitem','firstsubcheckarea.firstsubarea_id = checkingitem.firstsubarea_id','left outer');
        $query = $builder->get();
        // return $query->getResult();
        return $query->getResultArray();
    }
}