<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lupa_password extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('lupa_password_model');
		$this->page_active = 'login';

		$login_status = $this->session->userdata('login_status');
		if($login_status == 'ok')
		{
			redirect('home');
		}
	}

	public function index()
	{
		$param['main_content']	= 'form_lupa_password';
		$param['page_active'] 	= $this->page_active;		
		$this->templates->load('login_templates', $param);		
	}

	function submit()
	{
		$this->form_validation->set_rules('email', 'Email / Username', 'required');
		$this->form_validation->set_rules('password', 'Password Baru', 'required|min_length[5]');
		$this->form_validation->set_rules('cpassword', 'Ulangi Password Baru', 'required|matches[password]');
		if($this->form_validation->run() == false)
		{
			$respon = array('status' 	=> '201', 
							'data' 		=> validation_errors('', ''), 
							'msg'	 	=> 'Reset password gagal.');
		}
		else
		{	
			$email 		= $this->input->post('email');
			$password 	= $this->input->post('password');
			$get_data_login = $this->lupa_password_model->get_login($email)->row();
			if(!empty($get_data_login))
			{
				$param_update = array('password' => md5($password));
				$proses = $this->lupa_password_model->update($get_data_login->user_id, $param_update);
				if($proses)
				{
					$this->session->set_flashdata('msg', suc_msg('Proses reset password berhasil, silahkan login menggunakan password baru Anda.'));

					$respon = array('status' => '200', 
									'data' 	 => 'Proses reset password berhasil.', 
									'msg' 	 => 'Proses reset password berhasil.');				

					//Mail Administrator
					$param_mail_user = array(
											'mail_to'		=> $get_data_login->user_id,
											'template_id' 	=> 'reset_password_notif_user',
											'data' 			=> array('nama' 			=> $get_data_login->nama,
																	 'username'			=> $get_data_login->username,
																	 'email'			=> $get_data_login->email,
																	 'password' 		=> $password)
									   	);
					send_mail($param_mail_user);
					//End Of Mail Administrator

				}
				else
				{
					$respon = array('status' => '201', 
									'data' 	 => 'Proses reset password gagal.', 
									'msg' 	 => 'Proses reset password gagal.');				
				}
			}
			else
			{
				$respon = array('status' 	=> '201', 
								'data' 		=> 'Permintaan reset password gagal.', 
								'msg' 	 	=> 'Permintaan reset password gagal.');				
			}
		}

		echo json_encode($respon);
	}

}

/* End of file Lupa_password.php */
/* Location: ./application/controllers/Lupa_password.php */