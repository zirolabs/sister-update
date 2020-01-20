<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notifikasi_fcm_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();		
	}

	function get_data($param = array())
	{
		if(!empty($param))
		{
			if(!empty($param['user_id']))
			{
				$this->db->where('a.user_id', $param['user_id']);
			}

			if(!empty($param['baca']))
			{
				$this->db->where('a.baca', $param['baca']);
			}

			if(!empty($param['target_user']))
			{
				$this->db->where('a.target_user', $param['target_user']);
			}
		}
		$this->db->select('a.*');
		$this->db->order_by('a.fcm_id', 'DESC');
		$this->db->from('notifikasi_fcm a');
		$get = $this->db->get();
		return $get;
	}	

	function update_data($param = array(), $fcm_id)
	{
		$this->db->where('fcm_id', $fcm_id);
		$this->db->update('notifikasi_fcm', $param);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function get_jumlah_notifikasi($user_id, $target_user = 'user')
	{
		$this->db->where('target_user', $target_user);		
		$this->db->where('user_id', $user_id);
		$this->db->where('baca', 'N');
		$this->db->where('status', 'terkirim');
		$this->db->select('COUNT(*) as jumlah');
		$this->db->from('notifikasi_fcm');
		$query = $this->db->get();
		return $query->row()->jumlah;
	}
}

/* End of file Notifikasi_fcm_model.php */
/* Location: ./application/models/Notifikasi_fcm_model.php */