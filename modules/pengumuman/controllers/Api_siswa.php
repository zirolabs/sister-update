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
		$this->load->model('pengumuman_model');
	}

	public function index()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Data tidak ditemukan.',
			'data'		=> array()
		);	

		$data_post = $this->input->post();
		$filter = array(
			'saya'			=> $this->login_uid, 
			'target_siswa'  => 'Y'
		);
		$get_data = $this->pengumuman_model->get_data($filter)->result();

		if(!empty($get_data))
		{
			$result = array();
			foreach($get_data as $key => $c)
			{
				if(!empty($c->gambar))
				{
					$c->gambar 	= base_url($c->gambar);
				}

            	$c->waktu 	= format_tanggal_indonesia($c->waktu, true);
				$result[] 	= $c;
			}

			$respon = array(
				'status'	=> '200',
				'msg'		=> 'Data ditemukan',
				'data'		=> $result
			);
		}

		echo json_encode($respon);
	}
}
