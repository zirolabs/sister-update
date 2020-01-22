<div class="row">
	<div class="col-md-10 col-md-offset-1">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
           			<i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Pesan Broadcast
                </h3>
            </div>
            <div class="box-body">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><b>Kirim Berdasarkan Kelas</b></a></li>
					<?php if($login_level == 'administrator' || $login_level == 'operator sekolah' || $login_level == 'kepala sekolah'){ ?>
					<li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><b>Kirim Berdasarkan Jabatan</b></a></li>
					<?php } ?>
				</ul>
			</div>
            <div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					<form class="form-horizontal" method="POST" action="<?=site_url('pesan_broadcast/submit/' . $id)?>" enctype="multipart/form-data">
						<div class="form-group">
							<label class="col-md-2 control-label">Isi</label>
							<div class="col-md-9">
								<textarea class="form-control" name="isi" rows="7"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Gambar</label>
							<div class="col-md-5">
								<input type="file" class="form-control" name="userfiles">
							</div>
						</div>
						<hr/>
						<div class="form-group">
							<label class="col-md-2 control-label">Sekolah</label>
							<div class="col-md-5">
								<?=form_dropdown('sekolah_id', $opt_sekolah, @$data->sekolah_id, 'class="form-control" onchange="load_kelas()"')?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Kelas Tujuan</label>
							<div class="col-md-5" id="respon-kelas"></div>
						</div>
						<hr/>
						<div class="form-group">
							<label class="col-md-2 control-label">Target Pesan</label>
							<div class="col-md-8">
								<div class="checkbox">
									<label>
										<input type="checkbox" value="Y" name="target_siswa"> Siswa
									</label>
								</div>				        	
								<div class="checkbox">
									<label>
										<input type="checkbox" value="Y" name="target_wali"> Wali Siswa
									</label>
								</div>				        	
								<?php if($login_level == 'administrator' || $login_level == 'operator sekolah' || $login_level == 'kepala sekolah'){ ?>
									<div class="checkbox">
										<label>
											<input type="checkbox" value="Y" name="target_wali_kelas"> Wali Kelas
										</label>
									</div>				        				        	
								<?php } ?>
							</div>
						</div>
						<hr/>
						<div class="form-group">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-default">
									<i class="fa fa-send"></i>&nbsp;&nbsp;Kirim
								</button>
							</div>
						</div>
					</form>
				</div>
				<div class="tab-pane" id="tab_2">
				<form class="form-horizontal" method="POST" action="<?=site_url('pesan_broadcast/submitpegawai/' . $id)?>" enctype="multipart/form-data">
						<div class="form-group">
							<label class="col-md-2 control-label">Isi</label>
							<div class="col-md-9">
								<textarea class="form-control" name="isi" rows="7"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Gambar</label>
							<div class="col-md-5">
								<input type="file" class="form-control" name="userfiles">
							</div>
						</div>
						<hr/>
						<div class="form-group">
							<label class="col-md-2 control-label">Sekolah</label>
							<div class="col-md-5">
								<?=form_dropdown('sekolah_id', $opt_sekolah, @$data->sekolah_id, 'class="form-control" onchange="load_kelas()"')?>
							</div>
						</div>
						<hr/>
						<div class="form-group">
							<?php if($login_level == 'administrator' || $login_level == 'operator sekolah' || $login_level == 'kepala sekolah'){ ?>
								<div class="form-group">
									<label class="col-md-2 control-label">Kirim Juga ke</label>
									<div class="col-md-5">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="Y" name="target_operator_sekolah"> Operator Sekolah
											</label>
										</div>				        	
										<div class="checkbox">
											<label>
												<input type="checkbox" value="Y" name="target_kepala_sekolah"> Kepala Sekolah
											</label>
										</div>
										<!-- Menambahkan Checkbox untuk target semua pegawai -->
										<div class="checkbox">
											<label>
												<input type="checkbox" value="Y" name="target_guru_staff"> Guru dan Staff
											</label>
										</div>					        	
									</div>
								</div>
							<?php } ?>
						</div>
						<hr>
						<div class="form-group">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-default">
									<i class="fa fa-send"></i>&nbsp;&nbsp;Kirim
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>				
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	function load_kelas()
	{
		$.ajax({
			url 		: "<?=site_url('pesan_broadcast/ajax_form_kelas')?>",
			method		: "POST",
			data 		: "sekolah=" + $('select[name="sekolah_id"]').val(),
			success		: function(result){
				$('#respon-kelas').html(result);
			}
		})		
	}
	$('document').ready(function(){
		load_kelas();
	});
</script>