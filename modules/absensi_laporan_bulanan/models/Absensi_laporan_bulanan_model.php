<?php

class Absensi_laporan_bulanan_model extends CI_Model
{
	function get_data($filter = array())
	{
		$query_str = "";
		if(!empty($filter))
		{
			if(!empty($filter['limit']))
			{
				if(!empty($filter['offset']))
				{
					$this->db->limit($filter['limit'], $filter['offset']);
				}
				else
				{
					$this->db->limit($filter['limit']);
				}
			}
			
			if(!empty($filter['sekolah']))
			{
				$this->db->where('c.sekolah_id', $filter['sekolah']);
			}

			if(!empty($filter['kelas']))
			{
				$this->db->where('a.kelas_id', $filter['kelas']);
			}

			if(!empty($filter['bulan']))
			{
				$this->db->where('MONTH(a.waktu)', $filter['bulan']);				
			}

			if(!empty($filter['tahun']))
			{
				$this->db->where('YEAR(a.waktu)', $filter['tahun']);								
			}

			if(!empty($filter['user_id']))
			{
				$this->db->where('a.user_id', $filter['user_id']);
			}

			if(!empty($filter['opt_jenis']))
			{
				$query_str .= ", ";
				foreach($filter['opt_jenis'] as $key => $c)
				{
					$query_str .= "SUM(CASE WHEN a.status = '$key' THEN 1 ELSE 0 END) AS total_$key,";
				}
				$query_str = substr($query_str, 0, -1);
			}
		}

		$this->db->order_by('b.nama, c.nis');
		$this->db->select('
			b.nama, 
			c.nis,
			b.foto' . 
			$query_str
		);
		$this->db->from('absensi a');
		$this->db->join('user b', 'a.user_id = b.user_id');
		$this->db->join('user_siswa c', 'c.user_id = b.user_id');
		$this->db->group_by('b.user_id, c.nis');

		$level_user 	= $this->session->userdata('login_level');
		$id_user 	 	= $this->session->userdata('login_uid');
		if($level_user == 'kepala sekolah')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_kepala_sekolah x', 'x.sekolah_id = c.sekolah_id');
		}
		elseif($level_user == 'operator sekolah')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_operator x', 'x.sekolah_id = c.sekolah_id');
		}
		elseif($level_user == 'guru')
		{
			$this->db->where('x1.user_id', $id_user);
			$this->db->where('x2.user_id', $id_user);
			$this->db->join('user_guru x1', 'x1.sekolah_id = c.sekolah_id');			
			$this->db->join('master_kelas x2', 'x2.kelas_id = c.kelas_id');			
		}

		$query = $this->db->get();
		return $query;
	}

	function get_log($param = array())
	{
		if(!empty($param))
		{
			if(!empty($param['user_id']))
			{
				$this->db->where('a.user_id', $param['user_id']);
			}

			if(!empty($param['bulan']))
			{
				$this->db->where('MONTH(a.waktu)', $param['bulan']);
			}

			if(!empty($param['tahun']))
			{
				$this->db->where('YEAR(a.waktu)', $param['tahun']);
			}

			if(!empty($param['status']))
			{
				$this->db->where('a.status', $param['status']);
			}
		}

		$this->db->order_by('a.waktu', 'DESC');
		$this->db->select("
			a.status,
			a.waktu,
			a.telat,
			a.keterangan,
			b.nama as nama_sesi,
			CONCAT(c.masuk, ' - ', c.pulang) AS jam_sesi
		");
		$this->db->from('absensi a');
		$this->db->join('master_sesi b', 'a.sesi_id = b.sesi_id', 'left');
		$this->db->join('master_sesi_jam c', 'a.jam_id = c.jam_id', 'left');
		$query = $this->db->get();
		return $query;
	}

	function get_presensi_hari_ini($filter = array())
	{
		$query_str = "
			SELECT x.waktu 
			FROM absensi x 
			WHERE x.status = '$filter[status]' AND 
				  x.user_id = '$filter[user_id]' AND 
				  DATE(x.waktu) = '$filter[tahun]-$filter[bulan]-$filter[hari]'				  
			ORDER BY x.waktu DESC
			LIMIT 1
		";
		$query = $this->db->query($query_str);
		return $query;
	}
}
