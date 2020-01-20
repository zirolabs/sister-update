<div class="row">
	<div class="col-md-8 col-md-offset-2">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Pengaturan Kelas
                </h3>
            </div>
            <div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('pengaturan_kelas')?>" class="btn btn-default">
							<i class="fa fa-history"></i> Kembali
						</a>
					</div>
				</div>
				<hr/>
				<form class="form-horizontal" method="POST" action="<?=site_url('pengaturan_kelas/submit/' . $id)?>" enctype="multipart/form-data">
					<div class="form-group">
				        <label class="col-md-3 control-label">Sekolah</label>
				        <div class="col-md-5">
				        	<?=form_dropdown('sekolah_id', $opt_sekolah, @$data->sekolah_id, 'class="form-control"')?>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Jurusan</label>
				        <div class="col-md-5">
				        	<?=form_dropdown('jurusan_id', $opt_jurusan, @$data->jurusan_id, 'class="form-control"')?>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Jenjang</label>
				        <div class="col-md-3">
				            <input type="number" class="form-control" name="jenjang" value="<?=@$data->jenjang?>">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Nama</label>
				        <div class="col-md-7">
				            <input type="text" class="form-control" name="nama" value="<?=@$data->nama?>">
				        </div>
				    </div>
                    <hr/>
				    <div class="form-group">
				        <div class="col-md-12 text-right">
				            <button type="submit" class="btn btn-default">
				            	<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
				            </button>
				            <a href="<?=site_url('pengaturan_kelas')?>" class="btn btn-default">
				            	<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
				            </a>
				        </div>
				    </div>
				</form>
            </div>
        </div>
    </div>
</div>
