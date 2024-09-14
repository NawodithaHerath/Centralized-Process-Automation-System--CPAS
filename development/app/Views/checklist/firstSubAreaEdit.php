
<div class="container">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 mt-5 pt-3 pb-3 bg-white from-wrapper " >
        <div class="countainer" style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">
            <h3 style=" border: 1px solid; padding: 10px; box-shadow: 5px 10px 8px 10px #888888;">First Sub Area Editing</h3>
            <hr style="height:2px;border-width:0;color:gray;background-color:gray">
            <?php if(isset($validation)):?>
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                        <?php endif;?>
            <form action="" action="<?= base_url('/Development/development/public/firstSubAreaEdit/'.$firstsubarea_id); ?>" method="post">
            <div class="card shadow-out mb-5">
                        <div class="card-body ps-5">
                            <div class="form-group">
                                <label for="mainarea_id" >Main Area ID</label>
                                <input id="mainarea_id" class="form-control" value="<?=  $firstsubcheckarea['mainarea_id']; ?>"  readonly="text" name="mainarea_id">
                                <div class="text-primary"> <?=  $maincheckarea['mainarea_description']; ?></div>
                            </div>
                            <div class="form-group">
                                <label class="required" for="firstsubarea_id" >First  Sub Area ID</label>
                                <div class="text-primary"> Current ID -: <?= $firstsubcheckarea['firstsubarea_id']; ?> </div>
                                <input id="firstsubarea_id" class="form-control" value="<?= $firstsubcheckarea['firstsubarea_id']; ?>"  name="firstsubarea_id">
                            </div>
                            <div class="form-group">
                                <label class="required" for="firstsubarea_description" >First Sub Check Area Description</label>
                                <div class="text-primary"> Current Description -: <?= $firstsubcheckarea['firstsubarea_description']; ?> </div>
                                <input id="firstsubarea_description" class="form-control"  value="<?= $firstsubcheckarea['firstsubarea_description']; ?>" name="firstsubarea_description">
                            </div>
                     
                <div class="row">
                    <div class="col-12 col-sm-4 text-center">
                        <button type="submit" class="btn btn-primary col-9"> Update </button>
                    </div>
                    
                    <div class="col-12 col-sm-4 text-center">
                        <a class="btn btn-secondary col-9" href="<?= base_url('/Development/development/public/firstSubArea/'.$firstsubcheckarea['mainarea_id']); ?>">Back</a>
                    </div>
                </div> 
                <hr>
                <div class="col-12 col-sm-5 text-center">
                        <a class="btn btn-secondary col-12" href="<?= base_url('/Development/development/public/checkingItem/'.$firstsubcheckarea['firstsubarea_id']); ?>">Add Checking Item</a>
                </div>
                </form>
                <hr>
                <?php if(session()->get('success')):?>
                <div class="alert alert-success" role="alert">
                    <?= session()->get('success') ?>
                </div>
            <?php endif; ?>
                </div>
            
            </div>
        </div>
        </div>
    </div>
</div>