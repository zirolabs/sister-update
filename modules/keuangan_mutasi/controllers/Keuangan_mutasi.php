<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan_mutasi extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->login_status 	= $this->session->userdata('login_status');
		$this->login_uid 		= $this->session->userdata('login_uid');
		$this->login_level 		= $this->session->userdata('login_level');
		$cek = FALSE;
		if($this->login_level == 'operator sekolah' || $this->login_level == 'administrator' || $this->login_level == 'kepala sekolah'){
			$cek = TRUE;
		}

		if($cek != TRUE)
		{
			$this->session->set_flashdata('msg', err_msg('Silahkan login untuk melanjutkan.'));
			redirect(site_url('login'));
		}

		$this->load->model('keuangan_mutasi_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->load->model('pengaturan_kelas/pengaturan_kelas_model');
		$this->page_active 		= 'keuangan';
		$this->sub_page_active 	= 'keuangan_mutasi';
	}


	public function index()
	{
		$param['sekolah']		= $this->input->get('sekolah');
		$param['kelas']			= $this->input->get('kelas');
		$param['keyword']		= $this->input->get('q');
		$param['tanggal_awal']	= $this->input->get('tanggal_awal');
		$param['tanggal_akhir']	= $this->input->get('tanggal_akhir');

		if(!empty($param['sekolah']))
		{
			$limit 				= 25;
			$uri_segment		= 3;
			$filter = array(
				'limit'		=> $limit,
				'offset'	=> $this->uri->segment($uri_segment),
				'keyword'	=> $param['keyword'],
				'sekolah'	=> $param['sekolah'],
				'kelas'		=> $param['kelas'],
				'tgl_awal'	=> $param['tanggal_awal'],
				'tgl_akhir'	=> $param['tanggal_akhir']
			);

			$param['data']			= $this->keuangan_mutasi_model->get_data($filter)->result();

			unset($filter['limit']);
			unset($filter['offset']);
			$total_rows 			= $this->keuangan_mutasi_model->get_data($filter)->num_rows();
			$param['pagination']	= paging('keuangan_mutasi/index', $total_rows, $limit, $uri_segment);			
		}

		$param['opt_sekolah']		= $this->profil_sekolah_model->get_opt('Semua Sekolah');
		$param['main_content']		= 'keuangan_mutasi/table';
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

		$param['tanggal_awal']  = $this->input->get('tanggal_awal');
		$param['tanggal_akhir'] = $this->input->get('tanggal_akhir');

		$limit 			= 25;
		$uri_segment	= 4;
		$filter = array(
			'limit'		=> $limit,
			'offset'	=> $this->uri->segment($uri_segment),
			'user_id'	=> $id,
			'tgl_awal'	=> $param['tanggal_awal'],
			'tgl_akhir'	=> $param['tanggal_akhir'],
		);
		$param['data'] = $this->keuangan_mutasi_model->get_data($filter, 'detail')->result();

		unset($filter['limit']);
		unset($filter['offset']);
		$total_rows 			= $this->keuangan_mutasi_model->get_data($filter, 'detail')->num_rows();
		$param['pagination']	= paging('keuangan_mutasi/detail/' . $id, $total_rows, $limit, $uri_segment);			

		$param['nis']		= $param['data'][0]->nis;
		$param['nama']		= $param['data'][0]->nama_siswa;
		$param['kelas']		= $param['data'][0]->kelas;
		$param['sekolah']	= $param['data'][0]->sekolah;

		$param['main_content']		= 'keuangan_mutasi/detail';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function ajax_kelas()
	{
		$selected	= $this->input->get('selected');
		$sekolah_id = $this->input->get('sekolah_id');

		$result[''] = 'Semua Kelas';
		if(!empty($sekolah_id))
		{
			$result 	= $this->pengaturan_kelas_model->get_opt('Semua Kelas', $sekolah_id);
		}

		echo form_dropdown('kelas', $result, $selected, 'class="form-control"');		
	}
}
