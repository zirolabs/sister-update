<div class="box box-primary">
    <div class="box-header with-border">
    	<h3 class="box-title">
            <i class="fa fa-bullseye"></i>&nbsp;&nbsp;&nbsp;Monitoring Siswa
        </h3>
    </div>
    <div class="box-body">
        <form method="GET" action="<?=site_url('monitoring')?>">
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
            </form>
        </div>
        <hr/>
        <div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
                        <th class="col-md-2">Sekolah</th>
                        <th class="col-md-2">Kelas</th>
                        <th class="col-md-2">NIS</th>
						<th class="">Nama</th>
                        <th class="col-md-2">No. Handphone</th>
                        <th class="text-center"></th>
					</tr>
				</thead>
				<tbody>
					<?php if(!empty($data)){ ?>
						<?php foreach($data as $key => $c): ?>
							<tr>
                                <td><?=$c->sekolah?></td>
                                <td><?=$c->kelas?></td>
                                <td><?=$c->nis?></td>
                                <td><?=$c->nama?></td>
                                <td><?=$c->no_hp?></td>
                                <td class="text-center">
                                    <?php if(!empty($c->lokasi_latitude) && !empty($c->lokasi_longitude)){ ?>
                                        <a href="<?=site_url('monitoring/detail/' . $c->user_id)?>" class="btn btn-primary btn-xs" title="Perbaharui / Update">
                                            <i class="fa fa-map-marker"></i> Lihat Lokasi
                                        </a>
                                    <?php } ?>
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

<script type="text/javascript">
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