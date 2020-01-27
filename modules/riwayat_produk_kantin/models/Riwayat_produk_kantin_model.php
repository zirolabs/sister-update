<?php

class Riwayat_produk_kantin_model extends CI_Model
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

		}

		$this->db->select("
			a.*, 
			b.nama as sekolah,
			c.nama as nama_produk
		");
		$this->db->order_by('c.nama');
		$this->db->from('transaksi_produk a');
		$this->db->join('profil_sekolah b', 'b.sekolah_id = a.sekolah_id');
		$this->db->join('master_produk c', 'c.produk_id = a.produk_id');

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

	function delete($id)
	{
		$this->db->where('produk_id', $id);
		$this->db->delete('transaksi_produk');
		return true;
	}

}
