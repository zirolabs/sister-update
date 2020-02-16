<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notifikasi_jadwal extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->login_status 	= $this->session->userdata('login_status');
		$this->login_uid 		= $this->session->userdata('login_uid');
		$this->login_level 		= $this->session->userdata('login_level');
		$cek = FALSE;
		if($this->login_level == 'operator sekolah' || $this->login_level == 'administrator' || $this->login_level == 'kepala sekolah'){
			$cek = TRUE;
		}

		if($cek != TRUE)
		{
			$this->session->set_flashdata('msg', err_msg('Silahkan login untuk melanjutkan.'));
			redirect(site_url('login'));
		}

		$this->load->model('notifikasi_jadwal_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->load->model('manajemen_siswa/manajemen_siswa_model');
		$this->load->model('pengaturan_kelas/pengaturan_kelas_model');
		$this->page_active 		= 'notifikasi';
		$this->sub_page_active 	= 'notifikasi_jadwal';
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

		$param['data']			= $this->notifikasi_jadwal_model->get_data($filter)->result();
		foreach($param['data'] as $key => $c)
		{
			$explode_hari = explode(',', $c->hari);

			$param['data'][$key]->hari = array();
			foreach($explode_hari as $kex => $x)
			{
				$param['data'][$key]->hari[] = hari_indonesia($x);
			}			

			$param['data'][$key]->hari  = implode(', ', $param['data'][$key]->hari);
			$param['data'][$key]->kelas = $this->notifikasi_jadwal_model->get_kelas($c->notifikasi_id);
		}

		unset($filter['limit']);
		unset($filter['offset']);
		$total_rows 			= $this->notifikasi_jadwal_model->get_data($filter)->num_rows();
		$param['pagination']	= paging('notifikasi_jadwal/index', $total_rows, $limit, $uri_segment);

		$param['opt_sekolah']	= $this->profil_sekolah_model->get_opt('Semua Sekolah');
		$param['main_content']	= 'notifikasi_jadwal/table';
		$param['page_active'] 	= $this->page_active;
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
				$param['data'] 		 = $this->notifikasi_jadwal_model->get_data_row($id);
				$param['data']->hari = explode(',', $param['data']->hari);

				$param['opt_kelas']	 = $this->notifikasi_jadwal_model->get_kelas($id);
			}
		}

		$param['list_hari']		 	= hari_indonesia();

		$param['opt_sekolah']		= $this->profil_sekolah_model->get_opt();
		$param['main_content']		= 'notifikasi_jadwal/form';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function submit($id = '')
	{
		$data_post = $this->input->post();
		$this->form_validation->set_rules('judul', 'Judul', 'required');
		$this->form_validation->set_rules('isi', 'Isi', 'required');
		$this->form_validation->set_rules('waktu', 'Waktu Kirim', 'required');
		$this->form_validation->set_rules('sekolah_id', 'Sekolah', 'required');
		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('notifikasi_jadwal/form/' . $id);
		}
		else
		{
			if(empty($data_post['hari']))
			{
				$this->session->set_flashdata('msg', err_msg('Tentukan Hari Kirim'));
				$this->session->set_flashdata('last_data', $data_post);
				redirect('notifikasi_jadwal/form/' . $id);				
			}		

			if(empty($data_post['kelas']))
			{
				$this->session->set_flashdata('msg', err_msg('Pilih Kelas'));
				$this->session->set_flashdata('last_data', $data_post);
				redirect('notifikasi_jadwal/form/' . $id);				
			}		

			$data_post['hari']	= implode(',', $data_post['hari']);
			
			$list_kelas 		= $data_post['kelas'];
			unset($data_post['kelas']);

			if(empty($data_post['target_siswa'])) $data_post['target_siswa'] = 'N';
			if(empty($data_post['target_wali'])) $data_post['target_wali'] = 'N';
			if(empty($data_post['target_wali_kelas'])) $data_post['target_wali_kelas'] = 'N';
			if(empty($data_post['target_guru'])) $data_post['target_guru'] = 'N';

			if(empty($id))
			{
				$proses = $this->notifikasi_jadwal_model->insert($data_post);
				if($proses)
				{
					$this->session->set_flashdata('msg', suc_msg('Data berhasil disimpan.'));
					$id = $this->db->insert_id();
				}
				else
				{
					$this->session->set_flashdata('msg', err_msg('Data gagal disimpan, silahkan ulangi lagi.'));
					redirect('notifikasi_jadwal/form/' . $id);
				}
			}
			else
			{
				$proses = $this->notifikasi_jadwal_model->update($data_post, $id);
				if($proses)
				{
					$this->session->set_flashdata('msg', suc_msg('Data berhasil diperbaharui.'));
				}
				else
				{
					$this->session->set_flashdata('msg', err_msg('Data gagal diperbaharui, tidak ada yang berubah.'));
				}
			}

			if(!empty($id))
			{				
				$param_kelas 	= array();
				$param_user 	= array();

				foreach($list_kelas as $key => $c)
				{
					$param_kelas[]	= array(
						'notifikasi_id'	=> $id,
						'kelas_id'		=> $c
					);

					if($data_post['target_siswa'] == 'Y' || $data_post['target_wali'] == 'Y')
					{
						$filter = array('kelas' => $c); 
						$get_data_siswa = $this->manajemen_siswa_model->get_data($filter)->result();
						foreach($get_data_siswa as $kex => $x)
						{
							$param_user[] = array(
								'notifikasi_id'	=> $id,
								'user_id'		=> $x->user_id
							);
						}				
					}

					if($data_post['target_wali_kelas'] == 'Y')
					{
						$filter = array('kelas_id' => $c);
						$get_data_wali_kelas = $this->pengaturan_kelas_model->get_data($filter)->row();
						if(!empty($get_data_wali_kelas->user_id))
						{
							$param_user[] = array(
								'notifikasi_id'	=> $id,
								'user_id'		=> $get_data_wali_kelas->user_id
							);
						}
					}

					if($data_post['target_guru'] == 'Y')
					{

					}
				}

				if(!empty($param_kelas))
				{
					$this->notifikasi_jadwal_model->insert_kelas($param_kelas);
				}

				if(!empty($param_user))
				{
					$this->notifikasi_jadwal_model->insert_user($param_user);
				}
			}
		}
		redirect('notifikasi_jadwal/index?sekolah=' . $data_post['sekolah']);
	}

	public function hapus($id)
	{
		$from = $_SERVER['HTTP_REFERER'];
		$this->notifikasi_jadwal_model->delete($id);
		$this->notifikasi_jadwal_model->delete_kelas($id);
		$this->notifikasi_jadwal_model->delete_user($id);
		$this->session->set_flashdata('msg', suc_msg('Data berhasil dihapus.'));
		redirect($from);
	}

	public function ajax_form_kelas()
	{
		$data_post = $this->input->post();

		$data['kelas_selected'] = array();
		if(!empty($data_post['kelas']))
		{
			$data['kelas_selected'] = json_decode($data_post['kelas']);
		}

		$data['kelas']  = $this->pengaturan_kelas_model->get_opt('', $data_post['sekolah']);
		$this->load->view('form_kelas', $data);
	}

	public function ajax_kelas()
	{
		$selected	= $this->input->get('selected');
		$sekolah_id = $this->input->get('sekolah_id');

		$result[''] = 'Semua Kelas';
		if(!empty($sekolah_id))
		{
			$result    = $this->pengaturan_kelas_model->get_opt('Semua Kelas', $sekolah_id);
		}

		echo form_dropdown('kelas', $result, $selected, 'class="form-control"');		
	}
}
