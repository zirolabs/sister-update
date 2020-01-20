
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Import Jadwal Pelajaran
                </h3>
            </div>
			<form class="form-horizontal" method="POST" action="<?=site_url('pengaturan_jadwal_pelajaran/import_submit/?' . $url_param)?>" enctype="multipart/form-data" autocomplete="off">
	            <div class="box-body">
					<div class="row">
						<div class="col-md-12">
							<a href="<?=site_url('pengaturan_jadwal_pelajaran/index?' . $url_param)?>" class="btn btn-default">
								<i class="fa fa-history"></i> Kembali
							</a>
						</div>
					</div>
					<hr/>
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
						        <label class="col-md-3 control-label">Sekolah</label>
						        <div class="col-md-5">
						        	<p class="form-control-static"><?=$data_sekolah->nama?></p>
						        </div>
						    </div>
							<div class="form-group">
						        <label class="col-md-3 control-label">Kelas</label>
						        <div class="col-md-5">
						        	<p class="form-control-static"><?=$data_kelas->jenjang . ' ' . $data_kelas->nama_jurusan . ' ' . $data_kelas->nama?></p>
						        </div>
						    </div>
							<div class="form-group">
						        <label class="col-md-3 control-label">File Excel</label>
						        <div class="col-md-8">
						        	<input type="file" class="form-control" name="userfiles">
						        </div>
						    </div>
						</div>
						<div class="col-md-4">
							<a href="<?=base_url('assets/sample_import/import_jadwal.xlsx')?>" class="btn btn-success btn-block">
								<i class="fa fa-download"></i>&nbsp;&nbsp;&nbsp;Download Format Import
							</a>
							<a href="<?=site_url('pengaturan_jadwal_pelajaran/download_kode/' . $sekolah)?>" class="btn btn-success btn-block" target="_blank">
								<i class="fa fa-download"></i>&nbsp;&nbsp;&nbsp;Download Kode Guru & Mata Pelajaran
							</a>
						</div>
					</div>
				</div>
				<div class="box-footer text-right">
		            <a href="<?=site_url('pengaturan_jadwal_pelajaran/index?' . $url_param)?>" class="btn btn-default">
		            	<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
		            </a>
		            <button type="submit" class="btn btn-primary">
		            	<i class="fa fa-check"></i>&nbsp;&nbsp;Mulai Import
		            </button>
				</div>
			</form>
		</div>


<link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte')?>/plugins/timepicker/bootstrap-timepicker.min.css">
<script src="<?=base_url('vendor/almasaeed2010/adminlte')?>/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script type="text/javascript">
	$(function () {
	 	$(".timepicker").timepicker({
	    	showInputs		: false,
	    	showMeridian	: false,
	    });
	});	
</script>