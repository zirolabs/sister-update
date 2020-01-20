<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pesan_broadcast extends CI_Controller
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

		$this->load->model('pesan_broadcast_model');
		$this->load->model('pesan_kotak/pesan_kotak_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->load->model('manajemen_siswa/manajemen_siswa_model');
		$this->load->model('manajemen_operator/manajemen_operator_model');
		$this->load->model('manajemen_guru/manajemen_guru_model');
		$this->load->model('pengaturan_kelas/pengaturan_kelas_model');
		$this->page_active 		= 'pesan';
		$this->sub_page_active 	= 'pesan_broadcast';
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
		$this->form_validation->set_rules('sekolah_id', 'Sekolah', 'required');
		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('pesan_broadcast/index/' . $id);
		}
		else
		{
			if(empty($data_post['kelas']))
			{
				$this->session->set_flashdata('msg', err_msg('Pilih Kelas'));
				$this->session->set_flashdata('last_data', $data_post);
				redirect('pesan_broadcast/index/' . $id);				
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
					redirect('pesan_broadcast/index/' . $id);
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
			foreach($data_post['kelas'] as $key => $c)
			{
				if(@$data_post['target_siswa'] == 'Y' || @$data_post['target_wali'] == 'Y')
				{
					$filter = array('kelas' => $c); 
					$get_data_siswa = $this->manajemen_siswa_model->get_data($filter)->result();
					foreach($get_data_siswa as $kex => $x)
					{
						if(@$data_post['target_siswa'] == 'Y')
						{
							if($this->pesan_kotak_model->insert($param_pesan, $this->login_uid, $x->user_id, 'siswa'))
							{
								if(!empty($x->fcm))
								{
									$this->fcm->insertNotifikasiUser($x->user_id, 'Pesan Broadcast untuk Siswa', $isi_notifikasi, $x->fcm, 'pesan');
								}
								else
								{
									$this->sms->insertNotifikasiUser($x->user_id, $isi_notifikasi);
								}
							}
						}

						if(@$data_post['target_wali'] == 'Y')
						{
							if($this->pesan_kotak_model->insert($param_pesan, $this->login_uid, $x->user_id, 'wali siswa'))
							{
								if(!empty($x->fcm_ortu))
								{
									$this->fcm->insertNotifikasiWali($x->user_id, 'Pesan Broadcast untuk Wali Murid', $isi_notifikasi, $x->fcm_ortu, 'pesan');
								}								
								else
								{
									$this->sms->insertNotifikasiWali($x->user_id, $isi_notifikasi);									
								}
							}
						}
					}				
				}

				if(@$data_post['target_wali_kelas'] == 'Y')
				{
					$filter = array('kelas_id' => $c);
					$get_data_wali_kelas = $this->pengaturan_kelas_model->get_data($filter)->row();
					if(!empty($get_data_wali_kelas->user_id))
					{
						if($this->pesan_kotak_model->insert($param_pesan, $this->login_uid, $get_data_wali_kelas->user_id, 'wali kelas'))
						{
							if(!empty($get_data_wali_kelas->fcm))
							{
								$this->fcm->insertNotifikasiUser($get_data_wali_kelas->user_id, 'Pesan Broadcast untuk Wali Kelas', $isi_notifikasi, $get_data_wali_kelas->fcm, 'pesan');
							}							
							else
							{
								$this->sms->insertNotifikasiUser($get_data_wali_kelas->user_id, $isi_notifikasi);								
							}
						}
					}
				}

				if(@$data_post['target_operator_sekolah'] == 'Y')
				{
					$filter = array('sekolah_id' => $data_post['sekolah_id']);
					$get_data_operator = $this->manajemen_operator_model->get_data($filter)->result();
					if(!empty($get_data_operator))
					{
						foreach($get_data_operator as $kex => $x)
						{
							if($this->pesan_kotak_model->insert($param_pesan, $this->login_uid, $x->user_id, 'operator sekolah'))
							{
								if(!empty($x->fcm))
								{
									$this->fcm->insertNotifikasiUser($x->user_id, 'Pesan Broadcast untuk Operator Sekolah', $isi_notifikasi, $x->fcm, 'pesan');
								}			
								else
								{
									$this->sms->insertNotifikasiUser($x->user_id, $isi_notifikasi);									
								}				
							}							
						}
					}
				}

				if(@$data_post['target_kepala_sekolah'] == 'Y')
				{
					$get_data_kepala_sekolah = $this->profil_sekolah_model->get_data_row($data_post['sekolah_id']);
					if(!empty($get_data_kepala_sekolah))
					{
						if($this->pesan_kotak_model->insert($param_pesan, $this->login_uid, $get_data_kepala_sekolah->user_id, 'kepala sekolah'))
						{
							if(!empty($get_data_kepala_sekolah->user_fcm))
							{
								$this->fcm->insertNotifikasiUser($get_data_kepala_sekolah->user_id, 'Pesan Broadcast untuk Kepala Sekolah', $isi_notifikasi, $get_data_kepala_sekolah->user_fcm, 'pesan');
							}							
							else
							{
								$this->sms->insertNotifikasiUser($get_data_kepala_sekolah->user_id, $isi_notifikasi);								
							}
						}							
					}
				}

				if(@$data_post['target_guru'] == 'Y')
				{
					$filter = array('sekolah_id' => $data_post['sekolah_id']);
					$get_data = $this->manajemen_guru_model->get_data($filter)->result();
					if(!empty($get_data))
					{
						foreach($get_data as $kex => $x)
						{
							if($this->pesan_kotak_model->insert($param_pesan, $this->login_uid, $x->user_id,'Guru & Staff'))
							{
								if(!empty($x->fcm))
								{
									$this->fcm->insertNotifikasiUser($x->user_id, 'Pesan Broadcast untuk Seluruh Guru & Staff', $isi_notifikasi, $x->fcm, 'pesan');
								}			
								else
								{
									$this->sms->insertNotifikasiUser($x->user_id, $isi_notifikasi);									
								}				
							}							
						}
					}
				}

				// Menambahkan Controller untuk mengirim BC ke guru, staff, operator, kepala sekolah
				if(@$data_post['target_guru_staff'] == 'Y')
				{
					// untuk semua user level guru
					$filter = array('sekolah_id' => $data_post['sekolah_id']);
					$get_data_all = $this->manajemen_guru_model->get_data_all($filter)->result();
					if(!empty($get_data_all))
					{
						foreach($get_data_all as $kex => $x)
						{
							if($this->pesan_kotak_model->insert($param_pesan, $this->login_uid, $x->user_id,'Guru & Staff'))
							{
								if(!empty($x->fcm))
								{
									$this->fcm->insertNotifikasiUser($x->user_id, 'Pesan Broadcast untuk Seluruh Guru & Staff', $isi_notifikasi, $x->fcm, 'pesan');
								}			
								else
								{
									$this->sms->insertNotifikasiUser($x->user_id, $isi_notifikasi);									
								}				
							}							
						}
					}

					// user level kepala sekolah
					$get_data_kepala_sekolah = $this->profil_sekolah_model->get_data_row($data_post['sekolah_id']);
					if(!empty($get_data_kepala_sekolah))
					{
						if($this->pesan_kotak_model->insert($param_pesan, $this->login_uid, $get_data_kepala_sekolah->user_id, 'kepala sekolah'))
						{
							if(!empty($get_data_kepala_sekolah->user_fcm))
							{
								$this->fcm->insertNotifikasiUser($get_data_kepala_sekolah->user_id, 'Pesan Broadcast untuk Kepala Sekolah', $isi_notifikasi, $get_data_kepala_sekolah->user_fcm, 'pesan');
							}							
							else
							{
								$this->sms->insertNotifikasiUser($get_data_kepala_sekolah->user_id, $isi_notifikasi);								
							}
						}							
					}

					// operator sekolah
					$filter = array('sekolah_id' => $data_post['sekolah_id']);
					$get_data_operator = $this->manajemen_operator_model->get_data($filter)->result();
					if(!empty($get_data_operator))
					{
						foreach($get_data_operator as $kex => $x)
						{
							if($this->pesan_kotak_model->insert($param_pesan, $this->login_uid, $x->user_id, 'operator sekolah'))
							{
								if(!empty($x->fcm))
								{
									$this->fcm->insertNotifikasiUser($x->user_id, 'Pesan Broadcast untuk Operator Sekolah', $isi_notifikasi, $x->fcm, 'pesan');
								}			
								else
								{
									$this->sms->insertNotifikasiUser($x->user_id, $isi_notifikasi);									
								}				
							}							
						}
					}
				}
			}
			$this->session->set_flashdata('msg', suc_msg('Pesan berhasil dikirim.'));
		}
		redirect('pesan_broadcast');
	}

	public function ajax_form_kelas()
	{
		$data_post = $this->input->post();
		$data['kelas'] = $this->pengaturan_kelas_model->get_opt('', $data_post['sekolah']);
		$this->load->view('form_kelas', $data);
	}
}
