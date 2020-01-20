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
		$this->load->model('pesan_broadcast_model');
		$this->load->model('pesan_kotak/pesan_kotak_model');
		$this->load->model('manajemen_siswa/manajemen_siswa_model');
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
		$this->form_validation->set_rules('kelas', 'Kelas', 'required');
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
			$list_kelas = json_decode($data_post['kelas']);		
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

			if(@$data_post['target_siswa'] == 'true') $data_post['target_siswa'] = 'Y';
			if(@$data_post['target_wali'] == 'true') $data_post['target_wali'] = 'Y';

			$param_pesan = array(
				'user_id'		=> $this->login_uid,
				'isi'			=> $data_post['isi'],
				'gambar'		=> $gambar,
				'waktu_kirim'	=> date('Y-m-d H:i:s')
			);

			$isi_notifikasi = substr($param_pesan['isi'], 0, 150);
			foreach($list_kelas as $key => $c)
			{
				if(@$data_post['target_siswa'] == 'Y' || @$data_post['target_wali'] == 'Y')
				{
					$filter = array('kelas' => $c->id); 
					$get_data_siswa = $this->manajemen_siswa_model->get_data($filter)->result();
					foreach($get_data_siswa as $kex => $x)
					{
						if(@$data_post['target_siswa'] == 'Y')
						{
							if($this->pesan_kotak_model->insert($param_pesan, $this->login_uid, $x->user_id, 'siswa'))
							{
								if(!empty($x->fcm))
								{
									$this->fcm->insertNotifikasiUser($x->user_id, 'Pesan Broadcast untuk Siswa', $isi_notifikasi, $x->fcm, 'pesan');
								}
								else
								{
									$this->sms->insertNotifikasiUser($get_data_wali_kelas->user_id, $isi_notifikasi);								
								}
							}
						}

						if(@$data_post['target_wali'] == 'Y')
						{
							if($this->pesan_kotak_model->insert($param_pesan, $this->login_uid, $x->user_id, 'wali siswa'))
							{
								if(!empty($x->fcm_ortu))
								{
									$this->fcm->insertNotifikasiWali($x->user_id, 'Pesan Broadcast untuk Wali Murid', $isi_notifikasi, $x->fcm_ortu, 'pesan');
								}								
								else
								{
									$this->sms->insertNotifikasiWali($x->user_id, $isi_notifikasi);
								}
							}
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