<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_guru extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->device_id 		= $this->input->post('device_id');
		if(empty($this->device_id))
		{
			$this->device_id = $this->input->get('device_id');
		}

		$this->load->model('login/login_model');
		$this->cek_device = $this->login_model->get_data_guru_device($this->device_id);
		if(empty($this->cek_device))
		{
			$respon = array(
				'status'	=> '201',
				'msg'		=> 'Gagal',
				'data'		=> 'Autentikasi Gagal.'
			);	
			echo json_encode($respon);
			exit;
		}

		$this->login_uid	= $this->cek_device->user_id;		
		$this->load->model('notifikasi_fcm_model');
	}

	public function index()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Tidak ada data.',
			'data'		=> array()
		);	

		$param_notifikasi = array(
			'user_id' 		=> $this->login_uid,
			'baca'			=> 'N',
			'target_user'	=> 'user',
		);
		$get_data = $this->notifikasi_fcm_model->get_data($param_notifikasi)->result();
		if(!empty($get_data))
		{
			$result = array();
			foreach($get_data as $key => $c)
			{
				$c->waktu = format_tanggal_indonesia($c->waktu, true);
				unset($c->user_id);
				unset($c->target);
				$result[] = $c;
			}
			$respon = array(
				'status'	=> '200',
				'msg'		=> 'Jadwal ditemukan',
				'data'		=> $result
			);					
		}

		echo json_encode($respon);
	}

	public function update_baca()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Gagal update data.',
			'data'		=> array()
		);	

		$data_post = $this->input->post();
		if(!empty($data_post['fcm_id']))
		{
			$param_notifikasi = array('baca' => 'Y');
			$proses = $this->notifikasi_fcm_model->update_data($param_notifikasi, $data_post['fcm_id']);
			if($proses)
			{
				$respon = array(
					'status'	=> '200',
					'msg'		=> 'Berhasil update data.',
					'data'		=> array()
				);					
			}
		}

		echo json_encode($respon);		
	}
}
