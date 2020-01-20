<?php

class Manajemen_user_model extends CI_Model
{
	function get_data($param = array())
	{
		if(!empty($param))
		{
			if(!empty($param['limit']))
			{
				if(!empty($param['offset']))
				{
					$this->db->limit($param['limit'], $param['offset']);
				}
				else
				{
					$this->db->limit($param['limit']);
				}
			}

			if(!empty($param['keyword']))
			{
				$this->db->or_like('a.email', $param['keyword']);
				$this->db->or_like('a.nama', $param['keyword']);
			}
		}
		$this->db->select('a.*');
		$this->db->where("a.level", "administrator");
		$this->db->where("a.status != 'temporary'");
		$this->db->order_by('a.nama');
		$this->db->from('user a');
		$get = $this->db->get();
		return $get;
	}

	function get_data_row($id)
	{
		$this->db->where('user_id', $id);
		$get = $this->db->get('user');
		return $get->row();
	}

	function insert($data)
	{
		$this->db->insert('user', $data);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function update($data, $id)
	{
		$this->db->where('user_id', $id);
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

	function delete($id)
	{
		$uid = $this->session->userdata('login_uid');
		$this->db->where("user_id != '$uid'");
		$this->db->where('user_id', $id);
		$this->db->delete('user');
		return true;
	}

	function check_email($email)
	{
		$this->db->where('email', $email);
		$this->db->from('user');
		$query = $this->db->get();
		if(!empty($query->row()))
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	function get_user_by_email($email)
	{
		$this->db->where('email', $email);
		$this->db->from('user');
		$query = $this->db->get();
		return $query;
	}

}
