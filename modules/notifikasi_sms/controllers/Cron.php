<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('notifikasi_sms_model');
		$this->load->library('sms');
	}

	function index()
	{	
		$get_proses = $this->sms->getDB('proses')->result();
		if(!empty($get_proses))
		{
			foreach($get_proses as $key => $c)
			{
				if(empty($c->sms_id_api))
				{
					continue;
				}

				$proses = $this->sms->getMessageInfo($c->sms_id_api);
				if($proses['status'] == 'sukses')
				{
					if($proses['data']['status'] == 'sent')
					{
						$param_update = array('status'	=> 'terkirim');
						$this->sms->updateDB($param_update, $c->sms_id);

					}
					elseif($proses['data']['status'] == 'failed')
					{
						$param_update = array(
							'gagal_ke'	=> $c->gagal_ke + 1,
							'status'	=> 'pending'
						);
						
						if($c->gagal_ke >= 3)
						{
							$param_update = array('status'	=> 'gagal');
						}
						$this->sms->updateDB($param_update, $c->sms_id);
					}
				}
			}
		}

		/* Kirim SMS */
		$get_antrian = $this->sms->getDB('pending')->result();
		$data_sms = array();
		foreach($get_antrian as $key => $c)
		{
			if(count($data_sms) >= $c->sisa_sms)
			{
				break;
			}

			$data_sms[] = array(
				'id'			=> $c->sms_id,
				'deviceId'		=> $c->device_id,
				'message'		=> $c->pesan,
				'phoneNumber'	=> $c->target
			);
		}

		if(!empty($data_sms))
		{
			$proses = $this->sms->sendMessage($data_sms);
			if($proses['status'] == 'sukses')
			{
				foreach($proses['data'] as $key => $c)
				{
					if(empty($c['id']))
					{
						continue;
					}

					$param_update = array('sms_id_api'	=> $c['sms_id_api']);
					if($c['status'] == 'pending')
					{
						$param_update['status'] 	  = 'proses';
						$param_update['waktu_proses'] = date('Y-m-d H:i:s');
					}
					$this->sms->updateDB($param_update, $c['id']);
				}
			}
		}
	}

}
