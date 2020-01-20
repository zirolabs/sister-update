<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_wali extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->device_id = $this->input->post('device_id');
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
		$this->load->model('absensi_laporan_bulanan_model');
		$this->load->model('verifikasi_absensi/verifikasi_absensi_model');
	}

	public function index()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Data tidak ditemukan',
			'data'		=> array()
		);

		$filter = array(
			'bulan'		=> date('m'),
			'tahun'		=> date('Y'),
			'user_id'	=> $this->login_uid,
			'opt_jenis'	=> $this->verifikasi_absensi_model->get_jenis_absen()
		);
		$data = $this->absensi_laporan_bulanan_model->get_data($filter)->row();
		if(!empty($data))
		{
			$data->masuk_hari_ini  = '';
			$data->pulang_hari_ini = '';

			$filter['hari'] 	= date('d');
			$filter['status']	= 'hadir';
			$get_data_masuk = $this->absensi_laporan_bulanan_model->get_presensi_hari_ini($filter)->row();
			if(!empty($get_data_masuk))
			{
				$data->masuk_hari_ini = date('H:i:s', strtotime($get_data_masuk->waktu));
			}

			$filter['status']	= 'pulang';
			$get_data_pulang = $this->absensi_laporan_bulanan_model->get_presensi_hari_ini($filter)->row();
			if(!empty($get_data_pulang))
			{
				$data->pulang_hari_ini = date('H:i:s', strtotime($get_data_pulang->waktu));
			}

			$respon = array(
				'status'	=> '200',
				'msg'		=> 'Data ditemukan',
				'data'		=> $data
			);
		}

		echo json_encode($respon);
	}

	function log()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Data tidak ditemukan',
			'data'		=> array()
		);

		$data_post = $this->input->post();
		if(!empty($data_post['status']))
		{
			$filter = array(
				'bulan'		=> date('m'),
				'tahun'		=> date('Y'),
				'user_id'	=> $this->login_uid,
				'status'	=> $data_post['status']
			);

			$data = $this->absensi_laporan_bulanan_model->get_log($filter)->result();
			if(!empty($data))
			{
				$result = array();
				foreach($data as $key => $c)
				{
					$c->waktu = format_tanggal_indonesia($c->waktu, true);
					$result[] = $c;
				}
				$respon = array(
					'status'	=> '200',
					'msg'		=> 'Data ditemukan',
					'data'		=> $data
				);				
			}
		}

		echo json_encode($respon);
	}

	public function bulanan()
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

/* End of file Api_wali.php */
/* Location: ./application/controllers/Api_wali.php */