
<div class="container">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 mt-5 pt-3 pb-3 bg-white from-wrapper " >
        <div class="countainer" style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">
            <h3 style=" border: 1px solid; padding: 10px; box-shadow: 5px 10px 8px 10px #888888;">Editing Entity </h3>
            <hr style="height:2px;border-width:0;color:gray;background-color:gray">
            <?php if(isset($validation)):?>
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                        <?php endif;?>
            <form action="" action="/Development/development/public/entityEditing" method="post">
            <div class="card shadow-out mb-5">
                        <div class="card-body ps-5">
                            <div class="form-group">
                                <label for="entityid" class="fw-bold required" >Entity ID</label>
                                <div class="text-primary"> Current: Entity ID-: <?= $entitydetails['entityid']?> </div>
                                <input type="text" id="entityid" class="form-control" value="<?= $entitydetails['entityid']?>" name="entityid">
                            </div>
                            <div class="form-group mt-3">
                                <label for="entitytype " class="fw-bold required" > Entity Type</label>
                                <div class="text-primary"> Current: Entity Type-: <?= $entitydetails['entitytype']?> </div>
                                <select name="entitytype" class="form-control input-lg form-select" id="entitytype">
                                    <option value="<?= $entitydetails['entitytype']?>"> <?= $entitydetails['entitytype']?></option>
                                    <option value="branch">Branch</option>
                                    <option value="department">Department</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="entityname" class="fw-bold required" >Entity Name</label>
                                <div class="text-primary"> Current: Entity Name-: <?= $entitydetails['entityname']?> </div>
                                <input type="text" id="entityname" class="form-control" value="<?= $entitydetails['entityname']?>" name="entityname">
                            </div>
                            <div class="form-group">
                                <label for="manager" class="fw-bold required" >Manager ID</label>
                                <div class="text-primary"> Current: Manager ID-: <?= $entitydetails['manager']?> </div>
                                <input type="text" id="manager" class="form-control" value="<?= $entitydetails['manager']?>"  name="manager">
                            </div>
                            <div class="form-group">
                                <label for="assistantmanager" class="fw-bold required" >Assistant Manager ID</label>
                                <div class="text-primary"> Current: Assistant Manager ID-: <?= $entitydetails['assistantmanager']?> </div>
                                <input type="text" id="assistantmanager" class="form-control"value="<?= $entitydetails['assistantmanager']?>" name="assistantmanager">
                            </div>
                        </div>
                <div class="row mb-2 justify-content-md-center" >
                    <div class="col-12 col-sm-4 ">
                        <button type="submit" class="btn btn-primary col-9"> Update </button>
                    </div>
                    <div class="col-12 col-sm-4 ">
                        <a class="btn btn-secondary col-9" href="/Development/development/public/entityadding">Home</a>
                    </div>
                </div> 
             </form>
                <?php if(session()->get('success')):?>
                <div class="alert alert-success" role="alert">
                    <?= session()->get('success') ?>
                </div>
                <?php endif; ?>
                <?php if(session()->get('Unable')):?>
                <div class="alert alert-danger" role="alert">
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

    window.onload = fetchCountryData;

function fetchCountryData(){
    $.ajax({
        url:"<?php echo base_url('Development/development/public/checklist_id')?>",
        method:"POST",
        success:function(result){
            let data = JSON.parse(result);
            document.querySelector("#checklist_id").innerHTML = data;
            console.log(result);
        }
    });
}

</script>