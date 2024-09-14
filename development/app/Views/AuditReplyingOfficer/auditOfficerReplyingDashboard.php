<div class="container">
    <div class="row">
        <div class="col-12" style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">
        <table class="table table-striped ">
        <tr>Employee No-: <?= $user['EmpNo'] ?> </tr> <tr>Employee Name-: <?= $user['FirstName'] ?> <?= $user['LastName'] ?></tr>
        </table>
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
                        <div class="fw-bold">To add management reply-:</div>
                        (To add the managment reply on the assigned comment).
                        </td>
                        <td>  
                        <a names="mainChecklist" class="btn btn-primary me-md-2 col-12 " href="/Development/development/public/allOngoingAuditOfficerReplyingAdd/<?= $user['EmpNo'] ?>"> Add Reply</a>
                        </td>
                    </tr>
                    </tr>
                        <tr>
                        <td>  
                        <div class="ms-2 me-auto">
                        <div class="fw-bold">Completed Audit -:</div>
                        Fieldworks have been completed audit. (All field work completed and  the replies for audit comment have been submited)
                        </td>
                        <td>  
                        <a names="mainChecklist" class="btn btn-primary me-md-2 col-12 " href="/Development/development/public/entityadding">Completed Audit</a>
                        </td>
                    </tr>
                </table>
        </div>
        </div>
    </div>
</div>
