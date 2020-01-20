<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">
            <i class="fa fa-print"></i>&nbsp;&nbsp;&nbsp;Laporan Pelanggaran Siswa
        </h3>
    </div>
	
    <div class="box-body">
        <form method="GET" action="<?=site_url('pelanggaran/laporan')?>">
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
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Tanggal Awal</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right datepicker-input" name="tanggal_awal" readonly value="<?=empty($tanggal_awal) ? date('Y-m-d') : $tanggal_awal?>">
                        </div>
                    </div>                    
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Tanggal Akhir</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right datepicker-input" name="tanggal_akhir" readonly value="<?=empty($tanggal_akhir) ? date('Y-m-d') : $tanggal_akhir?>">
                        </div>
                    </div>                    
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="input-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-check"></i>&nbsp;&nbsp;Filter
                            </button>
                        </div>
                    </div>                    
                </div>
            </div>
        </form>
    </div>

</div>
<div class="box box-primary">
    <div class="box-body">
        <h4 class="text-center" style="margin: 3px;">Laporan Pelanggaran Siswa</h4>
        <h4 class="text-center" style="margin: 3px;"><?=format_tanggal_indonesia($tanggal_awal)?> - <?=format_tanggal_indonesia($tanggal_akhir)?></h4>
        <?php if($sekolah_label != 'Semua Sekolah'){ ?>
            <h3 class="text-center" style="margin: 3px;"><?=$sekolah_label?></h3>
        <?php } ?>
        <?php if($kelas_label != 'Semua Kelas'){ ?>
            <h3 class="text-center" style="margin: 3px;">Kelas : <?=$kelas_label?></h3>
        <?php } ?>
        <hr/>
        <table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-1 text-center"></th>
                        <th class="text-center">No</th>
						<th class="">Nama Siswa</th>
						<th class="">Kelas</th>
						<th class="">Tanggal</th>
                        <th class="">Pelanggaran</th>
						<th class="">Point</th>
				   </tr>
				</thead>
				<tbody>
					<?php 
						if(!empty($data)){ 
					?>
						<?php 
							$no=1;
							foreach($data as $key => $c): 
						?>
							<tr>
								<td class="text-center">
									<a href="<?=site_url('pelanggaran/edit/' . $c->id_pelanggaran)?>" class="btn btn-default btn-xs" title="Perbaharui / Update">
										<i class="fa fa-edit"></i>
									</a>
									<a onclick="confirm_hapus('<?=$c->id_pelanggaran?>')" class="btn btn-default btn-xs" title="Hapus">
										<i class="fa fa-trash"></i>
									</a>	
								</td>
                                <td class="text-center"><?=$no?></td>
								<td><?=$c->nama?></td>
								<td><?=$c->kelas?></td>
								<td><?=$c->tanggal_pelanggaran?></td>
								<td><?=$c->deskripsi_pelanggaran?></td>
								<td><?=$c->point_pelanggaran?></td>
							</tr>
						<?php 
							$no++;
							endforeach; 
						?>
					<?php } else { ?>
						<tr>
							<td colspan="6">Tidak ada data.</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
        <?=$pagination?>
		<hr />
		<a target="_blank" href="<?=site_url('pelanggaran/cetak_laporan/'.$tanggal_awal.'/'.$tanggal_akhir)?>" class="btn btn-primary btn-block" title="Cetak PDF">
			<i class="fa fa-print"></i> Cetak Laporan
		</a>
    </div>
</div>


<link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte')?>/plugins/datepicker/datepicker3.css">
<script src="<?=base_url('vendor/almasaeed2010/adminlte')?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript">

    $('.datepicker-input').datepicker({
        autoclose   : true,
        format      : 'yyyy-mm-dd'
    });    

    function get_kelas(id)
    {
        $.ajax({
            url     : "<?=site_url('absensi_laporan_bulanan/get_kelas?selected=' . @$kelas . '&sekolah_id=')?>" + id,
            method  : 'GET',
            success : function(result){
                $('#area_kelas').html(result);
            }

        });
    }

    get_kelas($('select[name="sekolah"]').val());    
</script>