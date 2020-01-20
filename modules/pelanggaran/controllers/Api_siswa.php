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
		$this->load->model('pelanggaran_model');
	}

	public function index()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Tidak ada data.',
			'data'		=> array('total' => 0)
		);	

		$get_data = $this->pelanggaran_model->get_total_poin($this->cek_device->nis);
		if(!empty($get_data))
		{
			$respon = array(
				'status'	=> '200',
				'msg'		=> 'Data ditemukan',
				'data'		=> array('total' => $get_data)
			);					
		}

		echo json_encode($respon);
	}

	public function detail()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Tidak ada data.',
			'data'		=> array()
		);	

		$param = array(
			'nis'		=> $this->cek_device->nis,
			'offset'	=> $this->input->post('offset'),
			'limit'		=> 10
		);
		$get_data = $this->pelanggaran_model->get_data($param)->result();
		if(!empty($get_data))
		{
			$result = array();
			foreach($get_data as $key => $c)
			{
				$result[] = array(
					'tanggal'			=> format_tanggal_indonesia($c->tanggal_pelanggaran),
					'poin'				=> $c->point_pelanggaran,
					'tindak_lanjut'		=> $c->tindak_lanjut,
					'deksripsi'			=> $c->deskripsi_pelanggaran
				);
			}
			$respon = array(
				'status'	=> '200',
				'msg'		=> 'Data ditemukan.',
				'data'		=> $result
			);				
		}

		echo json_encode($respon);
	}
}
