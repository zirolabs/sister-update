<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_absensi extends CI_Controller
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

		$this->load->model('rekap_absensi_model');
		$this->load->model('verifikasi_absensi/verifikasi_absensi_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->load->model('pengaturan_kelas/pengaturan_kelas_model');

		$this->page_active 	= 'laporan';
		$this->sub_page_active 	= 'rekap_absensi';
	}


	public function index()
	{
		$param['sekolah']	= $this->input->get('sekolah');
		$param['kelas']		= $this->input->get('kelas');
		$param['bulan']		= $this->input->get('bulan');
		if(empty($param['bulan']))
		{
			$param['bulan'] = date('m');
		}

		$param['tahun']	= $this->input->get('tahun');
		if(empty($param['tahun']))
		{
			$param['tahun'] = date('Y');
		}
		$param['url_param']	= http_build_query(array(
			'sekolah'	=> $param['sekolah'],
			'kelas'		=> $param['kelas'],
			'bulan'		=> $param['bulan'],
		));
		$param['opt_jenis']	= $this->verifikasi_absensi_model->get_jenis_absen();

		$uri_segment		= 3;
		$limit 				= 25;
		$param['limit']		= $limit;
		$param['offset']	= $this->uri->segment($uri_segment);		
		$param['data']		= $this->rekap_absensi_model->get_data($param)->result();

		unset($param['limit']);
		unset($param['offset']);
		$total_rows 			= $this->rekap_absensi_model->get_data($param)->num_rows();
		$param['pagination']	= paging('rekap_absensi/index', $total_rows, $limit, $uri_segment);				

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

		$param['main_content']		= 'rekap_absensi/table';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}


	public function get_kelas()
	{
		$selected	= $this->input->get('selected');
		$sekolah_id = $this->input->get('sekolah_id');

		$selected	= $this->input->get('selected');
		$sekolah_id = $this->input->get('sekolah_id');

		$result[''] = 'Semua Kelas';
		if(!empty($sekolah_id))
		{
			$result 	= $this->pengaturan_kelas_model->get_opt('Semua Kelas', $sekolah_id);
		}

		echo form_dropdown('kelas', $result, $selected, 'class="form-control"');
	}
        public function submit($id = '')
	{

		$data_post = $this->input->post();
                                print_r($data_post);
                die;
		$this->form_validation->set_rules('mata_pelajaran_id', 'Mata Pelajaran', 'required');
		$this->form_validation->set_rules('user_id', 'Guru', 'required');
		$this->form_validation->set_rules('jam_mulai', 'Jam Mulai', 'required');
		$this->form_validation->set_rules('jam_akhir', 'Jam Akhir', 'required');
		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('pengaturan_jadwal_pelajaran/form/' . $id . '?' . $url_param);
		}
		else
		{
                $proses = $this->pengaturan_jadwal_pelajaran_model->insert($data_post);
                    if($proses)
                    {
                        $this->session->set_flashdata('msg', suc_msg('Data berhasil disimpan.'));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg', err_msg('Data gagal disimpan, silahkan ulangi lagi.'));
                        $this->session->set_flashdata('last_data', $data_post);
                        redirect('pengaturan_jadwal_pelajaran/form/' . $id . '?' . $url_param);
                    }				
		}
		redirect('pengaturan_jadwal_pelajaran/index?' . $url_param);
	}
}
