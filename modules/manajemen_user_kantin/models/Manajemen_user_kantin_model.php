<?php

class Manajemen_user_kantin_model extends CI_Model
{
	// menampilkan semua wali kelas berdasakan filter 
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
			}

			if(!empty($param['sekolah']))
			{
				$this->db->where('b.sekolah_id', $param['sekolah']);
			}

			if(!empty($param['kelas']))
			{
				$this->db->where('c.kelas_id', $param['kelas']);
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
		$this->db->join('user_kantin b', 'b.user_id = a.user_id');
		$this->db->join('master_kelas c', 'c.user_id = a.user_id', 'left');
		$this->db->join('master_jurusan d', 'c.jurusan_id = d.jurusan_id', 'left');
		$this->db->join('profil_sekolah e', 'b.sekolah_id = e.sekolah_id');

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
		elseif($level_user == 'user kantin')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_kantin x', 'x.sekolah_id = c.sekolah_id');
		}

		$get = $this->db->get();
		return $get;
	}

	// menampilkan semua kantin berdasakan filter
	function get_data_user_kantin($param = array())
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
			}

			if(!empty($param['sekolah']))
			{
				$this->db->where('b.sekolah_id', $param['sekolah']);
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
			c.nama as sekolah
		");
		$this->db->order_by('a.nama');
		$this->db->from('user a');
		$this->db->join('user_kantin b', 'b.user_id = a.user_id');
		$this->db->join('profil_sekolah c', 'b.sekolah_id = c.sekolah_id');

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
		elseif($level_user == 'user kantin')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_kantin x', 'x.sekolah_id = c.sekolah_id');
		}

		$get = $this->db->get();
		return $get;
	}

	// Modal untuk mendapatkan data semua user dengan level kantinberdasarkan sekolah_id
	function get_data_all($param = array())
	{	
		$this->db->select("
			a.*, 
			b.*
		");
		$this->db->order_by('a.nama');
		$this->db->from('user a');
		$this->db->where('b.sekolah_id', $param['sekolah_id']);
		$this->db->join('user_kantin b', 'b.user_id = a.user_id');

		$get = $this->db->get();
		return $get;
	}

	function get_data_row($id)
	{
		$this->db->select('a.*, b.*, c.kelas_id');
		$this->db->where('a.user_id', $id);
		$this->db->from('user_kantin a');
		$this->db->join('user b', 'b.user_id = a.user_id');
		$this->db->join('master_kelas c', 'b.user_id = c.user_id', 'left');
		$get = $this->db->get();
		return $get->row();
	}

	function insert($data)
	{
		$this->db->insert('user_kantin', $data);
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
		$this->db->update('user_kantin', $data);
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
		$this->db->delete('user_kantin');
		return true;
	}

	function check_nip($nip)
	{
		$this->db->where('nip', $nip);
		$this->db->from('user_kantin');
		$query = $this->db->get();
		if(!empty($query->row()))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}
