<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan_deposit extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->login_status 	= $this->session->userdata('login_status');
		$this->login_uid 		= $this->session->userdata('login_uid');
		$this->login_level 		= $this->session->userdata('login_level');
		$cek = FALSE;
		if($this->login_level == 'operator sekolah' || $this->login_level == 'administrator' || $this->login_level == 'kepala sekolah'){
			$cek = TRUE;
		}

		if($cek != TRUE)
		{
			$this->session->set_flashdata('msg', err_msg('Silahkan login untuk melanjutkan.'));
			redirect(site_url('login'));
		}

		$this->load->model('keuangan_deposit_model');
		$this->load->model('keuangan_mutasi/keuangan_mutasi_model');
		$this->load->model('keuangan_rfid/keuangan_rfid_model');
		$this->page_active 		= 'keuangan';
		$this->sub_page_active 	= 'keuangan_deposit';
	}

	public function index()
	{
		$param['msg']	= $this->session->flashdata('msg');

		$last_data 	= $this->session->flashdata('last_data');
		if(!empty($last_data))
		{
			$param['data'] = (object) $last_data;
		}

		$param['main_content']		= 'keuangan_deposit/form';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function submit()
	{
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
				$param_db = array(
					'user_id'		=> $cek_rfid->user_id,
					'jenis'			=> $data_post['jenis'],
					'nominal'		=> $data_post['nominal'],
					'keterangan'	=> $data_post['keterangan'],
					'waktu'			=> date('Y-m-d H:i:s')
				);

				if($param_db['jenis'] == 'kredit')
				{	
					$param_db['keterangan'] = 'Deposit Saldo. ';
				}
				elseif($param_db['jenis'] == 'debit')
				{
					$param_db['keterangan'] = 'Koreksi Saldo. ';					
				}

				if(!empty($data_post['keterangan']))
				{
					$param_db['keterangan'] .= 'Keterangan : ' . $data_post['keterangan'];
				}

				$proses = $this->keuangan_mutasi_model->insert($param_db);
				if($proses)
				{
					$this->session->set_flashdata('msg', suc_msg('Data deposit berhasil disimpan.'));
				}
				else
				{
					$this->session->set_flashdata('msg', err_msg('Data deposit gagal disimpan. Silahkan ulangi lagi.'));
				}				
			}
		}
		redirect('keuangan_deposit');
	}

	// Deposit dengan QR Code
	public function submitnis()
	{
		$data_post = $this->input->post();
		$this->form_validation->set_rules('nis', 'QR Code Salah', 'required');
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
				$param_db = array(
					'user_id'		=> $cek_nis->user_id,
					'jenis'			=> $data_post['jenis'],
					'nominal'		=> $data_post['nominal'],
					'keterangan'	=> $data_post['keterangan'],
					'waktu'			=> date('Y-m-d H:i:s')
				);

				if($param_db['jenis'] == 'kredit')
				{	
					$param_db['keterangan'] = 'Deposit Saldo. ';
				}
				elseif($param_db['jenis'] == 'debit')
				{
					$param_db['keterangan'] = 'Koreksi Saldo. ';					
				}

				if(!empty($data_post['keterangan']))
				{
					$param_db['keterangan'] .= 'Keterangan : ' . $data_post['keterangan'];
				}

				$proses = $this->keuangan_mutasi_model->insert($param_db);
				if($proses)
				{
					$this->session->set_flashdata('msg', suc_msg('Data deposit berhasil disimpan.'));
				}
				else
				{
					$this->session->set_flashdata('msg', err_msg('Data deposit gagal disimpan. Silahkan ulangi lagi.'));
				}				
			}
		}
		redirect('keuangan_deposit');
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
