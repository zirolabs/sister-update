<?php

class Pesan_kotak_model extends CI_Model
{
	function get_data($param = array())
	{
		$sub_query = '';
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

			if(!empty($param['user_id']))
			{
				$this->db->where("(a.user_id_1 = '$param[user_id]' OR a.user_id_2 = '$param[user_id]')");
			}

			if(!empty($param['pesan_id']))
			{
				$this->db->where('a.pesan_id', $param['pesan_id']);
			}

			if(!empty($param['target']))
			{
				$this->db->where('a.target', $param['target']);
			}
		}

		$this->db->select('
			a.*,
			b1.nama as nama_user_1,
			b1.email as email_user_1,
			b1.no_hp as no_hp_user_1,
			b1.foto as foto_user_1,		
			b1.fcm as fcm_user_1,	
			b1.level as level_user_1,
			b2.nama as nama_user_2,
			b2.email as email_user_2,
			b2.no_hp as no_hp_user_2,
			b2.foto as foto_user_2,
			b2.fcm as fcm_user_2,
			b2.level as level_user_2,
		' . $sub_query);
		$this->db->order_by('a.waktu_terakhir', 'DESC');
		$this->db->from('pesan a');
		$this->db->join('user b1', 'a.user_id_1 = b1.user_id');
		$this->db->join('user b2', 'a.user_id_2 = b2.user_id');
		$get = $this->db->get();
		return $get;
	}

	function get_detail($id)
	{
		$this->db->where('pesan_id', $id);
		$this->db->from('pesan_detail');
		$query = $this->db->get();
		return $query;
	}

	function insert($param_pesan = array(), $user_id_pengirim, $user_id_penerima, $level_target)
	{
		if($level_target == 'wali kelas')
		{
			$level_target = 'guru';
		}
		
		$this->db->where("target", $level_target);
		$this->db->where("
			(
				(user_id_1 = '$user_id_pengirim' AND user_id_2 = '$user_id_penerima') OR
				(user_id_1 = '$user_id_penerima' AND user_id_2 = '$user_id_pengirim')
			)
		");
		$this->db->from('pesan');
		$check = $this->db->get()->row();

		if(!empty($check))
		{
			$pesan_id = $check->pesan_id;
			$param_update_pesan = array(
				'pesan_terakhir'	=> $param_pesan['isi'],
				'waktu_terakhir'	=> $param_pesan['waktu_kirim']
			);
			$this->db->where('pesan_id', $pesan_id);
			$this->db->update('pesan', $param_update_pesan);
		}
		else
		{
			$param_insert_pesan = array(
				'user_id_1'			=> $user_id_pengirim,
				'user_id_2'			=> $user_id_penerima,
				'target'			=> $level_target,
				'pesan_terakhir'	=> $param_pesan['isi'],
				'waktu_terakhir'	=> $param_pesan['waktu_kirim']
			);
			$this->db->insert('pesan', $param_insert_pesan);
			if($this->db->affected_rows() > 0)
			{
				$pesan_id = $this->db->insert_id();
			}
		}

		if(empty($pesan_id))
		{
			return false;
		}

		$param_pesan['pesan_id'] = $pesan_id;
		$param_pesan['user_id']  = $user_id_pengirim;
		$this->db->insert('pesan_detail', $param_pesan);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function delete($id, $user_id)
	{
		$this->db->where('pesan_id', $id);
		$this->db->delete('pesan');

		$this->db->where('user_id', $user_id);
		$this->db->where('pesan_id', $id);
		$this->db->delete('pesan_detail');

		return true;
	}
}
