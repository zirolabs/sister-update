<?php

class Verifikasi_absensi_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->check_dispensasi();
	}

	function get_jenis_absen()
	{
		$status = array(
			'hadir'		=> 'Hadir',
			'pulang'	=> 'Pulang',
			'sakit'		=> 'Sakit',
			'ijin'		=> 'Ijin',
			'bolos'		=> 'Bolos',	
		);

		return $status;
	}

	function get_data_absen($param = array())
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
			
			if(!empty($param['tanggal']))
			{
				$this->db->where('DATE(a.waktu)', $param['tanggal']);
			}

			if(!empty($param['absensi_id']))
			{
				$this->db->where('a.absensi_id', $param['absensi_id']);
			}

			if(!empty($param['kelas']))
			{
				$this->db->where('a.kelas_id', $param['kelas']);				
			}

			if(!empty($param['sekolah']))
			{
				$this->db->where('c.sekolah_id', $param['sekolah']);				
			}

			if(!empty($param['guru']))
			{
				$level_user = 'guru';
				$id_user 	= $param['guru'];
			}
		}

		$this->db->select("
			a.*, 			
			b.nama, 
			c.nis,
			CONCAT(d.jenjang, ' ', e.nama, ' ', d.nama) AS nama_kelas,
			f.nama as nama_sesi,
			CONCAT(g.masuk, ' - ', g.pulang) AS jam_sesi,
			h.nama as nama_sekolah
		");
		$this->db->order_by('a.waktu', 'DESC');
		$this->db->from('absensi a')	;
		$this->db->join('user b', 'b.user_id = a.user_id');
		$this->db->join('user_siswa c', 'c.user_id = b.user_id');
		$this->db->join('master_kelas d', 'd.kelas_id = a.kelas_id');
		$this->db->join('master_jurusan e', 'e.jurusan_id = d.jurusan_id');
		$this->db->join('master_sesi f', 'f.sesi_id = a.sesi_id', 'left');
		$this->db->join('master_sesi_jam g', 'g.jam_id = a.jam_id', 'left');
		$this->db->join('profil_sekolah h', 'h.sekolah_id = c.sekolah_id');

		if($level_user == 'kepala sekolah')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_kepala_sekolah x', 'x.sekolah_id = h.sekolah_id');
		}
		elseif($level_user == 'operator sekolah')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_operator x', 'x.sekolah_id = h.sekolah_id');
		}
		elseif($level_user == 'guru')
		{
			$this->db->where('d.user_id', $id_user);
			$this->db->where('x1.user_id', $id_user);
			$this->db->join('user_guru x1', 'x1.sekolah_id = h.sekolah_id');			
		}
				
		$get = $this->db->get();
		return $get;
	}

	function get_data_belum_absen($param = array())
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

			if(!empty($param['tanggal']))
			{
				$query_where = "
					SELECT x.user_id
					FROM absensi x
					WHERE DATE(x.waktu) = '$param[tanggal]'
				";
				$this->db->where("a.user_id NOT IN ($query_where)");
			}

			if(!empty($param['kelas']))
			{
				$this->db->where('b.kelas_id', $param['kelas']);				
			}

			if(!empty($param['sekolah']))
			{
				$this->db->where('b.sekolah_id', $param['sekolah']);				
			}

			if(!empty($param['user_id']))
			{
				if(is_array($param['user_id']))
				{
					$this->db->where_in('a.user_id', $param['user_id']);
				}
				else
				{
					$this->db->where('a.user_id', $param['user_id']);
				}
			}

			if(!empty($param['guru']))
			{
				$level_user = 'guru';
				$id_user 	= $param['guru'];
			}
		}

		$this->db->order_by('a.nama, b.nis');
		$this->db->select("
			a.*, 
			b.nis,
			b.sekolah_id,
			c.kelas_id,
			CONCAT(c.jenjang, ' ', d.nama, ' ', c.nama) AS nama_kelas,
			e.nama as nama_sekolah,
			f.waktu as telat_waktu,
			f.total as telat_total
		");		
		$this->db->from('user a');
		$this->db->join('user_siswa b', 'b.user_id = a.user_id');
		$this->db->join('master_kelas c', 'c.kelas_id = b.kelas_id');
		$this->db->join('master_jurusan d', 'd.jurusan_id = c.jurusan_id');
		$this->db->join('profil_sekolah e', 'e.sekolah_id = b.sekolah_id');
		$this->db->join('absensi_telat f', "f.user_id = a.user_id AND DATE(f.waktu) = '$param[tanggal]'", 'left');

		if($level_user == 'kepala sekolah')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_kepala_sekolah x', 'x.sekolah_id = e.sekolah_id');
		}
		elseif($level_user == 'operator sekolah')
		{
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_operator x', 'x.sekolah_id = e.sekolah_id');
		}
		elseif($level_user == 'guru')
		{
			$this->db->where('c.user_id', $id_user);
			$this->db->where('x1.user_id', $id_user);
			$this->db->join('user_guru x1', 'x1.sekolah_id = e.sekolah_id');			
		}

		$get = $this->db->get();
		return $get;
	}

	function verifikasi_jam_masuk($waktu, $sekolah_id)
	{
		$respon = array(
			'jam_id'	=> '',
			'sesi_id'	=> '',
			'telat'		=> 0,
			'status'	=> 201
		);

		$jam = date('H:i:s', strtotime($waktu));

		// $this->db->where('TIME(masuk) <= ', $jam);
		$this->db->where('sekolah_id', $sekolah_id);
		$this->db->where('TIME(pulang) > ', $jam);
		$this->db->from('master_sesi_jam');
		$query = $this->db->get();
		$result_sesi = $query->row();

		if(!empty($result_sesi))
		{
			$get_telat = 0;
			if(strtotime($jam) > strtotime($result_sesi->masuk))
			{
				$waktu_mulai = new DateTime($result_sesi->masuk);
				$waktu_telat = $waktu_mulai->diff(new DateTime($waktu));
				$get_telat	 = $waktu_telat->i;
				if($waktu_telat->h > 0)
				{
					$get_telat += $waktu_telat->h * 60;
				}
			}

			if(strtotime($jam) <= strtotime($result_sesi->toleransi_telat))
			{
				$respon = array(
					'jam_id'	=> $result_sesi->jam_id,
					'sesi_id'	=> $result_sesi->sesi_id,
					'telat'		=> $get_telat,
					'status'	=> 200
				);
			}
			else
			{
				$respon = array(
					'jam_id'	=> $result_sesi->jam_id,
					'sesi_id'	=> $result_sesi->sesi_id,
					'telat'		=> $get_telat,
					'status'	=> 199
				);				
			}
		}

		return $respon;
	}

	function check_absensi($param = array())
	{
		$this->db->where('user_id', $param['user_id']);
		$this->db->where('kelas_id', $param['kelas_id']);
		$this->db->where('status', $param['status']);
		$this->db->where('DATE(waktu)', date('Y-m-d', strtotime($param['waktu'])));
		$this->db->from('absensi');
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			return false;
		}
		else
		{
			return true;
		}		
	}

	function insert_absensi($param = array())
	{
		$this->db->insert('absensi', $param);
		if($this->db->affected_rows() > 0)
		{
			$pesan = '{nama_siswa} melakukan absensi ' . strtoupper($param['status']) . ' pada ' . format_tanggal_indonesia($param['waktu'], true);
			$this->fcm->insertNotifikasiWali(
				$param['user_id'], 
				'Absensi Terbaru', 
				$pesan,
				'',
				'absensi'
			);

			$this->sms->insertNotifikasiWali($param['user_id'], $pesan);

			/* Get Wali Kelas */
			// $this->fcm->insertNotifikasiWali(
			// 	$param['user_id'], 
			// 	'Absensi Terbaru', 
			// 	'{nama_siswa} melakukan absensi ' . strtoupper($param['status']) . ' pada ' . format_tanggal_indonesia($param['waktu'], true),
			// 	'',
			// 	'absensi'
			// );
			/* ENd Of Get Wali Kelas */			
			return true; 
		}
		else
		{
			return false;
		}
	}

	function update_absensi($param = array(), $absensi_id)
	{
		$this->db->where('absensi_id', $absensi_id);
		$this->db->update('absensi', $param);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

	function insert_telat($param = array())
	{
		$this->db->insert('absensi_telat', $param);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}				
	}

	function get_telat($user_id, $tanggal)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('DATE(waktu)', $tanggal);
		$this->db->from('absensi_telat');
		$query = $this->db->get();
		return $query;
	}

	function check_dispensasi()
	{
		$list_sesi_sekolah = array();

		$tanggal_sekarang  = date('Y-m-d');
		$this->db->select('a.*, b.sekolah_id, b.kelas_id');
		$this->db->where('a.tgl_mulai <= ', $tanggal_sekarang);
		$this->db->where('a.tgl_selesai >= ', $tanggal_sekarang);
		$this->db->from('absensi_dispensasi a');
		$this->db->join('user_siswa b', 'a.user_id = b.user_id');
		$get_data_dispensasi = $this->db->get()->result();

		foreach($get_data_dispensasi as $key => $c)
		{
			$this->db->where('DATE(waktu)', $tanggal_sekarang);
			$this->db->where('user_id', $c->user_id);
			$this->db->from('absensi');
			$check_absensi = $this->db->get()->row();
			if(!empty($check_absensi))
			{
				continue;
			}

			if(empty($list_sesi_sekolah[$c->sekolah_id]))
			{
				$this->db->where('sekolah_id', $c->sekolah_id);
				$this->db->from('master_sesi_jam');
				$get_sesi = $this->db->get()->row();
				if(empty($get_sesi))
				{
					continue;
				}			

				$list_sesi_sekolah[$c->sekolah_id] = $get_sesi;	
			}

			$param_db = array(
				'user_id'		=> $c->user_id,
				'kelas_id'		=> $c->kelas_id,
				'sesi_id'		=> $list_sesi_sekolah[$c->sekolah_id]->sesi_id,
				'jam_id'		=> $list_sesi_sekolah[$c->sekolah_id]->jam_id,
				'status'		=> 'ijin',
				'waktu'			=> $tanggal_sekarang . ' ' . date('H:i:s'),
				'telat'			=> 0,
				'keterangan'	=> $c->keterangan
			);

			$this->insert_absensi($param_db);
		}
	}

}
