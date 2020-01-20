<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">
			<i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Kotak Pesan
		</h3>
    </div>
    <div class="box-body no-padding">
		<div class="mailbox-controls">
            <div class="btn-group">
				<a class="btn btn-default btn-sm" href="<?=site_url('pesan_langsung')?>">
					<i class="fa fa-edit"></i> Buat Pesan Baru
				</a>
				<a class="btn btn-default btn-sm" href="<?=site_url('pesan_broadcast')?>">
					<i class="fa fa-edit"></i> Buat Pesan Broadcast
				</a>
            </div>
        </div>
		<div class="table-responsive mailbox-messages">
			<table class="table table-hover table-striped">
				<tbody>
					<?php if(!empty($data)){ ?>
						<?php foreach($data as $key => $c): ?>
		                	<tr>
		                		<td class="col-md-1">
		                			<a href="<?=site_url('pesan_kotak/detail/' . $c->pesan_id)?>" class="btn btn-default btn-xs">
		                				<i class="fa fa-comments"></i> Riwayat Pesan
		                			</a>
		                		</td>
		                		<td class="col-md-1">
		                			<img src="<?=default_foto_user($c->user_id_1 == $login_uid ? $c->foto_user_2 : $c->foto_user_1)?>" class="img-circle img-responsive">
		                		</td>
		                    	<td class="col-md-2 mailbox-name">
		                    		<a href="<?=base_url('pesan_kotak/detail/' . $c->pesan_id)?>">
		                    			<?=($c->user_id_1 == $login_uid) ? $c->nama_user_2 : $c->nama_user_1?>
		                    		</a> 
									<?=($c->user_id_1 == $login_uid) ? '<br/>sebagai ' . format_ucwords($c->target) : ''?>
		                    	</td>
		                    	<td class="mailbox-subject">
		                    		<?=strlen($c->pesan_terakhir) > 150 ? substr($c->pesan_terakhir, 0, 150) . '...' : $c->pesan_terakhir?>
		                    	</td>
		                    	<td class="col-md-2 mailbox-date">
		                    		<?=waktu_berlalu($c->waktu_terakhir, true)?>	                    		
	                    		</td>
		                  	</tr>
		                  <?php endforeach?>
	                <?php } else { ?>
	                	<tr>
	                		<td colspan="4">Tidak ada data</td>
	                	</tr>
	                <?php } ?>
                </tbody>
            </table>
		</div>
	</div>
</div>
