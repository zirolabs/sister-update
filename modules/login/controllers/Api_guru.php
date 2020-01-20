<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_guru extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('login_model');
		$this->page_active 	= 'login';
	}

	function submit()
	{
		$this->form_validation->set_rules('sekolah', 'ID Sekolah', 'required');
		$this->form_validation->set_rules('nip', 'NIP', 'required');
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
			$sekolah 		= $this->input->post('sekolah');
			$nip 			= $this->input->post('nip');
			$password 		= $this->input->post('password');
			$device_id		= $this->input->post('device_id');
			$get_data_login = $this->login_model->get_login_guru($nip, $sekolah)->row();
			if(!empty($get_data_login))
			{
				if(md5($password) == $get_data_login->password)
				{
					$terakhir_login = date('Y-m-d H:i:s');
					$param_update = array(
						'device_id'			=> $device_id,
						'terakhir_login'	=> $terakhir_login
					);
					$this->login_model->update_login($param_update, $get_data_login->user_id);

					unset($get_data_login->level);
					unset($get_data_login->password);
					unset($get_data_login->status);
					unset($get_data_login->guru_id);
					// unset($get_data_login->user_id);
					unset($get_data_login->username);

					$get_data_login->terakhir_login = format_tanggal_indonesia($terakhir_login, true);
					$get_data_login->foto 			= default_foto_user($get_data_login->foto);

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
					'msg' 		=> 'NIP tidak valid', 
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
			$cek_data = $this->login_model->get_data_guru_device($device_id);
			if(!empty($cek_data))
			{
				$cek_data->foto = default_foto_user($cek_data->foto);
				$cek_data->terakhir_login = format_tanggal_indonesia($cek_data->terakhir_login, true);

				unset($cek_data->level);
				unset($cek_data->password);
				unset($cek_data->status);
				unset($cek_data->guru_id);
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
}
