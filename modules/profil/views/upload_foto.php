<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-upload"></i>&nbsp;&nbsp;&nbsp;Upload Foto
                </h3>
            </div>
            <div class="box-body">
                <form class="form-horizontal" method="POST" action="<?=site_url('profil/do_upload/')?>" enctype="multipart/form-data">
                    <div class="form-group form-md-line-input form-md-floating-label" style="margin: 0px 0px 15px;">
                        <center>
                            <img src="<?=default_foto_user($data->foto)?>" class="img-circle" width="50%">
                        </center>
                        <hr/>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-4 control-label">Upload Foto Baru</label>
                        <div class="col-md-8">
                            <input type="file" class="form-control" name="userfiles">
                            <span>File harus berjenis JPG / PNG, Maksimal Ukuran : 2 MB</span>
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group form-md-line-input">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary btn-flat">
                                <i class="fa fa-check"></i>&nbsp;&nbsp;Mulai Upload
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
