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

        <style type="text/css">
            .layout-boxed .wrapper
            {
                max-width: 950px;
            }
        </style>
        
        <script src="<?=base_url('vendor/almasaeed2010/adminlte/bower_components/jquery/dist/jquery.min.js')?>"></script>
        <script src="<?=base_url('vendor/almasaeed2010/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js')?>"></script>
    </head>
    <body class="skin-blue layout-top-nav layout-boxed">
        <div class="wrapper">
            <header class="main-header">
                <?=$this->load->view('templates_kantin/main_templates_navbar')?>
            </header>
            <div class="content-wrapper">
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
                    Copyright &copy; <?=date('Y')?> <a href="<?=site_url()?>"><?=$this->config->item('app_name')?></a>.
                </strong>
                All rights reserved.
            </footer>
            <div class="control-sidebar-bg"></div>
        </div>
        <script src="<?=base_url('vendor/almasaeed2010/adminlte/dist/js/adminlte.min.js')?>"></script>
    </body>
</html>
