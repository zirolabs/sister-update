<div class="box box-primary">
    <div class="box-header with-border">
    	<h3 class="box-title">
            <i class="fa fa-info-circle"></i>&nbsp;&nbsp;&nbsp;Pengumuman Saya
        </h3>
    </div>
    <div class="box-body">
        <form method="GET" action="<?=site_url('pengumuman/saya')?>">
        	<div class="row">
        		<div class="col-md-4">
					<div class="form-group" style="margin-bottom: 0;">
                        <div class="input-group">
                            <div class="input-group-control">
	                            <input type="text" class="form-control input" placeholder="Pencarian.." name="q" value="<?=$keyword?>">
                            </div>
                            <span class="input-group-btn btn-right">
                                <button class="btn btn-default" type="submit">Cari !</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
   		</form>
    </div>
</div>

<?php if(empty($data)){ echo info_msg('Tidak ada data.'); } else { ?>
    <?php foreach($data as $key => $c): ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?=$c->judul?></h3>
            </div>
            <div class="box-body">
                <p>Dibuat oleh <b><?=$c->nama_user?></b> pada <b><?=format_tanggal_indonesia($c->waktu, true)?></b></p>
                <div class="row">
                    <?php if(!empty($c->gambar)){ ?>
                        <div class="col-md-4">
                            <img src="<?=base_url($c->gambar)?>" class="img-responsive">
                        </div>
                    <?php } ?>
                    <div class="col-md-8">
                        <p><?=nl2br($c->isi)?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?=@$pagination?>
<?php } ?>

