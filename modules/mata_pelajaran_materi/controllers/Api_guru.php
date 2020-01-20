<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_guru extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->device_id 		= $this->input->post('device_id');
		if(empty($this->device_id))
		{
			$this->device_id = $this->input->get('device_id');
		}

		$this->load->model('login/login_model');
		$this->cek_device = $this->login_model->get_data_guru_device($this->device_id);
		if(empty($this->cek_device))
		{
			$respon = array(
				'status'	=> '201',
				'msg'		=> 'Gagal',
				'data'		=> 'Autentikasi Gagal.'
			);	
			echo json_encode($respon);
			exit;
		}

		$this->login_uid	= $this->cek_device->user_id;		
		$this->load->model('mata_pelajaran_materi_model');
	}

	public function index()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Data tidak ditemukan.',
			'data'		=> array()
		);	

		$data_post = $this->input->post();
		$data_post['guru_id'] = $this->login_uid;
		if(!empty($data_post['kelas']) || !empty($data_post['mata_pelajaran']))
		{
			$get_data = $this->mata_pelajaran_materi_model->get_data($data_post)->result();
			if(!empty($get_data))
			{
				$result = array();
				foreach($get_data as $key => $c)
				{
					unset($c->mata_pelajaran_id);
					unset($c->sekolah_id);
					unset($c->user_id);
					if(!empty($c->lokasi_file))
					{
						$c->lokasi_file = base_url($c->lokasi_file);
					}
					$c->waktu_upload = format_tanggal_indonesia($c->waktu_upload, true);
					$result[] = $c;
				}
				$respon = array(
					'status'	=> '200',
					'msg'		=> 'Data ditemukan.',
					'data'		=> $result
				);					
			}
		}
		else
		{
			$respon = array(
				'status'	=> '201',
				'msg'		=> 'Parameter tidak valid.',
				'data'		=> array()
			);				
		}

		echo json_encode($respon);
	}

	public function kelas()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Data tidak ditemukan.',
			'data'		=> array()
		);				

		$data_post = $this->input->post();
		if(!empty($data_post['materi_id']))
		{
			$get_data = $this->mata_pelajaran_materi_model->get_kelas($data_post['materi_id']);
			if(!empty($get_data))
			{
				$respon = array(
					'status'	=> '200',
					'msg'		=> 'Data ditemukan.',
					'data'		=> $get_data
				);								
			}
		}

		echo json_encode($respon);
	}

	public function submit()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Data gagal disimpan, silahkan ulangi lagi.',
			'data'		=> array()
		);	

		$data_post = $this->input->post();
		$this->form_validation->set_rules('judul', 'Judul', 'required');
		$this->form_validation->set_rules('mata_pelajaran_id', 'Mata Pelajaran', 'required');
		$this->form_validation->set_rules('kelas', 'Kelas', 'required');
		if($this->form_validation->run() == false)
		{
			$respon = array(
				'status'	=> '201',
				'msg'		=> validation_errors(),
				'data'		=> array()
			);	
		}
		else
		{
			if(!empty($_FILES['file']))
			{
				$file = $_FILES['file'];

				$extention = get_extention($file['type']);
				$config['file_name']   	 = $this->login_uid . date('YmdHis') . '.' . $extention;
				$config['upload_path'] 	 = './uploads/materi_mata_pelajaran/';
				$config['allowed_types'] = '*';
	            if (!is_dir($config['upload_path']))
	            {
	                mkdir($config['upload_path']);
	            }

	            $this->load->library('upload', $config);
	            if (!$this->upload->do_upload('file'))
	            {
					$respon = array(
						'status'	=> '201',
						'msg'		=> $this->upload->display_errors(),
						'data'		=> array()
					);	
					echo json_encode($respon);
					exit;
	            }
	            else
	            {
	            	$data_upload 		 	  = $this->upload->data();
	            	$data_post['lokasi_file'] = $config['upload_path'] . $data_upload['file_name'];
	            }
			}

			$id 		= $data_post['id'];
			$list_kelas = json_decode($data_post['kelas']);		
			unset($data_post['kelas']);	
			unset($data_post['id']);	
			unset($data_post['device_id']);	
			unset($data_post['file']);	
					
			$data_post['user_id']	   = $this->login_uid;
			$data_post['waktu_upload'] = date('Y-m-d H:i:s');
			if(empty($id))
			{
				$proses = $this->mata_pelajaran_materi_model->insert($data_post);
				if($proses)
				{
					$id = $this->db->insert_id();
					$respon = array(
						'status'	=> '200',
						'msg'		=> 'Data berhasil disimpan.',
						'data'		=> array()
					);	
				}
			}
			else
			{
				$proses = $this->mata_pelajaran_materi_model->update($data_post, $id);
				$respon = array(
					'status'	=> '200',
					'msg'		=> 'Data berhasil disimpan.',
					'data'		=> array()
				);	
			}

			if(!empty($id))
			{
				$this->mata_pelajaran_materi_model->delete_kelas($id);
				foreach($list_kelas as $key => $c)
				{
					$param_kelas[]	= array(
						'materi_id'	=> $id,
						'kelas_id'	=> $c->id
					);
				}

				if(!empty($param_kelas))
				{
					$this->mata_pelajaran_materi_model->insert_kelas($param_kelas);
				}
			}
		}

		echo json_encode($respon);
	}

	public function hapus()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Data gagal dihapus.',
			'data'		=> array()
		);							

		$data_post = $this->input->post();
		if(!empty($data_post['materi_id']))
		{
			$id = $data_post['materi_id'];
			$data_sebelumnya = $this->mata_pelajaran_materi_model->get_data_row($id);
			if(!empty($data_sebelumnya))
			{
				if(!empty($data_sebelumnya->lokasi_file))
				{
					unlink($data_sebelumnya->lokasi_file);					
				}

				$this->mata_pelajaran_materi_model->delete_kelas($id);
				$this->mata_pelajaran_materi_model->delete($id);

				$respon = array(
					'status'	=> '200',
					'msg'		=> 'Data berhasil dihapus.',
					'data'		=> array()
				);							
			}
		}

		echo json_encode($respon);		
	}
}
