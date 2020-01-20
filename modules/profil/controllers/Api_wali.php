<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_wali extends CI_Controller 
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
		$this->cek_device = $this->login_model->get_data_wali_device($this->device_id);
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
		$this->load->model('profil_model');
	}

	public function index()
	{
		$data = $this->profil_model->get_data_siswa_row($this->login_uid);

		if(empty($data))
		{
			$respon = array(
				'status'	=> '201',
				'msg'		=> 'Data tidak ditemukan',
				'data'		=> array()
			);	
		}
		else
		{
			$data->foto = default_foto_user($data->foto);
			unset($data->siswa_id);
			unset($data->terakhir_login);
			unset($data->user_id);
			unset($data->username);
			unset($data->password);
			unset($data->password_ortu);
			unset($data->status);
			unset($data->level);

			$respon = array(
				'status'	=> '200',
				'msg'		=> 'Data ditemukan',
				'data'		=> $data
			);	
		}
		echo json_encode($respon);
		exit;
	}

	function submit_password()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Gagal',
			'data'		=> ''
		);

		$data_post = $this->input->post();
		$this->form_validation->set_rules('password_lama', 'Password Lama', 'required');
		$this->form_validation->set_rules('password_baru', 'Password Baru', 'required');
		$this->form_validation->set_rules('password_baru_ulangi', 'Ulangi Password Baru', 'required|matches[password_baru]');
		if($this->form_validation->run() == false)
		{
			$respon = array(
				'status'	=> '201',
				'msg'		=> validation_errors(),
				'data'		=> ''
			);
		}
		else
		{
			$password_lama = $this->cek_device->password_ortu;
			if($password_lama != md5($data_post['password_lama']))
			{
				$respon = array(
					'status'	=> '201',
					'msg'		=> 'Password lama tidak valid',
					'data'		=> ''
				);				
			}
			else
			{
				$param = array('password_ortu'	=> md5($data_post['password_baru']));				
				$proses = $this->profil_model->update_siswa($param, $this->login_uid);
				if($proses)
				{
					$respon = array(
						'status'	=> '200',
						'msg'		=> 'Password berhasil diperbaharui',
						'data'		=> ''
					);									
				}
				else
				{
					$respon = array(
						'status'	=> '201',
						'msg'		=> 'Password dilarang sama dengan yang lama.',
						'data'		=> ''
					);									
				}
			}
		}
		echo json_encode($respon);
	}		
}

/* End of file Logout.php */
