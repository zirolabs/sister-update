<div class="box box-primary">
    <div class="box-header with-border">
    	<h3 class="box-title">
            <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Pengaturan Jam Sesi 
        </h3>
    </div>
    <div class="box-body">
        <form method="GET" action="<?=site_url('pengaturan_jam_sesi')?>">
        	<div class="row">
                <div class="col-md-6">
                    <label>&nbsp;</label>
                    <div class="input-group">
                        <a href="<?=site_url('pengaturan_jam_sesi/form')?>" class="btn btn-default">
                            <i class="fa fa-plus hidden-xs"></i> Tambah
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Sekolah</label>
                        <?=form_dropdown('sekolah', $opt_sekolah, $sekolah, 'class="form-control"')?>
                    </div>                    
                </div>
        		<div class="col-md-3">
					<div class="form-group">
                        <label>Sekolah</label>
                        <div class="input-group">
                            <div class="input-group-control">
	                            <input type="text" class="form-control input" placeholder="Pencarian.." name="q" value="<?=$keyword?>">
                            </div>
                            <span class="input-group-btn btn-right">
                                <button class="btn btn-default" type="submit">Cari !</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <hr/>
        <div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-1 text-center"></th>
                        <th class="col-md-3">Sekolah</th>
                        <th class="col-md-2">Sesi</th>
                        <th class="col-md-2 text-center">Masuk</th>
						<th class="col-md-2 text-center">Pulang</th>
                        <th class="col-md-2 text-center">Toleransi Telat</th>
					</tr>
				</thead>
				<tbody>
					<?php if(!empty($data)){ ?>
						<?php foreach($data as $key => $c): ?>
							<tr>
								<td class="text-center">
									<a href="<?=site_url('pengaturan_jam_sesi/form/' . $c->jam_id)?>" class="btn btn-default btn-xs" title="Perbaharui / Update">
										<i class="fa fa-edit"></i>
									</a>
									<a onclick="confirm_hapus('<?=$c->jam_id?>')" class="btn btn-default btn-xs" title="Hapus">
										<i class="fa fa-trash"></i>
									</a>
								</td>
                                <td><?=$c->nama_sekolah?></td>
                                <td><?=$c->nama_sesi?></td>
                                <td class="text-center"><?=$c->masuk?></td>
                                <td class="text-center"><?=$c->pulang?></td>
                                <td class="text-center"><?=$c->toleransi_telat?></td>
							</tr>
						<?php endforeach; ?>
					<?php } else { ?>
						<tr>
							<td colspan="5">Tidak ada data.</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<?=$pagination?>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-hapus">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                	<span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                	<i class="fa fa-info-circle"></i>&nbsp;&nbsp;Hapus Data
                </h4>
            </div>
            <div class="modal-body">
            	<h4>Apakah Anda yakin ? </h4>
            </div>
            <div class="modal-footer">
            	<a href="<?=site_url('pengaturan_jam_sesi')?>" id="btn-yes" class="btn btn-default">
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
	function confirm_hapus(id)
	{
		$('#modal-hapus #btn-yes').attr({'href' : '<?=site_url('pengaturan_jam_sesi/hapus')?>/' + id});
		$('#modal-hapus').modal();
	}
</script>
