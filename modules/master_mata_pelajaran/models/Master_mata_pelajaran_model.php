<?php

class Master_mata_pelajaran_model extends CI_Model
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

			if(!empty($param['sekolah_id']))
			{
				$this->db->where('a.sekolah_id', $param['sekolah_id']);
			}
			
		}
		$this->db->select("a.*,b.sekolah_id,b.nama as nama_sekolah");
		$this->db->order_by('a.nama');
		$this->db->from('master_mata_pelajaran a');
		$this->db->join('profil_sekolah b', 'b.sekolah_id = a.sekolah_id');
		if($level_user == 'kepala sekolah')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_kepala_sekolah x', 'x.sekolah_id = b.sekolah_id');
		}
		elseif($level_user == 'operator sekolah')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_operator x', 'x.sekolah_id = b.sekolah_id');
		}
		elseif($level_user == 'guru')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_guru x', 'x.sekolah_id = b.sekolah_id');			
		}
		elseif($level_user == 'siswa')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_guru x', 'x.sekolah_id = b.sekolah_id');			
		}

		$get = $this->db->get();
		return $get;
	}

	function get_data_row($id)
	{
		$this->db->where('mata_pelajaran_id', $id);
		$get = $this->db->get('master_mata_pelajaran');
		return $get->row();
	}

	function insert($data)
	{
		$this->db->insert('master_mata_pelajaran', $data);
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
		$this->db->where('mata_pelajaran_id', $id);
		$this->db->update('master_mata_pelajaran', $data);
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
		$this->db->where('mata_pelajaran_id', $id);
		$this->db->delete('master_mata_pelajaran');
		return true;
	}

	function get_opt($add_on = '')
	{
		$level_user 	= $this->session->userdata('login_level');
		$id_user 	 	= $this->session->userdata('login_uid');
		$this->db->select("a.*,b.sekolah_id,b.nama as nama_sekolah");
		$this->db->order_by('a.nama');
		$this->db->from('master_mata_pelajaran a');
		$this->db->join('profil_sekolah b', 'b.sekolah_id = a.sekolah_id');
		if($level_user == 'kepala sekolah')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_kepala_sekolah x', 'x.sekolah_id = b.sekolah_id');
		}
		elseif($level_user == 'operator sekolah')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_operator x', 'x.sekolah_id = b.sekolah_id');
		}
		elseif($level_user == 'guru')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_guru x', 'x.sekolah_id = b.sekolah_id');			
		}
		elseif($level_user == 'siswa')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_guru x', 'x.sekolah_id = b.sekolah_id');			
		}
		$query = $this->db->get();

		$result = array();
		if(!empty($add_on))
		{
			$result[''] = $add_on;
		}

		foreach($query->result() as $key => $c)
		{
			$result[$c->mata_pelajaran_id] = $c->nama;
		}
		return $result;
	}
	
}
