<div class="row">
	<div class="col-md-10 col-md-offset-1">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
           			<i class="fa fa-info-circle"></i>&nbsp;&nbsp;&nbsp;Pengumuman
                </h3>
            </div>
            <div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('pengumuman')?>" class="btn btn-default">
							<i class="fa fa-history"></i> Kembali
						</a>
					</div>
				</div>
				<hr/>
				<form class="form-horizontal" method="POST" action="<?=site_url('pengumuman/submit/' . $id)?>" enctype="multipart/form-data">
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
				    <?php if(!empty($data->gambar)){ ?>
						<div class="form-group">
					        <label class="col-md-2 control-label"></label>
					        <div class="col-md-5">
					        	<img src="<?=load_gambar($data->gambar)?>" class="img-responsive">
					        </div>
					    </div>
				    <?php } ?>
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
				        <label class="col-md-2 control-label">Kelas</label>
				        <div class="col-md-5" id="respon-kelas"></div>
				    </div>
                    <hr/>
					<div class="form-group">
				        <label class="col-md-2 control-label">Target Pengumuman</label>
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
				            <button type="submit" class="btn btn-default">
				            	<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
				            </button>
				            <a href="<?=site_url('pengumuman')?>" class="btn btn-default">
				            	<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
				            </a>
				        </div>
				    </div>
				</form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	function load_kelas()
	{
		var kelas = [];
		<?php if(!empty($opt_kelas)){ foreach($opt_kelas as $key => $c): ?>
			kelas.push(<?=$c->kelas_id?>);
		<?php endforeach; } ?>

		$.ajax({
			url 		: "<?=site_url('pengumuman/ajax_form_kelas')?>",
			method		: "POST",
			data 		: "sekolah=" + $('select[name="sekolah_id"]').val() + "&kelas=" + JSON.stringify(kelas),
			success		: function(result){
				$('#respon-kelas').html(result);
			}
		})		
	}
	$('document').ready(function(){
		load_kelas();
	});
</script>