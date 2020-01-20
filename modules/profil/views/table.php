<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;Profil
                </h3>
            </div>
            <div class="box-body">
                <div class="row">
            		<div class="col-md-4">
                        <center>
                            <img src="<?=default_foto_user($data->foto)?>" width="75%" class="img-circle">
                        </center>
                        <hr/>
            			<?=!empty($data->tentang) ? '<p>' . $data->tentang . '</p><hr/>' : ''?>
                        <a href="<?=site_url('profil/upload_foto')?>" class="btn btn-default btn-flat btn-block">
                            <i class="fa fa-upload"></i>&nbsp;&nbsp;&nbsp;Upload Foto
                        </a>
                        <a href="<?=site_url('profil/form')?>" class="btn btn-default btn-flat btn-block">
                            <i class="fa fa-edit"></i>&nbsp;&nbsp;&nbsp;Perbaharui Profil
                        </a>
                        <hr/>
            		</div>
            		<div class="col-md-8">
                        <h3><strong><?=$data->nama?></strong></h3>
                        <hr/>
            			<form class="form-horizontal">
                            <?php if(!empty($data->usermame)){ ?>
                                <div class="form-group form-md-line-input" style="margin: 0 -15px 0px">
                                    <label class="col-md-4 control-label" style="text-align: left;">Username</label>
                                    <div class="col-md-8 text-right" style="font-weight: bold; font-size: 14px;">
                                        <div class="form-control form-control-static" style="border: none;"><?=$data->username?></div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if(!empty($data->email)){ ?>
                                <div class="form-group form-md-line-input" style="margin: 0 -15px 0px">
                                    <label class="col-md-4 control-label" style="text-align: left;">Email</label>
                                    <div class="col-md-8 text-right" style="font-weight: bold; font-size: 14px;">
                                        <div class="form-control form-control-static" style="border: none;"><?=$data->email?></div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="form-group form-md-line-input" style="margin: 0 -15px 0px">
                                <label class="col-md-4 control-label" style="text-align: left;">Terakhir Login</label>
                                <div class="col-md-8 text-right" style="font-weight: bold; font-size: 14px;">
                                    <div class="form-control form-control-static" style="border: none;"><?=format_tanggal($data->terakhir_login, true)?></div>
                                </div>
                            </div>
            			</form>
            		</div>
            	</div>
            </div>
        </div>
    </div>
</div>
