<?php

class Profil_model extends CI_Model
{
	function get_data_row($id)
	{
		$this->db->select('a.*');
		$this->db->where('a.user_id', $id);
		$this->db->from('user a');
		$get = $this->db->get();
		return $get->row();
	}

	function update($data, $id)
	{
		$this->db->where('user_id', $id);
		$this->db->update('user', $data);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function get_data_siswa_row($id)
	{
		$this->db->select("
			a.password_ortu, 
			a.siswa_id, 
			a.nis, 
			a.alamat,
			a.nama_ortu_bapak,
			a.nama_ortu_ibu,
			a.no_hp_ortu,
			a.alamat_ortu,
			b.*, 
			CONCAT(c.jenjang, ' ', d.nama, ' ', c.nama) as kelas,
			e.nama as sekolah
		");
		$this->db->where('a.user_id', $id);
		$this->db->from('user_siswa a');
		$this->db->join('user b', 'a.user_id = b.user_id');
		$this->db->join('master_kelas c', 'c.kelas_id = a.kelas_id');
		$this->db->join('master_jurusan d', 'd.jurusan_id = c.jurusan_id');
		$this->db->join('profil_sekolah e', 'e.sekolah_id = a.sekolah_id');
		$query = $this->db->get();
		return $query->row();		
	}

	function get_data_guru_row($id)
	{
		$this->db->select("a.guru_id, a.nip, b.*");
		$this->db->where('b.user_id', $id);
		$this->db->from('user_guru a');
		$this->db->join('user b', 'a.user_id = b.user_id');
		$query = $this->db->get();
		return $query->row();						
	}

	function update_siswa($data, $id)
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
}
