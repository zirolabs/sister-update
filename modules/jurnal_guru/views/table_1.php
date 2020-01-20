<div class="box box-primary">
    <div class="box-header with-border">
    	<h3 class="box-title">
            <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Jurnal Guru
        </h3>
    </div>
    <div class="box-body">
        <div class="col-md-12">
                <a href="<?=site_url('jurnal_guru')?>" class="btn btn-default">
                        <i class="fa fa-history"></i> Kembali
                </a>
        </div>
        <hr/>
        <div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<thead>
                                    <tr>
                                        <th class="col-md-2">Materi</th>
                                        <th class="col-md-2">Target</th>
                                        <th class="col-md-2">Hadir</th>
                                        <th class="col-md-2">Ijin</th>
                                        <th class="col-md-2">Aplha</th>
                                        <th class="col-md-2">Keterangan</th>
                                        <th class="col-md-2">hapus</th>
                                    </tr>
				</thead>
				<tbody>
				<?php if(!empty($data)){ ?>
				<?php foreach($data as $key => $c): ?>
				<tr>
                                <td><?=$c->materi?></td>
                                <td>
                                    <?=$c->target?>
                                </td>
                                <td>
                                    <?=$c->siswa_hadir?>
                                </td>
                                <td>
                                    <?=$c->siswa_ijin?>
                                </td>
                                <td>
                                    <?=$c->siswa_alpa?>
                                </td>
                                <td>
                                    <?=$c->keterangan?>
                                </td>
                                <td>  
                                    <?php if(!empty($c->id_jurnal)){ ?>
                                    <a href="<?=site_url('jurnal_guru/hapus/'. $c->id_jurnal)?>" class="btn btn-default btn-xs" title="hapus Jurnal">
    										<i class="fa fa-minus"></i>
    					<?php } ?>				</a>
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

    function get_kelas(id)
    {
        $.ajax({
            url     : "<?=site_url('monitoring/get_kelas?selected=' . @$kelas . '&sekolah_id=')?>" + id,
            method  : 'GET',
            success : function(result){
                $('#area_kelas').html(result);
            }

        });
    }

    get_kelas($('select[name="sekolah"]').val());
</script>
