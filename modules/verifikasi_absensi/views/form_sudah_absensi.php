<div class="row">
	<div class="col-md-10 col-md-offset-1">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Perbaharui Absensi
                </h3>
            </div>
            <div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('verifikasi_absensi/index?jenis=&tanggal=' . $tanggal. '&kelas=' . $kelas . '&sekolah=' . $sekolah)?>" class="btn btn-default">
							<i class="fa fa-history"></i> Kembali
						</a>
					</div>
				</div>
				<hr/>
				<form class="form-horizontal" method="POST" action="<?=site_url('verifikasi_absensi/submit_sudah_absen/' . $id . '?tanggal=' . $tanggal. '&kelas=' . $kelas . '&sekolah=' . $sekolah)?>" enctype="multipart/form-data" autocomplete="off">
					<table class="table table-striped">
						<tbody>
							<tr>
								<td class="col-md-2">NIS</td>
								<td><?=@$data->nis?></td>
							</tr>
							<tr>
								<td class="col-md-2">Nama</td>
								<td><?=@$data->nama?></td>
							</tr>
							<tr>
								<td class="col-md-2">Kelas</td>
								<td><?=@$data->nama_kelas?></td>
							</tr>
							<tr>
								<td class="col-md-2">Tanggal</td>
								<td><?=format_tanggal_indonesia($data->waktu, true)?></td>
							</tr>
						</tbody>
					</table>
					<hr/>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
						        <label class="col-md-4 control-label">Status *</label>
						        <div class="col-md-6">
						        	<?=form_dropdown('status', $opt_status, @$data->status, 'class="form-control" onchange="get_jam(this.value)"')?>
						        </div>
						    </div>
						</div>
						<div class="col-md-6">
							<div class="form-group" id="waktu_masuk_area" style="display: none;">
						        <label class="col-md-4 control-label">Jam Masuk *</label>
						        <div class="col-md-6">
						        	<div class="bootstrap-timepicker">
										<div class="input-group">
											<input type="text" class="form-control timepicker" name="waktu_masuk" value="<?=empty($data->waktu) ? date('H:i') : date('H:i', strtotime($data->waktu))?>">
											<div class="input-group-addon">
												<i class="fa fa-clock-o"></i>
											</div>
										</div>				        	
									</div>
						        </div>
						    </div>
						</div>						
					</div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Keterangan</label>
				        <div class="col-md-9">
				        	<textarea class="form-control" name="keterangan" rows="4"><?=@$data->keterangan?></textarea>
				        </div>
				    </div>

                    <hr/>
				    <div class="form-group">
				        <div class="col-md-12 text-right">
				            <button type="submit" class="btn btn-primary">
				            	<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
				            </button>
				            <a href="<?=site_url('verifikasi_absensi/index?jenis=&tanggal=' . $tanggal. '&kelas=' . $kelas . '&sekolah=' . $sekolah)?>" class="btn btn-default">
				            	<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
				            </a>
				        </div>
				    </div>
				</form>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('form_js'); ?>