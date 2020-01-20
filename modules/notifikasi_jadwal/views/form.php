<div class="row">
	<div class="col-md-10 col-md-offset-1">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
           			<i class="fa fa-calendar"></i>&nbsp;&nbsp;&nbsp;Notifikasi Terjadwal
                </h3>
            </div>
            <div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('notifikasi_jadwal')?>" class="btn btn-default">
							<i class="fa fa-history"></i> Kembali
						</a>
					</div>
				</div>
				<hr/>
				<form class="form-horizontal" method="POST" action="<?=site_url('notifikasi_jadwal/submit/' . $id)?>" enctype="multipart/form-data">
					<div class="form-group">
				        <label class="col-md-2 control-label">Judul</label>
				        <div class="col-md-9">
				            <input type="text" class="form-control" name="judul" value="<?=@$data->judul?>">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Isi</label>
				        <div class="col-md-9">
				        	<textarea class="form-control" name="isi" rows="5"><?=@$data->isi?></textarea>
				        </div>
				    </div>
				    <div class="row">
				    	<div class="col-md-6">
							<div class="form-group">
						        <label class="col-md-4 control-label">Kirim Setiap</label>
						        <div class="col-md-8">
						        	<div class="row">
							        	<?php foreach($list_hari as $key => $c): ?>
							        		<div class="col-md-6">
												<div class="checkbox">
												    <label>
												      	<input type="checkbox" value="<?=$key?>" name="hari[]" <?=!empty($data->hari) ? (in_array($key, $data->hari) ? 'checked' : '') : ''?>> <?=$c?>
												    </label>
												</div>				        	
											</div>
							        	<?php endforeach; ?>
							        </div>
						        </div>
						    </div>
				    	</div>
				    	<div class="col-md-6">
							<div class="form-group">
						        <label class="col-md-3 control-label">Waktu</label>
						        <div class="col-md-5">
					              	<div class="bootstrap-timepicker">
							            <input type="text" name="waktu" class="form-control timepicker" readonly value="<?=empty($data->waktu) ? date('H:i') : $data->waktu?>">
							      	</div>
						        </div>
						    </div>
				    	</div>
				    </div>
                    <hr style="margin: 10px 0;" />
					<div class="form-group">
				        <label class="col-md-2 control-label">Sekolah</label>
				        <div class="col-md-5">
				        	<?=form_dropdown('sekolah_id', $opt_sekolah, @$data->sekolah_id, 'class="form-control" onchange="load_kelas()"')?>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Kelas</label>
				        <div class="col-md-5" id="respon-kelas"></div>
				    </div>
                    <hr style="margin: 5px 0;" />
					<div class="form-group">
				        <label class="col-md-2 control-label">Target Notifikasi</label>
				        <div class="col-md-5">
							<div class="checkbox">
							    <label>
							      	<input type="checkbox" value="Y" name="target_siswa" <?=@$data->target_siswa == 'Y' ? 'checked' : ''?>> Siswa
							    </label>
							</div>				        	
							<div class="checkbox">
							    <label>
							      	<input type="checkbox" value="Y" name="target_wali" <?=@$data->target_wali == 'Y' ? 'checked' : ''?>> Wali Siswa
							    </label>
							</div>				        	
							<?php if($login_level == 'administrator' || $login_level == 'operator sekolah' || $login_level == 'kepala sekolah'){ ?>
								<div class="checkbox">
								    <label>
								      	<input type="checkbox" value="Y" name="target_wali_kelas" <?=@$data->target_wali_kelas == 'Y' ? 'checked' : ''?>> Wali Kelas
								    </label>
								</div>				        	
								<div class="checkbox">
								    <label>
								      	<input type="checkbox" value="Y" name="target_guru" <?=@$data->target_guru == 'Y' ? 'checked' : ''?>> Guru Mata Pelajaran
								    </label>
								</div>				        	
							<?php } ?>
				        </div>
				    </div>
                    <hr/>
				    <div class="form-group">
				        <div class="col-md-12 text-right">
				            <button type="submit" class="btn btn-primary">
				            	<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
				            </button>
				            <a href="<?=site_url('notifikasi_jadwal')?>" class="btn btn-default">
				            	<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
				            </a>
				        </div>
				    </div>
				</form>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte')?>/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte')?>/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css">
<script src="<?=base_url('vendor/almasaeed2010/adminlte')?>/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>

<link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte')?>/plugins/timepicker/bootstrap-timepicker.min.css">
<script src="<?=base_url('vendor/almasaeed2010/adminlte')?>/plugins/timepicker/bootstrap-timepicker.min.js"></script>

<script type="text/javascript">
	function load_kelas()
	{
		var kelas = [];
		<?php if(!empty($opt_kelas)){ foreach($opt_kelas as $key => $c): ?>
			kelas.push(<?=$c->kelas_id?>);
		<?php endforeach; } ?>

		$.ajax({
			url 		: "<?=site_url('notifikasi_jadwal/ajax_form_kelas')?>",
			method		: "POST",
			data 		: "sekolah=" + $('select[name="sekolah_id"]').val() + "&kelas=" + JSON.stringify(kelas),
			success		: function(result){
				$('#respon-kelas').html(result);
			}
		})		
	}

	$('document').ready(function(){
	    $('input[name="tgl_kirim"]').datepicker({
	        autoclose   : true,
	        format      : 'yyyy-mm-dd'
	    });    

	    $('.timepicker').timepicker({
      		showInputs 		: false,
      		showMeridian	: false,
    	});	    
		load_kelas();
	});
</script>