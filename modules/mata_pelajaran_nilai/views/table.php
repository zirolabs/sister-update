<div class="box box-primary">
    <div class="box-header with-border">
    	<h3 class="box-title">
            <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Nilai Mata Pelajaran
        </h3>
    </div>
    <div class="box-body">
        <form method="GET" action="<?=site_url('mata_pelajaran_nilai')?>">
        	<div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Sekolah *</label>
                        <?=form_dropdown('sekolah', $opt_sekolah, $sekolah, 'class="form-control" onchange="get_kelas()"')?>
                    </div>                    
                    <div class="form-group">
                        <label>Kelas *</label>
                        <div id="area_kelas">
                            <?=form_dropdown('kelas', array('' => 'Semua Kelas'), '', 'class="form-control"')?>
                        </div>
                    </div>                    
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Semester</label>
                        <?=form_dropdown('semester', $opt_semester, $semester, 'class="form-control"')?>
                    </div>                    
                    <div class="form-group">
                        <label>Jenis</label>
                        <?=form_dropdown('jenis', $opt_jenis, $jenis, 'class="form-control"')?>
                    </div>                    
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Mata Pelajaran *</label>
                        <?=form_dropdown('mata_pelajaran', $opt_mata_pelajaran, $mata_pelajaran, 'class="form-control"')?>
                    </div>                    
                    <div class="form-group" style="margin-bottom: 0px;">
                        <label>&nbsp;</label>
                        <button class="btn btn-primary btn-block" type="submit">
                            <i class="fa fa-filter"></i> Submit
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <hr/>
        <?php if(empty($sekolah) || empty($kelas)){ ?>
            <?=info_msg('Pilih sekolah, kelas dan mata pelajaran terlebih dahulu.')?>
        <?php } else { ?>
            <?php if($login_level == 'guru'){ ?>
                <a href="<?=site_url('mata_pelajaran_nilai/form?' . $url_param)?>" class="btn btn-default" style="margin-bottom: 5px;">
                    <i class="fa fa-plus hidden-xs"></i> Input Nilai
                </a>
                <hr style="margin: 5px 0;"/>
            <?php } ?>
            <?php if(!empty($data)){ ?>
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" style="vertical-align: middle;" rowspan="2">NIS</th>
                            <th class="text-center" style="vertical-align: middle;" rowspan="2">Nama</th>
                            <th class="text-center" style="vertical-align: middle;" colspan="<?=in_array($jenis, array('tugas', 'ulangan')) ? count($list_nilai) : ''?>">Nilai</th>                        
                        </tr>
                        <tr>
                            <?php $x = 1; foreach($list_nilai as $key => $c): ?>
                                <th class="text-center">
                                    <?=format_ucwords($jenis) . ' ' . $c['keterangan']?>
                                    <?php if($login_level == 'guru'){ ?>
                                        <a href="<?=site_url('mata_pelajaran_nilai/form/' . $key . '?' . $url_param)?>" class="btn btn-xs btn-default">
                                            <i class="fa fa-edit"></i>
                                        </a>                                        
                                        <a onclick="confirm_hapus('<?=$key?>')" class="btn btn-default btn-xs" title="Hapus">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    <?php } ?>
                                </th>
                            <?php $x++; endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data as $key => $c): ?>
                            <tr>
                                <td class="text-center"><?=$c['nis']?></td>
                                <td><?=$c['nama']?></td>
                                <?php foreach($list_nilai as $kex => $x): ?>
                                    <td class="text-center"><?=!empty($c['detail'][$x['id']]) ? $c['detail'][$x['id']] : 0?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <?=info_msg('Tidak ada nilai.')?>
            <?php } ?>
        <?php } ?>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-hapus">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                	<span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                	<i class="fa fa-info-circle"></i>&nbsp;&nbsp;Hapus Data
                </h4>
            </div>
            <div class="modal-body">
            	<h4>Apakah Anda yakin ? </h4>
            </div>
            <div class="modal-footer">
            	<a href="<?=site_url('mata_pelajaran_nilai')?>" id="btn-yes" class="btn btn-default">
            		<i class="fa fa-check"></i> Ya, Saya Yakin
            	</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                	<i class="fa fa-times"></i> Tidak
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	function confirm_hapus(id)
	{
		$('#modal-hapus #btn-yes').attr({'href' : '<?=site_url('mata_pelajaran_nilai/hapus')?>/' + id + '?<?=$url_param?>'});
		$('#modal-hapus').modal();
	}

    function get_kelas()
    {
        $.ajax({
            url     : "<?=site_url('mata_pelajaran_nilai/ajax_kelas?selected=' . @$kelas . '&sekolah_id=')?>" + $('select[name="sekolah"]').val(),
            method  : 'GET',
            success : function(result){
                $('#area_kelas').html(result);
            }

        });
    }    

    $(document).ready(function(){
        get_kelas();
    });    
</script>
