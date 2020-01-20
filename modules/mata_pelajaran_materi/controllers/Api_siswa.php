<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_siswa extends CI_Controller 
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
		$this->cek_device = $this->login_model->get_data_siswa_device($this->device_id);
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
		$this->load->model('mata_pelajaran_materi_model');
	}

	public function index()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Data tidak ditemukan.',
			'data'		=> array()
		);	

		$data_post = $this->input->post();
		if(!empty($data_post['kelas']) || !empty($data_post['mata_pelajaran']))
		{
			$get_data = $this->mata_pelajaran_materi_model->get_data($data_post)->result();
			if(!empty($get_data))
			{
				$result = array();
				foreach($get_data as $key => $c)
				{
					unset($c->mata_pelajaran_id);
					unset($c->sekolah_id);
					unset($c->user_id);
					if(!empty($c->lokasi_file))
					{
						$c->lokasi_file = base_url($c->lokasi_file);
					}
					$c->waktu_upload = format_tanggal_indonesia($c->waktu_upload, true);
					$result[] = $c;
				}
				$respon = array(
					'status'	=> '200',
					'msg'		=> 'Data ditemukan.',
					'data'		=> $result
				);					
			}
		}
		else
		{
			$respon = array(
				'status'	=> '201',
				'msg'		=> 'Parameter tidak valid.',
				'data'		=> array()
			);				
		}

		echo json_encode($respon);
	}

	public function kelas()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Data tidak ditemukan.',
			'data'		=> array()
		);				

		$data_post = $this->input->post();
		if(!empty($data_post['materi_id']))
		{
			$get_data = $this->mata_pelajaran_materi_model->get_kelas($data_post['materi_id']);
			if(!empty($get_data))
			{
				$respon = array(
					'status'	=> '200',
					'msg'		=> 'Data ditemukan.',
					'data'		=> $get_data
				);								
			}
		}

		echo json_encode($respon);
	}
}
