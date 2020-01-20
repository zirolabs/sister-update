<div class="row" style="margin-bottom: 10px;">
	<div class="col-md-12">
		<a href="<?=site_url('manajemen_siswa')?>" class="btn btn-default">
			<i class="fa fa-history"></i> Kembali
		</a>
	</div>
</div>

<form class="form-horizontal" method="POST" action="<?=site_url('manajemen_siswa/submit/' . $id)?>" enctype="multipart/form-data">
	<div class="row">
		<div class="col-md-4">
	        <div class="box box-primary">
	            <div class="box-header with-border">
	                <h3 class="box-title">
	                    <i class="fa fa-image"></i>&nbsp;&nbsp;&nbsp;Foto
	                </h3>
	            </div>
	            <div class="box-body">
	                <center>
	                    <img src="<?=default_foto_user(@$data->foto)?>" class="img-circle" width="50%">
	                </center>
	                <hr/>
                    <div class="form-group">
                    	<div class="col-md-12">
	                        <label>Upload Foto</label>
                            <input type="file" class="form-control" name="userfiles" style="margin-bottom: 5px;">
                            <span>File harus berjenis JPG / PNG, Maks : 2 MB</span>
	                    </div>
                    </div>
	            </div>
	        </div>
		</div>
		<div class="col-md-8">
			<div class="box box-primary">
			    <div class="box-header with-border">
			        <h3 class="box-title">
			            <i class="fa fa-users"></i>&nbsp;&nbsp;&nbsp;Biodata Siswa
			        </h3>
			    </div>
			    <div class="box-body">
					<div class="form-group">
				        <label class="col-md-3 control-label">Sekolah *</label>
				        <div class="col-md-5">
				        	<?=form_dropdown('sekolah_id', $opt_sekolah, @$data->sekolah_id, 'class="form-control"')?>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Kelas *</label>
				        <div class="col-md-5" id="area_kelas">
				        	<?=form_dropdown('kelas_id', array(), '', 'class="form-control"')?>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">NIS *</label>
				        <div class="col-md-5">
				            <input type="text" class="form-control" name="nis" value="<?=@$data->nis?>" <?=!empty($id) ? 'readonly' : '' ?>>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Nama *</label>
				        <div class="col-md-8">
				            <input type="text" class="form-control" name="nama" value="<?=@$data->nama?>">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Alamat</label>
				        <div class="col-md-9">
				            <input type="text" class="form-control" name="alamat" value="<?=@$data->alamat?>">
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
			    </div>
			</div>

			<div class="box box-primary">
			    <div class="box-header with-border">
			        <h3 class="box-title">
			            <i class="fa fa-users"></i>&nbsp;&nbsp;&nbsp;Orang Tua
			        </h3>
			    </div>
			    <div class="box-body">
					<div class="form-group">
				        <label class="col-md-3 control-label">Bapak</label>
				        <div class="col-md-8">
				            <input type="text" class="form-control" name="nama_ortu_bapak" value="<?=@$data->nama_ortu_bapak?>">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Ibu</label>
				        <div class="col-md-8">
				            <input type="text" class="form-control" name="nama_ortu_ibu" value="<?=@$data->nama_ortu_ibu?>">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Alamat</label>
				        <div class="col-md-9">
				            <input type="text" class="form-control" name="alamat_ortu" value="<?=@$data->alamat_ortu?>">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">No. Handphone</label>
				        <div class="col-md-6">
				            <input type="text" class="form-control" name="no_hp_ortu" value="<?=@$data->no_hp_ortu?>">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Password Orang Tua *</label>
				        <div class="col-md-6">
				            <input type="password" class="form-control" name="password_ortu">
							<?php if(!empty($id)): ?>
								<span class="help-block">Kosongkan jika password tidak ingin diganti.</span>
							<?php endif; ?>
				        </div>
				    </div>
				    <hr/>
				    <div class="form-group">
				        <div class="col-md-12 text-right">
				            <button type="submit" class="btn btn-default">
				            	<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
				            </button>
				            <a href="<?=site_url('manajemen_siswa')?>" class="btn btn-default">
				            	<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
				            </a>
				        </div>
				    </div>
				</div>
			</div>						
		</div>
	</div>
</form>

<script type="text/javascript">
	function get_kelas(id)
	{
		$.ajax({
			url 	: "<?=site_url('manajemen_siswa/get_kelas?selected=' . @$data->kelas_id . '&sekolah_id=')?>" + id,
			method	: 'GET',
			success	: function(result){
				$('#area_kelas').html(result);
			}

		});
	}

	get_kelas($('select[name="sekolah_id"]').val());
</script>