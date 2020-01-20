<?php

class Pengaturan_sesi_model extends CI_Model
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
				$this->db->where('a.nama', $param['keyword']);
			}
		}
		$this->db->select('a.*');
		$this->db->order_by('a.nama');
		$this->db->from('master_sesi a');
		$get = $this->db->get();
		return $get;
	}

	function get_data_row($id)
	{
		$this->db->where('sesi_id', $id);
		$get = $this->db->get('master_sesi');
		return $get->row();
	}

	function insert($data)
	{
		$this->db->insert('master_sesi', $data);
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
		$this->db->where('sesi_id', $id);
		$this->db->update('master_sesi', $data);
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
		$this->db->where('sesi_id', $id);
		$this->db->delete('master_sesi');
		return true;
	}

	function get_opt()
	{
		$this->db->order_by('nama');
		$this->db->from('master_sesi');
		$query = $this->db->get();

		$result = array();
		foreach($query->result() as $key => $c)
		{
			$result[$c->sesi_id] = $c->nama;
		}
		return $result;
	}	
}
