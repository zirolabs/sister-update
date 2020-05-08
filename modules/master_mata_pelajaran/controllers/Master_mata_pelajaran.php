<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_mata_pelajaran extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->login_status 	= $this->session->userdata('login_status');
		$this->login_uid 		= $this->session->userdata('login_uid');
		$this->login_level 		= $this->session->userdata('login_level');
		$cek = FALSE;
		if($this->login_level == 'operator sekolah' || $this->login_level == 'administrator'){
			$cek = TRUE;
		}

		if($cek != TRUE)
		{
			$this->session->set_flashdata('msg', err_msg('Silahkan login untuk melanjutkan.'));
			redirect(site_url('login'));
		}

		$this->load->model('master_mata_pelajaran_model');
		$this->page_active 		= 'master';
		$this->sub_page_active 	= 'master_mata_pelajaran';
	}


	public function index()
	{
		$param['keyword']	= $this->input->get('q');
		$limit 				= 25;
		$uri_segment		= 3;
		$filter = array('limit'		=> $limit,
						'offset'	=> $this->uri->segment($uri_segment),
						'keyword'	=> $param['keyword']);

		$param['data']			= $this->master_mata_pelajaran_model->get_data($filter)->result();

		unset($filter['limit']);
		unset($filter['offset']);
		$total_rows 			= $this->master_mata_pelajaran_model->get_data($filter)->num_rows();
		$param['pagination']	= paging('master_mata_pelajaran/index', $total_rows, $limit, $uri_segment);

		$param['main_content']	= 'master_mata_pelajaran/table';
		$param['page_active'] 	= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function pengaturan()
	{
		if($this->login_level == 'operator sekolah')
		{
			$limit 				= 1000;
			$uri_segment		= 3;
			$filter = array('limit'		=> $limit,
							'offset'	=> $this->uri->segment($uri_segment),
							);

			$param['data']				= $this->master_mata_pelajaran_model->get_data($filter)->result();
			$level_user 				= $this->session->userdata('login_level');
			$id_user 	 				= $this->session->userdata('login_uid');
			$sekolah_id 				= $this->master_mata_pelajaran_model->get_id_sekolah($id_user,$level_user);
			$param['sekolah_id']		= $sekolah_id;
			$param['sekolah'] 			= $this->master_mata_pelajaran_model->get_sekolah($sekolah_id);
			$param['main_content']		= 'master_mata_pelajaran/pengaturan';
			$param['page_active'] 		= 'pengaturan';
			$param['sub_page_active'] 	= 'master_mata_pelajaran/pengaturan';
			$this->templates->load('main_templates', $param);
		}else{
			redirect('home');
		}
	}

	public function tambah_mapel()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nama', 'Mata Pelajaran', 'max_length[200]|required|is_unique[master_mata_pelajaran.nama]');
		
		if($this->form_validation->run())
		{
			$data['nama'] = $this->input->post('nama');
			$this->master_mata_pelajaran_model->insert($data);
			redirect('master_mata_pelajaran/pengaturan');
		}else{
			$limit 				= 1000;
			$uri_segment		= 3;
			$filter = array('limit'		=> $limit,
							'offset'	=> $this->uri->segment($uri_segment),
							);

			$param['data']				= $this->master_mata_pelajaran_model->get_data($filter)->result();
			$level_user 				= $this->session->userdata('login_level');
			$id_user 	 				= $this->session->userdata('login_uid');
			$sekolah_id 				= $this->master_mata_pelajaran_model->get_id_sekolah($id_user,$level_user);
			$param['sekolah_id']		= $sekolah_id;
			$param['sekolah'] 			= $this->master_mata_pelajaran_model->get_sekolah($sekolah_id);
			$param['main_content']		= 'master_mata_pelajaran/pengaturan';
			$param['page_active'] 		= 'pengaturan';
			$param['sub_page_active'] 	= 'master_mata_pelajaran/pengaturan';
			$this->templates->load('main_templates', $param);
		}
		
	}

	public function update_mapel()
	{
		$mapel_id 	= $this->input->post('mata_pelajaran_id');
		$sekolah_id = $this->input->post('sekolah_id');
		if($mapel_id){
			$data['mata_pelajaran_id'] = implode(',',$mapel_id);
			$this->load->model('profil_sekolah/profil_sekolah_model');
			$this->profil_sekolah_model->update($data, $sekolah_id);
			redirect('master_mata_pelajaran/pengaturan');
		}else{
			$data['mata_pelajaran_id'] = '';
			$this->load->model('profil_sekolah/profil_sekolah_model');
			$this->profil_sekolah_model->update($data, $sekolah_id);
			redirect('master_mata_pelajaran/pengaturan');
		}
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
				$param['data'] = $this->master_mata_pelajaran_model->get_data_row($id);
			}
		}

		$param['main_content']		= 'master_mata_pelajaran/form';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function submit($id = '')
	{
		$data_post = $this->input->post();
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('master_mata_pelajaran/form/' . $id);
		}
		else
		{
			if(empty($id))
			{
				$proses = $this->master_mata_pelajaran_model->insert($data_post);
				if($proses)
				{
					$this->session->set_flashdata('msg', suc_msg('Data berhasil disimpan.'));
				}
				else
				{
					$this->session->set_flashdata('msg', err_msg('Data gagal disimpan, silahkan ulangi lagi.'));
					redirect('master_mata_pelajaran/form/' . $id);
				}
			}
			else
			{
				$proses = $this->master_mata_pelajaran_model->update($data_post, $id);
				if($proses)
				{
					$this->session->set_flashdata('msg', suc_msg('Data berhasil diperbaharui.'));
				}
				else
				{
					$this->session->set_flashdata('msg', err_msg('Data gagal diperbaharui, tidak ada yang berubah.'));
				}
			}
		}
		redirect('master_mata_pelajaran');
	}

	public function hapus($id)
	{
		$proses = $this->master_mata_pelajaran_model->delete($id);
		$this->session->set_flashdata('msg', suc_msg('Data berhasil dihapus.'));
		redirect('master_mata_pelajaran');
	}
}
