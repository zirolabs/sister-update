<div class="row">
	<div class="col-md-10 col-md-offset-1">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Input Jurnal Guru
                </h3>
            </div>
            <div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('jurnal_guru')?>" class="btn btn-default">
							<i class="fa fa-history"></i> Kembali
						</a>
					</div>
				</div>
				<hr/>
				<form class="form-horizontal" method="POST" action="<?=site_url('jurnal_guru/submit')?>" enctype="multipart/form-data">
					<div class="form-group">
                        <input type="hidden" name="id" value="<?=$data->jadwal_id?>">
                        <input type="hidden" name="hari" value="<?=$data->hari?>">
				        <label class="col-md-4 control-label">Sekolah</label>
				        <div class="col-md-5">
				        	<p class="form-control-static"><?=$data->sekolah?></p>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-4 control-label">Kelas</label>
				        <div class="col-md-5">
				        	<p class="form-control-static"><?=$data->kelas?></p>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-4 control-label">Mata Pelajaran</label>
				        <div class="col-md-5">
				        	<p class="form-control-static"><?=$data->nama_mata_pelajaran?></p>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-4 control-label">Jam</label>
				        <div class="col-md-5">
				        	<p class="form-control-static"><?=$data->jam_mulai?> - <?=$data->jam_akhir?></p>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-4 control-label">Hari</label>
				        <div class="col-md-5">
				        	<p class="form-control-static"><?=hari_indonesia($data->hari)?></p>
				        </div>
				    </div>
                                    <hr/>    
                                    <div class="form-group">
				        <label class="col-md-2 control-label">Materi Yang Diajarkan</label>
				        <div class="col-md-6">
                                            <textarea class="form-control" name="isi_materi" rows="7"></textarea>
				        </div>
				    </div>
                                    <div class="form-group">
				        <label class="col-md-2 control-label">Target Belajar</label>
				        <div class="col-md-6">
                                            <textarea class="form-control" name="target" rows="7"></textarea>
				        </div>
				    </div>
                                    <div class="form-group">
				        <label class="col-md-2 control-label">Siswa yang hadir</label>
				        <div class="col-md-6">
				            <input type="text" class="form-control" name="hadir" value="">
				        </div>
				    </div>
                                    <div class="form-group">
				        <label class="col-md-2 control-label">Siswa yang Ijin</label>
				        <div class="col-md-6">
				            <input type="text" class="form-control" name="ijin" value="">
				        </div>
				    </div>
                                    <div class="form-group">
				        <label class="col-md-2 control-label">Siswa yang Alpa</label>
				        <div class="col-md-6">
				            <input type="text" class="form-control" name="alpha" value="">
				        </div>
				    </div>
                                    <div class="form-group">
				        <label class="col-md-2 control-label">Keterangan</label>
				        <div class="col-md-6">
                                            <textarea class="form-control" name="keterangan" rows="7"></textarea>
				        </div>
				    </div>
                                    <hr/>
				    <div class="form-group">
				        <div class="col-md-12 text-right">
				            <button type="submit" class="btn btn-default">
				            	<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
				            </button>
				            <a href="<?=site_url('jurnal_guru')?>" class="btn btn-default">
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
			url 		: "<?=site_url('jurnal_guru/ajax_form_kelas')?>",
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
