<div class="row">
	<div class="col-md-10 col-md-offset-1">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Verifikasi Absensi Massal
                </h3>
            </div>
            <div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('verifikasi_absensi/index?jenis=belum_absen&tanggal=' . $tanggal. '&kelas=' . $kelas . '&sekolah=' . $sekolah)?>" class="btn btn-default">
							<i class="fa fa-history"></i> Kembali
						</a>
					</div>
				</div>
				<hr/>
				<form class="form-horizontal" method="POST" action="<?=site_url('verifikasi_absensi/submit_belum_absen_massal?tanggal=' . $tanggal. '&kelas=' . $kelas . '&sekolah=' . $sekolah)?>" enctype="multipart/form-data" autocomplete="off">
					<div class="row">
						<div class="col-md-12">
							<h3 class="text-center" style="margin: 0;">
								Tanggal : <?=format_tanggal_indonesia($tanggal)?>									
							</h3>
							<hr/>
						</div>
					</div>
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>NIS</th>
								<th>Nama</th>
								<th>Kelas</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($info_siswa as $key => $c): ?>
								<tr>
									<td><?=$c->nis?></td>
									<td><?=$c->nama?></td>
									<td>
										<?=$c->nama_kelas?>
										<input type="hidden" value="<?=$c->nis?>" name="nis[<?=$c->user_id?>]">
										<input type="hidden" value="<?=$c->nama?>" name="nama[<?=$c->user_id?>]">													
										<input type="hidden" value="<?=$c->kelas_id?>" name="kelas[<?=$c->user_id?>]">																										
										<input type="hidden" value="<?=$c->sekolah_id?>" name="sekolah[<?=$c->user_id?>]">																																					
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<hr/>
					<div class="row">
						<div class="col-md-8 col-md-offset-4">
							<div class="form-group">
						        <label class="col-md-2 control-label">Status *</label>
						        <div class="col-md-4">
						        	<?=form_dropdown('status', $opt_status, '', 'class="form-control" onchange="get_jam(this.value)"')?>
						        </div>
								<div id="waktu_masuk_area" style="display: none;">
							        <label class="col-md-2 control-label">Jam Masuk *</label>
							        <div class="col-md-4">
							        	<div class="bootstrap-timepicker">
											<div class="input-group">
												<input type="text" class="form-control timepicker" name="waktu_masuk" value="<?=date('H:i')?>">
												<div class="input-group-addon">
													<i class="fa fa-clock-o"></i>
												</div>
											</div>				        	
										</div>
							        </div>
							    </div>
						    </div>
							<div class="form-group">
						        <label class="col-md-2 control-label">Keterangan</label>
						        <div class="col-md-10">
						        	<textarea class="form-control" name="keterangan" rows="3"></textarea>
						        </div>
						    </div>
						   	<div class="form-group">
						        <div class="col-md-12 text-right">
						            <button type="submit" class="btn btn-primary">
						            	<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
						            </button>
						            <a href="<?=site_url('verifikasi_absensi/index?jenis=belum_absen&tanggal=' . $tanggal. '&kelas=' . $kelas . '&sekolah=' . $sekolah)?>" class="btn btn-default">
						            	<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
						            </a>
						        </div>
						   	</div>
						</div>
					</div>
				</form>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('form_massal_js'); ?>