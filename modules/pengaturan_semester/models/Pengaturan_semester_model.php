<?php

class Pengaturan_semester_model extends CI_Model
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
		$this->db->order_by('a.tahun_mulai', 'DESC');
		$this->db->order_by('a.tahun_akhir', 'DESC');
		$this->db->from('semester a');
		$get = $this->db->get();
		return $get;
	}

	function get_data_row($id)
	{
		$this->db->where('semester_id', $id);
		$get = $this->db->get('semester');
		return $get->row();
	}

	function insert($data)
	{
		$this->db->insert('semester', $data);
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
		$this->db->where('semester_id', $id);
		$this->db->update('semester', $data);
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
		$this->db->where('semester_id', $id);
		$this->db->delete('semester');
		return true;
	}

	function get_opt($add_ons = '')
	{
		$this->db->order_by('a.tahun_mulai', 'DESC');
		$this->db->order_by('a.tahun_akhir', 'DESC');
		$this->db->from('semester a');
		$query = $this->db->get();

		$result = array();
		if(!empty($add_ons))
		{
			$result[''] = $add_ons;
		}
		foreach($query->result() as $key => $c)
		{
			$result[$c->semester_id] = $c->tahun_mulai . '/' . $c->tahun_akhir . ' ' . $c->nama;
		}
		return $result;
	}	
}
