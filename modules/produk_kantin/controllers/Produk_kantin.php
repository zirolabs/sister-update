<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produk_kantin extends CI_Controller
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

		$this->load->model('produk_kantin_model');
		$this->load->model('riwayat_produk_kantin/riwayat_produk_kantin_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->page_active 		= 'kantin';
		$this->sub_page_active 	= 'produk_kantin';
	}


	public function index()
	{
		$param['sekolah']	= $this->input->get('sekolah');
		$param['keyword']	= $this->input->get('q');
		$limit 				= 25;
		$uri_segment		= 3;
		$filter = array(
			'limit'		=> $limit,
			'offset'	=> $this->uri->segment($uri_segment),
			'keyword'	=> $param['keyword'],
			'sekolah'	=> $param['sekolah'],
		);

		$param['data'] 		 = $this->produk_kantin_model->get_data($filter)->result();

		unset($filter['limit']);
		unset($filter['offset']);
		$total_rows 				= $this->produk_kantin_model->get_data($filter)->num_rows();
		$param['pagination']		= paging('produk_kantin/index', $total_rows, $limit, $uri_segment);
		$param['opt_sekolah']		= $this->profil_sekolah_model->get_opt('Semua Sekolah');
		$param['main_content']		= 'produk_kantin/table';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function form($id = '')
	{
		$param['msg']			= $this->session->flashdata('msg');
		$param['id']			= $id;

		$last_data 	= $this->session->flashdata('last_data');
		if(!empty($last_data))
		{
			$param['data'] = (object) $last_data;
		}
		else
		{
			if(!empty($id))
			{
				$param['data'] 	= $this->produk_kantin_model->get_data_row($id);
			}
		}

		$param['opt_sekolah']	= $this->profil_sekolah_model->get_opt();

		$param['main_content']		= 'produk_kantin/form';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}


	public function submit($id = '')
	{
		$data_post = $this->input->post();
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('kode_barang', 'Kode Barang', 'required|is_unique[master_produk.kode_barang]');
		$this->form_validation->set_rules('harga_awal', 'Harga Awal','required');
		$this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required');
		$this->form_validation->set_rules('kuantitas', 'Kuantitas', 'required');

		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('produk_kantin/form/' . $id);
		}
		else
		{
			$param = $data_post;

			if(empty($id))
			{
				$proses = $this->produk_kantin_model->insert($param);
				if($proses)
				{
					$this->session->set_flashdata('msg', suc_msg('Data berhasil disimpan.'));
				}
				else
				{
					$this->session->set_flashdata('msg', err_msg('Data gagal disimpan, silahkan ulangi lagi.'));
					redirect('produk_kantin/form/' . $id);
				}
			}
			else
			{
				$this->produk_kantin_model->update($param, $id);
				$this->session->set_flashdata('msg', suc_msg('Data berhasil diperbaharui.'));
			}

		}
		redirect('produk_kantin');
	}

	public function hapus($id)
	{
		$this->produk_kantin_model->delete($id);
		$this->riwayat_produk_kantin_model->delete($id);
		$this->session->set_flashdata('msg', suc_msg('Data berhasil dihapus.'));
		redirect('produk_kantin');
	}

}
