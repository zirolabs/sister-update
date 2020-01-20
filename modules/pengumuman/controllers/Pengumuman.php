<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengumuman extends CI_Controller
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

		$this->load->model('pengumuman_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->load->model('manajemen_siswa/manajemen_siswa_model');
		$this->load->model('pengaturan_kelas/pengaturan_kelas_model');
		$this->page_active 		= 'pengumuman';
		$this->sub_page_active 	= 'pengumuman';
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

		$param['data']			= $this->pengumuman_model->get_data($filter)->result();
		foreach($param['data'] as $key => $c)
		{
			$param['data'][$key]->kelas = $this->pengumuman_model->get_kelas($c->pengumuman_id);
		}

		unset($filter['limit']);
		unset($filter['offset']);
		$total_rows 			= $this->pengumuman_model->get_data($filter)->num_rows();
		$param['pagination']	= paging('pengumuman/index', $total_rows, $limit, $uri_segment);

		$param['opt_sekolah']	= $this->profil_sekolah_model->get_opt('Semua Sekolah');
		$param['main_content']	= 'pengumuman/table';
		$param['page_active'] 	= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function saya()
	{
		$param['keyword']	= $this->input->get('q');
		$limit 				= 10;
		$uri_segment		= 3;
		$filter = array(
			'limit'		=> $limit,
			'offset'	=> $this->uri->segment($uri_segment),
			'keyword'	=> $param['keyword'],
			'saya'		=> $this->login_uid
		);

		$param['data']			= $this->pengumuman_model->get_data($filter)->result();

		if(count($param['data']) == 10 && !empty($filter['offset']))
		{
			unset($filter['limit']);
			unset($filter['offset']);
			$total_rows 			= $this->pengumuman_model->get_data($filter)->num_rows();
			$param['pagination']	= paging('pengumuman/saya', $total_rows, $limit, $uri_segment);			
		}

		$param['main_content']		= 'pengumuman/saya';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= 'pengumuman_saya';
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
				$param['data'] 		= $this->pengumuman_model->get_data_row($id);
				$param['opt_kelas']	= $this->pengumuman_model->get_kelas($id);
			}
		}

		$param['opt_sekolah']		= $this->profil_sekolah_model->get_opt();
		$param['main_content']		= 'pengumuman/form';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function submit($id = '')
	{
		$data_post = $this->input->post();
		$this->form_validation->set_rules('judul', 'Judul', 'required');
		$this->form_validation->set_rules('sekolah_id', 'Sekolah', 'required');
		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('pengumuman/form/' . $id);
		}
		else
		{
			if(empty($data_post['kelas']))
			{
				$this->session->set_flashdata('msg', err_msg('Pilih Kelas'));
				$this->session->set_flashdata('last_data', $data_post);
				redirect('pengumuman/form/' . $id);				
			}

			if(!empty($_FILES['userfiles']['tmp_name']))
			{
				$config['upload_path']      = './uploads/pengumuman/';
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
					redirect('pengumuman/form/' . $id);
	            }
	            else
	            {
	            	$data_upload 			= $this->upload->data();
	            	$data_post['gambar']	= $config['upload_path'] . $data_upload['file_name'];
	            }
			}			
			
			$data_post['user_id']	= $this->login_uid;
			$data_post['waktu']		= date('Y-m-d H:i:s');
			$list_kelas 			= $data_post['kelas'];
			unset($data_post['kelas']);

			if(empty($data_post['target_siswa'])) $data_post['target_siswa'] = 'N';
			if(empty($data_post['target_wali'])) $data_post['target_wali'] = 'N';
			if(empty($data_post['target_wali_kelas'])) $data_post['target_wali_kelas'] = 'N';
			if(empty($data_post['target_guru'])) $data_post['target_guru'] = 'N';

			if(empty($id))
			{
				$proses = $this->pengumuman_model->insert($data_post);
				if($proses)
				{
					$this->session->set_flashdata('msg', suc_msg('Data berhasil disimpan.'));
					$id = $this->db->insert_id();
				}
				else
				{
					$this->session->set_flashdata('msg', err_msg('Data gagal disimpan, silahkan ulangi lagi.'));
					redirect('pengumuman/form/' . $id);
				}
			}
			else
			{
				$proses = $this->pengumuman_model->update($data_post, $id);
				if($proses)
				{
					$this->session->set_flashdata('msg', suc_msg('Data berhasil diperbaharui.'));
				}
				else
				{
					$this->session->set_flashdata('msg', err_msg('Data gagal diperbaharui, tidak ada yang berubah.'));
				}
			}

			if(!empty($id))
			{				
				$param_kelas 	= array();
				$param_user 	= array();
				$isi_notifikasi	= substr($data_post['judul'], 0, 250);

				foreach($list_kelas as $key => $c)
				{
					$param_kelas[]	= array(
						'pengumuman_id'	=> $id,
						'kelas_id'		=> $c
					);

					if($data_post['target_siswa'] == 'Y' || $data_post['target_wali'] == 'Y')
					{
						$filter = array('kelas' => $c); 
						$get_data_siswa = $this->manajemen_siswa_model->get_data($filter)->result();
						foreach($get_data_siswa as $kex => $x)
						{
							$param_user[] = array(
								'pengumuman_id'	=> $id,
								'user_id'		=> $x->user_id
							);

							if($data_post['target_siswa'] == 'Y')
							{
								if(!empty($x->fcm))
								{
									$this->fcm->insertNotifikasiUser($x->user_id, 'Pengumuman untuk Siswa', $isi_notifikasi, $x->fcm);
								}
								else
								{
									$this->sms->insertNotifikasiUser($x->user_id, $isi_notifikasi);
								}
							}

							if($data_post['target_wali'] == 'Y')
							{
								if(!empty($x->fcm_ortu))
								{
									$this->fcm->insertNotifikasiWali($x->user_id, 'Pengumuman untuk Wali Murid', $isi_notifikasi, $x->fcm_ortu);
								}
								else
								{
									$this->sms->insertNotifikasiWali($x->user_id, $isi_notifikasi);
								}
							}
						}				
					}

					if($data_post['target_wali_kelas'] == 'Y')
					{
						$filter = array('kelas_id' => $c);
						$get_data_wali_kelas = $this->pengaturan_kelas_model->get_data($filter)->row();
						if(!empty($get_data_wali_kelas->user_id))
						{
							$param_user[] = array(
								'pengumuman_id'	=> $id,
								'user_id'		=> $get_data_wali_kelas->user_id
							);

							if(!empty($get_data_wali_kelas->fcm))
							{
								$this->fcm->insertNotifikasiUser($get_data_wali_kelas->user_id, 'Pengumuman untuk Wali Kelas', $isi_notifikasi, $get_data_wali_kelas->fcm);
							}
							else
							{
								$this->sms->insertNotifikasiUser($get_data_wali_kelas->user_id, $isi_notifikasi);
							}
						}
					}

					if($data_post['target_guru'] == 'Y')
					{

					}
				}

				if(!empty($param_kelas))
				{
					$this->pengumuman_model->insert_kelas($param_kelas);
				}

				if(!empty($param_user))
				{
					$this->pengumuman_model->insert_user($param_user);
				}
			}
		}
		redirect('pengumuman');
	}

	public function hapus($id)
	{
		$this->pengumuman_model->delete($id);
		$this->pengumuman_model->delete_kelas($id);
		$this->pengumuman_model->delete_user($id);
		$this->session->set_flashdata('msg', suc_msg('Data berhasil dihapus.'));
		redirect('pengumuman');
	}

	public function ajax_form_kelas()
	{
		$data_post = $this->input->post();

		$data['kelas_selected'] = array();
		if(!empty($data_post['kelas']))
		{
			$data['kelas_selected'] = json_decode($data_post['kelas']);
		}

		$data['kelas'] = $this->pengaturan_kelas_model->get_opt('', $data_post['sekolah']);
		$this->load->view('form_kelas', $data);
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
