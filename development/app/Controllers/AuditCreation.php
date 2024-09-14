<?php

namespace App\Controllers;

use App\Models\ChecklistModel;
use App\Models\EntityAdding;
use App\Models\AuditCreationModel;
use App\Models\CustomModel;
use App\Models\UserModel;

class AuditCreation extends BaseController
{
    protected $checklistid;
    protected $entityid;
    protected $createdaudit;

    public function __construct()
    {
        $this->checklistid = new ChecklistModel();
        $this->entityid = new EntityAdding();
        $db = db_connect(); 
        $this->createdaudit = new CustomModel($db);
    }

    public function index()
    {
        $data = [];
        helper(['form']);
        if($this->request->getMethod()== 'post'){

            $rules = [
                'audityear'=>[
                   'rules'=> 'required',
                   'label'=> ' Audit year',
                   'errors'=> [
                        'required'=>'Audit year should be added',
                   ]
                ],
                'examinstartdate'=>[
                    'rules'=> 'required|auditStartDateCheck[examinstartdate,examinendtdate]',
                    'label'=> 'Examin Start date',
                    'errors'=> [
                         'required'=>'Examine start dates hould be added',
                         'auditStartDateCheck'=>' Examine start date should be before date of examine end date'
                    ]
                 ],
                 'examinendtdate'=>[
                    'rules'=> 'required|',
                    'label'=> 'Examin end date',
                    'errors'=> [
                         'required'=>'Examine end date should be added',
                    ]
                 ],
                 'coveredstartdate'=>[
                    'rules'=> 'required|coveredPeriodCheck[coveredstartdate,coveredenddate]',
                    'label'=> 'Covered period start date',
                    'errors'=> [
                         'required'=>'Examine start dates hould be added',
                         'coveredPeriodCheck'=>' Covered Period start date should be before date of covered period end date'
                    ]
                 ],
                 'coveredenddate'=>[
                    'rules'=> 'required|',
                    'label'=> 'Examin end date',
                    'errors'=> [
                         'required'=>'Examine end date should be added',
                    ]
                 ],
                 'audit_category'=>[
                    'rules'=> 'required|in_list[branch,department]',
                    'label'=> 'Audit Category',
                    'errors'=> [
                         'required'=>'Audit Category should be selected',
                         'in_list'=>'Entity type should be Selected'
                    ]
                 ],
                 'checklist_id'=>[
                    'rules'=> 'required',
                    'label'=> 'Audit Category',
                    'errors'=> [
                         'required'=>'Checklist Id should be selected',
                    ]
                 ],
                 'entityid'=>[
                    'rules'=> 'required',
                    'label'=> 'entityid',
                    'errors'=> [
                         'required'=>'Entity should be selected',
                    ]
                 ],
                 'reviewer'=>[
                    'rules'=> 'required',
                    'label'=> 'reviewer',
                    'errors'=> [
                         'required'=>'Reviewer should be selected',
                    ]
                 ],
            ];           
            if(! $this->validate($rules)){

                $db = db_connect(); 
                $model = new CustomModel($db);
                $data['allcreatedaudit'] =($model->joinauditcreation());

                $model2 = new UserModel(); 
                $data['iaduser'] = $model2->where('entityid','859')->findall();


                $data['validation'] = $this->validator;
                echo view('templates/header');
                echo view('auditcreation/creationaudit',$data);
                echo view('auditcreation/createdauditsummary');
                echo view('templates/footer');
            }else{

                $auditype = $this->checklistid->where('checklist_id',$this->request->getPost('checklist_id'))->first();
                $audittype =$auditype['audit_type'];
                $auditarea =$auditype['audit_area'];  
              
                $auditidcheck =$this->request->getPost('audityear').$audittype.$auditarea.$this->request->getPost('entityid');

                $model = new AuditCreationModel();
                $auditid = $model->where('auditid',$auditidcheck)
                                    ->first();
                    
                if($auditid){
                $session = session();
                $session->setFlashdata('Unable','Already this audit has been created');
                return redirect()->to('http://localhost/Development/development/public/AuditCreation');

                }else{
                $auditype = $this->checklistid->where('checklist_id',$this->request->getPost('checklist_id'))->first();
                $audittype =$auditype['audit_type'];
                $auditarea =$auditype['audit_area'];  
                
                $auditid = $this->request->getPost('audityear').$audittype.$auditarea.$this->request->getPost('entityid');
                
                $newdata = [
                    'auditid'=>$auditid,
                    'audityear' => $this->request->getPost('audityear'),
                    'examinstartdate' => $this->request->getPost('examinstartdate'),
                    'examinendtdate' => $this->request->getPost('examinendtdate'),
                    'coveredstartdate' => $this->request->getPost('coveredstartdate'),
                    'coveredenddate' => $this->request->getPost('coveredenddate'),
                    'audit_category' => $this->request->getPost('audit_category'),
                    'checklist_id' => $this->request->getPost('checklist_id'),
                    'entityid' => $this->request->getPost('entityid'),
                    'reviewer' => $this->request->getPost('reviewer'),
                    'audit_type'=> $audittype,
                    'status'=> 'created',
                    'created_by' => session()->get('EmpNo'),  
                        ];
                print_r($newdata);
                $model = new AuditCreationModel();
                $model->save($newdata);
                $session = session();
                $session->setFlashdata('success','Successfully audit was created');
                return redirect()->to('http://localhost/Development/development/public/AuditCreation');
                }
                }
        }else{
            $db = db_connect(); 
            $model = new CustomModel($db);
            $data['allcreatedaudit'] =($model->joinauditcreation());

            $model2 = new UserModel(); 
                $data['iaduser'] = $model2->where('entityid','859')->findall();


            echo view('templates/header');
            echo view('auditcreation/creationaudit',$data);
            echo view('auditcreation/createdauditsummary');
            echo view('templates/footer');
        }
    }

    public function checklist_id(){
        $audit_category = $this->request->getPost("cId");
        $checklistData = $this->checklistid->selectData("checklist",array("audit_category"=>$audit_category));
        $output = "<option value=''>Select Checklist ID</option>";
        foreach($checklistData as $checklsitid){
            $output .= "<option value='$checklsitid->checklist_id'>$checklsitid->checklist_id -  $checklsitid->audit_category, $checklsitid->audit_type, $checklsitid->audit_area</option>";
        }
        echo json_encode($output);

    }

    public function entityid(){
        $entitytype = $this->request->getPost("eId");
        $entityData = $this->checklistid->selectData("entity",array("entitytype"=>$entitytype));
        $output = "<option value=''>Select Entity Name</option>";
        foreach($entityData as $entityid){
            $output .= "<option value='$entityid->entityid'>$entityid->entityid-$entityid->entityname</option>";
        }
        echo json_encode($output);

    }

    public function auditcreationEdit($id){


        $data = [];
        helper(['form']);
        if($this->request->getMethod()== 'post'){
            $model1 = new AuditCreationModel();
            $data['auditid'] = $id;  ;
            $data['auditdetails'] = $model1->where('auditid',$id)->first();
            if($data['auditdetails']['status'] == 'created'){
                $rules = [
                    'audityear'=>[
                    'rules'=> 'required',
                    'label'=> ' Audit year',
                    'errors'=> [
                            'required'=>'Audit year should be added',
                    ]
                    ],
                    'examinstartdate'=>[
                        'rules'=> 'required|auditStartDateCheck[examinstartdate,examinendtdate]',
                        'label'=> 'Examin Start date',
                        'errors'=> [
                            'required'=>'Examine start dates hould be added',
                            'auditStartDateCheck'=>' Examine start date should be before date of examine end date'
                        ]
                    ],
                    'examinendtdate'=>[
                        'rules'=> 'required|',
                        'label'=> 'Examin end date',
                        'errors'=> [
                            'required'=>'Examine end date should be added',
                        ]
                    ],
                    'coveredstartdate'=>[
                        'rules'=> 'required|coveredPeriodCheck[coveredstartdate,coveredenddate]',
                        'label'=> 'Covered period start date',
                        'errors'=> [
                            'required'=>'Examine start dates hould be added',
                            'coveredPeriodCheck'=>' Covered Period start date should be before date of covered period end date'
                        ]
                    ],
                    'coveredenddate'=>[
                        'rules'=> 'required|',
                        'label'=> 'Examin end date',
                        'errors'=> [
                            'required'=>'Examine end date should be added',
                        ]
                    ],
                    'audit_category'=>[
                        'rules'=> 'required|in_list[branch,department]',
                        'label'=> 'Audit Category',
                        'errors'=> [
                            'required'=>'Audit Category should be selected',
                            'in_list'=>'Entity type should be Selected'
                        ]
                    ],
                    'checklist_id'=>[
                        'rules'=> 'required',
                        'label'=> 'Audit Category',
                        'errors'=> [
                            'required'=>'Checklist Id should be selected',
                        ]
                    ],
                    'entityid'=>[
                        'rules'=> 'required',
                        'label'=> 'entityid',
                        'errors'=> [
                            'required'=>'Entity should be selected',
                        ]
                    ],
                    'reviewer'=>[
                        'rules'=> 'required',
                        'label'=> 'reviewer',
                        'errors'=> [
                            'required'=>'reviewer should be selected',
                        ]
                    ],
                ];           
                if(! $this->validate($rules)){

                    $model1 = new AuditCreationModel();
                    $data['auditid'] = $id;  ;
                    $data['auditdetails'] = $model1->where('auditid',$id)->first();

                    $model2 = new UserModel(); 
                    $data['iaduser'] = $model2->where('entityid','859')->findall();

                    $db = db_connect(); 
                    $model = new CustomModel($db);
                    $data['allauditdetails'] =($model->joinauditiddetails($id));

                    $data['validation'] = $this->validator;
                    echo view('templates/header');
                    echo view('auditcreation/creationauditedit',$data);
                    echo view('templates/footer');
                }else{
                    $auditype = $this->checklistid->where('checklist_id',$this->request->getPost('checklist_id'))->first();
                    $audittype =$auditype['audit_type'];
                    $auditarea =$auditype['audit_area'];  
                    
                    $auditid = $this->request->getPost('audityear').$audittype.$auditarea.$this->request->getPost('entityid');
                    
                    $newdata = [
                        'auditid'=>$auditid,
                        'audityear' => $this->request->getPost('audityear'),
                        'examinstartdate' => $this->request->getPost('examinstartdate'),
                        'examinendtdate' => $this->request->getPost('examinendtdate'),
                        'coveredstartdate' => $this->request->getPost('coveredstartdate'),
                        'coveredenddate' => $this->request->getPost('coveredenddate'),
                        'audit_category' => $this->request->getPost('audit_category'),
                        'checklist_id' => $this->request->getPost('checklist_id'),
                        'entityid' => $this->request->getPost('entityid'),
                        'reviewer' => $this->request->getPost('reviewer'),
                        'audit_type'=> $audittype,
                        'created_by' => session()->get('EmpNo'),
                        'status'=> 'created',
                        
                            ];

                    $model = new AuditCreationModel();
                    $model->where('auditid',$id)->set($newdata)->update();
                    $session = session();
                    $session->setFlashdata('success','Successfully audit was updated');
                    return redirect()->to('http://localhost/Development/development/public/AuditCreation');
                    }
                }else{
                    $model1 = new AuditCreationModel();
                    $data['auditid'] = $id;  ;
                    $data['auditdetails'] = $model1->where('auditid',$id)->first();
        
                    $db = db_connect(); 
                    $model = new CustomModel($db);
                     $data['allauditdetails'] =($model->joinauditiddetails($id));
        
                     $model2 = new UserModel(); 
                     $data['iaduser'] = $model2->where('entityid','859')->findall();
        
                     $session = session();
                     $session->setFlashdata('Unable','Audit status was not created. Only created status audit can be edited');
                     return redirect()->to('http://localhost/Development/development/public/AuditCreation');
                }
        }else{
            $model1 = new AuditCreationModel();
            $data['auditid'] = $id;  ;
            $data['auditdetails'] = $model1->where('auditid',$id)->first();

            $db = db_connect(); 
            $model = new CustomModel($db);
             $data['allauditdetails'] =($model->joinauditiddetails($id));

             $model2 = new UserModel(); 
             $data['iaduser'] = $model2->where('entityid','859')->findall();

            echo view('templates/header');
            echo view('auditcreation/creationauditedit',$data);
            echo view('templates/footer');
        }

    }

    public function auditcreationDelete($id){
        $model = new AuditCreationModel();
        $auditdetails = new AuditCreationModel();
        $commentavailble  = $auditdetails->where('auditid',$id)->first();
        if($commentavailble['status'] == "created"){
            $model->where('auditid', $id)->delete();
            $session = session();
            $session->setFlashdata('success','Successfull audit was deleted');
            return redirect()->to('http://localhost/Development/development/public/AuditCreation');
        }
        else{
        $session = session();
        $session->setFlashdata('Unable','Current audit status is '.$commentavailble['status'].', therefore the created audit can not be deleted' );
        return redirect()->to('http://localhost/Development/development/public/AuditCreation');
        }
    }

    public function createdauditsummaryyearly(){
        $data= [];
        echo view('templates/header');
        echo view('auditcreation/createdauditsummaryyearly',$data);
        echo view('templates/footer'); 

    }

    public function createdauditdetailsyearly(){

        $audityear = $this->request->getPost("cId");
        $auditcreationData = $this->checklistid->selectData("auditcreation",array("audityear"=>$audityear));
        $output = "<option value=''> Select Audit Audit Id</option>";
        foreach($auditcreationData as $auditcreatedyear){
            $output .= "<option value='$auditcreatedyear->auditid'>$auditcreatedyear->auditid</option>";
        }
        echo json_encode($output);

    }

    public function createdauditdetailall(){

        $audityear = $this->request->getPost("cId");
        $auditcreationData = $this->checklistid->selectData("auditcreation",array("audityear"=>$audityear));
        $output ="<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
        foreach($auditcreationData as $auditcreateddetailall){
            // $output .= "<option value='$auditcreatedyear->auditid'>$auditcreatedyear->auditid</option>";
            $output .="<tr>
            <td>$auditcreateddetailall->auditid</td>
            <td>$auditcreateddetailall->examinstartdate</td>
            <td>$auditcreateddetailall->examinendtdate</td>
            <td>$auditcreateddetailall->coveredstartdate</td>
            <td>$auditcreateddetailall->coveredenddate</td>
            <td><a href='/Development/development/public/auditcreationEdit/$auditcreateddetailall->auditid' class='btn btn-secondary'>Edit</a></td>
            </tr>";
        }
        echo json_encode($output);

    }


    public function createdauditdetailallyearly(){

        $audityear = $this->request->getPost("cId");
        $auditcreationData = $this->checklistid->selectDataAudit("auditcreation",array("audityear"=>$audityear));
        $output ="<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
        // echo '<table>';
        foreach($auditcreationData as $auditcreateddetailall){
            // $output .= "<option value='$auditcreatedyear->auditid'>$auditcreatedyear->auditid</option>";
            $output .='<tr>
            <td>'.$auditcreateddetailall['auditid'].'</td>
            <td>'.$auditcreateddetailall['entityname'].'</td>
            <td>'.$auditcreateddetailall['examinstartdate'].'</td>
            <td>'.$auditcreateddetailall['examinendtdate'].'</td>
            <td>'.$auditcreateddetailall['coveredstartdate'].'</td>
            <td>'.$auditcreateddetailall['coveredenddate'].'</td>
            <td>'.$auditcreateddetailall['checklist_id'].'</td>
            <td>'.$auditcreateddetailall['status'].'</td>
            <td>'.$auditcreateddetailall['FirstName'].' '.$auditcreateddetailall['LastName'].'</td>
            <td><a href="/Development/development/public/auditcreationEdit/'.$auditcreateddetailall['auditid'].'"'.'class="btn btn-secondary">Edit</a></td>
            <td><a href="/Development/development/public/auditcreationDelete/'.$auditcreateddetailall['auditid'].'"'.'class="btn btn-danger">Delete</a></td>
            </tr>';
        }
        // echo '</table>';
        echo json_encode($output);

    }

    public function createdauditdetailallnew(){

        $audityear = $this->request->getPost("cId");
        $auditcreationData = $this->checklistid->selectDatajoin("auditcreation",array("audityear"=>$audityear));
        $output ="<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
        // echo '<table>';
        foreach($auditcreationData as $auditcreateddetailall){
            // $output .= "<option value='$auditcreatedyear->auditid'>$auditcreatedyear->auditid</option>";
            $output .='<tr>
            <td>'.$auditcreateddetailall['auditid'].'</td>
            <td>'.$auditcreateddetailall['entityname'].'</td>
            <td>'.$auditcreateddetailall['examinstartdate'].'</td>
            <td>'.$auditcreateddetailall['examinendtdate'].'</td>
            <td>'.$auditcreateddetailall['coveredstartdate'].'</td>
            <td>'.$auditcreateddetailall['coveredenddate'].'</td>
            <td>'.$auditcreateddetailall['status'].'</td>
            <td><a href="/Development/development/public/auditcreationEdit/'.$auditcreateddetailall['auditid'].'"'.'class="btn btn-secondary">Edit</a></td>
            <td><a href="/Development/development/public/auditteamcreation/'.$auditcreateddetailall['auditid'].'"'.'class="btn btn-secondary">Team</a></td>
            </tr>';
        }
        // echo '</table>';
        echo json_encode($output);

    }

    
}
