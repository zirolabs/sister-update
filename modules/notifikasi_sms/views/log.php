<form method="GET" action="<?=site_url('notifikasi_sms/index')?>">
	<div class="row">
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
                <label>Status</label>
                <div id="area_kelas">
                    <?=form_dropdown('status', $opt_status, $status, 'class="form-control"')?>
                </div>
            </div>                    
        </div>
		<div class="col-md-3">
			<div class="form-group">
                <label>&nbsp;</label><br/>
                <button class="btn btn-default" type="submit">Cari !</button>
            </div>
        </div>
    </div>
</form>
<hr/>
<div class="table-responsive">
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
                <th class="col-md-1 text-center">Status</th>
                <th class="col-md-2">Waktu</th>
                <th class="col-md-2">Sekolah</th>
                <th class="col-md-1">Kelas</th>
                <th>Isi</th>
                <th class="col-md-3">Penerima</th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($data)){ ?>
				<?php foreach($data as $key => $c): ?>
					<tr>
                        <td class="text-center">
                            <span class="text-<?=$c->status == 'terkirim' ? 'green' : ($c->status == 'pending' ? 'yellow' : ($c->status == 'proses' ? 'blue' : 'red'))?>">
                                <b><?=format_ucwords($c->status)?></b>
                            </span>
                        </td>
                        <td><?=format_tanggal_indonesia($c->waktu, true)?></td>
                        <td><?=$c->sekolah?></td>
                        <td><?=$c->kelas?></td>                        
                        <td><?=$c->pesan?></td>
                        <td>
                            <?=$c->target?> | 
                            <small>
                                <?php if($c->target_user == 'wali'){ ?>
                                    <?php if(!empty($c->nama_ortu_bapak)){ ?>
                                        Bpk. <?=$c->nama_ortu_bapak?><br/>
                                    <?php } else if(!empty($c->nama_ortu_ibu)) { ?>
                                        Ibu <?=$c->nama_ortu_ibu?><br/>
                                    <?php } ?>
                                    Wali <?=$c->nama_siswa?> (<?=$c->nis_siswa?>)
                                <?php } else { ?>
                                    <?=$c->nama_siswa?> (<?=$c->nis_siswa?>)
                                <?php } ?>
                            </small>
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
<script type="text/javascript">
    function confirm_hapus(id)
    {
        $('#modal-hapus #btn-yes').attr({'href' : '<?=site_url('pengumuman/hapus')?>/' + id});
        $('#modal-hapus').modal();
    }

    function get_kelas()
    {
        $.ajax({
            url     : "<?=site_url('pengumuman/ajax_kelas?selected=' . @$kelas . '&sekolah_id=')?>" + $('select[name="sekolah"]').val(),
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
