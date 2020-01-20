<?php

class Notifikasi_jadwal_model extends CI_Model
{
	function get_data($param = array())
	{
		$level_user 	= $this->session->userdata('login_level');
		$id_user 	 	= $this->session->userdata('login_uid');

		$this->db->select('a.*, b.nama as nama_sekolah');
		$this->db->order_by('a.notifikasi_id', 'DESC');
		$this->db->from('notifikasi_terjadwal a');
		$this->db->join('profil_sekolah b', 'a.sekolah_id = b.sekolah_id');

		if(!empty($param))
		{
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
				$this->db->where('a.judul', $param['keyword']);
			}

			if(!empty($param['sekolah']))
			{
				$this->db->where('a.sekolah_id', $param['sekolah']);
			}			

			if(!empty($param['kelas']))
			{
				$this->db->group_by('a.notifikasi_id');
				$this->db->join('notifikasi_terjadwal_kelas d1', 'd1.notifikasi_id = a.notifikasi_id');
				$this->db->where('d1.kelas_id', $param['kelas']);
			}			

			if($level_user == 'kepala sekolah')
			{
				$this->db->where('user_kepala_sekolah.user_id', $id_user);
				$this->db->join('user_kepala_sekolah', 'user_kepala_sekolah.sekolah_id = b.sekolah_id');
			}
			elseif($level_user == 'operator sekolah')
			{
				$this->db->where('user_operator.user_id', $id_user);
				$this->db->join('user_operator', 'user_operator.sekolah_id = b.sekolah_id');
			}
			elseif($level_user == 'guru')
			{
				$this->db->where('a.user_id', $id_user);
			}

			if(!empty($param['target_siswa']))
			{
				$this->db->where('a.target_siswa', $param['target_siswa']);
			}

			if(!empty($param['target_wali']))
			{
				$this->db->where('a.target_wali', $param['target_wali']);
			}

			if(!empty($param['target_wali_kelas']))
			{
				$this->db->where('a.target_wali_kelas', $param['target_wali_kelas']);
			}

			if(!empty($param['target_guru']))
			{
				$this->db->where('a.target_guru', $param['target_guru']);
			}
		}

		$get = $this->db->get();
		return $get;
	}

	function get_data_row($id)
	{
		$this->db->where('notifikasi_id', $id);
		$get = $this->db->get('notifikasi_terjadwal');
		return $get->row();
	}

	function insert($data)
	{
		$this->db->insert('notifikasi_terjadwal', $data);
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
		$this->db->where('notifikasi_id', $id);
		$this->db->update('notifikasi_terjadwal', $data);
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
		$this->db->where('notifikasi_id', $id);
		$this->db->delete('notifikasi_terjadwal');
		return true;
	}

	function get_kelas($notifikasi_id)
	{
		$query_str = "
			SELECT CONCAT(x2.jenjang, ' ', x3.nama, ' ', x2.nama) as nama, x2.kelas_id
			FROM notifikasi_terjadwal_kelas x1
			JOIN master_kelas x2 ON x1.kelas_id = x2.kelas_id
			JOIN master_jurusan x3 ON x2.jurusan_id = x3.jurusan_id
			WHERE x1.notifikasi_id = '$notifikasi_id'
		";
		$query = $this->db->query($query_str);
		return $query->result();
	}

	function insert_kelas($param = array())
	{
		$this->delete_kelas($param[0]['notifikasi_id']);
		$this->db->insert_batch('notifikasi_terjadwal_kelas', $param);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		return false;
	}

	function insert_user($param = array())
	{
		$this->delete_user($param[0]['notifikasi_id']);
		$this->db->insert_batch('notifikasi_terjadwal_user', $param);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		return false;
	}

	function delete_kelas($notifikasi_id)
	{
		$this->db->where('notifikasi_id', $notifikasi_id);
		$this->db->delete('notifikasi_terjadwal_kelas');
	}

	function delete_user($notifikasi_id)
	{
		$this->db->where('notifikasi_id', $notifikasi_id);
		$this->db->delete('notifikasi_terjadwal_user');
	}

	function get_data_terjadwal()
	{
		$sub_query_str = "
			(
				SELECT x.notifikasi_id
				FROM notifikasi_terjadwal_log x
				WHERE DATE(x.waktu) = '" . date('Y-m-d') . "' AND 
					  x.notifikasi_id = a.notifikasi_id
			)
		";

		$this->db->select('
			a.*, 
			c.user_id,
			c.no_hp,
			c.fcm,
			d.no_hp_ortu,
			d.fcm_ortu
		');

		$this->db->where("a.notifikasi_id NOT IN $sub_query_str");
		$this->db->where('a.hari', date('w'));
		$this->db->where('a.waktu <= ', date('H:i:s'));
		$this->db->from('notifikasi_terjadwal a');
		$this->db->join('notifikasi_terjadwal_user b', 'a.notifikasi_id = b.notifikasi_id');
		$this->db->join('user c', 'c.user_id = b.user_id');
		$this->db->join('user_siswa d', 'd.user_id = c.user_id', 'left');
		$query = $this->db->get();
		return $query;
	}

	function insert_log($param = array())
	{
		$this->db->insert('notifikasi_terjadwal_log', $param);
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
