<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kantin extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('kantin_model');
		$this->load->model('keuangan_transaksi/keuangan_transaksi_model');
		$this->load->model('keuangan_mutasi/keuangan_mutasi_model');
		$this->load->model('keuangan_rfid/keuangan_rfid_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
	}

	public function index()
	{
		$param['data']			= $this->profil_sekolah_model->get_data()->result();
		$param['main_content']	= 'kantin/table';
		$this->templates->load('main_templates', $param);
	}

	public function form($id = '')
	{
		if(empty($id))
		{
			show_404();
		}

		$last_data 	= $this->session->flashdata('last_data');
		if(!empty($last_data))
		{
			$param['data'] = (object) $last_data;
		}

		$param['id']			= $id;
		$param['data_sekolah']	= $this->profil_sekolah_model->get_data_row($id);
		$param['main_content']	= 'kantin/form';
		$this->templates->load('main_templates', $param);
	}

	public function submit($id = '')
	{
		if(empty($id))
		{
			show_404();
		}

		$data_post = $this->input->post();
		$this->form_validation->set_rules('sn_rfid', 'SN Kartu RFID', 'required');
		$this->form_validation->set_rules('nominal', 'Nominal', 'required');
		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
		}
		else
		{
			$param_cek_rfid = array('sn_rfid'	=> $data_post['sn_rfid']);
			$cek_rfid		= $this->keuangan_rfid_model->get_data($param_cek_rfid)->row(); 
			if(empty($cek_rfid))
			{
				$this->session->set_flashdata('msg', err_msg('Kartu RFID belum terdaftar.'));
				$this->session->set_flashdata('last_data', $data_post);
			}
			else
			{
				$this->load->model('keuangan_mutasi/keuangan_mutasi_model');
				$sisa_saldo  = $this->keuangan_mutasi_model->get_saldo($data_post['sn_rfid']);
				if($sisa_saldo < $data_post['nominal'])
				{
					$this->session->set_flashdata('msg', err_msg('Sisa saldo tidak mencukupi.'));
					redirect('kantin/form/' . $id);
					exit;
				}

				$data_post['jenis']		 = 'Debit';
				$data_post['keterangan'] = 'Transaksi di Kantin.';
				if(!empty($data_post['keterangan']))
				{
					$data_post['keterangan'] .= ' Keterangan : ' . $data_post['keterangan'];									
				}

				$param_db = array(
					'user_id'		=> $cek_rfid->user_id,
					'master_id'		=> 0,
					'jenis'			=> $data_post['jenis'],
					'nominal'		=> $data_post['nominal'],
					'keterangan'	=> $data_post['keterangan'],
					'waktu'			=> date('Y-m-d H:i:s')
				);

				$proses = $this->keuangan_mutasi_model->insert($param_db);
				if($proses)
				{
					$this->session->set_flashdata('msg', suc_msg('Data transaksi berhasil disimpan.'));
				}
				else
				{
					$this->session->set_flashdata('msg', err_msg('Data transaksi gagal disimpan. Silahkan ulangi lagi.'));
				}				
			}
		}
		redirect('kantin/form/' . $id);
	}

	public function submitqr($id = '')
	{
		if(empty($id))
		{
			show_404();
		}

		$data_post = $this->input->post();
		$this->form_validation->set_rules('nis', 'QR Code', 'required');
		$this->form_validation->set_rules('nominal', 'Nominal', 'required');
		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
		}
		else
		{
			$param_cek = array('nis'	=> $data_post['nis']);
			$cek_nis		= $this->keuangan_rfid_model->get_data_nis($param_cek)->row(); 
			if(empty($cek_nis))
			{
				$this->session->set_flashdata('msg', err_msg('QR Code belum terdaftar.'));
				$this->session->set_flashdata('last_data', $data_post);
			}
			else
			{
				$this->load->model('keuangan_mutasi/keuangan_mutasi_model');
				$sisa_saldo  = $this->keuangan_mutasi_model->get_saldo_by_nis($data_post['nis']);
				if($sisa_saldo < $data_post['nominal'])
				{
					$this->session->set_flashdata('msg', err_msg('Sisa saldo tidak mencukupi.'));
					redirect('kantin/form/' . $id);
					exit;
				}

				$data_post['jenis']		 = 'Debit';
				$data_post['keterangan'] = 'Transaksi di Kantin.';
				if(!empty($data_post['keterangan']))
				{
					$data_post['keterangan'] .= ' Keterangan : ' . $data_post['keterangan'];									
				}

				$param_db = array(
					'user_id'		=> $cek_nis->user_id,
					'master_id'		=> 0,
					'jenis'			=> $data_post['jenis'],
					'nominal'		=> $data_post['nominal'],
					'keterangan'	=> $data_post['keterangan'],
					'waktu'			=> date('Y-m-d H:i:s')
				);

				$proses = $this->keuangan_mutasi_model->insert($param_db);
				if($proses)
				{
					$this->session->set_flashdata('msg', suc_msg('Data transaksi berhasil disimpan.'));
				}
				else
				{
					$this->session->set_flashdata('msg', err_msg('Data transaksi gagal disimpan. Silahkan ulangi lagi.'));
				}				
			}
		}
		redirect('kantin/form/' . $id);
	}

	function ajax_pemilik_kartu()
	{
		$respon = array('status'	=> '201');

		$kode = $this->input->post('kode');
		if(!empty($kode))
		{
			$get_data = $this->keuangan_rfid_model->get_data(array('sn_rfid' => $kode))->row();
			if(!empty($get_data))
			{
				$this->load->model('keuangan_mutasi/keuangan_mutasi_model');
				$sisa_saldo = $this->keuangan_mutasi_model->get_saldo($kode);
				$respon = array(
					'status'	=> '200',
					'data'		=> array(
						'nama'			=> $get_data->nama_siswa,
						'nis'			=> $get_data->nis,
						'kelas'			=> $get_data->kelas,
						'sekolah'		=> $get_data->sekolah,
						'sisa_saldo'	=> format_rupiah($sisa_saldo)
					)
				);
			}
		}

		echo json_encode($respon);
	}

	// ajax untuk cek berdasarkan NIS (Untuk deposit dengan QR Code)
	function ajax_pemilik_nis()
	{
		$respon = array('status'	=> '201');

		$kode = $this->input->post('kode');
		if(!empty($kode))
		{
			$get_data = $this->keuangan_rfid_model->get_data_nis(array('nis' => $kode))->row();
			if(!empty($get_data))
			{
				$this->load->model('keuangan_mutasi/keuangan_mutasi_model');
				$sisa_saldo = $this->keuangan_mutasi_model->get_saldo_by_nis($kode);
				$respon = array(
					'status'	=> '200',
					'data'		=> array(
						'nama'			=> $get_data->nama_siswa,
						'nis'			=> $get_data->nis,
						'kelas'			=> $get_data->kelas,
						'sekolah'		=> $get_data->sekolah,
						'sisa_saldo'	=> format_rupiah($sisa_saldo)
					)
				);
			}
		}

		echo json_encode($respon);
	}
}
