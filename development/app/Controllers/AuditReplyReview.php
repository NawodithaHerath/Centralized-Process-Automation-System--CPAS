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
use App\Models\CommentsLogModel;
use App\Models\managerReplyModel;

    
class AuditReplyReview extends BaseController
{

    protected $auditfieldwork;
    protected $entityid;
    protected $createdaudit;
    protected $commentsdetails;

    public function __construct()
    {
        $this->auditfieldwork = new AuditfieldworkModel();
        $db = db_connect(); 
        $this->createdaudit = new CustomModel($db);
        $this->commentsdetails = new CommentsModel();
    }

    public function index($empno)
    {
        $data['empno']= $empno;
        echo view('templates/headerfieldwork');
        echo view('AuditReplyReview/ongoingAssignedReplyReviewedAudit',$data);
        echo view('templates/footer'); 
    }

    public function ongoingAssignedReplyReviewAllAudit(){

        $audityear = $this->request->getPost("cId");
        $empno = $this->request->getPost("empno");
        $auditcreationData = $this->auditfieldwork->assignedAuditReplyReview("auditcreation",array("audityear"=>$audityear),array("reviewer"=>$empno));
        $output ="<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
        foreach($auditcreationData as $auditcreateddetailall){
            // $output .= "<option value='$auditcreatedyear->auditid'>$auditcreatedyear->auditid</option>";
            $output .="<tr>
            <td>$auditcreateddetailall->auditid</td>
            <td>$auditcreateddetailall->entityname</td>
            <td>$auditcreateddetailall->examinstartdate</td>
            <td>$auditcreateddetailall->examinendtdate</td>
            <td>$auditcreateddetailall->coveredstartdate</td>
            <td>$auditcreateddetailall->coveredenddate</td>
            <td>$auditcreateddetailall->status</td>
            <td>$auditcreateddetailall->auditstatusupdate_at</td>
            <td><a href='/Development/development/public/OngoingReplyReviewAllComments/$auditcreateddetailall->auditid' class='btn btn-secondary'>Comments</a></td>
            <td><a href='/Development/development/public/AditFinalisation/$auditcreateddetailall->auditid' class='btn btn-danger' onclick=\"return confirm('Do you realy need to finalised the audit? After finalising, further changes can not be done')\">Audit Finalisation</a></td>
            </tr>";
        }
        echo json_encode($output);

    }

    public function OngoingReplyReviewAllComments($auditid){
        
        $data = [];

        // $model = new CommentsModel();
        $data['commentsdetails'] = $this->commentsdetails->auditongoingall(array("auditid"=>$auditid));

        echo view('templates/headerfieldwork');
        echo view('AuditReplyReview/ongoingReplyReviewAllComments',$data);
        echo view('templates/footer'); 
    }
    
    public function OngoingCommentReplyReviewing($comment_id){
        
        $comments = new CommentsModel();
        $commentsdetails = $comments->where('comment_id',$comment_id)->first();

        if($commentsdetails["status"] == "Recieved from branch" || $commentsdetails["status"] == "Submitted the edited comment to review" ){

            $newdata= [
                'status'=> 'Reviewing Management Reply',
                'statusupdate_at'=> date("Y-m-d H:i:s")
            ];
            $comments->where('comment_id',$comment_id)->set($newdata)->update();
        }

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

        $managerReply = new managerReplyModel();
        $assingedavailable = $managerReply->where('comment_id',$comment_id)->first();

        $data['replydata'] = $managerReply->where('comment_id',$comment_id)->first();


        if($assingedavailable){
        $assingedperson =  $assingedavailable['responseofficer'];
        $assigneddetails = new UserModel();
        $data['assignedpersondetails'] = $assigneddetails->where('EmpNo',$assingedperson)->first();
        
        $data['responsiblePerson'] = $assigneddetails->where('EmpNo', $data['replydata']['responsiblePerson'])->first();

        }

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
            

            $rules = [
                // 'audityear'=>[
                //    'rules'=> 'required',
                //    'label'=> ' Sample details',
                //    'errors'=> [
                //         'required'=>' audityear details should be added',
                //    ]
                // ],
                
                'commentheading'=>[
                    'rules'=> 'required',
                    'label'=> ' Commentheading',
                    'errors'=> [
                         'required'=>' Comment Heading  should be added',
                    ]
                 ],
                 'commentdetails'=>[
                    'rules'=> 'required',
                    'label'=> ' commentdetails',
                    'errors'=> [
                         'required'=>' Audit Finding (Comment) details should be added',
                    ]
                 ],
                 'potentialimpact'=>[
                    'rules'=> 'required',
                    'label'=> ' potentialimpact',
                    'errors'=> [
                         'required'=>' Potential details should be added',
                    ]
                 ],
                 'recommendation'=>[
                    'rules'=> 'required',
                    'label'=> ' recommendation',
                    'errors'=> [
                         'required'=>' Recommendation should be added',
                    ]
                 ],

            ];
            if(! $this->validate($rules)){

                $data['validation'] = $this->validator;

                echo view('templates/headerfieldwork');
                echo view('AuditReplyReview/commentsReplyRevewing',$data);
                echo view('AuditReplyReview/managementReplyingReviewing');
                echo view('templates/footer'); 
                
            }else{

                if($commentsdetails["status"] == "Recieved from branch" || $commentsdetails["status"] == "Submitted the edited comment to review" || $commentsdetails["status"] == "Accepted edited comment with reply"  ){

                    $newdata= [
                        'status'=> 'Reviewing Management Reply',
                        'statusupdate_at'=> date("Y-m-d H:i:s")
                    ];
                    $comments->where('comment_id',$comment_id)->set($newdata)->update();
                }


                $phrase = $this->request->getPost('checkingitem_id');
                $excludeWord = $this->request->getPost('checklist_id');
                $remainingSpaceremove = str_replace(" ",'', $phrase);
                $remainingPhrase = str_replace($excludeWord,'', $remainingSpaceremove);

                $newdata = [
                    'likelihood' => $this->request->getPost('likelihood'),
                    'significance' => $this->request->getPost('significance'),
                    'overallrisk' => $this->request->getPost('overallrisk'),
                    'commentheading' => $this->request->getPost('commentheading'),
                    'commentdetails' => $this->request->getPost('commentdetails'),
                    'potentialimpact' => $this->request->getPost('potentialimpact'),
                    'recommendation' => $this->request->getPost('recommendation'),
                    'targetdate' => $this->request->getPost('targetdate'),
                    'updated_by' => session()->get('EmpNo'),
                    'updated_at' => date("Y-m-d H:i:s"),
                    'statusupdate_at'=> date("Y-m-d H:i:s"),   
                ];

                $model = new CommentsModel();
                $model->where('comment_id',$comment_id)->set($newdata)->update();

                $commentlog = new CommentsLogModel();
                $commentlogdata = [
                    'comment_id'=> $comment_id,
                    'status'=> 'Reviewing',
                    'updated_by' => session()->get('EmpNo'),
                    'updated_at' => date("Y-m-d H:i:s"),  
                    ];
                $commentlog->save($commentlogdata);

                $session = session();
                $session->setFlashdata('success','Comment was successfully saved');
                return redirect()->to('http://localhost/Development/development/public/OngoingCommentReplyReviewing/'.$comment_id);
            }

        }else{

        echo view('templates/headerfieldwork');
        echo view('AuditReplyReview/commentsReplyRevewing',$data);
        echo view('AuditReplyReview/managementReplyingReviewing');
        echo view('templates/footer'); 
        
        
         }
    }

    public function CommentReplyReviewingReject($comment_id){

        $comments = new CommentsModel();
        $commentsdetails = $comments->where('comment_id',$comment_id)->first();
        $newdata= [
            'status'=> 'Comment Rejected to Enterer',
            'statusupdate_at'=> date("Y-m-d H:i:s")
        ];
        if($commentsdetails["status"] == "Reviewing Management Reply"){
                $comments->where('comment_id',$comment_id)->set($newdata)->update();

                $commentlog = new CommentsLogModel();
                $commentlogdata = [
                    'comment_id'=> $comment_id,
                    'status'=> 'Comment Rejected to Enterer',
                    'updated_by' => session()->get('EmpNo'),
                    'updated_at' => date("Y-m-d H:i:s"),  
                    ];
                $commentlog->save($commentlogdata);
                $session = session();
                $session->setFlashdata('Reject','Comment was reject back to enterer with management reply ');
                return redirect()->to('http://localhost/Development/development/public/OngoingCommentReplyReviewing/'.$comment_id);
        
        }else if($commentsdetails["status"] == "Comment Rejected to Enterer"){
            
                $session = session();
                $session->setFlashdata('Reject','Comment was already reject back to enterer ');
                return redirect()->to('http://localhost/Development/development/public/OngoingCommentReplyReviewing/'.$comment_id);

        }else if($commentsdetails["status"] == "Enterer editing comment after replied"){
                $session = session();
                $session->setFlashdata('Reject','Comment was  being still entering by the enterer ');
                return redirect()->to('http://localhost/Development/development/public/OngoingCommentReplyReviewing/'.$comment_id);

        }else if($commentsdetails["status"] == "Accepted management reply"){
            $session = session();
            $session->setFlashdata('Reject','Management reply already has been accepted ');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentReplyReviewing/'.$comment_id);
        }else if($commentsdetails["status"] == "Forwarded to branch"){
            $session = session();
            $session->setFlashdata('Reject','Comment has been forwarded to branch');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentReplyReviewing/'.$comment_id);

        }else if($commentsdetails["status"] == "Accepted management reply by audit"){
            $session = session();
            $session->setFlashdata('Reject','Comment and managment reply were already accepted, Comment finalised');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentReplyReviewing/'.$comment_id);

        }else if($commentsdetails["status"] == "Replied Comment Editing"){
            $session = session();
            $session->setFlashdata('Reject','Comment is editing by the enterer, not received for reviewing');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentReplyReviewing/'.$comment_id);
        }

    }

    public function CommentReplyReviewingAccepted($comment_id){

        $comments = new CommentsModel();
        $commentsdetails = $comments->where('comment_id',$comment_id)->first();
        $newdata= [
            'status'=> 'Accepted edited comment with reply',
            'statusupdate_at'=> date("Y-m-d H:i:s")
        ];
        if($commentsdetails["status"] == "Reviewing Management Reply"){
            $comments->where('comment_id',$comment_id)->set($newdata)->update();

            $commentlog = new CommentsLogModel();
            $commentlogdata = [
                'comment_id'=> $comment_id,
                'status'=> 'Accepted',
                'updated_by' => session()->get('EmpNo'),
                'updated_at' => date("Y-m-d H:i:s"),  
                ];
            $commentlog->save($commentlogdata);
            
            $session = session();
            $session->setFlashdata('success','Edited comment was accepted ');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentReplyReviewing/'.$comment_id);
        
        }else if($commentsdetails["status"] == "Accepted edited comment with reply"){
            $session = session();
            $session->setFlashdata('Reject','Edited Comment was already accepted');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentReplyReviewing/'.$comment_id);

        }else if($commentsdetails["status"] == "Comment Rejected to Enterer"){
            $session = session();
            $session->setFlashdata('Reject','Comment has been rejected back to the enterer ');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentReplyReviewing/'.$comment_id);

        }else if($commentsdetails["status"] == "Replied Comment Editing"){
            $session = session();
            $session->setFlashdata('Entering','Comment was  being still entering by the enterer ');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentReplyReviewing/'.$comment_id);
            
        }else if($commentsdetails["status"] == "Forwarded to branch"){
            $session = session();
            $session->setFlashdata('Reject','Comment has been forwarded to branch ');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentReplyReviewing/'.$comment_id);

        }else if($commentsdetails["status"] == "Accepted management reply by audit"){
            $session = session();
            $session->setFlashdata('Reject','Comment and managment reply were already accepted, Comment finalised');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentReplyReviewing/'.$comment_id);
        }


    }

    public function CommentReplyReviewManagmentAccept($comment_id){

        $comments = new CommentsModel();
        $commentsdetails = $comments->where('comment_id',$comment_id)->first();

        $managerReply = new managerReplyModel();
        
        if($commentsdetails["status"] == "Accepted edited comment with reply"){
            $newdata= [
                'status'=> 'Accepted management reply by audit',
                'statusupdate_at'=> date("Y-m-d H:i:s")
            ];

            $newdatareply = [
                'replyStatus'=> 'Accepted management reply by audit',
                'replystatusupdate_at'=> date("Y-m-d H:i:s")
            ];

            $comments->where('comment_id',$comment_id)->set($newdata)->update();
            $managerReply->where('comment_id',$comment_id)->set($newdatareply)->update(); 

            $session = session();
            $session->setFlashdata('success','Managment Reply was accepted  by audit');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentReplyReviewing/'.$commentsdetails['comment_id']);

        }else if($commentsdetails["status"] == "Accepted management reply by audit"){
            $session = session();
            $session->setFlashdata('Reject','Managment Reply was aslready accepted by audit');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentReplyReviewing/'.$commentsdetails['comment_id']);
        }else{
            $session = session();
            $session->setFlashdata('Reject',' Comment was not still accepted');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentReplyReviewing/'.$commentsdetails['comment_id']); 
        }
    }

    public function ReplyReviewForwardBranch($comment_id){

        $comments = new CommentsModel();
        $commentsdetails = $comments->where('comment_id',$comment_id)->first();

        if($commentsdetails["status"] == "Accepted edited comment with reply"){
            $newdata= [
                'status'=> 'Forwarded to branch',
                'statusupdate_at'=> date("Y-m-d H:i:s")
            ];
            $newdatareply = [
                'replyStatus'=> 'Received from branch',
                'replystatusupdate_at'=> date("Y-m-d H:i:s")
            ];
            $managerReply = new managerReplyModel();
            $comments->where('comment_id',$comment_id)->set($newdata)->update();
            $managerReply->where('comment_id',$comment_id)->set($newdatareply)->update(); 

            $session = session();
            $session->setFlashdata('Forwarded','Comment was forwarded to branch ');
            return redirect()->to('http://localhost/Development/development/public/OngoingReplyReviewAllComments/'.$commentsdetails['auditid']);

        }else if($commentsdetails["status"] == "Forwarded to branch"){
            $session = session();
            $session->setFlashdata('Forwarded','Comment was already forwarded to branch ');
            return redirect()->to('http://localhost/Development/development/public/OngoingReplyReviewAllComments/'.$commentsdetails['auditid']);

        }else if($commentsdetails["status"] == "Accepted management reply by audit"){
            $session = session();
            $session->setFlashdata('notaccept','Managment Reply was already accepted  by audit ');
            return redirect()->to('http://localhost/Development/development/public/OngoingReplyReviewAllComments/'.$commentsdetails['auditid']);
            
        }else{
            $session = session();
            $session->setFlashdata('notaccept','Comment status was not in accepted status');
            return redirect()->to('http://localhost/Development/development/public/OngoingReplyReviewAllComments/'.$commentsdetails['auditid']); 
        }    
        
    }

    public function AditFinalisation($auditid){

        $commentdetails = new CommentsModel();
        $commentstatus = $commentdetails->CommentStatus('comments',$auditid);

        $auditstatus = new AuditCreationModel();
        $auditstatusDetails =   $auditstatus->where('auditid',$auditid)->first();
        $reviewer = $auditstatusDetails['reviewer'];

        $allmanagmentstatus = 'All comment accepted';
        foreach  ($commentstatus as $row){
           if($row['status'] != "Accepted management reply by audit"){
             $allmanagmentstatus = 'Not all comment accepted';
                
           }
        }
        if($allmanagmentstatus == 'Not all comment accepted'){
            $session = session();
            $session->setFlashdata('alert','Before audit finalisation, each comment and reply should be accepted by the audit');
            return redirect()->to('http://localhost/Development/development/public/AuditReplyReview/'.$reviewer);

        }else if($allmanagmentstatus == 'All comment accepted'){

            $commentdetails = new CommentsModel();
            $commentstatus = $commentdetails->CommentStatus('comments',$auditid);

         $commentdetails = new CommentsModel();
         $commentHighCount = $commentdetails->CommentHighCount('comments',$auditid);
         $commentMediumCount = $commentdetails->CommentMediumCount('comments',$auditid);
         $commentLowCount = $commentdetails->CommentLowCount('comments',$auditid);

        $totalRisk = $commentHighCount * 2.5 + $commentMediumCount *1.0 + $commentLowCount * 0.25 ;

            If($totalRisk < 2.5){
                $branchriskgrade = 'AAA';
            }else if($totalRisk <= 5){
                $branchriskgrade = 'AA';
            }else if($totalRisk <= 7.5){
                $branchriskgrade = 'A'; 
            }else if($totalRisk <= 10){
                $branchriskgrade = 'BBB';
            }else if($totalRisk <= 12.5){
                $branchriskgrade = 'BB';
            } else if($totalRisk <= 15){
                $branchriskgrade = 'B';
            } else if($totalRisk <= 17){
                $branchriskgrade = 'CCC';
            } else if($totalRisk <= 19){
                $branchriskgrade = 'CC';
            }  else if($totalRisk <= 20){
                $branchriskgrade = 'C';
            }  else if($totalRisk > 20){
                $branchriskgrade = 'D';
            }
            
            if($auditstatusDetails['status']=='replied'){
                $newdata = [
                    'totalRisk'=>$totalRisk,
                    'branchRiskGrade'=> $branchriskgrade,
                    'status'=>'completed',
                    'auditstatusupdate_at'=>date("Y-m-d H:i:s")
                ];

                $auditstatus->where('auditid',$auditid)->set($newdata)->update();
                $session = session();
                $session->setFlashdata('success','Audit was successfuly finalised');
                return redirect()->to('http://localhost/Development/development/public/AuditFinalised/'.$reviewer);
                
            }else if($auditstatusDetails['status']=='completed'){
                $session = session();
                $session->setFlashdata('alert','Audit was already in completed status');
                return redirect()->to('http://localhost/Development/development/public/AuditFinalised/'.$reviewer);

            }

        }
    }



}
