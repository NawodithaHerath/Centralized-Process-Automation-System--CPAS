<div class="container">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 mt-5 pt-3 pb-3 bg-white from-wrapper " >
        <div class="countainer">
            <h3><?= $user['FirstName'] . " ". $user['LastName']  ?></h3>
            <hr>
            <?php if(session()->get('success')):?>
                <div class="alert alert-success" role="alert">
                    <?= session()->get('success') ?>
                </div>
            <?php endif; ?>
            <form action="" action="/Development/development/public/profile" method="post">
                <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="FirstName">First Name</label>
                                <input type="text" class="form-control" name="FirstName" id="FirstName" value="<?= set_value('FirstName',$user['FirstName'])?>">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="LastName">Last Name</label>
                                <input type="text" class="form-control" name="LastName" id="LastName" value="<?= set_value('LastName',$user['LastName'])?>">
                            </div>
                        </div>
                        <div class="container">
                        <div class="form-group">
                            <label for="Email">Email Address</label>
                            <input type="text" class="form-control" name="Email"  readonly ="Email" value="<?= $user['Email']?>">
                        </div>
                        <div class="row">   
                            <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="Password">Password</label>
                                <input type="Password" class="form-control" name="Password" id="Password" value="">
                            </div>
                            </div>
                            <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="Password_Confirm">Confirm Password</label>
                                <input type="password" class="form-control" name="Password_Confirm" id="Password_Confirm" value="">
                            </div>
                            </div>
                        </div>
                        </div>
                    <?php if(isset($validation)):?>
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                        <?php endif;?>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-4">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>