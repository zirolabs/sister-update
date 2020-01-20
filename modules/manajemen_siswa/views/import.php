<div class="row" style="margin-bottom: 10px;">
	<div class="col-md-6 col-md-offset-3">
		<a href="<?=site_url('manajemen_siswa')?>" class="btn btn-default">
			<i class="fa fa-history"></i> Kembali
		</a>
	</div>
</div>

<form class="form-horizontal" method="POST" action="<?=site_url('manajemen_siswa/submit_import/')?>" enctype="multipart/form-data">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="box box-primary">
			    <div class="box-header with-border">
			        <h3 class="box-title">
			            <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;Import Siswa
			        </h3>
			    </div>
			    <div class="box-body">
					<div class="form-group">
				        <label class="col-md-3 control-label">File</label>
				        <div class="col-md-8">
				            <input type="file" class="form-control" name="userfiles">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label"></label>
				        <div class="col-md-8">
				            <a href="<?=base_url('assets/sample_import/import_siswa.xlsx')?>" class="btn btn-success btn-xs" class="_blank">
				            	Download Sample File Import
				            </a>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Sekolah *</label>
				        <div class="col-md-6">
				        	<?=form_dropdown('sekolah_id', $opt_sekolah, @$data->sekolah_id, 'class="form-control" onchange="get_kelas(this.value)"')?>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Kelas *</label>
				        <div class="col-md-6" id="area_kelas">
				        	<?=form_dropdown('kelas_id', array(), '', 'class="form-control"')?>
				        </div>
				    </div>
				    <hr/>
				    <div class="form-group">
				        <div class="col-md-12 text-right">
				            <button type="submit" class="btn btn-primary">
				            	<i class="fa fa-check"></i>&nbsp;&nbsp;Submit
				            </button>
				            <a href="<?=site_url('manajemen_siswa')?>" class="btn btn-default">
				            	<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
				            </a>
				        </div>
				    </div>
			    </div>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript">
	function get_kelas(id)
	{
		$.ajax({
			url 	: "<?=site_url('manajemen_siswa/get_kelas?selected=' . @$data->kelas_id . '&sekolah_id=')?>" + id,
			method	: 'GET',
			success	: function(result){
				$('#area_kelas').html(result);
			}

		});
	}

	get_kelas($('select[name="sekolah_id"]').val());
</script>