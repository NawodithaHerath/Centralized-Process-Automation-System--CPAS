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

    
class ReportGenerating extends BaseController
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
        echo view('AuditCompleted/AllCompletedAudit',$data);
        echo view('templates/footer'); 
    }

    public function CompletedAllAudit(){

        $audityear = $this->request->getPost("cId");
        $empno = $this->request->getPost("empno");
        $auditcreationData = $this->auditfieldwork->CompletedAudit("auditcreation",array("audityear"=>$audityear),array("reviewer"=>$empno));
        $output ="<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
        foreach($auditcreationData as $auditcreateddetailall){
            // $output .= "<option value='$auditcreatedyear->auditid'>$auditcreatedyear->auditid</option>";
            $output .="<tr>
            <td>$auditcreateddetailall->auditid</td>
            <td>$auditcreateddetailall->entityname</td>
            <td>$auditcreateddetailall->examinendtdate</td>
            <td>$auditcreateddetailall->coveredstartdate</td>
            <td>$auditcreateddetailall->coveredenddate</td>
            <td>$auditcreateddetailall->totalRisk</td>
            <td>$auditcreateddetailall->branchRiskGrade</td>
            <td>$auditcreateddetailall->status</td>
            <td>$auditcreateddetailall->auditstatusupdate_at</td>
            <td><a href='/Development/development/public/AllCompletedComments/$auditcreateddetailall->auditid' class='btn btn-secondary'>Comments</a></td>
            </tr>";
        }
        echo json_encode($output);

    }

    public function AllCompletedComments($auditid){
        
        $data = [];

        // $model = new CommentsModel();
        $data['commentsdetails'] = $this->commentsdetails->auditongoingall(array("auditid"=>$auditid));

        echo view('templates/headerfieldwork');
        echo view('AuditCompleted/AllCompletedComments',$data);
        echo view('templates/footer'); 
    }
    
    public function CompletedComment($comment_id){
        
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
                echo view('AuditCompleted/CompletedComments',$data);
                echo view('AuditCompleted/CompletedManagementReply');
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
            echo view('AuditCompleted/CompletedComments',$data);
            echo view('AuditCompleted/CompletedManagementReply');
            echo view('templates/footer'); 
        
        
         }
    }
    

}
