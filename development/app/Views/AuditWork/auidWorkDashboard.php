<div class="container">
    <div class="row">
        <div class="col-12">
        <h5 style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">Employee No-: <?= $user['EmpNo'] ?> Employee Name-: <?= $user['FirstName'] ?> <?= $user['LastName'] ?> <h5>
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
        </div>
    </div>
</div>
</div>

<div class="container">
<div class="col-12 col-sm-8 col-md-12  mt-5 pt-3 pb-3 bg-white from-wrapper " >
    <div class="countainer" style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">
        <div class="countainer">
            <h3> Audit Field Work Releted Modules</h3>
            <hr style="height:2px;border-width:0;color:gray;background-color:gray">
                <table class="table">
                 <tr>   
                        <td> 
                        <div class="ms-2 me-auto">
                        <div class="fw-bold">  Assigned Audit -:</div>
                        All assigned audit that field work not still started (For starting the field work and entering comments, as an enterer)
                        </td>
                        <td>    
                        <a names="mainChecklist" class="btn btn-primary me-md-2 col-12" href="/Development/development/public/Assignedauditall/<?= $user['EmpNo'] ?>"> Assinged Audit    </a>
                        </td>
                    </tr>
                    <tr>
                        <td>  
                        <div class="ms-2 me-auto">
                        <div class="fw-bold">On Going Audit -:</div>
                        Fieldwork started audit, but not completed, (Ongoing audit related works- comment entering and modification as an enterer).
                        </td>
                        <td>  
                        <a names="mainChecklist" class="btn btn-primary me-md-2 col-12 " href="/Development/development/public/Ongoingallaudit/<?= $user['EmpNo'] ?>">Ongoing Audit</a>
                        </td>
                    </tr>
                    <tr>
                        <td>  
                        <div class="ms-2 me-auto">
                        <div class="fw-bold">Editing Replied Comment -:</div>
                        Management replied received comment and reviewer rejected comments for further changes for doing by the enterer.
                        </td>
                        <td>  
                        <a names="mainChecklist" class="btn btn-primary me-md-2 col-12 " href="/Development/development/public/AuditReplyCommentEdit/<?= $user['EmpNo'] ?>">Replied Comment Editing</a>
                        </td>
                    </tr>
                    <tr>
                        <td>  
                        <div class="ms-2 me-auto">
                        <div class="fw-bold">Reviewing Auditt -:</div>
                        All assigned audits for reviewing as a reviewer and all the comments that are submitted for reviewing. (After completing comments, received comment for reviewing, as a reviewer) 
                        </td>
                        <td>  
                        <a names="mainChecklist" class="btn btn-primary me-md-2 col-12 " href="/Development/development/public/ongoingAuditReviewing/<?= $user['EmpNo'] ?>">Reviewing Audit</a>
                        </td>
                    </tr>
                    <tr>
                        <td>  
                        <div class="ms-2 me-auto">
                        <div class="fw-bold">Reviewing Management Reply -:</div>
                        All the comments that are received from branch for reviewing. (After entered management reply for comments by branch, received  management reply from branch for reviewing, as reviewer)
                        </td>
                        <td>  
                        <a names="mainChecklist" class="btn btn-primary me-md-2 col-12 " href="/Development/development/public/AuditReplyReview/<?= $user['EmpNo'] ?>">Reviewing Managment Reply</a>
                        </td>
                    </tr>
                    </tr>
                        <tr>
                        <td>  
                        <div class="ms-2 me-auto">
                        <div class="fw-bold">Completed Audit -:</div>
                        Fieldworks have been completed audit and audit risk was graded branches. (All field work completed and finalized comments with their risk)
                        </td>
                        <td>  
                        <a names="mainChecklist" class="btn btn-primary me-md-2 col-12 " href="/Development/development/public/AuditFinalised/<?= $user['EmpNo'] ?>">Completed Audit</a>
                        </td>
                    </tr>
                    <tr>
                        <td>  
                        <div class="ms-2 me-auto">
                        <div class="fw-bold">Report Generating -:</div>
                        To generate all types of reports available for the audit team.
                        </td>
                        <td>  
                        <a names="mainChecklist" class="btn btn-primary me-md-2 col-12 " href="/Development/development/public/ReportAdminDashboard">Report Generating</a>
                        </td>
                    </tr>
                </table>
        </div>
        </div>
    </div>
</div>
