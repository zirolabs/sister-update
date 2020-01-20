<div class="row">
	<div class="col-md-8 col-md-offset-2">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Pengaturan Jam Sesi
                </h3>
            </div>
            <div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('pengaturan_jam_sesi')?>" class="btn btn-default">
							<i class="fa fa-history"></i> Kembali
						</a>
					</div>
				</div>
				<hr/>
				<form class="form-horizontal" method="POST" action="<?=site_url('pengaturan_jam_sesi/submit/' . $id)?>" enctype="multipart/form-data" autocomplete="off">
					<div class="form-group">
				        <label class="col-md-4 control-label">Sekolah</label>
				        <div class="col-md-5">
				        	<?=form_dropdown('sekolah_id', $opt_sekolah, @$data->sekolah_id, 'class="form-control"')?>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-4 control-label">Sesi</label>
				        <div class="col-md-5">
				        	<?=form_dropdown('sesi_id', $opt_sesi, @$data->sesi_id, 'class="form-control"')?>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-4 control-label">Masuk</label>
				        <div class="col-md-5">
				        	<div class="bootstrap-timepicker">
								<div class="input-group">
									<input type="text" class="form-control timepicker" value="<?=@$data->masuk?>" name="masuk">
									<div class="input-group-addon">
										<i class="fa fa-clock-o"></i>
									</div>
								</div>				        	
							</div>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-4 control-label">Pulang</label>
				        <div class="col-md-5">
				        	<div class="bootstrap-timepicker">
								<div class="input-group">
									<input type="text" class="form-control timepicker" value="<?=@$data->pulang?>" name="pulang">
									<div class="input-group-addon">
										<i class="fa fa-clock-o"></i>
									</div>
								</div>				        	
							</div>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-4 control-label">Toleransi Telat</label>
				        <div class="col-md-5">
				        	<div class="bootstrap-timepicker">
								<div class="input-group">
									<input type="text" class="form-control timepicker" value="<?=@$data->toleransi_telat?>" name="toleransi_telat">
									<div class="input-group-addon">
										<i class="fa fa-clock-o"></i>
									</div>
								</div>				        	
							</div>
				        </div>
				    </div>
                    <hr/>
				    <div class="form-group">
				        <div class="col-md-12 text-right">
				            <button type="submit" class="btn btn-default">
				            	<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
				            </button>
				            <a href="<?=site_url('pengaturan_jam_sesi')?>" class="btn btn-default">
				            	<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
				            </a>
				        </div>
				    </div>
				</form>
            </div>
        </div>
    </div>
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