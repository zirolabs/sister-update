<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Absensi_laporan_periode extends CI_Controller
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

		$this->load->model('absensi_laporan_periode_model');
		$this->load->model('verifikasi_absensi/verifikasi_absensi_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->load->model('pengaturan_kelas/pengaturan_kelas_model');

		$this->page_active 		= 'laporan';
		$this->sub_page_active 	= 'absensi_laporan_periode';
	}


	public function index()
	{
		$param['sekolah']		= $this->input->get('sekolah');
		$param['kelas']			= $this->input->get('kelas');
		$param['tanggal_awal']	= $this->input->get('tanggal_awal');
		if(empty($param['tanggal_awal']))
		{
			$param['tanggal_awal'] = date('Y-m-d', strtotime('-30 DAYS'));
		}

		$param['tanggal_akhir']	= $this->input->get('tanggal_akhir');
		if(empty($param['tanggal_akhir']))
		{
			$param['tanggal_akhir'] = date('Y-m-d');
		}

		$param['opt_jenis']	= $this->verifikasi_absensi_model->get_jenis_absen();

		$uri_segment		= 3;
		$limit 				= 25;
		$param['limit']		= $limit;
		$param['offset']	= $this->uri->segment($uri_segment);
		$param['data']		= $this->absensi_laporan_periode_model->get_data($param)->result();

		unset($param['limit']);
		unset($param['offset']);
		$total_rows 			= $this->absensi_laporan_periode_model->get_data($param)->num_rows();
		$param['pagination']	= paging('absensi_laporan_periode/index', $total_rows, $limit, $uri_segment);		

		$param['opt_sekolah']	= $this->profil_sekolah_model->get_opt('Semua Sekolah');
		$param['sekolah_label']	= 'Semua Sekolah';
		if(!empty($param['sekolah']))
		{
			foreach($param['opt_sekolah'] as $key => $c)
			{
				if($key == $param['sekolah'])
				{
					$param['sekolah_label'] = $c;
					break;
				}
			}
		}

		$param['kelas_label']	= 'Semua Kelas';
		if(!empty($param['kelas']))
		{
			$data_kelas = $this->pengaturan_kelas_model->get_data(array('kelas_id' => $param['kelas']))->row();
			$param['kelas_label'] 	= $data_kelas->jenjang . ' ' . $data_kelas->nama_jurusan . ' ' . $data_kelas->nama;
		}

		$param['main_content']		= 'absensi_laporan_periode/table';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}


	public function get_kelas()
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
