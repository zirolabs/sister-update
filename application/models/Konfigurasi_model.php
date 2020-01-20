<?php

class Konfigurasi_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();

		$cek_login = $this->session->userdata('login_status');
		if(empty($cek_login))
		{
			$app_name = $this->config->item('app_name');
			$data_cookie = get_cookie('cookie_' . format_uri($app_name, '_'));
			if(!empty($data_cookie))
			{
				$get_data_login = $this->get_login($data_cookie)->row();
				if(!empty($get_data_login))
				{
					$this->session->set_userdata('login_status', 'ok');
					$this->session->set_userdata('login_uid', $get_data_login->user_id);
				}
			}
		}

		$data = $this->get_data();
		foreach($data as $key => $c)
		{
			$this->config->set_item($c->konfigurasi_id, $c->isi);
			$this->config->set_item('label_menu_' . $c->konfigurasi_id, $c->label_menu);
		}
	}

	function get_data()
	{
		$this->db->from('konfigurasi');
		$query = $this->db->get();
		return $query->result();
	}

	function update_data($param = array(), $id)
	{
		$this->db->where('konfigurasi_id', $id);
		$this->db->update('konfigurasi', $param);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}	

	function get_data_agen($id)
	{
		$this->db->select('a.*');
		$this->db->where('a.user_id', $id);
		$this->db->from('user a');
		$get = $this->db->get();
		return $get->row();
	}

	function get_login($email, $field = 'email')
	{
		$this->db->where($field, $email);
		$this->db->from('user');
		$query = $this->db->get();
		$row = $query->row();
		if(empty($row))
		{
			if($field == 'email')
			{
				$result = $this->get_login($email, 'no_hp');
				return $result;
			}
		}
		return $query;
	}
}
