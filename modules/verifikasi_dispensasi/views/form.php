<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">
   			<i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Data Dispensasi Absen
        </h3>
    </div>
	<form class="form-horizontal" method="POST" action="<?=site_url('verifikasi_dispensasi/submit/' . $id)?>" enctype="multipart/form-data">
        <div class="box-body">
			<div class="row">
				<div class="col-md-12">
					<a href="<?=site_url('verifikasi_dispensasi')?>" class="btn btn-default">
						<i class="fa fa-history"></i> Kembali
					</a>
				</div>
			</div>
			<hr/>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
				        <label class="col-md-3 control-label">Sekolah</label>
				        <div class="col-md-8">
				        	<?=form_dropdown('sekolah_id', $opt_sekolah, @$data->sekolah_id, 'class="form-control" onchange="load_kelas()"')?>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">NIS</label>
				        <div class="col-md-3">
				            <input type="text" class="form-control" name="nis" value="<?=@$data->nis?>">
				        </div>
				        <div class="col-md-3">
	        				<button class="btn btn-primary btn-block" type="button" onclick="checkNIS()">Check NIS</button>
				        </div>
				        <div class="col-md-3">
				        	<p class="form-control-static status-check-nis"></p>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Tanggal Mulai</label>
				        <div class="col-md-4">
				        	<input type="text" name="tgl_mulai" class="form-control" readonly value="<?=empty($data->tgl_mulai) ? date('Y-m-d') : $data->tgl_mulai?>">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Tanggal Selesai</label>
				        <div class="col-md-4">
				        	<input type="text" name="tgl_selesai" class="form-control" readonly value="<?=empty($data->tgl_selesai) ? date('Y-m-d') : $data->tgl_selesai?>">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-3 control-label">Keterangan</label>
				        <div class="col-md-8">
				        	<textarea rows="4" name="keterangan" class="form-control"><?=@$data->keterangan?></textarea>
				        </div>
				    </div>
				 </div>
				 <div class="col-md-6">
	            	<table class="table table-striped table-hover table-bordered">
	            		<thead>
	            			<tr>
	            				<th colspan="2">Informasi Siswa</th>
	            			</tr>
	            		</thead>
	            		<tbody>
		            		<tr>
		            			<td class="col-md-3">Nama</td>
		            			<td class="info_nama"><?=@$data->nama_siswa?></td>
		            		</tr>
		            		<tr>
		            			<td>NIS</td>
		            			<td class="info_nis"><?=@$data->nis?></td>
		            		</tr>
		            		<tr>
		            			<td>Kelas</td>
		            			<td class="info_kelas"><?=@$data->kelas?></td>
		            		</tr>
		            		<tr>
		            			<td>Sekolah</td>
		            			<td class="info_sekolah"><?=@$data->sekolah?></td>
		            		</tr>
		            	</tbody>
	            	</table>						 	
				 </div>
			</div>
        </div>
        <div class="box-footer text-right">
            <button type="submit" class="btn btn-primary">
            	<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
            </button>
            <a href="<?=site_url('verifikasi_dispensasi')?>" class="btn btn-default">
            	<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
            </a>
        </div>
	</form>
</div>

<link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte')?>/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte')?>/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css">
<script src="<?=base_url('vendor/almasaeed2010/adminlte')?>/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>

<script type="text/javascript">
    $('input[name="tgl_mulai"], input[name="tgl_selesai"]').datepicker({
        autoclose   : true,
        format      : 'yyyy-mm-dd'
    });    

	function checkNIS()
	{
		$('.status-check-nis').html('');
		var nis = $('input[name="nis"]').val();
		if(nis == '')
		{
			return false;
		}

		$('.status-check-nis').html('<span class="text-blue"><b>Loading ...</b></span>');
		$.ajax({
			url 		: "<?=site_url('verifikasi_dispensasi/ajax_cek_nis')?>",
			method 		: "POST",
			data 		: "nis=" + nis + "&sekolah=" + $('select[name="sekolah_id"]').val(),
			dataType	: "json",
			success 	: function(resp){
				if(resp.status == '200')
				{
					$('.info_nama').html(resp.data.nama);
					$('.info_nis').html(resp.data.nis);
					$('.info_kelas').html(resp.data.kelas);
					$('.info_sekolah').html(resp.data.sekolah);
					$('.info_saldo').html(resp.data.sisa_saldo);
					$('.status-check-nis').html('<span class="text-green"><b>NIS valid</b></span>');
				}
				else if(resp.status == '201')
				{
					$('.status-check-nis').html('<span class="text-red"><b>NIS tidak valid</b></span>');
				}
			}
		});

		return false;
	}
</script>

