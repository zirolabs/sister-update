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
				$this->db->like('d.nama', $param['keyword']);
			}

			if(!empty($param['jenis_transaksi']))
			{
				$this->db->like('a.keterangan', $param['jenis_transaksi']);
			}

			if(!empty($param['sekolah']))
			{
				$this->db->where('c.sekolah_id', $param['sekolah']);
			}
			
			if(!empty($param['mutasi_id']))
			{
				$this->db->where('a.mutasi_id', $param['mutasi_id']);
			}

			if(!empty($param['user_id']))
			{
				$this->db->where('a.user_id', $param['user_id']);
			}

		}

		$this->db->select("
			a.*, 
			c.nama as sekolah,
			d.nama as nama_siswa
		");
		$this->db->order_by('a.waktu','desc');
		$this->db->from('keuangan_mutasi a');
		$this->db->join('user_siswa b', 'b.user_id = a.user_id');
		$this->db->join('profil_sekolah c', 'c.sekolah_id = b.sekolah_id');
		$this->db->join('user d', 'd.user_id = a.user_id');


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
		elseif($level_user == 'user kantin')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_kantin x', 'x.sekolah_id = b.sekolah_id');
		}

		$get = $this->db->get();
		return $get;
	}

	function get_laporan($param = array())
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
				$this->db->like('d.nama', $param['keyword']);
			}

			if(!empty($param['sekolah']))
			{
				$this->db->where('c.sekolah_id', $param['sekolah']);
			}

			if(!empty($param['tanggal']))
			{
				$this->db->where('DATE(a.waktu) >= ', $param['tanggal']);
				$this->db->where('DATE(a.waktu) <= ', $param['tanggal']);

			}else{
				$this->db->where('DATE(a.waktu) >= ', date('Y-m-d'));
				$this->db->where('DATE(a.waktu) <= ', date('Y-m-d'));
			}
		}

		$this->db->select(" 
		a.*, 
		e.nama as sekolah,
		c.nama as nama_produk,
		e.nama as nama_siswa,
		SUM(a.kuantitas) as total,
		");
		$this->db->from('transaksi_produk a');
		$this->db->group_by('a.produk_id');
		$this->db->join('user_siswa b', 'b.user_id = a.user_id');
		$this->db->join('master_produk c', 'c.produk_id = a.produk_id');
		$this->db->join('profil_sekolah d', 'd.sekolah_id = b.sekolah_id');
		$this->db->join('user e', 'e.user_id = a.user_id');

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
		elseif($level_user == 'user kantin')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_kantin x', 'x.sekolah_id = b.sekolah_id');
		}
		
		$get = $this->db->get();
		return $get;
	}

	function get_detail_data($param = array())
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
				$this->db->like('c.nama', $param['keyword']);
			}

			if(!empty($param['mutasi_id']))
			{
				$this->db->where('a.mutasi_id', $param['mutasi_id']);
			}

		}

		$this->db->select("
			a.*, 
			e.nama as sekolah,
			c.nama as nama_produk,
			e.nama as nama_siswa
		");
		$this->db->order_by('c.nama','asc');
		$this->db->from('transaksi_produk a');
		$this->db->join('user_siswa b', 'b.user_id = a.user_id');
		$this->db->join('master_produk c', 'c.produk_id = a.produk_id');
		$this->db->join('profil_sekolah d', 'd.sekolah_id = b.sekolah_id');
		$this->db->join('user e', 'e.user_id = a.user_id');

		$get = $this->db->get();
		return $get;
	}

	function delete($id)
	{
		$this->db->where('produk_id', $id);
		$this->db->delete('transaksi_produk');
		return true;
	}

	function insert($data)
	{
		$this->db->insert('transaksi_produk', $data);
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
