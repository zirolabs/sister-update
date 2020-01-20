<?php if(!empty($pengumuman)){ ?>
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Pengumuman</h3>
            </div>
            <div class="box-body">
                <?php if(!empty($pengumuman['data'])){ ?>
                    <?php foreach($pengumuman['data'] as $key => $c): ?>
                        <div class="box box-widget">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?=$c->judul?></h3>
                            </div>
                            <div class="box-body">
                                <p>
                                    Dibuat oleh <strong><?=$c->nama_user?></strong>, 
                                    pada <strong><?=format_tanggal_indonesia($c->waktu, true)?></strong>
                                </p>
                                <div class="row">
                                    <?php if(!empty($c->gambar)){ ?>
                                        <div class="col-md-4">
                                            <img class="img-responsive pad" src="<?=base_url($c->gambar)?>">
                                        </div>
                                    <?php } ?>
                                    <div class="col-md-8">
                                        <p><?=nl2br($c->isi)?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php } else { ?>
                    <?=info_msg('Tidak ada pengumuman.')?>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>