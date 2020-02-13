<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$app_name = $this->config->item('app_name');
		delete_cookie('cookie_' . format_uri($app_name, '_'));
	    $this->session->sess_destroy();

		$this->session->unset_userdata('login_status');
		$this->session->unset_userdata('login_uid');
		$this->session->unset_userdata('login_level');
		$this->session->set_flashdata('msg', suc_msg('Logout Berhasil.'));
		redirect();
	}
}

/* End of file Logout.php */
