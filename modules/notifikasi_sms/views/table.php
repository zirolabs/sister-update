<div class="nav-tabs-custom">
    <ul class="nav nav-tabs pull-right">
        <?php if(in_array($login_level, array('administrator'))){ ?>
            <li class="<?=@$sub_main_content == 'konfigurasi' ? 'active' : ''?>">
                <a href="<?=site_url('notifikasi_sms/konfigurasi')?>">Konfigurasi</a>
            </li>
            <li class="<?=@$sub_main_content == 'perangkat' ? 'active' : ''?>">
                <a href="<?=site_url('notifikasi_sms/perangkat')?>">Perangkat</a>
            </li>
        <?php } ?>
        <li class="<?=@$sub_main_content == 'log' ? 'active' : ''?>">
            <a href="<?=site_url('notifikasi_sms/index')?>">Log</a>
        </li>
        <li class="pull-left header">
            <i class="fa fa-bell"></i> Notifikasi SMS
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active">
            <?php 
                if(!empty($sub_main_content))
                {
                    $this->load->view($sub_main_content);
                }
            ?>
        </div>
    </div>
</div>