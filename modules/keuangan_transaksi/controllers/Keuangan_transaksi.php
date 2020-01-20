<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan_transaksi extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->login_status 	= $this->session->userdata('login_status');
		$this->login_uid 		= $this->session->userdata('login_uid');
		if($this->login_status != 'ok')
		{
			$this->session->set_flashdata('msg', err_msg('Silahkan login untuk melanjutkan.'));
			redirect(site_url('login'));
		}

		$this->load->model('keuangan_transaksi_model');
		$this->load->model('keuangan_mutasi/keuangan_mutasi_model');
		$this->load->model('keuangan_rfid/keuangan_rfid_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->page_active 		= 'keuangan';
		$this->sub_page_active 	= 'keuangan_transaksi';
	}

	public function index()
	{
		$param['msg']	= $this->session->flashdata('msg');

		$last_data 	= $this->session->flashdata('last_data');
		if(!empty($last_data))
		{
			$param['data'] = (object) $last_data;
		}

		$param['opt_sekolah']		= $this->profil_sekolah_model->get_opt('Semua Sekolah');
		unset($param['opt_sekolah']['']);

		$param['main_content']		= 'keuangan_transaksi/form';
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
				$this->load->model('keuangan_master/keuangan_master_model');
				$data_master = $this->keuangan_master_model->get_data_row($data_post['master_id']);
				if($data_master->operasi == 'debit')
				{
					$this->load->model('keuangan_mutasi/keuangan_mutasi_model');
					$sisa_saldo  = $this->keuangan_mutasi_model->get_saldo($data_post['sn_rfid']);
					if($sisa_saldo < $data_post['nominal'])
					{
						$this->session->set_flashdata('msg', err_msg('Sisa saldo tidak mencukupi.'));
						redirect('keuangan_transaksi');
						exit;
					}
				}

				$data_post['jenis']		 = $data_master->operasi;
				if(!empty($data_post['keterangan']))
				{
					$data_post['keterangan'] = $data_master->nama . '. Keterangan : ' . $data_post['keterangan'];									
				}
				else
				{
					$data_post['keterangan'] = $data_master->nama;				
				}

				$param_db = array(
					'user_id'		=> $cek_rfid->user_id,
					'master_id'		=> $data_post['master_id'],
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
		redirect('keuangan_transaksi');
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

	function ajax_master_keuangan()
	{
		$sekolah 	= $this->input->post('sekolah');
		$selected	= $this->input->post('selected');

		$this->load->model('keuangan_master/keuangan_master_model');
		$get_opt = $this->keuangan_master_model->get_opt($sekolah);
		echo form_dropdown('master_id', $get_opt, $selected, 'class="form-control" onchange="getNominalKeuangan()"');
	}
}
