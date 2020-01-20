<div class="row">
	<div class="col-md-6 col-md-offset-3">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Pengaturan Semester
                </h3>
            </div>
            <div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('pengaturan_semester')?>" class="btn btn-default">
							<i class="fa fa-history"></i> Kembali
						</a>
					</div>
				</div>
				<hr/>
				<form class="form-horizontal" method="POST" action="<?=site_url('pengaturan_semester/submit/' . $id)?>" enctype="multipart/form-data">
					<div class="form-group">
				        <label class="col-md-3 control-label">Nama</label>
				        <div class="col-md-8">
				            <input type="text" class="form-control" name="nama" value="<?=@$data->nama?>">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Tahun Mulai</label>
				        <div class="col-md-5">
				            <input type="text" class="form-control" name="tahun_mulai" value="<?=@$data->tahun_mulai?>">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Tahun Akhir</label>
				        <div class="col-md-5">
				            <input type="text" class="form-control" name="tahun_akhir" value="<?=@$data->tahun_akhir?>">
				        </div>
				    </div>
                    <hr/>
				    <div class="form-group">
				        <div class="col-md-12 text-right">
				            <button type="submit" class="btn btn-default">
				            	<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
				            </button>
				            <a href="<?=site_url('pengaturan_semester')?>" class="btn btn-default">
				            	<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
				            </a>
				        </div>
				    </div>
				</form>
            </div>
        </div>
    </div>
</div>
