<form class="form-horizontal" method="POST" action="<?=site_url('pelanggaran/submit/'. $id)?>">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="box box-primary">
			    <div class="box-header with-border">
			        <h3 class="box-title">
			            <i class="fa fa-tag"></i>&nbsp;&nbsp;&nbsp;Data Kejadian 
			        </h3>
			    </div>
			    <div class="box-body">
					<div class="row">
						<div class="col-md-12">
							<a href="<?=site_url('pelanggaran')?>" class="btn btn-default">
								<i class="fa fa-history"></i> Kembali
							</a>
						</div>
					</div>
					<hr/>
					<div class="form-group">
				        <label class="col-md-2 control-label">Nama *</label>
				        <div class="col-md-8">
							<input name="nama_siswa" id="autocomplete" class="autocomplete form-control" type="text">
						</div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">NIS *</label>
				        <div class="col-md-8">
							<input name="nis" placeholder="nis" id="nis" class="form-control" readonly></input>
						</div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Kelas *</label>
				        <div class="col-md-8">
							 <input name="kelas" placeholder="Kelas" id="kelas" class='form-control' type="text" readonly></input>
						</div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Sekolah *</label>
				        <div class="col-md-8">
							 <input name="sekolah" placeholder="Sekolah" id="sekolah" class='form-control' type="text" readonly></input>
						</div>
				    </div>
					<div class="form-group">
				        <div class="col-md-8">
							 <input name="sekolah_id" placeholder="Sekolah ID" id="sekolah_id" class='form-control' type="hidden" readonly></input>
						</div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Tanggal *</label>
				        <div class="col-md-8">
							 <input name="tanggal" placeholder="Tanggal Pelanggaran" id="tanggal" class='form-control' type="date" value="<?php echo date('Y-m-d');?>"></input>
						</div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Kejadian *</label>
				        <div class="col-md-8">
							<?=form_dropdown('kategori', $opt_kategori, @$data->id_kategori, 'class="form-control" onchange="get_subkategori(this.value)"')?>
						</div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label"></label>
				        <div class="col-md-8" id="subkategori">
				            <?=form_dropdown('subkategori', array('' => 'Pilih Sub Kategori'), '', 'class="form-control" onchange="get_point(this.value)"')?>
				        </div>
				    </div>
					<div class="form-group">
						<label class="col-md-2 control-label"></label>
				        <div class="col-md-8">
				            <input type="number" class="form-control" name="point" value="" id="point" readonly>
				        </div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Tindak Lanjut *</label>
				        <div class="col-md-8">
							<textarea class="form-control" name="tindak_lanjut" rows="2"></textarea>
						</div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Keterangan *</label>
				        <div class="col-md-8">
							<?=form_dropdown('keterangan', array('Teratasi' => 'Teratasi','Proses' => 'Proses','Belum Teratasi' => 'Belum Teratasi'), '', 'class="form-control"')?>
						</div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">
							Guru *<br />
							<a id="dataguru">Tampilkan Data Guru</a>	
						</label>
				        <div class="col-md-8" id="guru">
							<?=form_dropdown('guru', array('' => 'Pilih Guru'), '', 'class="form-control"')?>
						</div>
				    </div>
					<hr/>
				    <div class="form-group">
				        <div class="col-md-12 text-right">
				            <button type="submit" class="btn btn-default">
				            	<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
				            </button>
				            <a href="<?=site_url('pelanggaran')?>" class="btn btn-default">
				            	<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
				            </a>
				        </div>
				    </div>
				</div>
			</div>
		</div>
	</div>
</form>
<script type='text/javascript' src='<?php echo base_url();?>assets/js/jquery.autocomplete.js'></script>
<link href='<?php echo base_url();?>assets/js/jquery.autocomplete.css' rel='stylesheet' />
<script type="text/javascript">
    function get_subkategori(id)
    {
		$.ajax({
            url     : "<?=site_url('pelanggaran/get_subkategori?selected=' . @$subkategori . '&id_kategori=')?>" + id,
            method  : 'GET',
            success : function(result){
                $('#subkategori').html(result);
            }

        });
    }
    get_subkategori($('select[name="kategori"]').val());    
	
	function get_point(id)
    {
		$.ajax({
            url     : "<?=site_url('pelanggaran/get_point?id_subkategori=')?>" + id,
            method  : 'GET',
            success : function(result){
                $('#point').val(result);
			}

        });
    }
    get_point($('select[name="subkategori"]').val());  
	
	$("#dataguru").click(function(){
		var id = $('#sekolah_id').val();
		$.ajax({
            url     : "<?=site_url('pelanggaran/get_guru?sekolah_id=')?>" + id,
            method  : 'GET',
            success : function(result){
                $('#guru').html(result);
			}

        });
	}); 
	
	var site = "<?php echo site_url();?>";
	$(function(){
            $('#autocomplete').autocomplete({
                // serviceUrl berisi URL ke controller/fungsi yang menangani request kita
                serviceUrl: site+'/pelanggaran/search',
                // fungsi ini akan dijalankan ketika user memilih salah satu hasil request
                onSelect: function (suggestion) {
                    $('#nis').val(''+suggestion.nis); // membuat id 'v_nim' untuk ditampilkan
                    $('#kelas').val(''+suggestion.kelas); // membuat id 'v_jurusan' untuk ditampilkan
					$('#sekolah').val(''+suggestion.sekolah);
					$('#sekolah_id').val(''+suggestion.sekolah_id);
					$('#guru').empty();
				}
            });
    });
</script>