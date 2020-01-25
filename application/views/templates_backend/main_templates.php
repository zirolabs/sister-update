<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?=!empty($meta_title) ? $meta_title . ' - ' : ''?><?=$this->config->item('app_name')?> </title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="<?=base_url('vendor/fortawesome/font-awesome/css/font-awesome.css')?>">
        <link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css')?>">
        <link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte/bower_components/font-awesome/css/font-awesome.min.css')?>">
        <link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte/bower_components/Ionicons/css/ionicons.min.css')?>">
        <link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte/bower_components/select2/dist/css/select2.min.css')?>">
        <link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte/dist/css/AdminLTE.min.css')?>">
        <link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte/dist/css/skins/_all-skins.min.css')?>">
        <link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte/plugins/iCheck/square/blue.css')?>">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        
        <script src="<?=base_url('vendor/almasaeed2010/adminlte/bower_components/jquery/dist/jquery.min.js')?>"></script>
        <script src="<?=base_url('vendor/almasaeed2010/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js')?>"></script>
        <script src="<?=base_url('vendor/almasaeed2010/adminlte/bower_components/qrcode_js/qrcode.js')?>"></script>
    </head>
    <!--
        BODY TAG OPTIONS:
        =================
        Apply one or more of the following classes to get the
        desired effect
        |---------------------------------------------------------|
        | SKINS         | skin-blue                               |
        |               | skin-black                              |
        |               | skin-purple                             |
        |               | skin-yellow                             |
        |               | skin-red                                |
        |               | skin-green                              |
        |---------------------------------------------------------|
        |LAYOUT OPTIONS | fixed                                   |
        |               | layout-boxed                            |
        |               | layout-top-nav                          |
        |               | sidebar-collapse                        |
        |               | sidebar-mini                            |
        |---------------------------------------------------------|
    -->
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <?=$this->load->view('templates_backend/main_templates_navbar')?>
            </header>
            <aside class="main-sidebar">
                <?=$this->load->view('templates_backend/main_templates_sidebar')?>
            </aside>
            <div class="content-wrapper">
                <section class="content-header">
                    <?php if(!empty($page_title)){ ?>
                        <h1>
                            <?=$page_title?>
                            <?php if(!empty($sub_page_title)){ ?>
                                <small><?=$sub_page_title?></small>
                            <?php } ?>
                        </h1>
                    <?php } ?>
                    <?php if(!empty($breadcrumb)){ ?>
                        <ol class="breadcrumb">
                            <?php foreach($breadcrumb as $key => $c): ?>
                                <?php if(!empty($c['link'])){ ?>
                                    <li>
                                        <a href="<?=$c['link']?>?>"><?=$c['label']?></a>
                                    </li>
                                <?php } else { ?>
                                    <li class="active"><?=$c['label']?></li>
                                <?php } ?>
                            <?php endforeach; ?>
                        </ol>
                    <?php } ?>
                </section>
                <section class="content">
                    <?php if(!empty($msg)){ echo $msg; }?>
                    <?php
                        if(!empty($main_content))
                        {
                            $this->load->view($main_content);
                        }
                    ?>
                </section>
            </div>
            <footer class="main-footer">
                <strong>
                    Copyright &copy; <?=date('Y')?>
                    <a href="<?=site_url()?>"><?=$this->config->item('app_name')?></a>.
                </strong>
                All rights reserved.
            </footer>
            <div class="control-sidebar-bg"></div>
        </div>
        <script src="<?=base_url('vendor/almasaeed2010/adminlte/dist/js/adminlte.min.js')?>"></script>
    </body>
</html>
