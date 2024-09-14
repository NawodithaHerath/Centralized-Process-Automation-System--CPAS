<?php

namespace App\Controllers;
use App\Models\ChecklistModel;
use App\Models\MainCheckArea;
use App\Models\FirstSubCheckArea;
use App\Models\CheckingItem;
use App\Models\AuditCreationModel;
use App\Models\EntityAdding;
use App\Models\AuditTeamMember;
use App\Models\UserModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ReportAdmin extends BaseController
{
    public function index()
    {

        $data = [];
        echo view('templates/header',$data);
        echo view('ReportAdmin/ReportAdminDashboard');
        echo view('templates/footer');
    }

    public function ReportAdminAllChecklist()
    {
        $data=[];

        $checklistDetails = new ChecklistModel();
        
        $data['mainchecklist'] = $checklistDetails->reportAdminAllChecklist("checklist");

        echo view('templates/header',$data);
        echo view('ReportAdmin/ReportAdminChecklist/AdminReportmainsummary');
        echo view('templates/footer');


    }

    public function ReportAdminAllChecklistExcelExport()
    {
        $checklistDetails = new ChecklistModel();
        $data = $checklistDetails->findAll();
        $file_name = "data.xlsx";

        $spreadsheet = new Spreadsheet();

        $sheet =  $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1','All Created Checklist Details');
        $sheet->setCellValue('B1','Report generated at-'.date("Y-m-d H:i:s"));
        $sheet->setCellValue('A2','Checklist ID');
        $sheet->setCellValue('B2','Audit Category');
        $sheet->setCellValue('C2','Audit Type');
        $sheet->setCellValue('D2','Audit Area');
        $sheet->setCellValue('E2','created By');
        $sheet->setCellValue('F2','Updated At');

        $count = 3;

        foreach($data as $row){
            $sheet->setCellValue('A' . $count ,$row['checklist_id']);
            $sheet->setCellValue('B' . $count ,$row['audit_category']);
            $sheet->setCellValue('C' . $count ,$row['audit_type']);
            $sheet->setCellValue('D' . $count ,$row['audit_area']);
            $sheet->setCellValue('E' . $count ,$row['created_by']);
            $sheet->setCellValue('F' . $count ,$row['updated_at']);
            $count++;
        } 

        $writer  = new Xlsx($spreadsheet);
        $writer->save($file_name);

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=".basename($file_name));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length:'. filesize($file_name));
        flush();
        readfile($file_name);
        exit;
    } 

    public function ReportAdminAllChecklistMainSub($id)
    {
        $data=[];
        $mainCheckArea = new MainCheckArea();

        $data['checklist_id'] = $id  ;

        $data['maincheckarea'] = $mainCheckArea->reportAdminMainCheck('maincheckarea',array('checklist_id'=>$id)); 
        echo view('templates/header',$data);
        echo view('ReportAdmin/ReportAdminChecklist/AdminReportmainCheckAreaSummary');
        echo view('templates/footer');

    }

    public function ReportAdminChecklistMainSubExcelExport($id)
    {

        $mainSubArea = new MainCheckArea();
        $data = $mainSubArea->where('checklist_id',$id)->findall();
        $file_name = "data.xlsx";

        $spreadsheet = new Spreadsheet();

        $sheet =  $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1','Check List ID -'. $data[0]['checklist_id']);
        $sheet->setCellValue('A2','Report generated at-'.date("Y-m-d H:i:s"));
        $sheet->setCellValue('A3','Main Area Id');
        $sheet->setCellValue('B3','Mainarea Description');
        $sheet->setCellValue('C3','Created By');
        $sheet->setCellValue('D3','Updated At');

        $count = 4;

        foreach($data as $row){
            $sheet->setCellValue('A' . $count ,$row['mainarea_id']);
            $sheet->setCellValue('B' . $count ,$row['mainarea_description']);
            $sheet->setCellValue('C' . $count ,$row['created_by']);
            $sheet->setCellValue('D' . $count ,$row['created_at']);
            $count++;
        } 

        $writer  = new Xlsx($spreadsheet);
        $writer->save($file_name);

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=".basename($file_name));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length:'. filesize($file_name));
        flush();
        readfile($file_name);
        exit;
    }

    public function ReportAdminFirstSubArea($id)
    {
        $data=[];
        $FirstSubCheckArea = new FirstSubCheckArea();
        $mainSubArea = new MainCheckArea();
        $data['maincheckarea'] = $mainSubArea->where('mainarea_id',$id)->first();
        $data['mainarea_id'] = $id  ;

        $data['firstsubarea'] = $FirstSubCheckArea->reportAdminFirstAreaCheck('firstsubcheckarea',array('mainarea_id'=>$id)); 
        echo view('templates/header',$data);
        echo view('ReportAdmin/ReportAdminChecklist/AdminReportfirstCheckAreaSummary');
        echo view('templates/footer');

    }

    public function ReportAdminChecklistFirstSubExcelExport($id)
    {

        $mainSubArea = new FirstSubCheckArea();
        $data = $mainSubArea->where('mainarea_id',$id)->findall();
        $file_name = "data.xlsx";

        $spreadsheet = new Spreadsheet();

        $sheet =  $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1','Main Sub Area ID -'. $data[0]['mainarea_id']);
        $sheet->setCellValue('A2','Report generated at-'.date("Y-m-d H:i:s"));
        $sheet->setCellValue('A3','First Area ID');
        $sheet->setCellValue('B3','First Area Description');
        $sheet->setCellValue('C3','Created By');
        $sheet->setCellValue('D3','Created At');

        $count = 4;

        foreach($data as $row){
            $sheet->setCellValue('A' . $count ,$row['firstsubarea_id']);
            $sheet->setCellValue('B' . $count ,$row['firstsubarea_description']);
            $sheet->setCellValue('C' . $count ,$row['created_by']);
            $sheet->setCellValue('D' . $count ,$row['created_at']);
            $count++;
        } 

        $writer  = new Xlsx($spreadsheet);
        $writer->save($file_name);

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=".basename($file_name));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length:'. filesize($file_name));
        flush();
        readfile($file_name);
        exit;
    }

    public function ReportAdminCheckingItem($id)
    {
        $data=[];
        $CheckingItem = new CheckingItem();
        $FirstSubCheckArea = new FirstSubCheckArea();
        $data['firstsubarea'] = $FirstSubCheckArea->where('firstsubarea_id',$id)->first();
        $data['firstsubarea_id'] = $id  ;

        $data['checkingitem'] = $CheckingItem->reportAdminCheckingItem('checkingitem',array('firstsubarea_id'=>$id)); 
        echo view('templates/header',$data);
        echo view('ReportAdmin/ReportAdminChecklist/AdminReportcheckingitemSummary');
        echo view('templates/footer');

    }

    public function ReportAdminChecklistCheckingItemExport($id)
    {

        $mainSubArea = new CheckingItem();
        $data = $mainSubArea->where('firstsubarea_id',$id)->findall();
        $file_name = "data.xlsx";

        $spreadsheet = new Spreadsheet();

        $sheet =  $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1','First Area ID -'. $data[0]['firstsubarea_id']);
        $sheet->setCellValue('A2','Report generated at-'.date("Y-m-d H:i:s"));
        $sheet->setCellValue('A3','Checking Iteme ID');
        $sheet->setCellValue('B3','Checking Iteme Description');
        $sheet->setCellValue('C3','Created By');
        $sheet->setCellValue('D3','Created At');

        $count = 4;

        foreach($data as $row){
            $sheet->setCellValue('A' . $count ,$row['checkingitem_id']);
            $sheet->setCellValue('B' . $count ,$row['checkingitem_description']);
            $sheet->setCellValue('C' . $count ,$row['created_by']);
            $sheet->setCellValue('D' . $count ,$row['created_at']);
            $count++;
        } 

        $writer  = new Xlsx($spreadsheet);
        $writer->save($file_name);

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=".basename($file_name));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length:'. filesize($file_name));
        flush();
        readfile($file_name);
        exit;
    }

    public function ReportAdminEntireChecklist($id)
    {

        $entirechecklist = new MainCheckArea();
        $data = $entirechecklist->reportAdminMainCheckentirechecklist('maincheckarea',array('checklist_id'=>$id));
        $file_name = "data.xlsx";

        $spreadsheet = new Spreadsheet();

        $sheet =  $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1','Checklist ID -' . $id);
        $sheet->setCellValue('A2','Report generated at-'.date("Y-m-d H:i:s"));
        $sheet->setCellValue('A3','Main Area Id');
        $sheet->setCellValue('B3','Mainarea Description');
        $sheet->setCellValue('C3','First Area ID');
        $sheet->setCellValue('D3','First Area Description');
        $sheet->setCellValue('E3','Checking Iteme ID');
        $sheet->setCellValue('F3','Checking Iteme Description');

        $count = 4;

        foreach($data as $row){
            $sheet->setCellValue('A' . $count ,$row['mainarea_id']);
            $sheet->setCellValue('B' . $count ,$row['mainarea_description']);
            $sheet->setCellValue('C' . $count ,$row['firstsubarea_id']);
            $sheet->setCellValue('D' . $count ,$row['firstsubarea_description']);
            $sheet->setCellValue('E' . $count ,$row['checkingitem_id']);
            $sheet->setCellValue('F' . $count ,$row['checkingitem_description']);
            $count++;
        } 

        $writer  = new Xlsx($spreadsheet);
        $writer->save($file_name);

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=".basename($file_name));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length:'. filesize($file_name));
        flush();
        readfile($file_name);
        exit;
    }

    public function ReportAdminAllCreatedAudit()
    {
        $data=[];

        echo view('templates/header',$data);
        echo view('ReportAdmin/ReportAdminCreatedAuidt/ReportAdminCreatedAuditDashboard');
        echo view('templates/footer');
    }
    public function ReportAdminAllCreatedAuditYearly()
    {
        $data=[];

        $checklistDetails = new ChecklistModel();
        $entityDetails = new EntityAdding();
        $statusdetails =  new AuditCreationModel();
        
        $data['mainchecklist'] = $checklistDetails->reportAdminAllChecklist("checklist");
        $data['entityDetails'] = $entityDetails->findAll();
        $data['statusDetails'] = $statusdetails->selectDataAllstatus('auditcreation');

        echo view('templates/header',$data);
        echo view('ReportAdmin/ReportAdminCreatedAuidt/ReportAdminCreatedAuditYearly');
        echo view('templates/footer');

    }

    public function ReportAdminCreatedAuditYearlyWise(){

        $audityear = $this->request->getPost("cId");
        $auditcreationModel = new ChecklistModel();
        $auditcreationData = $auditcreationModel->selectDataAudit("auditcreation",array("audityear"=>$audityear));
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
            <td>'.$auditcreateddetailall['FirstName'].' '.$auditcreateddetailall['LastName'].'</td>
            <td>'.$auditcreateddetailall['status'].'</td>
            </tr>';
        }
        // echo '</table>';
        echo json_encode($output);
    }

    public function ReportAdminCreatedAuditFiltering(){
        $id1 = $this->request->getPost("opt");
        $id2 = $this->request->getPost("cId");
        $auditcreationModel = new ChecklistModel();
        $auditcreationdetails = new AuditCreationModel();
        if($id1 == "ChekclistWise"){
        $auditcreationData = $auditcreationModel->selectDataAudit("auditcreation",array("checklist_id"=>$id2));
        }else if($id1 == "EntityWise"){
        $auditcreationData = $auditcreationdetails->selectDataAudit("auditcreation",array("entity.entityid"=>$id2));
        }else if($id1 == "StatusWise"){
            $auditcreationData = $auditcreationModel->selectDataAudit("auditcreation",array("status"=>$id2));
            }
        $output ="<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
        foreach($auditcreationData as $auditcreateddetailall){
            $output .='<tr>
            <td>'.$auditcreateddetailall['auditid'].'</td>
            <td>'.$auditcreateddetailall['entityname'].'</td>
            <td>'.$auditcreateddetailall['examinstartdate'].'</td>
            <td>'.$auditcreateddetailall['examinendtdate'].'</td>
            <td>'.$auditcreateddetailall['coveredstartdate'].'</td>
            <td>'.$auditcreateddetailall['coveredenddate'].'</td>
            <td>'.$auditcreateddetailall['checklist_id'].'</td>
            <td>'.$auditcreateddetailall['FirstName'].' '.$auditcreateddetailall['LastName'].'</td>
            <td>'.$auditcreateddetailall['status'].'</td>
            </tr>';
        }
        // echo '</table>';
        echo json_encode($output);
    }
    

    public function ReportAdminCreatedAuditYearlyWiseExcel($id1,$id2){

        $auditcreationModel = new AuditCreationModel();
        if($id1=="All"){
            $data = $auditcreationModel->selectDataAllAudit("auditcreation");

        }else if($id1 =="YearWise"){
            $data = $auditcreationModel->selectDataAudit("auditcreation",array("audityear"=>$id2));
        }else if($id1 =="ChekclistWise"){
            $data = $auditcreationModel->selectDataAudit("auditcreation",array("checklist_id"=>$id2));
        }else if($id1 =="EntityWise"){
            $data = $auditcreationModel->selectDataAudit("auditcreation",array("entity.entityid"=>$id2));
        }else if($id1 =="StatusWise"){
            $data = $auditcreationModel->selectDataAudit("auditcreation",array("status"=>$id2));
        }else if($id1 =="Audittype"){
            $data = $auditcreationModel->selectDataAudit("auditcreation",array("audit_type"=>$id2));
        }
        $file_name = "data.xlsx";

        $spreadsheet = new Spreadsheet();

        $sheet =  $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1','Audit Year -' . $id1);
        $sheet->setCellValue('A2','Report generated at-'.date("Y-m-d H:i:s"));
        $sheet->setCellValue('A3','Auditid');
        $sheet->setCellValue('B3','EntityName');
        $sheet->setCellValue('C3','ExaminStartSate');
        $sheet->setCellValue('D3','ExaminEndtDate');
        $sheet->setCellValue('E3','CoveredStartDate');
        $sheet->setCellValue('F3','coveredenddate');
        $sheet->setCellValue('G3','Checklist ID');
        $sheet->setCellValue('H3','Reviewer');
        $sheet->setCellValue('I3','Status');
        $count = 4;

        foreach($data as $row){
            $sheet->setCellValue('A' . $count ,$row['auditid']);
            $sheet->setCellValue('B' . $count ,$row['entityname']);
            $sheet->setCellValue('C' . $count ,$row['examinstartdate']);
            $sheet->setCellValue('D' . $count ,$row['examinendtdate']);
            $sheet->setCellValue('E' . $count ,$row['coveredstartdate']);
            $sheet->setCellValue('F' . $count ,$row['coveredenddate']);
            $sheet->setCellValue('G' . $count ,$row['checklist_id']);
            $sheet->setCellValue('H' . $count ,$row['FirstName']." " .$row['LastName']);
            $sheet->setCellValue('I' . $count ,$row['status']);
            $count++;
        } 

        $writer  = new Xlsx($spreadsheet);
        $writer->save($file_name);

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=".basename($file_name));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length:'. filesize($file_name));
        flush();
        readfile($file_name);
        exit;
    }

    public function ReportAdminAllTeamFilstering(){

        $checklistDetails = new ChecklistModel();
        $entityDetails = new EntityAdding();
        $employe = new AuditTeamMember();
        
        $data['mainchecklist'] = $checklistDetails->reportAdminAllChecklist("checklist");
        $data['entityDetails'] = $entityDetails->findAll();
        $data['auditteammember'] = $employe->auditteamassignmentflteremployee("auditteammember");

        echo view('templates/header',$data);
        echo view('ReportAdmin/ReportAdminAuditTeam/ReportAdminAuditTeam');
        echo view('templates/footer');
        
    }

    public function AdminReportteamassignmentdetails(){

        $audityear = $this->request->getPost("cId");
        $aduitteamdetails = new AuditTeamMember;
        $auditteamData = $aduitteamdetails->auditteamassignment("auditteammember",$audityear);
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
            </tr>";
        }
        echo json_encode($output);
        
    }

    public function AdminReportteamassignmentdetdata(){

        $filteroption = $this->request->getPost("opt");
        $critiria = $this->request->getPost("cId");
        $aduitteamdetails = new AuditTeamMember;
        if($filteroption =="EntityWise"){
        $auditteamData = $aduitteamdetails->auditteamassignmentFilter("auditteammember",array("entity.entityid"=>$critiria));
        }else if($filteroption =="EmployeeWise"){
            $auditteamData = $aduitteamdetails->auditteamassignmentFilter("auditteammember",array("employee.EmpNo"=>$critiria));
        }
        $output ="";
        foreach($auditteamData as $auditcreateddetailall){
            $output .="<tr>
            <td>$auditcreateddetailall->auditid</td>
            <td>$auditcreateddetailall->entityname</td>
            <td>$auditcreateddetailall->examinstartdate</td>
            <td>$auditcreateddetailall->FirstName $auditcreateddetailall->LastName</td>
            <td>$auditcreateddetailall->Email</td>
            </tr>";
        }
        echo json_encode($output);
        
    }

    public function AdminReportteamAssignmentExcel($id1,$id2){

        $aduitteamdetails = new AuditTeamMember;

        if($id1=="YearWise"){
            
            $data = $aduitteamdetails->auditteamassignmentExcel("auditteammember",$id2);
        }else if($id1 =="EntityWise"){
            $data = $aduitteamdetails->auditteamassignmentFilterExcel("auditteammember",array("entity.entityid"=>$id2));
            
        }else if($id1 =="EmployeeWise"){
            $data = $aduitteamdetails->auditteamassignmentFilterExcel("auditteammember",array("employee.EmpNo"=>$id2));
            
        }
        $file_name = "data.xlsx";

        $spreadsheet = new Spreadsheet();

        $sheet =  $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1','Repor Type -' . $id1);
        $sheet->setCellValue('A2','Report generated at-'.date("Y-m-d H:i:s"));
        $sheet->setCellValue('A3','Auditid');
        $sheet->setCellValue('B3','EntityName');
        $sheet->setCellValue('C3','ExaminStartSate');
        $sheet->setCellValue('D3','TeamMember');
        $sheet->setCellValue('E3','Email');
        $count = 4;

        foreach($data as $row){
            $sheet->setCellValue('A' . $count ,$row['auditid']);
            $sheet->setCellValue('B' . $count ,$row['entityname']);
            $sheet->setCellValue('C' . $count ,$row['examinstartdate']);
            $sheet->setCellValue('D' . $count ,$row['FirstName']." " .$row['LastName']);
            $sheet->setCellValue('E' . $count ,$row['Email']);
            $count++;
        } 

        $writer  = new Xlsx($spreadsheet);
        $writer->save($file_name);

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=".basename($file_name));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length:'. filesize($file_name));
        flush();
        readfile($file_name);
        exit;
    }

    public function AdminReportteamassitypedata(){

        $audityear = $this->request->getPost("cId");
        $auditcreationModel = new ChecklistModel();
        $auditcreationData = $auditcreationModel->selectDataAudit("auditcreation",array("audit_type"=>$audityear));
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
            <td>'.$auditcreateddetailall['FirstName'].' '.$auditcreateddetailall['LastName'].'</td>
            <td>'.$auditcreateddetailall['status'].'</td>
            </tr>';
        }
        // echo '</table>';
        echo json_encode($output);
    }

    public function ReportAdminCreatedAudittypeExcel($id1,$id2){

        $auditcreationModel = new AuditCreationModel();
        if($id1=="All"){
            $data = $auditcreationModel->selectDataAllAudit("auditcreation");

        }else if($id1 =="YearWise"){
            $data = $auditcreationModel->selectDataAudit("auditcreation",array("audityear"=>$id2));
        }else if($id1 =="ChekclistWise"){
            $data = $auditcreationModel->selectDataAudit("auditcreation",array("checklist_id"=>$id2));
        }else if($id1 =="EntityWise"){
            $data = $auditcreationModel->selectDataAudit("auditcreation",array("entity.entityid"=>$id2));
        }else if($id1 =="StatusWise"){
            $data = $auditcreationModel->selectDataAudit("auditcreation",array("status"=>$id2));
        }
        $file_name = "data.xlsx";

        $spreadsheet = new Spreadsheet();

        $sheet =  $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1','Audit Year -' . $id1);
        $sheet->setCellValue('A2','Report generated at-'.date("Y-m-d H:i:s"));
        $sheet->setCellValue('A3','Auditid');
        $sheet->setCellValue('B3','EntityName');
        $sheet->setCellValue('C3','ExaminStartSate');
        $sheet->setCellValue('D3','ExaminEndtDate');
        $sheet->setCellValue('E3','CoveredStartDate');
        $sheet->setCellValue('F3','coveredenddate');
        $sheet->setCellValue('G3','Checklist ID');
        $sheet->setCellValue('H3','Reviewer');
        $sheet->setCellValue('I3','Status');
        $count = 4;

        foreach($data as $row){
            $sheet->setCellValue('A' . $count ,$row['auditid']);
            $sheet->setCellValue('B' . $count ,$row['entityname']);
            $sheet->setCellValue('C' . $count ,$row['examinstartdate']);
            $sheet->setCellValue('D' . $count ,$row['examinendtdate']);
            $sheet->setCellValue('E' . $count ,$row['coveredstartdate']);
            $sheet->setCellValue('F' . $count ,$row['coveredenddate']);
            $sheet->setCellValue('G' . $count ,$row['checklist_id']);
            $sheet->setCellValue('H' . $count ,$row['FirstName']." " .$row['LastName']);
            $sheet->setCellValue('I' . $count ,$row['status']);
            $count++;
        } 

        $writer  = new Xlsx($spreadsheet);
        $writer->save($file_name);

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=".basename($file_name));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length:'. filesize($file_name));
        flush();
        readfile($file_name);
        exit;
    }
    
}
