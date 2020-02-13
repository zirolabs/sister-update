<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Riwayat_produk_kantin extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->login_status 	= $this->session->userdata('login_status');
		$this->login_uid 		= $this->session->userdata('login_uid');
		$this->login_level 		= $this->session->userdata('login_level');
		$cek = FALSE;
		if($this->login_level == 'operator sekolah' || $this->login_level == 'administrator' || $this->login_level == 'user kantin'){
			$cek = TRUE;
		}

		if($cek != TRUE)
		{
			$this->session->set_flashdata('msg', err_msg('Silahkan login untuk melanjutkan.'));
			redirect(site_url('login'));
		}

		$this->load->model('riwayat_produk_kantin_model');
		$this->load->model('produk_kantin/produk_kantin_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->page_active 		= 'kantin';
		$this->sub_page_active 	= 'riwayat_produk_kantin';

		require_once APPPATH.'third_party/fpdf/fpdf.php';
		
		$pdf = new FPDF();
		$pdf->AddPage();
		
		$CI =& get_instance();
		$CI->fpdf = $pdf;
	}

	public function index()
	{
		$param['sekolah']	= $this->input->get('sekolah');
		$param['keyword']	= $this->input->get('q');
		$limit 				= 25;
		$uri_segment		= 3;
		$filter = array(
			'limit'				=> $limit,
			'offset'			=> $this->uri->segment($uri_segment),
			'keyword'			=> $param['keyword'],
			'sekolah'			=> $param['sekolah'],
			'jenis_transaksi'	=> 'Transaksi di Kantin.',
		);

		$param['data'] 		 = $this->riwayat_produk_kantin_model->get_data($filter)->result();

		unset($filter['limit']);
		unset($filter['offset']);
		$total_rows 				= $this->riwayat_produk_kantin_model->get_data($filter)->num_rows();
		$param['pagination']		= paging('riwayat_produk_kantin/index', $total_rows, $limit, $uri_segment);
		$param['opt_sekolah']		= $this->profil_sekolah_model->get_opt('Semua Sekolah');
		$param['main_content']		= 'riwayat_produk_kantin/table';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function laporan()
	{
		$param['sekolah']	= $this->input->get('sekolah');
		$param['keyword']	= $this->input->get('q');
		$param['tanggal']	= $this->input->get('tanggal');
		$filter = array(
			'keyword'			=> $param['keyword'],
			'sekolah'			=> $param['sekolah'],
			'tanggal'			=> $param['tanggal'],
		);

		$param['data'] 		 = $this->riwayat_produk_kantin_model->get_laporan($filter)->result();
		// pdf_laporan($this->riwayat_produk_kantin_model->get_laporan($filter)->result());

		unset($filter['limit']);
		unset($filter['offset']);
		$param['opt_sekolah']		= $this->profil_sekolah_model->get_opt('Semua Sekolah');
		$param['main_content']		= 'riwayat_produk_kantin/laporan';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= 'laporan';
		$this->templates->load('main_templates', $param);
	}
	
    public function pdf_laporan($param = array())
    {
        $pdf = new FPDF('L','mm','A4'); //L = lanscape P= potrait
        // membuat halaman baru
        $pdf->AddPage();
        // setting jenis font yang akan digunakan
        $pdf->SetFont('Arial','B',16);
        $ya = 44;
        // mencetak string 
        $pdf->Cell(190,7,"Laporan Penjualan Kantin Pada ".date('Y-m-d'),0,1,'C');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,7,'Angket Semester 2 TA 2017-2018',0,1,'C');
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(15,6,'No',1,0);
        $pdf->Cell(55,6,'Nama Produk',1,0);
        $pdf->Cell(40,6,'Kuantitas',1,0);
        $pdf->Cell(30,6,'Harga Jual',1,0);
        $pdf->Cell(35,6,'Total Harga',1,0);
        $pdf->SetFont('Arial','',10);
        foreach ($param as $row){
			$no = 1;
            $pdf->Cell(15,6,$no,1,0);
			$pdf->Cell(55,6,$row->param['nama_produk'],1,0);
            $pdf->Cell(30,6,$row->param['total'],1,0);
			$pdf->Cell(40,6,$row->param['harga_jual'],1,0);
			$pdf->Cell(40,6,($row->param['harga_jual']*$row->param['total']),1,0);
			$no = $no +1;
		}
		$pdf->Output();
	}

	public function detail()
	{	
		$param['sekolah']	= $this->input->get('sekolah');
		$param['keyword']	= $this->input->get('q');
		$param['mutasi_id']	= $this->input->get('mutasi_id');
		$limit 				= 25;
		$uri_segment		= 3;
		$filter = array(
			'limit'				=> $limit,
			'offset'			=> $this->uri->segment($uri_segment),
			'keyword'			=> $param['keyword'],
			'mutasi_id'			=> $param['mutasi_id']
		);

		$filter_id = array(
			'mutasi_id'			=> $param['mutasi_id']
		);

		$param['data'] 		 		= $this->riwayat_produk_kantin_model->get_detail_data($filter)->result();
		$param['data_mutasi'] 		= $this->riwayat_produk_kantin_model->get_data($filter_id)->row();
		unset($filter['limit']);
		unset($filter['offset']);
		$total_rows 				= $this->riwayat_produk_kantin_model->get_detail_data($filter)->num_rows();
		$param['pagination']		= paging('riwayat_produk_kantin/detail', $total_rows, $limit, $uri_segment);
		$param['opt_sekolah']		= $this->profil_sekolah_model->get_opt('Semua Sekolah');
		$param['main_content']		= 'riwayat_produk_kantin/detail';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

}
