<style>
#myProgress {
  width: 100%;
  background-color: #ddd;
}

#myBar {
  width: 0%;
  height: 30px;
  background-color: #4CAF50;
  text-align: center; /* To center it horizontally (if you want) */
  line-height: 30px; /* To center it vertically */
  color: white;
}

.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

</style>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
           			<i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Pesan Broadcast
                </h3>
            </div>
            <div class="box-body">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><b>Kirim Berdasarkan Kelas</b></a></li>
					<?php if($login_level == 'administrator' || $login_level == 'operator sekolah' || $login_level == 'kepala sekolah'){ ?>
					<li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><b>Kirim Berdasarkan Jabatan</b></a></li>
					<?php } ?>
				</ul>
			</div>
            <div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					<div class="form-horizontal" >
						<div class="form-group">
							<label class="col-md-2 control-label">Isi</label>
							<div class="col-md-9">
								<textarea id="isi" class="form-control" name="isi" rows="7"></textarea>
								<p id="peringatan" class="danger" style="display:none;color:red">Isi Pesan Kosong !</p>
								<input type="hidden" id="user_id" value="<?php echo $this->login_uid?>">
							</div>
						</div>
						<hr/>
						<div class="form-group">
							<label class="col-md-2 control-label">Sekolah</label>
							<div class="col-md-5">
								<?=form_dropdown('sekolah_id', $opt_sekolah, @$data->sekolah_id, 'class="form-control" onchange="load_kelas()"')?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Kelas Tujuan</label>
							<div class="col-md-10" id="respon-kelas"></div>
						</div>
						<div class="form-group">
							<div class="col-md-2"></div>
							<div class="col-md-10">
								<p id="peringatan_kelas" class="danger" style="display:none;color:red">Silahkan Pilih Kelas !</p>
							</div>
						</div>
						<hr/>
						<div class="form-group">
							<label class="col-md-2 control-label">Target Pesan</label>
							<div class="col-md-8">
								<div class="checkbox">
									<label>
										<input type="checkbox" id="siswa" value="Y" name="target_siswa"> Siswa
									</label>
								</div>				        	
								<div class="checkbox">
									<label>
										<input type="checkbox" id="wali_siswa" value="Y" name="target_wali"> Wali Siswa
									</label>
								</div>				        	
								<?php if($login_level == 'administrator' || $login_level == 'operator sekolah' || $login_level == 'kepala sekolah'){ ?>
									<div class="checkbox">
										<label>
											<input type="checkbox" id="wali_kelas" value="Y" name="target_wali_kelas"> Wali Kelas
										</label>
									</div>				        				        	
								<?php } ?>
								<p id="peringatan_target" class="danger" style="display:none;color:red">Anda belum memasukkan target !</p>
							</div>
						</div>
						<hr/>
						<div class="form-group">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-default" onclick="move()">
									<i class="fa fa-send"></i>&nbsp;&nbsp;Kirim
								</button>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="tab_2">
				<form class="form-horizontal" method="POST" action="<?=site_url('pesan_broadcast/submitpegawai/' . $id)?>" enctype="multipart/form-data">
						<div class="form-group">
							<label class="col-md-2 control-label">Isi</label>
							<div class="col-md-9">
								<textarea class="form-control" name="isi" rows="7"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Gambar</label>
							<div class="col-md-5">
								<input type="file" class="form-control" name="userfiles">
							</div>
						</div>
						<hr/>
						<div class="form-group">
							<label class="col-md-2 control-label">Sekolah</label>
							<div class="col-md-5">
								<?=form_dropdown('sekolah_id', $opt_sekolah, @$data->sekolah_id, 'class="form-control" onchange="load_kelas()"')?>
							</div>
						</div>
						<hr/>
						<div class="form-group">
							<?php if($login_level == 'administrator' || $login_level == 'operator sekolah' || $login_level == 'kepala sekolah'){ ?>
								<div class="form-group">
									<label class="col-md-2 control-label">Kirim Juga ke</label>
									<div class="col-md-5">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="Y" name="target_operator_sekolah"> Operator Sekolah
											</label>
										</div>				        	
										<div class="checkbox">
											<label>
												<input type="checkbox" value="Y" name="target_kepala_sekolah"> Kepala Sekolah
											</label>
										</div>
										<!-- Menambahkan Checkbox untuk target semua pegawai -->
										<div class="checkbox">
											<label>
												<input type="checkbox" value="Y" name="target_guru_staff"> Guru dan Staff
											</label>
										</div>					        	
									</div>
								</div>
							<?php } ?>
						</div>
						<hr>
						<div class="form-group">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-default">
									<i class="fa fa-send"></i>&nbsp;&nbsp;Kirim
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>				
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" id="tutupModal" class="close" data-dismiss="modal" style="display:none;">&times;</button>
		  	<div class="alert alert-info alert-dismissible">
                <h4><i class="icon fa fa-info"></i> Mohon Tunggu </h4>
                Jangan tutup atau merefresh halaman selama proses pengiriman pesan. jendela ini akan otomatis ditutup setelah semua proses pengiriman pesan berhasil.
        	</div>
        </div>
        <div class="modal-body">
			<div class="loader"></div>
			<h3>
				Dikirim Ke <span class="tujuan"></span> : <span class="prosesdata">0</span> / <span class="semuadata">0</span>
			</h3>
			<h3>
				Dikirim Ke Wali Kelas : <span class="prosesdataWali">0</span> / <span class="semuadataWali">0</span>
			</h3>
			<br>
			<hr>
        </div>
      </div>
      
    </div>
</div>

<script type="text/javascript">

	function load_kelas()
	{
		$.ajax({
			url 		: "<?=site_url('pesan_broadcast/ajax_form_kelas')?>",
			method		: "POST",
			data 		: "sekolah=" + $('select[name="sekolah_id"]').val(),
			success		: function(result){
				$('#respon-kelas').html(result);
			}
		})		
	}
	$('document').ready(function(){
		load_kelas();
	});

	var i = 0;

	function kirim_pesan(data) {
		$("#myModal").modal();

		var isisiswa 	= document.getElementById("siswa").checked;
		var isiwali 	= document.getElementById("wali_siswa").checked;
		var isi 	 	= document.getElementById("isi").value;
		waktu = data.length-1;
		totalData = data.length;
		
		if(isisiswa == true){
			target_siswa = 'Y';
		}else{
			target_siswa = 'N';
		}

		if(isiwali == true){
			target_wali = 'Y';
		}else{
			target_wali = 'N';
		}

		for (let i = 0; i < data.length; i++) {

			var dataForm = {
				user_id 		: data[i].user_id,
				pesan			: isi,
				fcm 			: data[i].fcm,
				fcm_ortu 		: data[i].fcm_ortu,
				target_siswa 	: target_siswa,
				target_wali 	: target_wali,
			};

			$.ajax({
				url 		: "<?=site_url('pesan_broadcast/kirim_pesan_all')?>",
				method		: "POST",
				data 		: dataForm,
				success: function (data) { 
					a = i + 1;
					$('.prosesdata').html(a);

					if(document.querySelector('.semuadata').innerHTML == document.querySelector('.prosesdata').innerHTML && document.querySelector('.semuadataWali').innerHTML == document.querySelector('.prosesdataWali').innerHTML){
						$("#myModal").modal('hide');
					}
				},
        		error: function (jqXHR, textStatus, errorThrown) {
					alert('Gagal mengirim pesan, Pastikan koneksi stabil'); 
				}
			});
			
			
		}
	}

	function kirim_pesan_guru(data) {
		
		waktu = data.length-1;
		$("#myModal").modal();

		if (document.getElementById("wali_kelas").checked){
			var isi 	 	= document.getElementById("isi").value;

			for (let i = 0; i < data.length; i++) {
				var dataForm = {
					user_id 		: data[i].user_id,
					pesan			: isi,
					fcm 			: data[i].fcm,
				};

				$.ajax({
					url 		: "<?=site_url('pesan_broadcast/kirim_pesan_wali_kelas')?>",
					method		: "POST",
					data 		: dataForm,
					success: function (data) { 
						a = i + 1;
						$('.prosesdataWali').html(a);

						if(document.querySelector('.semuadata').innerHTML == document.querySelector('.prosesdata').innerHTML && document.querySelector('.semuadataWali').innerHTML == document.querySelector('.prosesdataWali').innerHTML){
							$("#myModal").modal('hide');
						}
					},
					error: function (jqXHR, textStatus, errorThrown) {
						alert('Gagal mengirim pesan, Pastikan koneksi stabil'); 
						document.getElementById("tutupModal").style.display = "block";
					}
				});
			}
		}
		
	}

	function move() {
		// ini untuk isi
		var isi 		 = document.getElementById("isi").value;
		var user_id	 	 = document.getElementById("user_id").value;
			
		// var isikelas 	 = document.getElementById("kelas").checked;
		var isisiswa 	 = document.getElementById("siswa").checked;
		var isiwali 	 = document.getElementById("wali_siswa").checked;
		var isiwalikelas = document.getElementById("wali_kelas").checked;

		// mengambil data peringatan
		var peringatan = document.getElementById("peringatan");
		var peringatan_kelas = document.getElementById("peringatan_kelas");
		var peringatan_target = document.getElementById("peringatan_target");

		// default pemanggilan kelas
		var kelas_centang = [];
            $.each($("input[name='kelas[]']:checked"), function(){
                kelas_centang.push($(this).val());
            });

		// pengecekan 
		kosong = 0;
		if(isi.length==0){
			peringatan.style.display = "block";
			kosong = 1;
		}else{
			peringatan.style.display = "none";
		}

		if (isisiswa == true || isiwali == true || isiwalikelas == true){
			peringatan_target.style.display = "none";
			if(isisiswa == true && isiwali == true){
				$('.tujuan').html(' Siswa & Wali Siswa');
			}else if(isisiswa == true && isiwali == false){
				$('.tujuan').html(' Siswa');
			}else if(isisiswa == false && isiwali == true){
				$('.tujuan').html(' Wali Siswa');
			}else{
				$('.tujuan').html(' Siswa');
			}
		}else{
			peringatan_target.style.display = "block";
			kosong = 1;
		}

		if (kelas_centang == ''){
			peringatan_kelas.style.display = "block";
			kosong = 1;
		}else{
			peringatan_kelas.style.display = "none";
		}

		if(kosong == 1){
			return false;
		}

		var dataForm = {
			kelas		: kelas_centang,
		}

		total = 0;

		if (isisiswa == true || isiwali == true){
			$.ajax({
				url 		: "<?=site_url('pesan_broadcast/ajax_get_all')?>",
				method		: "POST",
				data 		: dataForm,
				success		: function(result){
					data = JSON.parse(result);
						if(data.length!=0)
						{
							$('.semuadata').html(data.length);
							kirim_pesan(data);
						}
						else
						{
							$('.semuadata').html('Terjadi Kesalahan');
						}
				}
			})	
		}

		if (isiwalikelas == true){
			$.ajax({
				url 		: "<?=site_url('pesan_broadcast/ajax_get_guru')?>",
				method		: "POST",
				data 		: dataForm,
				success		: function(result){
					data = JSON.parse(result);
						if(data.length!=0)
						{
							$('.semuadataWali').html(data.length);
							kirim_pesan_guru(data);
						}
						else
						{
							$('.semuadataWali').html('Terjadi Kesalahan');
						}
				}
			})	
		}

	}


</script>