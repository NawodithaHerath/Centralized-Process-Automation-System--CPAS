
<div class="container">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 mt-5 pt-3 pb-3 bg-white from-wrapper " >
        <div class="countainer" style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">
            <h3 style=" border: 1px solid; padding: 10px; box-shadow: 5px 10px 8px 10px #888888;">Checking Item Adding</h3>
            <hr style="height:2px;border-width:0;color:gray;background-color:gray">
            <?php if(isset($validation)):?>
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                        <?php endif;?>
            <form action="" action="<?= base_url('/Development/development/public/checkingItem/'.$firstsubarea_id); ?>" method="post">
            <div class="card shadow-out mb-5">
                        <div class="card-body ps-5">
                            <div class="form-group">
                                <label for="firstsubarea_id" >First Sub Area ID</label>
                                <input id="firstsubarea_id" class="form-control" value="<?= $firstsubarea_id?>"  readonly="text" name="firstsubarea_id">
                                <div class="text-primary"><?=  $firstsubarea['firstsubarea_description']; ?></div>
                            </div>
                            <div class="form-group">
                                <label class="required" for="checkingitem_id" >Checking Item ID</label>
                                <input id="checkingitem_id" class="form-control" placeholder="Ex-: <?= $firstsubarea_id?>_C01"  name="checkingitem_id">
                            </div>
                            <div class="form-group">
                                <label class="required" for="checkingitem_description" >Check Item Description</label>
                                <input id="checkingitem_description" class="form-control"  name="checkingitem_description">
                            </div>
                     
                <div class="row">
                    <div class="col-12 col-sm-4 text-center">
                        <button type="submit" class="btn btn-primary col-9"> Create </button>
                    </div>
                    <div class="col-12 col-sm-4 text-center">
                        <a class="btn btn-secondary col-9" href="<?= base_url('/Development/development/public/firstSubArea/'.$firstsubarea['mainarea_id']); ?>">Back</a>
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