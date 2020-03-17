<div class="box box-primary">
    <div class="box-header with-border">
    	<h3 class="box-title">
            <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Pengaturan Jadwal Pelajaran 
        </h3>
    </div>
    <div class="box-body">
        <form method="GET" action="<?=site_url('pengaturan_jadwal_pelajaran')?>">
        	<div class="row">
                <div class="col-md-3">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Sekolah</label>
                        <?=form_dropdown('sekolah', $opt_sekolah, $sekolah, 'class="form-control" onchange="get_kelas()"')?>
                    </div>                    
                </div>
                <div class="col-md-3">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Kelas</label>
                        <div id="area_kelas"></div>
                    </div>                    
                </div>
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Hari</label>
                        <?=form_dropdown('hari', $opt_hari, $hari, 'class="form-control"')?>
                    </div>                    
                </div>
                <div class="col-md-1">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>&nbsp;</label>
                        <div class="input-group">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fa fa-check"></i>&nbsp;&nbsp;Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <hr/>
        <?php if(empty($sekolah) || empty($kelas)){ ?>
            <?=info_msg('Pilih sekolah dan kelas terlebih dahulu.')?>
        <?php } else { ?>
            <div class="row">
                <div class="col-md-6">
                    <a href="<?=site_url('pengaturan_jadwal_pelajaran/form?' . $url_param)?>" class="btn btn-primary" style="margin-bottom: 5px;">
                        <i class="fa fa-plus"></i> Tambah
                    </a>
                    <a href="<?=site_url('pengaturan_jadwal_pelajaran/import?' . $url_param)?>" class="btn btn-success" style="margin-bottom: 5px;">
                        <i class="fa fa-file-excel-o"></i> Import Excel
                    </a>
                </div>
            </div>
            <?php if(empty($data)){ echo info_msg('Tidak ada data.'); } else { ?>
                <div class="table-responsive">
        			<table class="table table-striped">
        				<thead>
        					<tr>
        						<th class="col-md-1 text-center"></th>
                                <th class="col-md-2 text-center">Jam Mulai</th>
                                <th class="col-md-2 text-center">Jam Selesai</th>
                                <th>Mata Pelajaran</th>
                                <?php if(in_array($login_level, array('guru'))){ ?>
                                <th>Kelas</th>
                                <?php }else{ ?>
                                <th>Guru</th>
                                <?php } ?>
        					</tr>
        				</thead>
        				<tbody>
    						<?php foreach($data as $key => $c): ?>
    							<tr>
    								<td class="text-center">
    									<a href="<?=site_url('pengaturan_jadwal_pelajaran/form/' . $c->jadwal_id . '?' . $url_param)?>" class="btn btn-default btn-xs" title="Perbaharui / Update">
    										<i class="fa fa-edit"></i>
    									</a>
    									<a onclick="confirm_hapus('<?=$c->jadwal_id?>')" class="btn btn-default btn-xs" title="Hapus">
    										<i class="fa fa-trash"></i>
    									</a>
    								</td>
                                    <td class="text-center"><?=$c->jam_mulai?></td>
                                    <td class="text-center"><?=$c->jam_akhir?></td>
                                    <td><?=$c->nama_mata_pelajaran?></td>
                                    <?php if(in_array($login_level, array('guru'))){ ?>
                                    <td><?=$c->kelas?></td>
                                    <?php }else{ ?>
                                    <td><?=$c->nip_guru?> - <?=$c->nama_guru?></td>
                                    <?php } ?>
                                    
    							</tr>
    						<?php endforeach; ?>
        				</tbody>
        			</table>
        		</div>
            <?php } ?>
        <?php } ?>
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
            	<a href="<?=site_url('pengaturan_jadwal_pelajaran')?>" id="btn-yes" class="btn btn-default">
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
  function get_kelas()
    {
        $.ajax({
            url     : "<?=site_url('pengaturan_jadwal_pelajaran/ajax_kelas?selected=' . @$kelas . '&sekolah_id=')?>" + $('select[name="sekolah"]').val(),
            method  : 'GET',
            success : function(result){
                $('#area_kelas').html(result);
            }

        });
    }    

    $(document).ready(function(){
        get_kelas();
    }); 

	function confirm_hapus(id)
	{
		$('#modal-hapus #btn-yes').attr({'href' : '<?=site_url('pengaturan_jadwal_pelajaran/hapus')?>/' + id + '?<?=$url_param?>'});
		$('#modal-hapus').modal();
	}
</script>
