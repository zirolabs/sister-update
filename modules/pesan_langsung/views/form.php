<form method="POST" action="<?=site_url('pesan_langsung/submit/' . $id)?>" enctype="multipart/form-data" autocomplete="off">
	<div class="row">
		<div class="col-md-4">
			<div class="box box-primary">
			    <div class="box-header with-border">
			        <h3 class="box-title">
			   			<i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Pilih Penerima
			        </h3>
			    </div>
			    <div class="box-body">
					<div class="form-group">
				        <label class="control-label">Sekolah</label>
			        	<?=form_dropdown('sekolah_id', $opt_sekolah, '', 'class="form-control" onchange="load_user()"')?>
				    </div>
					<div class="form-group">
				        <label class="control-label">Jenis Penerima</label>
			        	<?=form_dropdown('jenis_penerima', $opt_jenis_user, '', 'class="form-control" onchange="load_user()"')?>
				    </div>
					<div class="form-group">
				        <label class="control-label">Nama</label>
			            <input type="text" class="form-control" name="keyword" onkeyup="load_user();">
			            <p class="help-block">Masukkan setidaknya 4 karakter.</p>
				    </div>
				</div>
				<div class="box-footer">
				    <div class="row">
				    	<div class="col-md-12" id="respon-user"></div>
				    </div>
			    </div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="box box-primary">
			    <div class="box-header with-border">
			        <h3 class="box-title">
			   			<i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Tulis Pesan
			        </h3>
			    </div>
			    <div class="box-body form-horizontal">
					<div class="form-group">
				        <label class="col-md-2 control-label">Isi</label>
				        <div class="col-md-9">
				        	<textarea class="form-control" name="isi" rows="7"></textarea>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Gambar</label>
				        <div class="col-md-6">
				            <input type="file" class="form-control" name="userfiles">
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Penerima</label>
				        <div class="col-md-10">
							<ul class="todo-list" style="display: none;" id="respon-penerima"></ul>				        	
				        </div>
				    </div>
				</div>
				<div class="box-footer">
			        <div class="col-md-12 text-right">
			            <button type="submit" class="btn btn-default">
			            	<i class="fa fa-send"></i>&nbsp;&nbsp;Kirim
			            </button>
			        </div>
				</div>
			</div>
            <hr/>
		    <div class="form-group">
		    </div>
		</form>
    </div>
</div>

<script type="text/javascript">
	var int_pencarian;
	function load_user()
	{
		$('#respon-user').html('');

		var dataForm = {
			sekolah_id		: $('select[name="sekolah_id"]').val(),
			jenis_penerima	: $('select[name="jenis_penerima"]').val(),
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

		$('#respon-user').html('Memuat Data ...');
		int_pencarian = setTimeout(function(){
			clearTimeout(int_pencarian);

			$.ajax({
				url 		: "<?=site_url('pesan_langsung/ajax_pencarian_user')?>",
				method		: "POST",
				data 		: dataForm,
				success		: function(result){
					$('#respon-user').html(result);
				}
			})		


		}, 500);
		return false;
	}

	function tambah_penerima(label, value, jenis, fcm)
	{
		if($('#respon-penerima #penerima_' + jenis + '_' + value).length > 0)
		{
			return false;
		}

		var str = `
			<li id="penerima_` + jenis + '_' + value + `" class="">
				<input type="hidden" name="penerima[]" value="` + value + `">
				<input type="hidden" name="jenis[]" value="` + jenis + `">
				<input type="hidden" name="fcm[]" value="` + fcm + `">
				<span class="text">` + label + `</span>
				<div class="tools">
					<i class="fa fa-trash-o" onclick="hapus_penerima(` + value + `, '` + jenis + `')"></i>
				</div>
			</li>
		`;
		$('#respon-penerima').append(str);
		if($('#respon-penerima').html() != '')
		{
			$('#respon-penerima').removeAttr('style');
		}
	}

	function hapus_penerima(id, jenis)
	{
		$('#respon-penerima #penerima_' + jenis + '_' + id).remove();
		if($('#respon-penerima').html() == '')
		{
			$('#respon-penerima').attr('style', 'display: none');
		}		
	}
</script>