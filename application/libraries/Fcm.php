<?php

class Fcm
{	
	public function insertDB($param = array())
	{
		$CI =& get_instance();
		$param['waktu']	= date('Y-m-d H:i:s');
		$CI->db->insert('notifikasi_fcm', $param);
		if($CI->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function updateDB($param = array(), $id)
	{
		$CI =& get_instance();
		$CI->db->where('fcm_id', $id);
		$CI->db->update('notifikasi_fcm', $param);
		if($CI->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function getAntrianDB()
	{
		$CI =& get_instance();
		$CI->db->where('status', 'pending');
		$CI->db->from('notifikasi_fcm');
		$query = $CI->db->get();
		return $query;
	}

	public function insertNotifikasiWali($user_id = '', $judul = '', $pesan = '', $fcm_token = '', $method = '')
	{
		$CI 	=& get_instance();

		if(empty($fcm_token))
		{
			if(empty($user_id))
			{
				return '';
			}

			$CI->db->select('a.*, b.*');
			$CI->db->where('a.user_id', $user_id);
			$CI->db->from('user a');
			$CI->db->join('user_siswa b', 'a.user_id = b.user_id');
			$query 	= $CI->db->get();
			$result = $query->row();

			if(empty($result))
			{
				return '';
			}

			if(empty($result->fcm_ortu))
			{
				return '';
			}			

			$fcm_token	= $result->fcm_ortu;
			$judul 		= str_replace('{nama_siswa}', $result->nama, $judul);
			$pesan 		= str_replace('{nama_siswa}', $result->nama, $pesan);
		}

		$param = array(
			'user_id'		=> $user_id,
			'judul'			=> $judul,
			'pesan'			=> $pesan,
			'target'		=> $fcm_token,
			'status'		=> 'pending',
			'method'		=> $method,
			'target_user'	=> 'wali'
		);
		return $this->insertDB($param);
	}

	public function insertNotifikasiUser($user_id = '', $judul = '', $pesan = '', $fcm_token = '', $method = '')
	{
		$CI 	=& get_instance();
		if(empty($fcm_token))
		{
			if(empty($user_id))
			{
				return '';
			}

			$CI->db->select('a.*');
			$CI->db->where('a.user_id', $user_id);
			$CI->db->from('user a');
			$query 	= $CI->db->get();
			$result = $query->row();

			if(empty($result))
			{
				return '';
			}

			if(empty($result->fcm))
			{
				return '';
			}

			$fcm_token = $result->fcm;
			$judul = str_replace('{nama_siswa}', $result->nama, $judul);
			$pesan = str_replace('{nama_siswa}', $result->nama, $pesan);			
		}

		$param = array(
			'user_id'		=> $user_id,
			'judul'			=> $judul,
			'pesan'			=> $pesan,
			'target'		=> $fcm_token,
			'status'		=> 'pending',
			'method'		=> $method,
			'target_user'	=> 'user'
		);
		return $this->insertDB($param);
	}

	public function sendMessage($data, $target)
	{
		$CI 	=& get_instance();
		$url 	= 'https://fcm.googleapis.com/fcm/send';
		$fields = $data;
		if(is_array($target))
		{
			$fields['registration_ids'] = $target;
		}
		else
		{
			$fields['to'] = $target;
		}

		$headers = array(
			'Content-Type:application/json',
	  		'Authorization:key=' . $CI->config->item('google_fcm')
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);
		if ($result === FALSE) 
		{
			$result = 'FCM Send Error: ' . curl_error($ch);
		}
		curl_close($ch);

		return $result;
	}
}
	