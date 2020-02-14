<?php

class Pengaturan_jam_sesi_model extends CI_Model
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
				$this->db->like('b.nama', $param['keyword']);
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

			if(!empty($param['operator sekolah']))
			{
				$level_user = 'operator sekolah';
				$id_user 	= $param['operator sekolah'];
			}
		}
		$this->db->select('a.*, b.nama as nama_sesi, c.nama as nama_sekolah');
		$this->db->order_by('c.nama');
		$this->db->order_by('b.nama');
		$this->db->from('master_sesi_jam a');
		$this->db->join('master_sesi b', 'a.sesi_id = b.sesi_id');
		$this->db->join('profil_sekolah c', 'c.sekolah_id = a.sekolah_id');

		if($level_user == 'kepala sekolah')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_kepala_sekolah x', 'x.sekolah_id = c.sekolah_id');
		}
		elseif($level_user == 'operator sekolah')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_operator x', 'x.sekolah_id = c.sekolah_id');
		}
		elseif($level_user == 'guru')
		{
			$this->db->where('c.user_id', $id_user);
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_guru x', 'x.sekolah_id = c.sekolah_id');			
		}
		$get = $this->db->get();
		return $get;
	}

	function get_data_row($id)
	{
		$this->db->where('jam_id', $id);
		$get = $this->db->get('master_sesi_jam');
		return $get->row();
	}

	function insert($data)
	{
		$this->db->insert('master_sesi_jam', $data);
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
		$this->db->where('jam_id', $id);
		$this->db->update('master_sesi_jam', $data);
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
		$this->db->where('jam_id', $id);
		$this->db->delete('master_sesi_jam');
		return true;
	}

	function get_opt()
	{
		$this->db->order_by('nama');
		$this->db->from('master_sesi_jam');
		$query = $this->db->get();

		$result = array();
		foreach($query->result() as $key => $c)
		{
			$result[$c->jam_id] = $c->nama;
		}
		return $result;
	}	
}
