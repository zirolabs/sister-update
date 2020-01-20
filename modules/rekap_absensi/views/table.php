<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">
            <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Rekap Absensi
        </h3>
    </div>
    <div class="box-body">
        <form method="GET" action="<?=site_url('rekap_absensi/index')?>">
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
                        <label>Bulan</label>
                        <?=form_dropdown('bulan', bulan_indonesia(), $bulan, 'class="form-control"')?>
                    </div>                    
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Tahun</label>
                        <?=form_dropdown('tahun', opt_tahun(2015), $tahun, 'class="form-control"')?>
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
        <h4 class="text-center" style="margin: 3px;">Laporan Rekap Absensi</h4>
        <h4 class="text-center" style="margin: 3px;">Bulan <?=bulan_indonesia($bulan)?>, <?=$tahun?></h4>
        <?php if($sekolah_label != 'Semua Sekolah'){ ?>
            <h3 class="text-center" style="margin: 3px;"><?=$sekolah_label?></h3>
        <?php } ?>
        <?php if($kelas_label != 'Semua Kelas'){ ?>
            <h3 class="text-center" style="margin: 3px;">Kelas : <?=$kelas_label?></h3>
        <?php } ?>
        <hr/>
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="col-md-2">Sekolah</th>
                    <th class="col-md-4">Kelas</th>
                    <?php foreach($opt_jenis as $key => $c): ?>
                        <th class="text-center"><?=$c?></th>
                    <?php endforeach; ?>
                    <th class="col-md-4">Total siswa</th>    
                    <th class="col-md-4">Total siswa</th>    
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($data)){ ?>
                    <?php foreach($data as $key => $c): ?>
                        <tr>
                            <td><?=$c->nama?></td>
                            <td><?=$c->kelas?></td>
                            <?php foreach($opt_jenis as $kex => $x): $var = 'total_' . $kex; ?>
                                <td class="text-center"><?=$c->$var?></td>
                            <?php endforeach; ?>
                                <td><?=$c->total_siswa?></td>
            
                                <td>
                                <form class="form-horizontal" method="POST" action="<?=site_url('rekap_absensi/submit')?>" enctype="multipart/form-data">                                    
                                    <input type="hidden" class="form-control" name="sklh" value="<?=$c->sekolah_id?>">
                                    <input type="hidden" class="form-control" name="kls" value="<?=$c->kelas_id?>">
                                    <input type="hidden" class="form-control" name="tgl" value="<?=$c->tgl?>">
                                    <button type="submit" class="btn btn-default">
				            	<i class="fa fa-check"></i>&nbsp;&nbsp;Kirim
				            </button>
                                </form>
                                </td>
                        </tr>
                    <?php endforeach; ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="<?=count($opt_jenis) + 2?>"><?=war_msg('Tidak ada data.')?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
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
            	<a href="<?=site_url('profil_sekolah')?>" id="btn-yes" class="btn btn-default">
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
    function get_kelas(id)
    {
        $.ajax({
            url     : "<?=site_url('rekap_absensi/get_kelas?selected=' . @$kelas . '&sekolah_id=')?>" + id,
            method  : 'GET',
            success : function(result){
                $('#area_kelas').html(result);
            }

        });
    }

    get_kelas($('select[name="sekolah"]').val());    
    	function confirm_hapus(id)
	{
		$('#modal-hapus #btn-yes').attr({'href' : '<?=site_url('rekap_absensi/fcm')?>/' + id});
		$('#modal-hapus').modal();
	}
</script>