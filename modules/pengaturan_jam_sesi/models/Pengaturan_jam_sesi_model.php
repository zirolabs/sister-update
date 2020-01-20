<?php

class Pengaturan_jam_sesi_model extends CI_Model
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
				$this->db->like('b.nama', $param['keyword']);
			}

			if(!empty($param['sekolah']))
			{
				$this->db->where('c.sekolah_id', $param['sekolah']);
			}
		}
		$this->db->select('a.*, b.nama as nama_sesi, c.nama as nama_sekolah');
		$this->db->order_by('c.nama');
		$this->db->order_by('b.nama');
		$this->db->from('master_sesi_jam a');
		$this->db->join('master_sesi b', 'a.sesi_id = b.sesi_id');
		$this->db->join('profil_sekolah c', 'c.sekolah_id = a.sekolah_id');
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
