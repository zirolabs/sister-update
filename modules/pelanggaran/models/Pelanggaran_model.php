<?php

class Pelanggaran_model extends CI_Model
{	
	function get_total_poin($nis)
	{
		$this->db->where('nis', $nis);
		$this->db->select('SUM(point_pelanggaran) as total');
		$this->db->from('tbl_pelanggaransiswa');
		$query = $this->db->get();
		return $query->row()->total;
	}

	function get_data($param = array())
	{
		$level_user 	= $this->session->userdata('login_level');
		$id_user 	 	= $this->session->userdata('login_uid');	
		$this->db->select('
			a.*, 
			b.*,
			c.*,
			d.*,
			e.*,
			i.nama as nama_guru
		');
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
			if(!empty($param['id_pelanggaran']))
			{
				$this->db->where('a.id_pelanggaran', $param['id_pelanggaran']);
			}			

			if(!empty($param['keyword']))
			{
				$this->db->like('nama', $param['keyword']);
			}
			if(!empty($param['sekolah']))
			{
				$this->db->where('c.sekolah_id', $param['sekolah']);
			}
			if(!empty($param['kelas']))
			{
				$this->db->where('c.kelas_id', $param['kelas']);
			}
			if(!empty($param['nis']))
			{
				$this->db->where('c.nis', $param['nis']);
			}	
			if((!empty($param['tanggal_awal']))&&(!empty($param['tanggal_akhir']))&&($level_user =='administrator'))
			{
				$condition = "a.tanggal_pelanggaran BETWEEN " . "'" . $param['tanggal_awal'] . "'" . " AND " . "'" . $param['tanggal_akhir']. "' ORDER BY 'tanggal_pelanggaran' ASC";
				$this->db->where($condition);
			}

		}
		$this->db->from('tbl_pelanggaransiswa a');
		$this->db->join('tbl_subkategori b', 'a.subkategori = b.id_subkategori');
		$this->db->join('user_siswa c', 'a.nis = c.nis');
		$this->db->join('user d', 'c.user_id = d.user_id');
		$this->db->join('user_guru e', 'e.guru_id = a.guru_id');
		$this->db->join('user i', 'i.user_id = e.user_id');
		if($level_user == 'guru')
		{
			$this->db->where('f.user_id', $id_user);
			$this->db->join('user_guru f', 'f.sekolah_id = c.sekolah_id');			
		}elseif($level_user == 'kepala sekolah')
		{
			$this->db->where('g.user_id', $id_user);
			$this->db->join('user_kepala_sekolah g', 'g.sekolah_id = c.sekolah_id');
		}
		elseif($level_user == 'operator sekolah')
		{
			$this->db->where('h.user_id', $id_user);
			$this->db->join('user_operator h', 'h.sekolah_id = c.sekolah_id');
		}
		$get = $this->db->get();
		return $get;
	}
	

	function get_data_row($id)
	{
		$this->db->where('id_pelanggaran', $id);
		$this->db->from('tbl_pelanggaransiswa');
		$query = $this->db->get();
		return $query->row();
	}

	function insert($data)
	{
		$this->db->insert('tbl_pelanggaransiswa', $data);
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
		$this->db->where('id_pelanggaran', $id);
		$this->db->update('tbl_pelanggaransiswa', $data);
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
		$this->db->where('id_pelanggaran', $id);
		$this->db->delete('tbl_pelanggaransiswa');
		return true;
	}
	
	function search($new_keyword)
	{
		$level_user 	= $this->session->userdata('login_level');
		$id_user 	 	= $this->session->userdata('login_uid');
		$this->db->like('a.nama', $new_keyword);
		$this->db->select('
			a.*, 
			b.*, 
			c.nama as nama_sekolah,
			d.nama as nama_kelas,
			d.jenjang,
			e.nama as nama_jurusan,
		');
		$this->db->from('user a');
		$this->db->join('user_siswa b', 'a.user_id = b.user_id');
		$this->db->join('profil_sekolah c', 'c.sekolah_id = b.sekolah_id');
		$this->db->join('master_kelas d', 'd.kelas_id = b.kelas_id');
		$this->db->join('master_jurusan e', 'e.jurusan_id = d.jurusan_id');
		if($level_user == 'guru')
		{
			$this->db->where('f.user_id', $id_user);
			$this->db->join('user_guru f', 'f.sekolah_id = b.sekolah_id');			
		}elseif($level_user == 'kepala sekolah')
		{
			$this->db->where('g.user_id', $id_user);
			$this->db->join('user_kepala_sekolah g', 'g.sekolah_id = b.sekolah_id');
		}
		elseif($level_user == 'operator sekolah')
		{
			$this->db->where('h.user_id', $id_user);
			$this->db->join('user_operator h', 'h.sekolah_id = b.sekolah_id');
		}
		$get = $this->db->get();
		return $get;
	}
	
	function get_opt($addon = '', $id = '')
	{
		

		$result = array();
		if(!empty($addon))
		{
			$result['']	= $addon;
		}
		if(!empty($id))
		{
			$this->db->where('a.sekolah_id', $id);
		}
		$this->db->from('user_guru a');
		$this->db->join('user b', 'a.user_id = b.user_id');
		$query = $this->db->get();
		foreach($query->result() as $key => $c)
		{
			$result[$c->guru_id] = $c->nama;
		}
		return $result;
	}	
	
	function cari_sekolah($id)
	{
		$this->db->select('
			a.*, 
			b.*,
			b.alamat as alamat_siswa,			
			c.*,
			c.nama as nama_sekolah,
			d.nama as nama_siswa,
			e.nama as nama_kelas,
			e.jenjang,
			f.nama as nama_jurusan,
			g.*,
			h.*,
			i.nama as nama_guru
		');
		$this->db->where('a.id_pelanggaran', $id);
		$this->db->from('tbl_pelanggaransiswa a');
		$this->db->join('user_siswa b', 'a.nis = b.nis');
		$this->db->join('profil_sekolah c', 'c.sekolah_id = b.sekolah_id');
		$this->db->join('user d', 'd.user_id = b.user_id');
		$this->db->join('master_kelas e', 'e.kelas_id = b.kelas_id');
		$this->db->join('master_jurusan f', 'f.jurusan_id = e.jurusan_id');
		$this->db->join('tbl_subkategori g', 'g.id_subkategori = a.subkategori');
		$this->db->join('user_guru h', 'h.guru_id = a.guru_id');
		$this->db->join('user i', 'i.user_id = h.user_id');
		$get = $this->db->get();
		return $get->result();
	}
	
	function laporan_per_siswa($nis)
	{
		$this->db->select('
			a.*, 
			b.*,
			b.alamat as alamat_siswa,			
			c.*,
			c.nama as nama_sekolah,
			d.nama as nama_siswa,
			e.nama as nama_kelas,
			e.jenjang,
			f.nama as nama_jurusan,
			g.*,
			h.*,
			i.nama as nama_guru
		');
		$this->db->where('a.nis', $nis);
		$this->db->from('tbl_pelanggaransiswa a');
		$this->db->join('user_siswa b', 'a.nis = b.nis');
		$this->db->join('profil_sekolah c', 'c.sekolah_id = b.sekolah_id');
		$this->db->join('user d', 'd.user_id = b.user_id');
		$this->db->join('master_kelas e', 'e.kelas_id = b.kelas_id');
		$this->db->join('master_jurusan f', 'f.jurusan_id = e.jurusan_id');
		$this->db->join('tbl_subkategori g', 'g.id_subkategori = a.subkategori');
		$this->db->join('user_guru h', 'h.guru_id = a.guru_id');
		$this->db->join('user i', 'i.user_id = h.user_id');
		$get = $this->db->get();
		return $get->result();
	}
}
