<div class="row">
	<div class="col-md-12">
		<div class="box box-default">
            <div class="box-header with-border">
            	<h3 class="box-title">
                    <i class="fa fa-users"></i>&nbsp;&nbsp;&nbsp;Manajemen Administrator
                </h3>
            </div>
            <div class="box-body">
            	<div class="row">
                    <div class="col-md-9">
	                    <a href="<?=site_url('manajemen_user/form')?>" class="btn btn-default">
	                        <i class="fa fa-plus hidden-xs"></i> Tambah
	                    </a>
                    </div>
                    <hr class="hidden-lg hidden-md">
            		<div class="col-md-3">
		           		<form method="GET" action="<?=site_url('manajemen_user')?>">
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
								<th class=""></th>
								<th class="">Email</th>
								<th class="text-center">Status</th>
								<th class="text-center">Terakhir Login</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($data as $key => $c): ?>
								<tr>
									<td class="text-center">
										<a href="<?=site_url('manajemen_user/form/' . $c->user_id)?>" class="btn btn-default btn-xs" title="Perbaharui / Update">
											<i class="fa fa-edit"></i>
										</a>
										<?php if($c->user_id != $login_uid){ ?>
											<a onclick="confirm_hapus('<?=$c->user_id?>')" class="btn btn-default btn-xs" title="Hapus">
												<i class="fa fa-trash"></i>
											</a>
										<?php } ?>
									</td>
									<td>
		                                <img src="<?=default_foto_user($c->foto)?>" class="thumbnails" width="50px">
									</td>
									<td><?=$c->email?></td>
									<td class="text-center">
										<a href="<?=site_url('manajemen_user/form/' . $c->user_id)?>" class="btn btn-xs blue">
											<?=ucfirst($c->status)?>
										</a>
									</td>
									<td class="text-center"><?=format_tanggal($c->terakhir_login, true)?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<?=$pagination?>
            </div>
        </div>
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
            	<a href="<?=site_url('manajemen_user')?>" id="btn-yes" class="btn btn-default">
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
		$('#modal-hapus #btn-yes').attr({'href' : '<?=site_url('manajemen_user/hapus')?>/' + id});
		$('#modal-hapus').modal();
	}
</script>
