<div class="row" style="margin-bottom: 10px;">
	<div class="col-md-8 col-md-offset-2">
		<a href="<?=site_url('keuangan_master')?>" class="btn btn-default">
			<i class="fa fa-history"></i> Kembali
		</a>
	</div>
</div>

<form class="form-horizontal" method="POST" action="<?=site_url('keuangan_master/submit/' . $id)?>" enctype="multipart/form-data">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="box box-primary">
			    <div class="box-header with-border">
			        <h3 class="box-title">
			            <i class="fa fa-users"></i>&nbsp;&nbsp;&nbsp;Master Keuangan
			        </h3>
			    </div>
			    <div class="box-body">
					<div class="form-group">
				        <label class="col-md-3 control-label">Sekolah *</label>
				        <div class="col-md-6">
				        	<?=form_dropdown('sekolah_id', $opt_sekolah, @$data->sekolah_id, 'class="form-control"')?>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Nama *</label>
				        <div class="col-md-8">
				            <input type="text" class="form-control" name="nama" value="<?=@$data->nama?>">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Operasi *</label>
				        <div class="col-md-6">
				        	<?=form_dropdown('operasi', array('kredit' => 'Kredit', 'debit' => 'Debit'), @$data->jenis, 'class="form-control"')?>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Nominal</label>
				        <div class="col-md-6">
				            <input type="text" class="form-control" name="nominal" value="<?=@$data->nominal?>">
				        </div>
				    </div>
				</div>
				<div class="box-footer text-right">
		            <button type="submit" class="btn btn-primary">
		            	<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
		            </button>
		            <a href="<?=site_url('keuangan_master')?>" class="btn btn-default">
		            	<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
		            </a>
			    </div>
			</div>
		</div>
	</div>
</form>