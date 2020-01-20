<div class="row" style="margin-bottom: 10px;">
	<div class="col-md-6 col-md-offset-3">
		<a href="<?=site_url('manajemen_guru')?>" class="btn btn-default">
			<i class="fa fa-history"></i> Kembali
		</a>
	</div>
</div>

<form class="form-horizontal" method="POST" action="<?=site_url('manajemen_guru/submit_import/')?>" enctype="multipart/form-data">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="box box-primary">
			    <div class="box-header with-border">
			        <h3 class="box-title">
			            <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;Import Guru
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
				            <a href="<?=base_url('assets/sample_import/import_guru.xlsx')?>" class="btn btn-success btn-xs" class="_blank">
				            	Download Sample File Import
				            </a>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Sekolah *</label>
				        <div class="col-md-6">
				        	<?=form_dropdown('sekolah_id', $opt_sekolah, @$data->sekolah_id, 'class="form-control"')?>
				        </div>
				    </div>
				    <hr/>
				    <div class="form-group">
				        <div class="col-md-12 text-right">
				            <button type="submit" class="btn btn-primary">
				            	<i class="fa fa-check"></i>&nbsp;&nbsp;Submit
				            </button>
				            <a href="<?=site_url('manajemen_guru')?>" class="btn btn-default">
				            	<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
				            </a>
				        </div>
				    </div>
			    </div>
			</div>
		</div>
	</div>
</form>