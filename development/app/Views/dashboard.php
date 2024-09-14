<div class="container">
    <div class="row">
        <div class="col-12" >
        <h5 style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">Employee No-: <?= session()->get('EmpNo') ?> Employee Name-: <?= session()->get('FirstName') ?> <?= session()->get('LastName') ?> <h5>
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
        </div>
    </div>
</div>
</div>

<div class="container">
<div class="col-12 col-sm-8 col-md-12  mt-5 pt-3 pb-3 bg-white from-wrapper " >
    <div class="countainer" style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">
        <div class="countainer">
            <h3>All Modules</h3>
            <hr style="height:2px;border-width:0;color:gray;background-color:gray">
                <table class="table">
                 <tr>   
                        <td> 
                        <div class="ms-2 me-auto">
                        <div class="fw-bold"> Audit Controls -:</div>
                        To create checklist, create audit and team assigning..
                        </td>
                        <td>    
                        <a names="mainChecklist" class="btn btn-primary me-md-2 col-12" href="/Development/development/public/checklistcontrol"> Audit Controls    </a>
                        </td>
                        </div>
                    </tr>
                    <tr>
                        <td>  
                        <div class="ms-2 me-auto">
                        <div class="fw-bold">Entity Controls -:</div>
                        To maintain entity deitals  (Branches,Departments) adding and upgration.
                        </td>
                        <td>  
                        <a names="mainChecklist" class="btn btn-primary me-md-2 col-12 " href="/Development/development/public/entityadding">Entity Controls</a>
                        
                    </td>
                    </tr>
                    <tr>
                    <td>  
                        <div class="ms-2 me-auto">
                        <div class="fw-bold">Report Generating -:</div>
                        To generate report all types of reports. (Admin purposes, Branch related, Audit finding and Audit Teams)
                    </td>
                    <td>  
                        <a names="mainChecklist" class="btn btn-primary me-md-2 col-12 " href="/Development/development/public/ReportAdminDashboard">Report Generating</a>
                    </div>
                    </td>
                    </tr>
                </table>
        </div>
        </div>
    </div>
</div>
