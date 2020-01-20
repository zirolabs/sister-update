<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_siswa extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		// $this->device_id 		= $this->input->post('device_id');
		// if(empty($this->device_id))
		// {
		// 	$this->device_id = $this->input->get('device_id');
		// }

		// $this->load->model('login/login_model');
		// $this->cek_device = $this->login_model->get_data_siswa_device($this->device_id);
		// if(empty($this->cek_device))
		// {
		// 	$respon = array(
		// 		'status'	=> '201',
		// 		'msg'		=> 'Gagal',
		// 		'data'		=> 'Autentikasi Gagal.'
		// 	);	
		// 	echo json_encode($respon);
		// 	exit;
		// }

		// $this->login_uid	= $this->cek_device->user_id;		
		$this->load->model('login/login_model');
		$this->load->model('monitoring_model');
	}

	function submit()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Syncronize data gagal',
			'data'		=> array()
		);

		$data_post = file_get_contents('php://input');
		if(!empty($data_post))
		{
			$user_id = '';
			$data_post = json_decode($data_post);
			foreach($data_post as $key => $c)
			{
				if(empty($user_id))
				{
					$user_id = $this->login_model->get_data_siswa_device($c->device_id)->user_id;			  		
				file_put_contents('logs/lokasi_terakhir_' . $user_id . '.txt', print_r($data_post, true));
				}

				if(empty($user_id))
				{
					continue;
				}

				$param = array(
					'lokasi_latitude'	=> $c->lat,
					'lokasi_longitude'	=> $c->lon,
					'lokasi_waktu'		=> date('Y-m-d H:i:s')
				);
				$proses = $this->monitoring_model->update($param, $user_id);
				if($proses)
				{
					file_put_contents('logs/lokasi_terakhir_' . $user_id . '.txt', print_r($param, true));
				}
			}
		}
		echo json_encode($respon);
	}
}
