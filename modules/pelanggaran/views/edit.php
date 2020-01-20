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
				        <label class="col-md-2 control-label">Tanggal *</label>
				        <div class="col-md-8">
							 <input name="tanggal" placeholder="Tanggal Pelanggaran" id="tanggal" class='form-control' type="date" value="<?=@$data->tanggal_pelanggaran?>"></input>
						</div>
				    </div>
					
					<div class="form-group">
				        <label class="col-md-2 control-label">Tindak Lanjut *</label>
				        <div class="col-md-8">
							<textarea class="form-control" name="tindak_lanjut" rows="2"><?=@$data->tindak_lanjut?></textarea>
						</div>
				    </div>
					<div class="form-group">
				        <label class="col-md-2 control-label">Keterangan *</label>
				        <div class="col-md-8">
							<?=form_dropdown('keterangan', array('Teratasi' => 'Teratasi','Proses' => 'Proses','Belum Teratasi' => 'Belum Teratasi'),$data->keterangan, 'class="form-control"')?>
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