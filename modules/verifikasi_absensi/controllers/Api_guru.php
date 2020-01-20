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
		$this->load->model('verifikasi_absensi_model');
	}

	public function index()
	{
		$data_post = $this->input->post();
		$param  = array(
			'guru'		=> $this->login_uid,
			'sekolah'	=> $data_post['sekolah_id'],
			'tanggal'	=> $data_post['tanggal']
		);
		$data	= $this->verifikasi_absensi_model->get_data_belum_absen($param)->result();
		if(!empty($data))
		{
			$result = array();
			foreach($data as $key => $c)
			{
				$result[] = array(
					'user_id'		=> $c->user_id,
					'nama'			=> $c->nama,
					'kelas'			=> $c->nama_kelas,
					'kelas_id'		=> $c->kelas_id,
					'sekolah'		=> $c->nama_sekolah,
					'sekolah_id'	=> $c->sekolah_id,
					'nis'			=> $c->nis,
					'foto'			=> default_foto_user($c->foto)
				);
			}

			$respon = array(
				'status'	=> '200',
				'msg'		=> 'Data siswa ditemukan.',
				'data'		=> $result
			);	
		}
		else
		{
			$respon = array(
				'status'	=> '201',
				'msg'		=> 'Data siswa belum absen tidak ditemukan.',
				'data'		=> array()
			);	
		}
		echo json_encode($respon);
	}

	public function jenis_absen()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Data tidak ditemukan',
			'data'		=> array()
		);	

		$data = $this->verifikasi_absensi_model->get_jenis_absen();
		if(!empty($data))
		{
			$result = array();
			foreach($data as $key => $c)
			{
				$result[] = array(
					'value' => $key,
					'label' => $c
				);
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
		$data_post = $this->input->post();

		if(empty($data_post))
		{
			show_404();
		}

		$sukses_insert 		= array();

		$input_status 	  	= $data_post['status'];
		$input_keterangan 	= $data_post['keterangan'];
		$input_tanggal 	  	= $data_post['tanggal'];
		if(!empty($data_post['waktu']))
		{
			$input_tanggal .= ' ' . $data_post['waktu'];
		}

		$msg = '';
		$data_siswa = json_decode($data_post['siswa_id']);
		foreach($data_siswa as $key => $c)
		{
			$input_nis		  = $c->nis; 
			$input_nama		  = $c->nama; 
			$input_sekolah 	  = $c->sekolah_id;
			$input_kelas	  = $c->kelas_id; 

			$input_sesi_id 	 = 0;
			$input_jam_id	 = 0;
			$input_telat 	 = 0;

			if($input_status == 'hadir')
			{
				$verifikasi_telat 	= $this->verifikasi_absensi_model->verifikasi_jam_masuk($input_tanggal, $input_sekolah);
				if(empty($verifikasi_telat['sesi_id']))
				{
					$msg .= $input_nis . ' ' . $input_nama . " : Waktu Masuk tidak valid. \n";
					continue;
				}

				$input_sesi_id   = $verifikasi_telat['sesi_id'];
				$input_jam_id    = $verifikasi_telat['jam_id'];
				$input_telat 	 = $verifikasi_telat['telat'];
			}

			$param = array(
				'user_id'		=> $c->user_id,
				'kelas_id'		=> $input_kelas,
				'jam_id'		=> $input_jam_id,
				'sesi_id'		=> $input_sesi_id,
				'status'		=> $input_status,
				'waktu'			=> $input_tanggal,
				'telat'			=> $input_telat,
				'keterangan'	=> $input_keterangan				
			);

			$proses = $this->verifikasi_absensi_model->insert_absensi($param);
			if($proses)
			{
				$sukses_insert[] = $c->user_id;
			}
			else
			{
				$msg .= $input_nis . ' ' . $input_nama . " : Verifikasi gagal, silahkan ulangi lagi. \n";
			}
		}

		if(!empty($msg))
		{
			$respon = array(
				'status'	=> '201',
				'msg'		=> $msg,
				'data'		=> $sukses_insert
			);
		}
		else
		{
			$respon = array(
				'status'	=> '200',
				'msg'		=> $msg,
				'data'		=> $sukses_insert
			);			
		}

		echo json_encode($respon);
	}
}

?>