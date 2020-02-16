<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manajemen_siswa extends CI_Controller
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

		$this->load->model('manajemen_siswa_model');
		$this->load->model('manajemen_user/manajemen_user_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->load->model('pengaturan_kelas/pengaturan_kelas_model');

		$this->page_active 		= 'manajemen_siswa';
		$this->sub_page_active 	= 'manajemen_siswa';
	}


	public function index()
	{
		$param['sekolah']	= $this->input->get('sekolah');
		$param['kelas']		= $this->input->get('kelas_id');
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

		$param['data']			= $this->manajemen_siswa_model->get_data($filter)->result();

		unset($filter['limit']);
		unset($filter['offset']);
		$total_rows 			= $this->manajemen_siswa_model->get_data($filter)->num_rows();
		$param['pagination']	= paging('manajemen_siswa/index', $total_rows, $limit, $uri_segment);

		$param['opt_sekolah'] = $this->profil_sekolah_model->get_opt('Semua Sekolah');

		$param['main_content']		= 'manajemen_siswa/table';
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
				$param['data'] = $this->manajemen_siswa_model->get_data_row($id);
			}
		}

		$param['opt_sekolah']	= $this->profil_sekolah_model->get_opt();

		$param['main_content']		= 'manajemen_siswa/form';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function import()
	{
		$param['msg']	= $this->session->flashdata('msg');
		$last_data 		= $this->session->flashdata('last_data');
		if(!empty($last_data))
		{
			$param['data'] = (object) $last_data;
		}
		$param['opt_sekolah']	= $this->profil_sekolah_model->get_opt();

		$param['main_content']		= 'manajemen_siswa/import';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);		
	}

	public function submit_import()
	{
		$data_post = $this->input->post();
		$this->form_validation->set_rules('kelas_id', 'Kelas', 'required');
		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('manajemen_siswa/import/');
		}
		else
		{
			if(!empty($_FILES['userfiles']['tmp_name']))
			{
				$config['upload_path']      = './uploads/temp_import_excel/';
	            $config['allowed_types']    = 'xls|xlsx';

	            if (!is_dir($config['upload_path']))
	            {
	                mkdir($config['upload_path']);
	            }

	            $this->load->library('upload', $config);
	            if (!$this->upload->do_upload('userfiles'))
	            {
					$this->session->set_flashdata('last_data', $data_post);
					$this->session->set_flashdata('msg', err_msg($this->upload->display_errors()));
					redirect('manajemen_siswa/import/');
	            }
	            else
	            {
	            	$data_upload = $this->upload->data();
	            	$file 		 = $config['upload_path'] . $data_upload['file_name'];

	            	require('application/libraries/SpreadsheetReader.php');
	            	$reader = new SpreadsheetReader($file);
					unlink($file);

	            	$i = 0;
					foreach($reader as $row)
					{
						$i++;
						if($i == 1)
						{
							continue;
						}

						if(empty($row[0]) || empty($row[4]) || empty($row[5]) || empty($row[10]))
						{
							continue;
						}

						$param_user = array(
							'email'		=> $row[4],
							'no_hp'		=> $row[3 ],
							'password'	=> md5($row[5]),
							'nama'		=> $row[1],
							'status'	=> 'aktif',
							'level'		=> 'siswa'
						);

						$param_siswa = array(
							'sekolah_id' 		=> $data_post['sekolah_id'],
							'kelas_id'	 		=> $data_post['kelas_id'],
							'nis'		 		=> $row[0],
							'alamat'	 		=> $row[2],
							'nama_ortu_bapak'	=> $row[7],
							'nama_ortu_ibu'		=> $row[6],
							'no_hp_ortu'	 	=> $row[9],
							'alamat_ortu'	 	=> $row[8],
							'password_ortu'	 	=> md5($row[10]),
						);

						$check_email = $this->manajemen_user_model->get_user_by_email($row[4])->row();
						if(!empty($check_email))
						{
							$this->manajemen_user_model->update($param_user, $check_email->user_id);
							$this->manajemen_siswa_model->update($param_siswa, $check_email->user_id);
						}
						else
						{
							$check_nis = $this->manajemen_siswa_model->cek_nis($row['0'], $data_post['sekolah_id']);
							if(!$check_nis)
							{
								continue;
							}

							if($this->manajemen_user_model->insert($param_user))
							{
								$param_siswa['user_id']	= $this->db->insert_id();
								$this->manajemen_siswa_model->insert($param_siswa);
							}						
						}
					}            	

					$this->session->set_flashdata('msg', suc_msg('Import Data berhasil.'));
					redirect('manajemen_siswa/index?sekolah=' . $data_post['sekolah_id'] . '&kelas=' . $data_post['kelas_id']);
	            }
			}
			else
			{
				$this->session->set_flashdata('last_data', $data_post);
				$this->session->set_flashdata('msg', err_msg('Masukkan File Excel.'));
				redirect('manajemen_siswa/import/');
			}
		}
	}

	public function submit($id = '')
	{
		$data_post = $this->input->post();
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		if(empty($id))
		{
			$this->form_validation->set_rules('nis', 'NIS', 'required');
			$this->form_validation->set_rules('email', 'Email', 'is_unique[user.email]|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('no_hp', 'No Handphone', 'required|is_unique[user.no_hp]');
			$this->form_validation->set_rules('password_ortu', 'Password Orang Tua', 'required');
		}

		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('manajemen_siswa/form/' . $id);
		}
		else
		{
			if(empty($id))
			{
				$validasi = $this->manajemen_siswa_model->cek_nis($data_post['nis'], $data_post['sekolah_id']);
				if(!$validasi)
				{
					$this->session->set_flashdata('msg', err_msg('NIS sudah terdaftar, gunakan nis lain.'));
					$this->session->set_flashdata('last_data', $data_post);
					redirect('manajemen_siswa/form/' . $id);
					exit;
				}
			}

			if(!empty($_FILES['userfiles']['tmp_name']))
			{
				$config['upload_path']      = './uploads/profil/';
	            $config['allowed_types']    = 'jpg|png|jpeg';
	 			$config['max_size'] 		= '2048';

	            if (!is_dir($config['upload_path']))
	            {
	                mkdir($config['upload_path']);
	            }

	            $this->load->library('upload', $config);
	            if (!$this->upload->do_upload('userfiles'))
	            {
					$this->session->set_flashdata('last_data', $data_post);
					$this->session->set_flashdata('msg', err_msg($this->upload->display_errors()));
					redirect('manajemen_siswa/form/' . $id);
	            }
	            else
	            {
	            	$data_upload 		= $this->upload->data();
	            	$data_post['foto']	= $data_upload['file_name'];
	            }
			}

			if(empty($data_post['password']))
			{
				unset($data_post['password']);
			}
			else
			{
				$data_post['password'] = md5($data_post['password']);
			}

			if(empty($data_post['password_ortu']))
			{
				unset($data_post['password_ortu']);
			}
			else
			{
				$data_post['password_ortu'] = md5($data_post['password_ortu']);
			}

			$param_user = $data_post;
			$param_user['level'] = 'siswa';
			unset($param_user['sekolah_id']);
			unset($param_user['kelas_id']);
			unset($param_user['nis']);
			unset($param_user['alamat']);
			unset($param_user['nama_ortu_bapak']);
			unset($param_user['nama_ortu_ibu']);
			unset($param_user['no_hp_ortu']);
			unset($param_user['alamat_ortu']);
			unset($param_user['password_ortu']);

			$param_siswa = $data_post;
			unset($param_siswa['no_hp']);
			unset($param_siswa['email']);
			unset($param_siswa['password']);
			unset($param_siswa['nama']);
			unset($param_siswa['foto']);

			if(empty($id))
			{
				$proses = $this->manajemen_user_model->insert($param_user);
				if($proses)
				{
					$param_siswa['user_id'] = $this->db->insert_id();
					$this->manajemen_siswa_model->insert($param_siswa);

					$this->session->set_flashdata('msg', suc_msg('Data berhasil disimpan.'));
				}
				else
				{
					$this->session->set_flashdata('msg', err_msg('Data gagal disimpan, silahkan ulangi lagi.'));
					redirect('manajemen_siswa/form/' . $id);
				}
			}
			else
			{
				$this->manajemen_user_model->update($param_user, $id);
				$this->manajemen_siswa_model->update($param_siswa, $id);
				$this->session->set_flashdata('msg', suc_msg('Data berhasil diperbaharui.'));
			}
		}
		redirect('manajemen_siswa');
	}

	public function hapus($id)
	{
		$this->manajemen_user_model->delete($id);
		$this->manajemen_siswa_model->delete($id);
		$this->session->set_flashdata('msg', suc_msg('Data berhasil dihapus.'));
		redirect('manajemen_siswa');
	}
	public	function remove(){
            foreach ($_POST['id'] as $id) {
                $this->manajemen_user_model->delete($id);
		$this->manajemen_siswa_model->delete($id);
            }
            return redirect('manajemen_siswa');
	}
	public function get_kelas()
	{
		$selected	= $this->input->get('selected');
		$sekolah_id = $this->input->get('sekolah_id');

		$result['']	= 'Semua Kelas';
		if(!empty($sekolah_id))
		{
			$result = $this->pengaturan_kelas_model->get_opt('Pilih Kelas', $sekolah_id);
		}
		echo form_dropdown('kelas_id', $result, $selected, 'class="form-control"');
	}	
}
