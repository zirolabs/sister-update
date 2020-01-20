<?php

class Subkategori_model extends CI_Model
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

			if(!empty($param['id_subkategori']))
			{
				$this->db->where('id_subkategori', $param['id_subkategori']);
			}			

			if(!empty($param['keyword']))
			{
				$this->db->like('deskripsi_pelanggaran', $param['keyword']);
			}

		}
		$this->db->order_by('id_subkategori','ASC');
		$this->db->from('tbl_subkategori');
		$this->db->join('tbl_kategori', 'tbl_subkategori.id_kategori = tbl_kategori.id_kategori');
		$get = $this->db->get();
		return $get;
	}

	function get_data_row($id)
	{
		$this->db->where('id_subkategori', $id);
		$this->db->from('tbl_subkategori');
		$query = $this->db->get();
		return $query->row();
	}

	function insert($data)
	{
		$this->db->insert('tbl_subkategori', $data);
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
		$this->db->where('id_subkategori', $id);
		$this->db->update('tbl_subkategori', $data);
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
		$this->db->where('id_subkategori', $id);
		$this->db->delete('tbl_subkategori');
		return true;
	}
	
	function get_opt($addon = '', $kategori_id = '')
	{
		

		$result = array();
		if(!empty($addon))
		{
			$result['']	= $addon;
		}
		if(!empty($kategori_id))
		{
			$this->db->where('id_kategori', $kategori_id);
		}
		$this->db->order_by('deskripsi_pelanggaran');
		$this->db->from('tbl_subkategori');
		$query = $this->db->get();
		foreach($query->result() as $key => $c)
		{
			$result[$c->id_subkategori] = $c->deskripsi_pelanggaran;
		}
		return $result;
	}	
	
	function get_point($id)
	{
		$this->db->where('id_subkategori', $id);
		$this->db->from('tbl_subkategori');
		$query = $this->db->get();
		return $query->row()->point_pelanggaran;
	}
	
}
