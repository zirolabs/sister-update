<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_wali extends CI_Controller
{
	function __construct()
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
		$this->load->model('monitoring_model');
		$this->load->model('manajemen_siswa/manajemen_siswa_model');
	}

	function get_siswa_detail()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Data siswa tidak ditemukan',
			'data'		=> array()
		);

		$get_data   = $this->manajemen_siswa_model->get_data(array('user_id' => $this->login_uid))->row();
		if(!empty($get_data))
		{
			$result		= array(
				'nis'		=> $get_data->nis,
				'nama'		=> $get_data->nama,
				'foto'		=> default_foto_user($get_data->foto),
				'kelas'		=> $get_data->kelas,
				'latitude'	=> $get_data->lokasi_latitude,
				'longitude'	=> $get_data->lokasi_longitude,
				'waktu'		=> format_tanggal_indonesia($get_data->lokasi_waktu, true)
			);

			$respon = array(
				'status'	=> '200',
				'msg'		=> 'Data siswa ditemukan',
				'data'		=> $result
			);
		}

		echo json_encode($respon);
	}	
}
