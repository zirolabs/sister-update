<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rfid_sync_model extends CI_Model 
{
	function get_user($nis, $sekolah_id)
	{
		$this->db->select("
			a.nis, 
			a.alamat, 
			a.sn_rfid,
			b.nama,
			CONCAT(c.jenjang, ' ', d.nama, ' ', c.nama) as kelas,
			e.nama as sekolah			
		");
		$this->db->where('a.sekolah_id', $sekolah_id);
		$this->db->where('a.nis', $nis);
		$this->db->from('user_siswa a');
		$this->db->join('user b', 'a.user_id = b.user_id');
		$this->db->join('master_kelas c', 'c.kelas_id = a.kelas_id');
		$this->db->join('master_jurusan d', 'd.jurusan_id = c.jurusan_id');
		$this->db->join('profil_sekolah e', 'e.sekolah_id = a.sekolah_id');
		$query = $this->db->get();
		return $query;
	}

	function get_rfid($rfid, $sekolah_id)
	{
		$this->db->where('sekolah_id', $sekolah_id);
		$this->db->where('sn_rfid', $rfid);
		$this->db->from('user_siswa');
		$query = $this->db->get();
		return $query;
	}

	function update_rfid($nis, $sekolah_id, $rfid)
	{
		$this->db->where('nis', $nis);
		$this->db->where('sekolah_id', $sekolah_id);
		$this->db->update('user_siswa', array('sn_rfid' => $rfid));
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
