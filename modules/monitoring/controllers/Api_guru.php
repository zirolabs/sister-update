<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_guru extends CI_Controller
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
		$this->load->model('monitoring_model');
		$this->load->model('manajemen_siswa/manajemen_siswa_model');
	}

	function get_siswa()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Data siswa tidak ditemukan',
			'data'		=> array()
		);

		$get_data   = $this->manajemen_siswa_model->get_data(array('guru' => $this->cek_device->user_id))->result();
		if(!empty($get_data))
		{
			$result		= array();
			foreach($get_data as $key => $c)
			{
				$result[] = array(
					'nis'		=> $c->nis,
					'id'		=> $c->user_id,
					'nama'		=> $c->nama,
					'foto'		=> default_foto_user($c->foto),
					'kelas'		=> $c->kelas,
					'latitude'	=> $c->lokasi_latitude,
					'longitude'	=> $c->lokasi_longitude,
					'waktu'		=> format_tanggal_indonesia($c->lokasi_waktu, true)
				);
			}

			$respon = array(
				'status'	=> '200',
				'msg'		=> 'Data siswa ditemukan',
				'data'		=> $result
			);
		}
		echo json_encode($respon);
	}

	function get_siswa_detail()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Data siswa tidak ditemukan',
			'data'		=> array()
		);

		$data_post = $this->input->post();
		if(!empty($data_post['id']))
		{
			$get_data   = $this->manajemen_siswa_model->get_data(array('user_id' => $data_post['id']))->row();
			if(!empty($get_data))
			{
				$result		= array(
					'nis'		=> $get_data->nis,
					'id'		=> $get_data->user_id,
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
		}

		echo json_encode($respon);
	}	
}
