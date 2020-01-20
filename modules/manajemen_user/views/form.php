<div class="row">
	<div class="col-md-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-users"></i>&nbsp;&nbsp;&nbsp;Manajemen Administrator
                </h3>
            </div>
            <div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('manajemen_user')?>" class="btn btn-default">
							<i class="fa fa-history"></i> Kembali
						</a>
					</div>
				</div>
				<hr/>
				<form class="form-horizontal" method="POST" action="<?=site_url('manajemen_user/submit/' . $id)?>" enctype="multipart/form-data">
					<div class="form-group">
				        <label class="col-md-4 control-label">Nama</label>
				        <div class="col-md-6">
				            <input type="text" class="form-control" name="nama" value="<?=@$data->nama?>">
				        </div>
				    </div>
				    <div class="form-group">
				        <label class="col-md-4 control-label">Username</label>
				        <div class="col-md-6">
				            <input type="text" class="form-control" name="username" value="<?=@$data->username?>" <?=!empty($data->username) && !empty($id) ? "readonly" : ""?>>
				        </div>
				    </div>
				    <div class="form-group">
				        <label class="col-md-4 control-label">Email</label>
				        <div class="col-md-6">
				            <input type="email" class="form-control" name="email" value="<?=@$data->email?>" <?=!empty($data->email) && !empty($id) ? "readonly" : ""?>>
				        </div>
				    </div>
				    <div class="form-group">
				        <label class="col-md-4 control-label">Password</label>
				        <div class="col-md-6">
				            <input type="password" class="form-control" name="password" value="">
							<?php if(!empty($id)): ?>
								<span class="help-block">Kosongkan jika password tidak ingin diganti.</span>
							<?php endif; ?>
				        </div>
				    </div>
				    <div class="form-group">
				        <label class="col-md-4 control-label">No Handphone</label>
				        <div class="col-md-6">
				            <input type="text" class="form-control" name="no_hp" value="<?=@$data->no_hp?>">
				        </div>
				    </div>
				    <div class="form-group">
				        <label class="col-md-4 control-label">Status</label>
				        <div class="col-md-6">
				        	<?=form_dropdown('status', array('aktif' => 'Aktif', 'blokir' => 'Blokir'), @$data->status, 'class="form-control"')?>
							<div class="form-control-focus"> </div>
				        </div>
				    </div>
                    <hr/>
				    <div class="form-group">
				        <div class="col-md-12 text-right">
				            <button type="submit" class="btn btn-default">
				            	<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
				            </button>
				            <a href="<?=site_url('manajemen_user')?>" class="btn btn-default">
				            	<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
				            </a>
				        </div>
				    </div>
				</form>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?=base_url('vendor/eternicode/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css')?>">
<script src="<?=base_url('vendor/eternicode/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')?>"></script>
<script src="<?=base_url('vendor/eternicode/bootstrap-datepicker/dist/locales/bootstrap-datepicker.id.min.js')?>"></script>

<script type="text/javascript">
    $("#tgl_lahir").datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        language : 'id',
    });
    function countChar(val)
    {
        var len = val.value.length;
        if (len > 250)
        {
            val.value = val.value.substring(0, 250);
        }
        else
        {
            $('#charNum').text('Sisa ' + (250 - len) + ' Karakter');
        }
    };

    function get_kabupaten_kota(val)
    {
        $.ajax({
            url     : "<?=site_url('manajemen_user/get_opt_kabupaten_kota')?>/" + val,
            data    : "selected=<?=@$data->id_kabupaten_kota?>",
            method  : "GET",
            success : function(result)
            {
                $('#kabupaten_kota_area').html(result);
                get_kecamatan($('#id_kabupaten_kota').val());
            }
        });
    }

    function get_kecamatan(val)
    {
        $.ajax({
            url     : "<?=site_url('manajemen_user/get_opt_kecamatan')?>/" + val,
            method  : "GET",
            data    : "selected=<?=@$data->id_kecamatan?>",
            success : function(result)
            {
                $('#kecamatan_area').html(result);
                get_desa($('#id_kecamatan').val());
            }
        });
    }

    function get_desa(val)
    {
        $.ajax({
            url     : "<?=site_url('manajemen_user/get_opt_desa')?>/" + val,
            method  : "GET",
            data    : "selected=<?=@$data->id_desa?>",
            success : function(result)
            {
                $('#desa_area').html(result);
            }
        });
    }

    get_kabupaten_kota($('#id_provinsi').val());
</script>
