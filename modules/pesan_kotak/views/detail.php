<a href="<?=site_url('pesan_kotak')?>" class="btn btn-default" style="margin-bottom: 5px;">
    <i class="fa fa-history"></i> Kembali
</a>
<div class="box box-primary direct-chat direct-chat-warning">
    <div class="box-header with-border">
        <h3 class="box-title">Riwayat Pesan</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <div class="user-block" style="padding: 5px;">
                    <img class="img-circle img-bordered-sm" src="<?=default_foto_user($kamu->foto)?>">
                    <span class="username">
                        <a href="#"><?=$kamu->nama?></a> 
                        <?=($data->user_id_1 == $login_uid) ? ' sebagai ' . format_ucwords($kamu->target) : ''?>
                    </span>
                    <span class="description">Kontak : <?=$kamu->email?> / <?=$kamu->no_hp?></span>
                </div>
            </div>
        </div>
        <hr style="margin-top: 0;" />
        <div class="direct-chat-messages" style="min-height: 550px; overflow-x: hidden;">
            <?php foreach($detail as $key => $c): ?>
                <div class="row">
                    <?php if($c->user_id == $login_uid){ ?>
                        <div class="col-md-6 col-md-offset-6">
                            <div class="direct-chat-msg right">
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name pull-left"><?=$saya->nama?></span>
                                    <span class="direct-chat-timestamp pull-right"><?=waktu_berlalu($c->waktu_kirim, true)?></span>
                                </div>
                                <img class="direct-chat-img" src="<?=default_foto_user($saya->foto)?>">
                                <div class="direct-chat-text">
                                    <?php if(!empty($c->gambar)){ ?>
                                        <img src="<?=load_gambar($c->gambar)?>" class="img-responsive"><br/>
                                    <?php } ?>
                                    <p><?=$c->isi?></p>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="col-md-6">
                            <div class="direct-chat-msg">
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name pull-left"><?=$kamu->nama?></span>
                                    <span class="direct-chat-timestamp pull-right"><?=waktu_berlalu($c->waktu_kirim, true)?></span>
                                </div>
                                <img class="direct-chat-img" src="<?=default_foto_user($kamu->foto)?>">
                                <div class="direct-chat-text">
                                    <?php if(!empty($c->gambar)){ ?>
                                        <img src="<?=load_gambar($c->gambar)?>" class="img-responsive"><br/>
                                    <?php } ?>
                                    <p><?=$c->isi?></p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="box-footer">
        <form action="<?=site_url('pesan_kotak/kirim/' . $pesan_id)?>" method="POST" autocomplete="off" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><i class="fa fa-image"></i>&nbsp;&nbsp;&nbsp;Tambahkan Foto</label>
                        <input type="file" class="form-control" name="userfiles">
                    </div>
                </div>
                <div class="col-md-8">
                    <label><i class="fa fa-edit"></i>&nbsp;&nbsp;&nbsp;Isi Pesan</label>
                    <div class="input-group">
                        <input type="hidden" name="fcm" value="<?=$kamu->fcm?>">
                        <input type="hidden" name="penerima" value="<?=$kamu->user_id?>">
                        <input type="hidden" name="target" value="<?=$kamu->target?>">
                        <input type="text" name="isi" placeholder="Tulis Pesan ..." class="form-control">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default">
                                <i class="fa fa-send"></i> Kirim
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </form> 
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.direct-chat-messages').animate({
            scrollTop   : $('.direct-chat-messages').get(0).scrollHeight
        }, 2000);
    });    
</script>