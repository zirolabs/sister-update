<a href="<?=site_url()?>" class="logo">
    <span class="logo-mini">S<b>A</b></span>
    <span class="logo-lg">SIS<b>TER</b></span>
</a>
<nav class="navbar navbar-static-top" role="navigation">
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </a>
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="<?=default_foto_user($data_agen->foto)?>" class="user-image" alt="<?=$data_agen->nama?>">
                    <span class="hidden-xs"><?=$data_agen->nama?></span>
                </a>
                <ul class="dropdown-menu">
                    <li class="user-header">
                        <img src="<?=default_foto_user($data_agen->foto)?>" class="img-circle" alt="<?=$data_agen->nama?>">
                        <p>
                            <b><?=$data_agen->nama?></b><br/>
                            <small><?=$data_agen->email?></small>
                            <small>Terakhir Login : <strong><?=format_tanggal($data_agen->terakhir_login, true)?></strong></small>
                        </p>
                    </li>
                    <li class="user-footer">
                        <div class="pull-left">
                            <a href="<?=site_url('profil')?>" class="btn btn-default btn-flat">Profil</a>
                        </div>
                        <div class="pull-right">
                            <a href="<?=site_url('logout')?>" class="btn btn-default btn-flat">Logout</a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
