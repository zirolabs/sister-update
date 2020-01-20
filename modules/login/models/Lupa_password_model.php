<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lupa_password_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();		
	}

	function get_login($email)
	{
		$this->db->or_where('username', $email);
		$this->db->or_where('email', $email);
		$this->db->from('user');
		$query = $this->db->get();
		return $query;
	}

	function update($user_id, $data)
	{
		$this->db->where('user_id', $user_id);
		$this->db->update('user', $data);

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

/* End of file Lupa_password_model.php */
/* Location: ./application/models/Lupa_password_model.php */