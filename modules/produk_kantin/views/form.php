<div class="row">
	<div class="col-md-10 col-md-offset-1">
        <div class="box box-primary">
			<div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Tambah Produk
                </h3>
            </div>
			<form class="form-horizontal" method="POST" action="<?=site_url('produk_kantin/submit/' . $id)?>" enctype="multipart/form-data">
				<div class="row">
					<div>
						<div class="box-body">
							<div class="col-md-12">
								<a href="<?=site_url('produk_kantin')?>" class="btn btn-default">
									<i class="fa fa-history"></i>&nbsp;&nbsp;&nbsp;Kembali
								</a>
							</div>
							<hr>
							<div class="form-group">
								<label class="col-md-3 control-label">Kode Barang *</label>
								<div class="col-md-5">
									<input type="number" class="form-control" name="kode_barang" value="<?=@$data->kode_barang?>" autofocus="" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Nama Produk *</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="nama" value="<?=@$data->nama?>" autofocus="" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Harga Pokok (Awal) *</label>
								<div class="col-md-6">
									<input type="number" class="form-control" name="harga_awal" value="<?=@$data->harga_awal?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Harga Jual *</label>
								<div class="col-md-6">
									<input type="number" class="form-control" name="harga_jual" value="<?=@$data->harga_jual?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Kuantitas</label>
								<div class="col-md-8">
									<input type="number" class="form-control" name="kuantitas" value="<?=@$data->kuantitas?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Sekolah *</label>
								<div class="col-md-8">
									<?=form_dropdown('sekolah_id', $opt_sekolah, @$data->sekolah_id, 'class="form-control" onchange="get_kelas(this.value)"')?>
								</div>
							</div>
							<hr/>
							<div class="form-group">
								<div class="col-md-12 text-right">
									<button type="submit" class="btn btn-default">
										<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
									</button>
									<a href="<?=site_url('produk_kantin')?>" class="btn btn-default">
										<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>