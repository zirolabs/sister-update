<?php

class Verifikasi_dispensasi_model extends CI_Model
{
	function get_data($param = array())
	{
		$this->db->select("
			a.*, 
			b.nama as nama_siswa,
			c.nis,
			CONCAT(d.jenjang, ' ', e.nama, ' ', d.nama) as kelas,
			f.nama as sekolah
		");

		$this->db->where('DATE(a.tgl_selesai) >= ', date('Y-m-d'));
		$this->db->order_by('a.tgl_mulai', 'ASC');
		$this->db->order_by('a.tgl_selesai', 'ASC');
		$this->db->from('absensi_dispensasi a');		
		$this->db->join('user b', 'a.user_id = b.user_id');
		$this->db->join('user_siswa c', 'b.user_id = c.user_id');
		$this->db->join('master_kelas d', 'd.kelas_id = c.kelas_id');
		$this->db->join('master_jurusan e', 'e.jurusan_id = d.jurusan_id');
		$this->db->join('profil_sekolah f', 'f.sekolah_id = c.sekolah_id');

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
				$this->db->where('b.nama', $param['keyword']);
			}
 
			if(!empty($param['sekolah']))
			{
				$this->db->where('c.sekolah_id', $param['sekolah']);
			}			

			if(!empty($param['kelas']))
			{
				$this->db->where('c.kelas_id', $param['kelas']);
			}		

			if(!empty($param['dispensasi_id']))	
			{
				$this->db->where('a.dispensasi_id', $param['dispensasi_id']);				
			}
		}

		$get = $this->db->get();
		return $get;
	}

	function insert($param_db = array())
	{
		$this->db->insert('absensi_dispensasi', $param_db);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function update($param_db = array(), $id)
	{
		$this->db->where('dispensasi_id', $id);
		$this->db->update('absensi_dispensasi', $param_db);
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
		$this->db->where('dispensasi_id', $id);
		$this->db->delete('absensi_dispensasi');
		return true;
	}
}
