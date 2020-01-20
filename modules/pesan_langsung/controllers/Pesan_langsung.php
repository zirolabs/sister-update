<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pesan_langsung extends CI_Controller
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

		$this->load->model('pesan_langsung_model');
		$this->load->model('pesan_kotak/pesan_kotak_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->load->model('manajemen_siswa/manajemen_siswa_model');
		$this->load->model('manajemen_guru/manajemen_guru_model');
		$this->load->model('manajemen_operator/manajemen_operator_model');
		$this->load->model('pengaturan_kelas/pengaturan_kelas_model');
		$this->page_active 		= 'pesan';
		$this->sub_page_active 	= 'pesan_langsung';
	}

	public function index($id = '')
	{
		$param['msg']			= $this->session->flashdata('msg');
		$param['id']			= $id;

		$last_data 	= $this->session->flashdata('last_data');
		if(!empty($last_data))
		{
			$param['data'] = (object) $last_data;
		}

		$param['opt_jenis_user']	= array(
			'siswa'				=> 'Siswa',
			'wali siswa'		=> 'Wali Siswa',
			'wali kelas'		=> 'Wali Kelas',
			'operator sekolah'	=> 'Operator Sekolah',
			'kepala sekolah'	=> 'Kepala Sekolah',
			'guru'				=> 'Guru'
		);

		if($this->login_level == 'guru')
		{
			unset($param['opt_jenis_user']['wali kelas']);
			unset($param['opt_jenis_user']['operator sekolah']);
			unset($param['opt_jenis_user']['kepala sekolah']);
			unset($param['opt_jenis_user']['guru']);
		}

		$param['opt_sekolah']		= $this->profil_sekolah_model->get_opt();
		$param['main_content']		= 'form';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function submit($id = '')
	{
		$data_post = $this->input->post();
		$this->form_validation->set_rules('isi', 'Isi', 'required');

		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('pesan_langsung/');
		}
		else
		{
			if(empty($data_post['penerima']))
			{
				$this->session->set_flashdata('msg', err_msg('Masukkan setidaknya 1 penerima pesan.'));
				$this->session->set_flashdata('last_data', $data_post);
				redirect('pesan_langsung/');				
			}

			$gambar = '';
			if(!empty($_FILES['userfiles']['tmp_name']))
			{
				$config['upload_path']      = './uploads/pesan/';
	            $config['allowed_types']    = 'jpg|png';
	 			$config['max_size'] 		= '2048';

	            if (!is_dir($config['upload_path']))
	            {
	                mkdir($config['upload_path']);
	            }

	            $this->load->library('upload', $config);
	            if (!$this->upload->do_upload('userfiles'))
	            {
					$this->session->set_flashdata('msg', err_msg($this->upload->display_errors()));
					$this->session->set_flashdata('last_data', $data_post);
					redirect('pesan_langsung/');
	            }
	            else
	            {
	            	$data_upload 	= $this->upload->data();
	            	$gambar			= $config['upload_path'] . $data_upload['file_name'];
	            }
			}			

			$param_pesan = array(
				'user_id'		=> $this->login_uid,
				'isi'			=> $data_post['isi'],
				'gambar'		=> $gambar,
				'waktu_kirim'	=> date('Y-m-d H:i:s')
			);

			$isi_notifikasi 	= substr($param_pesan['isi'], 0, 150);
			foreach($data_post['penerima'] as $key => $c)
			{
				$target = str_replace('-', ' ', @$data_post['jenis'][$key]);
				if($this->pesan_kotak_model->insert($param_pesan, $this->login_uid, $c, $target))
				{
					$fcm = @$data_post['fcm'][$key];
					if(!empty($fcm))
					{
						if($target == 'wali siswa')
						{
							$this->fcm->insertNotifikasiWali($c, 'Pesan Baru', $isi_notifikasi, $fcm, 'pesan');						
							$this->sms->insertNotifikasiWali($c, $isi_notifikasi);
						}
						else
						{
							$this->fcm->insertNotifikasiUser($c, 'Pesan Baru', $isi_notifikasi, $fcm, 'pesan');
							$this->sms->insertNotifikasiUser($c, $isi_notifikasi);
						}						
					}
				}
			}
			$this->session->set_flashdata('msg', suc_msg('Pesan berhasil dikirim.'));
		}
		redirect('pesan_langsung');
	}

	public function ajax_pencarian_user()
	{
		$data_post = $this->input->post();

		$data['jenis_penerima'] = $data_post['jenis_penerima'];
		if($data_post['jenis_penerima'] == 'siswa' || $data_post['jenis_penerima'] == 'wali siswa')
		{
			$filter = array(
				'keyword'	=> $data_post['keyword'],
				'sekolah'	=> $data_post['sekolah_id']
			);
			if($this->login_level == 'guru')
			{
				$filter['guru'] = $this->login_uid;
			}
			$data['user'] = $this->manajemen_siswa_model->get_data($filter)->result();
		}
		elseif($data_post['jenis_penerima'] == 'wali kelas')
		{
			$filter = array(
				'keyword_wali_kelas'	=> $data_post['keyword'],
				'sekolah_id'			=> $data_post['sekolah_id']
			);
			$get_data_user = $this->pengaturan_kelas_model->get_data($filter)->result();
			$data['user']  = array();
			foreach($get_data_user as $key => $c)
			{
				$data['user'][] = (object) array(
					'user_id'	=> $c->user_id,
					'nama'		=> $c->nama_wali_kelas,
					'foto'		=> $c->foto_wali_kelas,
					'email'		=> $c->email_wali_kelas,
					'no_hp'		=> $c->no_hp_wali_kelas,
					'kelas'		=> $c->jenjang . ' ' . $c->nama_jurusan . ' ' . $c->nama,
					'fcm'		=> $c->fcm
				);				
			}
		}
		elseif($data_post['jenis_penerima'] == 'operator sekolah')
		{
			$filter = array(
				'sekolah'	=> $data_post['sekolah_id'], 
				'keyword' => $data_post['keyword']
			);
			$data['user'] = $this->manajemen_operator_model->get_data($filter)->result();
		}
		elseif($data_post['jenis_penerima'] == 'kepala sekolah')
		{
			$filter = array(
				'sekolah_id'				=> $data_post['sekolah_id'], 
				'keyword_kepala_sekolah' 	=> $data_post['keyword']
			);
			$get_data_user = $this->profil_sekolah_model->get_data($filter)->result();
			$data['user']  = array();
			foreach($get_data_user as $key => $c)
			{
				$data['user'][] = (object) array(
					'user_id'	=> $c->user_id,
					'nama'		=> $c->kepsek_nama,
					'foto'		=> $c->kepsek_foto,
					'email'		=> $c->kepsek_email,
					'no_hp'		=> $c->kepsek_no_hp,
					'sekolah'	=> $c->nama,
					'fcm'		=> $c->kepsek_fcm
				);				
			}
		}
		elseif($data_post['jenis_penerima'] == 'guru')
		{
			$filter = array(
				'sekolah'	=> $data_post['sekolah_id'], 
				'keyword' => $data_post['keyword']
			);
			$data['user'] = $this->manajemen_guru_model->get_data($filter)->result();			
		}

		$this->load->view('form_user', $data);
	}
}
