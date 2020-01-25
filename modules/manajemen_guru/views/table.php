<div class="box box-primary">
    <div class="box-header with-border">
    	<h3 class="box-title">
            <i class="fa fa-users"></i>&nbsp;&nbsp;&nbsp;Manajemen Guru
        </h3>
    </div>
    <div class="box-body">
        <form method="GET" action="<?=site_url('manajemen_guru')?>">
        	<div class="row">
                <div class="col-md-1">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="input-group">
                            <a href="<?=site_url('manajemen_guru/form')?>" class="btn btn-default btn-block">
                                <i class="fa fa-plus"></i> Tambah
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="input-group">
                            <a href="<?=site_url('manajemen_guru/import')?>" class="btn btn-success btn-block">
                                <i class="fa fa-file-excel-o"></i> Import
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-md-offset-4">
                    <div class="form-group">
                        <label>Sekolah</label>
                        <?=form_dropdown('sekolah', $opt_sekolah, $sekolah, 'class="form-control"')?>
                    </div>                    
                </div>
        		<div class="col-md-3">
					<div class="form-group">
                        <label>&nbsp;</label>
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
                        <th class="col-md-2">Sekolah</th>
                        <th class="col-md-2">NIP</th>
						<th class="">Nama</th>
                        <th class="col-md-2">Jabatan</th>
                        <th class="col-md-2">Wali Kelas</th>
					</tr>
				</thead>
				<tbody>
					<?php if(!empty($data)){ ?>
						<?php foreach($data as $key => $c): ?>
							<tr>
								<td class="text-center">
									<a href="<?=site_url('manajemen_guru/form/' . $c->user_id)?>" class="btn btn-default btn-xs" title="Perbaharui / Update">
										<i class="fa fa-edit"></i>
									</a>
									<a onclick="confirm_hapus('<?=$c->user_id?>')" class="btn btn-default btn-xs" title="Hapus">
										<i class="fa fa-trash"></i>
									</a>
								</td>
                                <td><?=$c->sekolah?></td>
                                <td><?=$c->nip?></td>
                                <td><?=$c->nama?></td>
                                <td><?=$c->jabatan?></td>
                                <td>
                                    <?  $kelas = $this->pengaturan_kelas_model->get_data_guru($c->user_id);
                                        if(!empty($kelas))
                                        echo $kelas->kelas;
                                    ?>
                                </td>
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
            	<a href="<?=site_url('manajemen_guru')?>" id="btn-yes" class="btn btn-default">
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
		$('#modal-hapus #btn-yes').attr({'href' : '<?=site_url('manajemen_guru/hapus')?>/' + id});
		$('#modal-hapus').modal();
	}
</script> 
