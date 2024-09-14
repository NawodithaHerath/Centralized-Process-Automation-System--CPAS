
<div class="container">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 mt-5 pt-3 pb-3 bg-white from-wrapper " >
        <div class="countainer" style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">
            <h3 style=" border: 1px solid; padding: 10px; box-shadow: 5px 10px 8px 10px #888888;">Audit Team Assigning </h3>
            <hr style="height:2px;border-width:0;color:gray;background-color:gray">
            <?php if(isset($validation)):?>
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                        <?php endif;?>
            <form action="/Development/development/public/auditteamcreation/<?php echo $auditid?>" method="post">
            <div class="card shadow-out mb-5">
                        <div class="card-body ps-5">
                            <div class="form-group">
                                <label for="auditid" class="fw-bold" >Audit ID:</label>
                                <input type="text" class="form-control input-lg" readonly="text" value="<?= $auditid ?>" name="auditid" id="auditid">
                            </div>
                            <div class="form-group mt-3">
                                <label for="empno" class="fw-bold required" >Team Member</label>
                                <select name="empno" class="form-control input-lg form-select" id="empno" >
                                    <option value="">Select Team Member</option>
                                    <?php if($iaduser):?>
                                    <?php foreach($iaduser as $row): ?>
                                        <option value="<?php echo $row['EmpNo'];?>"><?php echo $row['EmpNo'];?> - <?php echo $row['FirstName'];?>  <?php echo $row['LastName'];?></option>
                                        <?php endforeach; ?>    
                                    <?php endif; ?>
                                </select>
                            </div>

                <div class="row mt-5">
                    <div class="col-12 col-sm-4 text-center">
                        <button type="submit" class="btn btn-primary col-9"> Add </button>
                    </div>
                    
                    <div class="col-12 col-sm-4 text-center">
                        <a class="btn btn-primary col-9" href="http://localhost/Development/development/public/createdauditsummaryyearly"> Back </a>
                    </div>
                    <div class="col-12 col-sm-4 text-center">
                        <a class="btn btn-secondary col-9" href="/Development/development/public/dashboard">Home</a>
                    </div>
                </div> 

                <?php if(session()->get('success')):?>
                <div class="alert alert-success mt-2" role="alert">
                    <?= session()->get('success') ?>
                </div>
                <?php endif; ?>
                <?php if(session()->get('Unable')):?>
                <div class="alert alert-danger mt-2" role="alert">
                    <?= session()->get('Unable') ?>
                </div>
                <?php endif; ?>
                </div>     
            </div>
            </form>
           
        </div>
        </div>
    </div>
</div>

<script>

    // window.onload = fetchCountryData;

function fetchChecklistId(audit_category){
        $.ajax({
            url:"<?php echo base_url('Development/development/public/checklist_id')?>",
            method:"POST",
            data:{
                cId:audit_category
            },
            success:function(result){
                let data = JSON.parse(result);
                document.querySelector("#checklist_id").innerHTML = data;
                console.log(result);
            }
        });
    }
    function fetchEntity(entityid){
        $.ajax({
            url:"<?php echo base_url('Development/development/public/entityid')?>",
            method:"POST",
            data:{
                eId:entityid
            },
            success:function(result){
                let data = JSON.parse(result);
                document.querySelector("#entityid").innerHTML = data;
                console.log(result);
            }
        });
    }

</script>