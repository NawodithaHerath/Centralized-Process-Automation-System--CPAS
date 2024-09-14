<div class="container-fluid ">
    <div class="col-12 col-sm-12 offset-sm-12 col-md-12 mt-1 pt-1 pb-1 bg-white from-wrapper " >
        <div class="countainer" style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">
        <h5 style=" border: 1px solid; padding: 10px; background-color:gray; color:white;">All Comments Recivied for replying </h5>
            <hr style="height:2px;border-width:0;color:gray;background-color:gray">

            <Div><h5> All comments available for assignning to reply and their details</h5></Div> 
            <a href="<?= base_url('/Development/development/public/allOngoingAuditReplyingAssign/'.session()->get('EmpNo')); ?>" class="btn btn-secondary float-right">Back</a>
           

            <div class="row">
                <div class="col-6 col-sm-3 text-left mt-3 ">
                <label for="myInput " class="fw-bold text-primary" >Search from ID</label>
                <input class="form-control input-lg border-primary" type="text" id="myInput1" onkeyup="myFunction1()" placeholder="Search for ID.." title="Type in a ID">
                </div>
                <div class="col-6 col-sm-3 text-left mt-3 ">
                <label for="myInput2 " class="fw-bold text-primary" >Seach from Overall Risk</label> 
                <input class="form-control input-lg border-primary" type="text" id="myInput2" onkeyup="myFunction2()" placeholder="Search for Checklsit Id.." title="Type in a Checklist">
                </div>
                <div class="col-6 col-sm-3 text-left mt-3 ">
                <label for="myInput3 " class="fw-bold text-primary" >Seach from Comment Heading</label> 
                <input class="form-control input-lg border-primary" type="text" id="myInput3" onkeyup="myFunction3()" placeholder="Search for Heading .." title="Type in a heading">
                </div>
                <div class="col-6 col-sm-3 text-left mt-3 ">
                <label for="myInput4 " class="fw-bold text-primary" >Seach from Team Member</label> 
                <input class="form-control input-lg border-primary" type="text" id="myInput4" onkeyup="myFunction4()" placeholder="Search for Name .." title="Type in a name">
                </div>
            </div>

            <table class="table" id=myTable >
            <thead>
                <tr>
                <th scope="col">Comments ID</th>
                <th scope="col">Overall Riks</th>
                <th scope="col">Comment Heading</th>
                <th scope="col">Target Date</th>
                <!-- <th scope="col">Reply Status</th>
                <th scope="col">Assigned Officer</th> -->
                </tr>
            </thead>
            <tbody>
                <?php if($commentsdetails):?>
                    <?php foreach($commentsdetails as $row): ?> 
                <tr>
                <td scope="row" ><?php echo $row['comment_id'];?> </td>
                <td><?php echo $row['overallrisk'];?> </td>
                <td><?php echo $row['commentheading'];?> </td>
                <td><?php echo $row['targetdate'];?> </td>
                <td><?php //if(!isset($row['replyStatus'])){echo "Not Assinged";} else{echo $row['replyStatus'];}?> </td>
                <td><?php //if(!isset($row['replyStatus'])){echo "Not Assinged";} else{echo $row['FirstName'].' '.$row['LastName'];}?> </td>
                <td><a href="<?= base_url('/Development/development/public/CommentReplyingAssigned/'.$row['comment_id']); ?>" class="btn btn-secondary">View</a></td>
                </tr>
                <?php endforeach; ?>    
                <?php endif; ?> 
            </tbody>
            </table>

        </div>
    </div>    
</div>

<script>
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
            td = tr[i].getElementsByTagName("td")[5];
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