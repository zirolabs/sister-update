<?php

class Absensi_laporan_periode_model extends CI_Model
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

			if(!empty($param['keyword']))
			{
				$this->db->where('a.nama', $param['keyword']);
			}

			if(!empty($filter['sekolah']))
			{
				$this->db->where('c.sekolah_id', $filter['sekolah']);
			}

			if(!empty($filter['kelas']))
			{
				$this->db->where('a.kelas_id', $filter['kelas']);
			}

			if(!empty($filter['tanggal_awal']))
			{
				$this->db->where('DATE(a.waktu) >= ', $filter['tanggal_awal']);				
			}

			if(!empty($filter['tanggal_akhir']))
			{
				$this->db->where('DATE(a.waktu) <= ', $filter['tanggal_akhir']);				
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

		$this->db->select('
			b.foto,
			b.nama, 
			c.nis' . $query_str
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
}
