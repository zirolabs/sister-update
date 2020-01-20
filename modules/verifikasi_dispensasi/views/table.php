<div class="box box-primary">
    <div class="box-header with-border">
    	<h3 class="box-title">
            <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Data Dispensasi Absen
        </h3>
    </div>
    <div class="box-body">
        <form method="GET" action="<?=site_url('verifikasi_dispensasi')?>">
        	<div class="row">
                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <div class="form-group">
                        <a href="<?=site_url('verifikasi_dispensasi/form')?>" class="btn btn-primary">
                            <i class="fa fa-plus hidden-xs"></i> Dispensasi Baru
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
        <hr style="margin: 10px 0;" />
        <div class="table-responsive">
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th class="col-md-1"></th>
                        <th class="col-md-2">Sekolah</th>
                        <th class="col-md-1">Kelas</th>
                        <th class="col-md-1">NIS</th>
                        <th class="col-md-2">Nama</th>
                        <th class="col-md-1">Tgl Mulai</th>
                        <th class="col-md-1">Tgl Selesai</th>
                        <th>Keterangan</th>
					</tr>
				</thead>
				<tbody>
                    <?php if(!empty($data)){ ?>
                        <?php foreach($data as $key => $c): ?>
                            <tr>
                                <td class="text-center">
                                    <a href="<?=site_url('verifikasi_dispensasi/form/' . $c->dispensasi_id)?>" class="btn btn-default btn-xs" title="Perbaharui / Update">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a onclick="confirm_hapus('<?=$c->dispensasi_id?>')" class="btn btn-default btn-xs" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                                <td><?=$c->sekolah?></td>
                                <td><?=$c->kelas?></td>
                                <td><?=$c->nis?></td>
                                <td><?=$c->nama_siswa?></td>
                                <td><?=format_tanggal_indonesia($c->tgl_mulai)?></td>
                                <td><?=format_tanggal_indonesia($c->tgl_selesai)?></td>
                                <td><?=$c->keterangan?></td>
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
            	<a href="<?=site_url('pengumuman')?>" id="btn-yes" class="btn btn-default">
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
		$('#modal-hapus #btn-yes').attr({'href' : '<?=site_url('verifikasi_dispensasi/hapus')?>/' + id});
		$('#modal-hapus').modal();
	}

    function get_kelas()
    {
        $.ajax({
            url     : "<?=site_url('verifikasi_dispensasi/ajax_kelas?selected=' . @$kelas . '&sekolah_id=')?>" + $('select[name="sekolah"]').val(),
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
