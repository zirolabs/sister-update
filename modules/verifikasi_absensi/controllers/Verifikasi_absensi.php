<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Verifikasi_absensi extends CI_Controller
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

		$this->load->model('verifikasi_absensi_model');
		$this->load->model('pengaturan_kelas/pengaturan_kelas_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->load->model('pengaturan_kelas/pengaturan_kelas_model');

		$this->page_active 		= 'laporan';
		$this->sub_page_active 	= 'verifikasi_absensi';
	}

	public function index()
	{
		$param['sekolah']	= $this->input->get('sekolah');
		$param['kelas']		= $this->input->get('kelas');
		$param['jenis']		= $this->input->get('jenis');
		$param['tanggal']	= $this->input->get('tanggal');
		if(empty($param['tanggal']))
		{
			$param['tanggal'] = date('Y-m-d');
		}

		$uri_segment		= 3;
		$limit 				= 25;
		$param['limit']		= $limit;
		$param['offset']	= $this->uri->segment($uri_segment);

		if(empty($param['jenis']))
		{
			$param['data']	= $this->verifikasi_absensi_model->get_data_absen($param)->result();			
			// pre($this->db->last_query());

			unset($param['limit']);
			unset($param['offset']);
			$total_rows 			= $this->verifikasi_absensi_model->get_data_absen($param)->num_rows();
			$param['pagination']	= paging('verifikasi_absensi/index', $total_rows, $limit, $uri_segment);
		}
		else if($param['jenis'] == 'belum_absen')
		{
			$param['data']	= $this->verifikasi_absensi_model->get_data_belum_absen($param)->result();

			unset($param['limit']);
			unset($param['offset']);
			$total_rows 			= $this->verifikasi_absensi_model->get_data_belum_absen($param)->num_rows();
			$param['pagination']	= paging('verifikasi_absensi/index', $total_rows, $limit, $uri_segment);
		}

		$param['opt_sekolah'] 		= $this->profil_sekolah_model->get_opt('Semua Sekolah');
		$param['main_content']		= 'verifikasi_absensi/table';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function sudah_absen($absensi_id = '')
	{
		if(empty($absensi_id))
		{
			show_404();
		}

		$param['kelas']		= $this->input->get('kelas');
		$param['sekolah']	= $this->input->get('sekolah');
		$param['tanggal']	= $this->input->get('tanggal');
		if(empty($param['tanggal']))
		{
			show_404();
		}

		$param['msg']		= $this->session->flashdata('msg');
		$param['id']		= $absensi_id;
		
		$param['data']			= $this->verifikasi_absensi_model->get_data_absen(array('absensi_id' => $absensi_id))->row();
		if(empty($param['kelas']))
		{
			$param['kelas'] 	= $param['data']->kelas_id;
		}

		$param['opt_status']		= $this->verifikasi_absensi_model->get_jenis_absen();
		$param['main_content']		= 'verifikasi_absensi/form_sudah_absensi';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function submit_sudah_absen($absensi_id = '')
	{
		if(empty($absensi_id))
		{
			show_404();
		}

		$sekolah 	= $this->input->get('sekolah');
		if(empty($sekolah))
		{
			show_404();
		}		

		$kelas		= $this->input->get('kelas');
		if(empty($kelas))
		{
			show_404();
		}

		$tanggal	= $this->input->get('tanggal');
		if(empty($tanggal))
		{
			show_404();
		}

		$data_post 	= $this->input->post();
		$this->form_validation->set_rules('status', 'Status', 'required');
		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('verifikasi_absensi/sudah_absen/' . $absensi_id . '?tanggal=' . $tanggal . '&kelas=' . $kelas . '&sekolah=' . $sekolah);
		}
		else
		{
			$tanggal_temp	= $tanggal;
			$sesi_id 		= 0;
			$telat 			= 0;
			$jam_id			= 0;
			if($data_post['status'] == 'hadir')
			{
				if(empty($data_post['waktu_masuk']))
				{
					$this->session->set_flashdata('msg', err_msg('Masukkan Waktu Masuk.'));
					$this->session->set_flashdata('last_data', $data_post);
					redirect('verifikasi_absensi/sudah_absen/' . $absensi_id . '?tanggal=' . $tanggal_temp . '&kelas=' . $kelas . '&sekolah=' . $sekolah);				
				}
				else
				{
					$tanggal			.= ' ' . $data_post['waktu_masuk'];
					$verifikasi_telat 	= $this->verifikasi_absensi_model->verifikasi_jam_masuk($tanggal, $sekolah);
					if($verifikasi_telat['status'] == 201)
					{
						$this->session->set_flashdata('msg', err_msg('Waktu Masuk tidak valid.'));
						$this->session->set_flashdata('last_data', $data_post);
						redirect('verifikasi_absensi/sudah_absen/' . $absensi_id . '?tanggal=' . $tanggal_temp . '&kelas=' . $kelas . '&sekolah=' . $sekolah);										
					}

					$jam_id  = $verifikasi_telat['jam_id'];
					$sesi_id = $verifikasi_telat['sesi_id'];
					$telat 	 = $verifikasi_telat['telat'];
				}
			}

			$param = array(
				'kelas_id'		=> $kelas,
				'jam_id'		=> $jam_id,
				'sesi_id'		=> $sesi_id,
				'status'		=> $data_post['status'],
				'waktu'			=> $tanggal,
				'telat'			=> $telat,
				'keterangan'	=> $data_post['keterangan']				
			);
			$proses = $this->verifikasi_absensi_model->update_absensi($param, $absensi_id);
			if($proses)
			{
				$this->session->set_flashdata('msg', suc_msg('Data berhasil disimpan.'));
			}
			else
			{
				$this->session->set_flashdata('msg', err_msg('Data gagal disimpan, silahkan ulangi lagi.'));
				$this->session->set_flashdata('last_data', $data_post);
				redirect('verifikasi_absensi/sudah_absen/' . $absensi_id . '?tanggal=' . $tanggal_temp . '&kelas=' . $kelas . '&sekolah=' . $sekolah);										
			}
		}
		redirect('verifikasi_absensi/index?jenis=&' . '?tanggal=' . $tanggal_temp . '&kelas=' . $kelas . '&sekolah=' . $sekolah);

	}

	public function belum_absen($user_id = '')
	{
		if(empty($user_id))
		{
			show_404();
		}

		$param['sekolah']	= $this->input->get('sekolah');
		$param['kelas']		= $this->input->get('kelas');
		$param['msg']		= $this->session->flashdata('msg');
		$param['id']		= $user_id;
		$param['tanggal']	= $this->input->get('tanggal');
		if(empty($param['tanggal']))
		{
			$param['tanggal'] = date('Y-m-d');
		}
		
		$last_data 	= $this->session->flashdata('last_data');
		if(!empty($last_data))
		{
			$param['data'] = (object) $last_data;
		}

		$param['info_siswa'] = $this->verifikasi_absensi_model->get_data_belum_absen(array(
			'user_id' => $user_id, 
			'tanggal' => $param['tanggal'])
		)->row();

		if(empty($param['info_siswa']))
		{
			show_404();
		}

		if(!empty($param['info_siswa']->telat_waktu))
		{
			@$param['data']->waktu_masuk = date('H:i', strtotime($param['info_siswa']->telat_waktu));
		}
		// pre($param['info_siswa']);

		// $this->load->model('manajemen_siswa/manajemen_siswa_model');
		// $param['info_siswa'] = $this->manajemen_siswa_model->get_data(array('user_id' => $param['id']))->row();
		// $param['info_telat'] = $this->verifikasi_absensi_model->get_telat($param['id'], $param['tanggal'])->row();
		// if(!empty($param['info_telat']))
		// {
		// 	@$param['data']->waktu_masuk = date('H:i', strtotime($param['info_telat']->waktu));
		// }

		if(empty($param['kelas']))
		{
			$param['kelas']	= $param['info_siswa']->kelas_id;
		}

		if(empty($param['sekolah']))
		{
			$param['sekolah']	= $param['info_siswa']->sekolah_id;
		}

		$param['opt_status']		= $this->verifikasi_absensi_model->get_jenis_absen();
		$param['main_content']		= 'verifikasi_absensi/form_belum_absensi';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function submit_belum_absen($user_id = '')
	{
		if(empty($user_id))
		{
			show_404();
		}

		$sekolah 	= $this->input->get('sekolah');
		if(empty($sekolah))
		{
			show_404();
		}		

		$kelas		= $this->input->get('kelas');
		if(empty($kelas))
		{
			show_404();
		}		

		$tanggal 	= $this->input->get('tanggal');
		if(empty($tanggal))
		{
			show_404();
		}

		$data_post 	= $this->input->post();
		$this->form_validation->set_rules('status', 'Status', 'required');
		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('verifikasi_absensi/belum_absen/' . $user_id . '?tanggal=' . $tanggal . '&kelas=' . $kelas . '&sekolah=' . $sekolah);
		}
		else
		{
			$tanggal_temp	= $tanggal;
			$sesi_id 		= 0;
			$telat 			= 0;
			$jam_id			= 0;
			if($data_post['status'] == 'hadir')
			{
				if(empty($data_post['waktu_masuk']))
				{
					$this->session->set_flashdata('msg', err_msg('Masukkan Waktu Masuk.'));
					$this->session->set_flashdata('last_data', $data_post);
					redirect('verifikasi_absensi/belum_absen/' . $user_id . '?tanggal=' . $tanggal_temp . '&kelas=' . $kelas . '&sekolah=' . $sekolah);				
				}
				else
				{
					$tanggal			.= ' ' . $data_post['waktu_masuk'];
					$verifikasi_telat 	= $this->verifikasi_absensi_model->verifikasi_jam_masuk($tanggal, $sekolah);
					if(empty($verifikasi_telat['sesi_id']))
					{
						$this->session->set_flashdata('msg', err_msg('Waktu Masuk tidak valid.'));
						$this->session->set_flashdata('last_data', $data_post);
						redirect('verifikasi_absensi/belum_absen/' . $user_id . '?tanggal=' . $tanggal_temp . '&kelas=' . $kelas . '&sekolah=' . $sekolah);										
					}

					$jam_id  = $verifikasi_telat['jam_id'];
					$sesi_id = $verifikasi_telat['sesi_id'];
					$telat 	 = $verifikasi_telat['telat'];
				}
			}

			$param = array(
				'user_id'		=> $user_id,
				'kelas_id'		=> $kelas,
				'jam_id'		=> $jam_id,
				'sesi_id'		=> $sesi_id,
				'status'		=> $data_post['status'],
				'waktu'			=> $tanggal,
				'telat'			=> $telat,
				'keterangan'	=> $data_post['keterangan']				
			);
			$proses = $this->verifikasi_absensi_model->insert_absensi($param);
			if($proses)
			{
				$this->session->set_flashdata('msg', suc_msg('Data berhasil disimpan.'));
			}
			else
			{
				$this->session->set_flashdata('msg', err_msg('Data gagal disimpan, silahkan ulangi lagi.'));
				$this->session->set_flashdata('last_data', $data_post);
				redirect('verifikasi_absensi/belum_absen/' . $user_id . '?tanggal=' . $tanggal_temp . '&kelas=' . $kelas . '&sekolah=' . $sekolah);										
			}
		}
		redirect('verifikasi_absensi/index?jenis=belum_absen&tanggal=' . $tanggal_temp . '&kelas=' . $kelas . '&sekolah=' . $sekolah);
	}

	public function belum_absen_massal()
	{
		$param['tanggal']	= $this->input->get('tanggal');
		$param['sekolah']	= $this->input->get('sekolah');
		$param['kelas']		= $this->input->get('kelas');		
		$param['user_id'] 	= $this->input->post('user_id');

		if(empty($param['user_id']))
		{
			$this->session->set_flashdata('msg', err_msg('Pilih siswa yang akan diverifikasi.'));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('verifikasi_absensi/belum_absen?tanggal=' . $param['tanggal'] . '&kelas=' . $param['kelas'] . '&sekolah=' . $param['kelas']);										
		}
		else
		{
			$param['info_siswa'] = $this->verifikasi_absensi_model->get_data_belum_absen(array(
				'user_id' => $param['user_id'], 
				'tanggal' => $param['tanggal'])
			)->result();

			if(empty($param['info_siswa']))
			{
				show_404();
			}

			$param['opt_status']		= $this->verifikasi_absensi_model->get_jenis_absen();
			$param['main_content']		= 'verifikasi_absensi/form_belum_absensi_massal';
			$param['page_active'] 		= $this->page_active;
			$param['sub_page_active'] 	= $this->sub_page_active;
			$this->templates->load('main_templates', $param);
		}
	}

	public function submit_belum_absen_massal()
	{
		$tanggal   = $this->input->get('tanggal');
		$kelas     = $this->input->get('kelas');
		$sekolah   = $this->input->get('sekolah');

		$data_post = $this->input->post();
		$msg 	   = '';

		$input_status 	  = @$data_post['status'];
		$input_waktu 	  = @$data_post['waktu_masuk'];
		$input_keterangan = @$data_post['keterangan'];

		foreach($data_post['nis'] as $key => $c)
		{
			$input_nis		  = @$c; 
			$input_nama		  = @$data_post['nama'][$key]; 
			$input_kelas	  = @$data_post['kelas'][$key]; 
			$input_sekolah	  = @$data_post['sekolah'][$key];
			$input_tanggal	  = $tanggal;

			$sesi_id = 0;
			$jam_id	 = 0;
			$telat 	 = 0;

			if($input_status == 'hadir')
			{
				if(empty($input_waktu))
				{
					$msg .= err_msg($input_nis . ' ' . $input_nama . ' : Masukkan Waktu Masuk.');
				}
				else
				{
					$input_tanggal	.= ' ' . $input_waktu;

					$verifikasi_telat 	= $this->verifikasi_absensi_model->verifikasi_jam_masuk($input_tanggal, $input_sekolah);
					if(empty($verifikasi_telat['sesi_id']))
					{
						$msg .= err_msg($input_nis . ' ' . $input_nama . ' : Waktu Masuk tidak valid.');
						continue;
					}

					$sesi_id = $verifikasi_telat['sesi_id'];
					$jam_id  = $verifikasi_telat['jam_id'];
					$telat 	 = $verifikasi_telat['telat'];
				}
			}

			$param = array(
				'user_id'		=> $key,
				'kelas_id'		=> $input_kelas,
				'jam_id'		=> $jam_id,
				'sesi_id'		=> $sesi_id,
				'status'		=> $input_status,
				'waktu'			=> $input_tanggal,
				'telat'			=> $telat,
				'keterangan'	=> $input_keterangan				
			);

			$proses = $this->verifikasi_absensi_model->insert_absensi($param);
			if($proses)
			{
				$msg .= suc_msg($input_nis . ' ' . $input_nama . ' : Verifikasi berhasil.');
			}
			else
			{
				$msg .= err_msg($input_nis . ' ' . $input_nama . ' : Verifikasi gagal, silahkan ulangi lagi.');				
			}
		}

		if(!empty($msg))
		{
			$this->session->set_flashdata('msg', $msg);
		}
		redirect('verifikasi_absensi/index?jenis=belum_absen&?tanggal=' . $tanggal . '&kelas=' . $kelas . '&sekolah=' . $sekolah);
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
