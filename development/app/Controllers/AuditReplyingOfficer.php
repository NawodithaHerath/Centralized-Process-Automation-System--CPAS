<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ChecklistModel;
use App\Models\AuditfieldworkModel;
use App\Models\CustomModel;
use App\Models\AuditCreationModel;
use App\Models\MainCheckArea;
use App\Models\FirstSubCheckArea;
use App\Models\CheckingItem;
use App\Models\CommentsModel;
use App\Models\AuditTeamMember;
use App\Models\CommentsLogModel;
use App\Models\EntityAdding;
use App\Models\AuditReplyingModel;
use App\Models\managerReplyModel;
use App\Models\CommentReplyLogModel;
    
class AuditReplyingOfficer extends BaseController
{

    protected $auditfieldwork;
    protected $auditreplying;
    protected $entityid;
    protected $createdaudit;
    protected $commentsdetails;

    public function __construct()
    {
        $this->auditfieldwork = new AuditfieldworkModel();
        $db = db_connect(); 
        $this->createdaudit = new CustomModel($db);
        $this->commentsdetails = new CommentsModel();
        $this->auditreplying = new AuditReplyingModel();

    }

    public function index($empno)
    {
        {
            $model = new UserModel();
            $user = $model->where('EmpNo',$empno)->first();
            $data['empno']= $empno;
            $data['user']= $user ;

            if($user['userclass']==30){

                $employee = new UserModel();
                $employeedetails = $employee->where('EmpNo',$empno)->first();

                $entity = new EntityAdding();
                $entitydetails = $entity->where('entityid',$employeedetails['entityid'])->first();

                // print_r( $entitydetails);

                $employee = new UserModel();
                $manager = $employee->where('EmpNo',$entitydetails['manager'])->first();
                $data['manager'] = $manager['FirstName']." ".$manager['LastName'];
                $Assistmanager = $employee->where('EmpNo',$entitydetails['assistantmanager'])->first();
                $data['Assistmanager'] = $Assistmanager['FirstName'].' '.$Assistmanager['LastName'];

            }

            $data['entitydetails'] = $entitydetails;
                            

            echo view('templates/headerreplyofficer',$data);
            echo view('AuditReplyingOfficer/auditOfficerReplyingDashboard');
            echo view('templates/footer');
        }

    }

    public function allOngoingAuditOfficerReplyingAdd($empno){


        if(session()->get('userclass') == 30){
            $employee = new UserModel();
            $employeedetails = $employee->where('EmpNo',$empno)->first();
    
            $entity = new EntityAdding();
            $entitydetails = $entity->where('entityid',$employeedetails['entityid'])->first();
        }

        $data['entitydetails'] = $entitydetails;
        $data['empno']= $empno;
        $data['entityid'] = $entitydetails['entityid'] ;

        echo view('templates/headerreplyofficer');
        echo view('AuditReplyingOfficer/allAuditOfficerReplyAdd',$data);
        echo view('templates/footer'); 
    }

    
    public function CurrentOngoingAuditOfficerReplyAdd(){
 
        $audityear = $this->request->getPost("audityear");
        $entityid = $this->request->getPost("entityid");
        // $audityear = "2023";
        // $entityid = '001';
        $auditcreationData = $this->auditreplying->allAuditNeedReplying("auditcreation",array("audityear"=>$audityear),array("entityid"=>$entityid));
 
        $output ="<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
        foreach($auditcreationData as $auditcreateddetailall){
            // $output .= "<option value='$auditcreatedyear->auditid'>$auditcreatedyear->auditid</option>";
            $output .="<tr>
            <td>$auditcreateddetailall->auditid</td>
            <td>$auditcreateddetailall->examinstartdate</td>
            <td>$auditcreateddetailall->examinendtdate</td>
            <td>$auditcreateddetailall->coveredstartdate</td>
            <td>$auditcreateddetailall->coveredenddate</td>
            <td><a href='/Development/development/public/sessionauditidOfficerReplyingAdd/$auditcreateddetailall->auditid' class='btn btn-secondary'>Comments</a></td>
            </tr>";
        }
        echo json_encode($output);

    }

    public function sessionauditidOfficerReplyingAdd($auditid){
        
        $sessionauditid = ['auditid' => $auditid];
        session()->set($sessionauditid);
        return redirect()->to('http://localhost/Development/development/public/allCommentOfficerReplyingAdd/'.$auditid);

    }

    public function allCommentOfficerReplyingAdd($auditid){

        $EmpNo = session()->get('EmpNo');

        $auditreplying = new AuditReplyingModel();
        
        $auditcreationData = $auditreplying->allCommentNeedReplying("auditcreation",array("auditid"=>$auditid));

        $employee = new UserModel();
        
        $employeedetails = $employee->where('EmpNo',$EmpNo)->first();

         if(!session()->get('isLoggedIn') ||  session()->get('userclass') != 30  ||  $employeedetails['entityid'] != $auditcreationData[0]['entityid']){
            return redirect()->to('http://localhost/Development/development/public/logout');
         }else{

        $data = [];

        $commentsdetails = new AuditReplyingModel();
        $data['commentsdetails'] = $commentsdetails->auditongoingcommentreplyassignonly(array("auditid"=>$auditid,'responseofficer'=>$EmpNo));
        
        echo view('templates/headerreplyofficer');
        echo view('AuditReplyingOfficer/AllCommentsOfficerReplyingAdd',$data);
        echo view('templates/footer'); 
         
        }
    }

    public function CommentOfficerReplyingAdd($comment_id){
        
        $comments = new CommentsModel();
        $commentsdetails = $comments->where('comment_id',$comment_id)->first();
        $data['commentdetails']  = $commentsdetails;
        $checkingitem_id =  $commentsdetails['checkingitem_id'];
        $auditid =  $commentsdetails['auditid'];

        $auditcreation = new AuditCreationModel();
        $auditdetails = $auditcreation->where('auditid',$auditid)->first();

        $checklistid = $auditdetails['checklist_id'];

        $checkingitemdata = new CheckingItem();
        $checkingitemdetails = $checkingitemdata->where('checkingitem_id',$checkingitem_id)->first();
        $checkingitem_description = $checkingitemdetails['checkingitem_description'];
        $firstsubarea_id = $checkingitemdetails['firstsubarea_id'];

        $firstareadata = new FirstSubCheckArea();
        $firstareadetails = $firstareadata->where('firstsubarea_id',$firstsubarea_id)->first();
        $firstsubarea_description =  $firstareadetails['firstsubarea_description'];
        $mainarea_id = $firstareadetails['mainarea_id'];

        $mainareadata = new MainCheckArea();
        $mainareadetails = $mainareadata->where('mainarea_id',$mainarea_id)->first();
        $maindescription =  $mainareadetails['mainarea_description'];

        $maincheckarea = new MainCheckArea();
        $maincheckareadetails = $maincheckarea->where('checklist_id',$checklistid)->findAll();

        $data['maincheckareadetails'] = $maincheckareadetails;

        $data['checklist'] = [
            'checklist_id' => $checklistid,
            'mainarea_id' => $mainarea_id,
            'mainarea_description' => $maindescription,
            'firstsubarea_id' => $firstsubarea_id,
            'firstsubarea_description' => $firstsubarea_description,
            'checkingitem_id' => $checkingitem_id,
            'checkingitem_description' => $checkingitem_description
        ];
        $entityuserdetils = new UserModel();
        $data['entityusers'] = $entityuserdetils->where('entityid',$auditdetails['entityid'])->findAll();

        $managerReply = new managerReplyModel();

        $data['managerReplyDetails']=  $managerReply->where('comment_id',$comment_id)->first();
        $assingedperson = $data['managerReplyDetails']['responseofficer'] ;

        helper(['form']);
        if($this->request->getMethod()== 'post'){

            $managerReply = new managerReplyModel();
            $assingedavailable = $managerReply->where('comment_id',$comment_id)->first();

            if($assingedavailable){
            $assingedperson =  $assingedavailable['responseofficer'];
            $assigneddetails = new UserModel();
            $data['assignedpersondetails'] = $assigneddetails->where('EmpNo',$assingedperson)->first();
            $data['responsiblePerson'] = $assigneddetails->where('EmpNo',$this->request->getPost('responsiblePerson'))->first();
            }

            $data['postdata'] = [
                'comment_id'=> $comment_id,
                'ManagerResponse'=> $this->request->getPost('ManagerResponse'),
                'replyDetails'=> $this->request->getPost('replyDetails'),
                'responsiblePerson'=> $this->request->getPost('responsiblePerson'),
                'rectification'=> $this->request->getPost('rectification'),
                'rectifiedDate'=> $this->request->getPost('rectifiedDate'),
                'rectificationAction'=> $this->request->getPost('rectificationAction'),
                'rectificationTargetDate'=> $this->request->getPost('rectificationTargetDate'),
                'HR'=> $this->request->getPost('HR'),
                'IT'=> $this->request->getPost('IT'),
                'Execution'=> $this->request->getPost('Execution'),
                'Process'=> $this->request->getPost('Process'),
                'Policies'=> $this->request->getPost('Policies'),
                'rootcauseDetails'=> $this->request->getPost('rootcauseDetails'),
                'updated_by' => session()->get('EmpNo'),
                'updated_at' => date("Y-m-d H:i:s"),
                'replyStatus'=>'Adding Reply',
                'replystatusupdate_at'=> date("Y-m-d H:i:s")
            ];

            $rules = [
                 'ManagerResponse'=>[
                    'rules'=> 'required',
                    'label'=> ' responsibleperson',
                    'errors'=> [
                         'required'=>'Manager Response should be added',
                        ]
                    ],
                 'replyDetails'=>[
                    'rules'=> 'required',
                    'label'=> ' replyDetails',
                    'errors'=> [
                         'required'=>'Reply Details Response should be added',
                        ]
                    ],
                'responsiblePerson'=>[
                    'rules'=> 'required',
                    'label'=> ' replyDetails',
                    'errors'=> [
                            'required'=>'Responsible Person should be added',
                        ]
                    ],
                'rectification'=>[
                    'rules'=> 'required',
                    'label'=> ' replyDerectificationtails',
                    'errors'=> [
                            'required'=>'Rectification status should be added',
                            ]
                        ],
                'rootcause'=>[
                    'rules'=> 'rootcausecheck[HR,IT,Execution,Process,Policies]',
                    'label'=> ' rootcauseDetails',
                    'errors'=> [
                            'rootcausecheck'=>'Root cause should be selected',
                             ]
                        ], 
                'rootcauseDetails'=>[
                    'rules'=> 'required',
                    'label'=> ' rootcauseDetails',
                    'errors'=> [
                            'required'=>'Root cause details should be added',
                             ]
                        ],
                'rectificationstatus'=>[
                    'rules'=> 'rectificationstatuscheck[rectification,rectifiedDate,rectificationAction,rectificationTargetDate]',
                    'label'=> ' replyDerectificationtails',
                    'errors'=> [
                        'rectificationstatuscheck'=>'Proper rectification details should be added (Reactified date, Reactification action or Tartgetdate)',
                            ]
                        ],
                    ];

 
            if(! $this->validate($rules)){

                $data['validation'] = $this->validator;

                echo view('templates/headerreplyofficer');
                echo view('AuditReplyingOfficer/commentOfficerReplyingAdd',$data);
                echo view('AuditReplyingOfficer/managementOfficerReplying');
                echo view('templates/footer'); 

                
            }else{

                $commentlogdata = [
                    'comment_id'=> $comment_id,
                    'updated_by' => session()->get('EmpNo'),
                    'updated_at' => date("Y-m-d H:i:s"),
                    'status' => 'Adding Reply'
                ];
                
                $commentreplylogupdate = new CommentReplyLogModel();
    

                if($assingedavailable){
                    $managerReply->where('comment_id',$comment_id)->set($data['postdata'])->update();
                    $commentreplylogupdate->save($commentlogdata);

                }else{
                    $managerReply->save($data['postdata']);
                    $commentreplylogupdate->save($commentlogdata);
                }

                $assigneddetails = new UserModel();
                $data['assignedpersondetails'] = $assigneddetails->where('EmpNo',$assingedperson)->first();

                $session = session();
                $session->setFlashdata('success','Comment was successfully saved');
                echo view('templates/headerreplyofficer');
                echo view('AuditReplyingOfficer/commentOfficerReplyingAdd',$data);
                echo view('AuditReplyingOfficer/managementOfficerReplying');
                echo view('templates/footer'); 
            }

        }else{
                $managerReply = new managerReplyModel();
                $assingedavailable = $managerReply->where('comment_id',$comment_id)->first();

                $data['postdata'] = $managerReply->where('comment_id',$comment_id)->first();


                if($assingedavailable){
                $assingedperson =  $assingedavailable['responseofficer'];
                $assigneddetails = new UserModel();
                $data['assignedpersondetails'] = $assigneddetails->where('EmpNo',$assingedperson)->first();
                
                $data['responsiblePerson'] = $assigneddetails->where('EmpNo', $data['postdata']['responsiblePerson'])->first();

                }
                echo view('templates/headerreplyofficer');
                echo view('AuditReplyingOfficer/commentOfficerReplyingAdd',$data);
                echo view('AuditReplyingOfficer/managementOfficerReplying');
                echo view('templates/footer'); 
        
         }
    }

    public function CommentOfficerReplySubmitReview($comment_id){

        $managerReply = new managerReplyModel();
        $newdata = [
            'replyStatus'=> 'Submit for review',
            'replystatusupdate_at'=> date("Y-m-d H:i:s")
        ];
        $commentsdetails = $managerReply->where('comment_id',$comment_id)->first();
        if($commentsdetails["replyStatus"] == "Adding Reply" || $commentsdetails["replyStatus"] == "Reject"){
            $managerReply->where('comment_id',$comment_id)->set($newdata)->update();

            $commentlog = new CommentReplyLogModel();
            $commentlogdata = [
                'comment_id'=> $comment_id,
                'updated_by' => session()->get('EmpNo'),
                'updated_at' => date("Y-m-d H:i:s"),
                'status' => 'Reply submitted for review' 
                ];
            $commentlog->save($commentlogdata);
            $session = session();
            $session->setFlashdata('success','Managment reply was successfully submitted for review');
            return redirect()->to('http://localhost/Development/development/public/CommentOfficerReplyingAdd/'.$comment_id);

        }else if($commentsdetails["replyStatus"] == 'Submit for review' || $commentsdetails["replyStatus"] == 'Reviewing Reply' ){
            $session = session();
            $session->setFlashdata('alert','Comment was already submitted for review');
            return redirect()->to('http://localhost/Development/development/public/CommentOfficerReplyingAdd/'.$comment_id);

        }else if($commentsdetails["replyStatus"] == 'Assigned for reply'){
            $session = session();
            $session->setFlashdata('alert','Management reply details should be added, before submit for reviewing');
            return redirect()->to('http://localhost/Development/development/public/CommentOfficerReplyingAdd/'.$comment_id);
        }

    }

}
