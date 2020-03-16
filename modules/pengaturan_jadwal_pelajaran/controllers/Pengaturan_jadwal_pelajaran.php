<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengaturan_jadwal_pelajaran extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->login_status 	= $this->session->userdata('login_status');
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

		$this->load->model('pengaturan_jadwal_pelajaran_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->load->model('pengaturan_kelas/pengaturan_kelas_model');
		$this->load->model('master_mata_pelajaran/master_mata_pelajaran_model');
		$this->load->model('manajemen_guru/manajemen_guru_model');

		$this->page_active 		= 'pengaturan';
		$this->sub_page_active 	= 'pengaturan_jadwal_pelajaran';
	}

	public function index()
	{
		$param['sekolah']	= $this->input->get('sekolah');
		$param['kelas']		= $this->input->get('kelas');
		$param['hari']		= $this->input->get('hari');

		if(!isset($param['hari']))
		{
			$param['hari'] 	= date('w');
		}

		$param['opt_sekolah']	= $this->profil_sekolah_model->get_opt('Pilih Sekolah');
		$param['opt_hari']		= hari_indonesia();

		$param['url_param']	= http_build_query(array(
			'sekolah'	=> $param['sekolah'],
			'kelas'		=> $param['kelas'],
			'hari'		=> $param['hari'],
		));

		if(!empty($param['sekolah']) && !empty($param['kelas']))
		{
			$filter = array(
				'sekolah'	=> $param['sekolah'],
				'kelas'		=> $param['kelas'],
				'hari'		=> $param['hari']
			);
			$param['data']	= $this->pengaturan_jadwal_pelajaran_model->get_data($filter)->result();
		}

		$param['main_content']	= 'pengaturan_jadwal_pelajaran/table';
		$param['page_active'] 	= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	} 

	public function form($id = '')
	{
		$param = $this->input->get();
		$param['url_param']	= http_build_query($param);
		$param['msg']		= $this->session->flashdata('msg');
		$param['id']		= $id;
//                print_r($this->login_uid);
//                die;
		$last_data 	= $this->session->flashdata('last_data');
		if(!empty($last_data))
		{
			$param['data'] = (object) $last_data;
		}
		else
		{
			if(!empty($id))
			{
				$param['data'] = $this->pengaturan_jadwal_pelajaran_model->get_data_row($id);
			}
		}

		$param['opt_mata_pelajaran']	= $this->master_mata_pelajaran_model->get_opt();
		$param['data_sekolah']			= $this->profil_sekolah_model->get_data_row($param['sekolah']);
		$param['data_kelas']			= $this->pengaturan_kelas_model->get_data(array('kelas_id' => $param['kelas']))->row();

		$get_guru = $this->manajemen_guru_model->get_data_guru(array('sekolah' => $param['sekolah']))->result();
		$param['opt_guru']	= array();
		foreach($get_guru as $key => $c)
		{
			$param['opt_guru'][$c->user_id] = $c->nip . ' - ' . $c->nama;
		}

		$param['main_content']		= 'pengaturan_jadwal_pelajaran/form';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function submit($id = '')
	{
		$data_get  = $this->input->get();
		$url_param = http_build_query($data_get);

		$data_post = $this->input->post();
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
			$data_post['sekolah_id'] = $data_get['sekolah'];
			$data_post['kelas_id']	 = $data_get['kelas'];
			$data_post['hari']		 = $data_get['hari'];
			if(empty($id))
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
			else
			{
				$proses = $this->pengaturan_jadwal_pelajaran_model->update($data_post, $id);
				if($proses)
				{
					$this->session->set_flashdata('msg', suc_msg('Data berhasil diperbaharui.'));
				}
				else
				{
					$this->session->set_flashdata('msg', err_msg('Data gagal diperbaharui, tidak ada yang berubah.'));
				}
			}
		}
		redirect('pengaturan_jadwal_pelajaran/index?' . $url_param);
	}

	public function hapus($id)
	{
		$url_param = http_build_query($this->input->get());
		$proses = $this->pengaturan_jadwal_pelajaran_model->delete($id);
		$this->session->set_flashdata('msg', suc_msg('Data berhasil dihapus.'));
		redirect('pengaturan_jadwal_pelajaran/index?' . $url_param);
	}

	public function import()
	{
		$param = $this->input->get();
		if(empty($param['sekolah']))
		{
			show_404();
		}

		$param['url_param']	= http_build_query($param);
		$param['msg']		= $this->session->flashdata('msg');

		$last_data 	= $this->session->flashdata('last_data');
		if(!empty($last_data))
		{
			$param['data'] = (object) $last_data;
		}

		$param['opt_mata_pelajaran']	= $this->master_mata_pelajaran_model->get_opt();
		$param['data_sekolah']			= $this->profil_sekolah_model->get_data_row($param['sekolah']);
		$param['data_kelas']			= $this->pengaturan_kelas_model->get_data(array('kelas_id' => $param['kelas']))->row();

		$get_guru = $this->manajemen_guru_model->get_data(array('sekolah' => $param['sekolah']))->result();
		$param['opt_guru']	= array();
		foreach($get_guru as $key => $c)
		{ 
			$param['opt_guru'][$c->user_id] = $c->nip . ' - ' . $c->nama;
		}

		$param['main_content']		= 'pengaturan_jadwal_pelajaran/import';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function import_submit()
	{
		$param 		= $this->input->get();
		$data_post	= $this->input->post();
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
				redirect('pengaturan_jadwal_pelajaran/import?' . http_build_query($param));
            }
            else
            {
            	$data_upload = $this->upload->data();
            	$file 		 = $config['upload_path'] . $data_upload['file_name'];

            	require('application/libraries/SpreadsheetReader.php');
            	$reader = new SpreadsheetReader($file);
				unlink($file);

				$sheets = $reader->Sheets();
				foreach ($sheets as $index => $name)
				{
					$reader->ChangeSheet($index);
					$i = 0;
					foreach($reader as $row => $c)
					{
						$i++;
						if($i == 1)
						{
							continue;
						}

						if(empty($c[0]))
						{
							continue;
						}

						$param_db = array(
							'sekolah_id'			=> $param['sekolah'],
							'kelas_id'				=> $param['kelas'],
							'jam_mulai'				=> $c[0],
							'jam_akhir'				=> $c[1],
							'user_id'				=> $c[2],
							'mata_pelajaran_id'		=> $c[3],
							'hari'					=> $index
						);
						$this->pengaturan_jadwal_pelajaran_model->insert($param_db);
					}
				}

				$this->session->set_flashdata('msg', suc_msg('Import Data berhasil.'));
				redirect('pengaturan_jadwal_pelajaran/index?' . http_build_query($param));
            }
		}
		else
		{
			$this->session->set_flashdata('last_data', $data_post);
			$this->session->set_flashdata('msg', err_msg('Masukkan File Excel.'));
			redirect('pengaturan_jadwal_pelajaran/import?' . http_build_query($param));
		}
	}

	public function download_kode($id = '')
	{
		if(empty($id))
		{
			show_404();
		}

		$param['data_sekolah']			= $this->profil_sekolah_model->get_data_row($id);
		$param['data_guru'] 			= $this->manajemen_guru_model->get_data(array('sekolah' => $id))->result();
		$param['data_mata_pelajaran']	= $this->master_mata_pelajaran_model->get_data()->result();

		header("Content-type: application/vnd-ms-excel");
		header("Content-Disposition: attachment; filename=Kode Guru dan Kelas " . $param['data_sekolah']->nama . ".xls");
		$this->load->view('excel_kode', $param);
	}

	/* Ajax */
	function ajax_kelas()
	{
		$selected	= $this->input->get('selected');
		$sekolah 	= $this->input->get('sekolah');


		$result[''] = 'Semua Kelas';
		if(!empty($sekolah))
		{
			$result 	= $this->pengaturan_kelas_model->get_opt('Semua Kelas', $sekolah);
		}
//                print_r($result);
//                die;
		echo form_dropdown('kelas', $result, $selected, 'class="form-control"');		
	}
	/* End Of Ajax */

}
