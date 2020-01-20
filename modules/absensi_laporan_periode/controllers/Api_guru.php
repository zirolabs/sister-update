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
		$this->load->model('absensi_laporan_periode_model');
	}

	public function index()
	{
		$filter = $this->input->post();

		$this->load->model('verifikasi_absensi/verifikasi_absensi_model');		
		$filter['opt_jenis'] = $this->verifikasi_absensi_model->get_jenis_absen();

		$data = $this->absensi_laporan_periode_model->get_data($filter)->result();
		if(!empty($data))
		{
			$result = array();
			foreach($data as $key => $c)
			{
				$c->foto 	= default_foto_user($c->foto);
				$c->detail 	= array();
				foreach($filter['opt_jenis'] as $kex => $x)
				{
					$variable = 'total_' . $kex;
					$c->detail[] = array(
						'label'		=> $x,
						'value'		=> $c->$variable
					);
					unset($c->$variable);
				}
				$result[] = $c;
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