<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mata_pelajaran extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->login_status 	= $this->session->userdata('login_status');
		$this->login_uid 		= $this->session->userdata('login_uid');
		$this->login_level 		= $this->session->userdata('login_level');
		$cek = FALSE;
		if($this->login_level == 'operator sekolah' || $this->login_level == 'administrator' || $this->login_level == 'guru' || $this->login_level == 'kepala sekolah'){
			$cek = TRUE;
		}

		if($cek != TRUE)
		{
			$this->session->set_flashdata('msg', err_msg('Silahkan login untuk melanjutkan.'));
			redirect(site_url('login'));
		}

		$this->load->model('mata_pelajaran_materi_model');
		$this->load->model('master_mata_pelajaran/master_mata_pelajaran_model');
		$this->load->model('pengaturan_kelas/pengaturan_kelas_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->page_active 		= 'mata_pelajaran';
		$this->sub_page_active 	= 'mata_pelajaran';
	}


	public function index()
	{
		$param['sekolah']		 = $this->input->get('sekolah');
		$param['kelas']			 = $this->input->get('kelas');
		$param['mata_pelajaran'] = $this->input->get('mata_pelajaran');
		$param['keyword']		 = $this->input->get('q');
		$limit 				= 25;
		$uri_segment		= 3;
		$filter = array(
			'limit'				=> $limit,
			'offset'			=> $this->uri->segment($uri_segment),
			'keyword'			=> $param['keyword'],
			'sekolah'			=> $param['sekolah'],
			'kelas'				=> $param['kelas'],
			'mata_pelajaran'	=> $param['mata_pelajaran']
		);

		if($this->login_level == 'guru')
		{
			$filter['guru_id'] = $this->login_uid;
		}

		$param['data']	= $this->mata_pelajaran_materi_model->get_data($filter)->result();
		foreach($param['data'] as $key => $c)
		{
			$param['data'][$key]->kelas = $this->mata_pelajaran_materi_model->get_kelas($c->materi_id);
		}

		unset($filter['limit']);
		unset($filter['offset']);
		$total_rows 			= $this->mata_pelajaran_materi_model->get_data($filter)->num_rows();
		$param['pagination']	= paging('mata_pelajaran/index', $total_rows, $limit, $uri_segment);

		$param['opt_mata_pelajaran'] = $this->master_mata_pelajaran_model->get_opt('Semua Mata Pelajaran');
		$param['opt_sekolah']		 = $this->profil_sekolah_model->get_opt('Semua Sekolah');
		$param['main_content']		 = 'table';
		$param['page_active'] 		 = $this->page_active;
		$param['sub_page_active'] 	 = $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function detail($id)
	{
		if(!empty($id))
			{
				$param['data'] 		= $this->mata_pelajaran_materi_model->detail($id);
			}

		$param['main_content']		= 'mata_pelajaran/detail';
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
				$param['data'] 		= $this->mata_pelajaran_materi_model->get_data_row($id);
				// $param['opt_kelas']	= $this->mata_pelajaran_materi_model->get_kelas($id);
			}
		}

		$param['opt_sekolah']	     = $this->profil_sekolah_model->get_opt();
		$param['opt_mata_pelajaran'] = $this->master_mata_pelajaran_model->get_opt('Pilih Mata Pelajaran');

		$param['main_content']		= 'mata_pelajaran/form';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function submit($id = '')
	{
		$data_post = $this->input->post();
		$this->form_validation->set_rules('judul', 'Judul', 'required');
		$this->form_validation->set_rules('keterangan', 'Deskripsi', 'required');
		$this->form_validation->set_rules('mata_pelajaran_id', 'Mata Pelajaran', 'required');
		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('mata_pelajaran/form/' . $id);
		}
		else
		{
			if(!empty($_FILES['userfiles']['tmp_name']))
			{
				$config['upload_path']      = './uploads/materi_mata_pelajaran/';
	            $config['allowed_types']    = '*';

	            if (!is_dir($config['upload_path']))
	            {
	                mkdir($config['upload_path']);
	            }

	            $this->load->library('upload', $config);
	            if (!$this->upload->do_upload('userfiles'))
	            {
					$this->session->set_flashdata('msg', err_msg($this->upload->display_errors()));
					$this->session->set_flashdata('last_data', $data_post);
					redirect('mata_pelajaran/form/' . $id);
	            }
	            else
	            {
	            	$data_upload 		 	  = $this->upload->data();
	            	$data_post['lokasi_file'] = $config['upload_path'] . $data_upload['file_name'];
	            }
			}		

			$list_kelas = $data_post['kelas'];
			unset($data_post['kelas']);

			$data_post['user_id']	   = $this->login_uid;
			$data_post['waktu_upload'] = date('Y-m-d H:i:s');
			if(empty($id))
			{
				$proses = $this->mata_pelajaran_materi_model->insert($data_post);
				if($proses)
				{
					$id = $this->db->insert_id();
					$this->session->set_flashdata('msg', suc_msg('Data berhasil disimpan.'));
				}
				else
				{
					$this->session->set_flashdata('msg', err_msg('Data gagal disimpan, silahkan ulangi lagi.'));
					redirect('mata_pelajaran/form/' . $id);
				}
			}
			else
			{
				$proses = $this->mata_pelajaran_materi_model->update($data_post, $id);
				$this->session->set_flashdata('msg', suc_msg('Data berhasil diperbaharui.'));
			}

			if(!empty($id))
			{
				$this->mata_pelajaran_materi_model->delete_kelas($id);
				foreach($list_kelas as $key => $c)
				{
					$param_kelas[]	= array(
						'materi_id'	=> $id,
						'kelas_id'	=> $c
					);
				}

				if(!empty($param_kelas))
				{
					$this->mata_pelajaran_materi_model->insert_kelas($param_kelas);
				}
			}
		}
		redirect('mata_pelajaran');
	}

	public function hapus($id)
	{
		$data_sebelumnya = $this->mata_pelajaran_materi_model->get_data_row($id);
		if(empty($data_sebelumnya))
		{
			show_404();
		}
		elseif(!empty($data_sebelumnya->lokasi_file))
		{
			unlink($data_sebelumnya->lokasi_file);
		}

		$this->mata_pelajaran_materi_model->delete_kelas($id);
		$this->mata_pelajaran_materi_model->delete($id);

		$this->session->set_flashdata('msg', suc_msg('Data berhasil dihapus.'));
		redirect('mata_pelajaran');
	}

	public function ajax_form_kelas()
	{
		$data_post = $this->input->post();

		$data['kelas_selected'] = array();
		if(!empty($data_post['kelas']))
		{
			$data['kelas_selected'] = json_decode($data_post['kelas']);
		}

		$filter = array('sekolah_id' => $data_post['sekolah']);
		$get_data_kelas = $this->pengaturan_kelas_model->get_data($filter)->result();

		$data['kelas'] = array();
		foreach($get_data_kelas as $key => $c)
		{
			$data['kelas'][$c->kelas_id] = $c->jenjang . ' ' . $c->nama_jurusan . ' ' . $c->nama;
		}
		$this->load->view('form_kelas', $data);
	}	

	public function ajax_kelas()
	{
		$selected	= $this->input->get('selected');
		$sekolah_id = $this->input->get('sekolah_id');

		$result[''] = 'Semua Kelas';
		if(!empty($sekolah_id))
		{
			$filter = array('sekolah_id' => $sekolah_id);
			$get_data_kelas = $this->pengaturan_kelas_model->get_data($filter)->result();

			if(!empty($get_data_kelas))
			{
				foreach($get_data_kelas as $key => $c)
				{
					$result[$c->kelas_id] = $c->jenjang . ' ' . $c->nama_jurusan . ' ' . $c->nama;
				}
			}
		}
		echo form_dropdown('kelas', $result, $selected, 'class="form-control"');		
	}

}
