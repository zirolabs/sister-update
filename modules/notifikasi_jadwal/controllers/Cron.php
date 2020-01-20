<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('notifikasi_jadwal_model');
		$this->load->model('pengaturan_kelas/pengaturan_kelas_model');
	}

	function index()
	{
		$notifikasi_id_list = array();
		$get_data = $this->notifikasi_jadwal_model->get_data_terjadwal()->result();
		foreach($get_data as $key => $c)
		{
			$isi_notifikasi	= substr($c->isi, 0, 250);
			if(!empty($c->fcm))				
			{
				$this->fcm->insertNotifikasiUser($c->user_id, $c->judul, $isi_notifikasi, $c->fcm);
			}
			
			if(!empty($c->no_hp))
			{
				$this->sms->insertNotifikasiUser($c->user_id, $isi_notifikasi);
			}

			if($c->target_wali == 'Y')
			{
				if(!empty($c->fcm_ortu))
				{
					$this->fcm->insertNotifikasiWali($c->user_id, $c->judul, $isi_notifikasi, $c->fcm_ortu);
				}
				
				if(!empty($c->no_hp_ortu))
				{
					$this->sms->insertNotifikasiWali($c->user_id, $isi_notifikasi);
				}
			}

			if(!in_array($c->notifikasi_id, $notifikasi_id_list))
			{
				$notifikasi_id_list[] = $c->notifikasi_id;
				$param_log = array(
					'notifikasi_id'	=> $c->notifikasi_id,
					'waktu'			=> date('Y-m-d H:i:s')
				);
				$this->notifikasi_jadwal_model->insert_log($param_log);
			}
		}

		// pre($notifikasi_id_list);


	}
}
