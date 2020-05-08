<?php

class Master_mata_pelajaran_model extends CI_Model
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

			if(!empty($param['keyword']))
			{
				$this->db->where('a.nama', $param['keyword']);
			}
		}
		$this->db->select('a.*');
		$this->db->order_by('a.nama');
		$this->db->from('master_mata_pelajaran a');
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
		$sekolah_id = $this->get_id_sekolah($id_user,$level_user);
	
		if($sekolah_id){
			$mata_pelajaran = $this->get_sekolah($sekolah_id);
			$this->db->where_in('a.mata_pelajaran_id',explode(',',$mata_pelajaran));
		}
			
		$this->db->order_by('nama');
		$this->db->from('master_mata_pelajaran a');

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
	
	function get_id_sekolah($id_user,$level_user){
		if($level_user == 'kepala sekolah')
		{
			$this->db->select('x.sekolah_id');
			$this->db->where('x.user_id', $id_user);
			$get = $this->db->get('user_kepala_sekolah x');
			return $get->row()->sekolah_id;
			
		}
		elseif($level_user == 'operator sekolah')
		{
			$this->db->select('x.sekolah_id');
			$this->db->where('x.user_id', $id_user);
			$get = $this->db->get('user_operator x');
			return $get->row()->sekolah_id;

		}
		elseif($level_user == 'guru')
		{
			$this->db->select('x.sekolah_id');
			$this->db->where('x.user_id', $id_user);
			$get = $this->db->get('user_guru x');
			return $get->row()->sekolah_id;
		}
		elseif($level_user == 'siswa')
		{
			$this->db->select('x.sekolah_id');
			$this->db->where('x.user_id', $id_user);
			$get = $this->db->get('user_siswa x');
			return $get->row()->sekolah_id;
		}
	}

	function get_sekolah($id_sekolah){
		$this->db->select('x.mata_pelajaran_id');
		$this->db->where('x.sekolah_id', $id_sekolah);
		$get = $this->db->get('profil_sekolah x');
		return $get->row()->mata_pelajaran_id;
	}
}
