<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();		
	}

	function get_login($email)
	{
		$this->db->select('
			a.*,
			b.guru_id,
			c.kepsek_id,
			d.siswa_id
		');
		// untuk menonaktifkan user siswa
		// $this->db->where('a.level != ', 'siswa');

		$this->db->where("(a.username LIKE '%$email%' OR a.email LIKE '%$email%')");
		$this->db->from('user a');
		$this->db->join('user_guru b', 'a.user_id = b.user_id', 'left');
		$this->db->join('user_kepala_sekolah c', 'a.user_id = c.user_id', 'left');
		$this->db->join('user_siswa d', 'a.user_id = d.user_id', 'left');
		$query = $this->db->get();

		if(empty($query->row()))
		{
			return $this->get_login_guru($email);
		}
		return $query;
	}

	function update_last_login($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->update('user', array('terakhir_login' => date('Y-m-d H:i:s')));
	}	

	function update_login($param = array(), $user_id)
	{
		$this->db->where('user_id', $user_id);
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

	function get_login_siswa($nis, $sekolah)
	{
		$this->db->select("
			a.password_ortu, 
			a.siswa_id, 
			a.nis, 
			b.*, 
			CONCAT(c.jenjang, ' ', d.nama, ' ', c.nama) as kelas
		");
		$this->db->where('a.sekolah_id', $sekolah);
		$this->db->where('a.nis', $nis);
		$this->db->from('user_siswa a');
		$this->db->join('user b', 'a.user_id = b.user_id');
		$this->db->join('master_kelas c', 'c.kelas_id = a.kelas_id');
		$this->db->join('master_jurusan d', 'd.jurusan_id = c.jurusan_id');
		$query = $this->db->get();
		return $query;		
	}

	function get_login_guru($nip, $sekolah = '')
	{
		if(!empty($sekolah))
		{
			$this->db->where('a.sekolah_id', $sekolah);
		}
		$this->db->where('a.nip', $nip);
		$this->db->select("a.guru_id, a.nip, b.*");
		$this->db->from('user_guru a');
		$this->db->join('user b', 'a.user_id = b.user_id');
		$query = $this->db->get();
		return $query;				
	}

	function update_login_siswa($param = array(), $user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->update('user_siswa', $param);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function get_data_wali_device($device_id)
	{
		$this->db->select("
			a.password_ortu, 
			a.siswa_id, 
			a.nis, 
			a.sn_rfid,
			b.*, 
			CONCAT(c.jenjang, ' ', d.nama, ' ', c.nama) as kelas
		");
		$this->db->where('a.device_id_ortu', $device_id);
		$this->db->from('user_siswa a');
		$this->db->join('user b', 'a.user_id = b.user_id');
		$this->db->join('master_kelas c', 'c.kelas_id = a.kelas_id');
		$this->db->join('master_jurusan d', 'd.jurusan_id = c.jurusan_id');
		$get = $this->db->get();
		return $get->row();
	}

	function get_data_guru_device($device_id)
	{
		$this->db->select("a.guru_id, a.nip, b.*, a.sekolah_id");
		$this->db->where('b.device_id', $device_id);
		$this->db->from('user_guru a');
		$this->db->join('user b', 'a.user_id = b.user_id');
		$query = $this->db->get();
		return $query->row();				
	}

	function get_data_siswa_device($device_id)
	{
		$this->db->select("
			a.siswa_id, 
			a.nis, 
			a.sn_rfid,
			b.*, 
			c.kelas_id,
			CONCAT(c.jenjang, ' ', d.nama, ' ', c.nama) as kelas
		");
		$this->db->where('b.device_id', $device_id);
		$this->db->from('user_siswa a');
		$this->db->join('user b', 'a.user_id = b.user_id');
		$this->db->join('master_kelas c', 'c.kelas_id = a.kelas_id');
		$this->db->join('master_jurusan d', 'd.jurusan_id = c.jurusan_id');
		$get = $this->db->get();
		return $get->row();
	}	
}

/* End of file Login_model.php */