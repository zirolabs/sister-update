<?php

class Templates
{
	function load($template_name = '', $param = array())
	{
		if(empty($template_name))
		{
			return '';
		}

		$CI =& get_instance();

		$param['login_status'] 		= $CI->session->userdata('login_status');
		$param['login_uid'] 		= $CI->session->userdata('login_uid');
		$param['login_terakhir'] 	= $CI->session->userdata('login_terakhir');
		$param['login_level'] 		= $CI->session->userdata('login_level');

		$param['msg']	= $CI->session->flashdata('msg');

		if($param['login_status'] == 'ok')
		{
			$param['data_agen']	= $CI->konfigurasi_model->get_data_agen($param['login_uid']);
		}

		$CI->load->view(MY_CONFIG_TEMPLATES . '/' . $template_name, $param);
	}

}
