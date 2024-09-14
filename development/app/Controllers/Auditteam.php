<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AuditTeamMember;
use App\Models\ChecklistModel;
use App\Models\AuditCreationModel;


class Auditteam extends BaseController
{

    protected $checklistid;
    protected $teammemberassignment;

    public function __construct()

    {
        $this->teammemberassignment = new AuditTeamMember();
        $this->checklistid = new ChecklistModel();
    }
    public function index($id)
    {
        $data = [];
        helper(['form']);
        if($this->request->getMethod()== 'post'){

            $model1 = new AuditCreationModel();
            $data['auditid'] = $id;  ;
            $data['auditdetails'] = $model1->where('auditid',$id)->first();

            if($data['auditdetails']['status']!='completed'){
                
                    $rules = [
                    'empno'=>[
                    'rules'=> 'required',
                    'label'=> ' Audit year',
                    'errors'=> [
                            'required'=>'Team Member should be selected',
                    ]
                    ],
                ];
                if(! $this->validate($rules)){
                    $data['validation'] = $this->validator;

                    $model2 = new UserModel(); 
                    $data['iaduser'] = $model2->where('entityid','859')->findall();
                    $model = new AuditTeamMember();
                    $teammemberdetails = $model->selectDatajoin($id);
                    $data['teammemberdetails'] =  $teammemberdetails;

                    $data['auditid'] = $id;
                    echo view('templates/header');
                    echo view('auditteam/auditteam', $data);
                    echo view('auditteam/auditteamsummary');
                    echo view('templates/footer');

                }else{
                    $auditassignedid = $this->request->getPost('auditid').'_'.$this->request->getPost('empno');
                    $model = new AuditTeamMember();
                    $auditid = $model->where('auditassignedid',$auditassignedid)
                                        ->first();
                        
                    if($auditid){
                        $session = session();
                        $session->setFlashdata('Unable','Already this team member has been assinged to this audit');
                        return redirect()->to('http://localhost/Development/development/public/auditteamcreation/'.$id);}
                    
                    else{

                        $newdata = [
                            'auditassignedid' => $this->request->getPost('auditid').'_'.$this->request->getPost('empno'),
                            'empno' => $this->request->getPost('empno'),
                            'auditid' => $this->request->getPost('auditid'),
                            'created_by' => session()->get('EmpNo'),
                            ];
                            $model = new AuditTeamMember();
                            $model->save($newdata);
                            $session = session();
                            $session->setFlashdata('success','Successfully team member added');
                            return redirect()->to('http://localhost/Development/development/public/auditteamcreation/'.$id);

                        }
                    }
            }else{
                $session = session();
                $session->setFlashdata('Unable','Team members can not be updated, due to audit status is competed.');
                return redirect()->to('http://localhost/Development/development/public/auditteamcreation/'.$id);
            }
        }else{
            $model2 = new UserModel(); 
            
            $data['iaduser'] = $model2->where('entityid','859')->findall();
            $model = new AuditTeamMember();
            $teammemberdetails = $model->selectDatajoin($id);
            $data['teammemberdetails'] =  $teammemberdetails;
            
            $data['auditid'] = $id;
            echo view('templates/header');
            echo view('auditteam/auditteam', $data);
            echo view('auditteam/auditteamsummary');
            echo view('templates/footer');

        }
    }

    public function auditteamremove($id){

        $model = new AuditTeamMember();
        $auditId = $model->where('auditassignedid',$id)->first();

        $model->where('auditassignedid', $id)->delete();
        $session = session();
        $session->setFlashdata('success','Successfuly team member asssingment was removed');

        return redirect()->to('http://localhost/Development/development/public/auditteamcreation/'.$auditId['auditid']);
        
    }

    public function allteamassignment(){

        echo view('templates/header');
        echo view('auditteam/allteamassignment');
        echo view('templates/footer');
        
    }

    public function teamassignmentdetails(){

        $audityear = $this->request->getPost("cId");
        // $audityear = '2023';
        $auditteamData = $this->teammemberassignment->auditteamassignment("auditteammember",$audityear);
        // $auditteamData = $this->checklistid->selectData("auditcreation",array("audityear"=>$audityear));
        // print_r( $auditteamData);
        $output ="";
        foreach($auditteamData as $auditcreateddetailall){
            $output .="<tr>
            <td>$auditcreateddetailall->auditid</td>
            <td>$auditcreateddetailall->entityname</td>
            <td>$auditcreateddetailall->examinstartdate</td>
            <td>$auditcreateddetailall->FirstName $auditcreateddetailall->LastName</td>
            <td>$auditcreateddetailall->Email</td>
            <td><a href=\"/Development/development/public/auditteamcreation/$auditcreateddetailall->auditid\" class=\"btn btn-secondary\"> Edit</a></td>

            </tr>";
        }
        echo json_encode($output);
        
    }
    
    public function auditteamdetails($id){
        $model = new AuditTeamMember();
        $teammemberdetails = $model->selectDatajoin($id);
        print_r($teammemberdetails);
        
    }


}
