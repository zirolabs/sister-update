<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_wali extends CI_Controller 
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
		$this->cek_device = $this->login_model->get_data_wali_device($this->device_id);
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
		$this->load->model('mata_pelajaran_nilai_model');
		$this->load->model('manajemen_siswa/manajemen_siswa_model');
	}

	public function index()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Data tidak ditemukan.',
			'data'		=> array()
		);	

		$data_post = $this->input->post();
		if(!empty($data_post['kelas']) || !empty($data_post['semester']) || !empty($data_post['jenis']) || !empty($data_post['mata_pelajaran']))
		{
			$data_post['user_id'] = $this->login_uid;
			$get_data = $this->mata_pelajaran_nilai_model->get_data_v_siswa($data_post)->result();
			if(!empty($get_data))
			{
				$result = array();
				foreach($get_data as $key => $c)
				{
					$c->jenis = format_ucwords($c->jenis);
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
}
