<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mata_pelajaran_nilai extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->login_status 	= $this->session->userdata('login_status');
		$this->login_uid 		= $this->session->userdata('login_uid');
		$this->login_level 		= $this->session->userdata('login_level');
		if($this->login_status != 'ok')
		{
			$this->session->set_flashdata('msg', err_msg('Silahkan login untuk melanjutkan.'));
			redirect(site_url('login'));
		}

		$this->load->model('mata_pelajaran_nilai_model');
		$this->load->model('master_mata_pelajaran/master_mata_pelajaran_model');
		$this->load->model('pengaturan_semester/pengaturan_semester_model');
		$this->load->model('pengaturan_kelas/pengaturan_kelas_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->page_active 		= 'mata_pelajaran';
		$this->sub_page_active 	= 'mata_pelajaran_nilai';
	}


	public function index()
	{
		$param['sekolah']		 = $this->input->get('sekolah');
		$param['kelas']			 = $this->input->get('kelas');
		$param['mata_pelajaran'] = $this->input->get('mata_pelajaran');
		$param['semester'] 		 = $this->input->get('semester');
		$param['jenis'] 		 = $this->input->get('jenis');
		$param['url_param']		 = http_build_query($this->input->get());
		$filter = array(
			'sekolah'			=> $param['sekolah'],
			'kelas'				=> $param['kelas'],
			'mata_pelajaran'	=> $param['mata_pelajaran'],
			'jenis'				=> $param['jenis']
		);

		if($this->login_level == 'guru')
		{
			$filter['guru_id']  = $this->login_uid;
		}

		if(!empty($param['sekolah']) && !empty($param['kelas']) && !empty($param['mata_pelajaran']) && !empty($param['jenis']))
		{
			$data_get 				= $this->mata_pelajaran_nilai_model->get_data($filter)->result();
			$param['list_nilai']	= array();
			$param['data']			= array();
			foreach($data_get as $key => $c)
			{
				$param['list_nilai'][$c->nilai_id]  = array(
					'id'			=> $c->nilai_id,
					'keterangan'	=> $c->keterangan
				);
				$param['data'][$c->user_id]['nis']  = $c->nis_siswa;		
				$param['data'][$c->user_id]['nama'] = $c->nama_siswa;		
				$param['data'][$c->user_id]['detail'][$c->nilai_id] = $c->nilai;		
			}

			usort($param['list_nilai'], function($a, $b) {
			    return $a['keterangan'] - $b['keterangan'];
			});			
		}

		$param['opt_sekolah']		 = $this->profil_sekolah_model->get_opt('Pilih Sekolah');
		$param['opt_mata_pelajaran'] = $this->master_mata_pelajaran_model->get_opt('Pilih Mata Pelajaran');
		$param['opt_semester']		 = $this->pengaturan_semester_model->get_opt();
		$param['opt_jenis']		 	 = $this->mata_pelajaran_nilai_model->get_jenis();
		
		$param['main_content']		 = 'table';
		$param['page_active'] 		 = $this->page_active;
		$param['sub_page_active'] 	 = $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function form($id = '')
	{
		$param = $this->input->get();
		$param['url_param']	= http_build_query($param);
		$param['msg']		= $this->session->flashdata('msg');
		$param['id']		= $id;

		$last_data 	= $this->session->flashdata('last_data');
		if(!empty($last_data))
		{
			$param['data'] = (object) $last_data;
		}

		$param['data'] 		= $this->mata_pelajaran_nilai_model->get_data_siswa($param)->result();
		foreach($param['data'] as $key => $c)
		{
			if(!empty($c->keterangan))
			{
				$param['keterangan']	= $c->keterangan;
				break;
			}
		}

		$param['data_sekolah']			= $this->profil_sekolah_model->get_data_row($param['sekolah']);
		$param['data_kelas']			= $this->pengaturan_kelas_model->get_data(array('kelas_id' => $param['kelas']))->row();
		$param['data_mata_pelajaran']	= $this->master_mata_pelajaran_model->get_data_row($param['mata_pelajaran']);
		$param['data_semester']			= $this->pengaturan_semester_model->get_data_row($param['semester']);
		$param['data_jenis']			= $this->mata_pelajaran_nilai_model->get_jenis($param['jenis']);

		$param['main_content']		= 'form';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function submit($id = '')
	{
		$param 	   = $this->input->get();
		$data_post = $this->input->post();
		$url_param = http_build_query($param);

		$param_input = array(
			'mata_pelajaran_id'	=> $param['mata_pelajaran'],
			'sekolah_id'		=> $param['sekolah'],
			'kelas_id'			=> $param['kelas'],
			'user_id'			=> $this->login_uid,
			'semester_id'		=> $param['semester'],
			'jenis'				=> $param['jenis'],
			'keterangan'		=> @$data_post['keterangan'],
		);

		if(empty($id))
		{
			$proses = $this->mata_pelajaran_nilai_model->insert($param_input);		
			if($proses)
			{
				$id = $this->db->insert_id();
				$this->session->set_flashdata('msg', suc_msg('Data berhasil disimpan.'));				
			}
			else
			{
				$this->session->set_flashdata('msg', err_msg('Data gagal disimpan.'));				
			}
		}
		else
		{
			$proses = $this->mata_pelajaran_nilai_model->update($param_input, $id);		
			$this->session->set_flashdata('msg', suc_msg('Data berhasil disimpan.'));							
		}

		if(!empty($id))
		{
			$param_nilai = array();
			foreach($data_post['nilai'] as $key => $c)
			{
				$param_nilai[] = array(
					'nilai_id'	=> $id,
					'user_id'	=> $key,
					'nilai'		=> empty($c) ? 0 : $c
				);
				$this->mata_pelajaran_nilai_model->insert_nilai($param_nilai);
			}
		}
		redirect('mata_pelajaran_nilai/index?' . $url_param);
	}

	public function hapus($id)
	{
		$url_param = http_build_query($this->input->get());
		$this->mata_pelajaran_nilai_model->delete_nilai($id);
		$this->mata_pelajaran_nilai_model->delete($id);

		$this->session->set_flashdata('msg', suc_msg('Data berhasil dihapus.'));
		redirect('mata_pelajaran_nilai/index?' . $url_param);
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
