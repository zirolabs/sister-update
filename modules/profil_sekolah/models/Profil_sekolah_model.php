<?php

class Profil_sekolah_model extends CI_Model
{
	function get_data($param = array())
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

			if(!empty($param['sekolah_id']))
			{
				$this->db->where('a.sekolah_id', $param['sekolah_id']);
			}			

			if(!empty($param['keyword']))
			{
				$this->db->like('a.nama', $param['keyword']);
			}

			if(!empty($param['keyword_kepala_sekolah']))
			{
				$this->db->like('c.nama', $param['keyword_kepala_sekolah']);
			}
		}
		$this->db->select('
			a.*, 
			b.nip as kepsek_nip, 
			c.user_id,
			c.foto as kepsek_foto,
			c.nama as kepsek_nama,
			c.email as kepsek_email,
			c.no_hp as kepsek_no_hp,
			c.fcm as kepsek_fcm
		');
		$this->db->order_by('a.nama');
		$this->db->from('profil_sekolah a');
		$this->db->join('user_kepala_sekolah b', 'a.sekolah_id = b.sekolah_id');
		$this->db->join('user c', 'c.user_id = b.user_id');
		$get = $this->db->get();
		return $get;
	}

	function get_data_row($id)
	{
		$this->db->select('
			a.*,
			b.user_id,
			b.nama as user_nama,
			b.email as user_email,
			b.fcm as user_fcm,
			c.nip as user_nip
		');
		$this->db->where('a.sekolah_id', $id);
		$this->db->from('profil_sekolah a');
		$this->db->join('user_kepala_sekolah c', 'c.sekolah_id = a.sekolah_id');
		$this->db->join('user b', 'b.user_id = c.user_id');
		$query = $this->db->get();
		return $query->row();
	}

	function insert($data)
	{
		$this->db->insert('profil_sekolah', $data);
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
		$this->db->where('sekolah_id', $id);
		$this->db->update('profil_sekolah', $data);
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
		$this->db->where('sekolah_id', $id);
		$this->db->delete('profil_sekolah');
		return true;
	}

	function get_data_kantin($param = array())
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

			if(!empty($param['sekolah_id']))
			{
				$this->db->where('a.sekolah_id', $param['sekolah_id']);
			}			

			if(!empty($param['keyword']))
			{
				$this->db->like('a.nama', $param['keyword']);
			}

			if(!empty($param['keyword_kepala_sekolah']))
			{
				$this->db->like('c.nama', $param['keyword_kepala_sekolah']);
			}
		}
		$this->db->select('
			a.*, 
			b.nip as kepsek_nip, 
			c.user_id,
			c.foto as kepsek_foto,
			c.nama as kepsek_nama,
			c.email as kepsek_email,
			c.no_hp as kepsek_no_hp,
			c.fcm as kepsek_fcm
		');
		$this->db->order_by('a.nama');
		$this->db->from('profil_sekolah a');
		$this->db->join('user_kepala_sekolah b', 'a.sekolah_id = b.sekolah_id');
		$this->db->join('user c', 'c.user_id = b.user_id');

		if($level_user == 'kepala sekolah')
		{
			$this->db->where('user_kepala_sekolah.user_id', $id_user);
			$this->db->join('user_kepala_sekolah', 'user_kepala_sekolah.sekolah_id = a.sekolah_id');
		}
		elseif($level_user == 'operator sekolah')
		{
			$this->db->where('user_operator.user_id', $id_user);
			$this->db->join('user_operator', 'user_operator.sekolah_id = a.sekolah_id');
		}
		elseif($level_user == 'guru')
		{
			$this->db->where('user_guru.user_id', $id_user);
			$this->db->join('user_guru', 'user_guru.sekolah_id = a.sekolah_id');			
		}
		elseif($level_user == 'user kantin')
		{
			$this->db->where('user_kantin.user_id', $id_user);
			$this->db->join('user_kantin', 'user_kantin.sekolah_id = a.sekolah_id');			
		}
		else
		{
			if(!empty($addon))
			{
				$result['']	= $addon;
			}
		}

		$get = $this->db->get();
		return $get;
	}

	function get_opt($addon = '', $param = array())
	{
		$result 		= array();

		$level_user 	= $this->session->userdata('login_level');
		$id_user 	 	= $this->session->userdata('login_uid');

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

			if(!empty($param['user_kantin']))
			{
				$level_user = 'user_kantin';
				$id_user 	= $param['user_kantin'];
			}

			if(!empty($param['siswa']))
			{
				$level_user = 'siswa';
				$id_user 	= $param['siswa'];
			}
		}

		if($level_user == 'kepala sekolah')
		{
			$this->db->where('user_kepala_sekolah.user_id', $id_user);
			$this->db->join('user_kepala_sekolah', 'user_kepala_sekolah.sekolah_id = profil_sekolah.sekolah_id');
		}
		elseif($level_user == 'operator sekolah')
		{
			$this->db->where('user_operator.user_id', $id_user);
			$this->db->join('user_operator', 'user_operator.sekolah_id = profil_sekolah.sekolah_id');
		}
		elseif($level_user == 'guru')
		{
			$this->db->where('user_guru.user_id', $id_user);
			$this->db->join('user_guru', 'user_guru.sekolah_id = profil_sekolah.sekolah_id');			
		}
		elseif($level_user == 'user kantin')
		{
			$this->db->where('user_kantin.user_id', $id_user);
			$this->db->join('user_kantin', 'user_kantin.sekolah_id = profil_sekolah.sekolah_id');			
		}
		elseif($level_user == 'siswa')
		{
			$this->db->where('user_siswa.user_id', $id_user);
			$this->db->join('user_siswa', 'user_siswa.sekolah_id = profil_sekolah.sekolah_id');			
		}
		else
		{
			if(!empty($addon))
			{
				$result['']	= $addon;
			}
		}

		$this->db->order_by('nama','asc');
		$this->db->from('profil_sekolah');
		$query = $this->db->get();

		foreach($query->result() as $key => $c)
		{
			$result[$c->sekolah_id] = $c->nama;
		}
		return $result;
	}	

	function insert_kepsek($param = array())
	{
		$this->db->insert('user_kepala_sekolah', $param);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function update_kepsek($param = array(), $sekolah_id)
	{
		$this->db->where('sekolah_id', $sekolah_id);
		$this->db->update('user_kepala_sekolah', $param);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	function update_user_kepsek($param = array(), $sekolah_id)
	{
		$sub_query = "
			(
				SELECT user_id
			 	FROM user_kepala_sekolah
			 	WHERE sekolah_id = '$sekolah_id'
			)
		";

		$this->db->where("user_id IN $sub_query");
		$this->db->update('user', $param);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
}
