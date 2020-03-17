<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jurnal_guru extends CI_Controller
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

		$this->load->model('jurnal_guru_model');
		$this->load->model('master_mata_pelajaran/master_mata_pelajaran_model');
		$this->load->model('pengaturan_kelas/pengaturan_kelas_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
                $this->load->model('pengaturan_jadwal_pelajaran/pengaturan_jadwal_pelajaran_model');
		$this->page_active 	= 'jurnal_guru';
		$this->sub_page_active 	= 'jurnal_guru';
	}


	public function index()
	{
		$param['sekolah']	= $this->input->get('sekolah');
		$param['kelas']		= $this->input->get('kelas_id');
		$param['keyword']	= $this->input->get('q');
                $param['hari']		= $this->input->get('hari');
		$limit 			= 25;
		$uri_segment		= 3;
             
		$filter = array(
                    'limit'	=> $limit,
                    'offset'	=> $this->uri->segment($uri_segment),
                    'keyword'	=> $param['keyword'],
                    'sekolah'	=> $param['sekolah'],
                    'kelas'	=> $param['kelas']
		);
                $param['url_param']	= http_build_query(array(
			'sekolah'	=> $param['sekolah'],
			'kelas'		=> $param['kelas'],
			'hari'		=> $param['hari'],
		));
//                print_r($param['sekolah']);
//                die;
		$param['data']	= $this->jurnal_guru_model->get_data($filter)->result();

		unset($filter['limit']);
		unset($filter['offset']);
		$total_rows                 = $this->jurnal_guru_model->get_data($filter)->num_rows();
		$param['pagination']        = paging('jurnal_guru/index', $total_rows, $limit, $uri_segment);
		$param['opt_sekolah']       = $this->profil_sekolah_model->get_opt('Semua Sekolah');
		$param['main_content']      = 'jurnal_guru/table';
		$param['page_active']       = $this->page_active;
		$param['sub_page_active']   = $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function form($id = '')
	{
		$param = $this->input->get();
		$param['url_param']	= http_build_query($param);
		$param['msg']		= $this->session->flashdata('msg');
		$param['id']		= $id;
//                print_r($this->login_uid);
//                die;
		$last_data 	= $this->session->flashdata('last_data');
		if(!empty($last_data))
		{
			$param['data'] = (object) $last_data;
		}
		else
		{
			if(!empty($id))
			{
				$param['data'] = $this->jurnal_guru_model->get_data_row($id);
			}
		}
//                print_r($param['data']);
//                die;
//
//		$param['opt_mata_pelajaran']	= $this->master_mata_pelajaran_model->get_opt();
//		$param['data_sekolah']			= $this->profil_sekolah_model->get_data_row($param['sekolah']);
//		$param['data_kelas']			= $this->pengaturan_kelas_model->get_data(array('kelas_id' => $param['kelas']))->row();

//		$get_guru = $this->manajemen_guru_model->get_data(array('sekolah' => $param['sekolah']))->result();
//		$param['opt_guru']	= array();
//		foreach($get_guru as $key => $c)
//		{
//			$param['opt_guru'][$c->user_id] = $c->nip . ' - ' . $c->nama;
//		}

		$param['main_content']		= 'jurnal_guru/form';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}
	public function lihat($id = '')
	{
		$param['sekolah']	= $this->input->get('sekolah');
		$param['kelas']		= $this->input->get('kelas_id');
		$param['keyword']	= $this->input->get('q');
                $param['hari']		= $this->input->get('hari');
		$limit 			= 25;
		$uri_segment		= 3;
             
		$filter = array(
                    'limit'	=> $limit,
                    'offset'	=> $this->uri->segment($uri_segment),
                    'keyword'	=> $param['keyword'],
                    'sekolah'	=> $param['sekolah'],
                    'kelas'	=> $param['kelas']
		);
		$param['data'] = $this->jurnal_guru_model->get_data_row_jurnal($id)->result();

//                print_r($param['data']);
//                die;
//
//		$param['opt_mata_pelajaran']	= $this->master_mata_pelajaran_model->get_opt();
//		$param['data_sekolah']			= $this->profil_sekolah_model->get_data_row($param['sekolah']);
//		$param['data_kelas']			= $this->pengaturan_kelas_model->get_data(array('kelas_id' => $param['kelas']))->row();

//		$get_guru = $this->manajemen_guru_model->get_data(array('sekolah' => $param['sekolah']))->result();
//		$param['opt_guru']	= array();
//		foreach($get_guru as $key => $c)
//		{
//			$param['opt_guru'][$c->user_id] = $c->nip . ' - ' . $c->nama;
//		}
		unset($filter['limit']);
		unset($filter['offset']);
                $total_rows                 = $this->jurnal_guru_model->get_data_row_jurnal($id)->num_rows();
		$param['pagination']        = paging('jurnal_guru/index', $total_rows, $limit, $uri_segment);
		$param['main_content']		= 'jurnal_guru/table_1';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function submit($id = '')
	{
		$data_post = $this->input->post();
		$this->form_validation->set_rules('isi_materi', 'Materi', 'required');
		$this->form_validation->set_rules('target', 'Target Belajar', 'required');
		if($this->form_validation->run() == false)
		{
                    $this->session->set_flashdata('msg', err_msg(validation_errors()));
                    redirect('jurnal_guru/form/' .$data_post["id"]);
		}
		else
		{
                    $this->jurnal_guru_model->insert($data_post);
                    $this->session->set_flashdata('msg', suc_msg('Data berhasil diperbaharui.'));
		}
		redirect('jurnal_guru');
	}

	public function hapus()
	{
			$jurnal = $this->input->post('id_jurnal');
			$jadwal = $this->input->post('id_jadwal');
            $this->jurnal_guru_model->delete($jurnal);

            $this->session->set_flashdata('msg', suc_msg('Data berhasil dihapus.'));
            redirect('jurnal_guru/lihat/'.$jadwal);
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
		echo form_dropdown('kelas_id', $result, $selected, 'class="form-control"');		
	}

}
