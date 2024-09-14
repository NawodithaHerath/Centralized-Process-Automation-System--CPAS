<div class="container">
    <div class="row">
        <div class="col-12" style="  border: 1px solid; padding: 2px; box-shadow: 5px 10px 18px #888888;">
        <table class="table table-striped" >
        <tr >Employee No-: <?= $user['EmpNo'] ?> </tr> <tr>Employee Name-: <?= $user['FirstName'] ?> <?= $user['LastName'] ?></tr>
        </table>
        </div>
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
        </div>
        <h4> Branch Details</h4>
            <br>
            <table class="table table-striped ">
            <tr><th>Entity ID</th><th>Entity Type</th><th>Entity Name</th><th>Manager</th><th>Assistant Manager</th></tr>
            <tr><td><?= $entitydetails['entityid'] ?></td><td><?= $entitydetails['entitytype'] ?></td><td><?= $entitydetails['entityname'] ?></td><td><?= $manager ?></td><td><?= $Assistmanager ?></td></tr>
            </table>
        </div>
</div>
</div>

<div class="container">
<div class="col-12 col-sm-8 col-md-12  mt-5 pt-3 pb-3 bg-white from-wrapper " >
    <div class="countainer" style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">
        <div class="countainer">
            <h3> Audit Replying Releted Modules</h3>
            <hr style="height:2px;border-width:0;color:gray;background-color:gray">
                <table class="table">
                    <tr>
                        <td>  
                        <div class="ms-2 me-auto">
                        <div class="fw-bold">To assign comment -:</div>
                        (Comments should be assinged to reponse officer for replying).
                        </td>
                        <td>  
                        <a names="mainChecklist" class="btn btn-primary me-md-2 col-12 " href="/Development/development/public/allOngoingAuditReplyingAssign/<?= $user['EmpNo'] ?>">Comment Assign</a>
                        </td>
                    </tr>
                    <tr>
                        <td>  
                        <div class="ms-2 me-auto">
                        <div class="fw-bold">To add management reply-:</div>
                        (To add the managment reply on the assigned comment).
                        </td>
                        <td>  
                        <a names="mainChecklist" class="btn btn-primary me-md-2 col-12 " href="/Development/development/public/allOngoingAuditReplyingAdd/<?= $user['EmpNo'] ?>"> Add Reply</a>
                        </td>
                    </tr>
                    <tr>
                        <td>  
                        <div class="ms-2 me-auto">
                        <div class="fw-bold">To review response officer's reply -:</div>
                        (Reviewing of the management reply provided by response officer).
                        </td>
                        <td>  
                        <a names="mainChecklist" class="btn btn-primary me-md-2 col-12 " href="/Development/development/public/allOngoingAuditReplyingReview/<?= $user['EmpNo'] ?>"> Review Reply</a>
                        </td>
                    </tr>

                    </tr>
                        <tr>
                        <td>  
                        <div class="ms-2 me-auto">
                        <div class="fw-bold">Completed Audit -:</div>
                        Fieldworks have been completed audit. (All field work completed and reply for audit comment have been submited)
                        </td>
                        <td>  
                        <a names="mainChecklist" class="btn btn-primary me-md-2 col-12 " href="/Development/development/public/AllReplyCompleted/<?= $user['EmpNo'] ?>">Completed Audit</a>
                        </td>
                    </tr>
                    </tr>
                        <tr>
                        <td>  
                        <div class="ms-2 me-auto">
                        <div class="fw-bold">Report generating  -:</div>
                            MIS Report generating (Report generating module for management information)
                        </td>
                        <td>  
                        <a names="mainChecklist" class="btn btn-primary me-md-2 col-12 " href="/Development/development/public/entityadding">Report Generating</a>
                        </td>
                    </tr>
                </table>
        </div>
        </div>
    </div>
</div>
