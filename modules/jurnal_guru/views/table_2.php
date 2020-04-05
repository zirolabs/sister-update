<div class="box box-primary">
    <div class="box-header with-border">
    	<h3 class="box-title">
            
            <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;<a href="<?=site_url('jurnal_guru')?>">Jurnal Guru / </a>Laporan Jurnal Guru
        </h3>
    </div>
    <div class="box-body">
        <form method="GET" action="<?=site_url('jurnal_guru/laporan')?>">
        	<div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Sekolah</label>
                        <?=form_dropdown('sekolah', $opt_sekolah, $sekolah, 'class="form-control" onchange="get_kelas(this.value)"')?>
                    </div>                    
                </div>
                <div class="col-md-2">
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
        	    <div class="col-md-2">
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
                <div class="col-md-1">
                    <label>&nbsp;</label>
                        <button title="Data yang dicetak berdasarkan semua data yang di tampilkan" class="btn btn-info btn-block" onclick="printDiv('printableArea')">Cetak</button>
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
        <h4 class="text-center" style="margin: 3px;">Laporan Jurnal Guru</h4>
        <h4 class="text-center" style="margin: 3px;">Bulan <?=bulan_indonesia($bulan)?>, <?=$tahun?></h4>
        <?php if($sekolah_label != 'Semua Sekolah'){ ?>
            <h3 class="text-center" style="margin: 3px;"><?=$sekolah_label?></h3>
        <?php } ?>
        <?php if($kelas_label != 'Semua Kelas'){ ?>
            <h3 class="text-center" style="margin: 3px;">Kelas : <?=$kelas_label?></h3>
        <?php } ?>
        <hr/>
        <?php $jumHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun); ?>
        <div class="table-responsive">
                <?php 
                if($kelas_label == 'Semua Kelas'){
                    echo war_msg('Silahkan Pilih Kelas Terlebih Dahulu');
                }else{
                    $array =[];
                    foreach ($data as $key => $c) {
                        $array[$key] = day($c->hari);
                    }

                    for ($i=1; $i < $jumHari; $i++) { 
                        $date = $i.'-'.$bulan.'-'.$tahun;
                        if(in_array(date('D',strtotime($date)),$array)){
                            echo '<b> '.tanggal_indo(date('Y-m-d',strtotime($date))) . '</b><br>';?>
                            <table class="table table-striped table-bordered table-hover">
                                <tr>
                                    <td class="col-md-2">Nama Pelajaran</td>
                                    <td class="col-md-2">Guru</td>
                                    <td class="col-md-2">Jam</td>
                                    <td class="col-md-2">Materi</td>
                                </tr>
                            <?php 
                            // menampilkan data perhari
                            foreach ($data_jurnal as $key => $c) {
                                $no = 0;
                                if(date('Y-m-d',strtotime($c->tanggal))==date('Y-m-d',strtotime($date))) { 
                                $no++
                                ?>
                                    <tr>
                                        <td>
                                            <?=$c->nama_mata_pelajaran?>
                                        </td>
                                        <td>
                                            <?=$c->nama_guru?>
                                        </td>
                                        <td>
                                            <?=date('H:i',strtotime($c->jam_mulai))?> - <?=date('H:i',strtotime($c->jam_akhir))?> 
                                        </td>
                                        <td>
                                            <?=$c->materi?>
                                        </td>
                                    </tr>
                            <?php } 
                            } if($no==0){ ?>
                                <tr>
                                    <td colspan="4">Tidak ada data.</td>
                                </tr>
                            <?php } ?>
                            </table>
                            <?php  
                        }
                    }
                }
                ?>
		</div>
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

    function get_kelas()
    {
        $.ajax({
            url     : "<?=site_url('jurnal_guru/ajax_kelas?selected=' . @$kelas . '&sekolah_id=')?>" + $('select[name="sekolah"]').val(),
            method  : 'GET',
            success : function(result){
                $('#area_kelas').html(result);
            }

        });
    }    

    $(document).ready(function(){
        get_kelas();
    }); 

    function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
    }  
</script>
