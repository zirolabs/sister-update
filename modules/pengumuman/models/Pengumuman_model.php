<?php

class Pengumuman_model extends CI_Model
{
	function get_data($param = array())
	{
		$level_user 	= $this->session->userdata('login_level');
		$id_user 	 	= $this->session->userdata('login_uid');

		$this->db->select('a.*, b.nama as nama_sekolah, c.nama as nama_user, c.email as email_user');
		$this->db->order_by('a.pengumuman_id', 'DESC');
		$this->db->from('pengumuman a');
		$this->db->join('profil_sekolah b', 'a.sekolah_id = b.sekolah_id');
		$this->db->join('user c', 'a.user_id = c.user_id');

		if(!empty($param))
		{
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
				$this->db->where('a.judul', $param['keyword']);
			}

			if(!empty($param['sekolah']))
			{
				$this->db->where('a.sekolah_id', $param['sekolah']);
			}			

			if(!empty($param['kelas']))
			{
				$this->db->group_by('a.pengumuman_id');
				$this->db->join('pengumuman_kelas_target d1', 'd1.pengumuman_id = a.pengumuman_id');
				$this->db->where('d1.kelas_id', $param['kelas']);
			}			

			if(empty($param['saya']))
			{
				if($level_user == 'kepala sekolah')
				{
					$this->db->where('user_kepala_sekolah.user_id', $id_user);
					$this->db->join('user_kepala_sekolah', 'user_kepala_sekolah.sekolah_id = b.sekolah_id');
				}
				elseif($level_user == 'operator sekolah')
				{
					$this->db->where('user_operator.user_id', $id_user);
					$this->db->join('user_operator', 'user_operator.sekolah_id = b.sekolah_id');
				}
				elseif($level_user == 'guru')
				{
					$this->db->where('a.user_id', $id_user);
				}
			}
			else
			{
				$this->db->where('d.user_id', $param['saya']);
				$this->db->join('pengumuman_user_target d', 'd.pengumuman_id = a.pengumuman_id');
			}

			if(!empty($param['target_siswa']))
			{
				$this->db->where('a.target_siswa', $param['target_siswa']);
			}

			if(!empty($param['target_wali']))
			{
				$this->db->where('a.target_wali', $param['target_wali']);
			}

			if(!empty($param['target_wali_kelas']))
			{
				$this->db->where('a.target_wali_kelas', $param['target_wali_kelas']);
			}

			if(!empty($param['target_guru']))
			{
				$this->db->where('a.target_guru', $param['target_guru']);
			}

		}

		$get = $this->db->get();
		return $get;
	}

	function get_data_row($id)
	{
		$this->db->where('pengumuman_id', $id);
		$get = $this->db->get('pengumuman');
		return $get->row();
	}

	function insert($data)
	{
		$this->db->insert('pengumuman', $data);
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
		$this->db->where('pengumuman_id', $id);
		$this->db->update('pengumuman', $data);
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
		$this->db->where('pengumuman_id', $id);
		$this->db->delete('pengumuman');
		return true;
	}

	function get_opt()
	{
		$this->db->order_by('nama');
		$this->db->from('pengumuman');
		$query = $this->db->get();

		$result = array();
		foreach($query->result() as $key => $c)
		{
			$result[$c->pengumuman_id] = $c->nama;
		}
		return $result;
	}	

	function get_kelas($pengumuman_id)
	{
		$query_str = "
			SELECT CONCAT(x2.jenjang, ' ', x3.nama, ' ', x2.nama) as nama, x2.kelas_id
			FROM pengumuman_kelas_target x1
			JOIN master_kelas x2 ON x1.kelas_id = x2.kelas_id
			JOIN master_jurusan x3 ON x2.jurusan_id = x3.jurusan_id
			WHERE x1.pengumuman_id = '$pengumuman_id'
		";
		$query = $this->db->query($query_str);
		return $query->result();
	}

	function insert_kelas($param = array())
	{
		$this->delete_kelas($param[0]['pengumuman_id']);
		$this->db->insert_batch('pengumuman_kelas_target', $param);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		return false;
	}

	function insert_user($param = array())
	{
		$this->delete_user($param[0]['pengumuman_id']);
		$this->db->insert_batch('pengumuman_user_target', $param);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		return false;
	}

	function delete_kelas($pengumuman_id)
	{
		$this->db->where('pengumuman_id', $pengumuman_id);
		$this->db->delete('pengumuman_kelas_target');
	}

	function delete_user($pengumuman_id)
	{
		$this->db->where('pengumuman_id', $pengumuman_id);
		$this->db->delete('pengumuman_user_target');
	}

}
