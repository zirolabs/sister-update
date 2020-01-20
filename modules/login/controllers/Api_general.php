<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_general extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('login_model');
		$this->load->model('notifikasi_fcm/notifikasi_fcm_model');
		$this->page_active 	= 'login';
	}

	function submit_fcm()
	{
		$respon = array(
			'status' 	=> '201',
			'msg' 		=> 'Gagal memperbaharui Token FCM',
			'data' 		=>  array(),
		);				

		$data_post = $this->input->post();
		$this->form_validation->set_rules('user_id', 'User ID', 'required');
		$this->form_validation->set_rules('token_fcm', 'Token FCM', 'required');
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
			$param_update = array('fcm'	=> $data_post['token_fcm']);
			$proses = $this->login_model->update_login($param_update, $data_post['user_id']);

			if($proses)
			{
				$total_notifikasi = $this->notifikasi_fcm_model->get_jumlah_notifikasi($data_post['user_id']);
				$respon = array(
					'status' 	=> '200',
					'msg' 		=> 'Token FCM Updated',
					'data' 		=>  array('total_notifikasi' => $total_notifikasi),
				);				
			}
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
		if(!empty($data_post['user_id']))
		{
			$total_notifikasi = $this->notifikasi_fcm_model->get_jumlah_notifikasi($data_post['user_id']);
			$respon = array(
				'status' 	=> '200',
				'msg' 		=> 'Notifikasi berhasil ditemukan',
				'data' 		=>  array('total_notifikasi' => $total_notifikasi),
			);									
		}

		echo json_encode($respon);
	}
}
