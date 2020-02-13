<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('login_model');
		$this->page_active 	= 'login';

		$this->load->helper('cookie');
		$app_name = $this->config->item('app_name');
		$this->cookie_name	= 'cookie_' . format_uri($app_name, '_');
		$login_status = $this->session->userdata('login_status');
		if($login_status == 'ok')
		{
			redirect('home');
		}
		else
		{
			$cek_login = get_cookie($this->cookie_name);
			if(!empty($cek_login))
			{
				$this->auto_login($cek_login);
			}
		}

	}

	function index()
	{
		$param['main_content']	= 'form';
		$param['page_active'] 	= $this->page_active;
		$this->templates->load('login_templates', $param);
	}

	function auto_login($data_cookie)
	{
		$get_data_login = $this->login_model->get_login($data_cookie)->row();
		$this->session->set_flashdata('msg', suc_msg('Selamat Datang.'));
		$this->session->set_userdata('login_status', 'ok');
		$this->session->set_userdata('login_uid', $get_data_login->user_id);
		$this->session->set_userdata('login_level', $get_data_login->level);
		redirect('home');
	}

	function submit()
	{
		$this->form_validation->set_rules('email', 'Email / Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if($this->form_validation->run() == false)
		{
			$respon = array('status' => '201', 'data' => validation_errors('', ''), 'msg' => 'Login gagal.');
		}
		else
		{
			$email 	= $this->input->post('email');
			$get_data_login = $this->login_model->get_login($email)->row();
			if(!empty($get_data_login))
			{
				$password = $this->input->post('password');
				if(md5($password) == $get_data_login->password)
				{
					if($get_data_login->status == 'blokir')
					{
						$respon = array('status' 	=> '201',
										'data' 		=> 'Login gagal, Akun diblokir.',
										'msg' 		=> 'Login gagal, Akun diblokir.');
					}
					elseif($get_data_login->status == 'aktif')
					{
						$respon = array('status' 	=> '200',
										'data' 		=> 'Login berhasil.',
										'msg' 		=> 'Login berhasil.');

						$ingat_saya = $this->input->post('ingat_saya');
						if(!empty($ingat_saya))
						{
							set_cookie($this->cookie_name, $get_data_login->email, '1209600');
						}

						$this->login_model->update_last_login($get_data_login->user_id);

						$this->session->set_flashdata('msg', suc_msg('Login Berhasil, Selamat Datang.'));
						$this->session->set_userdata('login_status', 'ok');
						$this->session->set_userdata('login_uid', $get_data_login->user_id);
						$this->session->set_userdata('login_level', $get_data_login->level);

						// if($get_data_login->level == 'guru')
						// {
						// 	$this->session->set_userdata('login_add_id', $get_data_login->guru_id);
						// }
						// else if($get_data_login->level == 'kepala sekolah')
						// {
						// 	$this->session->set_userdata('login_add_id', $get_data_login->kepsek_id);							
						// }

						if(empty($get_data_login->terakhir_login))
						{
							$terakhir_login = date('H:i:s d-m-Y');
						}
						else
						{
							$terakhir_login = date('H:i:s d-m-Y', strtotime($get_data_login->terakhir_login));
						}
						$this->session->set_userdata('login_terakhir', $terakhir_login);
					}
					else
					{
						$respon = array('status' => '201', 'data' => 'Login Gagal.', 'msg' => 'Login gagal, silahkan ulangi lagi.');
					}
				}
				else
				{
					$respon = array('status' => '201', 'data' => 'Password tidak valid', 'msg' => 'Login gagal.');
				}
			}
			else
			{
				$respon = array('status' => '201', 'data' => 'Email tidak valid', 'msg' => 'Login gagal.');
			}
		}
		echo json_encode($respon);
	}
}
