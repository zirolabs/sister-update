<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->login_status 	= $this->session->userdata('login_status');
		$this->login_uid 		= $this->session->userdata('login_uid');
		$this->login_level 		= $this->session->userdata('login_level');
		$cek = FALSE;
		$kantin = FALSE;
		if($this->login_level == 'operator sekolah' || $this->login_level == 'administrator' || $this->login_level == 'guru' || $this->login_level == 'kepala sekolah'){
			$cek = TRUE;
			$kantin = FALSE;
		}elseif($this->login_level == 'user kantin'){
			$kantin = TRUE;
		}

		if($cek != TRUE)
		{	
			if($kantin != TRUE){
				$this->session->set_flashdata('msg', err_msg('Silahkan login untuk melanjutkan.'));
				redirect(site_url('login'));
			}else
			{
				redirect(site_url('kantin'));
			}
			
		}

		$this->page_active = 'home';
	}

	function index()
	{
		$data_get = $this->input->get();
		if($this->login_level == 'guru')
		{
			$this->load->model('pengaturan_jadwal_pelajaran/pengaturan_jadwal_pelajaran_model');
			$param['jadwal_pelajaran']['tanggal'] = empty($data_get['jadwal_tanggal']) ? date('Y-m-d') : $data_get['jadwal_tanggal'];
			$param_jadwal_pelajaran = array(
				'user_id' => $this->login_uid,
				'hari'	  => date('w', strtotime($param['jadwal_pelajaran']['tanggal']))
			);
			$param['jadwal_pelajaran']['data'] = $this->pengaturan_jadwal_pelajaran_model->get_data($param_jadwal_pelajaran)->result();
		}

		if($this->login_level == 'administrator' || $this->login_level == 'kepala sekolah' || $this->login_level == 'operator sekolah')
		{
			
		}


		$this->load->model('pengumuman/pengumuman_model');
		$param_pengumuman = array(
			'limit'	=> 10,
			'saya'	=> $this->login_uid
		);
		$param['pengumuman']['data'] = $this->pengumuman_model->get_data($param_pengumuman)->result();

		$param['main_content']	= 'main';
		$param['page_active'] 	= $this->page_active;
		$this->templates->load('main_templates', $param);
	}
}
