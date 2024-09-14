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

    
class AuditReview extends BaseController
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
        echo view('AuditReview/ongoingAssignedReviewedAudit',$data);
        echo view('templates/footer'); 
    }

    public function ongoingAssignedReviewAllAudit(){

        $audityear = $this->request->getPost("cId");
        $empno = $this->request->getPost("empno");
        $auditcreationData = $this->auditfieldwork->assignedAuditStartReview("auditcreation",array("audityear"=>$audityear),array("auditcreation.reviewer"=>$empno));
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
            <td><a href='/Development/development/public/OngoingReviewAllComments/$auditcreateddetailall->auditid' class='btn btn-secondary'>Comments</a></td>
            </tr>";
        }
        echo json_encode($output);

    }

    public function OngoingReviewAllComments($auditid){
        
        $data = [];

        // $model = new CommentsModel();
        $data['commentsdetails'] = $this->commentsdetails->auditongoingall(array("auditid"=>$auditid));

        echo view('templates/headerfieldwork');
        echo view('AuditReview/ongoingReviewAllComments',$data);
        echo view('templates/footer'); 
    }
    
    public function OngoingCommentReviewing($comment_id){
        
        $comments = new CommentsModel();
        $commentsdetails = $comments->where('comment_id',$comment_id)->first();

        if($commentsdetails["status"] == "Submit for review"){

            $newdata= [
                'status'=> 'Reviewing',
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

        helper(['form']);
        if($this->request->getMethod()== 'post'){

            $rules = [
                // 'audityear'=>[
                //    'rules'=> 'required',
                //    'label'=> ' Sample details',
                //    'errors'=> [
                //         'required'=>' audityear details should be added',
                //    ]
                // ],
                'mainarea_id'=>[
                    'rules'=> 'required',
                    'label'=> ' mainarea_id',
                    'errors'=> [
                         'required'=>' Mainarea should be added',
                    ]
                 ],
                 'firstsubarea_id'=>[
                    'rules'=> 'required',
                    'label'=> ' firstsubarea_id',
                    'errors'=> [
                         'required'=>' Firstsubarea should be added',
                    ]
                 ],
                 'checkingitem_id'=>[
                    'rules'=> 'required',
                    'label'=> ' checkingitem_id',
                    'errors'=> [
                         'required'=>' Checking Item should be added',
                    ]
                 ],
                 'numberofissues'=>[
                    'rules'=> 'samplecountcheck[samplesize,numberofissues]',
                    'label'=> 'Examin Start date',
                    'errors'=> [
                         'samplecountcheck'=>' Sample Size should be greater than number of issues'
                    ]
                 ],
                'sampledetails'=>[
                    'rules'=> 'required',
                    'label'=> ' sampledetails',
                    'errors'=> [
                         'required'=>' Sampledetails should be added',
                    ]
                 ],
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
                 'targetdate'=>[
                    'rules'=> 'required|targetdateFuturedate[targetdate]',
                    'label'=> ' targetdate',
                    'errors'=> [
                         'required'=>' Targetdate should be added',
                         'targetdateFuturedate'=>' Targetdate should be future date'

                    ]
                 ],
            ];
            if(! $this->validate($rules)){

                $data['validation'] = $this->validator;

                echo view('templates/headerfieldwork');
                echo view('AuditReview/commentsRevewing',$data);
                echo view('templates/footer'); 
                
            }else{

                $phrase = $this->request->getPost('checkingitem_id');
                $excludeWord = $this->request->getPost('checklist_id');
                $remainingSpaceremove = str_replace(" ",'', $phrase);
                $remainingPhrase = str_replace($excludeWord,'', $remainingSpaceremove);

                $newdata = [
                    'comment_id' => $auditid.$remainingPhrase,
                    'auditid' => $this->request->getPost('auditid'),
                    'checkingitem_id' => $this->request->getPost('checkingitem_id'),
                    'samplesize' => $this->request->getPost('samplesize'),
                    'numberofissues' => $this->request->getPost('numberofissues'),
                    'sampledetails' => $this->request->getPost('sampledetails'),
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
                return redirect()->to('http://localhost/Development/development/public/OngoingCommentReviewing/'.$comment_id);
            }

        }else{


        echo view('templates/headerfieldwork');
        echo view('AuditReview/commentsRevewing',$data);
        echo view('templates/footer'); 
        
         }
    }

    public function CommentReviewingReject($comment_id){

        $comments = new CommentsModel();
        $commentsdetails = $comments->where('comment_id',$comment_id)->first();
        $newdata= [
            'status'=> 'Rejected',
            'statusupdate_at'=> date("Y-m-d H:i:s")
        ];
        if($commentsdetails["status"] == "Reviewing"){
                $comments->where('comment_id',$comment_id)->set($newdata)->update();

                $commentlog = new CommentsLogModel();
                $commentlogdata = [
                    'comment_id'=> $comment_id,
                    'status'=> 'Rejected',
                    'updated_by' => session()->get('EmpNo'),
                    'updated_at' => date("Y-m-d H:i:s"),  
                    ];
                $commentlog->save($commentlogdata);
                $session = session();
                $session->setFlashdata('Reject','Comment was reject back to enterer ');
                return redirect()->to('http://localhost/Development/development/public/OngoingCommentReviewing/'.$comment_id);
        
        }else if($commentsdetails["status"] == "Rejected"){
            
                $session = session();
                $session->setFlashdata('Reject','Comment was already reject back to enterer ');
                return redirect()->to('http://localhost/Development/development/public/OngoingCommentReviewing/'.$comment_id);

        }else if($commentsdetails["status"] == "Entering"){
                $session = session();
                $session->setFlashdata('Reject','Comment was  being still entering by the enterer ');
                return redirect()->to('http://localhost/Development/development/public/OngoingCommentReviewing/'.$comment_id);

        }else if($commentsdetails["status"] == "Accepted"){
            $session = session();
            $session->setFlashdata('Reject','Comment already has been accepted ');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentReviewing/'.$comment_id);
        }else if($commentsdetails["status"] == "Forwarded to branch"){
            $session = session();
            $session->setFlashdata('Reject','Comment has been forwarded to branch ');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentReviewing/'.$comment_id);
        }

    }

    public function CommentReviewingAccept($comment_id){

        $comments = new CommentsModel();
        $commentsdetails = $comments->where('comment_id',$comment_id)->first();
        $newdata= [
            'status'=> 'Accepted',
            'statusupdate_at'=> date("Y-m-d H:i:s")
        ];
        if($commentsdetails["status"] == "Reviewing"){
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
            $session->setFlashdata('success','Comment was accepted ');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentReviewing/'.$comment_id);
        
        }else if($commentsdetails["status"] == "Accepted"){
            $session = session();
            $session->setFlashdata('Reject','Comment was already accepted');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentReviewing/'.$comment_id);

        }else if($commentsdetails["status"] == "Rejected"){
            $session = session();
            $session->setFlashdata('Reject','Comment has been rejected back to the enterer ');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentReviewing/'.$comment_id);

        }else if($commentsdetails["status"] == "Entering"){
            $session = session();
            $session->setFlashdata('Entering','Comment was  being still entering by the enterer ');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentReviewing/'.$comment_id);
        }else if($commentsdetails["status"] == "Forwarded to branch"){
            $session = session();
            $session->setFlashdata('Reject','Comment has been forwarded to branch ');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentReviewing/'.$comment_id);
        }

    }

    public function OngoingCommentForward($comment_id){

        $comments = new CommentsModel();
        $commentsdetails = $comments->where('comment_id',$comment_id)->first();

        if($commentsdetails["status"] == "Accepted"){
            $newdata= [
                'status'=> 'Forwarded to branch',
                'statusupdate_at'=> date("Y-m-d H:i:s")
            ];
            $comments->where('comment_id',$comment_id)->set($newdata)->update(); 
            $session = session();
            $session->setFlashdata('Forwarded','Comment was forwarded to branch ');
            return redirect()->to('http://localhost/Development/development/public/OngoingReviewAllComments/'.$commentsdetails['auditid']);

        }else if($commentsdetails["status"] == "Forwarded to branch"){
            $session = session();
            $session->setFlashdata('Forwarded','Comment was already forwarded to branch ');
            return redirect()->to('http://localhost/Development/development/public/OngoingReviewAllComments/'.$commentsdetails['auditid']);
        }else{
            $session = session();
            $session->setFlashdata('notaccept','Comment was not still accepted');
            return redirect()->to('http://localhost/Development/development/public/OngoingReviewAllComments/'.$commentsdetails['auditid']); 
        }
        
    }

}
