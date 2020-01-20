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
		$this->load->model('keuangan_mutasi_model');
	}

	public function index()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Tidak ada data.',
			'data'		=> array(
				'total'	=> format_rupiah(0)
			)
		);	

		if(!empty($this->cek_device->sn_rfid))
		{
			$get_data = $this->keuangan_mutasi_model->get_saldo($this->cek_device->sn_rfid);
			if(!empty($get_data))
			{
				$respon = array(
					'status'	=> '200',
					'msg'		=> 'Data ditemukan',
					'data'		=> array(
						'total'	=> format_rupiah($get_data)
					)
				);					
			}
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
			'user_id'	=> $this->login_uid,
			'offset'	=> $this->input->post('offset'),
			'limit'		=> 10
		);
		$get_data = $this->keuangan_mutasi_model->get_data($param, 'detail')->result();
		if(!empty($get_data))
		{
			$result = array();
			foreach($get_data as $key => $c)
			{
				unset($c->mutasi_id);
				unset($c->master_id);
				unset($c->user_id);

				$c->waktu 	= format_tanggal_indonesia($c->waktu, true);
				$c->nominal = format_rupiah($c->nominal);
				$c->jenis 	= format_ucwords($c->jenis);
				$result[] 	= $c;
			}
			$respon = array(
				'status'	=> '200',
				'msg'		=> 'Data ditemukan.',
				'data'		=> $get_data
			);				
		}

		echo json_encode($respon);
	}
}
