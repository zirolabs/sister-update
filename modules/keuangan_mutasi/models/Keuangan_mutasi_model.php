<?php

class Keuangan_mutasi_model extends CI_Model
{
	function get_saldo($sn_rfid)
	{
		$this->db->select('
			(
				SUM(CASE WHEN a.jenis = "kredit" THEN a.nominal ELSE 0 END) -
				SUM(CASE WHEN a.jenis = "debit" THEN a.nominal ELSE 0 END)
			) AS total_saldo
		');
		$this->db->where('b.sn_rfid', $sn_rfid);
		$this->db->from('keuangan_mutasi a');
		$this->db->join('user_siswa b', 'a.user_id = b.user_id');
		$query = $this->db->get();
		return $query->row()->total_saldo;
	}

	function get_saldo_by_nis($nis)
	{
		$this->db->select('
			(
				SUM(CASE WHEN a.jenis = "kredit" THEN a.nominal ELSE 0 END) -
				SUM(CASE WHEN a.jenis = "debit" THEN a.nominal ELSE 0 END)
			) AS total_saldo
		');
		$this->db->where('b.nis', $nis);
		$this->db->from('keuangan_mutasi a');
		$this->db->join('user_siswa b', 'a.user_id = b.user_id');
		$query = $this->db->get();
		return $query->row()->total_saldo;
	}

	function insert($param = array())
	{
		$this->db->insert('keuangan_mutasi', $param);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function get_data($param = array(), $method = 'overall')
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
				$this->db->where('b.nama', $param['keyword']);
			}
 
			if(!empty($param['sekolah']))
			{
				$this->db->where('c.sekolah_id', $param['sekolah']);
			}			

			if(!empty($param['user_id']))
			{
				$this->db->where('a.user_id', $param['user_id']);
			}			

			if(!empty($param['kelas']))
			{
				$this->db->where('c.kelas_id', $param['kelas']);
			}			

			if(!empty($param['tgl_awal']))
			{
				$this->db->where('DATE(a.waktu) >= ', $param['tgl_awal']);
			}

			if(!empty($param['tgl_akhir']))
			{
				$this->db->where('DATE(a.waktu) <= ', $param['tgl_akhir']);
			}
		}


		if($method == 'overall')
		{
			$this->db->select("
				b.user_id,
				b.nama as nama_siswa,
				c.nis as nis,
				COUNT(*) AS jml_mutasi,
				CONCAT(d.jenjang, ' ', e.nama, ' ', d.nama) as kelas,
				f.nama as sekolah
			");
			$this->db->group_by('b.user_id');
			$this->db->order_by('b.nama', 'ASC');
		}
		else
		{
			$this->db->select("
				a.*,
				b.user_id,
				b.nama as nama_siswa,
				c.nis as nis,
				CONCAT(d.jenjang, ' ', e.nama, ' ', d.nama) as kelas,
				f.nama as sekolah
			");			
			$this->db->order_by('a.mutasi_id', 'DESC');
		}

		$this->db->from('keuangan_mutasi a');
		$this->db->join('user b', 'a.user_id = b.user_id');
		$this->db->join('user_siswa c', 'c.user_id = b.user_id');
		$this->db->join('master_kelas d', 'd.kelas_id = c.kelas_id');
		$this->db->join('master_jurusan e', 'e.jurusan_id = d.jurusan_id');
		$this->db->join('profil_sekolah f', 'f.sekolah_id = c.sekolah_id');
		$get = $this->db->get();
		return $get;		
	}
}
