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
		$cek_device = $this->login_model->get_data_guru_device($this->device_id);
		if(empty($cek_device))
		{
			$respon = array(
				'status'	=> '201',
				'msg'		=> 'Gagal',
				'data'		=> 'Autentikasi Gagal.'
			);	
			echo json_encode($respon);
			exit;
		}

		$this->login_uid	= $cek_device->user_id;		
	}

	public function index()
	{
		$this->load->model('login/login_model');
		$param_update = array('device_id' => '', 'fcm' => '');
		$this->login_model->update_login($param_update, $this->login_uid);

		$respon = array(
			'status'	=> '200',
			'msg'		=> 'Sukses',
			'data'		=> 'Logout berhasil'
		);	
		echo json_encode($respon);
		exit;
	}
}

/* End of file Logout.php */
