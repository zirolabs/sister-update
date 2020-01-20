<?php

class Pengaturan_kelas_model extends CI_Model
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
			}

			if(!empty($param['keyword_wali_kelas']))
			{
				$this->db->like('d.nama', $param['keyword_wali_kelas']);
			}

			if(!empty($param['sekolah_id']))
			{
				$this->db->where('a.sekolah_id', $param['sekolah_id']);
			}

			if(!empty($param['kelas_id']))
			{
				$this->db->where('a.kelas_id', $param['kelas_id']);
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

		$this->db->select('
			a.*, 
			b.nama as nama_jurusan, 
			c.nama as nama_sekolah,
			d.nama as nama_wali_kelas,
			d.foto as foto_wali_kelas,
			d.email as email_wali_kelas,
			d.no_hp as no_hp_wali_kelas,
			d.fcm
		');
		$this->db->order_by('b.nama, a.jenjang, a.nama');
		$this->db->from('master_kelas a');
		$this->db->join('master_jurusan b', 'a.jurusan_id = b.jurusan_id');
		$this->db->join('profil_sekolah c', 'c.sekolah_id = a.sekolah_id');
		$this->db->join('user d', 'd.user_id = a.user_id', 'left');

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
			$this->db->where('d.user_id', $id_user);
		}

		$get = $this->db->get();
		return $get;
	}

	function get_data_row($id)
	{
		$this->db->where('kelas_id', $id);
		$get = $this->db->get('master_kelas');
		return $get->row();
	}

	function insert($data)
	{
		$this->db->insert('master_kelas', $data);
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
		$this->db->where('kelas_id', $id);
		$this->db->update('master_kelas', $data);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function update_by_guru($data, $id)
	{
		$this->db->where('user_id', $id);
		$this->db->update('master_kelas', $data);
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
		$this->db->where('kelas_id', $id);
		$this->db->delete('master_kelas');
		return true;
	}

	function get_opt($addon = '', $sekolah_id = '', $param = array())
	{
		$result 		= array();
		$level_user 	= $this->session->userdata('login_level');
		$id_user 	 	= $this->session->userdata('login_uid');
		if(!empty($param))
		{
			if(!empty($param['guru']))
			{
				$level_user = 'guru';
				$id_user 	= $param['guru'];
			}
			if(!empty($param['siswa']))
			{
				$level_user = 'siswa';
				$id_user 	= $param['siswa'];
			}
		}

		if($level_user == 'guru')
		{
			$this->db->where('a.user_id', $id_user);
		}
		elseif($level_user == 'siswa')
		{
			$this->db->where('c.user_id', $id_user);
			$this->db->join('user_siswa c', 'c.kelas_id = a.kelas_id');
		}
		else
		{
			if(!empty($addon))
			{
				$result['']	= $addon;
			}
		}

		if(!empty($sekolah_id))
		{
			$this->db->where('a.sekolah_id', $sekolah_id);
		}

		$this->db->select('a.*, b.nama as nama_jurusan');
		$this->db->order_by('a.jenjang asc,b.nama asc, a.nama asc');
		$this->db->from('master_kelas a');
		$this->db->join('master_jurusan b', 'a.jurusan_id = b.jurusan_id');
		$query = $this->db->get();

		foreach($query->result() as $key => $c)
		{
			$result[$c->kelas_id] = $c->jenjang . ' ' . $c->nama_jurusan . ' ' . $c->nama;
		}
		return $result;
	}	
}
