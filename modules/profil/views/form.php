<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;Profil
                </h3>
            </div>
            <div class="box-body">
                <form class="form-horizontal" method="POST" action="<?=site_url('profil/submit/')?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Nama</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="nama" value="<?=@$data->nama?>">
                        </div>
                    </div>
                    <?php if(!empty($data->username)){ ?>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Username</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="username" value="<?=@$data->username?>" readonly>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(!empty($data->email)){ ?>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Email</label>
                            <div class="col-md-8">
                                <input type="email" class="form-control" name="email" value="<?=@$data->email?>" readonly>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Password</label>
                        <div class="col-md-8">
                            <input type="password" class="form-control" name="password" value="">
                            <span class="help-block">Kosongkan jika password tidak ingin diganti.</span>
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary btn-flat">
                                <i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
                            </button>
                            <a href="<?=site_url('profil')?>" class="btn btn-default btn-flat">
                                <i class="fa fa-times"></i>&nbsp;&nbsp;Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
