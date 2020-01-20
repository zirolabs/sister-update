<div class="row">
	<div class="col-md-10 col-md-offset-1">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Upload Materi Mata Pelajaran
                </h3>
            </div>
            <div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('mata_pelajaran')?>" class="btn btn-default">
							<i class="fa fa-history"></i> Kembali
						</a>
					</div>
				</div>
				<hr/>
				<form class="form-horizontal" method="POST" action="<?=site_url('mata_pelajaran/submit/' . $id)?>" enctype="multipart/form-data">
					<div class="form-group">
				        <label class="col-md-2 control-label">Mata Pelajaran</label>
				        <div class="col-md-5">
				        	<?=form_dropdown('mata_pelajaran_id', $opt_mata_pelajaran, @$data->mata_pelajaran_id, 'class="form-control"')?>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Judul</label>
				        <div class="col-md-9">
				            <input type="text" class="form-control" name="judul" value="<?=@$data->judul?>">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Deskripsi</label>
				        <div class="col-md-9">
				        	<textarea class="form-control" rows="5" name="keterangan"><?=@$data->keterangan?></textarea>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Upload File</label>
					    <?php if(!empty($data->lokasi_file)){ ?>
					        <div class="col-md-4">
					        	<a href="<?=base_url($data->lokasi_file)?>" class="btn btn-primary btn-block" target="_blank">
					        		<i class="fa fa-link"></i> Lihat File Sebelumnya
					        	</a>
					        </div>
				        <?php } ?>
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
				        <div class="col-md-12 text-right">
				            <button type="submit" class="btn btn-default">
				            	<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
				            </button>
				            <a href="<?=site_url('mata_pelajaran')?>" class="btn btn-default">
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
			url 		: "<?=site_url('mata_pelajaran_materi/ajax_form_kelas')?>",
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
