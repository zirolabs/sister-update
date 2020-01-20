<?php

require './vendor/autoload.php';
use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;

class Sms
{	
	public function __construct()
	{
		$this->CI 	=& get_instance();		
	}

	public function insertDB($param = array())
	{
		$param['waktu']		= date('Y-m-d H:i:s');
		$param['status']	= 'pending';
		$param['device_id']	= $this->getDeviceQueue();
		if(empty($param['device_id']))
		{
			return false;
		}

		$this->CI->db->insert('notifikasi_sms', $param);
		if($this->CI->db->affected_rows() > 0)
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
		$this->CI->db->where('sms_id', $id);
		$this->CI->db->update('notifikasi_sms', $param);
		if($this->CI->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function getDB($status = '')
	{
		$sub_query = '';
		if($status == 'pending')
		{
			$sub_query .= ',';
			$sub_query .= '
				(b.maks_per_jam - (
					SELECT COUNT(*) 
					FROM notifikasi_sms x 
					WHERE 
						  x.device_id = b.device_id AND 
						  HOUR(x.waktu_proses) <= ' . date('H') . ')
				) AS sisa_sms
			';

			$this->CI->db->where('HOUR(a.waktu) <= ', date('H'));
		}

		$this->CI->db->where('a.status', $status);
		$this->CI->db->select('a.*' . $sub_query);
		$this->CI->db->from('notifikasi_sms a');
		$this->CI->db->join('notifikasi_sms_device b', 'a.device_id = b.device_id');
		$query = $this->CI->db->get();
		return $query;
	}

	public function insertNotifikasiWali($user_id = '', $pesan = '')
	{
		$this->CI->db->select('a.*, b.*, c.nama as nama_sekolah');
		$this->CI->db->where('a.user_id', $user_id);
		$this->CI->db->from('user a');
		$this->CI->db->join('user_siswa b', 'a.user_id = b.user_id');
		$this->CI->db->join('profil_sekolah	c', 'c.sekolah_id = b.sekolah_id');
		$query 	= $this->CI->db->get();
		$result = $query->row();

		if(empty($result))
		{
			return '';
		}

		if(!empty($result->fcm_ortu))
		{
			return '';
		}

		$result->no_hp_ortu = trim($result->no_hp_ortu);
		if(empty($result->no_hp_ortu))
		{
			return '';
		}

		$pesan 	= str_replace('{nama_siswa}', $result->nama, $pesan);
		$pesan 	= $result->nama_sekolah . ' : ' . $pesan;

		$param = array(
			'pesan'			=> $pesan,
			'user_id'		=> $user_id,
			'target'		=> $result->no_hp_ortu,
			'target_user'	=> 'wali'
		);

		return $this->insertDB($param);
	}

	public function insertNotifikasiUser($user_id = '', $pesan = '')
	{
		$this->CI->db->select('a.*');
		$this->CI->db->where('a.user_id', $user_id);
		$this->CI->db->from('user a');
		$query 	= $this->CI->db->get();
		$result = $query->row();

		if(empty($result))
		{
			return '';
		}

		if(!empty($result->fcm))
		{
			return '';
		}

		$result->no_hp = trim($result->no_hp);
		if(empty($result->no_hp))
		{
			return '';
		}

		$sekolah = '';
		if($result->level == 'siswa')
		{
			$this->CI->db->select('b.nama as nama_sekolah');
			$this->CI->db->where('a.user_id', $user_id);
			$this->CI->db->from('user_siswa a');
			$this->CI->db->join('profil_sekolah b', 'a.sekolah_id = b.sekolah_id');
			$get_sekolah = $this->CI->db->get()->row();
			if(empty($get_sekolah))
			{
				return '';
			}

			$sekolah = $get_sekolah->nama_sekolah;
		}		
		elseif($result->level == 'guru')
		{
			$this->CI->db->select('b.nama as nama_sekolah');
			$this->CI->db->where('a.user_id', $user_id);
			$this->CI->db->from('user_guru a');
			$this->CI->db->join('profil_sekolah b', 'a.sekolah_id = b.sekolah_id');
			$get_sekolah = $this->CI->db->get()->row();
			if(empty($get_sekolah))
			{
				return '';
			}

			$sekolah = $get_sekolah->nama_sekolah;			
		}		
		elseif($result->level == 'kepala sekolah')
		{
			$this->CI->db->select('b.nama as nama_sekolah');
			$this->CI->db->where('a.user_id', $user_id);
			$this->CI->db->from('user_kepala_sekolah a');
			$this->CI->db->join('profil_sekolah b', 'a.sekolah_id = b.sekolah_id');
			$get_sekolah = $this->CI->db->get()->row();
			if(empty($get_sekolah))
			{
				return '';
			}

			$sekolah = $get_sekolah->nama_sekolah;				
		}		
		elseif($result->level == 'operator sekolah')
		{
			$this->CI->db->select('b.nama as nama_sekolah');
			$this->CI->db->where('a.user_id', $user_id);
			$this->CI->db->from('user_operator a');
			$this->CI->db->join('profil_sekolah b', 'a.sekolah_id = b.sekolah_id');
			$get_sekolah = $this->CI->db->get()->row();
			if(empty($get_sekolah))
			{
				return '';
			}

			$sekolah = $get_sekolah->nama_sekolah;					
		}		

		$fcm_token = $result->fcm;
		$pesan = str_replace('{nama_siswa}', $result->nama, $pesan);	
		$pesan = $sekolah . ' : ' . $pesan;
		$param = array(
			'pesan'			=> $pesan,
			'user_id'		=> $user_id,
			'target'		=> $result->no_hp,
			'target_user'	=> 'user'
		);

		return $this->insertDB($param);
	}

	public function sendMessage($param = array())
	{
		$config = Configuration::getDefaultConfiguration();
		$config->setApiKey('Authorization', $this->CI->config->item('sms_gateway_key'));

		$api_instance = new SMSGatewayMe\Client\Api\MessageApi();

		$sendMessageRequest = array();
		foreach($param as $key => $c)
		{
			$sendMessageRequest[] = new SMSGatewayMe\Client\Model\SendMessageRequest([
			    'phoneNumber' 	=> $c['phoneNumber'],
			    'message' 		=> $c['message'],
			    'deviceId' 		=> $c['deviceId']
			]);			
		}

		$result = array(
			'status'	=> 'gagal',
			'msg'		=> 'tidak diketahui',
			'data'		=> array()
		);
		
		try 
		{ 
			$data_sms  = array();
		    $post_data = $api_instance->sendMessages($sendMessageRequest);
		    foreach($post_data as $key => $c)
		    {
		    	$c = json_decode($c);
		    	$data_sms_temp = array(
		    		'sms_id_api'	=> $c->id,
		    		'status'		=> $c->status,
		    	);

		    	if(!empty($param[$key]['id']))
		    	{
		    		$data_sms_temp['id'] = $param[$key]['id'];
		    	}

		    	$data_sms[] = $data_sms_temp;
		    }

		    $result = array(
				'status'	=> 'sukses',
				'msg'		=> 'kirim sms sukses',
				'data'		=> $data_sms		    	
		    );
		} 
		catch (Exception $e) 
		{
			$result = array(
				'status'	=> 'gagal',
				'msg'		=> $e->getMessage(),
				'data'		=> array()
			);			
		}	
		return $result;	
	}

	public function getMessageInfo($id)
	{
		$config = Configuration::getDefaultConfiguration();
		$config->setApiKey('Authorization', $this->CI->config->item('sms_gateway_key'));

		$api_instance = new SMSGatewayMe\Client\Api\MessageApi();

		$result = array(
			'status'	=> 'gagal',
			'msg'		=> 'tidak diketahui',
			'data'		=> array()
		);
		try 
		{
		    $get_data = json_decode($api_instance->getMessage($id));
		    $result = array(
		    	'status'	=> 'sukses',
		    	'msg'		=> 'data ditemukan',
		    	'data'		=> array(
		    		'status'	=> $get_data->status
		    	)
		    );
		} 
		catch (Exception $e) 
		{
			$result = array(
				'status'	=> 'gagal',
				'msg'		=> $e->getMessage(),
				'data'		=> array()
			);			
		}
		return $result;
	}	

	public function getDevice()
	{
		$config = Configuration::getDefaultConfiguration();
		$config->setApiKey('Authorization', $this->CI->config->item('sms_gateway_key'));

		$api_instance = new SMSGatewayMe\Client\Api\DeviceApi();
		$search = new \SMSGatewayMe\Client\Model\Search();

		$result = array(
			'status'	=> 'gagal',
			'msg'		=> 'tidak diketahui',
			'data'		=> array()
		);

		try 
		{
		    $get_data = json_decode($api_instance->searchDevices($search));
		    if($get_data->count > 0)
		    {
		    	$data = array();
		    	foreach($get_data->results as $key => $c)
		    	{	
		    		$data[] = array(
		    			'device_id'	=> $c->id,
		    			'name'		=> $c->name,
		    		);
		    	}
			    $result = array(
			    	'status'	=> 'sukses',
			    	'msg'		=> 'perangkat ditemukan',
			    	'data'		=> $data
			    );
		    }
		} 
		catch (Exception $e) 
		{
			$result = array(
				'status'	=> 'gagal',
				'msg'		=> $e->getMessage(),
				'data'		=> array()
			);			
		}
		return $result;
	}

	public function getDeviceQueue()
	{
		$query_str = "
			SELECT a.device_id, COUNT(*) as total_sms
			FROM notifikasi_sms_device a 
			LEFT JOIN notifikasi_sms b ON a.device_id = b.device_id
			WHERE a.aktif = 'Y'
			GROUP BY a.device_id
			ORDER BY total_sms ASC
			LIMIT 1
		";

		$query = $this->CI->db->query($query_str);
		return $query->row()->device_id;
	}
}
	