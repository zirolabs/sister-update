<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Riwayat_produk_kantin extends CI_Controller
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

		$this->load->model('riwayat_produk_kantin_model');
		$this->load->model('produk_kantin/produk_kantin_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->page_active 		= 'kantin';
		$this->sub_page_active 	= 'riwayat_produk_kantin';
	}

	public function index()
	{
		$param['sekolah']	= $this->input->get('sekolah');
		$param['keyword']	= $this->input->get('q');
		$limit 				= 25;
		$uri_segment		= 3;
		$filter = array(
			'limit'				=> $limit,
			'offset'			=> $this->uri->segment($uri_segment),
			'keyword'			=> $param['keyword'],
			'sekolah'			=> $param['sekolah'],
			'jenis_transaksi'	=> 'Transaksi di Kantin.',
		);

		$param['data'] 		 = $this->riwayat_produk_kantin_model->get_data($filter)->result();

		unset($filter['limit']);
		unset($filter['offset']);
		$total_rows 				= $this->riwayat_produk_kantin_model->get_data($filter)->num_rows();
		$param['pagination']		= paging('riwayat_produk_kantin/index', $total_rows, $limit, $uri_segment);
		$param['opt_sekolah']		= $this->profil_sekolah_model->get_opt('Semua Sekolah');
		$param['main_content']		= 'riwayat_produk_kantin/table';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function detail()
	{	
		$param['sekolah']	= $this->input->get('sekolah');
		$param['keyword']	= $this->input->get('q');
		$param['mutasi_id']	= $this->input->get('mutasi_id');
		$limit 				= 25;
		$uri_segment		= 3;
		$filter = array(
			'limit'				=> $limit,
			'offset'			=> $this->uri->segment($uri_segment),
			'keyword'			=> $param['keyword'],
			'mutasi_id'			=> $param['mutasi_id']
		);

		$filter_id = array(
			'mutasi_id'			=> $param['mutasi_id']
		);

		$param['data'] 		 		= $this->riwayat_produk_kantin_model->get_detail_data($filter)->result();
		$param['data_mutasi'] 		= $this->riwayat_produk_kantin_model->get_data($filter_id)->row();
		unset($filter['limit']);
		unset($filter['offset']);
		$total_rows 				= $this->riwayat_produk_kantin_model->get_detail_data($filter)->num_rows();
		$param['pagination']		= paging('riwayat_produk_kantin/detail', $total_rows, $limit, $uri_segment);
		$param['opt_sekolah']		= $this->profil_sekolah_model->get_opt('Semua Sekolah');
		$param['main_content']		= 'riwayat_produk_kantin/detail';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

}
