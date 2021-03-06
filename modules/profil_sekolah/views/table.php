<div class="box box-primary">
    <div class="box-header with-border">
    	<h3 class="box-title">
            <i class="fa fa-university"></i>&nbsp;&nbsp;&nbsp;Manajemen Sekolah
        </h3>
    </div>
    <div class="box-body">
    	<div class="row">
            <div class="col-md-9">
                <a href="<?=site_url('profil_sekolah/form')?>" class="btn btn-default">
                    <i class="fa fa-plus hidden-xs"></i> Tambah
                </a>
            </div>
    		<div class="col-md-3">
           		<form method="GET" action="<?=site_url('profil_sekolah')?>">
					<div class="form-group" style="margin-bottom: 0px;">
                        <div class="input-group">
                            <div class="input-group-control">
	                            <input type="text" class="form-control input" placeholder="Pencarian.." name="q" value="<?=$keyword?>">
                            </div>
                            <span class="input-group-btn btn-right">
                                <button class="btn btn-default" type="submit">Cari !</button>
                            </span>
                        </div>
                    </div>
           		</form>
            </div>
        </div>
        <hr/>
        <div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-1 text-center"></th>
                        <th class="text-center">Kode</th>
                        <th class="">NISN</th>
						<th class="">Nama</th>
                        <th class="">Kontak</th>
                        <th class="">Kepala Sekolah</th>
					</tr>
				</thead>
				<tbody>
					<?php if(!empty($data)){ ?>
						<?php foreach($data as $key => $c): ?>
							<tr>
								<td class="text-center">
									<a href="<?=site_url('profil_sekolah/form/' . $c->sekolah_id)?>" class="btn btn-default btn-xs" title="Perbaharui / Update">
										<i class="fa fa-edit"></i>
									</a>
									<a onclick="confirm_hapus('<?=$c->sekolah_id?>')" class="btn btn-default btn-xs" title="Hapus">
										<i class="fa fa-trash"></i>
									</a>
								</td>
                                <td class="text-center">#<?=$c->sekolah_id?></td>
                                <td><?=$c->nisn?></td>
								<td><?=$c->nama?></td>
                                <td>
                                    Email : <?=$c->email?><br/>
                                    Telepon : <?=$c->telepon?><br/>
                                    Alamat : <?=$c->alamat?>
                                </td>
                                <td>
                                    Nama : <?=$c->kepsek_nama?><br/>
                                    NIP : <?=$c->kepsek_nip?><br/>
                                    Email : <?=$c->kepsek_email?>
                                </td>
							</tr>
						<?php endforeach; ?>
					<?php } else { ?>
						<tr>
							<td colspan="6">Tidak ada data.</td>
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
            	<a href="<?=site_url('profil_sekolah')?>" id="btn-yes" class="btn btn-default">
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
		$('#modal-hapus #btn-yes').attr({'href' : '<?=site_url('profil_sekolah/hapus')?>/' + id});
		$('#modal-hapus').modal();
	}
</script>
