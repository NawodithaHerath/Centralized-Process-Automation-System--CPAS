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
    
class AuditReplying extends BaseController
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

            if($user['userclass']==50){
                $entity = new EntityAdding();
                $entitydetails = $entity->where('manager',$empno)->first();

                $employee = new UserModel();
                $manager = $employee->where('empno',$empno)->first();
                $data['manager'] = $manager['FirstName']." ".$manager['LastName'];
                $Assistmanager = $employee->where('empno',$entitydetails['assistantmanager'])->first();
                $data['Assistmanager'] = $Assistmanager['FirstName'].' '.$Assistmanager['LastName'];

            }else if($user['userclass']==40){
                $entity = new EntityAdding();
                $entitydetails = $entity->where('assistantmanager',$empno)->first();
    
                $employee = new UserModel();
                $manager = $employee->where('empno',$entitydetails['manager'])->first();
                $data['manager'] = $manager['FirstName']." ".$manager['LastName'];
                $Assistmanager = $employee->where('empno',$empno)->first();
                $data['Assistmanager'] = $Assistmanager['FirstName'].' '.$Assistmanager['LastName'];
                }

            $data['entitydetails'] = $entitydetails;
                            

            echo view('templates/headerreplymanager',$data);
            echo view('AuditReplying/auditReplyingDashboard');
            echo view('templates/footer');
        }

    }
    public function allOngoingAuditReplyingAssign($empno){
        
        if(session()->get('userclass') == 50){
            $entity = new EntityAdding();
            $entitydetails = $entity->where('manager',$empno)->first();

        }else if((session()->get('userclass') == 40)){
            $entity = new EntityAdding();
            $entitydetails = $entity->where('assistantmanager',$empno)->first();
        }

        $data['entitydetails'] = $entitydetails;
        $data['empno']= $empno;
        $data['entityid'] = $entitydetails['entityid'] ;

        echo view('templates/headerreplymanager');
        echo view('AuditReplying/allOngoingAuditReplyAssign',$data);
        echo view('templates/footer'); 
    }

    
    public function CurrentOngoingAuditReplyAssign(){
 
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
            <td><a href='/Development/development/public/sessionauditidReplyingAssign/$auditcreateddetailall->auditid' class='btn btn-secondary'>Comments</a></td>
            
            </tr>";
        }
        echo json_encode($output);

    }

    public function sessionauditidReplyingAssign($auditid){

        $sessionauditid = ['auditid' => $auditid];
        session()->set($sessionauditid);
        return redirect()->to('http://localhost/Development/development/public/allCommentReplyingAssign/'.$auditid);

    }

    public function allCommentReplyingAssign($auditid){

        $EmpNo = session()->get('EmpNo');
        $newauditid = $auditid;
        $auditreplying = new AuditReplyingModel();
        
        $auditcreationData = $auditreplying->allCommentNeedReplying("auditcreation",array("auditid"=>$newauditid));
         $manager = $auditcreationData[0]['manager'];
         $assitmanager = $auditcreationData[0]['assistantmanager'];

         if(session()->get('userclass') == 50){
            $checkmanager =  $manager;
         }else if(session()->get('userclass') == 40){
            $checkmanager =  $assitmanager;
        }
        
         if(!session()->get('isLoggedIn') || (session()->get('userclass') != 50 &&  session()->get('userclass') != 40) ||  $checkmanager != $EmpNo){
            echo 'logout';
            return redirect()->to('http://localhost/Development/development/public/logout');
         }else{

        $data = [];

        // $model = new CommentsModel();
        $data['commentsdetails'] = $this->commentsdetails->auditongoingcommentreplyassign(array("auditid"=>$auditid));

        echo view('templates/headerreplymanager');
        echo view('AuditReplying/ongoingAllCommentsReplyingAssign',$data);
        echo view('templates/footer'); 
         
        }
    }

    public function CommentReplyingAssigned($comment_id){
        
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

        helper(['form']);
        if($this->request->getMethod()== 'post'){

            $rules = [
                 'responseofficer'=>[
                    'rules'=> 'required',
                    'label'=> ' responsibleperson',
                    'errors'=> [
                         'required'=>'Response person should be selected',
                        ]
                    ],
                ];

            $managerReply = new managerReplyModel();
            $commentsdetails = $managerReply->where('comment_id',$comment_id)->first();


            if(! $this->validate($rules)){

                $data['validation'] = $this->validator;

                echo view('templates/headerreplymanager');
                echo view('AuditReplying/commentReplying',$data);
                echo view('AuditReplying/managementReplyingAssigned');
                echo view('templates/footer'); 

            }else{

                $assingedperson = $this->request->getPost('responseofficer');
                $newdata = [
                    'comment_id'=> $comment_id,
                    'responseofficer' => $assingedperson,
                    'assignedDate' => date("Y-m-d H:i:s"),
                    'created_by' => session()->get('EmpNo'),
                    'created_at' => date("Y-m-d H:i:s"),
                    'replyStatus'=>'Assigned for reply'
                ];

                $commentlogdata = [
                    'comment_id'=> $comment_id,
                    'updated_by' => session()->get('EmpNo'),
                    'updated_at' => date("Y-m-d H:i:s"),
                    'status' => 'comment asssigned-'. $assingedperson
                ];
                

                $managerReply = new managerReplyModel();
                $commentreplylogupdate = new CommentReplyLogModel();

                $assingedavailable = $managerReply->where('comment_id',$comment_id)->first();

                if($assingedavailable){

                    if($commentsdetails['replyStatus']=="Submitted to Audit"){
                    
                        $session = session();
                        $session->setFlashdata('alert','Management reply already submitted to audit, unable to re-assign');
                        return redirect()->to('http://localhost/Development/development/public/CommentReplyingAssigned/'.$comment_id);
                    }else {
                        $managerReply->where('comment_id',$comment_id)->set($newdata)->update();
                        $commentreplylogupdate->save($commentlogdata);
                    }

                }else{
                    $managerReply->save($newdata);
                    $commentreplylogupdate->save($commentlogdata);
                }

                $assigneddetails = new UserModel();
                $data['assignedpersondetails'] = $assigneddetails->where('EmpNo',$assingedperson)->first();

                $session = session();
                $session->setFlashdata('success','Comment was successfully assigned');
                echo view('templates/headerreplymanager');
                echo view('AuditReplying/commentReplying',$data);
                echo view('AuditReplying/managementReplyingAssigned');
                echo view('templates/footer'); 
            }

        }else{
                $managerReply = new managerReplyModel();
                $assingedavailable = $managerReply->where('comment_id',$comment_id)->first();

                if($assingedavailable){
                $assingedperson =  $assingedavailable['responseofficer'];
                $assigneddetails = new UserModel();
                $data['assignedpersondetails'] = $assigneddetails->where('EmpNo',$assingedperson)->first();
                }

                echo view('templates/headerreplymanager');
                echo view('AuditReplying/commentReplying',$data);
                echo view('AuditReplying/managementReplyingAssigned');
                echo view('templates/footer'); 
        
         }
    }

    public function allOngoingAuditReplyingAdd($empno){
        
        if(session()->get('userclass') == 50){
            $entity = new EntityAdding();
            $entitydetails = $entity->where('manager',$empno)->first();
        }else if((session()->get('userclass') == 40)){
            $entity = new EntityAdding();
            $entitydetails = $entity->where('assistantmanager',$empno)->first();
        }

        $data['entitydetails'] = $entitydetails;
        $data['empno']= $empno;
        $data['entityid'] = $entitydetails['entityid'] ;

        echo view('templates/headerreplymanager');
        echo view('AuditReplying/allOngoingAuditReplyAdd',$data);
        echo view('templates/footer'); 
    }

    
    public function CurrentOngoingAuditReplyAdd(){
 
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
            <td><a href='/Development/development/public/sessionauditidReplyingAdd/$auditcreateddetailall->auditid' class='btn btn-secondary'>Comments</a></td>
            </tr>";
        }
        echo json_encode($output);

    }

    public function sessionauditidReplyingAdd($auditid){
        
        $sessionauditid = ['auditid' => $auditid];
        session()->set($sessionauditid);
        return redirect()->to('http://localhost/Development/development/public/allCommentReplyingAdd/'.$auditid);

    }

    public function allCommentReplyingAdd($auditid){

        $EmpNo = session()->get('EmpNo');
        $newauditid = $auditid;
        $auditreplying = new AuditReplyingModel();
        
        $auditcreationData = $auditreplying->allCommentNeedReplying("auditcreation",array("auditid"=>$newauditid));
         $manager = $auditcreationData[0]['manager'];
         $assitmanager = $auditcreationData[0]['assistantmanager'];

         if(session()->get('userclass') == 50){
            $checkmanager =  $manager;
         }else if(session()->get('userclass') == 40){
            $checkmanager =  $assitmanager;
        }
        
         if(!session()->get('isLoggedIn') || (session()->get('userclass') != 50 && session()->get('userclass') != 40 ) ||  $checkmanager != $EmpNo){
            return redirect()->to('http://localhost/Development/development/public/logout');
         }else{

        $data = [];

        $commentsdetails = new AuditReplyingModel();
        $data['commentsdetails'] = $commentsdetails->auditongoingcommentreplyassignonly(array("auditid"=>$auditid,'responseofficer'=>$EmpNo));

        echo view('templates/headerreplymanager');
        echo view('AuditReplying/ongoingAllCommentsReplyingAdd',$data);
        echo view('templates/footer'); 
         
        }
    }

    public function CommentReplyingAdd($comment_id){
        
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

                echo view('templates/headerreplymanager');
                echo view('AuditReplying/commentReplyingAdd',$data);
                echo view('AuditReplying/managementReplying');
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
                echo view('templates/headerreplymanager');
                echo view('AuditReplying/commentReplyingAdd',$data);
                echo view('AuditReplying/managementReplying');;
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

                echo view('templates/headerreplymanager');
                echo view('AuditReplying/commentReplyingAdd',$data);
                echo view('AuditReplying/managementReplying');
                echo view('templates/footer'); 
        
         }
    }

    public function OngoingCommentReplySubmitReview($comment_id){

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
            return redirect()->to('http://localhost/Development/development/public/CommentReplyingAdd/'.$comment_id);

        }else if($commentsdetails["replyStatus"] == 'Submit for review' || $commentsdetails["replyStatus"] == 'Reviewing Reply' ){
            $session = session();
            $session->setFlashdata('alert','Comment was already submitted for review');
            return redirect()->to('http://localhost/Development/development/public/CommentReplyingAdd/'.$comment_id);

        }else if($commentsdetails["replyStatus"] == 'Assigned for reply'){
            $session = session();
            $session->setFlashdata('alert','Management reply details should be added, before submit for reviewing');
            return redirect()->to('http://localhost/Development/development/public/CommentReplyingAdd/'.$comment_id);

        }else if($commentsdetails["replyStatus"] == 'Submitted to Audit'){
            $session = session();
            $session->setFlashdata('alert','Management reply already forwarded to Audit');
            return redirect()->to('http://localhost/Development/development/public/CommentReplyingAdd/'.$comment_id);
        }

    }

    public function allOngoingAuditReplyingReview($empno){
        
        if(session()->get('userclass') == 50){
            $entity = new EntityAdding();
            $entitydetails = $entity->where('manager',$empno)->first();
        }else if((session()->get('userclass') == 40)){
            $entity = new EntityAdding();
            $entitydetails = $entity->where('assistantmanager',$empno)->first();
        }

        $data['entitydetails'] = $entitydetails;
        $data['empno']= $empno;
        $data['entityid'] = $entitydetails['entityid'] ;

        echo view('templates/headerreplymanager');
        echo view('AuditReplying/allOngoingAuditReplyReview',$data);
        echo view('templates/footer'); 
    }

    
    public function CurrentOngoingAuditReplyReview(){
 
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
            <td><a href='/Development/development/public/sessionauditidReplyingReview/$auditcreateddetailall->auditid' class='btn btn-secondary'>Comments</a></td>
            <td><a href='/Development/development/public/RepliedAuditSubmitAudit/$auditcreateddetailall->auditid' class='btn btn-secondary' onclick=\"return confirm('Do you realy need to submit management reply to the audit?')\">Submit to Audit</a></td>
            </tr>";
        }
        echo json_encode($output);

    }

    public function sessionauditidReplyingReview($auditid){
        
        $sessionauditid = ['auditid' => $auditid];
        session()->set($sessionauditid);
        return redirect()->to('http://localhost/Development/development/public/allCommentReplyingReview/'.$auditid);

    }

    public function allCommentReplyingReview($auditid){

        $EmpNo = session()->get('EmpNo');
        $newauditid = $auditid;
        $auditreplying = new AuditReplyingModel();
        
        $auditcreationData = $auditreplying->allCommentNeedReplying("auditcreation",array("auditid"=>$newauditid));
         $manager = $auditcreationData[0]['manager'];
         $assitmanager = $auditcreationData[0]['assistantmanager'];

         if(session()->get('userclass') == 50){
            $checkmanager =  $manager;
         }else if(session()->get('userclass') == 40){
            $checkmanager =  $assitmanager;
        }
         if(!session()->get('isLoggedIn') || (session()->get('userclass') != 50 && session()->get('userclass') != 40 ) ||  $checkmanager != $EmpNo){
            echo 'logout';
            return redirect()->to('http://localhost/Development/development/public/logout');
         }else{

        $data = [];

        // $model = new CommentsModel();
        $data['commentsdetails'] = $this->commentsdetails->auditongoingcommentreplyreview(array("auditid"=>$auditid));

        echo view('templates/headerreplymanager');
        echo view('AuditReplying/ongoingAllCommentsReplyingReview',$data);
        echo view('templates/footer'); 
         
        }
    }

    public function CommentReplyingReview($comment_id){
        
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
                'replyStatus'=>'Reviewing Reply',
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

                echo view('templates/headerreplymanager');
                echo view('AuditReplying/commentReplyingReview',$data);
                echo view('AuditReplying/managementReplyingReview');
                echo view('templates/footer'); 
                
            }else{

                $commentlogdata = [
                    'comment_id'=> $comment_id,
                    'updated_by' => session()->get('EmpNo'),
                    'updated_at' => date("Y-m-d H:i:s"),
                    'status' => 'Reviewing Reply'
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
                echo view('templates/headerreplymanager');
                echo view('AuditReplying/commentReplyingReview',$data);
                echo view('AuditReplying/managementReplyingReview');
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

                echo view('templates/headerreplymanager');
                echo view('AuditReplying/commentReplyingReview',$data);
                echo view('AuditReplying/managementReplyingReview');
                echo view('templates/footer'); 
        
         }
    }

    public function OngoingCommentReplyReviewedAccepted($comment_id){

        $managerReply = new managerReplyModel();
        $newdata = [
            'replyStatus'=> 'Accepted management reply',
            'replystatusupdate_at'=> date("Y-m-d H:i:s")
        ];
        $commentsdetails = $managerReply->where('comment_id',$comment_id)->first();
        if($commentsdetails["replyStatus"] == "Submit for review" || $commentsdetails["replyStatus"] == "Reviewing Reply"){
            $managerReply->where('comment_id',$comment_id)->set($newdata)->update();

            $commentlog = new CommentReplyLogModel();
            $commentlogdata = [
                'comment_id'=> $comment_id,
                'updated_by' => session()->get('EmpNo'),
                'updated_at' => date("Y-m-d H:i:s"),
                'status' => 'Accepted management reply' 
                ];
            $commentlog->save($commentlogdata);
            $session = session();
            $session->setFlashdata('success','Managment reply was successfully accepted');
            return redirect()->to('http://localhost/Development/development/public/CommentReplyingReview/'.$comment_id);

        }else if($commentsdetails["replyStatus"] == "Adding Reply" || $commentsdetails["replyStatus"] == "Reject" ){
            $session = session();
            $session->setFlashdata('alert','Comment was not submitted for review');
            return redirect()->to('http://localhost/Development/development/public/CommentReplyingReview/'.$comment_id);

        }else if($commentsdetails["replyStatus"] == 'Assigned for reply'){
            $session = session();
            $session->setFlashdata('alert','Management reply details should be added, before submit to audit');
            return redirect()->to('http://localhost/Development/development/public/CommentReplyingReview/'.$comment_id);

        }else if($commentsdetails["replyStatus"] == "Submit for review" || $commentsdetails["replyStatus"] == "Reviewing Reply"){
            $session = session();
            $session->setFlashdata('alert','Management reply should be accepted, before submit to audit');
            return redirect()->to('http://localhost/Development/development/public/CommentReplyingReview/'.$comment_id);

        }else if($commentsdetails["replyStatus"] == "Accepted Management Reply"){
            $session = session();
            $session->setFlashdata('alert','Management reply already accepted');
            return redirect()->to('http://localhost/Development/development/public/CommentReplyingReview/'.$comment_id);

        }else if($commentsdetails["replyStatus"] == 'Submitted to Audit'){
            $session = session();
            $session->setFlashdata('alert','Management reply already forwarded to Audit');
            return redirect()->to('http://localhost/Development/development/public/CommentReplyingReview/'.$comment_id);

        }else if($commentsdetails["replyStatus"] == 'Accepted management reply by audit'){
            $session = session();
            $session->setFlashdata('alert','Management reply already accpted by the audit');
            return redirect()->to('http://localhost/Development/development/public/CommentReplyingReview/'.$comment_id);
        }

    }

    public function OngoingCommentReplyReviewedSubmitAudit($comment_id){

        $managerReply = new managerReplyModel();
        $newdata = [
            'replyStatus'=> 'Submitted to Audit',
            'replystatusupdate_at'=> date("Y-m-d H:i:s")
        ];
        $commentsdetails = $managerReply->where('comment_id',$comment_id)->first();
        if($commentsdetails["replyStatus"] == "Accepted management reply"){
            $managerReply->where('comment_id',$comment_id)->set($newdata)->update();

        $comments = new CommentsModel();
        $commentsdetails = $comments->where('comment_id',$comment_id)->first();
    
        $newdataaudit= [
                    'status'=> 'Recieved from branch',
                    'statusupdate_at'=> date("Y-m-d H:i:s")
                ];

            $commentlog = new CommentReplyLogModel();
            $commentlogdata = [
                'comment_id'=> $comment_id,
                'updated_by' => session()->get('EmpNo'),
                'updated_at' => date("Y-m-d H:i:s"),
                'status' => 'Submitted to Audit' 
                ];

                
            $comments->where('comment_id',$comment_id)->set($newdataaudit)->update();

            $commentlog->save($commentlogdata);
            $session = session();
            $session->setFlashdata('success','Managment reply was successfully submitted to audit');
            return redirect()->to('http://localhost/Development/development/public/CommentReplyingReview/'.$comment_id);

        }else if($commentsdetails["replyStatus"] == "Adding Reply" || $commentsdetails["replyStatus"] == "Reject" ){
            $session = session();
            $session->setFlashdata('alert','Comment was not submitted for review');
            return redirect()->to('http://localhost/Development/development/public/CommentReplyingReview/'.$comment_id);

        }else if($commentsdetails["replyStatus"] == 'Assigned for reply'){
            $session = session();
            $session->setFlashdata('alert','Management reply details should be added, before submit to audit');
            return redirect()->to('http://localhost/Development/development/public/CommentReplyingReview/'.$comment_id);

        }else if($commentsdetails["replyStatus"] == "Submit for review" || $commentsdetails["replyStatus"] == "Reviewing Reply"){
            $session = session();
            $session->setFlashdata('alert','Management reply should be accepted, before submit to audit');
            return redirect()->to('http://localhost/Development/development/public/CommentReplyingReview/'.$comment_id);

        }else if($commentsdetails["replyStatus"] == 'Submitted to Audit'){
            $session = session();
            $session->setFlashdata('alert','Management reply already submitted to audit');
            return redirect()->to('http://localhost/Development/development/public/CommentReplyingReview/'.$comment_id);

        }else if($commentsdetails["replyStatus"] == 'Submitted to Audit'){
            $session = session();
            $session->setFlashdata('alert','Management reply already submitted to audit');
            return redirect()->to('http://localhost/Development/development/public/CommentReplyingReview/'.$comment_id);

        }else if($commentsdetails["replyStatus"] == 'Accepted management reply by audit'){
            $session = session();
            $session->setFlashdata('alert','Management reply already accpted by the audit');
            return redirect()->to('http://localhost/Development/development/public/CommentReplyingReview/'.$comment_id);
        }

    }

    public function RepliedAuditSubmitAudit($auditid){
        $managerReply = new managerReplyModel();
        $managementreplystatus = $managerReply->managmentReplyStatus('managementreply',$auditid);

        $auditstatus = new AuditCreationModel();
        $auditstatusDetails =   $auditstatus->where('auditid',$auditid)->first();

        if($auditstatusDetails['status']=='replied'){
            $session = session();
            $session->setFlashdata('alert','Management replied already submitted to the audit');
            return redirect()->to('http://localhost/Development/development/public/allCommentReplyingReview/'.$auditid);

        }

        $allmanagmentstatus = 'All comment submitted to audit';
        foreach  ($managementreplystatus as $row){
           if($row['replyStatus'] != "Submitted to Audit"){
             $allmanagmentstatus = 'Not all submit to audit';
                
           }
        }
        if($allmanagmentstatus == 'Not all submit to audit' || empty($managementreplystatus)){
            $session = session();
            $session->setFlashdata('alert','Before submit the managment reply to the audit, each reply is needed to submit to the audit individualy (After adding management reply) ');
            return redirect()->to('http://localhost/Development/development/public/allCommentReplyingReview/'.$auditid);

        }else if($allmanagmentstatus == 'All comment submitted to audit'){
            $auditstatus = new AuditCreationModel();
            $auditstatusDetails =   $auditstatus->where('auditid',$auditid)->first();

            if($auditstatusDetails['status']=='started'){
                $newdata = [
                    'status'=>'replied',
                    'auditstatusupdate_at'=>date("Y-m-d H:i:s")
                ];

                $auditstatus->where('auditid',$auditid)->set($newdata)->update();
                $session = session();
                $session->setFlashdata('success','All management reply was submitted to the audit');
                return redirect()->to('http://localhost/Development/development/public/allCommentReplyingReview/'.$auditid);
                
            }else if($auditstatusDetails['status']=='replied'){
                $session = session();
                $session->setFlashdata('alert','All management reply already submitted to the audit');
                return redirect()->to('http://localhost/Development/development/public/allCommentReplyingReview/'.$auditid);

            }

        }

        

    }

}
