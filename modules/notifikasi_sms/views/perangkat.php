<div class="table-responsive">
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
                <th>Nama</th>
                <th class="col-md-1 text-center">Pending</th>
                <th class="col-md-1 text-center">Proses</th>
                <th class="col-md-1 text-center">Terkirim</th>
                <th class="col-md-1 text-center">Gagal</th>
                <th class="col-md-2">Maksium SMS / Jam</th>
                <th class="col-md-2">Test SMS</th>
                <th class="col-md-1"></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($data)){ ?>
				<?php foreach($data as $key => $c): ?>
					<tr>
                        <td style="vertical-align: middle;">[<?=$c->device_id?>] <?=$c->nama?></td>
                        <td style="vertical-align: middle;" class="text-center">
                            <span class="text-yellow"><?=$c->total_pending?></span></td>
                        <td style="vertical-align: middle;" class="text-center">
                            <span class="text-blue"><?=$c->total_proses?></span></td>
                        </td>
                        <td style="vertical-align: middle;" class="text-center">
                            <span class="text-green"><?=$c->total_terkirim?></span></td>
                        </td>
                        <td style="vertical-align: middle;" class="text-center">
                            <span class="text-red"><?=$c->total_gagal?></span></td>
                        </td>
                        <td style="vertical-align: middle;" class="text-center">
                            <form action="<?=site_url('notifikasi_sms/maksimum_sms/' . $c->device_id)?>" method="POST">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <div class="input-group">
                                        <div class="input-group-control">
                                            <input type="text" class="form-control input input-sm" placeholder="Total SMS" name="total" value="<?=$c->maks_per_jam?>">
                                        </div>
                                        <span class="input-group-btn btn-right">
                                            <button class="btn btn-primary btn-sm" type="submit">
                                                <i class="fa fa-check"></i>                                            
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </td>
                        <td style="vertical-align: middle;" class="text-center">
                            <form action="<?=site_url('notifikasi_sms/perangkat_test/' . $c->device_id)?>" method="POST">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <div class="input-group">
                                        <div class="input-group-control">
                                            <input type="text" class="form-control input input-sm" placeholder="No Handphone" name="telp">
                                        </div>
                                        <span class="input-group-btn btn-right">
                                            <button class="btn btn-primary btn-sm" type="submit">
                                                <i class="fa fa-send"></i>                                            
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </td>
                        <td style="vertical-align: middle;" class="text-center">
                            <?php if($c->aktif == 'Y'){ ?>
                                <a onclick="confirm_nonaktif_perangkat('<?=$c->device_id?>')" class="btn btn-danger btn-xs" title="Hapus">
                                    <i class="fa fa-times"></i> Nonaktifkan Perangkat
                                </a>
                            <?php } else { ?>
                                <a onclick="confirm_aktif_perangkat('<?=$c->device_id?>')" class="btn btn-success btn-xs" title="Hapus">
                                    <i class="fa fa-check"></i> Aktifkan Perangkat
                                </a>
                            <?php } ?>
                        </td>                        
					</tr>
				<?php endforeach; ?>
			<?php } else { ?>
				<tr>
					<td colspan="8">Tidak ada data.</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-aktif-perangkat">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    <i class="fa fa-info-circle"></i>&nbsp;&nbsp;Aktifkan Perangkat
                </h4>
            </div>
            <div class="modal-body">
                <h4>Apakah Anda yakin ? </h4>
            </div>
            <div class="modal-footer">
                <a href="<?=site_url('notifikasi_sms')?>" id="btn-yes" class="btn btn-default">
                    <i class="fa fa-check"></i> Ya, Saya Yakin
                </a>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-times"></i> Tidak
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-nonaktif-perangkat">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    <i class="fa fa-info-circle"></i>&nbsp;&nbsp;Nonaktifkan Perangkat
                </h4>
            </div>
            <div class="modal-body">
                <h4>Apakah Anda yakin ? </h4>
            </div>
            <div class="modal-footer">
                <a href="<?=site_url('notifikasi_sms')?>" id="btn-yes" class="btn btn-default">
                    <i class="fa fa-check"></i> Ya, Saya Yakin
                </a>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-times"></i> Tidak
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function confirm_aktif_perangkat(id)
    {
        $('#modal-aktif-perangkat #btn-yes').attr({'href' : '<?=site_url('notifikasi_sms/perangkat_aktif')?>/' + id});
        $('#modal-aktif-perangkat').modal();
    }

    function confirm_nonaktif_perangkat(id)
    {
        $('#modal-nonaktif-perangkat #btn-yes').attr({'href' : '<?=site_url('notifikasi_sms/perangkat_nonaktif')?>/' + id});
        $('#modal-nonaktif-perangkat').modal();
    }
</script>