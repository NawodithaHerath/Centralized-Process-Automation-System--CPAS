<?php

namespace App\Controllers;
use App\Models\EntityAdding;
use App\Models\AuditCreationModel;

use function PHPUnit\Framework\isEmpty;

class Admin extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function entityadding()
    {
        $data=[];

        helper(['form']);

        if($this->request->getMethod()== 'post'){
            $rules = [
                'entityid'=>[
                   'rules'=> 'required|min_length[3]|max_length[4]|entityAvailability[entityid]',
                   'label'=> 'entityid',
                   'errors'=> [
                        'required'=>'Entity ID should be added',
                        'min_length'=>'Entity ID should be three digit',
                        'max_length'=>'Entity ID should be three digit',
                        'entityAvailability'=> 'Entity ID should be new one'
                   ]
                ],
                'entitytype'=>[
                    'rules'=> 'in_list[branch,department]',
                    'label'=> 'Entity type',
                    'errors'=> [
                         'in_list'=>'Entity type should be Selected',
                    ]
                ],
                'entityname'=>[
                    'rules'=> 'required|min_length[3]|entityNameExist[entityname]',
                    'label'=> 'Entity Name',
                    'errors'=> [
                            'min_length'=>'Proper Entity Name Should be endted',
                            'entityNameExist'=>'Entity Name already exists'
                    ]
                ],
                'manager'=>[
                    'rules'=> 'required|min_length[4]|max_length[5]|checkManagerAvailable[manager]|alreadyManager[manager]|alreadyManagerAsAssisnt[manager]',
                    'label'=> 'Manager ID',
                    'errors'=> [
                                'required'=>'Manager ID should be added',
                                 'min_length'=>'Manager ID should be four digit',
                                 'max_length'=>'Manager ID should be four digit',
                                 'checkManagerAvailable'=> 'Manager ID should be existance one',
                                 'alreadyManager' => 'Manager ID already exist as a manager',
                                 'alreadyManagerAsAssisnt' => 'Manager ID already exist as an assistant manager'
                            ]
                         ],  
                'assistantmanager'=>[
                    'rules'=> 'required|min_length[4]|max_length[5]|checkAssistManagerAvailable[assistantmanager]|alreadyAssistManager[assistantmanager]|alreadyAssisntAsManager[assistantmanager]',
                    'label'=> 'Asistant Manager ID',
                    'errors'=> [
                                'required'=>'Assistant Manager ID should be added',
                                'min_length'=>'Assistant Manager ID should be four digit',
                                'max_length'=>'Assistant Manager ID should be four digit',
                                'checkAssistManagerAvailable'=> 'Assistant Manager ID should be existance one',
                                'alreadyAssistManager' => 'Assistant Manager ID already exist as a manager',
                                'alreadyAssisntAsManager' => 'Assistant Manager ID already exist as an assistant manager'
                                ]
                 ],  

                ];
                if(! $this->validate($rules)){
                    $data['validation'] = $this->validator;
                    echo view('templates/header',$data);
                    echo view('Admin/entityadding');
                    echo view('templates/footer');

                }else{

                    $data = [
                        'entityid' => $this->request->getPost('entityid'),
                        'entitytype' => $this->request->getPost('entitytype'),
                        'entityname' => $this->request->getPost('entityname'),
                        'manager' => $this->request->getPost('manager'),
                        'assistantmanager' => $this->request->getPost('assistantmanager'),
                        'created_by' => session()->get('EmpNo'),  
                    ];
                        $model = new EntityAdding();
                        $model->save($data);
                        $session = session();
                        $session->setFlashdata('success','Successfully entity was added');
                        return redirect()->to('http://localhost/Development/development/public/entityadding');
                    }
        }else{
            $db = db_connect(); 
            $allEntity  = new EntityAdding();
            $data['allcreatedentity'] = $allEntity->selectDatajoin();

            echo view('templates/header',$data);
            echo view('Admin/entityadding');
            echo view('Admin/allEntity');
            echo view('templates/footer');
        }

    }
    public function entityEditing($entityid)
    {
        $data=[];

        helper(['form']);

        if($this->request->getMethod()== 'post'){
            $rules = [
                'entityid'=>[
                   'rules'=> 'required|min_length[3]|max_length[4]',
                   'label'=> 'entityid',
                   'errors'=> [
                        'required'=>'Entity ID should be added',
                        'min_length'=>'Entity ID should be three digit',
                        'max_length'=>'Entity ID should be three digit',
                   ]
                ],
                'entitytype'=>[
                    'rules'=> 'in_list[branch,department]',
                    'label'=> 'Entity type',
                    'errors'=> [
                         'in_list'=>'Entity type should be Selected',
                    ]
                ],
                'entityname'=>[
                    'rules'=> 'required|min_length[3]',
                    'label'=> 'Entity Name',
                    'errors'=> [
                            'min_length'=>'Proper Entity Name Should be endted',
                    ]
                ],
                'manager'=>[
                    'rules'=> 'required|min_length[4]|max_length[5]|checkManagerAvailable[manager]',
                    'label'=> 'Manager ID',
                    'errors'=> [
                                'required'=>'Manager ID should be added',
                                 'min_length'=>'Manager ID should be four digit',
                                 'max_length'=>'Manager ID should be four digit',
                                 'checkManagerAvailable'=> 'Manager ID should be existance one'
                            ]
                         ],  
                'assistantmanager'=>[
                    'rules'=> 'required|min_length[4]|max_length[5]|checkAssistManagerAvailable[assistantmanager]',
                    'label'=> 'Asistant Manager ID',
                    'errors'=> [
                                'required'=>'Assistant Manager ID should be added',
                                'min_length'=>'Assistant Manager ID should be four digit',
                                'max_length'=>'Assistant Manager ID should be four digit',
                                'checkAssistManagerAvailable'=> 'Assistant Manager ID should be existance one',
                                ]
                 ],  
                ];
                if(! $this->validate($rules)){
                    $data['validation'] = $this->validator;
                    $allEntity  = new EntityAdding();
                    $data['entitydetails'] =  $allEntity->where('entityid',$entityid)->first();
                    echo view('templates/header',$data);
                    echo view('Admin/entityEditing');
                    echo view('templates/footer');

                }else{

                    $data = [
                        'entityid' => $this->request->getPost('entityid'),
                        'entitytype' => $this->request->getPost('entitytype'),
                        'entityname' => $this->request->getPost('entityname'),
                        'manager' => $this->request->getPost('manager'),
                        'assistantmanager' => $this->request->getPost('assistantmanager'),
                        'created_by' => session()->get('EmpNo'),  
                    ];
                        $model = new EntityAdding();
                        $model->where('entityid',$entityid)->set($data)->update();

                        $session = session();
                        $session->setFlashdata('success','Successfully entity was edited');
                        return redirect()->to('http://localhost/Development/development/public/entityadding');
                    }
        }else{

            $allEntity  = new EntityAdding();
            $data['entitydetails'] =  $allEntity->where('entityid',$entityid)->first();
            
            echo view('templates/header',$data);
            echo view('Admin/entityEditing');
            echo view('templates/footer');
        }

    }

    public function entityDelete($entityid)
    {
        $model = new AuditCreationModel(); 
        $auditid = $model->where('entityid',$entityid)->first();
        if(isset($auditid)){
            $session = session();
            $session->setFlashdata('Unable','Already, Audit has been created for this entity');
            return redirect()->to('http://localhost/Development/development/public/entityadding');
            
        }else{
            $session = session();
            $session->setFlashdata('success','Entity was deleted successfully');
            return redirect()->to('http://localhost/Development/development/public/entityadding');
        }

    }

}
