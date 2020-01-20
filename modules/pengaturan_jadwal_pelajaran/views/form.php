<div class="row">
	<div class="col-md-8 col-md-offset-2">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Jadwal Pelajaran
                </h3>
            </div>
            <div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('pengaturan_jadwal_pelajaran/index?' . $url_param)?>" class="btn btn-default">
							<i class="fa fa-history"></i> Kembali
						</a>
					</div>
				</div>
				<hr/>
				<form class="form-horizontal" method="POST" action="<?=site_url('pengaturan_jadwal_pelajaran/submit/' . $id . '?' . $url_param)?>" enctype="multipart/form-data" autocomplete="off">
					<div class="form-group">
				        <label class="col-md-4 control-label">Sekolah</label>
				        <div class="col-md-5">
				        	<p class="form-control-static"><?=$data_sekolah->nama?></p>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-4 control-label">Kelas</label>
				        <div class="col-md-5">
				        	<p class="form-control-static"><?=$data_kelas->jenjang . ' ' . $data_kelas->nama_jurusan . ' ' . $data_kelas->nama?></p>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-4 control-label">Hari</label>
				        <div class="col-md-5">
				        	<p class="form-control-static"><?=hari_indonesia($hari)?></p>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-4 control-label">Mata Pelajaran</label>
				        <div class="col-md-7">
				        	<?=form_dropdown('mata_pelajaran_id', $opt_mata_pelajaran, @$data->mata_pelajaran_id, 'class="form-control"')?>
				        </div>
				    </div>
                                    <?php if(in_array($login_level, array('guru'))){ ?>
                                    <input type="hidden" class="form-control" value="<?=$this->login_uid?>" name="user_id">
                                    <?php }else{ ?>
                                    <div class="form-group">
				        <label class="col-md-4 control-label">Guru</label>
				        <div class="col-md-7">
				        	<?=form_dropdown('user_id', $opt_guru, @$data->user_id, 'class="form-control"')?>
				        </div>
				    </div>
                                     <?php } ?>
					<div class="form-group">
				        <label class="col-md-4 control-label">Jam Mulai</label>
				        <div class="col-md-5">
				        	<div class="bootstrap-timepicker">
								<div class="input-group">
									<input type="text" class="form-control timepicker" value="<?=@$data->jam_mulai?>" name="jam_mulai">
									<div class="input-group-addon">
										<i class="fa fa-clock-o"></i>
									</div>
								</div>				        	
							</div>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-4 control-label">Jam Selesai</label>
				        <div class="col-md-5">
				        	<div class="bootstrap-timepicker">
								<div class="input-group">
									<input type="text" class="form-control timepicker" value="<?=@$data->jam_akhir?>" name="jam_akhir">
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
				            <a href="<?=site_url('pengaturan_jadwal_pelajaran/index?' . $url_param)?>" class="btn btn-default">
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