<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">
            <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Verifikasi Absensi
        </h3>
    </div>
    <div class="box-body">
        <form method="GET" action="<?=site_url('verifikasi_absensi/index?jenis=' . $jenis)?>">
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
                        <label>Tanggal</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="hidden" name="jenis" value="<?=$jenis?>">
                            <input type="text" class="form-control pull-right" id="datepicker" name="tanggal" readonly value="<?=empty($tanggal) ? date('Y-m-d') : $tanggal?>">
                        </div>
                    </div>                    
                </div>
                <div class="col-md-3">
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

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="<?=$jenis == '' ? 'active' : ''?>">
            <a href="<?=site_url('verifikasi_absensi/index?jenis=&tanggal=' . $tanggal . '&sekolah=' . $sekolah . '&kelas=' . $kelas)?>">
                Sudah Absen
            </a>
        </li>
        <li class="<?=$jenis == 'belum_absen' ? 'active' : ''?>">
            <a href="<?=site_url('verifikasi_absensi/index?jenis=belum_absen&tanggal=' . $tanggal . '&sekolah=' . $sekolah . '&kelas=' . $kelas)?>">
                Belum Absen
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active">
            <?php
                if(!empty($data))
                {
                    if($jenis == '')
                    { 
                        $this->load->view('table_sudah_absen'); 
                    }
                    elseif($jenis == 'belum_absen')
                    { 
                        $this->load->view('table_belum_absen'); 
                    }
                    echo $pagination;
                }
                else
                {
                    echo war_msg('Tidak ada data.');
                }
            ?>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte')?>/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte')?>/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css">
<script src="<?=base_url('vendor/almasaeed2010/adminlte')?>/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
    $('#datepicker').datepicker({
        autoclose   : true,
        format      : 'yyyy-mm-dd'
    });    

    function get_kelas(id)
    {
        $.ajax({
            url     : "<?=site_url('verifikasi_absensi/get_kelas?selected=' . @$kelas . '&sekolah_id=')?>" + id,
            method  : 'GET',
            success : function(result){
                $('#area_kelas').html(result);
            }

        });
    }

    get_kelas($('select[name="sekolah"]').val());    
</script>