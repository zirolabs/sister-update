<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan_rfid extends CI_Controller
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

		$this->load->model('keuangan_rfid_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->load->model('manajemen_siswa/manajemen_siswa_model');
		$this->load->model('pengaturan_kelas/pengaturan_kelas_model');
		$this->page_active 		= 'keuangan';
		$this->sub_page_active 	= 'keuangan_rfid';
	}


	public function index()
	{
		$param['sekolah']	= $this->input->get('sekolah');
		$param['kelas']		= $this->input->get('kelas');
		$param['keyword']	= $this->input->get('q');
		$limit 				= 25;
		$uri_segment		= 3;
		$filter = array(
			'limit'		=> $limit,
			'offset'	=> $this->uri->segment($uri_segment),
			'keyword'	=> $param['keyword'],
			'sekolah'	=> $param['sekolah'],
			'kelas'		=> $param['kelas']
		);

		$param['data']			= $this->keuangan_rfid_model->get_data($filter)->result();

		unset($filter['limit']);
		unset($filter['offset']);
		$total_rows 			= $this->keuangan_rfid_model->get_data($filter)->num_rows();
		$param['pagination']	= paging('keuangan_rfid/index', $total_rows, $limit, $uri_segment);

		$param['opt_sekolah']		= $this->profil_sekolah_model->get_opt('Semua Sekolah');
		$param['main_content']		= 'keuangan_rfid/table';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function form($id = '')
	{
		$param['msg']			= $this->session->flashdata('msg');
		$param['id']			= $id;

		$last_data 	= $this->session->flashdata('last_data');
		if(!empty($last_data))
		{
			$param['data'] = (object) $last_data;
		}
		else
		{
			if(!empty($id))
			{
				$param['data'] 		= $this->keuangan_rfid_model->get_data(array('user_id' => $id))->row();
			}
		}

		$param['opt_sekolah']		= $this->profil_sekolah_model->get_opt();
		$param['main_content']		= 'keuangan_rfid/form';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function submit($id = '')
	{
		$data_post = $this->input->post();
		$this->form_validation->set_rules('sekolah_id', 'Sekolah', 'required');
		$this->form_validation->set_rules('nis', 'NIS Siswa', 'required');
		$this->form_validation->set_rules('sn_rfid', 'SN Kartu RFID', 'required');
		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('keuangan_rfid/form/' . $id);
		}
		else
		{
			$param_cek_nis = array(
				'nis'		=> $data_post['nis'],
				'sekolah'	=> $data_post['sekolah_id']
			);
			$cek_nis 	= $this->manajemen_siswa_model->get_data($param_cek_nis)->row();
			if(empty($cek_nis))
			{
				$this->session->set_flashdata('msg', err_msg('NIS tidak valid'));
				$this->session->set_flashdata('last_data', $data_post);
				redirect('keuangan_rfid/form/' . $id);
				exit;
			}

			$param_cek_rfid = array('sn_rfid'	=> $data_post['sn_rfid']);
			$cek_rfid		= $this->keuangan_rfid_model->get_data($param_cek_rfid)->row(); 
			if(!empty($cek_rfid))
			{
				if($cek_rfid->nis == $data_post['nis'] && $cek_rfid->sekolah_id == $data_post['sekolah_id'])
				{
					$this->session->set_flashdata('msg', info_msg('Kartu RFID sudah terdaftar dengan siswa yang bersangkutan.'));
				}
				else
				{
					$this->session->set_flashdata('msg', err_msg('Kartu RFID sudah terdaftar pada siswa lain, gunakan kartu lain.'));
				}

				$this->session->set_flashdata('last_data', $data_post);
				redirect('keuangan_rfid/form/' . $id);
				exit;										
			}

			$param_update = array('sn_rfid'	=> $data_post['sn_rfid']);
			$proses = $this->manajemen_siswa_model->update($param_update, $cek_nis->user_id);
			if($proses)
			{
				$this->session->set_flashdata('msg', suc_msg('Kartu berhasil didaftarkan.'));
			}
			else
			{
				$this->session->set_flashdata('msg', err_msg('Kartu gagal didaftarkan. Silahkan ulangi lagi.'));
			}
			redirect('keuangan_rfid/form/' . $id);
		}
	}

	// registrasi dengan QR Code 
	public function submitqr($id = '')
	{
		$data_post = $this->input->post();
		$this->form_validation->set_rules('sekolah_id', 'Sekolah', 'required');
		$this->form_validation->set_rules('nis', 'NIS Siswa', 'required');
		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('keuangan_rfid/form/' . $id);
		}
		else
		{
			$param_cek_nis = array(
				'nis'		=> $data_post['nis'],
				'sekolah'	=> $data_post['sekolah_id']
			);
			$cek_nis 	= $this->manajemen_siswa_model->get_data($param_cek_nis)->row();
			if(empty($cek_nis))
			{
				$this->session->set_flashdata('msg', err_msg('NIS tidak valid'));
				$this->session->set_flashdata('last_data', $data_post);
				redirect('keuangan_rfid/form/' . $id);
				exit;
			}

			$param_cek_nis = array('nis'	=> $data_post['nis']);
			$cek_qr		= $this->keuangan_rfid_model->get_data_nis($param_cek_nis)->row(); 
			if(!empty($cek_qr))
			{
				if($cek_qr->status_qr == 1 && $cek_rfid->sekolah_id == $data_post['sekolah_id'])
				{
					$this->session->set_flashdata('msg', info_msg('QR Code sudah didaftarkan.'));
				}

				$this->session->set_flashdata('last_data', $data_post);
				redirect('keuangan_rfid/form/' . $id);
				exit;										
			}

			$param_update = array('status_qr'	=> '1');
			$proses = $this->manajemen_siswa_model->update($param_update, $cek_nis->user_id);
			if($proses)
			{
				$this->session->set_flashdata('msg', suc_msg('QR Code berhasil didaftarkan.'));
			}
			else
			{
				$this->session->set_flashdata('msg', err_msg('QR Code gagal didaftarkan. Silahkan ulangi lagi.'));
			}
			redirect('keuangan_rfid/form/' . $id);
		}
	}

	public function hapus($id)
	{
		$param_update = array('sn_rfid'	=> '');
		$proses = $this->manajemen_siswa_model->update($param_update, $id);
		if($proses)
		{
			$this->session->set_flashdata('msg', suc_msg('Berhasil menghapus kartu.'));
		}
		else
		{
			$this->session->set_flashdata('msg', err_msg('Gagal menghapus kartu.'));
		}
		redirect('keuangan_rfid');
	}

	public function ajax_cek_nis()
	{
		$respon = array('status'	=> '201');

		$nis 	 = $this->input->post('nis');
		$sekolah = $this->input->post('sekolah');
		if(!empty($nis) && !empty($sekolah))
		{
			$this->load->model('manajemen_siswa/manajemen_siswa_model');
			$param = array(
				'nis'		=> $nis,
				'sekolah'	=> $sekolah
			);
			$get_data = $this->manajemen_siswa_model->get_data($param)->row();
			if(!empty($get_data))
			{
				$respon = array(
					'status'	=> '200',
					'data'		=> array(
						'nama'		=> $get_data->nama,
						'nis'		=> $get_data->nis,
						'kelas'		=> $get_data->kelas,
						'sekolah'	=> $get_data->sekolah
					)
				);
			}
		}
		echo json_encode($respon);
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
