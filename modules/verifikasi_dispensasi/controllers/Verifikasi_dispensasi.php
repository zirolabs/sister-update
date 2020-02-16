<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Verifikasi_dispensasi extends CI_Controller
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

		$this->load->model('verifikasi_dispensasi_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->load->model('manajemen_siswa/manajemen_siswa_model');
		$this->load->model('pengaturan_kelas/pengaturan_kelas_model');
		$this->page_active 		= 'laporan';
		$this->sub_page_active 	= 'verifikasi_dispensasi';
	}


	public function index()
	{
		$param['sekolah']	= $this->input->get('sekolah');
		$param['kelas']		= $this->input->get('kelas');
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

		$param['data']			= $this->verifikasi_dispensasi_model->get_data($filter)->result();

		unset($filter['limit']);
		unset($filter['offset']);
		$total_rows 			= $this->verifikasi_dispensasi_model->get_data($filter)->num_rows();
		$param['pagination']	= paging('verifikasi_dispensasi/index', $total_rows, $limit, $uri_segment);

		$param['opt_sekolah']		= $this->profil_sekolah_model->get_opt('Semua Sekolah');
		$param['main_content']		= 'verifikasi_dispensasi/table';
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
				$param['data'] 		= $this->verifikasi_dispensasi_model->get_data(array('dispensasi_id' => $id))->row();
			}
		}

		$param['opt_sekolah']		= $this->profil_sekolah_model->get_opt();
		$param['main_content']		= 'verifikasi_dispensasi/form';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function submit($id = '')
	{
		$data_post = $this->input->post();
		$this->form_validation->set_rules('sekolah_id', 'Sekolah', 'required');
		$this->form_validation->set_rules('nis', 'NIS Siswa', 'required');
		$this->form_validation->set_rules('tgl_mulai', 'Tanggal Mulai', 'required');
		$this->form_validation->set_rules('tgl_selesai', 'Tanggal Selesai', 'required');
		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('verifikasi_dispensasi/form/' . $id);
		}
		else
		{
			$param_cek_nis = array(
				'nis'		=> $data_post['nis'],
				'sekolah'	=> $data_post['sekolah_id']
			);
			$cek_nis 	= $this->manajemen_siswa_model->get_data($param_cek_nis)->row();
			if(empty($cek_nis))
			{
				$this->session->set_flashdata('msg', err_msg('NIS tidak valid'));
				$this->session->set_flashdata('last_data', $data_post);
				redirect('verifikasi_dispensasi/form/' . $id);
				exit;
			}

			if(!$this->validasi_tanggal($data_post['tgl_mulai'], $data_post['tgl_selesai']))
			{
				$this->session->set_flashdata('msg', err_msg('Tanggal tidak valid'));
				$this->session->set_flashdata('last_data', $data_post);
				redirect('verifikasi_dispensasi/form/' . $id);
				exit;
			}

			$param_db = array(
				'user_id'		=> $cek_nis->user_id,
				'tgl_mulai'		=> $data_post['tgl_mulai'],
				'tgl_selesai'	=> $data_post['tgl_selesai'],
				'keterangan'	=> $data_post['keterangan']
			);

			if(empty($id))
			{
				$proses = $this->verifikasi_dispensasi_model->insert($param_db);
				if($proses)
				{
					$this->session->set_flashdata('msg', suc_msg('Data berhasil disimpan.'));
				}
				else
				{
					$this->session->set_flashdata('msg', err_msg('Data gagal disimpan, silahkan ulangi lagi.'));
					redirect('verifikasi_dispensasi/form/' . $id);
				}
			}
			else
			{
				$proses = $this->verifikasi_dispensasi_model->update($param_db, $id);
				if($proses)
				{
					$this->session->set_flashdata('msg', suc_msg('Data berhasil diperbaharui.'));
				}
				else
				{
					$this->session->set_flashdata('msg', suc_msg('Data gagal diperbaharui, tidak ada perubahan data.'));
					redirect('verifikasi_dispensasi/form/' . $id);
				}
			}
			redirect('verifikasi_dispensasi/index?sekolah=' . $data_post['sekolah_id'] . '&kelas=' . $data_post['kelas']);
		}
	}

	public function validasi_tanggal($tanggal_mulai, $tanggal_selesai)
	{
		$tanggal_sekarang = strtotime(date('Y-m-d 00:00:00'));
		$tanggal_mulai 	  = strtotime($tanggal_mulai);
		$tanggal_selesai  = strtotime($tanggal_selesai);

		if($tanggal_mulai < $tanggal_sekarang)
		{
			return false;
		}
		else
		{
			if($tanggal_selesai < $tanggal_mulai)
			{
				return false;
			}
		}

		return true;		
	}

	public function hapus($id)
	{
		$this->verifikasi_dispensasi_model->delete($id);
		$this->session->set_flashdata('msg', suc_msg('Berhasil menghapus data.'));
		redirect($_SERVER['HTTP_REFERER']);
	}

	public function ajax_cek_nis()
	{
		$respon = array('status'	=> '201');

		$nis 	 = $this->input->post('nis');
		$sekolah = $this->input->post('sekolah');
		if(!empty($nis) && !empty($sekolah))
		{
			$this->load->model('manajemen_siswa/manajemen_siswa_model');
			$param = array(
				'nis'		=> $nis,
				'sekolah'	=> $sekolah
			);
			$get_data = $this->manajemen_siswa_model->get_data($param)->row();
			if(!empty($get_data))
			{
				$respon = array(
					'status'	=> '200',
					'data'		=> array(
						'nama'		=> $get_data->nama,
						'nis'		=> $get_data->nis,
						'kelas'		=> $get_data->kelas,
						'sekolah'	=> $get_data->sekolah
					)
				);
			}
		}
		echo json_encode($respon);
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
