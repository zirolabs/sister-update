<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifikasi_fcm extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$get_antrian = $this->fcm->getAntrianDB()->result();
		foreach($get_antrian as $key => $c)
		{

			$data_fcm 	= array(
				'notification' 	=> array(
					'title'			=> $c->judul,
					'body' 			=> $c->pesan,
				),
			);
			$proses = $this->fcm->sendMessage($data_fcm, $c->target);

			if(!empty($proses))
			{
				$proses = json_decode($proses);
				if(@$proses->success == 1)
				{
					$this->fcm->updateDB(array('status' => 'terkirim'), $c->fcm_id);
				}
				else
				{
					$this->fcm->updateDB(array('status' => 'gagal'), $c->fcm_id);					
				}
			}
			else
			{
				$this->fcm->updateDB(array('status' => 'gagal'), $c->fcm_id);									
			}
		}
	}
}
