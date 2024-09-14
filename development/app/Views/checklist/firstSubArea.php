
<div class="container">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 mt-5 pt-3 pb-3 bg-white from-wrapper " >
        <div class="countainer" style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">
            <h3 style=" border: 1px solid; padding: 10px; box-shadow: 5px 10px 8px 10px #888888;">First Sub Area Adding</h3>
            <hr style="height:2px;border-width:0;color:gray;background-color:gray">
            <?php if(isset($validation)):?>
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                        <?php endif;?>
            <form action="" action="<?= base_url('/Development/development/public/mainChecklistEdit/'.$mainarea_id); ?>" method="post">
            <div class="card shadow-out mb-5">
                        <div class="card-body ps-5">
                            <div class="form-group">
                                <label for="mainarea_id" >Main Area ID</label>
                                <input id="mainarea_id" class="form-control" value="<?=  $mainarea_id; ?>"  readonly="text" name="mainarea_id">
                                <div class="text-primary"> <?=  $maincheckarea['mainarea_description']; ?></div>
                            </div>
                            <div class="form-group">
                                <label class="required" for="firstsubarea_id" >First Check Area ID</label>
                                <input id="firstsubarea_id" class="form-control" placeholder="Ex-: <?= $mainarea_id.'_F01' ?>"  name="firstsubarea_id">
                            </div>
                            <div class="form-group">
                                <label class="required" for="firstsubarea_description" >First Sub Check Area Description</label>
                                <input id="firstsubarea_description" class="form-control"  name="firstsubarea_description">
                            </div>
                     
                <div class="row">
                    <div class="col-12 col-sm-4 text-center">
                        <button type="submit" class="btn btn-primary col-9"> Create </button>
                    </div>
                    
                    <!-- <div class="col-12 col-sm-4 text-center">
                        <a class="btn btn-primary" href="<?= base_url('/Development/development/public/mainChecklistEdit/'.$mainarea_id); ?>"> New </a>
                    </div> -->
                    <div class="col-12 col-sm-4 text-center">
                        <a class="btn btn-secondary col-9" href="<?= base_url('/Development/development/public/mainAraAdding/'.$maincheckarea['checklist_id']); ?>">Back</a>
                    </div>
                </div> 
                </form>
                <hr>
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