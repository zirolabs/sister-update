<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">
            <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Laporan Periode
        </h3>
    </div>
    <div class="box-body">
        <form method="GET" action="<?=site_url('absensi_laporan_periode/index')?>">
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
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="input-group">
                            <button type="submit" class="btn btn-primary btn-block">
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
        <h4 class="text-center" style="margin: 3px;">Laporan Absensi</h4>
        <h4 class="text-center" style="margin: 3px;"><?=format_tanggal_indonesia($tanggal_awal)?> - <?=format_tanggal_indonesia($tanggal_akhir)?></h4>
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
                    <th class="col-md-2">NIS</th>
                    <th class="col-md-4">Nama</th>
                    <?php foreach($opt_jenis as $key => $c): ?>
                        <th class="text-center"><?=$c?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($data)){ ?>
                    <?php foreach($data as $key => $c): ?>
                        <tr>
                            <td><?=$c->nis?></td>
                            <td><?=$c->nama?></td>
                            <?php foreach($opt_jenis as $kex => $x): $var = 'total_' . $kex; ?>
                                <td class="text-center"><?=$c->$var?></td>
                            <?php endforeach; ?>
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

<link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte')?>/bower_components/bootstrap-datepicker/dist/css/datepicker.css">
<link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte')?>/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css">
<script src="<?=base_url('vendor/almasaeed2010/adminlte')?>/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
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