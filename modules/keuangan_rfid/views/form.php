
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="box box-primary">
		    <div class="box-header with-border">
		        <h3 class="box-title">
		   			<i class="fa fa-credit-card"></i>&nbsp;&nbsp;&nbsp;Registrasi Kartu
		        </h3>
			</div>
			<br>
			<div class="row">
				<div class="col-md-12">
					<a href="<?=site_url('keuangan_rfid')?>" class="btn btn-default">
						<i class="fa fa-history"></i> Kembali
					</a>
				</div>
			</div>
			<hr/>
			<!-- Nav Tabs -->
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab_2" data-toggle="tab" aria-expanded="true"><b>Gunakan QR Code</b></a></li>
					<li class=""><a href="#tab_1" data-toggle="tab" aria-expanded="false"><b>Gunakan RFID</b></a></li>
				</ul>
			</div>
			<!-- end nav tabs -->
			<div class="tab-content">
              <div class="tab-pane" id="tab_1">
				<!-- Registrasi dengan RFID -->
					<form class="form-horizontal form1" method="POST" action="<?=site_url('keuangan_rfid/submit/' . $id)?>" enctype="multipart/form-data">
						<div class="box-body">
							
							<div class="form-group">
								<label class="col-md-2 control-label">Sekolah</label>
								<div class="col-md-9">
									<?=form_dropdown('sekolah_id', $opt_sekolah, @$data->sekolah_id, 'class="form-control" onchange="load_kelas()"')?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">NIS</label>
								<div class="col-md-4">
									<input type="text" class="form-control" name="nis" value="<?=@$data->nis?>">
									<input type="hidden" name="sn_rfid" class="form-control" value="<?=@$data->sn_rfid?>">
								</div>
								<div class="col-md-3">
									<button class="btn btn-primary btn-block" type="button" onclick="checkNIS()">Check NIS</button>
								</div>
								<div class="col-md-3">
									<p class="form-control-static status-check-nis"></p>
								</div>
							</div>
							<hr/>
							<table class="table table-striped table-hover table-bordered">
								<thead>
									<tr>
										<th colspan="2">Informasi Siswa</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="col-md-3">Nama</td>
										<td class="info_nama"></td>
									</tr>
									<tr>
										<td>NIS</td>
										<td class="info_nis"></td>
									</tr>
									<tr>
										<td>Kelas</td>
										<td class="info_kelas"></td>
									</tr>
									<tr>
										<td>Sekolah</td>
										<td class="info_sekolah"></td>
									</tr>
								</tbody>
							</table>						 	
						</div>
						<div class="box-footer text-right">
							<button type="button" class="btn btn-primary" onclick="openModalRFID()">
								<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
							</button>
							<a href="<?=site_url('keuangan_rfid')?>" class="btn btn-default">
								<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
							</a>
						</div>
					</form>
			   </div>
			   <div class="tab-pane active" id="tab_2">
			   	<!-- Registrasi dengan QR Code -->
			   		<form class="form-horizontal form2" method="POST" action="<?=site_url('keuangan_rfid/submitqr/' . $id)?>" enctype="multipart/form-data">
						<div class="box-body">
							<div class="form-group">
								<label class="col-md-2 control-label">Sekolah</label>
								<div class="col-md-9">
									<?=form_dropdown('sekolah_id', $opt_sekolah, @$data->sekolah_id, 'class="form-control" onchange="load_kelas()"')?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">NIS</label>
								<div class="col-md-4">
									<input type="text" id="nis2" class="form-control" name="nis2" value="<?=@$data->nis?>">
								</div>
								<div class="col-md-3">
									<button class="btn btn-primary btn-block" type="button" onclick="checkNISQR()">Check NIS</button>
								</div>
								<div class="col-md-3">
									<p class="form-control-static status-check-nis"></p>
								</div>
							</div>
							<hr/>
							<table class="table table-striped table-hover table-bordered">
								<thead>
									<tr>
										<th colspan="2">Informasi Siswa</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="col-md-3">Nama</td>
										<td class="info_nama"></td>
									</tr>
									<tr>
										<td>NIS</td>
										<td class="info_nis"></td>
									</tr>
									<tr>
										<td>Kelas</td>
										<td class="info_kelas"></td>
									</tr>
									<tr>
										<td>Sekolah</td>
										<td class="info_sekolah"></td>
									</tr>
									<tr>
										<td>QR Code</td>
										<td>
											<div id="qrcode" style="width:100px; height:100px;"></div>
										</td>
									</tr>
								</tbody>
							</table>						 	
						</div>
						<div class="box-footer text-right">
							<button type="button" class="btn btn-primary" onclick="openModalQR()">
								<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
							</button>
							<a href="<?=site_url('keuangan_rfid')?>" class="btn btn-default">
								<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
							</a>
						</div>
					</form>
			   </div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="modal-rfid">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                	<span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                	<i class="fa fa-info-circle"></i>&nbsp;&nbsp;Scan RFID
                </h4>
            </div>
            <div class="modal-body text-center">
            	<img src="<?=base_url('assets/img/rfid.png')?>" style="max-width: 50%;">
            	<h5>Letakkan Kartu pada RFID Reader <br/>atau<br/> Input 10 digit SN secara Manual melalui input dibawah ini </h5>
            	<div class="row">
            		<div class="col-md-8 col-md-offset-2">
		            	<input type="text" name="rfid_sn_temp" class="form-control">
        			</div>
        		</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                	<i class="fa fa-times"></i> Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

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
			url 		: "<?=site_url('keuangan_rfid/ajax_cek_nis')?>",
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

	// Fungsi untuk NIS di Registrasi QR
	function checkNISQR()
	{
		$('.status-check-nis').html('');
		var nis = $('input[name="nis2"]').val();
		if(nis == '')
		{
			return false;
		}

		$('.status-check-nis').html('<span class="text-blue"><b>Loading ...</b></span>');
		$.ajax({
			url 		: "<?=site_url('keuangan_rfid/ajax_cek_nis')?>",
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

	function openModalRFID()
	{
		$('#modal-rfid').modal();

		$('#modal-rfid').on('shown.bs.modal', function() {

			var rfidTimer;                
			var rfidDoneInterval = 1000;
			var $input = $('input[name="rfid_sn_temp"]');

  			$input.val('');
			$input.focus();

			$input.on('keyup', function () {
		  		clearTimeout(rfidTimer);
		  		rfidTimer = setTimeout(function(){
		  			if($input.val().length >= 9)
		  			{
						$('.sn_rfid_label').html($input.val());
						$('input[name="sn_rfid"]').val($input.val());
						$('#modal-rfid').modal('hide');		  
						setTimeout(function(){
							$('.form1').submit();
						}, 1000);			
		  			}
		  		}, rfidDoneInterval);
			});

			$input.on('keydown', function () {
		  		clearTimeout(rfidTimer);
			});
		});	
	}	

	function openModalQR()
	{	
		$('.form2').submit();
	}

	// Source code menampilkan NIS menjadi QR Qode
	var qrcode = new QRCode(document.getElementById("qrcode"), {
	width : 100,
	height : 100
	});

	function makeCode () {		
		var elText = document.getElementById("nis2");
		qrcode.makeCode(elText.value);
	}

	makeCode();

	$("#nis2").
		on("blur", function () {
			makeCode();
		}).
		on("keydown", function (e) {
			if (e.keyCode == 13) {
				makeCode();
			}
		});
	// Akhir Source Code
</script>

