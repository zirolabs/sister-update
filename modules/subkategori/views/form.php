<form class="form-horizontal" method="POST" action="<?=site_url('subkategori/submit/'. $id)?>">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="box box-primary">
			    <div class="box-header with-border">
			        <h3 class="box-title">
			            <i class="fa fa-tag"></i>&nbsp;&nbsp;&nbsp;Sub Kategori Pelanggaran
			        </h3>
			    </div>
			    <div class="box-body">
					<div class="row">
						<div class="col-md-12">
							<a href="<?=site_url('subkategori')?>" class="btn btn-default">
								<i class="fa fa-history"></i> Kembali
							</a>
						</div>
					</div>
					<hr/>
					<div class="form-group">
				        <label class="col-md-2 control-label">Kategori Pelanggaran *</label>
				        <div class="col-md-8">
							<?=form_dropdown('kategori', $opt_kategori, @$data->id_kategori, 'class="form-control"')?>
						</div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Deskripsi Pelanggaran *</label>
				        <div class="col-md-8">
				            <input type="text" class="form-control" name="subkategori" value="<?=@$data->deskripsi_pelanggaran?>">
				        </div>
				    </div>
					<div class="form-group">
						<label class="col-md-2 control-label">Point *</label>
				        <div class="col-md-8">
				            <input type="number" class="form-control" name="point" value="<?=@$data->point_pelanggaran?>">
				        </div>
				    </div>
					<hr/>
				    <div class="form-group">
				        <div class="col-md-12 text-right">
				            <button type="submit" class="btn btn-default">
				            	<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
				            </button>
				            <a href="<?=site_url('subkategori')?>" class="btn btn-default">
				            	<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
				            </a>
				        </div>
				    </div>
				</div>
			</div>
		</div>
	</div>
</form>