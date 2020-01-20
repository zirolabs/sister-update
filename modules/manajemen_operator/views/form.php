<div class="row" style="margin-bottom: 10px;">
	<div class="col-md-8 col-md-offset-2">
		<a href="<?=site_url('manajemen_operator')?>" class="btn btn-default">
			<i class="fa fa-history"></i> Kembali
		</a>
	</div>
</div>

<form class="form-horizontal" method="POST" action="<?=site_url('manajemen_operator/submit/' . $id)?>" enctype="multipart/form-data">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="box box-primary">
			    <div class="box-header with-border">
			        <h3 class="box-title">
			            <i class="fa fa-users"></i>&nbsp;&nbsp;&nbsp;Biodata Operator
			        </h3>
			    </div>
			    <div class="box-body">
                    <div class="form-group">
                    	<div class="col-md-6 col-md-offset-3">
			                <center>
			                    <img src="<?=default_foto_user(@$data->foto)?>" class="img-circle" width="50%">
			                </center>
			            </div>
			        </div>
                    <div class="form-group">
                    	<div class="col-md-6 col-md-offset-3">
	                        <label>Upload Foto</label>
                            <input type="file" class="form-control" name="userfiles" style="margin-bottom: 5px;">
                            <span>File harus berjenis JPG / PNG, Maks : 2 MB</span>
	                    </div>
                    </div>
                    <hr/>
					<div class="form-group">
				        <label class="col-md-3 control-label">Nama *</label>
				        <div class="col-md-6">
				            <input type="text" class="form-control" name="nama" value="<?=@$data->nama?>">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">No. Handphone *</label>
				        <div class="col-md-6">
				            <input type="text" class="form-control" name="no_hp" value="<?=@$data->no_hp?>">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Email *</label>
				        <div class="col-md-6">
				            <input type="email" class="form-control" name="email" value="<?=@$data->email?>">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Password *</label>
				        <div class="col-md-6">
				            <input type="password" class="form-control" name="password">
							<?php if(!empty($id)): ?>
								<span class="help-block">Kosongkan jika password tidak ingin diganti.</span>
							<?php endif; ?>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Sekolah *</label>
				        <div class="col-md-6">
				        	<?=form_dropdown('sekolah_id', $opt_sekolah, @$data->sekolah_id, 'class="form-control"')?>
				        </div>
				    </div>
				    <hr/>
				    <div class="form-group">
				        <div class="col-md-12 text-right">
				            <button type="submit" class="btn btn-default">
				            	<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
				            </button>
				            <a href="<?=site_url('manajemen_operator')?>" class="btn btn-default">
				            	<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
				            </a>
				        </div>
				    </div>
			    </div>
			</div>
		</div>
	</div>
</form>