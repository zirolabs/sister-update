<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manajemen_user extends CI_Controller
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

		$this->load->model('manajemen_user_model');
		$this->page_active 		= 'manajemen_user';
		$this->sub_page_active 	= 'manajemen_user';
	}


	public function index()
	{
		$param['keyword']	= $this->input->get('q');
		$limit 				= 25;
		$uri_segment		= 3;
		$filter = array('limit'		=> $limit,
						'offset'	=> $this->uri->segment($uri_segment),
						'keyword'	=> $param['keyword']);

		$param['data']			= $this->manajemen_user_model->get_data($filter)->result();

		unset($filter['limit']);
		unset($filter['offset']);
		$total_rows 			= $this->manajemen_user_model->get_data($filter)->num_rows();
		$param['pagination']	= paging('manajemen_user/index', $total_rows, $limit, $uri_segment);

		$param['main_content']	= 'manajemen_user/table';
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
				$param['data'] = $this->manajemen_user_model->get_data_row($id);
			}
		}

		$param['main_content']	= 'manajemen_user/form';
		$param['page_active'] 	= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function submit($id = '')
	{
		$data_post = $this->input->post();
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		if(empty($id))
		{
			$this->form_validation->set_rules('username', 'Username', 'required|is_unique[user.username]|alpha_numeric');
			$this->form_validation->set_rules('email', 'Email', 'required|is_unique[user.email]|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('no_hp', 'No Handphone', 'required|is_unique[user.no_hp]');
		}
		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('manajemen_user/form/' . $id);
		}
		else
		{
			$data_post['level']	= 'administrator';
			if(empty($id))
			{
				$data_post['password'] = md5($data_post['password']);
				$proses = $this->manajemen_user_model->insert($data_post);
				if($proses)
				{
					$this->session->set_flashdata('msg', suc_msg('Data berhasil disimpan.'));
					redirect('manajemen_user');
				}
				else
				{
					$this->session->set_flashdata('msg', err_msg('Data gagal disimpan, silahkan ulangi lagi.'));
					redirect('manajemen_user/form/' . $id);
				}
			}
			else
			{
				if(empty($data_post['password']))
				{
					unset($data_post['password']);
				}
				else
				{
					$data_post['password'] = md5($data_post['password']);
				}
				$proses = $this->manajemen_user_model->update($data_post, $id);
				if($proses)
				{
					$this->session->set_flashdata('msg', suc_msg('Data berhasil diperbaharui.'));
					redirect('manajemen_user');
				}
				else
				{
					$this->session->set_flashdata('msg', err_msg('Data gagal diperbaharui, tidak ada yang berubah.'));
					redirect('manajemen_user');
				}
			}
		}
	}

	public function hapus($id)
	{
		$proses = $this->manajemen_user_model->delete($id);
		$this->session->set_flashdata('msg', suc_msg('Data berhasil dihapus.'));
		redirect('manajemen_user');
	}
}
