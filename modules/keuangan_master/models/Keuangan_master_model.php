<?php

class Keuangan_master_model extends CI_Model
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
				$this->db->where('a.sekolah_id', $param['sekolah']);
			}

			if(!empty($param['kepala sekolah']))
			{
				$level_user = 'kepala sekolah';
				$id_user 	= $param['kepala sekolah'];
			}			

			if($level_user == 'kepala sekolah')
			{
				$this->db->where('x.user_id', $id_user);
				$this->db->join('user_kepala_sekolah x', 'x.sekolah_id = a.sekolah_id');
			}
			elseif($level_user == 'operator sekolah')
			{
				$this->db->where('x.user_id', $id_user);
				$this->db->join('user_operator x', 'x.sekolah_id = a.sekolah_id');
			}


		}

		$this->db->select("
			a.*, 
			c.nama as sekolah
		");
		$this->db->order_by('a.nama');
		$this->db->from('keuangan_master a');
		$this->db->join('profil_sekolah c', 'a.sekolah_id = c.sekolah_id');

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
		$this->db->select('a.*');
		$this->db->where('a.master_id', $id);
		$this->db->from('keuangan_master a');
		$get = $this->db->get();
		return $get->row();
	}

	function insert($data)
	{
		$this->db->insert('keuangan_master', $data);
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
		$this->db->where('master_id', $id);
		$this->db->update('keuangan_master', $data);
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
		$this->db->where('master_id', $id);
		$this->db->delete('keuangan_master');
		return true;
	}

	function get_opt($sekolah_id = '')
	{
		if(!empty($sekolah_id))
		{	
			$this->db->where('sekolah_id', $sekolah_id);
		}

		$result = array();
		$this->db->from('keuangan_master');
		$query = $this->db->get();
		foreach($query->result() as $key => $c)
		{
			$result[$c->master_id] = $c->nama . ' (' . format_ucwords($c->operasi) . ' '  . format_rupiah($c->nominal) . ')';
		}
		return $result;
	}
}
