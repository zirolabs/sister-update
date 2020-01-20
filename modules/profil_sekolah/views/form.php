<form class="form-horizontal" method="POST" action="<?=site_url('profil_sekolah/submit/' . $id)?>" enctype="multipart/form-data">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="box box-primary">
			    <div class="box-header with-border">
			        <h3 class="box-title">
			            <i class="fa fa-university"></i>&nbsp;&nbsp;&nbsp;Manajemen Sekolah
			        </h3>
			    </div>
			    <div class="box-body">
					<div class="row">
						<div class="col-md-12">
							<a href="<?=site_url('profil_sekolah')?>" class="btn btn-default">
								<i class="fa fa-history"></i> Kembali
							</a>
						</div>
					</div>
					<hr/>
					<div class="form-group">
				        <label class="col-md-2 control-label">NISN *</label>
				        <div class="col-md-5">
				            <input type="text" class="form-control" name="nisn" value="<?=@$data->nisn?>" <?=!empty($data) ? 'readonly' : ''?>>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Nama *</label>
				        <div class="col-md-8">
				            <input type="text" class="form-control" name="nama" value="<?=@$data->nama?>">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Email</label>
				        <div class="col-md-8">
				            <input type="text" class="form-control" name="email" value="<?=@$data->email?>">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Telepon</label>
				        <div class="col-md-5">
				            <input type="text" class="form-control" name="telepon" value="<?=@$data->telepon?>">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Alamat</label>
				        <div class="col-md-8">
				            <input type="text" class="form-control" name="alamat" value="<?=@$data->alamat?>">
				        </div>
				    </div>
				</div>
			</div>
			<div class="box box-primary">
			    <div class="box-header with-border">
			        <h3 class="box-title">
			            <i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;Login Kepala Sekolah
			        </h3>
			    </div>
			    <div class="box-body">
					<div class="form-group">
				        <label class="col-md-2 control-label">NIP</label>
				        <div class="col-md-5">
				            <input type="text" class="form-control" name="user_nip" value="<?=@$data->user_nip?>">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Nama</label>
				        <div class="col-md-5">
				            <input type="text" class="form-control" name="user_nama" value="<?=@$data->user_nama?>">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Email</label>
				        <div class="col-md-5">
				            <input type="text" class="form-control" name="user_email" value="<?=@$data->user_email?>" <?=!empty($data) ? 'readonly' : ''?>>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Password</label>
				        <div class="col-md-5">
				            <input type="password" class="form-control" name="user_password" value="">
							<?php if(!empty($data)){ ?>
								<span class="help-block">Kosongkan jika password tidak ingin diganti.</span>
							<?php } ?>
				        </div>
				    </div>
				    <hr/>
				    <div class="form-group">
				        <div class="col-md-12 text-right">
				            <button type="submit" class="btn btn-default">
				            	<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
				            </button>
				            <a href="<?=site_url('profil_sekolah')?>" class="btn btn-default">
				            	<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
				            </a>
				        </div>
				    </div>
				</form>
		    </div>
		</div>
	</div>
</div>