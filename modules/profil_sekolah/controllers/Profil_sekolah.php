<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profil_sekolah extends CI_Controller
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

		$this->load->model('profil_sekolah_model');
		$this->load->model('manajemen_user/manajemen_user_model');
		$this->page_active 		= 'manajemen_sekolah';
		$this->sub_page_active 	= 'manajemen_sekolah';
	}


	public function index()
	{
		$param['keyword']	= $this->input->get('q');
		$limit 				= 25;
		$uri_segment		= 3;
		$filter = array(
			'limit'		=> $limit,
			'offset'	=> $this->uri->segment($uri_segment),
			'keyword'	=> $param['keyword']
		);

		$param['data']			= $this->profil_sekolah_model->get_data($filter)->result();

		unset($filter['limit']);
		unset($filter['offset']);
		$total_rows 			= $this->profil_sekolah_model->get_data($filter)->num_rows();
		$param['pagination']	= paging('profil_sekolah/index', $total_rows, $limit, $uri_segment);

		$param['main_content']	= 'profil_sekolah/table';
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
				$param['data'] = $this->profil_sekolah_model->get_data_row($id);
			}
		}

		$param['main_content']		= 'profil_sekolah/form';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function submit($id = '')
	{
		$data_post = $this->input->post();
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		if(empty($id))
		{
			$this->form_validation->set_rules('nisn', 'NISN', 'required|is_unique[profil_sekolah.nisn]');
			$this->form_validation->set_rules('user_nama', 'Nama', 'required');
			$this->form_validation->set_rules('user_email', 'Email', 'required|is_unique[user.email]|valid_email');
			$this->form_validation->set_rules('user_password', 'Password', 'required');
		}

		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('profil_sekolah/form/' . $id);
		}
		else
		{
			$param_sekolah = $data_post;
			unset($param_sekolah['user_nama']);
			unset($param_sekolah['user_email']);
			unset($param_sekolah['user_password']);
			unset($param_sekolah['user_nip']);

			$param_user = array(
				'nama' 		=> $data_post['user_nama'],
				'email' 	=> $data_post['user_email'],
				'password' 	=> md5($data_post['user_password']),
				'level'		=> 'kepala sekolah'
			);

			if(empty($id))
			{
				$proses = $this->profil_sekolah_model->insert($param_sekolah);
				if($proses)
				{
					$sekolah_id = $this->db->insert_id();

					$this->manajemen_user_model->insert($param_user);
					$user_id = $this->db->insert_id();

					$param_kepsek = array(
						'user_id'		=> $user_id,
						'sekolah_id'	=> $sekolah_id,
						'nip'			=> $data_post['user_nip']
					);
					$this->profil_sekolah_model->insert_kepsek($param_kepsek);					
					$this->session->set_flashdata('msg', suc_msg('Data berhasil disimpan.'));
				}
				else
				{
					$this->session->set_flashdata('msg', err_msg('Data gagal disimpan, silahkan ulangi lagi.'));
					redirect('profil_sekolah/form/' . $id);
				}
			}
			else
			{
				unset($param_user['username']);
				if(empty($data_post['user_password']))
				{
					unset($param_user['password']);
				}

				$param_kepsek = array('nip' => $data_post['user_nip']);

				$this->profil_sekolah_model->update($param_sekolah, $id);
				$this->profil_sekolah_model->update_user_kepsek($param_user, $id);					
				$this->profil_sekolah_model->update_kepsek($param_kepsek, $id);					
				$this->session->set_flashdata('msg', suc_msg('Data berhasil diperbaharui.'));
			}
		}
		redirect('profil_sekolah');
	}

	public function hapus($id)
	{
		$proses = $this->profil_sekolah_model->delete($id);
		$this->session->set_flashdata('msg', suc_msg('Data berhasil dihapus.'));
		redirect('profil_sekolah');
	}
}
