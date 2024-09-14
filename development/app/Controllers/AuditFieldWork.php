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
use App\Models\managerReplyModel;
    
class AuditFieldWork extends BaseController
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
        {
            $model = new UserModel();
            $user = $model->where('EmpNo',$empno)->first();
            $data['empno']= $empno;
            $data['user']= $user ;

            echo view('templates/headerfieldwork',$data);
            echo view('AuditWork/auidWorkDashboard');
            echo view('templates/footer');
        }

    }
    public function Assignedauditall($empno){

        
        $data['empno']= $empno;
        echo view('templates/headerfieldwork');
        echo view('AuditWork/assignedallaudit',$data);
        echo view('templates/footer'); 
    }

    
    public function createdauditdetailall(){

        $audityear = $this->request->getPost("cId");
        $empno = $this->request->getPost("empno");
        
        $auditcreationData = $this->auditfieldwork->assignedAuditNotStart("auditcreation",array("audityear"=>$audityear),array("empno"=>$empno));
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
            <td><a href='/Development/development/public/sessionauditid/$auditcreateddetailall->auditid' class='btn btn-secondary'>Start</a></td>
            </tr>";
        }
        echo json_encode($output);

    }

    public function sessionauditid($auditid){
        
        $sessionauditid = ['auditid' => $auditid];
        session()->set($sessionauditid);
        return redirect()->to('http://localhost/Development/development/public/commententering/'.$auditid);

    }

    public function commententering($auditid){
        
        $sessionauditid = ['auditid' => $auditid];
        session()->set($sessionauditid);
        
        $auditcreateddetails = new AuditCreationModel();
        $auditcreationdetails = $auditcreateddetails->where('auditid',$auditid)->first();
        $checklistid = $auditcreationdetails['checklist_id'];

        $maincheckarea = new MainCheckArea();
        $maincheckareadetails = $maincheckarea->where('checklist_id',$checklistid)->findAll();
        
        $data = [];
        $data['auditid']= $auditid;
        $data['auditcreationdetails']= $auditcreationdetails;
        $data['maincheckareadetails']= $maincheckareadetails;



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
                         'required'=>' Sample details should be added',
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

                $mainareadata = new MainCheckArea();
                $mainareadetails = $mainareadata->where('mainarea_id',$this->request->getPost('mainarea_id'))->first();
                $maindescription =  $mainareadetails['mainarea_description'];

                
                $firstareadata = new FirstSubCheckArea();
                $firstareadetails = $firstareadata->where('firstsubarea_id',$this->request->getPost('firstsubarea_id'))->first();
                $firstsubarea_description =  $firstareadetails['firstsubarea_description'];

                $checkingitemdata = new CheckingItem();
                $checkingitemdetails = $checkingitemdata->where('checkingitem_id',$this->request->getPost('checkingitem_id'))->first();
                $checkingitem_description = $checkingitemdetails['checkingitem_description'];

                $phrase = $this->request->getPost('checkingitem_id');
                $excludeWord = $this->request->getPost('checklist_id');
                $remainingSpaceremove = str_replace(" ",'', $phrase);
                $remainingPhrase = str_replace($excludeWord,'', $remainingSpaceremove);
                $data['postdata'] = [
                    'comment_id' => $auditid.$remainingPhrase,
                    'mainarea_id' => $this->request->getPost('mainarea_id'),
                    'mainarea_description' =>  $maindescription, 
                    'firstsubarea_id' => $this->request->getPost('firstsubarea_id'),
                    'firstsubarea_description' =>  $firstsubarea_description, 
                    'checkingitem_id' => $this->request->getPost('checkingitem_id'),
                    'checkingitem_description'=> $checkingitem_description,
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
                    'status' => 'Entering',
                    'statusupdate_at'=> date("Y-m-d H:i:s"),
                ];

                echo view('templates/headerfieldwork');
                echo view('AuditWork/commententering',$data);
                echo view('templates/footer'); 

            }else{

                $mainareadata = new MainCheckArea();
                $mainareadetails = $mainareadata->where('mainarea_id',$this->request->getPost('mainarea_id'))->first();
                $maindescription =  $mainareadetails['mainarea_description'];

                
                $firstareadata = new FirstSubCheckArea();
                $firstareadetails = $firstareadata->where('firstsubarea_id',$this->request->getPost('firstsubarea_id'))->first();
                $firstsubarea_description =  $firstareadetails['firstsubarea_description'];

                $checkingitemdata = new CheckingItem();
                $checkingitemdetails = $checkingitemdata->where('checkingitem_id',$this->request->getPost('checkingitem_id'))->first();
                $checkingitem_description = $checkingitemdetails['checkingitem_description'];

                $phrase = $this->request->getPost('checkingitem_id');
                $excludeWord = $this->request->getPost('checklist_id');
                $remainingSpaceremove = str_replace(" ",'', $phrase);
                $remainingPhrase = str_replace($excludeWord,'', $remainingSpaceremove);
                
                $data['postdata'] = [
                    'comment_id' => $auditid.$remainingPhrase,
                    'mainarea_id' => $this->request->getPost('mainarea_id'),
                    'mainarea_description' =>  $maindescription, 
                    'firstsubarea_id' => $this->request->getPost('firstsubarea_id'),
                    'firstsubarea_description' =>  $firstsubarea_description, 
                    'checkingitem_id' => $this->request->getPost('checkingitem_id'),
                    'checkingitem_description'=> $checkingitem_description,
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
                    'status' => 'Entering',
                ];

                $newdata = [
                    'comment_id' => $auditid.$remainingPhrase,
                    'auditid' => $auditid,
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
                    'created_by' => session()->get('EmpNo'),
                    'created_at' => date("Y-m-d H:i:s"),
                    'status' => 'Entering',
                    'statusupdate_at'=> date("Y-m-d H:i:s"),   
                ];

                $commentid = $auditid.$remainingPhrase;
                $model = new CommentsModel();
                $commentavailable= $model->where('comment_id',$commentid)->first();

                $auditstatus = new AuditCreationModel();
                $auditstatus->where('auditid',$auditid)->set('status','started')->update();

                if($commentavailable){
                    $model->where('comment_id',$commentid)->set($newdata)->update();

                    $commentlog = new CommentsLogModel();
                    $commentlogdata = [
                        'comment_id'=> $commentid,
                        'status'=> 'Editing',
                        'updated_by' => session()->get('EmpNo'),
                        'updated_at' => date("Y-m-d H:i:s"),  
                        ];
                    $commentlog->save($commentlogdata);


                }else{
                    $model->save($newdata); 

                    $commentlog = new CommentsLogModel();
                    $commentlogdata = [
                        'comment_id'=> $commentid,
                        'status'=> 'Entered',
                        'updated_by' => session()->get('EmpNo'),
                        'updated_at' => date("Y-m-d H:i:s"),  
                        ];
                    $commentlog->save($commentlogdata);

                }
                $session = session();
                $session->setFlashdata('success','Comment was successfully saved');

                echo view('templates/headerfieldwork');
                echo view('AuditWork/commententering',$data);
                echo view('templates/footer'); 
            
            }            
            }
            else{

        echo view('templates/headerfieldwork');
        echo view('AuditWork/commententering',$data);
        echo view('templates/footer'); 

        }

    }


    public function commentfirstareadetails(){

        $mainarea_id = $this->request->getPost("cId");
        $firstareadetails = $this->auditfieldwork->selectData("firstsubcheckarea",array("mainarea_id"=>$mainarea_id));
        $output ='<option value=""> Select first Sub Area </option>';
        foreach($firstareadetails as $firstarea){
             $output .= "<option value='$firstarea->firstsubarea_id'> $firstarea->firstsubarea_id - $firstarea->firstsubarea_description</option>";
        }
        echo json_encode($output);

    }

    public function commentcheckitemdeails(){

        $firstsubarea_id = $this->request->getPost("cId");
        $checkingitemdata = $this->auditfieldwork->selectData("checkingitem",array("firstsubarea_id"=>$firstsubarea_id));
        $output ='<option value=""> Select checking item </option>';
        foreach($checkingitemdata as $checkitem){
             $output .= "<option value='$checkitem->checkingitem_id'>$checkitem->checkingitem_id - $checkitem->checkingitem_description</option>";
        }
        echo json_encode($output);

    }

    public function alreadycommententer(){

        $phrase = $this->request->getPost('alreadycheckitem');
        $excludeWord = $this->request->getPost('alreadychecklsitid');
        $alreadyauditid = $this->request->getPost('alreadyauditid');
        $remainingSpaceremove = str_replace(" ",'', $phrase);
        $remainingPhrase = str_replace($excludeWord,'', $remainingSpaceremove);

       $commentId =  $alreadyauditid.$remainingPhrase;
       $model = new CommentsModel();
       $commentIdalldetails= $model->where('comment_id',$commentId)->first();
            $output = $commentIdalldetails['comment_id'];
        echo json_encode($output);

    }

    public function Ongoingallaudit($empno){

        
        $data['empno']= $empno;
        echo view('templates/headerfieldwork');
        echo view('AuditWork/ongoingassignedaudit',$data);
        echo view('templates/footer'); 
    }

    public function ongoingassingedaudit(){

        $audityear = $this->request->getPost("cId");
        $empno = $this->request->getPost("empno");
        $auditcreationData = $this->auditfieldwork->assignedAuditStart("auditcreation",array("audityear"=>$audityear),array("auditteammember.empno"=>$empno));
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
            <td><a href='/Development/development/public/sessionauditidcomment/$auditcreateddetailall->auditid' class='btn btn-secondary'>Comments</a></td>
            </tr>";
        }
        echo json_encode($output);

    }

    public function sessionauditidcomment($auditid){
        
        $sessionauditid = ['auditid' => $auditid];
        session()->set($sessionauditid);
        return redirect()->to('http://localhost/Development/development/public/Ongoingallcomments/'.$auditid);

    }

    public function Ongoingallcomments($auditid){
        
        $auditcreateddetails = new AuditCreationModel();
        $auditcreationdetails = $auditcreateddetails->selectDataAudit('auditcreation',array('auditid'=>$auditid));
        
        $data = [];
        $data['auditcreationdetails'] =  $auditcreationdetails[0];
        $data['auditid'] = $auditid;
        // $model = new CommentsModel();
        $data['commentsdetails'] = $this->commentsdetails->auditongoingall(array("auditid"=>$auditid));

        echo view('templates/headerfieldwork');
        echo view('AuditWork/ongoingAllComments',$data);
        echo view('templates/footer'); 
    }

    public function OngoingCommentSubmitDelete($comment_id){

        $comments = new CommentsModel();
        $managerReply = new managerReplyModel();

        
        $commentsdetails = $comments->where('comment_id',$comment_id)->first();
        $commentReplyDetails=  $managerReply->where('comment_id',$comment_id)->first();
        if ($commentsdetails['status'] == 'Entering' && $commentReplyDetails ==''){

            $commentlog = new CommentsLogModel();
            if($commentsdetails['created_by'] == session()->get('EmpNo')){
                    $comments->where('comment_id',$comment_id)->delete();
                    $commentlogdata = [
                        'comment_id'=> $comment_id,
                        'status'=> 'Deleted',
                        'updated_by' => session()->get('EmpNo'),
                        'updated_at' => date("Y-m-d H:i:s"),  
                        ];
                    $commentlog->save($commentlogdata);
                    $session = session();
                    $session->setFlashdata('success','Comment was successfully deleted');
                    return redirect()->to('http://localhost/Development/development/public/Ongoingallcomments/'.$commentsdetails['auditid']);
                }else{
                    $session = session();
                    $session->setFlashdata('failed','Comment can not be deleted, comment was not created by this user.');
                    return redirect()->to('http://localhost/Development/development/public/Ongoingallcomments/'.$commentsdetails['auditid']);
                }
        }else{
            $session = session();
            $session->setFlashdata('failed','Comment can not be deleted, comment was not in entering status.');
            return redirect()->to('http://localhost/Development/development/public/Ongoingallcomments/'.$commentsdetails['auditid']);
        }
       

    }

    public function OngoingCommentEdit($comment_id){
        
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
                         'required'=>' Sample details should be added',
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
                echo view('AuditWork/commentediting',$data);
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
                    'status' => 'Entering',
                    'updated_by' => session()->get('EmpNo'),
                    'updated_at' => date("Y-m-d H:i:s"),
                    'statusupdate_at'=> date("Y-m-d H:i:s"),
                       
                ];

                $model = new CommentsModel();
                $model->where('comment_id',$comment_id)->set($newdata)->update();

                $commentlog = new CommentsLogModel();
                $commentlogdata = [
                    'comment_id'=> $comment_id,
                    'status'=> 'Edited',
                    'updated_by' => session()->get('EmpNo'),
                    'updated_at' => date("Y-m-d H:i:s"),  
                    ];
                $commentlog->save($commentlogdata);

                $session = session();
                $session->setFlashdata('success','Comment was successfully saved');
                return redirect()->to('http://localhost/Development/development/public/OngoingCommentEdit/'.$comment_id);
            }

        }else{
        echo view('templates/headerfieldwork');
        echo view('AuditWork/commentediting',$data);
        echo view('templates/footer'); 
        
         }
    }

    public function OngoingCommentSubmitReview($comment_id){

        $comments = new CommentsModel();
        $newdata = [
            'status'=> 'Submit for review',
            'statusupdate_at'=> date("Y-m-d H:i:s")
        ];
        $commentsdetails = $comments->where('comment_id',$comment_id)->first();
        if($commentsdetails["status"] == "Entering" || $commentsdetails["status"] == "Reject"){
            $comments->where('comment_id',$comment_id)->set($newdata)->update();

            $commentlog = new CommentsLogModel();
            $commentlogdata = [
                'comment_id'=> $comment_id,
                'status'=> 'Submit for review',
                'updated_by' => session()->get('EmpNo'),
                'updated_at' => date("Y-m-d H:i:s"),  
                ];
            $commentlog->save($commentlogdata);
            $session = session();
            $session->setFlashdata('success','Comment was successfully submitted for review');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentEdit/'.$comment_id);

        }else if($commentsdetails["status"] == 'Submit for review' || $commentsdetails["status"] == 'Reviewing' ){
            $session = session();
            $session->setFlashdata('success','Comment was already submitted for review');
            return redirect()->to('http://localhost/Development/development/public/OngoingCommentEdit/'.$comment_id);

        }
    }



}
