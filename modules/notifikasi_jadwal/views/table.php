<div class="box box-primary">
    <div class="box-header with-border">
    	<h3 class="box-title">
            <i class="fa fa-calendar"></i>&nbsp;&nbsp;&nbsp;Notifikasi Terjadwal
        </h3>
    </div>
    <div class="box-body">
        <form method="GET" action="<?=site_url('notifikasi_jadwal')?>">
        	<div class="row">
                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <div class="form-group">
                        <a href="<?=site_url('notifikasi_jadwal/form')?>" class="btn btn-primary">
                            <i class="fa fa-plus hidden-xs"></i> Notifikasi Baru
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Sekolah</label>
                        <?=form_dropdown('sekolah', $opt_sekolah, $sekolah, 'class="form-control" onchange="get_kelas()"')?>
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
           		</form>
            </div>
        </div>
        <hr/>
        <div class="table-responsive">
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th class="col-md-1 text-center"></th>
                        <th class="col-md-2">Sekolah</th>
                        <th>Isi</th>
                        <th class="col-md-2">Kirim Setiap</th>
                        <th class="col-md-1 text-center">Jam Kirim</th>
                        <th class="col-md-1">Penerima</th>
                        <th class="col-md-1">Kelas</th>
					</tr>
				</thead>
				<tbody>
					<?php if(!empty($data)){ ?>
						<?php foreach($data as $key => $c): ?>
							<tr>
								<td class="text-center">
									<a href="<?=site_url('notifikasi_jadwal/form/' . $c->notifikasi_id)?>" class="btn btn-default btn-xs" title="Perbaharui / Update">
										<i class="fa fa-edit"></i>
									</a>
									<a onclick="confirm_hapus('<?=$c->notifikasi_id?>')" class="btn btn-default btn-xs" title="Hapus">
										<i class="fa fa-trash"></i>
									</a>
								</td>
                                <td><?=$c->nama_sekolah?></td>
                                <td>
                                    Judul : <b><?=$c->judul?></b><br/>
                                    <?=nl2br($c->isi)?>
                                </td>
                                <td><?=$c->hari?></td>
								<td class="text-center"><?=$c->waktu?></td>
                                <td>
                                    Siswa : <i class="fa fa-<?=$c->target_siswa == 'N' ? 'times' : 'check'?>"></i><br/>
                                    Wali Siswa : <i class="fa fa-<?=$c->target_wali == 'N' ? 'times' : 'check'?>"></i><br/>
                                    <?php if($login_level == 'administrator' || $login_level == 'operator sekolah' || $login_level == 'kepala sekolah'){ ?>
                                        Wali Kelas : <i class="fa fa-<?=$c->target_wali_kelas == 'N' ? 'times' : 'check'?>"></i><br/>
                                        Guru : <i class="fa fa-<?=$c->target_guru == 'N' ? 'times' : 'check'?>"></i><br/>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php foreach ($c->kelas as $key => $x) : ?>
                                        <?=$x->nama?> <i class="fa fa-check"></i><br/>
                                    <?php endforeach; ?>
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
            	<a href="<?=site_url('notifikasi_jadwal')?>" id="btn-yes" class="btn btn-default">
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
		$('#modal-hapus #btn-yes').attr({'href' : '<?=site_url('notifikasi_jadwal/hapus')?>/' + id});
		$('#modal-hapus').modal();
	}

    function get_kelas()
    {
        $.ajax({
            url     : "<?=site_url('notifikasi_jadwal/ajax_kelas?selected=' . @$kelas . '&sekolah_id=')?>" + $('select[name="sekolah"]').val(),
            method  : 'GET',
            success : function(result){
                $('#area_kelas').html(result);
            }

        });
    }    

    $(document).ready(function(){
        get_kelas();
    });
</script>
