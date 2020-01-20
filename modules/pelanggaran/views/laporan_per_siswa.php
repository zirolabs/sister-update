<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">
            <i class="fa fa-print"></i>&nbsp;&nbsp;&nbsp;Laporan Pelanggaran Siswa
        </h3>
    </div>
	
    <div class="box-body">
        <form method="GET" action="<?=site_url('pelanggaran/laporan_per_siswa')?>">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Nama Siswa</label>
                        <input name="nama_siswa" id="autocomplete" class="autocomplete form-control" type="text">
				    </div>                    
                </div>
				<div class="col-md-2">
                    <div class="form-group">
                        <label>NIS</label>
                        <input name="nis" placeholder="nis" id="nis" class="form-control" readonly value="<?=$nis?>"></input>
				    </div>                    
                </div>
				<div class="col-md-2">
                    <div class="form-group">
                        <label>Kelas</label>
                        <input name="kelas" placeholder="Kelas" id="kelas" class='form-control' type="text" readonly></input>
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
<div class="box box-primary" id="laporan">
    <div class="box-body">
	<?php
		if (empty($data)){
	?>
		 <h4 class="text-center" style="margin: 3px;">Data Tidak Tersedia</h4>
	<?php
		}else{
	?>	
        <h4 class="text-center" style="margin: 3px;">Laporan Pelanggaran Siswa</h4>
        <h4 class="text-center" style="margin: 3px;"><?=format_tanggal_indonesia($tanggal_awal)?> - <?=format_tanggal_indonesia($tanggal_akhir)?></h4>
       
        <hr/>
		
			<table class="table">
	    	    <tr>
                	<h4><strong>Keterangan Tentang Diri Siswa</strong></h4>
                </tr>
                <?php
			       foreach($data as $row){
						$nama = $row->nama;
						$nis = $row->nis;
						$nama = $row->nama;
						$kelas = $row->kelas;
						$alamat = $row->alamat;
						$agama_siswa = $row->agama_siswa;
						$foto = $row->foto;
						$tempat_lahir_siswa = $row->tempat_lahir_siswa;
						$tanggal_lahir_siswa = $row->tanggal_lahir_siswa;
				   }	
			    ?>
                


                 <?php 
                    $hari = array("Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu");
                    $bulan = array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
                 ?>

                <tr>
                	<td width="185px">Nama</td>
                	<td width="5px">:</td>
                	<td><?php echo $nama;?></td>
                	<td rowspan="4" width="150px"><right>
                    <img src="<?php echo base_url(); ?>assets/img/foto-profil/<?php echo $foto; ?>" onerror="this.src='<?php echo base_url(); ?>assets/img/foto-profil/avatar.png'" height="150" width="130"></right></td>
                </tr>
                <tr>
                	<td width="160px">NIS</td>
                	<td width="5px">:</td>
                	<td><?php echo $nis;?></td>
                </tr>
                <tr>
                	<td width="160px">Tempat Tanggal Lahir</td>
                	<td width="5px">:</td>
                	<td>
                		<?php echo $tempat_lahir_siswa;?>, 
                		<?php echo date('j', strtotime(date($tanggal_lahir_siswa))).' '.$bulan[date('n',strtotime(date($tanggal_lahir_siswa)))].' '.date('Y', strtotime(date($tanggal_lahir_siswa))); ?>
                	</td>
                </tr>
                <tr>
                	<td width="160px">Agama</td>
                	<td width="5px">:</td>
                	<td><?php echo $agama_siswa;?></td>
                </tr>
                <tr>
                	<td width="160px">Kelas</td>
                	<td width="5px">:</td>
                	<td><?php echo $kelas;?></td>
                </tr>
                <tr>
                	<td width="160px">Alamat</td>
                	<td width="5px">:</td>
                	<td><?php echo $alamat;?></td>
                </tr>
            </table>
				
			<table class="table">
                 <tr>
                	<h4><strong>Keterangan Pelanggaran</strong></h4>
                </tr>
                <?php
                $no=1;
                $total=0;
			      if(empty($data))
			      {
			        echo "<tr><td colspan=\"6\">Data tidak tersedia</td></tr>";
			      } else{
			       foreach($data as $row)
			      {    
			    ?>
                <tr>
                <td class="text-center" rowspan="6"><?php echo $no;?>.</td>
                	<td width="160px">Tanggal</td>
                	<td width="5px">:</td>
                	<td><?php echo date('j', strtotime(date($row->tanggal_pelanggaran))).' '.$bulan[date('n',strtotime(date($row->tanggal_pelanggaran)))].' '.date('Y', strtotime(date($row->tanggal_pelanggaran))); ?></td>
                </tr>
                <tr>
                    <td width="160px">Kelas</td>
                    <td width="5px">:</td>
                    <td><?php echo $row->kelas;?></td>
                </tr>
                <tr>
                	<td width="160px">Pelanggaran</td>
                	<td width="5px">:</td>
                	<td><?php echo $row->deskripsi_pelanggaran;?></td>
                </tr>
                <tr>
                    <td width="160px">Point</td>
                    <td width="5px">:</td>
                    <td><?php echo $row->point_pelanggaran;?></td>
                </tr>
                <tr>
                    <td width="160px">Tindak Lanjut</td>
                    <td width="5px">:</td>
                    <td><?php echo $row->tindak_lanjut;?></td>
                </tr>
                <tr>
                    <td width="160px">Guru Penanggung Jawab</td>
                    <td width="5px">:</td>
                    <td><?php echo $row->nama_guru;?></td>
                </tr>
                <?php
                $no++;
                $total+=$row->point_pelanggaran;
			        }}
			     ?>
                 </br>
               </table>	
			   
			    <b>Catatan :</b>
				<!--
               <table class="table table-bordered">
                <tr>
                    <td width="160px" height="10">Pengurangan Poin</td>
                    <td></td>
                </tr>
                <tr>
                    <td width="160px">Point Penghargaan</td>
                    <td>
                        <?php foreach ($data3 as $key): ?>
                            <?php 
                                $poin_penghargaan = $key->poin_penghargaan; 
                                echo $poin_penghargaan; 
                            ?>
                        <?php endforeach ?>
                    </td>
                </tr>
               </table>
				-->
				 <table class="table table-bordered">
                <tr>
                    <td width="160px" height="10">Jumlah Kejadian</td>
                    <td><?php echo " ". $no-1;?></td>
                </tr>
                <tr>
                    <td width="160px">Poins Pelanggaran</td>
                    <td><?php echo " ". $total;?></td>
                </tr>
                <tr>
                    <td width="160px">Poin Total</td>
                    <td><?php echo " ". $total;?></td>
                </tr>

               </table>
				
		
		<?php //if($sekolah_label != 'Semua Sekolah'){ ?>
		<hr />
		<a target="_blank" href="<?=site_url('pelanggaran/cetak_laporan_per_siswa/'. $nis.'/'.$tanggal_awal.'/'.$tanggal_akhir)?>" class="btn btn-primary btn-block" title="Cetak PDF">
			<i class="fa fa-print"></i> Cetak Laporan
		</a>
		<?php //}?>
		<?php }?>
    </div>
</div>

<script type='text/javascript' src='<?php echo base_url();?>assets/js/jquery.autocomplete.js'></script>
<link href='<?php echo base_url();?>assets/js/jquery.autocomplete.css' rel='stylesheet' />
<link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte')?>/plugins/datepicker/datepicker3.css">
<script src="<?=base_url('vendor/almasaeed2010/adminlte')?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript">

    $('.datepicker-input').datepicker({
        autoclose   : true,
        format      : 'yyyy-mm-dd'
    });    

    if ($('#nis').val()==''){		
        $('#laporan').empty();
	}        
	
	var site = "<?php echo site_url();?>";
	$(function(){
            $('#autocomplete').autocomplete({
                // serviceUrl berisi URL ke controller/fungsi yang menangani request kita
                serviceUrl: site+'/pelanggaran/search',
                // fungsi ini akan dijalankan ketika user memilih salah satu hasil request
                onSelect: function (suggestion) {
                    $('#nis').val(''+suggestion.nis); // membuat id 'v_nim' untuk ditampilkan
                    $('#kelas').val(''+suggestion.kelas); // membuat id 'v_jurusan' untuk ditampilkan
				}
            });
    });
</script>