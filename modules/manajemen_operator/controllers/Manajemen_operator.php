<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manajemen_operator extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->login_status 	= $this->session->userdata('login_status');
		$this->login_uid 		= $this->session->userdata('login_uid');
		$this->login_level 		= $this->session->userdata('login_level');
		$cek = FALSE;
		if($this->login_level == 'administrator' || $this->login_level == 'kepala sekolah'){
			$cek = TRUE;
		}

		if($cek != TRUE)
		{
			$this->session->set_flashdata('msg', err_msg('Silahkan login untuk melanjutkan.'));
			redirect(site_url('login'));
		}

		$this->load->model('manajemen_operator_model');
		$this->load->model('manajemen_user/manajemen_user_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->page_active 		= 'manajemen_operator';
		$this->sub_page_active 	= 'manajemen_operator';
	}


	public function index()
	{
		$param['sekolah']	= $this->input->get('sekolah');
		$param['keyword']	= $this->input->get('q');
		$limit 				= 25;
		$uri_segment		= 3;
		$filter = array(
			'limit'		=> $limit,
			'offset'	=> $this->uri->segment($uri_segment),
			'keyword'	=> $param['keyword'],
			'sekolah'	=> $param['sekolah'],
		);

		$param['data']	= $this->manajemen_operator_model->get_data($filter)->result();

		unset($filter['limit']);
		unset($filter['offset']);
		$total_rows 				= $this->manajemen_operator_model->get_data($filter)->num_rows();
		$param['pagination']		= paging('manajemen_operator/index', $total_rows, $limit, $uri_segment);

		$param['opt_sekolah']		= $this->profil_sekolah_model->get_opt('Semua Sekolah');

		$param['main_content']		= 'manajemen_operator/table';
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
				$param['data'] 	= $this->manajemen_operator_model->get_data_row($id);
			}
		}

		$param['opt_sekolah']	= $this->profil_sekolah_model->get_opt('Pilih Sekolah');

		$param['main_content']		= 'manajemen_operator/form';
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
			$this->form_validation->set_rules('email', 'Email', 'required|is_unique[user.email]|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('no_hp', 'No Handphone', 'required|is_unique[user.no_hp]');
		}
		$this->form_validation->set_rules('sekolah_id', 'Sekolah', 'required');

		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('manajemen_operator/form/' . $id);
		}
		else
		{
			if(!empty($_FILES['userfiles']['tmp_name']))
			{
				$config['upload_path']      = './uploads/profil/';
	            $config['allowed_types']    = 'jpg|png|jpeg';
	 			$config['max_size'] 		= '2048';

	            if (!is_dir($config['upload_path']))
	            {
	                mkdir($config['upload_path']);
	            }

	            $this->load->library('upload', $config);
	            if (!$this->upload->do_upload('userfiles'))
	            {
					$this->session->set_flashdata('last_data', $data_post);
					$this->session->set_flashdata('msg', err_msg($this->upload->display_errors()));
					redirect('manajemen_operator/form/' . $id);
	            }
	            else
	            {
	            	$data_upload 		= $this->upload->data();
	            	$data_post['foto']	= $data_upload['file_name'];
	            }
			}

			if(empty($data_post['password']))
			{
				unset($data_post['password']);
			}
			else
			{
				$data_post['password'] = md5($data_post['password']);
			}

			$param_user = $data_post;
			$param_user['level'] = 'operator sekolah';
			unset($param_user['sekolah_id']);

			$param_guru = $data_post;
			unset($param_guru['no_hp']);
			unset($param_guru['email']);
			unset($param_guru['password']);
			unset($param_guru['nama']);
			unset($param_guru['foto']);

			if(empty($id))
			{
				$proses = $this->manajemen_user_model->insert($param_user);
				if($proses)
				{
					$id = $this->db->insert_id();
					$param_guru['user_id'] = $id;
					$this->manajemen_operator_model->insert($param_guru);

					$this->session->set_flashdata('msg', suc_msg('Data berhasil disimpan.'));
				}
				else
				{
					$this->session->set_flashdata('msg', err_msg('Data gagal disimpan, silahkan ulangi lagi.'));
					redirect('manajemen_operator/form/' . $id);
				}
			}
			else
			{
				$this->manajemen_user_model->update($param_user, $id);
				$this->manajemen_operator_model->update($param_guru, $id);
				$this->session->set_flashdata('msg', suc_msg('Data berhasil diperbaharui.'));
			}
		}
		redirect('manajemen_operator');
	}

	public function hapus($id)
	{
		$this->manajemen_user_model->delete($id);
		$this->manajemen_operator_model->delete($id);
		$this->session->set_flashdata('msg', suc_msg('Data berhasil dihapus.'));
		redirect('manajemen_operator');
	}
}
