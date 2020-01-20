<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_guru extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->device_id 		= $this->input->post('device_id');
		if(empty($this->device_id))
		{
			$this->device_id = $this->input->get('device_id');
		}

		$this->load->model('login/login_model');
		$this->cek_device = $this->login_model->get_data_guru_device($this->device_id);
		if(empty($this->cek_device))
		{
			$respon = array(
				'status'	=> '201',
				'msg'		=> 'Gagal',
				'data'		=> 'Autentikasi Gagal.'
			);	
			echo json_encode($respon);
			exit;
		}

		$this->login_uid	= $this->cek_device->user_id;		
		$this->load->model('pesan_kotak_model');
	}

	public function index()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Data tidak ditemukan.',
			'data'		=> array()
		);	

		$filter	  = array('user_id' => $this->login_uid);
		$get_data = $this->pesan_kotak_model->get_data($filter)->result();
		if(!empty($get_data))
		{
			$result = array();
			foreach($get_data as $key => $c)
			{
				if($c->user_id_1 != $this->login_uid)
				{
					$c->foto_user   = default_foto_user($c->foto_user_1);
					$c->nama_user 	= $c->nama_user_1;
					$c->email_user 	= $c->email_user_1;
					$c->no_hp_user 	= $c->no_hp_user_1;
					$c->fcm_user 	= $c->fcm_user_1;
					$c->user_id 	= $c->user_id_1;
					$c->target 		= format_ucwords($c->target);
				}
				else
				{
					$c->foto_user   = default_foto_user($c->foto_user_2);					
					$c->nama_user 	= $c->nama_user_2;
					$c->email_user 	= $c->email_user_2;
					$c->no_hp_user 	= $c->no_hp_user_2;
					$c->fcm_user 	= $c->fcm_user_2;
					$c->user_id 	= $c->user_id_2;
					$c->target 		= format_ucwords($c->target);
				}

				// if($c->target == 'Wali Siswa')
				// {
				// 	$c->nama_user = 
				// }

				$c->waktu_terakhir  = waktu_berlalu($c->waktu_terakhir, true);
				$c->pesan_terakhir  = strlen($c->pesan_terakhir) >= 150 ? substr($c->pesan_terakhir, 0, 150) . ' ...' : $c->pesan_terakhir;
				
				unset($c->user_id_1);
				// unset($c->user_id_2);
				unset($c->foto_user_1);
				unset($c->foto_user_2);
				unset($c->nama_user_1);
				unset($c->nama_user_2);
				unset($c->email_user_1);
				unset($c->email_user_2);
				unset($c->no_hp_user_1);
				unset($c->no_hp_user_2);
				unset($c->fcm_user_1);
				unset($c->fcm_user_2);
				$result[] = $c;
			}
			$respon = array(
				'status'	=> '200',
				'msg'		=> 'Data ditemukan',
				'data'		=> $result
			);				
		}
		echo json_encode($respon);
	}

	public function detail()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Data tidak ditemukan.',
			'data'		=> array()
		);	
		$data_post = $this->input->post();
		if(!empty($data_post['pesan_id']))
		{
			$get_detail	= $this->pesan_kotak_model->get_detail($data_post['pesan_id'])->result();

			$result = array();
			foreach($get_detail as $key => $c)
			{
				if(!empty($c->gambar))
				{
					$c->gambar = base_url($c->gambar);
				}
				$c->waktu_kirim = waktu_berlalu($c->waktu_kirim, true);
				$result[] 		= $c;
			}

			$respon = array(
				'status'	=> '200',
				'msg'		=> 'Data ditemukan',
				'data'		=> $result
			);				
		}
		echo json_encode($respon);
	}

	public function submit()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Pesan gagal dikirim.',
			'data'		=> array()
		);	

		$data_post = $this->input->post();
		$penerima_id 	= $data_post['penerima'];
		$target 		= $data_post['target'];
		$fcm 			= $data_post['fcm'];

		if(empty($data_post['isi']) && empty($data_post['userfiles']))
		{
			$respon = array(
				'status'	=> '200',
				'msg'		=> 'Tidak ada data yang dikirim.',
				'data'		=> array()
			);	
			echo json_encode($respon);
			exit;
		}

		$gambar 			   = '';
		$config['upload_path'] = './uploads/pesan/';
        if (!is_dir($config['upload_path']))
        {
            mkdir($config['upload_path']);
        }

		if(!empty($data_post['userfiles']))
		{
			$file_name = $this->login_uid . date('YmdHis');
			$create_berkas = create_image($file_name, $data_post['userfiles'], $config['upload_path']);
			if(!empty($create_berkas))
			{
				$gambar = $config['upload_path'] . $file_name . '.jpg';
			}				
		}

		$param_pesan = array(
			'user_id'		=> $this->login_uid,
			'isi'			=> $data_post['isi'],
			'gambar'		=> $gambar,
			'waktu_kirim'	=> date('Y-m-d H:i:s')
		);

		if($this->pesan_kotak_model->insert($param_pesan, $this->login_uid, $penerima_id, $target))
		{
			$respon = array(
				'status'	=> '200',
				'msg'		=> 'Pesan berhasil dikirim',
				'data'		=> array(
					'detail_id'		=> $this->db->insert_id(),
					'gambar'		=> !empty($gambar) ? base_url($gambar) : '',
					'isi'			=> $data_post['isi'],
					'waktu_kirim'	=> format_tanggal_indonesia($param_pesan['waktu_kirim'], true)
				)
			);	

			if(!empty($fcm))
			{
				$isi_notifikasi = substr($param_pesan['isi'], 0, 150);
				if($target == 'wali siswa')
				{
					$this->fcm->insertNotifikasiWali($penerima_id, 'Pesan Baru', $isi_notifikasi, $fcm, 'pesan');						
					$this->sms->insertNotifikasiWali($penerima_id, $isi_notifikasi);
				}
				else
				{
					$this->fcm->insertNotifikasiUser($penerima_id, 'Pesan Baru', $isi_notifikasi, $fcm, 'pesan');
					$this->sms->insertNotifikasiUser($penerima_id, $isi_notifikasi);
				}						
			}
		}
		echo json_encode($respon);
	}

	function hapus()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Pesan gagal dihapus.',
			'data'		=> array()
		);	

		$data_post = $this->input->post();
		if(!empty($data_post['pesan_id']))
		{
			$proses = $this->pesan_kotak_model->delete($data_post['pesan_id'], $this->login_uid);
			if($proses)
			{
				$respon = array(
					'status'	=> '200',
					'msg'		=> 'Pesan berhasil dihapus.',
					'data'		=> array()
				);				
			}			
		}

		echo json_encode($respon);
	}
}

?>