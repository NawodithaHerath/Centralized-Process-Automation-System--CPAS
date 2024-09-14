
<div class="container">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 mt-5 pt-3 pb-3 bg-white from-wrapper " >
        <div class="countainer" style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">
            <h3 style=" border: 1px solid; padding: 10px; box-shadow: 5px 10px 8px 10px #888888;">Check List Editing</h3>
            <hr style="height:2px;border-width:0;color:gray;background-color:gray">
            <?php if(isset($validation)):?>
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                        <?php endif;?>
            <form action="" action="/Development/development/public/mainChecklist" method="post">
            <div class="card shadow-out mb-5">
                        <div class="card-body ps-5">
                        <div class="form-group">
                                <label for="checklist_id" >Checklist ID</label>
                                <input id="checklist_id" readonly value="<?=  $mainChecklistEdit['checklist_id']; ?> " class="form-control" type="text" name="checklist_id">
                            </div>
                            <div class="form-group">
                                <label class="required" for="audit_category" >Audit Category</label>
                                <div class="text-primary"> Selected : <?=  $mainChecklistEdit['audit_category']; ?></div>
                                <select name="audit_category" class="form-control input-lg form-select" id="audit_category">
                                    <option value="<?=  $mainChecklistEdit['audit_category']; ?>"><?=  $mainChecklistEdit['audit_category']; ?></option>
                                    <option value="branch">Branch</option>
                                    <option value="department">Department</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="required" for="audit_type" >Audit type</label>
                                <div class="text-primary"> Selected : <?=  $mainChecklistEdit['audit_type']; ?></div>
                                <select name="audit_type" class="form-control input-lg form-select" id="audit_type">
                                    <option value="<?=  $mainChecklistEdit['audit_type']; ?>"><?=  $mainChecklistEdit['audit_type']; ?></option>
                                    <option value="onsite">On site</option>
                                    <option value="offsite">Off site</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="required" for="audit_area" >Audit Area</label>
                                <div class="text-primary"> Selected : <?=  $mainChecklistEdit['audit_area']; ?></div>
                                <select name="audit_area" class="form-control input-lg form-select" id="audit_area">
                                    <option value="<?=  $mainChecklistEdit['audit_area']; ?>"><?=  $mainChecklistEdit['audit_area']; ?></option>
                                    <option value="operation">Operation</option>
                                    <option value="credit">Credit</option>
                                    <option value="general">General</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="required" for="checklist_description" >Checklist Description</label>
                                <input value="<?=  $mainChecklistEdit['checklist_description']; ?>" id="checklist_description" class="form-control"  name="checklist_description">
                            </div>
                     
                <div class="row">
                    <div class="col-12 col-sm-4 text-center">
                        <button type="submit" class="btn btn-primary col-9"> Update </button>
                    </div>
                    </form>
                    <div class="col-12 col-sm-4 text-center">
                        <a class="btn btn-primary col-9" href="/Development/development/public/mainChecklist"> New </a>
                    </div>
                    <div class="col-12 col-sm-4 text-center">
                        <a class="btn btn-secondary col-9" href="/Development/development/public/mainChecklist">Back</a>
                    </div>
                </div> 
                <hr>
                    <div class="col-12 col-sm-4 text-center">
                    <a class="btn btn-secondary" href="<?= base_url('/Development/development/public/mainAraAdding/'.$mainChecklistEdit['checklist_id']); ?>">Adding Main Areas</a>
                    </div>
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
