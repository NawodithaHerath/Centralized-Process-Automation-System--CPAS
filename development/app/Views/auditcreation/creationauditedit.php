
<div class="container">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 mt-5 pt-3 pb-3 bg-white from-wrapper " >
        <div class="countainer" style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">
            <h3 style=" border: 1px solid; padding: 10px; box-shadow: 5px 10px 8px 10px #888888;">Editing Created Audit </h3>
            <hr style="height:2px;border-width:0;color:gray;background-color:gray">
            <?php if(isset($validation)):?>
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                <?php endif;?>
            <form action="" action="/Development/development/public/auditcreationEdit" method="post">
            <div class="card shadow-out mb-5">
                        <div class="card-body ps-5">
                                <div class="form-group">
                                <label for="auditidold" class="fw-bold" >Current: Audit ID</label>
                                <input id="auditidold" class="form-control" value="<?= $auditdetails['auditid']?>"  readonly="text" name="auditidold">

                            </div>
                            <div class="form-group">
                                <label for="audityear" class="fw-bold required" >Audit Year:</label>
                                <div class="text-primary"> Current Audit Year -: <?= $auditdetails['audityear']?> </div>
                                <input type="number" class="form-control input-lg" value="<?= $auditdetails['audityear']?>" placeholder="YYYY" min="2023" max="2025" name="audityear" id="audityear">
                            </div>
                            <div class="row">
                            <label for="examinperiod"class="fw-bold " >Examine Period:</label>
                                    <div class="col-12 col-sm-5 text-center text-right">
                                    <label for="examinstartdate" class="required" >Examine Start Date</label>
                                    <div class="text-primary"> Current: Start Date -: <?= $auditdetails['examinstartdate']?> </div>
                                    <input type="date" class="form-control input-lg" value="<?= $auditdetails['examinstartdate']?>"  name="examinstartdate" id="examinstartdate">
                                     
                                    </div>
                                    <div class="col-12 col-sm-5 text-center">
                                    <label for="examinendtdate" class="required" >Examine End Date</label> 
                                    <div class="text-primary"> Current: End Date -: <?= $auditdetails['examinendtdate']?> </div>
                                        <input type="date" class="form-control input-lg" value="<?= $auditdetails['examinendtdate']?>" name="examinendtdate" id="examinendtdate">
                                    </div>   
                            </div> 
                            <div class="row mt-3" >
                            <label for="audityear" class="fw-bold">Audit Covered Period:</label>
                                    <div class="col-12 col-sm-5 text-center text-right">
                                    <label for="coveredstartdate" class="required" >From </label>
                                    <div class="text-primary"> Current: Start Date -: <?= $auditdetails['coveredstartdate']?> </div>
                                    <input type="date" class="form-control input-lg" value="<?= $auditdetails['coveredstartdate']?>" name="coveredstartdate" id="coveredstartdate">
                                     
                                    </div>
                                    <div class="col-12 col-sm-5 text-center">
                                    <label for="coveredenddate" class="required" >To</label> 
                                    <div class="text-primary"> Current: End Date -: <?= $auditdetails['coveredenddate']?> </div>
                                        <input type="date" class="form-control input-lg" value="<?= $auditdetails['coveredenddate']?>" name="coveredenddate" id="coveredenddate">
                                    </div>   
                            </div> 
                            <div class="form-group mt-3">
                                <label for="audit_category " class="fw-bold required" >Audit Category</label>
                                <div class="text-primary"> Current: Audit Category -: <?= $allauditdetails[0]['audit_category']?> </div>
                                <select name="audit_category" class="form-control input-lg form-select" id="audit_category" onchange="fetchChecklistId(this.value); fetchEntity(this.value);">
                                    <option value="<?= $allauditdetails[0]['audit_category']?>"><?= $allauditdetails[0]['audit_category']?></option>
                                    <option value="branch">Branch</option>
                                    <option value="department">Department</option>
                                </select>
                            </div>

                            <div class="form-group mt-3">
                                <label for="checklist_id"class="fw-bold required" >Checklist ID</label>
                                <div class="text-primary"> Current: Checklist ID -: <?= $auditdetails['checklist_id']?> </div>
                                <select name="checklist_id" class="form-control input-lg form-select" id="checklist_id" >
                                    <option value="<?= $auditdetails['checklist_id']?>"><?= $auditdetails['checklist_id']?></option>
                                </select>
                            </div>
                            <div class="form-group mt-3">
                                <label for="entityid" class="fw-bold required" >Audit Entity</label>
                                <div class="text-primary"> Current: Audit Entity -: <?= $auditdetails['entityid']."-".$allauditdetails[0]['entityname']?> </div>
                                <select name="entityid" class="form-control input-lg form-select" id="entityid">
                                    <option value="<?= $auditdetails['entityid']?>"><?= $auditdetails['entityid']."-".$allauditdetails[0]['entityname']?></option>
                                </select>
                            </div>
                            <div class="form-group mt-3">
                                <label for="reviewer" class="fw-bold required" >Reviewer</label>
                                <div class="text-primary"> Current: Reviewer -: <?= $auditdetails['reviewer'];?> - <?= $allauditdetails[0]['FirstName'];?> <?= $allauditdetails[0]['LastName'];?> </div>
                                <select name="reviewer" class="form-control input-lg form-select" id="reviewer" >
                                    <option value="<?= $auditdetails['reviewer'];?>"><?= $auditdetails['reviewer'];?> - <?= $allauditdetails[0]['FirstName'];?></option>
                                    <?php if($iaduser):?>
                                    <?php foreach($iaduser as $row): ?>
                                        <option value="<?php echo $row['EmpNo'];?>"><?php echo $row['EmpNo'];?> - <?php echo $row['FirstName'];?>  <?php echo $row['LastName'];?></option>
                                        <?php endforeach; ?>    
                                    <?php endif; ?>
                                </select>
                            </div>

                <div class="row mt-5">
                    <div class="col-12 col-sm-4 text-center">
                        <button type="submit" class="btn btn-primary col-9"> Upadate </button>
                    </div>
                    </form>
                    <div class="col-12 col-sm-4 text-center">
                        <a class="btn btn-primary col-9" href="/Development/development/public/AuditCreation"> New </a>
                    </div>
                    <div class="col-12 col-sm-4 text-center">
                        <a class="btn btn-secondary col-9" href="/Development/development/public/AuditCreation">Back</a>
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