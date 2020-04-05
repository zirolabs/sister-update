<?php

/**
 * @property int id_jadwal
 * @property string materi
 * @property string target
 * @property int siswa_hadir
 * @property int siswa_ijin
 * @property int siswa_alpa
 * @property string keterangan
 * @property $db
 */
class Jurnal_guru_model extends CI_Model {
    function get_data($param = array()) {
        $level_user = $this->session->userdata('login_level');
        $id_user = $this->session->userdata('login_uid');

        if (!empty($param)) {
            if (!empty($param['limit'])) {
                if (!empty($param['offset'])) {
                    $this->db->limit($param['limit'], $param['offset']);
                } else {
                    $this->db->limit($param['limit']);
                }
            }

            if (!empty($param['keyword'])) {
                $this->db->like('b.nama', $param['keyword']);
            }

            if (!empty($param['sekolah'])) {
                $this->db->where('a.sekolah_id', $param['sekolah']);
            }

            if (!empty($param['kelas'])) {
                $this->db->where('a.kelas_id', $param['kelas']);
            }

            if (isset($param['hari'])) {
                $this->db->where('a.hari', $param['hari']);
            }

            if (!empty($param['user_id'])) {
                $this->db->where('a.user_id', $param['user_id']);
            }

            if (!empty($param['kelas_id'])) {
                $this->db->where('a.kelas_id', $param['kelas_id']);
            }

            if (!empty($param['kepala sekolah'])) {
                $level_user = 'kepala sekolah';
                $id_user = $param['kepala sekolah'];
            }

            if (!empty($param['operator sekolah'])) {
                $level_user = 'operator sekolah';
                $id_user = $param['operator sekolah'];
            }

            if (!empty($param['guru'])) {
                $level_user = 'guru';
                $id_user = $param['guru'];
            }
        }

        $this->db->select("
			a.*, 
			b.nama as nama_mata_pelajaran, 
			c.nama as nama_guru,
			d.nip as nip_guru,
			CONCAT(e.jenjang, ' ', f.nama, ' ', e.nama) AS kelas
		");
        $this->db->order_by('a.jam_mulai');
        $this->db->from('jadwal_pelajaran a');
        $this->db->join('master_mata_pelajaran b', 'a.mata_pelajaran_id = b.mata_pelajaran_id');
        $this->db->join('user c', 'c.user_id = a.user_id');
        $this->db->join('user_guru d', 'd.user_id = c.user_id');
        $this->db->join('master_kelas e', 'e.kelas_id = a.kelas_id');
        $this->db->join('master_jurusan f', 'f.jurusan_id = e.jurusan_id');

        if ($level_user == 'kepala sekolah') {
            $this->db->where('x.user_id', $id_user);
            $this->db->join('user_kepala_sekolah x', 'x.sekolah_id = e.sekolah_id');
        } elseif ($level_user == 'operator sekolah') {
            $this->db->where('x.user_id', $id_user);
            $this->db->join('user_operator x', 'x.sekolah_id = e.sekolah_id');
        } elseif ($level_user == 'guru') {
            $this->db->where('c.user_id', $id_user);
            $this->db->where('x.user_id', $id_user);
            $this->db->join('user_guru x', 'x.sekolah_id = e.sekolah_id');
        }

        $get = $this->db->get();
        return $get;
    }

    function get_data_row($id) {
        $level_user = $this->session->userdata('login_level');
        $id_user = $this->session->userdata('login_uid');
        $this->db->select("
			a.*, 
			b.nama as nama_mata_pelajaran, 
            g.nama as sekolah,
			c.nama as nama_guru,
			d.nip as nip_guru,
			CONCAT(e.jenjang, ' ', f.nama, ' ', e.nama) AS kelas
		");
        $this->db->order_by('a.jam_mulai');
        $this->db->from('jadwal_pelajaran a');
        $this->db->join('jurnal_guru j', 'a.jadwal_id=j.id_jadwal', 'left');
        $this->db->join('master_mata_pelajaran b', 'a.mata_pelajaran_id = b.mata_pelajaran_id');
        $this->db->join('user c', 'c.user_id = a.user_id');
        $this->db->join('user_guru d', 'd.user_id = c.user_id');
        $this->db->join('profil_sekolah g', 'g.sekolah_id = d.sekolah_id');
        $this->db->join('master_kelas e', 'e.kelas_id = a.kelas_id');
        $this->db->join('master_jurusan f', 'f.jurusan_id = e.jurusan_id');

        $this->db->where('jadwal_id', $id);
        if ($level_user == 'kepala sekolah') {
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_kepala_sekolah x', 'x.sekolah_id = e.sekolah_id');
		} elseif ($level_user == 'operator sekolah') {
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_operator x', 'x.sekolah_id = e.sekolah_id');
		} elseif($level_user == 'guru') {
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_guru x', 'x.sekolah_id = e.sekolah_id');
		}
		$get = $this->db->get();
		return $get->row();
    }

    function find($id) {
        $jurnal = $this->db->get_where('jurnal_guru', ['id_jurnal' => $id])->result();
        return $jurnal;
    }

    function get_data_row_jurnal($id) {
        $level_user = $this->session->userdata('login_level');
        $id_user = $this->session->userdata('login_uid');
        $this->db->select("
			a.*, 
			b.nama as nama_mata_pelajaran, 
                        g.nama as sekolah,
			c.nama as nama_guru,
			d.nip as nip_guru,
                        j.id_jurnal,
                        j.id_jadwal,
                        j.materi,
                        j.target,
                        j.siswa_hadir,
                        j.siswa_ijin,
                        j.siswa_alpa,
                        j.keterangan,
			CONCAT(e.jenjang, ' ', f.nama, ' ', e.nama) AS kelas
		");
        $this->db->order_by('a.jam_mulai');
        $this->db->from('jadwal_pelajaran a');
        $this->db->join('jurnal_guru j', 'a.jadwal_id=j.id_jadwal', 'left');
        $this->db->join('master_mata_pelajaran b', 'a.mata_pelajaran_id = b.mata_pelajaran_id');
        $this->db->join('user c', 'c.user_id = a.user_id');
        $this->db->join('user_guru d', 'd.user_id = c.user_id');
        $this->db->join('profil_sekolah g', 'g.sekolah_id = d.sekolah_id');
        $this->db->join('master_kelas e', 'e.kelas_id = a.kelas_id');
        $this->db->join('master_jurusan f', 'f.jurusan_id = e.jurusan_id');

        $this->db->where('jadwal_id', $id);
		if($level_user == 'kepala sekolah') {
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_kepala_sekolah x', 'x.sekolah_id = e.sekolah_id');
		} elseif($level_user == 'operator sekolah') {
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_operator x', 'x.sekolah_id = e.sekolah_id');
		} elseif($level_user == 'guru') {
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_guru x', 'x.sekolah_id = e.sekolah_id');
		}
		$get = $this->db->get();
		return $get;

	}
	
	function get_data_jurnal() {
        $level_user = $this->session->userdata('login_level');
		$id_user = $this->session->userdata('login_uid');
		if (!empty($param)) {
            if (!empty($param['limit'])) {
                if (!empty($param['offset'])) {
                    $this->db->limit($param['limit'], $param['offset']);
                } else {
                    $this->db->limit($param['limit']);
                }
            }

            if (!empty($param['keyword'])) {
                $this->db->like('b.nama', $param['keyword']);
            }

            if (!empty($param['sekolah'])) {
                $this->db->where('a.sekolah_id', $param['sekolah']);
            }

            if (!empty($param['kelas'])) {
                $this->db->where('a.kelas_id', $param['kelas']);
            }

            if (isset($param['bulan'])) {
                $this->db->where('MONTH(a.tanggal)', $param['bulan']);
			}
			
			if (isset($param['tahun'])) {
                $this->db->where('YEAR(a.tanggal)', $param['tahun']);
            }

            if (!empty($param['user_id'])) {
                $this->db->where('a.user_id', $param['user_id']);
            }

            if (!empty($param['kelas_id'])) {
                $this->db->where('a.kelas_id', $param['kelas_id']);
            }

            if (!empty($param['kepala sekolah'])) {
                $level_user = 'kepala sekolah';
                $id_user = $param['kepala sekolah'];
            }

            if (!empty($param['operator sekolah'])) {
                $level_user = 'operator sekolah';
                $id_user = $param['operator sekolah'];
            }

            if (!empty($param['guru'])) {
                $level_user = 'guru';
                $id_user = $param['guru'];
            }
		}
		
        $this->db->select("
			a.*, 
			b.nama as nama_mata_pelajaran, 
                        g.nama as sekolah,
			c.nama as nama_guru,
			d.nip as nip_guru,
                        j.id_jurnal,
                        j.id_jadwal,
                        j.materi,
                        j.target,
                        j.siswa_hadir,
                        j.siswa_ijin,
                        j.siswa_alpa,
                        j.keterangan,
                        j.tanggal,
			CONCAT(e.jenjang, ' ', f.nama, ' ', e.nama) AS kelas
		");
        $this->db->order_by('j.tanggal','asc');
        $this->db->order_by('a.jam_mulai','desc');
        $this->db->from('jadwal_pelajaran a');
        $this->db->join('jurnal_guru j', 'a.jadwal_id=j.id_jadwal', 'left');
        $this->db->join('master_mata_pelajaran b', 'a.mata_pelajaran_id = b.mata_pelajaran_id');
        $this->db->join('user c', 'c.user_id = a.user_id');
        $this->db->join('user_guru d', 'd.user_id = c.user_id');
        $this->db->join('profil_sekolah g', 'g.sekolah_id = d.sekolah_id');
        $this->db->join('master_kelas e', 'e.kelas_id = a.kelas_id');
        $this->db->join('master_jurusan f', 'f.jurusan_id = e.jurusan_id');

		if($level_user == 'kepala sekolah') {
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_kepala_sekolah x', 'x.sekolah_id = e.sekolah_id');
		} elseif($level_user == 'operator sekolah') {
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_operator x', 'x.sekolah_id = e.sekolah_id');
		} elseif($level_user == 'guru') {
			$this->db->where('x.user_id', $id_user);
			$this->db->join('user_guru x', 'x.sekolah_id = e.sekolah_id');
		}
		$get = $this->db->get();
		return $get;

    }

    function getListByJadwal($idJadwal) {
        $this->db->select('jg.id_jurnal, jg.materi, jg.target, jg.siswa_hadir, jg.siswa_ijin, jg.siswa_alpa, 
        jg.keterangan, jg.tanggal, jp.jadwal_id')
            ->from('jurnal_guru AS jg')
            ->join('jadwal_pelajaran AS jp', 'jg.id_jadwal = jp.jadwal_id')
            ->where('jp.jadwal_id', $idJadwal);
        return $this->db->get()->result();
    }

    function insert($data) {
        $this->id_jadwal = $data["id"];
        $this->materi = $data["isi_materi"];
        $this->target = $data["target"];
        $this->siswa_hadir = $data["hadir"];
        $this->siswa_ijin = $data["ijin"];
        $this->siswa_alpa = $data["alpha"];
        $this->keterangan = $data["keterangan"];
        $this->db->insert('jurnal_guru', $this);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    function update($data, $id) {
        $this->db->where('id_jurnal', $id);
        $this->db->update('jurnal_guru', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function delete($id) {
        $this->db->where('id_jurnal', $id);
        $this->db->delete('jurnal_guru');
        return true;
    }

    function insert_kelas($param = array()) {
        $this->delete_kelas($param[0]['materi_id']);
        $this->db->insert_batch('mata_pelajaran_materi_kelas', $param);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    function delete_kelas($kelas_id) {
        $this->db->where('materi_id', $kelas_id);
        $this->db->delete('mata_pelajaran_materi_kelas');
    }

    function get_kelas($materi_id) {
        $query_str = "
			SELECT CONCAT(x2.jenjang, ' ', x3.nama, ' ', x2.nama) as nama, x2.kelas_id
			FROM mata_pelajaran_materi_kelas x1
			JOIN master_kelas x2 ON x1.kelas_id = x2.kelas_id
			JOIN master_jurusan x3 ON x2.jurusan_id = x3.jurusan_id
			WHERE x1.materi_id = '$materi_id'
		";
        $query = $this->db->query($query_str);
        return $query->result();
    }

}
