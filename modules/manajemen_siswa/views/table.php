<div class="box box-primary">
    <div class="box-header with-border">
    	<h3 class="box-title">
            <i class="fa fa-users"></i>&nbsp;&nbsp;&nbsp;Manajemen Siswa
        </h3>
    </div>
    <div class="box-body">
        <form method="GET" action="<?=site_url('manajemen_siswa')?>">
        	<div class="row">
                <div class="col-md-1">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="input-group">
                            <a href="<?=site_url('manajemen_siswa/form')?>" class="btn btn-default">
                                <i class="fa fa-plus hidden-xs"></i> Tambah
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="input-group">
                            <a href="<?=site_url('manajemen_siswa/import')?>" class="btn btn-success btn-block">
                                <i class="fa fa-file-excel-o"></i> Import
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-md-offset-1">
                    <div class="form-group">
                        <label>Sekolah</label>
                        <?=form_dropdown('sekolah', $opt_sekolah, $sekolah, 'class="form-control" onchange="get_kelas(this.value)"')?>
                    </div>                    
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Kelas</label>
                        <div id="area_kelas">
                            <?=form_dropdown('kelas', array('' => 'Semua Kelas'), '', 'class="form-control"')?>
                        </div>
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
            </form>
        </div>
        <hr/>
        <div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-1 text-center"></th>
						<th class="col-md-1 text-center"></th>
                        <th class="col-md-2">Sekolah</th>
                        <th class="col-md-2">Kelas</th>
                        <th class="col-md-2">NIS</th>
						<th class="">Nama</th>
                        <th class="col-md-2">No. Handphone</th>
					</tr>
				</thead>
                                <?php echo form_open('manajemen_siswa/remove'); ?>
				<tbody><input type="submit" value="Delete All" onclick="return confirm('anda yakin')">
					<?php if(!empty($data)){ ?>
						<?php foreach($data as $key => $c): ?>
							<tr><td><input type="checkbox" name="id[]" value="<?php echo $c->user_id ?>"></td>
								<td class="text-center">
									<a href="<?=site_url('manajemen_siswa/form/' . $c->user_id)?>" class="btn btn-default btn-xs" title="Perbaharui / Update">
										<i class="fa fa-edit"></i>
									</a>
									<a onclick="confirm_hapus('<?=$c->user_id?>')" class="btn btn-default btn-xs" title="Hapus">
										<i class="fa fa-trash"></i>
									</a>
								</td>
                                <td><?=$c->sekolah?></td>
                                <td><?=$c->kelas?></td>
                                <td><?=$c->nis?></td>
                                <td><?=$c->nama?></td>
                                <td><?=$c->no_hp?></td>
							</tr>
						<?php endforeach; ?>
					<?php } else { ?>
						<tr>
							<td colspan="5">Tidak ada data.</td>
						</tr>
					<?php } ?>
				</tbody>
                                <?php echo form_close()?>
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
            	<a href="<?=site_url('manajemen_siswa')?>" id="btn-yes" class="btn btn-default">
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
		$('#modal-hapus #btn-yes').attr({'href' : '<?=site_url('manajemen_siswa/hapus')?>/' + id});
		$('#modal-hapus').modal();
	}
    function get_kelas(id)
    {
        $.ajax({
            url     : "<?=site_url('manajemen_siswa/get_kelas?selected=' . @$kelas . '&sekolah_id=')?>" + id,
            method  : 'GET',
            success : function(result){
                $('#area_kelas').html(result);
            }

        });
    }

    get_kelas($('select[name="sekolah"]').val());    
</script>