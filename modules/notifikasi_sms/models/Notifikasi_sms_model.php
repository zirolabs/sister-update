<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notifikasi_sms_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();		
	}

	function get_data_perangkat()
	{
		$sub_query = "
			(
				SELECT COUNT(*) 
				FROM notifikasi_sms x1 
				WHERE x1.device_id = a.device_id AND 
					  x1.status = 'pending'
			) AS total_pending,
			(
				SELECT COUNT(*) 
				FROM notifikasi_sms x1 
				WHERE x1.device_id = a.device_id AND 
					  x1.status = 'proses'
			) AS total_proses,			
			(
				SELECT COUNT(*) 
				FROM notifikasi_sms x1 
				WHERE x1.device_id = a.device_id AND 
					  x1.status = 'terkirim'
			) AS total_terkirim,			
			(
				SELECT COUNT(*) 
				FROM notifikasi_sms x1 
				WHERE x1.device_id = a.device_id AND 
					  x1.status = 'gagal'
			) AS total_gagal
		";

		$this->db->select('a.*,' . $sub_query);
		$this->db->from('notifikasi_sms_device a');
		$query = $this->db->get();
		return $query;
	}

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

			if(!empty($param['sekolah_id']))
			{
				$this->db->where('c.sekolah_id', $param['sekolah_id']);
			}

			if(!empty($param['kelas_id']))
			{
				$this->db->where('c.kelas_id', $param['kelas_id']);
			}

			if(!empty($param['status']))
			{
				$this->db->where('a.status', $param['status']);
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
		}

		$this->db->select("
			a.*, 
			b.nama as nama_siswa, 
			c.nis as nis_siswa, 
			c.nama_ortu_bapak, 
			c.nama_ortu_ibu,
			CONCAT(d.jenjang, ' ', e.nama, ' ', d.nama) AS kelas,
			f.nama as sekolah
		");
		$this->db->order_by('a.sms_id', 'DESC');
		$this->db->from('notifikasi_sms a');
		$this->db->join('user b', 'a.user_id = b.user_id');
		$this->db->join('user_siswa c', 'b.user_id = c.user_id');
		$this->db->join('master_kelas d', 'c.kelas_id = d.kelas_id');
		$this->db->join('master_jurusan e', 'd.jurusan_id = e.jurusan_id');
		$this->db->join('profil_sekolah f', 'c.sekolah_id = f.sekolah_id');

		if($level_user == 'kepala sekolah')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_kepala_sekolah x', 'x.sekolah_id = c.sekolah_id');
		}
		elseif($level_user == 'operator sekolah')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_operator x', 'x.sekolah_id = c.sekolah_id');
		}


		$get = $this->db->get();
		return $get;
	}	

	public function simpan_device($param = array())
	{
		$this->db->where('device_id', $param['device_id']);
		$this->db->from('notifikasi_sms_device');
		$cek = $this->db->get()->row();
		if(!empty($cek))
		{
			$this->update_device($param, $cek->device_id);
		}
		else
		{
			$this->db->insert('notifikasi_sms_device', $param);
		}

		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function update_device($param = array(), $id)
	{
		$this->db->where('device_id', $id);
		$this->db->update('notifikasi_sms_device', $param);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function delete_device($id)
	{
		$this->db->where('device_id', $id);
		$this->db->delete('notifikasi_sms_device');		
		return true;
	}
}

/* End of file Notifikasi_fcm_model.php */
/* Location: ./application/models/Notifikasi_fcm_model.php */