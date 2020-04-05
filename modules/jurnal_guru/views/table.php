<div class="box box-primary">
    <div class="box-header with-border">
    	<h3 class="box-title">
            <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Jurnal Guru
        </h3>
        <a href="<?=site_url('jurnal_guru/laporan')?>" class="btn btn-info" style="float: right">
            Laporan Jurnal
        </a>
    </div>
    <div class="box-body">
        <form method="GET" action="<?=site_url('jurnal_guru')?>">
        	<div class="row">
                <div class="col-md-3">
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
                <?php if(in_array($login_level,array('operator sekolah','administrator'))){ ?>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="input-group">
                           
                        </div>
                    </div>                    
                </div>
                <?php } ?>
            </div>
        </form>
        <hr/>
        <div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<thead>
                    <tr>
                        <th class="col-md-2">Hari</th>
                        <th class="col-md-2">Jam Mulai</th>
                        <th class="col-md-2">Jam Akhir</th>
                        <th class="col-md-2">Nama Pelajaran</th>
                        <th class="col-md-2">Kelas</th>
                        <th class="col-md-2">Lihat Jurnal</th>
                        <th class="col-md-2">Isi Jurnal</th>
                    </tr>
				</thead>
				<tbody>
				<?php if(!empty($data)){ ?>
				<?php foreach($data as $key => $c): ?>
				<tr>
                                <td><?=hari_indonesia($c->hari)?></td>
                                <td>
                                    <?=$c->jam_mulai?>
                                </td>
                                <td><?=$c->jam_akhir?></td>
                                <td>
                                    <?=$c->nama_mata_pelajaran?>
                                </td>
                                <td>
                                    <?=$c->kelas?>
                                </td>
                                <td>
                                    <a href="<?=site_url('jurnal_guru/lihat/'. $c->jadwal_id)?>" class="btn btn-default btn-xs" title="Lihat Jurnal">
    										<i class="fa  fa-search"></i>
    									</a>
                                </td>
                                <td>
                                    <a href="<?=site_url('jurnal_guru/form/'. $c->jadwal_id)?>" class="btn btn-default btn-xs" title="Tambah Jurnal">
    										<i class="fa fa-plus"></i>
    									</a>
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
            	<a href="<?=site_url('jurnal_guru')?>" id="btn-yes" class="btn btn-default">
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
		$('#modal-hapus #btn-yes').attr({'href' : '<?=site_url('jurnal_guru/hapus')?>/' + id});
		$('#modal-hapus').modal();
	}

    function get_kelas()
    {
        $.ajax({
            url     : "<?=site_url('jurnal_guru/ajax_kelas?selected=' . @$kelas . '&sekolah_id=')?>" + $('select[name="sekolah"]').val(),
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
