<?php

class Keuangan_rfid_model extends CI_Model
{
	function get_data($param = array())
	{
		$this->db->select("
			a.*, 
			b.nama as nama_siswa,
			CONCAT(c.jenjang, ' ', d.nama, ' ', c.nama) as kelas,
			e.nama as sekolah
		");
		$this->db->order_by('b.nama', 'DESC');
		$this->db->from('user_siswa a');
		$this->db->join('user b', 'a.user_id = b.user_id');
		$this->db->join('master_kelas c', 'c.kelas_id = a.kelas_id');
		$this->db->join('master_jurusan d', 'd.jurusan_id = c.jurusan_id');
		$this->db->join('profil_sekolah e', 'e.sekolah_id = a.sekolah_id');
		$this->db->where('a.sn_rfid !=','');
		$this->db->or_where('a.status_qr !=','');
		

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
				$this->db->where('a.sekolah_id', $param['sekolah']);
			}			

			if(!empty($param['kelas']))
			{
				$this->db->where('a.kelas_id', $param['kelas']);
			}			

			if(!empty($param['nis']))
			{
				$this->db->where('a.nis', $param['nis']);
			}

			if(!empty($param['sn_rfid']))
			{
				$this->db->where('a.sn_rfid', $param['sn_rfid']);
			}

			if(!empty($param['user_id']))
			{
				$this->db->where('a.user_id', $param['user_id']);
			}
		}

		$get = $this->db->get();
		return $get;
	}

	// get data berdasarkan nis (QR Code)
	function get_data_nis($param = array())
	{
		$this->db->select("
			a.*, 
			b.nama as nama_siswa,
			CONCAT(c.jenjang, ' ', d.nama, ' ', c.nama) as kelas,
			e.nama as sekolah
		");
		$this->db->order_by('b.nama', 'DESC');
		$this->db->from('user_siswa a');
		$this->db->join('user b', 'a.user_id = b.user_id');
		$this->db->join('master_kelas c', 'c.kelas_id = a.kelas_id');
		$this->db->join('master_jurusan d', 'd.jurusan_id = c.jurusan_id');
		$this->db->join('profil_sekolah e', 'e.sekolah_id = a.sekolah_id');
		$this->db->where('a.nis != ', '');
		$this->db->where('a.status_qr != ', '');
		

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
				$this->db->where('a.sekolah_id', $param['sekolah']);
			}			

			if(!empty($param['kelas']))
			{
				$this->db->where('a.kelas_id', $param['kelas']);
			}			

			if(!empty($param['nis']))
			{
				$this->db->where('a.nis', $param['nis']);
			}

			if(!empty($param['user_id']))
			{
				$this->db->where('a.user_id', $param['user_id']);
			}
		}

		$get = $this->db->get();
		return $get;
	}
}
