<?php
	$active_config 	= 'kantin';
	$require_files 	= array('config.php', 'codeigniter.php');

	foreach($require_files as $key => $c)
	{
		if(!file_exists($c))
		{
			echo "Error, File Core $c tidak ditemukan!!";
			exit;
		}

		require_once($c);
		if($c == 'config.php')
		{
			foreach($my_config[$active_config] as $key => $c)
			{
				define('MY_CONFIG_' . strtoupper($key), $c);
			}
		}
	}
// header("location:panel.php");
// require_once('vendor/codeigniter/framework/index.php');
