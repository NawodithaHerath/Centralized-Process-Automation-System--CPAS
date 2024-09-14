<div class="container">
    <div class="col-12 col-sm-12 offset-sm-12 col-md-12 mt-1 pt-1 pb-1 bg-white from-wrapper " >
        <div class="countainer" style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">
        <h5 style=" border: 1px solid; padding: 10px;">Report for Audit Team Assignment</h5>
            <hr style="height:2px;border-width:0;color:gray;background-color:gray">

            <Div><h5> Team assignment related report and data viewing </h5></Div>            
            <a href="<?= base_url('/Development/development/public/ReportAdminDashboard')?>" class="btn btn-secondary float-right">Back</a>

            <div class="row">
                <div class="col-6 col-sm-3 text-left mt-3 ">
                    <label for="filteroption " class="fw-bold" >Select filtering options</label>
                    <select name="filteroption" class="form-control input-lg form-select" id="filteroption" onchange="filteringoption(this.value)">
                        <option value="">Select filtering options</option>
                        <option value="YearWise">Year Wise</option>
                        <option value="EntityWise">Entity Wise</option>
                        <option value="EmployeeWise">Employee Wise</option>
                    </select>
                </div>
                <div class="col-2 col-sm-6 text-left mt-2 ">
                        <div class="col-6"><p class="fw-bold text-secondary">To export data into the excel file</p><button  class="btn btn-success" id="intoExcel" name="intoExcel" title="For exporting all list of audit into Excel">Export into Excel</button> </div>
                    </div>
                </div> 
                
            <style>.hidden {display: none;}</style>
            
            <div class="hidden" id="YearWiseDiv">
            <div class="row">
                <div class="col-6 col-sm-3 text-primary text-left">
                <label for="audityear " class="fw-bold" >Select audit Year</label>
                <select name="audityear" class="form-control input-lg form-select" id="audityear" onchange="teamassignmentdetails(this.value);">
                    <option value="">Select Year</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024"selected>2024</option>
                    </select>
                    </div>
                </div>
            </div>

            <div class="hidden" id="employeewisediv">
                <div class="row">
                    <div class="col-6 col-sm-3  text-primary text-left">
                        <label for="employeewise" class="fw-bold" >Select Employee</label>
                        <select name="employeewise" class="form-control input-lg form-select" id="employeewise" onchange="fetchcreatedauditdata(this.value)">
                            <option value="">Select Employee</option>
                            <?php if($auditteammember):?>
                                    <?php foreach($auditteammember as $row): ?>
                                <option value="<?php echo $row['empno'];?>"><?php echo $row['empno'];?> - <?php echo $row['FirstName']." ".$row['FirstName'];?> </option>
                                <?php endforeach; ?>    
                                <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="hidden" id="entitywisediv">
                <div class="row">
                    <div class="col-6 col-sm-3  text-primary text-left">
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
                
                <div class="row">
                <div class="col-6 col-sm-3 text-primary text-left mt-3">
                <label for="myInput " class="fw-bold" >Seach for Audit ID</label>
                <input class="form-control input-lg" type="text" name="myInput1" id="myInput1" onkeyup="myFunction1()" placeholder="Search for ID.." title="Type in a ID">
                </div>
                <div class="col-6 col-sm-3 text-primary text-left mt-3">
                <label for="myInput2 " class="fw-bold" >Seach for Entity Name</label> 
                <input class="form-control input-lg" type="text" name="myInput2" id="myInput2" onkeyup="myFunction2()" placeholder="Search for Entity.." title="Type in a name">
                </div>
                <div class="col-6 col-sm-3 text-primary text-left mt-3">
                <label for="myInput3 " class="fw-bold" >Seach for date</label> 
                <input class="form-control input-lg" type="text" name="myInput3" id="myInput3" onkeyup="myFunction3()" placeholder="Search for Date.." title="Type in a Date">
                </div>
                <div class="col-6 col-sm-3 text-primary text-left mt-3">
                <label for="myInput3 " class="fw-bold" >Seach for Team Member</label> 
                <input class="form-control input-lg" type="text" name="myInput4" id="myInput4" onkeyup="myFunction4()" placeholder="Search for Date.." title="Type in a Naame">
                </div>
            </div>
            </form>
            <table class="table" id="myTable">
            <thead>
                <tr>
                <th scope="col">Audit Id</th>
                <th scope="col">Entity Name</th> 
                <th scope="col">Examin start date</th>
                <th scope="col">Team Member</th>
                <th scope="col">Email</th>
                </tr>
            </thead>
            <tbody id="auditdetails">

            </tbody>
            </table>


        </div>
    </div>    
</div>

<script>

window.onload =   onPageLoad;

function onPageLoad() {
    var currentDate = new Date();
    let currentyear = currentDate.getFullYear();
    teamassignmentdetails(currentyear);;
     }

    function teamassignmentdetails(audityear){
        $.ajax({
            url:"<?php echo base_url('Development/development/public/AdminReportteamassignmentdetails')?>",
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
            url:"<?php echo base_url('Development/development/public/AdminReportteamassignmentdetdata')?>",
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
            td = tr[i].getElementsByTagName("td")[2];
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

        function myFunction4() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput4");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[3];
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


  audityear.addEventListener('change', function() {
        const filteroptionValue = filteroption.value;
        const selectedValue2 = audityear.value;
            if (filteroptionValue === "YearWise") {
            linkButton.setAttribute('onclick', `window.location.href='AdminReportteamAssignmentExcel/${filteroptionValue}/${selectedValue2}'`);
            } else {
            linkButton.removeAttribute('onclick');
         }
        });

    employeewise.addEventListener('change', function() {
        const filteroptionValue = filteroption.value;
        const selectedValue2 = employeewise.value;
            if (filteroptionValue === "EmployeeWise") {
            linkButton.setAttribute('onclick', `window.location.href='AdminReportteamAssignmentExcel/${filteroptionValue}/${selectedValue2}'`);
            } else {
            linkButton.removeAttribute('onclick');
         }
        });

    wiseentityid.addEventListener('change', function() {
        const filteroptionValue = filteroption.value;
        const selectedValue2 = wiseentityid.value;
            if (filteroptionValue === "EntityWise") {
            linkButton.setAttribute('onclick', `window.location.href='AdminReportteamAssignmentExcel/${filteroptionValue}/${selectedValue2}'`);
            } else {
            linkButton.removeAttribute('onclick');
         }
        });

</script>


<script>
        function filteringoption() {
            var filteroption = document.getElementById("filteroption").value;
            var audityear = document.getElementById("audityear");
            var allaudit = document.getElementById("AllAudit");
            if (filteroption === "YearWise") {
                YearWiseDiv.classList.remove("hidden");
                entitywisediv.classList.add("hidden");
                employeewisediv.classList.add("hidden");
            }else if (filteroption === "EntityWise") {
                YearWiseDiv.classList.add("hidden");
                entitywisediv.classList.remove("hidden");
                employeewisediv.classList.add("hidden");
            }else if (filteroption === "EmployeeWise") {
                YearWiseDiv.classList.add("hidden");
                entitywisediv.classList.add("hidden");
                employeewisediv.classList.remove("hidden");
            }else{
                YearWiseDiv.classList.add("hidden");
                entitywisediv.classList.add("hidden");
            }
        }
</script>