<?php

class Manajemen_siswa_model extends CI_Model
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
				$this->db->like('a.nama', $param['keyword']);
				$this->db->or_like('b.nis', $param['keyword']);
			}

			if(!empty($param['user_id']))
			{
				$this->db->where('a.user_id', $param['user_id']);
			}

			if(!empty($param['sekolah']))
			{
				$this->db->where('b.sekolah_id', $param['sekolah']);
			}

			if(!empty($param['nis']))
			{
				$this->db->where('b.nis', $param['nis']);
			}

			if(!empty($param['kelas']))
			{
				$this->db->where('b.kelas_id', $param['kelas']);
			}

			if(!empty($param['kepala sekolah']))
			{
				$level_user = 'kepala sekolah';
				$id_user 	= $param['kepala sekolah'];
			}

			if(!empty($param['operator sekolah']))
			{
				$level_user = 'operator sekolah';
				$id_user 	= $param['operator sekolah'];
			}

			if(!empty($param['guru']))
			{
				$level_user = 'guru';
				$id_user 	= $param['guru'];
			}			
		}

		$this->db->select("
			a.*, 
			b.*, 
			CONCAT(c.jenjang, ' ', d.nama, ' ', c.nama) AS kelas,
			e.nama as sekolah
		");
		$this->db->order_by('a.nama');
		$this->db->from('user a');
		$this->db->join('user_siswa b', 'b.user_id = a.user_id');
		$this->db->join('master_kelas c', 'b.kelas_id = c.kelas_id');
		$this->db->join('master_jurusan d', 'c.jurusan_id = d.jurusan_id');
		$this->db->join('profil_sekolah e', 'e.sekolah_id = b.sekolah_id');

		if($level_user == 'kepala sekolah')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_kepala_sekolah x', 'x.sekolah_id = e.sekolah_id');
		}
		elseif($level_user == 'operator sekolah')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_operator x', 'x.sekolah_id = c.sekolah_id');
		}
		elseif($level_user == 'guru')
		{
			$this->db->where('c.user_id', $id_user);
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_guru x', 'x.sekolah_id = e.sekolah_id');			
		}

		$get = $this->db->get();
		return $get;
	}

	function get_data_row($id)
	{
		$this->db->where('a.user_id', $id);
		$this->db->from('user_siswa a');
		$this->db->join('user b', 'b.user_id = a.user_id');
		$get = $this->db->get();
		return $get->row();
	}

	function insert($data)
	{
		$this->db->insert('user_siswa', $data);
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
		$this->db->where('user_id', $id);
		$this->db->update('user_siswa', $data);
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
		$this->db->where('user_id', $id);
		$this->db->delete('user_siswa');
		return true;
	}

	function cek_nis($nis, $sekolah_id)
	{
		$this->db->where('sekolah_id', $sekolah_id);
		$this->db->where('nis', $nis);
		$this->db->from('user_siswa');
		$query = $this->db->get();
		$result = $query->row();

		if(!empty($result))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}
