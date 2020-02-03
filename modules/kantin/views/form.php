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
			<!-- <li class="disabled" disabled><a href="#tab_2" data-toggle="tab" aria-expanded="true"><b>Gunakan QR Code</b></a></li> -->
			<li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false"><b>Gunakan RFID</b></a></li>
		</ul>
	</div>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_1">
			<div class="row">
					<form class="form-horizontal formrfid" method="POST" action="<?=site_url('kantin/submit/' . $id)?>" enctype="multipart/form-data">	
						<div class="col-md-4">
							<div class="box-body">
								<div class="form-group">
									<label class="control-label">&nbsp;&nbsp;&nbsp;Scan Barcode</label>
									<input type="text" class="form-control" name="keyword_code" onkeyup="barcode_produk();" style="margin-left: 10px;" autofocus>
								</div>
								<div id="#status-barcode"></div>
								<div class="form-group">
									<label class="control-label">&nbsp;&nbsp;&nbsp;Sekolah</label>
									<?=form_dropdown('sekolah_id', $opt_sekolah, $data_sekolah->sekolah_id, 'class="form-control" onchange="load_user()" disabled style="margin-left: 10px;"')?>
								</div>
								<div class="form-group">
									<label class="control-label">&nbsp;&nbsp;&nbsp;Cari berdasarkan nama Produk</label>
									<input type="text" class="form-control" name="keyword" onkeyup="load_produk();" style="margin-left: 10px;">
									<p class="help-block">&nbsp;&nbsp;&nbsp;Masukkan setidaknya 4 karakter.</p>
								</div>
								<div class="row">
									<div class="col-md-12" id="respon-produk"></div>
								</div>
							</div>
						</div>
						<div class="col-md-8">
							<div class="box-body">
							<div class="form-group">
								<label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Daftar Produk yang dibeli</label>
								<div class="col-md-12">
									<table class="table table-striped table-hover">
										
									</table>
									<table class="table table-striped table-hover" style="display: none;" id="respon-beli">
										<tr>
											<td>Produk</td>
											<td>Harga</td>
											<td>Kuantitas</td>
											<td>Total</td>
											<td>Hapus</td>
										</tr>
									</table>			        	
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label">Nominal</label>
								<div class="col-md-8">
									<input type="hidden" id="nominal" name="nominal" class="form-control" value="<?=@$data->nominal?>">
									<input type="text" id="show-nominal" name="nominal" class="form-control" value="<?=@$data->nominal?>" disabled>
									<input type="hidden" name="sn_rfid" value="<?=@$data->sn_rfid?>">
								</div>
							</div>
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
						</div>
					</form>				
			</div>
		</div>
		<div class="tab-pane" id="tab_2">
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
	var int_pencarian;
	function load_produk()
	{
		$('#respon-produk').html('');

		var dataForm = {
			sekolah_id		: $('select[name="sekolah_id"]').val(),
			keyword			: $('input[name="keyword"]').val()
		}

		if(int_pencarian != undefined && int_pencarian != '')
		{
			clearTimeout(int_pencarian);
		}

		if(dataForm.keyword == undefined || dataForm.keyword == '')
		{
			return false;
		}

		if(dataForm.keyword.length < 4)
		{
			return false;
		}

		$('#respon-produk').html('Memuat Data ...');
		int_pencarian = setTimeout(function(){
			clearTimeout(int_pencarian);

			$.ajax({
				url 		: "<?=site_url('kantin/ajax_pencarian_produk')?>",
				method		: "POST",
				data 		: dataForm,
				success		: function(result){
					$('#respon-produk').html(result);
				}
			})		


		}, 500);
		return false;
	}

	function barcode_produk()
	{

		var dataForm = {
			sekolah_id		: $('select[name="sekolah_id"]').val(),
			keyword_code	: $('input[name="keyword_code"]').val()
		}

		if(int_pencarian != undefined && int_pencarian != '')
		{
			clearTimeout(int_pencarian);
		}

		if(dataForm.keyword_code == undefined || dataForm.keyword_code == '')
		{
			return false;
		}

		if(dataForm.keyword_code.length < 4)
		{
			return false;
		}

		int_pencarian = setTimeout(function(){
			clearTimeout(int_pencarian);

			$.ajax({
				url 		: "<?=site_url('kantin/ajax_pencarian_barcode')?>",
				method		: "POST",
				data 		: dataForm,
				success		: function(result)
				{

					produk = JSON.parse(result);
					if(produk.status == '200')
					{
						tambah_beli(produk.data.nama, produk.data.produk_id, produk.data.harga_jual, produk.data.harga_awal);
						$('input[name="keyword_code"]').val("");
					}
					if(produk.status == '401')
					{
						$('input[name="keyword_code"]').val("");
						window.alert('Barcode yang anda masukan tidak terdaftar');
					}
					if(produk.status == '201')
					{
						$('input[name="keyword_code"]').val("");
						window.alert('Stok Produk Habis !');
					}
				},
			})
			
		}, 700);

		return false;
		
	}

	total = 0;

	function tambah_beli(nama, produk_id, harga_jual, harga_awal)
	{	
		
		if( $('#respon-beli #beli_' + produk_id).length > 0 )
		{
			input = parseInt($('#kuantitas_' + produk_id).val());
			total -= (input*harga_jual);
			jumlah = input + 1;
			$('#respon-beli #beli_' + produk_id).remove();	

		}else{
			jumlah = 1;
		}

			var str = `
			<tr id="beli_` + produk_id + `" class="">
				<input type="hidden" name="produk_id[]" value="` + produk_id + `">
				<input type="hidden" name="harga_awal[]" value="` + harga_awal + `">
				<input type="hidden" name="harga_jual[]" value="` + harga_jual + `">
				<input type="hidden" id="kuantitas_`+produk_id+`" name="kuantitas[]" value="` + jumlah + `">
				<input type="hidden" name="total[]" value="` + (jumlah*harga_jual) + `">
				<td>` + nama + `</td>
				<td>` + harga_jual + `</td>
				<td>` + jumlah + `</td>
				<td>` + (jumlah*harga_jual) + `</td>
				<td>
					<div class="tools">
						<i class="fa fa-trash-o" onclick="hapus_beli(` + produk_id + `, '` + (jumlah*harga_jual) + `')"></i>
					</div>
				</td>	
			</tr>
		`;

		total += (jumlah*harga_jual);

		$('#nominal').val(total);
		$('#show-nominal').val(total);

		$('#respon-beli').append(str);
		if($('#respon-beli').html() != '')
		{
			$('#respon-beli').removeAttr('style');
		}
	}

	function hapus_beli(id,total_produk)
	{
		$('#respon-beli #beli_' + id).remove();
		total -= total_produk;
		$('#nominal').val(total);
		$('#show-nominal').val(total);

		if($('#respon-beli').html() == '')
		{
			$('#respon-beli').attr('style', 'display: none');
		}		
	}
</script>

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

						$('.box-footer button').html('Lanjutkan');		  				
						$('.box-footer button').attr('class', 'btn btn-success');		  																
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