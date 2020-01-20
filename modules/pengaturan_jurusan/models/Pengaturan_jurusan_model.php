<?php

class Pengaturan_jurusan_model extends CI_Model
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
		$this->db->from('master_jurusan a');
		$get = $this->db->get();
		return $get;
	}

	function get_data_row($id)
	{
		$this->db->where('jurusan_id', $id);
		$get = $this->db->get('master_jurusan');
		return $get->row();
	}

	function insert($data)
	{
		$this->db->insert('master_jurusan', $data);
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
		$this->db->where('jurusan_id', $id);
		$this->db->update('master_jurusan', $data);
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
		$this->db->where('jurusan_id', $id);
		$this->db->delete('master_jurusan');
		return true;
	}

	function get_opt()
	{
		$this->db->order_by('nama');
		$this->db->from('master_jurusan');
		$query = $this->db->get();

		$result = array();
		foreach($query->result() as $key => $c)
		{
			$result[$c->jurusan_id] = $c->nama;
		}
		return $result;
	}	
}
