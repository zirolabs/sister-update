<div class="box box-primary">
    <div class="box-header with-border">
    	<h3 class="box-title">
            <i class="fa fa-credit-card"></i>&nbsp;&nbsp;&nbsp;Data Mutasi
        </h3>
    </div>
    <div class="box-body">
        <form method="GET" action="<?=site_url('keuangan_mutasi')?>">
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
            </div>
            <div class="row">
                <div class="col-md-3">
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
                <div class="col-md-3">
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
        		<div class="col-md-6">
					<div class="form-group">
                        <label>&nbsp;</label>
                        <div class="input-group">
                            <div class="input-group-control">
	                            <input type="text" class="form-control input" placeholder="Nama Siswa .." name="q" value="<?=$keyword?>">
                            </div>
                            <span class="input-group-btn btn-right">
                                <button class="btn btn-default" type="submit">Cari !</button>
                            </span>
                        </div>
                    </div>
           		</form>
            </div>
        </div>
        <hr style="margin: 5px 0;" />
        <div class="table-responsive">
            <?php if(empty($sekolah)){ echo info_msg('Pilih sekolah terlebih dahulu'); } else { ?>
    			<table class="table table-striped table-bordered">
    				<thead>
    					<tr>
    						<th class="col-md-1 text-center"></th>
                            <th class="col-md-2">Sekolah</th>
                            <th class="col-md-2">Kelas</th>
                            <th class="col-md-2">NIS</th>
                            <th>Nama</th>
    					</tr>
    				</thead>
    				<tbody>
    					<?php if(!empty($data)){ ?>
    						<?php foreach($data as $key => $c): ?>
    							<tr>
    								<td class="text-center">
    									<a href="<?=site_url('keuangan_mutasi/detail/' . $c->user_id . '?tanggal_awal=' . $tanggal_awal . '&tanggal_akhir=' . $tanggal_akhir)?>" class="btn btn-default btn-xs" title="Detail Mutasi" target="_blank">
    										<i class="fa fa-search"></i>
    									</a>
    								</td>
                                    <td><?=$c->sekolah?></td>
                                    <td><?=$c->kelas?></td>
                                    <td><?=$c->nis?></td>
    								<td><?=$c->nama_siswa?></td>
    							</tr>
    						<?php endforeach; ?>
    					<?php } else { ?>
    						<tr>
    							<td colspan="5">Tidak ada data.</td>
    						</tr>
    					<?php } ?>
    				</tbody>
    			</table>
            <?php } ?>
		</div>
		<?=@$pagination?>
    </div>
</div>

<link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte')?>/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte')?>/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css">
<script src="<?=base_url('vendor/almasaeed2010/adminlte')?>/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">

        $('.datepicker-input').datepicker({
            autoclose   : true,
            format      : 'yyyy-mm-dd'
        });    

    function get_kelas()
    {
        $.ajax({
            url     : "<?=site_url('keuangan_mutasi/ajax_kelas?selected=' . @$kelas . '&sekolah_id=')?>" + $('select[name="sekolah"]').val(),
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
