<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subkategori extends CI_Controller
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

		$this->load->model('subkategori_model');
		$this->load->model('kategori/kategori_model');
		$this->page_active 		= 'pelanggaran';
		$this->sub_page_active 	= 'subkategori';
	}


	public function index()
	{
		$param['keyword']	= $this->input->get('q');
		$limit 				= 10;
		$uri_segment		= 3;
		$filter = array(
			'limit'		=> $limit,
			'offset'	=> $this->uri->segment($uri_segment),
			'keyword'	=> $param['keyword']
		);

		$param['data']			= $this->subkategori_model->get_data($filter)->result();

		unset($filter['limit']);
		unset($filter['offset']);
		$total_rows 			= $this->subkategori_model->get_data($filter)->num_rows();
		$param['pagination']	= paging('subkategori/index', $total_rows, $limit, $uri_segment);

		$param['main_content']	= 'subkategori/table';
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
				$param['data'] = $this->subkategori_model->get_data_row($id);
			}
		}
		$param['opt_kategori']		= $this->kategori_model->get_opt();
		$param['main_content']		= 'subkategori/form';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function submit($id = '')
	{
		$data_post = $this->input->post();
		$this->form_validation->set_rules('kategori', 'Kategori Pelanggaran', 'required');
		$this->form_validation->set_rules('subkategori', 'Deskripsi Pelanggaran', 'required');
		$this->form_validation->set_rules('point', 'Point', 'required');
		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('subkategori/form/' . $id);
		}
		else
		{
			$data = array(
				'id_kategori' 		=> $data_post['kategori'],
				'deskripsi_pelanggaran' 		=> $data_post['subkategori'],
				'point_pelanggaran' 		=> $data_post['point']
			);
			if(empty($id)){			
				$this->subkategori_model->insert($data);	
				$this->session->set_flashdata('msg', suc_msg('Data berhasil disimpan.'));
			}else{
				$this->subkategori_model->update($data, $id);			
				$this->session->set_flashdata('msg', suc_msg('Data berhasil diperbaharui.'));
			}
		}
		redirect('subkategori');
	}

	public function hapus($id)
	{
		$proses = $this->subkategori_model->delete($id);
		$this->session->set_flashdata('msg', suc_msg('Data berhasil dihapus.'));
		redirect('subkategori');
	}
}
