<div class="container">
    <div class="col-12 col-sm-12 offset-sm-12 col-md-12 mt-1 pt-1 pb-1 bg-white from-wrapper " >
        <div class="countainer" style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">
        <h5 style=" border: 1px solid; padding: 10px; background-color:green; color:white ">All Assigned  Ongoing Audit For Reviewing</h5>
            <hr style="height:2px;border-width:0;color:gray;background-color:gray">
            <h6 style=" border: 1px solid; padding: 10px;">Revewing Audit</h6>
            <Div><h5> Employee no: <?php echo $empno;?></h5></Div> 
            <a href="<?= base_url('/Development/development/public/AuditFieldWork/'.session()->get('EmpNo')) ?>" class="btn btn-secondary float-right">Back</a>
            <div class="row">
                <div class="col-12 col-sm-5  text-left">
                <label for="audityear " class="fw-bold" >Select audit Year</label>
                <select name="audityear" class="form-control input-lg form-select" id="audityear" onchange="fetchcreatedauditall(this.value,<?php echo $empno;?>);">
                    <option value="">Select Year</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024" selected>2024</option>
                    </select>
                </div>
                <div class="row mb-2">
                <div class="col-6 col-sm-3 text-primary text-left mt-3">
                <label for="myInput " class="fw-bold" >Seach for Audit ID</label>
                <input class="form-control input-lg" type="text" id="myInput1" onkeyup="myFunction1()" placeholder="Search for ID.." title="Type in a ID">
                </div>
                <div class="col-6 col-sm-3 text-primary text-left mt-3">
                <label for="myInput2 " class="fw-bold" >Seach for Entity name</label> 
                <input class="form-control input-lg" type="text" id="myInput2" onkeyup="myFunction2()" placeholder="Search for name.." title="Type in a name">
                </div>
                <div class="col-6 col-sm-3 text-primary text-left mt-3">
                <label for="myInput3 " class="fw-bold" >Seach for Audit Start Date</label> 
                <input class="form-control input-lg" type="text" id="myInput3" onkeyup="myFunction3()" placeholder="Search for Date.." title="Type in a Date">
                </div>
                </div>
            </div>
            <table class="table" id="myTable">
            <thead>
                <tr>
                <th scope="col">Audit Id</th>
                <th scope="col">Entity Name</th>
                <th scope="col">Examin start date</th>
                <th scope="col">Examin end date</th>
                <th scope="col">Covered period start date</th>
                <th scope="col">Covered period end date</th>
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
    fetchcreatedauditall(currentyear,<?php echo $empno;?>);;
     }

    function fetchcreatedauditall(audityear,empno){
        $.ajax({
            url:"<?php echo base_url('Development/development/public/ongoingAssignedReviewAllAudit')?>",
            method:"POST",
            data:{
                cId:audityear,
                empno:empno
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

</script>