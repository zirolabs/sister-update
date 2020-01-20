<?php

class Pengaturan_jadwal_pelajaran_model extends CI_Model
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

			if(!empty($param['sekolah']))
			{
				$this->db->where('a.sekolah_id', $param['sekolah']);
			}

			if(!empty($param['kelas']))
			{
				$this->db->where('a.kelas_id', $param['kelas']);
			}

			if(isset($param['hari']))
			{
				$this->db->where('a.hari', $param['hari']);
			}

			if(!empty($param['user_id']))
			{
				$this->db->where('a.user_id', $param['user_id']);
			}

			if(!empty($param['kelas_id']))
			{
				$this->db->where('a.kelas_id', $param['kelas_id']);
			}
		}
		$this->db->select("
			a.*, 
			b.nama as nama_mata_pelajaran, 
			c.nama as nama_guru,
			d.nip as nip_guru,
			CONCAT(e.jenjang, ' ', f.nama, ' ', e.nama) AS kelas
		");
		$this->db->order_by('a.jam_mulai');
		$this->db->from('jadwal_pelajaran a');
		$this->db->join('master_mata_pelajaran b', 'a.mata_pelajaran_id = b.mata_pelajaran_id');
		$this->db->join('user c', 'c.user_id = a.user_id');
		$this->db->join('user_guru d', 'd.user_id = c.user_id');
		$this->db->join('master_kelas e', 'e.kelas_id = a.kelas_id');
		$this->db->join('master_jurusan f', 'f.jurusan_id = e.jurusan_id');		
		$get = $this->db->get();
		return $get;
	}

	function get_data_row($id)
	{
		$this->db->where('jadwal_id', $id);
		$get = $this->db->get('jadwal_pelajaran');
		return $get->row();
	}

	function insert($data)
	{
		$this->db->insert('jadwal_pelajaran', $data);
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
		$this->db->where('jadwal_id', $id);
		$this->db->update('jadwal_pelajaran', $data);
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
		$this->db->where('jadwal_id', $id);
		$this->db->delete('jadwal_pelajaran');
		return true;
	}

	function get_opt()
	{
		$this->db->order_by('nama');
		$this->db->from('jadwal_pelajaran');
		$query = $this->db->get();

		$result = array();
		foreach($query->result() as $key => $c)
		{
			$result[$c->jadwal_id] = $c->nama;
		}
		return $result;
	}	
}
