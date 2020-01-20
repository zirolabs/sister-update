<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monitoring extends CI_Controller
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

		$this->load->model('manajemen_siswa/manajemen_siswa_model');
		$this->load->model('manajemen_user/manajemen_user_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->load->model('pengaturan_kelas/pengaturan_kelas_model');

		$this->page_active 		= 'monitoring';
		$this->sub_page_active 	= 'monitoring';
	}


	public function index()
	{
		$param['sekolah']	= $this->input->get('sekolah');
		$param['kelas']		= $this->input->get('kelas_id');
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

		$param['data']			= $this->manajemen_siswa_model->get_data($filter)->result();

		unset($filter['limit']);
		unset($filter['offset']);
		$total_rows 			= $this->manajemen_siswa_model->get_data($filter)->num_rows();
		$param['pagination']	= paging('monitoring/index', $total_rows, $limit, $uri_segment);

		$param['opt_sekolah'] = $this->profil_sekolah_model->get_opt('Semua Sekolah');

		$param['main_content']		= 'monitoring/table';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function detail($id = '')
	{
		if(empty($id))
		{
			show_404();
		}

		$param['data'] = $this->manajemen_siswa_model->get_data_row($id);
		if(empty($param['data']))
		{
			show_404();
		}

		$param['main_content']		= 'monitoring/detail';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);

	}

	public function get_kelas()
	{
		$selected	= $this->input->get('selected');
		$sekolah_id = $this->input->get('sekolah_id');

		$result['']	= 'Semua Kelas';
		if(!empty($sekolah_id))
		{
			$result = $this->pengaturan_kelas_model->get_opt('Pilih Kelas', $sekolah_id);
		}
		echo form_dropdown('kelas_id', $result, $selected, 'class="form-control"');
	}	
}
