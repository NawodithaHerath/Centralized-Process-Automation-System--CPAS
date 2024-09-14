<div class="container-fluid ">
    <div class="col-12 col-sm-12 offset-sm-12 col-md-12 mt-1 pt-1 pb-1 bg-white from-wrapper " >
        <div class="countainer" style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">
        <h5 style=" border: 1px solid; padding: 10px;">All created audit</h5>
            <hr style="height:2px;border-width:0;color:gray;background-color:gray">
            <Div><h5> Created all audit and their details</h5></Div> 
            <a href="<?= base_url('/Development/development/public/ReportAdminAllCreatedAudit')?>" class="btn btn-secondary float-right">Back</a>
                <div class="row">
                <div class="col-6 col-sm-3 text-left mt-3 ">
                    <label for="filteroption " class="fw-bold" >Select filtering options</label>
                    <select name="filteroption" class="form-control input-lg form-select" id="filteroption" onchange="filteringoption(this.value)">
                        <option value="">Select filtering options</option>
                        <option value="All">All</option>
                        <option value="YearWise">Year Wise</option>
                        <option value="ChekclistWise">Chekclist Wise</option>
                        <option value="EntityWise">Entity Wise</option>
                        <option value="StatusWise">Status Wise</option>
                        <option value="Audittype">Audit Type</option>
                    </select>
                </div>
                <div class="col-2 col-sm-6 text-left mt-2 ">
                        <div class="col-6"><p class="fw-bold text-secondary">To export data into the excel file</p><button  class="btn btn-success" id="intoExcel" name="intoExcel" title="For exporting all list of audit into Excel">Export into Excel</button> </div>
                    </div>
                </div> 
            <style>.hidden {display: none;}</style>

            <div class="hidden" id="YearWiseDiv">
                <div class="row">
                    <div class="col-6 col-sm-3 text-left mt-3 ">
                        <label for="audityear " class="fw-bold" >Select audit Year</label>
                        <select name="audityear" class="form-control input-lg form-select" id="audityear" onchange="fetchcreatedauditall(this.value)">
                            <option value="">Select Year</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="hidden" id="ChecklistWiseDiv">
                <div class="row">
                    <div class="col-6 col-sm-3 text-left mt-3 ">
                        <label for="wiseChecklistid" class="fw-bold" >Select Checklist ID</label>
                        <select name="wiseChecklistid" class="form-control input-lg form-select" id="wiseChecklistid" onchange="fetchcreatedauditdata(this.value)">
                            <option value="">Select Checklsit ID</option>
                            <?php if($mainchecklist):?>
                                    <?php foreach($mainchecklist as $row): ?>
                                <option value="<?php echo $row['checklist_id'];?>"><?php echo $row['checklist_id'];?> - <?php echo $row['checklist_description'];?> </option>
                                <?php endforeach; ?>    
                                <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="hidden" id="entitywisediv">
                <div class="row">
                    <div class="col-6 col-sm-3 text-left mt-3 ">
                        <label for="wiseentityid" class="fw-bold" >Select Entity</label>
                        <select name="wiseentityid" class="form-control input-lg form-select" id="wiseentityid" onchange="fetchcreatedauditdata(this.value)">
                            <option value="">Select Entity</option>
                            <?php if($entityDetails):?>
                                    <?php foreach($entityDetails as $row): ?>
                                <option value="<?php echo $row['entityid'];?>"><?php echo $row['entityid'];?> - <?php echo $row['entityname'];?> </option>
                                <?php endforeach; ?>    
                                <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="hidden" id="statusdiv">
                <div class="row">
                    <div class="col-6 col-sm-3 text-left mt-3 ">
                        <label for="wiseauditstatus" class="fw-bold" >Select Audit Status</label>
                        <select name="wiseauditstatus" class="form-control input-lg form-select" id="wiseauditstatus" onchange="fetchcreatedauditdata(this.value)">
                            <option value="">Select Status</option>
                            <?php if($statusDetails):?>
                                    <?php foreach($statusDetails as $row): ?>
                                <option value="<?php echo $row['status'];?>"><?php echo $row['status'];?> </option>
                                <?php endforeach; ?>    
                                <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>

            
            <div class="hidden" id="Typediv">
                <div class="row">
                    <div class="col-6 col-sm-3 text-left mt-3 ">
                        <label for="wiseauditype" class="fw-bold" >Select Audit Status</label>
                        <select name="wiseauditype" class="form-control input-lg form-select" id="wiseauditype" onchange="fetchcreatedauditdatatype(this.value)">
                            <option value="">Select Type</option>
                            <option value="onsite">Onsite</option>
                            <option value="offsite">Offsite</option>
                        </select>
                    </div>
                </div>
            </div>

                <div class="row">
                <div class="col-6 col-sm-3 text-left mt-3 ">
                <label for="myInput " class="fw-bold text-primary" >Search from ID</label>
                <input class="form-control input-lg border-primary" type="text" id="myInput1" onkeyup="myFunction1()" placeholder="Search for ID.." title="Type in a ID">
                </div>
                <div class="col-6 col-sm-3 text-left mt-3 ">
                <label for="myInput2 " class="fw-bold text-primary" >Seach from Entity Name</label> 
                <input class="form-control input-lg border-primary" type="text" id="myInput2" onkeyup="myFunction2()" placeholder="Search for Entity Name.." title="Type in a Checklist">
                </div>
                <div class="col-6 col-sm-3 text-left mt-3 ">
                <label for="myInput3 " class="fw-bold text-primary" >Seach from Checklist ID</label> 
                <input class="form-control input-lg border-primary" type="text" id="myInput3" onkeyup="myFunction3()" placeholder="Search for Checklsit Id.." title="Type in a name">
                </div>
            </div>

            <table class="table"id=myTable >
            <thead>
                <tr>
                <th scope="col">Audit Id</th>
                <th scope="col">Entity Name</th>
                <th scope="col">Examin start date</th>
                <th scope="col">Examin end date</th>
                <th scope="col">Covered period start date</th>
                <th scope="col">Covered period end date</th>
                <th scope="col">Checklsit ID</th>
                <th scope="col">Reviewer</th>
                <th scope="col">Audit Status</th>

                </tr>
            </thead>
            <tbody id="auditdetails">

            </tbody>
            </table>

        </div>
    </div>    
</div>

<script>

// window.onload =   onPageLoad;

// function onPageLoad() {
//     var currentDate = new Date();
//     let currentyear = currentDate.getFullYear();
//     fetchcreatedauditall(currentyear);
//      }


    function fetchcreatedauditall(audityear){
        $.ajax({
            url:"<?php echo base_url('Development/development/public/ReportAdminCreatedAuditYearlyWise')?>",
            method:"POST",
            data:{
                cId:audityear
            },
            success:function(result){
                let data = JSON.parse(result);
                document.querySelector("#auditdetails").innerHTML = data;
                console.log(result);
            }
        });
    }

    function fetchcreatedauditdata(critiria){
        var filteroption = document.getElementById("filteroption").value;
        $.ajax({
            url:"<?php echo base_url('Development/development/public/ReportAdminCreatedAuditFiltering')?>",
            method:"POST",
            data:{
                cId:critiria,
                opt:filteroption
            },
            success:function(result){
                let data = JSON.parse(result);
                document.querySelector("#auditdetails").innerHTML = data;
                console.log(result);
            }
        });
    }

    function fetchcreatedauditdatatype(critiria){
        var filteroption = document.getElementById("filteroption").value;
        $.ajax({
            url:"<?php echo base_url('Development/development/public/AdminReportteamassitypedata')?>",
            method:"POST",
            data:{
                cId:critiria,
            },
            success:function(result){
                let data = JSON.parse(result);
                document.querySelector("#auditdetails").innerHTML = data;
                console.log(result);
            }
        });
    }

    function myFunction1() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput1");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
            }       
        }
        }

        function myFunction2() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput2");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1];
            if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
            }       
        }
        }
        function myFunction3() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput3");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[6];
            if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
            }       
        }
        }
</script>

<script>

  const filteroption = document.getElementById('filteroption');  
  const audityear = document.getElementById('audityear');
  const linkButton = document.getElementById('intoExcel');
  var filteroptionValue = document.getElementById("filteroption").value;


    filteroption.addEventListener('change', function() {
        const filteroptionValue = filteroption.value;
         const selectedValue2 =  "NO";
            if (filteroptionValue === "All") {
            linkButton.setAttribute('onclick', `window.location.href='ReportAdminCreatedAuditYearlyWiseExcel/${filteroptionValue}/${selectedValue2}'`);
            } else {
            linkButton.removeAttribute('onclick');
         }
        });

    audityear.addEventListener('change', function() {
        const filteroptionValue = filteroption.value;
        const selectedValue2 = audityear.value;
            if (filteroptionValue === "YearWise") {
            linkButton.setAttribute('onclick', `window.location.href='ReportAdminCreatedAuditYearlyWiseExcel/${filteroptionValue}/${selectedValue2}'`);
            } else {
            linkButton.removeAttribute('onclick');
         }
        });
    wiseChecklistid.addEventListener('change', function() {
        const filteroptionValue = filteroption.value;
        const selectedValue2 = wiseChecklistid.value;
            if (filteroptionValue === "ChekclistWise") {
            linkButton.setAttribute('onclick', `window.location.href='ReportAdminCreatedAuditYearlyWiseExcel/${filteroptionValue}/${selectedValue2}'`);
            } else {
            linkButton.removeAttribute('onclick');
         }
        });

    wiseentityid.addEventListener('change', function() {
        const filteroptionValue = filteroption.value;
        const selectedValue2 = wiseentityid.value;
            if (filteroptionValue === "EntityWise") {
            linkButton.setAttribute('onclick', `window.location.href='ReportAdminCreatedAuditYearlyWiseExcel/${filteroptionValue}/${selectedValue2}'`);
            } else {
            linkButton.removeAttribute('onclick');
         }
        });

    wiseauditstatus.addEventListener('change', function() {
        const filteroptionValue = filteroption.value;
        const selectedValue2 = wiseauditstatus.value;
            if (filteroptionValue === "StatusWise") {
            linkButton.setAttribute('onclick', `window.location.href='ReportAdminCreatedAuditYearlyWiseExcel/${filteroptionValue}/${selectedValue2}'`);
            } else {
            linkButton.removeAttribute('onclick');
         }
         
    });

    wiseauditype.addEventListener('change', function() {
        const filteroptionValue = filteroption.value;
        const selectedValue2 = wiseauditype.value;
            if (filteroptionValue === "Audittype") {
            linkButton.setAttribute('onclick', `window.location.href='ReportAdminCreatedAuditYearlyWiseExcel/${filteroptionValue}/${selectedValue2}'`);
            } else {
            linkButton.removeAttribute('onclick');
         }
         
    });

    
  
</script>

<script>
        function filteringoption() {
            var filteroption = document.getElementById("filteroption").value;
            var allaudit = document.getElementById("AllAudit");
            var audityear = document.getElementById("audityear");
            if((filteroption === "All")){
                YearWiseDiv.classList.add("hidden");
                ChecklistWiseDiv.classList.add("hidden");
                entitywisediv.classList.add("hidden");
            }
            else if (filteroption === "YearWise") {
                YearWiseDiv.classList.remove("hidden");
                ChecklistWiseDiv.classList.add("hidden");
                entitywisediv.classList.add("hidden");
                statusdiv.classList.add("hidden");
            }else if (filteroption === "ChekclistWise") {
                YearWiseDiv.classList.add("hidden");
                ChecklistWiseDiv.classList.remove("hidden");
                entitywisediv.classList.add("hidden");
                statusdiv.classList.add("hidden");
            }else if (filteroption === "EntityWise") {
                YearWiseDiv.classList.add("hidden");
                ChecklistWiseDiv.classList.add("hidden");
                entitywisediv.classList.remove("hidden");
                statusdiv.classList.add("hidden");
            }else if (filteroption === "StatusWise") {
                YearWiseDiv.classList.add("hidden");
                ChecklistWiseDiv.classList.add("hidden");
                entitywisediv.classList.add("hidden");
                statusdiv.classList.remove("hidden");
            }else if(filteroption === "Audittype"){
                YearWiseDiv.classList.add("hidden");
                ChecklistWiseDiv.classList.add("hidden");
                entitywisediv.classList.add("hidden");
                statusdiv.classList.add("hidden");
                Typediv.classList.remove("hidden");
            }else {
                YearWiseDiv.classList.add("hidden");
                ChecklistWiseDiv.classList.add("hidden");
                entitywisediv.classList.add("hidden");
                statusdiv.classList.add("hidden");
            }
        }
</script>
