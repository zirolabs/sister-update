<?php

class Mata_pelajaran_nilai_model extends CI_Model
{
	function get_jenis($index = '')
	{
		$list = array(
			'tugas'		=> 'Tugas',
			'ulangan'	=> 'Ulangan',
			'UTS'		=> 'UTS',
			'UAS'		=> 'UAS'
		);

		if(!empty($index))
		{
			return $list[$index];
		}
		return $list;
	}

	function get_data($param = array())
	{
		if(!empty($param))
		{
			if(!empty($param['guru_id']))
			{
				$this->db->where('d.user_id', $param['guru_id']);
			}

			if(!empty($param['sekolah']))
			{
				$this->db->where('d.sekolah_id', $param['sekolah']);
			}

			if(!empty($param['kelas']))
			{
				$this->db->where('d.kelas_id', $param['kelas']);
			}

			if(!empty($param['mata_pelajaran']))
			{
				$this->db->where('d.mata_pelajaran_id', $param['mata_pelajaran']);
			}

			if(!empty($param['jenis']))
			{
				$this->db->where('d.jenis', $param['jenis']);
			}
		}

		$this->db->select("
			a.*, 
			b.nama as nama_siswa,
			c.nis as nis_siswa,
			a.nilai,
			d.keterangan
		");
		$this->db->order_by('b.nama, c.nis');
		$this->db->from('user b');
		$this->db->join('user_siswa c', 'c.user_id = b.user_id');
		$this->db->join('mata_pelajaran_nilai_detail a', 'a.user_id = b.user_id', 'left');
		$this->db->join('mata_pelajaran_nilai d', 'd.nilai_id = a.nilai_id', 'left');
		$get = $this->db->get();
		return $get;
	}

	function get_data_v_siswa($param = array())
	{
		if(!empty($param))
		{
			if(!empty($param['user_id']))
			{
				$this->db->where('b.user_id', $param['user_id']);
			}
			if(!empty($param['semester']))
			{
				$this->db->where('a.semester_id', $param['semester']);
			}
			if(!empty($param['kelas']))
			{
				$this->db->where('a.kelas_id', $param['kelas']);
			}
			if(!empty($param['mata_pelajaran']))
			{
				$this->db->where('a.mata_pelajaran_id', $param['mata_pelajaran']);
			}
			if(!empty($param['jenis']))
			{
				$this->db->where('a.jenis', $param['jenis']);
			}
		}

		$this->db->order_by('a.keterangan');
		$this->db->select('a.jenis, a.keterangan, b.nilai');
		$this->db->from('mata_pelajaran_nilai a');
		$this->db->join('mata_pelajaran_nilai_detail b', 'a.nilai_id = b.nilai_id');
		$query = $this->db->get();
		return $query;
	}

	function get_data_siswa($param = array())
	{
		$query_str = '';
		if(!empty($param))
		{
			if(!empty($param['id']))
			{
				$query_str = ", x.nilai, z.keterangan";
				$this->db->join("mata_pelajaran_nilai_detail x", "a.user_id = x.user_id AND x.nilai_id = '$param[id]'", "left");
				$this->db->join("mata_pelajaran_nilai z", 'z.nilai_id = x.nilai_id', 'left');
			}
			else
			{
				if(!empty($param['kelas']))
				{
					$this->db->where('b.kelas_id', $param['kelas']);
				}				
			}
		}

		$this->db->order_by('a.nama');
		$this->db->select('a.user_id, a.nama, a.foto, b.nis' . $query_str);
		$this->db->from('user a');
		$this->db->join('user_siswa b', 'a.user_id = b.user_id');
		$query = $this->db->get();
		return $query;
	}

	function get_data_row($id)
	{
		$this->db->where('nilai_id', $id);
		$get = $this->db->get('mata_pelajaran_nilai');
		return $get->row();
	}

	function insert($data)
	{
		$this->db->insert('mata_pelajaran_nilai', $data);
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
		$this->db->where('nilai_id', $id);
		$this->db->update('mata_pelajaran_nilai', $data);
		return true;
	}

	function delete($id)
	{
		$this->db->where('nilai_id', $id);
		$this->db->delete('mata_pelajaran_nilai');
		return true;
	}

	function insert_nilai($param = array())
	{
		$this->delete_nilai($param[0]['nilai_id']);
		$this->db->insert_batch('mata_pelajaran_nilai_detail', $param);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		return false;
	}	

	function delete_nilai($kelas_id)
	{
		$this->db->where('nilai_id', $kelas_id);
		$this->db->delete('mata_pelajaran_nilai_detail');
	}

	function get_kelas($nilai_id)
	{
		$query_str = "
			SELECT CONCAT(x2.jenjang, ' ', x3.nama, ' ', x2.nama) as nama, x2.kelas_id
			FROM mata_pelajaran_nilai_kelas x1
			JOIN master_kelas x2 ON x1.kelas_id = x2.kelas_id
			JOIN master_jurusan x3 ON x2.jurusan_id = x3.jurusan_id
			WHERE x1.nilai_id = '$nilai_id'
		";
		$query = $this->db->query($query_str);
		return $query->result();
	}

}
