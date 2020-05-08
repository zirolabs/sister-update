<div class="row">
	<div class="col-md-8 col-md-offset-2">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Mata Pelajaran
                </h3>
            </div>
            <div class="box-body">
                <h3>Tambah Mata Pelajaran</h3>
                <?php echo validation_errors(); ?>
                <form method="POST" action="<?=site_url('master_mata_pelajaran/tambah_mapel')?>" enctype="multipart/form-data">
                    <div class="row">
				        <div class="col-md-8">
                            <input type="text" class="form-control" name="nama" placeholder="Silahkan cek mata pelajaran dibawah terlebih dahulu">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-default">
				            	<i class="fa fa-save"></i>&nbsp;&nbsp;Simpan
				            </button>
				        </div>
				    </div>
                </form>
                <hr>
                
                <h1>Daftar Mata Pelajaran</h1>
                <form  method="POST" action="<?=site_url('master_mata_pelajaran/update_mapel')?>" enctype="multipart/form-data">
                    <?php 
                        $chkbox=$sekolah;
                        $arr=explode(",",$chkbox);
                        foreach($data as $value) { ?>  
                            <?= ($value->mata_pelajaran_id == $sekolah)? '':''?>
                            <input <?php if(in_array($value->mata_pelajaran_id,$arr)){echo "checked";}?> type="checkbox" name="mata_pelajaran_id[]" value="<?php echo $value->mata_pelajaran_id;?>"> <?php echo $value->nama ?><br>
                    <?php } ?>
                    <input type="hidden" name="sekolah_id" value="<?= $sekolah_id ?>">
                    <div class="form-group">
				        <div class="col-md-12 text-center">
				            <button type="submit" class="btn btn-default">
				            	<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
				            </button>
				        </div>
				    </div>
                    <br>
				<hr>
                <br>
                </form>
            </div>
        </div>
    </div>
</div>
