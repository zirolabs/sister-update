<?php

class Kategori_model extends CI_Model
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

			if(!empty($param['id_kategori']))
			{
				$this->db->where('id_kategori', $param['id_kategori']);
			}			

			if(!empty($param['keyword']))
			{
				$this->db->like('nama_pelanggaran', $param['keyword']);
			}

		}
		$this->db->order_by('id_kategori','ASC');
		$this->db->from('tbl_kategori');
		$get = $this->db->get();
		return $get;
	}

	function get_data_row($id)
	{
		$this->db->where('id_kategori', $id);
		$this->db->from('tbl_kategori');
		$query = $this->db->get();
		return $query->row();
	}

	function insert($data)
	{
		$this->db->insert('tbl_kategori', $data);
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
		$this->db->where('id_kategori', $id);
		$this->db->update('tbl_kategori', $data);
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
		$this->db->where('id_kategori', $id);
		$this->db->delete('tbl_kategori');
		return true;
	}

	function get_opt($addon = '')
	{
		$this->db->order_by('nama_pelanggaran');
		$this->db->from('tbl_kategori');
		$query = $this->db->get();

		$result = array();
		if(!empty($addon))
		{
			$result['']	= $addon;
		}
		foreach($query->result() as $key => $c)
		{
			$result[$c->id_kategori] = $c->nama_pelanggaran;
		}
		return $result;
	}	
}
