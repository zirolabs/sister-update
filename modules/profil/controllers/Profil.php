<?php

class Profil extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->login_status 	= $this->session->userdata('login_status');
		$this->login_uid 		= $this->session->userdata('login_uid');
		if($this->login_status != 'ok')
		{
			$this->session->set_flashdata('msg', err_msg('Silahkan login untuk melanjutkan.'));
			redirect(site_url());
		}
		$this->load->model('profil_model');
		$this->page_active 		= 'profil';
		$this->sub_page_active 	= 'profil';
	}


	public function index()
	{
		$param['data']	= $this->profil_model->get_data_row($this->login_uid);
		$param['main_content']		= 'profil/table';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function form()
	{
		$param['msg']	= $this->session->flashdata('msg');
		$last_data 		= $this->session->flashdata('last_data');

		if(!empty($last_data))
		{
			$param['data'] = (object) $last_data;
		}
		else
		{
			$param['data'] = $this->profil_model->get_data_row($this->login_uid);
		}

		$param['main_content']		= 'profil/form';
		$param['page_active']		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function submit()
	{
		$data_post = $this->input->post();

		$this->form_validation->set_rules('nama', 'Nama User', 'required');
		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('profil/form/');
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
			unset($data_post['username']);
			unset($data_post['email']);

			$proses = $this->profil_model->update($data_post, $this->login_uid);
			if($proses)
			{
				$this->session->set_flashdata('msg', suc_msg('Data berhasil diperbaharui.'));
				redirect('profil');
			}
			else
			{
				$this->session->set_flashdata('msg', err_msg('Data gagal diperbaharui, tidak ada yang berubah.'));
				redirect('profil');
			}
		}
	}

	public function upload_foto()
	{
		$param['msg']	= $this->session->flashdata('msg');
		$param['data'] = $this->profil_model->get_data_row($this->login_uid);

		$param['main_content']	= 'profil/upload_foto';
		$param['page_active']	= $this->page_active;
		$this->templates->load('main_templates', $param);
	}

	public function do_upload()
	{
		if(!empty($_FILES['userfiles']['tmp_name']))
		{
			$config['upload_path']      = './uploads/profil/';
            $config['allowed_types']    = 'jpg|png';
 			$config['max_size'] 		= '2048';

            if (!is_dir($config['upload_path']))
            {
                mkdir($config['upload_path']);
            }

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('userfiles'))
            {
				$this->session->set_flashdata('msg', err_msg($this->upload->display_errors()));
				redirect('profil/upload_foto/');
            }
            else
            {
            	$data_upload 			= $this->upload->data();
            	$data_post['foto']		= $data_upload['file_name'];
				$proses = $this->profil_model->update($data_post, $this->login_uid);
				if($proses)
				{
					$this->session->set_flashdata('msg', suc_msg('Foto berhasil disimpan.'));
					redirect('profil');
				}
				else
				{
					$this->session->set_flashdata('msg', err_msg('Foto gagal disimpan, silahkan ulangi lagi.'));
					redirect('profil/upload_foto/');
				}
            }
		}
		else
		{
			// $this->session->set_flashdata('msg', err_msg('Masukkan Foto Profil'));
			redirect('profil/upload_foto/');
		}
	}
}
