<?php

class Manajemen_operator_model extends CI_Model
{
	function get_data($param = array())
	{
		$level_user 	= $this->session->userdata('login_level');
		$id_user 	 	= $this->session->userdata('login_uid');

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
				$this->db->like('a.nama', $param['keyword']);
			}

			if(!empty($param['sekolah']))
			{
				$this->db->where('b.sekolah_id', $param['sekolah']);
			}

			if(!empty($param['kepala sekolah']))
			{
				$level_user = 'kepala sekolah';
				$id_user 	= $param['kepala sekolah'];
			}			
		}

		$this->db->select("
			a.*, 
			b.*, 
			c.nama as sekolah
		");
		$this->db->order_by('a.nama');
		$this->db->from('user a');
		$this->db->join('user_operator b', 'b.user_id = a.user_id');
		$this->db->join('profil_sekolah c', 'b.sekolah_id = c.sekolah_id');

		if($level_user == 'kepala sekolah')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_kepala_sekolah x', 'x.sekolah_id = c.sekolah_id');
		}

		$get = $this->db->get();
		return $get;
	}

	function get_data_row($id)
	{
		$this->db->select('a.*, b.*');
		$this->db->where('a.user_id', $id);
		$this->db->from('user_operator a');
		$this->db->join('user b', 'b.user_id = a.user_id');
		$get = $this->db->get();
		return $get->row();
	}

	function insert($data)
	{
		$this->db->insert('user_operator', $data);
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
		$this->db->update('user_operator', $data);
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
		$this->db->where('user_id', $id);
		$this->db->delete('user_operator');
		return true;
	}
}
