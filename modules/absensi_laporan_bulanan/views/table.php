<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">
            <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Laporan Bulanan
        </h3>
    </div>
    <div class="box-body">
        <form method="GET" action="<?=site_url('absensi_laporan_bulanan/index')?>">
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
                <div class="col-md-1">
                <?php if($this->input->get('kelas')!=''){?>
                    <label>&nbsp;</label>
                        <button title="Data yang dicetak berdasarkan semua data yang di tampilkan" class="btn btn-info btn-block" onclick="printDiv('printableArea')">Cetak</button>
                <?php } ?>                  
                </div>
            </div>
        </form>
    </div>
</div>
<div class="box box-primary" id="printableArea">
<style>
@page {
    size: A4 landscape;
}
</style>
    <div class="box-body">
        <h4 class="text-center" style="margin: 3px;">Laporan Absensi</h4>
        <h4 class="text-center" style="margin: 3px;">Bulan <?=bulan_indonesia($bulan)?>, <?=$tahun?></h4>
        <?php if($sekolah_label != 'Semua Sekolah'){ ?>
            <h3 class="text-center" style="margin: 3px;"><?=$sekolah_label?></h3>
        <?php } ?>
        <?php if($kelas_label != 'Semua Kelas'){ ?>
            <h3 class="text-center" style="margin: 3px;">Kelas : <?=$kelas_label?></h3>
        <?php } ?>
        <hr/>
        <table class="table table-bordered table-striped table-hover" style="zoom : 0.65" width="100%">
        <?php $jumHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun); ?>
            <thead>
                <tr>
                    <th rowspan="2" class="text-center">NIS</th>
                    <th rowspan="2" class="text-center">Nama</th>
                    <th colspan="<?=$jumHari ?>" class="text-center">Bulan</th>
                    <th colspan="3" class="text-center">Total</th>
                </tr>
                <tr>
                    <?php
                        for ($i=1; $i <= $jumHari; $i++) {  ?>
                    <td><?php echo $i ?></td>   
                    <?php  }
                    ?>
                    <td class="text-center">Absen</td>
                    <td class="text-center">Ijin</td>
                    <td class="text-center">Sakit</td>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($data)){ ?>
                    <?php foreach($data as $key => $c): ?>
                        <tr id="siswa">
                            <td id="nis"><?=$c->nis?></td>
                            <td><?=$c->nama?></td>
                            <?php
                                $hadir = $kehadiran->get_kehadiran($c->user_id,$bulan,$tahun)->result();
                                $hari = array();
                                $ijin = 0;
                                $alfa = 0;
                                $sakit = 0;
                                foreach($hadir as $key => $h):
                                    if(!empty($hadir)){
                                        if($h->status=='hadir'){
                                            $hari[$h->tanggal]='H';
                                        }elseif($h->status=='ijin'){
                                            $hari[$h->tanggal]='I';
                                            $ijin += 1;
                                        }elseif($h->status=='sakit'){
                                            $hari[$h->tanggal]='S';
                                            $sakit += 1;
                                        }elseif($h->status=='pulang'){
                                            $hari[$h->tanggal]='H';
                                        }elseif($h->status=='bolos'){
                                            $hari[$h->tanggal]='A';
                                            $alfa += 1;
                                        }
                                    }
                                endforeach;
                            for ($i=1; $i <= $jumHari; $i++){  
                                ?>
                            <td>
                                <?php      
                                    if (!empty($hari[$i])){
                                        echo $hari[$i];
                                    }else{echo '-';}
                                ?>
                            </td>   
                            <?php 
                            }
                            ?>
                            <td class="text-center"><?= $alfa;?></td>
                            <td class="text-center"><?= $ijin;?></td>
                            <td class="text-center"><?= $sakit;?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="<?=count($jumHari) + 5?>"><?=war_msg('Tidak ada data.')?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?=$pagination?>
    </div>
</div>

<script type="text/javascript">

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

    function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
    }  

    document.getElementById("ijin").value = 3;

    // var css = '@page { size: landscape; }',
    // head = document.head || document.getElementsById("#printableArea2"),
    // style = document.createElement('style');
    // // style = document.getElementsById("#printableArea2");

    // style.type = 'text/css';
    // style.media = 'print';

    // if (style.styleSheet){
    // style.styleSheet.cssText = css;
    // } else {
    // style.appendChild(document.createTextNode(css));
    // }

    // head.appendChild(style);

    // window.print();
</script>