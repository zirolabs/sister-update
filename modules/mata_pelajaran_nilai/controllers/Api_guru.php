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
		$this->load->model('mata_pelajaran_nilai_model');
		$this->load->model('manajemen_siswa/manajemen_siswa_model');
	}

	public function index()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Data tidak ditemukan.',
			'data'		=> array()
		);	

		$data_post = $this->input->post();
		if(!empty($data_post['sekolah']) || !empty($data_post['kelas']) || !empty($data_post['semester']) || !empty($data_post['jenis']) || !empty($data_post['mata_pelajaran']))
		{
			$get_data = $this->mata_pelajaran_nilai_model->get_data_siswa($data_post)->result();
			if(!empty($get_data))
			{
				$result = array();
				foreach($get_data as $key => $c)
				{
					$c->foto  = default_foto_user($c->foto);
					$result[] = $c;
				}
				$respon = array(
					'status'	=> '200',
					'msg'		=> 'Data ditemukan.',
					'data'		=> $result
				);					
			}
		}
		else
		{
			$respon = array(
				'status'	=> '201',
				'msg'		=> 'Parameter tidak valid.',
				'data'		=> array()
			);				
		}

		echo json_encode($respon);
	}

	public function submit($id = '')
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Data gagal disimpan, silahkan ulangi lagi.',
			'data'		=> array()
		);	

		$data_post = $this->input->post();

		$param_input = array(
			'mata_pelajaran_id'	=> $data_post['mata_pelajaran'],
			'sekolah_id'		=> $data_post['sekolah'],
			'kelas_id'			=> $data_post['kelas'],
			'user_id'			=> $this->login_uid,
			'semester_id'		=> $data_post['semester'],
			'jenis'				=> $data_post['jenis'],
			'keterangan'		=> $data_post['keterangan']
		);

		$id 	= @$data_post['id'];
		$proses = false;
		if(empty($id))
		{
			$proses = $this->mata_pelajaran_nilai_model->insert($param_input);		
			if($proses)
			{
				$id = $this->db->insert_id();
			}
		}
		else
		{
			$proses = $this->mata_pelajaran_nilai_model->update($param_input, $id);		
		}

		if($proses)
		{
			$respon = array(
				'status'	=> '200',
				'msg'		=> 'Data berhasil disimpan.',
				'data'		=> array()
			);				
		}

		if(!empty($id))
		{
			$nilai 		 = json_decode($data_post['nilai']);
			$param_nilai = array();
			foreach($nilai as $key => $c)
			{
				$param_nilai[] = array(
					'nilai_id'	=> $id,
					'user_id'	=> $c->user_id,
					'nilai'		=> empty($c->nilai) ? 0 : $c->nilai
				);
				$this->mata_pelajaran_nilai_model->insert_nilai($param_nilai);
			}
		}

		echo json_encode($respon);
	}

	public function laporan()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Data tidak ditemukan.',
			'data'		=> array()
		);	

		$data_post = $this->input->post();
		$data_post['guru_id']  = $this->login_uid;
		if(!empty($data_post['sekolah']) || !empty($data_post['kelas']) || !empty($data_post['semester']) || !empty($data_post['jenis']) || !empty($data_post['mata_pelajaran']))
		{
			$get_data 	= $this->mata_pelajaran_nilai_model->get_data($data_post)->result();
			if(!empty($get_data))
			{
				$list_nilai	= array();
				$nilai 		= array();
				$user 		= array();
				$data		= array();
				$index_user = -1;
				foreach($get_data as $key => $c)
				{
					if(!in_array($c->nilai_id, $list_nilai))					
					{
						$list_nilai[]  = $c->nilai_id;
						$nilai[] = array(
							'label'			=> format_ucwords($data_post['jenis']) . ' ke ' . $c->keterangan,
							'value'			=> $c->nilai_id,
							'keterangan'	=> $c->keterangan
						);
					}

					if(!in_array($c->user_id, $user))					
					{
						$user[] = $c->user_id;
						$index_user++;
					}

					$data[$index_user]['nis']  = $c->nis_siswa;		
					$data[$index_user]['nama'] = $c->nama_siswa;		
					$data[$index_user]['detail'][$c->nilai_id] = $c->nilai;		

				}

				usort($nilai, function($a, $b) {
				    return $a['keterangan'] - $b['keterangan'];
				});			

				$siswa 		= array();
				foreach ($list_nilai as $key => $c) 
				{
					foreach($data as $kex => $x)
					{
						if(empty($siswa[$kex]))
						{
							$siswa[$kex] = $x;
							$siswa[$kex]['detail'] = array();							
						}

						if(empty($x['detail'][$c]))
						{
							$siswa[$kex]['detail'][] = array(
								'label'	=> 0,
								'value'	=> $c
							);
						}
						else
						{
							$siswa[$kex]['detail'][] = array(
								'label'	=> $x['detail'][$c],
								'value'	=> $c
							);							
						}
					}
				}

				$respon = array(
					'status'	=> '200',
					'msg'		=> 'Data ditemukan.',
					'data'		=> array(
						'tugas'	=> $nilai,
						'siswa'	=> $siswa
					)
				);					
			}
		}
		else
		{
			$respon = array(
				'status'	=> '201',
				'msg'		=> 'Parameter tidak valid.',
				'data'		=> array()
			);				
		}

		echo json_encode($respon);		
	}

	public function hapus()
	{
		$respon = array(
			'status'	=> '201',
			'msg'		=> 'Data gagal dihapus.',
			'data'		=> array()
		);							

		$data_post = $this->input->post();
		if(!empty($data_post['nilai_id']))
		{
			$this->mata_pelajaran_nilai_model->delete_nilai($data_post['nilai_id']);
			$this->mata_pelajaran_nilai_model->delete($data_post['nilai_id']);

			$respon = array(
				'status'	=> '200',
				'msg'		=> 'Data berhasil dihapus.',
				'data'		=> array()
			);							
		}

		echo json_encode($respon);		
	}
}
