
<div class="container">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 mt-5 pt-3 pb-3 bg-white from-wrapper " >
        <div class="countainer" style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">
            <h3 style=" border: 1px solid; padding: 10px; box-shadow: 5px 10px 8px 10px #888888;">Check List Creation</h3>
            <hr style="height:2px;border-width:0;color:gray;background-color:gray">
            <?php if(isset($validation)):?>
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                        <?php endif;?>
            <form  action="/Development/development/public/mainChecklist" method="post">
            <div class="card shadow-out mb-5">
                        <div class="card-body ps-5">
                            <div class="form-group">
                                <label class="required" for="checklist_id" >Checklist ID</label>
                                <input id="checklist_id" class="form-control" type="text" placeholder="Ex-: BRNOFFOP001" name="checklist_id">
                            </div>
                            <div class="form-group">
                                <label class="required" for="audit_category" >Audit Category</label>
                                <select name="audit_category" class="form-control input-lg form-select" id="audit_category">
                                    <option value="">Select Audit Category</option>
                                    <option value="branch">Branch</option>
                                    <option value="department">Department</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label  class="required" for="audit_type" >Audit type</label>
                                <select name="audit_type" class="form-control input-lg form-select" id="audit_type">
                                    <option value="">Select Audit Type</option>
                                    <option value="onsite">On site</option>
                                    <option value="offsite">Off site</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="required"  for="audit_area" >Audit Area</label>
                                <select name="audit_area" class="form-control input-lg form-select" id="audit_area">
                                    <option value="">Select Audit Type</option>
                                    <option value="operation">Operation</option>
                                    <option value="credit">Credit</option>
                                    <option value="general">General</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="required" for="checklist_description" >Checklist Description</label>
                                <input id="checklist_description" class="form-control"  name="checklist_description">
                            </div>
                     
                <div class="row">
                    <div class="col-12 col-sm-4 text-center">
                        <button type="submit" class="btn btn-primary col-9"> Create </button>
                    </div>
                    </form>
                    <div class="col-12 col-sm-4 text-center">
                        <a class="btn btn-primary col-9" href="/Development/development/public/mainChecklist"> New </a>
                    </div>
                    <div class="col-12 col-sm-4 text-center">
                        <a class="btn btn-secondary col-9" href="/Development/development/public/dashboard">Home</a>
                    </div>
                </div> 
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