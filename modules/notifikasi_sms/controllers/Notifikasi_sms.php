<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifikasi_sms extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->login_status 	= $this->session->userdata('login_status');
		$this->login_uid 		= $this->session->userdata('login_uid');
		if($this->login_status != 'ok')
		{
			$this->session->set_flashdata('msg', err_msg('Silahkan login untuk melanjutkan.'));
			redirect(site_url('login'));
		}

		$this->load->library('sms');
		$this->load->model('notifikasi_sms_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->load->model('pengaturan_kelas/pengaturan_kelas_model');

		$this->page_active 		= 'notifikasi';
		$this->sub_page_active 	= 'notifikasi_sms';
	}

	public function index()
	{
		$param['sekolah']	= $this->input->get('sekolah');
		$param['kelas']		= $this->input->get('kelas');
		$param['status']	= $this->input->get('status');

		$limit 				= 25;
		$uri_segment		= 3;
		$filter = array(
			'limit'			=> $limit,
			'offset'		=> $this->uri->segment($uri_segment),
			'sekolah_id'	=> $param['sekolah'],
			'kelas_id'		=> $param['kelas'],
			'status'		=> $param['status']
		);

		$param['data']			= $this->notifikasi_sms_model->get_data($filter)->result();

		unset($filter['limit']);
		unset($filter['offset']);
		$total_rows 			= $this->notifikasi_sms_model->get_data($filter)->num_rows();
		$param['pagination']	= paging('notifikasi_sms/index', $total_rows, $limit, $uri_segment);

		$param['opt_sekolah']		= $this->profil_sekolah_model->get_opt('Semua Sekolah');
		$param['opt_status']		= array(
			''			=> 'Semua',
			'pending'	=> 'Pending',
			'proses'	=> 'Proses',
			'terkirim'	=> 'Terkirim',
			'gagal'		=> 'Gagal'
		);
		$param['main_content']		= 'notifikasi_sms/table';
		$param['sub_main_content']	= 'log';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function perangkat()
	{
		$get_device = $this->sms->getDevice();
		if($get_device['status'] == 'sukses')
		{	
			foreach($get_device['data'] as $key => $c)
			{
				$param = array(
					'device_id'	=> $c['device_id'],
					'nama'		=> $c['name']
				);

				$this->notifikasi_sms_model->simpan_device($param);
			}
		}

		$param['data']		= $this->notifikasi_sms_model->get_data_perangkat()->result();

		$param['main_content']		= 'notifikasi_sms/table';
		$param['sub_main_content']	= 'perangkat';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);		
	}

	public function perangkat_test($id = '')
	{
		if(empty($id))
		{
			show_404();
		}
		$this->session->set_flashdata('msg', err_msg('Test perangkat gagal.'));		

		$data_post = $this->input->post();
		if(empty($data_post['telp']))
		{
			$this->session->set_flashdata('msg', err_msg('Masukkan nomor telepon.'));		
		}
		else
		{
			$param 	 	= array();
			$pesan_text = 'Test SMS ' . rand(000000, 999999);
			$param[] 	= array(
			    'phoneNumber' 	=> $data_post['telp'],
			    'message' 		=> $pesan_text,
			    'deviceId' 		=> $id
			);
			$proses = $this->sms->sendMessage($param);

			if($proses['status'] == 'sukses')
			{
				if(!empty($proses['data'][0]['sms_id_api']) && !empty($proses['data'][0]['status']))
				{
					if($proses['data'][0]['status'] == 'pending')
					{
						$this->session->set_flashdata('msg', suc_msg('Test perangkat berhasil. Isi Pesan : ' . $pesan_text . ', No Tujuan : ' . $data_post['telp']));		
					}
				}
			}
		}

		redirect('notifikasi_sms/perangkat');
	}

	public function perangkat_nonaktif($id = '')
	{
		if(empty($id))
		{
			show_404();
		}

		$param_db = array('aktif'	=> 'N');
		$this->notifikasi_sms_model->update_device($param_db, $id);

		$this->session->set_flashdata('msg', suc_msg('Perangkat berhasil dinonaktifkan.'));		
		redirect('notifikasi_sms/perangkat');		
	}

	public function perangkat_aktif($id = '')
	{
		if(empty($id))
		{
			show_404();
		}

		$param_db = array('aktif'	=> 'Y');
		$this->notifikasi_sms_model->update_device($param_db, $id);

		$this->session->set_flashdata('msg', suc_msg('Perangkat berhasil diaktifkan.'));		
		redirect('notifikasi_sms/perangkat');		
	}

	public function maksimum_sms($id = '')
	{
		if(empty($id))
		{
			show_404();
		}

		$total = $this->input->post('total');
		if(empty($total))
		{
			$total = 0;
		}

		$param_db = array('maks_per_jam'	=> $total);
		$this->notifikasi_sms_model->update_device($param_db, $id);

		$this->session->set_flashdata('msg', suc_msg('Pengaturan berhasil diperbaharui.'));		
		redirect('notifikasi_sms/perangkat');
	}

	public function konfigurasi()
	{
		$param['main_content']		= 'notifikasi_sms/table';
		$param['sub_main_content']	= 'konfigurasi';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function konfigurasi_submit()
	{
		$data_post = $this->input->post();
		foreach($data_post as $key => $c)
		{
			$param = array('isi' => $c);
			$this->konfigurasi_model->update_data($param, $key);
		}
		$this->session->set_flashdata('msg', suc_msg('Konfigurasi berhasil diperbaharui.'));		
		redirect('notifikasi_sms/konfigurasi');
	}

	public function ajax_form_kelas()
	{
		$data_post = $this->input->post();

		$data['kelas_selected'] = array();
		if(!empty($data_post['kelas']))
		{
			$data['kelas_selected'] = json_decode($data_post['kelas']);
		}

		$data['kelas'] = $this->pengaturan_kelas_model->get_opt('', $data_post['sekolah']);
		$this->load->view('form_kelas', $data);
	}

	public function ajax_kelas()
	{
		$selected	= $this->input->get('selected');
		$sekolah_id = $this->input->get('sekolah_id');

		$result[''] = 'Semua Kelas';
		if(!empty($sekolah_id))
		{
			$result 	= $this->pengaturan_kelas_model->get_opt('Semua Kelas', $sekolah_id);
		}

		echo form_dropdown('kelas', $result, $selected, 'class="form-control"');		
	}
}
