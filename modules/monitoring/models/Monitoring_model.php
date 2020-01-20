<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monitoring_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
	}

	public function update($param = array(), $user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->update('user_siswa', $param);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

/* End of file Monitoring_model.php */
/* Location: ./application/models/Monitoring_model.php */