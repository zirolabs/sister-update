<?php

class Produk_kantin_model extends CI_Model
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
			b.nama as sekolah
		");
		$this->db->order_by('a.nama');
		$this->db->from('master_produk a');
		$this->db->join('profil_sekolah b', 'b.sekolah_id = a.sekolah_id');

		if($level_user == 'kepala sekolah')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_kepala_sekolah x', 'x.sekolah_id = e.sekolah_id');
		}
		elseif($level_user == 'operator sekolah')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_operator x', 'x.sekolah_id = b.sekolah_id');
		}

		$get = $this->db->get();
		return $get;
	}



	function get_data_row($id)
	{
		$this->db->select('a.*, b.nama as sekolah');
		$this->db->where('a.produk_id', $id);
		$this->db->from('master_produk a');
		$this->db->join('profil_sekolah b', 'b.sekolah_id = a.sekolah_id');
		$get = $this->db->get();
		return $get->row();
	}

	function insert($data)
	{
		$this->db->insert('master_produk', $data);
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
		$this->db->where('produk_id', $id);
		$this->db->update('master_produk', $data);
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
		$this->db->where('produk_id', $id);
		$this->db->delete('master_produk');
		return true;
	}

}
