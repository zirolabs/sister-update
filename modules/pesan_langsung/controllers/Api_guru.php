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
		$this->load->model('pesan_langsung_model');
		$this->load->model('pesan_kotak/pesan_kotak_model');
		$this->load->model('manajemen_siswa/manajemen_siswa_model');
	}

	function penerima()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Data tidak ditemukan',
			'data'		=> array()
		);	

		$data_post = $this->input->post();
		if(!empty($data_post['jenis_penerima']) && !empty($data_post['keyword']))		
		{
			$result = array();
			if($data_post['jenis_penerima'] == 'siswa' || $data_post['jenis_penerima'] == 'wali siswa')
			{
				$filter = array(
					'keyword'	=> $data_post['keyword'],
					'guru'		=> $this->login_uid,
				);
				$get_data = $this->manajemen_siswa_model->get_data($filter)->result();
				foreach($get_data as $key => $c)
				{
					if($data_post['jenis_penerima'] == 'siswa')
					{
						$result[] = (object) array(
							'id'			=> $c->user_id,
							'idp'			=> $c->nis . ' - ' . $c->kelas,
							'nama'			=> $c->nama,
							'foto'			=> default_foto_user($c->foto),
							'fcm'			=> $c->fcm,
							'target'		=> $data_post['jenis_penerima'],
							'keterangan'	=> ''
						);						
					}
					else if($data_post['jenis_penerima'] == 'wali siswa')
					{
						$result[] = (object) array(
							'id'			=> $c->user_id,
							'idp'			=> $c->nis . ' - ' . $c->kelas,
							'nama'			=> $c->nama_ortu_bapak,
							'foto'			=> default_foto_user($c->foto),
							'fcm'			=> $c->fcm_ortu,
							'target'		=> $data_post['jenis_penerima'],
							'keterangan'	=> 'Wali dari ' . $c->nama
						);						
					}
				}
			}				

			if(!empty($result))
			{
				$respon = array(
					'status'	=> '200',
					'msg'		=> 'Data ditemukan',
					'data'		=> $result
				);					
			}
		}

		echo json_encode($respon);
	}

	function submit()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Pesan gagal dikirim.',
			'data'		=> array()
		);	

		$data_post = $this->input->post();
		$this->form_validation->set_rules('isi', 'Isi', 'required');
		$this->form_validation->set_rules('penerima', 'Penerima', 'required');
		if($this->form_validation->run() == false)
		{
			$respon = array(
				'status'	=> '201',
				'msg'		=> validation_errors(),
				'data'		=> array()
			);	
		}
		else
		{
			$list_penerima = json_decode($data_post['penerima']);	

			$gambar 	= '';
			if(!empty($data_post['userfiles']))
			{
				$config['upload_path']      = './uploads/pesan/';
	            if (!is_dir($config['upload_path']))
	            {
	                mkdir($config['upload_path']);
	            }

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

			$isi_notifikasi 	= substr($param_pesan['isi'], 0, 150);
			foreach($list_penerima as $key => $c)
			{
				$user_id  	= $c->id;
				$target 	= $c->target;
				$fcm 		= $c->fcm;
				if($this->pesan_kotak_model->insert($param_pesan, $this->login_uid, $user_id, $target))
				{
					$fcm = @$data_post['fcm'][$key];
					if(!empty($fcm))
					{
						if($target == 'wali siswa')
						{
							$this->fcm->insertNotifikasiWali($user_id, 'Pesan Baru', $isi_notifikasi, $fcm, 'pesan');						
							$this->sms->insertNotifikasiWali($user_id, $isi_notifikasi);
						}
						else
						{
							$this->fcm->insertNotifikasiUser($user_id, 'Pesan Baru', $isi_notifikasi, $fcm, 'pesan');
							$this->sms->insertNotifikasiUser($user_id, $isi_notifikasi);
						}						
					}
				}
			}
			$respon = array(
				'status'	=> '200',
				'msg'		=> 'Pesan berhasil dikirim',
				'data'		=> array()
			);
		}
		echo json_encode($respon);			
	}
}