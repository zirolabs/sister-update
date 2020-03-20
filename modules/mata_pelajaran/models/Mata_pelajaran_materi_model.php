<?php

class Mata_pelajaran_materi_model extends CI_Model
{
	function get_data($param = array())
	{
		$level_user 	= $this->session->userdata('login_level');
		$id_user 	 	= $this->session->userdata('login_uid');

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
				$this->db->like('a.judul', $param['keyword']);
			}

			if(!empty($param['sekolah']))
			{
				$this->db->where('a.sekolah_id', $param['sekolah']);
			}

			if(!empty($param['mata_pelajaran']))
			{
				$this->db->where('a.mata_pelajaran_id', $param['mata_pelajaran']);
			}

			if(!empty($param['kelas']))
			{
				$this->db->where('y.kelas_id', $param['kelas']);
				$this->db->join('mata_pelajaran_materi_kelas y', 'y.materi_id = a.materi_id');
			}

		}

		$this->db->select('
			a.*, 
			b.nama as nama_mata_pelajaran,
			c.nama as nama_sekolah,
			d.nama as nama_uploader
		');
		$this->db->order_by('a.waktu_upload', 'DESC');
		$this->db->from('mata_pelajaran_materi a');
		$this->db->join('master_mata_pelajaran b', 'a.mata_pelajaran_id = b.mata_pelajaran_id');
		$this->db->join('profil_sekolah c', 'c.sekolah_id = a.sekolah_id');
		$this->db->join('user d', 'd.user_id = a.user_id');
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
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_guru x', 'x.sekolah_id = c.sekolah_id');
		}
		elseif($level_user == 'siswa')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_siswa x', 'x.sekolah_id = c.sekolah_id');
		}
		$get = $this->db->get();
		return $get;
	}

	function get_data_row($id)
	{
		$this->db->where('materi_id', $id);
		$get = $this->db->get('mata_pelajaran_materi');
		return $get->row();
	}

	function detail($id)
	{
		$this->db->where('materi_id', $id);
		$get = $this->db->get('mata_pelajaran_materi');
		return $get->row();
	}

	function insert($data)
	{
		$this->db->insert('mata_pelajaran_materi', $data);
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
		$this->db->where('materi_id', $id);
		$this->db->update('mata_pelajaran_materi', $data);
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
		$this->db->where('materi_id', $id);
		$this->db->delete('mata_pelajaran_materi');
		return true;
	}

	function insert_kelas($param = array())
	{
		$this->delete_kelas($param[0]['materi_id']);
		$this->db->insert_batch('mata_pelajaran_materi_kelas', $param);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		return false;
	}	

	function delete_kelas($kelas_id)
	{
		$this->db->where('materi_id', $kelas_id);
		$this->db->delete('mata_pelajaran_materi_kelas');
	}

	function get_kelas($materi_id)
	{
		$query_str = "
			SELECT CONCAT(x2.jenjang, ' ', x3.nama, ' ', x2.nama) as nama, x2.kelas_id
			FROM mata_pelajaran_materi_kelas x1
			JOIN master_kelas x2 ON x1.kelas_id = x2.kelas_id
			JOIN master_jurusan x3 ON x2.jurusan_id = x3.jurusan_id
			WHERE x1.materi_id = '$materi_id'
		";
		$query = $this->db->query($query_str);
		return $query->result();
	}

}
