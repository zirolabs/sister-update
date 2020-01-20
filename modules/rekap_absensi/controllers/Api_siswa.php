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
		$this->load->model('absensi_laporan_bulanan_model');
	}

	public function index()
	{
		$filter = $this->input->post();

		$filter['user_id'] = $this->login_uid;
		$data = $this->absensi_laporan_bulanan_model->get_log($filter)->result();

		if(!empty($data))
		{
			foreach($data as $key => $c)
			{
				$c->status = format_ucwords($c->status);
				$c->waktu  = format_tanggal_indonesia($c->waktu, true);
				$result[]  = $c;
			}

			$respon = array(
				'status'	=> '200',
				'msg'		=> 'Data laporan ditemukan.',
				'data'		=> $result
			);	
		}
		else
		{
			$respon = array(
				'status'	=> '201',
				'msg'		=> 'Data laporan tidak ditemukan.',
				'data'		=> array()
			);	
		}
		echo json_encode($respon);
	}

}

?>