<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Finger_sync_model extends CI_Model 
{
	function get_user($nis, $sekolah_id)
	{
		$this->db->where('sekolah_id', $sekolah_id);
		$this->db->where('nis', $nis);
		$this->db->from('user_siswa');
		$query = $this->db->get();
		return $query;
	}
}
