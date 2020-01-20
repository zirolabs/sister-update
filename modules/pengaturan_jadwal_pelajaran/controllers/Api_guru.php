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
		$this->load->model('pengaturan_jadwal_pelajaran_model');
	}

	public function index()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Tidak ada jadwal.',
			'data'		=> array()
		);	

		$data_post = $this->input->post();
		if(!empty($data_post['tanggal']))
		{
			$param_jadwal_pelajaran = array(
				'user_id'	=> $this->login_uid,
				'hari'		=> date('w', strtotime($data_post['tanggal']))
			);
			$get_data = $this->pengaturan_jadwal_pelajaran_model->get_data($param_jadwal_pelajaran)->result();
			if(!empty($get_data))
			{
				$result = array();
				foreach($get_data as $key => $c)
				{
					unset($c->jadwal_id);
					unset($c->kelas_id);
					unset($c->mata_pelajaran_id);
					unset($c->sekolah_id);
					unset($c->user_id);
					$c->hari = hari_indonesia($c->hari);
					$result[] = $c;
				}
				$respon = array(
					'status'	=> '200',
					'msg'		=> 'Jadwal ditemukan',
					'data'		=> $get_data
				);					
			}
		}

		echo json_encode($respon);
	}
}
