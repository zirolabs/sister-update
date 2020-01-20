<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_wali extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('login_model');
		$this->load->model('notifikasi_fcm/notifikasi_fcm_model');		
		$this->page_active 	= 'login';
	}

	function submit()
	{
		$this->form_validation->set_rules('sekolah', 'ID Sekolah', 'required');
		$this->form_validation->set_rules('nis', 'NIS', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if($this->form_validation->run() == false)
		{
			$respon = array(
				'status' 	=> '201', 
				'data' 		=> array(), 
				'msg' 		=> validation_errors('', '')
			);
		}
		else
		{
			$sekolah 	= $this->input->post('sekolah');
			$nis 		= $this->input->post('nis');
			$get_data_login = $this->login_model->get_login_siswa($nis, $sekolah)->row();
			if(!empty($get_data_login))
			{
				$password = $this->input->post('password');
				if(md5($password) == $get_data_login->password_ortu)
				{
					$param_update['device_id_ortu'] = $this->input->post('device_id');
					$this->login_model->update_login_siswa($param_update, $get_data_login->user_id);

					unset($get_data_login->email);
					unset($get_data_login->level);
					unset($get_data_login->no_hp);
					unset($get_data_login->password);
					unset($get_data_login->password_ortu);
					unset($get_data_login->status);
					unset($get_data_login->terakhir_login);
					unset($get_data_login->siswa_id);
					// unset($get_data_login->user_id);
					unset($get_data_login->username);

					$get_data_login->foto = default_foto_user($get_data_login->foto);

					$respon = array(
						'status' 	=> '200',
						'msg' 		=> 'Login berhasil.',
						'data' 		=>  $get_data_login,
					);
				}
				else
				{
					$respon = array(
						'status' 	=> '201', 
						'msg' 		=> 'Password tidak valid',
						'data' 		=>  array(), 
					);
				}
			}
			else
			{
				$respon = array(
					'status' 	=> '201', 
					'msg' 		=> 'NIS tidak valid', 
					'data' 		=> array()
				);
			}
		}
		echo json_encode($respon);
	}

	function check()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Auto login tidak valid',
			'data'		=> array()
		);

		$device_id = $this->input->post('device_id');
		if(empty($device_id))
		{
			$respon = array(
				'status'	=> '201',
				'msg'		=> 'Parameter tidak valid',
				'data'		=> array()
			);
		}
		else
		{
			$cek_data = $this->login_model->get_data_wali_device($device_id);
			if(!empty($cek_data))
			{
				$cek_data->foto = default_foto_user($cek_data->foto);

				unset($cek_data->email);
				unset($cek_data->level);
				unset($cek_data->no_hp);
				unset($cek_data->password);
				unset($cek_data->password_ortu);
				unset($cek_data->status);
				unset($cek_data->terakhir_login);
				unset($cek_data->siswa_id);
				// unset($cek_data->user_id);
				unset($cek_data->username);

				$respon = array(
					'status'	=> '200',
					'msg'		=> 'Auto login OK',
					'data'		=> $cek_data
				);

			}
		}

		echo json_encode($respon);
		exit;
	}	

	function update_fcm()
	{
		$respon = array(
			'status' 	=> '201', 
			'msg' 		=> 'Gagal', 
			'data' 		=> array('total_notifikasi' => 0)
		);

		$data_post = $this->input->post();
		$data_user = $this->login_model->get_data_wali_device($data_post['device_id']);
		if(!empty($data_user))
		{
			$param  = array('fcm_ortu' => $data_post['token_fcm']);
			$proses = $this->login_model->update_login_siswa($param, $data_user->user_id);

			$total_notifikasi = $this->notifikasi_fcm_model->get_jumlah_notifikasi($data_user->user_id, 'wali');
			$respon = array(
				'status' 	=> '200',
				'msg' 		=> 'Token FCM Updated',
				'data' 		=>  array('total_notifikasi' => $total_notifikasi),
			);				
		}
		echo json_encode($respon);
	}

	function total_notifikasi()
	{
		$respon = array(
			'status' 	=> '200',
			'msg' 		=> 'Notifikasi berhasil ditemukan',
			'data' 		=>  array('total_notifikasi' => 0),
		);						

		$data_post = $this->input->post();
		$data_user = $this->login_model->get_data_wali_device($data_post['device_id']);
		if(!empty($data_post['user_id']))
		{
			$total_notifikasi = $this->notifikasi_fcm_model->get_jumlah_notifikasi($data_post['user_id'], 'wali');
			$respon = array(
				'status' 	=> '200',
				'msg' 		=> 'Notifikasi berhasil ditemukan',
				'data' 		=>  array('total_notifikasi' => $total_notifikasi),
			);									
		}

		echo json_encode($respon);
	}	
}
