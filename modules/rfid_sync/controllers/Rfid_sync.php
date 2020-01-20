<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rfid_sync extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('rfid_sync_model');
	}

	function update_siswa($sekolah_id = '', $nis = '', $rfid = '')
	{
		$respon = array();
		if(empty($sekolah_id) || empty($nis))
		{
			$respon = array(
				'status'	=> '201',
				'msg'		=> 'Parameter tidak valid',
				'data'		=> array()
			);
		}
		else
		{
			$cek_rfid = $this->rfid_sync_model->get_rfid($rfid, $sekolah_id)->row();
			if(!empty($cek))
			{
				if($cek_rfid->nis == $nis)
				{
					$respon = array(
						'status'	=> '200',
						'msg'		=> 'Kartu RFID sudah terdaftar dengan siswa bersangkutan.',
						'data'		=> array()
					);					
				}
				else
				{
					$respon = array(
						'status'	=> '201',
						'msg'		=> 'Kartu RFID sudah terdaftar oleh siswa lain.',
						'data'		=> array()
					);					
				}
			}
			else
			{
				$proses = $this->rfid_sync_model->update_rfid($nis, $sekolah_id, $rfid);
				$respon = array(
					'status'	=> '200',
					'msg'		=> 'Kartu RFID berhasil didaftarkan dengan siswa bersangkutan.',
					'data'		=> array()
				);					
			}
		}

		echo json_encode($respon);		
	}

	function get_siswa($sekolah_id = '', $nis = '')
	{
		$respon = array();
		if(empty($sekolah_id) || empty($nis))
		{
			$respon = array(
				'status'	=> '201',
				'msg'		=> 'Parameter tidak valid',
				'data'		=> array()
			);
		}
		else
		{
			$get_data = $this->rfid_sync_model->get_user($nis, $sekolah_id)->row();
			if(empty($get_data))
			{
				$respon = array(
					'status'	=> '201',
					'msg'		=> 'Data siswa tidak ditemukan',
					'data'		=> array()
				);
			}
			else
			{
				$respon = array(
					'status'	=> '200',
					'msg'		=> 'Data siswa ditemukan',
					'data'		=> $get_data
				);
			}
		}

		echo json_encode($respon);
	}
}
