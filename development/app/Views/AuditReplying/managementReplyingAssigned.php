
<div class="container">
    <div class="col-12 col-sm-12 offset-sm-12 col-md-12 mt-1 pt-1 pb-1 bg-white from-wrapper " >
        <div class="countainer" style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">
        <h4 style=" border: 1px solid; padding: 10px; background-color:gray; color:white;">Recieved Comment For Replying</h4>
            <hr style="height:2px;border-width:0;color:gray;background-color:gray">
            <div class="countainer" style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">
            <h5> Assigned audit comment for replying</h5>
            <div class="card-body ps-5">

            <?php if(isset($validation)):?>
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                        <?php endif;?>
            <?php if(session()->get('success')):?>
                <div class="alert alert-success mt-2" role="alert">
                    <?= session()->get('success') ?>
                </div>
                <?php endif; ?>

                <?php if(session()->get('alert')):?>
                <div class="alert alert-warning mt-2" role="alert">
                    <?= session()->get('alert') ?>
                </div>
                <?php endif; ?>    

            <form name="commentassignform" id="commentassignform" action="/Development/development/public/CommentReplyingAssigned/<?php echo $commentdetails['comment_id'];?>" method="post">
            <div class="container border">
                <div class="form-group mt-1">
                        <label for="responseofficer " class="fw-bold" >Respone Officer</label>
                            <select name="responseofficer" class="form-control input-lg form-select" id="responseofficer" >
                            <option value="<?php if(isset($assignedpersondetails)){echo $assignedpersondetails['EmpNo'];}else{echo "";} ?>"> <?php if(isset($assignedpersondetails)){echo $assignedpersondetails['EmpNo'].'-'.$assignedpersondetails['FirstName']." ".$assignedpersondetails['LastName'];}else{echo "Select responsible officer";}?></option>
                            <?php if($entityusers):?>
                                    <?php foreach($entityusers as $row): ?>
                                <option value="<?php echo $row['EmpNo'];?>"><?php echo $row['EmpNo'];?> - <?php echo $row['FirstName'].' '.$row['LastName'];?> </option>
                                <?php endforeach; ?>    
                                <?php endif; ?>
                            </select>
                        </div>

                </div>
                <div class="container border mt-1">         
                        <div class="row m-1">
                            <div class="col-12 col-sm-3 text-center">
                                <button type="submit" class="btn btn-primary col-9"> Assign </button>
                            </div>

                            <div class="col-12 col-sm-3 text-center">
                                <a class="btn btn-secondary col-9" href="/Development/development/public/allCommentReplyingAssign/<?= $commentdetails['auditid'] ?>">Back</a>
                            </div>
                        </div> 
                </div> 
                </form>
            </div>
        </div>
        </div>
    </div>
</div>    


