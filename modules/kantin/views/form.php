<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

<h2 class="text-center" style="margin-top: 0px;"><?=$data_sekolah->nama?></h2>
<h4 class="text-center"><?=$data_sekolah->alamat?></h4>
<div class="box box-primary" style="margin-top: 20px;">
    <div class="box-header with-border">
        <h3 class="box-title">
   			<i class="fa fa-credit-card"></i>&nbsp;&nbsp;&nbsp;Transaksi
        </h3>
    </div>
	<div class="nav-tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab_2" data-toggle="tab" aria-expanded="true"><b>Gunakan QR Code</b></a></li>
			<li class=""><a href="#tab_1" data-toggle="tab" aria-expanded="false"><b>Gunakan RFID</b></a></li>
		</ul>
	</div>
	<div class="tab-content">
		<div class="tab-pane" id="tab_1">
			<form class="form-horizontal formrfid" method="POST" action="<?=site_url('kantin/submit/' . $id)?>" enctype="multipart/form-data">
				<div class="box-body">
					<div class="form-group">
						<label class="col-md-3 control-label">Nominal</label>
						<div class="col-md-6">
							<input type="text" name="nominal" class="form-control" value="<?=@$data->nominal?>">
							<input type="hidden" name="sn_rfid" value="<?=@$data->sn_rfid?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Keterangan</label>
						<div class="col-md-8">
							<textarea name="keterangan" class="form-control" rows="4"><?=@$data->keterangan?></textarea>
						</div>
					</div>
					<hr/>
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th colspan="2">Informasi Pemilik Kartu</th>
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
								<td>Sisa Saldo</td>
								<td class="info_saldo" style="font-size: 20px;"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="box-footer box1 text-right">
					<a href="<?=site_url('kantin/form/' . $id)?>" class="btn btn-default">Reset</a>
					<button type="button" class="btn btn-primary btn1" onclick="openModalRFID()">Scan RFID</button>
				</div>
			</form>
		</div>
		<div class="tab-pane active" id="tab_2">
		<form class="form-horizontal formqr" method="POST" action="<?=site_url('kantin/submitqr/' . $id)?>" enctype="multipart/form-data">
				<div class="box-body">
					<div class="form-group">
						<label class="col-md-3 control-label">Nominal</label>
						<div class="col-md-6">
							<input type="text" name="nominal" class="form-control" value="<?=@$data->nominal?>">
							<input type="hidden" name="nis" value="<?=@$data->nis?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Keterangan</label>
						<div class="col-md-8">
							<textarea name="keterangan" class="form-control" rows="4"><?=@$data->keterangan?></textarea>
						</div>
					</div>
					<hr/>
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th colspan="2">Informasi Pemilik Kartu</th>
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
								<td>Sisa Saldo</td>
								<td class="info_saldo" style="font-size: 20px;"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="box-footer box2 text-right">
					<a href="<?=site_url('kantin/form/' . $id)?>" class="btn btn-default">Reset</a>
					<button type="button" class="btn btn-primary btn2" onclick="openModalQR()">Scan QRCode</button>
				</div>
			</form>
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

<div class="modal fade" tabindex="-1" role="dialog" id="modal-qrcode">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                	<span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                	<i class="fa fa-info-circle"></i>&nbsp;&nbsp;Scan QRCode
                </h4>
            </div>
            <div class="modal-body text-center">
            	<h5>Hadapkan Layar HP anda di depan kamera</h5>
				<video id="preview" style="max-width: 500px;"></video>
            	<div class="row">
            		<div class="col-md-8 col-md-offset-2">
		            	<input type="text" id="qrcode" name="nis_temp" class="form-control">
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
	function openModalRFID()
	{
		var text_label = $('.box-footer .btn1').html();
		if(text_label == 'Scan RFID')
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
			  			}
			  		}, rfidDoneInterval);
				});

				$input.on('keydown', function () {
			  		clearTimeout(rfidTimer);
				});
			});	
		}
		else
		{
			$('.formrfid').submit();
		}
	}	

	function openModalQR()
	{
		var text_label = $('.box-footer .btn2').html();
		if(text_label == 'Scan QRCode')
		{	
			QRCode();

			$('#modal-qrcode').modal();
			$('#modal-qrcode').on('shown.bs.modal', function() {

				var rfidTimer;                
				var rfidDoneInterval = 1000;

				var $input = $('input[name="nis_temp"]');

	  			$input.val('');
				$input.focus();
				
				$input.on('keyup', function () {
			  		clearTimeout(rfidTimer);
			  		rfidTimer = setTimeout(function(){
			  			if($input.val().length >= 3)
			  			{
							$('input[name="nis"]').val($input.val());							
							$('#modal-qrcode').modal('hide');
			  			}
			  		}, rfidDoneInterval);
				});

				$input.on('keydown', function () {
			  		clearTimeout(rfidTimer);
				});
			});	
		}
		else
		{
			$('.formqr').submit();
		}
	}

	function QRCode()
	{
		let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
		scanner.addListener('scan', function (content) {
			// var x = 32.charCode || 32.keyCode;
			$('input[name="nis_temp"]').val(content); // Pass the scanned content value to an input field
			$('input[name="nis"]').val(content);							
			$('#modal-qrcode').modal('hide');
			scanner.stop()
		});
		Instascan.Camera.getCameras().then(function (cameras) {
			if (cameras.length > 0) {
			scanner.start(cameras[0]);
			} else {
			console.error('No cameras found.');
			}
		}).catch(function (e) {
			console.error(e);
		});
	}

	$('#modal-rfid').on('hidden.bs.modal', function(){

		$('.info_nama').html('');
		$('.info_nis').html('');
		$('.info_kelas').html('');
		$('.info_sekolah').html('');
		$('.info_saldo').html('');

		var kode = $('input[name="sn_rfid"').val();
		if(kode != '')
		{
			$.ajax({
				url 		: "<?=site_url('kantin/ajax_pemilik_kartu')?>",
				method		: "POST",
				data 		: "kode=" + kode,
				dataType	: "json",
				success		: function(resp)
				{
					if(resp.status == '200')
					{
						$('.info_nama').html(resp.data.nama);
						$('.info_nis').html(resp.data.nis);
						$('.info_kelas').html(resp.data.kelas);
						$('.info_sekolah').html(resp.data.sekolah);
						$('.info_saldo').html(resp.data.sisa_saldo);

						$('.box1 button').html('Lanjutkan');		  				
						$('.box1 button').attr('class', 'btn btn-success');		  																
					}
				}
			});
		}

		return true;
	});

	$('#modal-qrcode').on('hidden.bs.modal', function(){

		$('.info_nama').html('');
		$('.info_nis').html('');
		$('.info_kelas').html('');
		$('.info_sekolah').html('');

		var kode = $('input[name="nis"').val();
		if(kode != '')
		{
			$.ajax({
				url 		: "<?=site_url('kantin/ajax_pemilik_nis')?>",
				method		: "POST",
				data 		: "kode=" + kode,
				dataType	: "json",
				success		: function(resp)
				{
					if(resp.status == '200')
					{
						$('.info_nama').html(resp.data.nama);
						$('.info_nis').html(resp.data.nis);
						$('.info_kelas').html(resp.data.kelas);
						$('.info_sekolah').html(resp.data.sekolah);
						$('.info_saldo').html(resp.data.sisa_saldo);

						$('.box2 button').html('Lanjutkan');		  				
						$('.box2 button').attr('class', 'btn btn-success');		  										
					}
				}
			});
		}

		return true;
	});

</script>