<div class="container">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 mt-5 pt-3 pb-3 bg-white from-wrapper " >
        <div class="countainer" style="  border: 1px solid; padding: 10px; box-shadow: 5px 10px 18px #888888;">
        <h4 style=" border: 1px solid; padding: 10px; background-color:blue; color:white;">Centerl Proces Automation System (CPAS) </h4>
            <h3>Login</h3>
            <hr>
            <?php if(session()->get('success')):?>
                <div class="alert alert-success" role="alert">
                    <?= session()->get('success') ?>
                </div>
            <?php endif; ?>
            <form action="" action="/Development/development/public/users" method="post">
                <div class="form-group">
                    <label for="Email">Email Address</label>
                        <div class="input-group">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="text" class="form-control" name="Email" id="Email" value="<?= set_value('Email')?>" >
                    </div>
                    </div>
                <div class="form-group">
                    <label for="Password">Password</label>
                    <input type="Password" class="form-control" name="Password" id="Password" value="">
                </div>
                <?php if(isset($validation)):?>
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                        <?php endif;?>
                <div class="row">
                    <div class="col-12 col-sm-4">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                    <div class="col-12 col-sm-8 text-right">
                        <a href="/Development/development/public/register">Don't have an acccount?</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>