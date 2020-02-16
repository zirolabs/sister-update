<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pelanggaran extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->login_status 	= $this->session->userdata('login_status');
		$this->login_uid 		= $this->session->userdata('login_uid');
		$this->login_level 		= $this->session->userdata('login_level');
		$cek = FALSE;
		if($this->login_level == 'operator sekolah' || $this->login_level == 'administrator' || $this->login_level == 'guru' || $this->login_level == 'kepala sekolah'){
			$cek = TRUE;
		}

		if($cek != TRUE)
		{
			$this->session->set_flashdata('msg', err_msg('Silahkan login untuk melanjutkan.'));
			redirect(site_url('login'));
		}

		$this->load->library('pdf');
		$this->load->model('pelanggaran_model');
		$this->load->model('kategori/kategori_model');
		$this->load->model('subkategori/subkategori_model');
		$this->load->model('profil_sekolah/profil_sekolah_model');
		$this->load->model('pengaturan_kelas/pengaturan_kelas_model');
		$this->page_active 		= 'pelanggaran';
		$this->sub_page_active 	= 'pelanggaran';
	}


	public function index()
	{
		$param['keyword']	= $this->input->get('q');
		$limit 				= 10;
		$uri_segment		= 3;
		$filter = array(
			'limit'		=> $limit,
			'offset'	=> $this->uri->segment($uri_segment),
			'keyword'	=> $param['keyword']
		);

		$param['data']			= $this->pelanggaran_model->get_data($filter)->result();

		unset($filter['limit']);
		unset($filter['offset']);
		$total_rows 			= $this->pelanggaran_model->get_data($filter)->num_rows();
		$param['pagination']	= paging('pelanggaran/index', $total_rows, $limit, $uri_segment);

		$param['main_content']	= 'pelanggaran/table';
		$param['page_active'] 	= $this->page_active;
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
		/*
		else
		{
			if(!empty($id))
			{
				$param['data'] = $this->pelanggaran_model->get_data_row($id);
			}
		}
		
		$param['level_user']		= $this->session->userdata('login_level');
		$param['id_user']		= $this->session->userdata('login_uid');
		*/
		$param['opt_kategori']		= $this->kategori_model->get_opt('Pilih Kategori');
		$param['main_content']		= 'pelanggaran/form';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}
	
	public function edit($id = '')
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
				$param['data'] = $this->pelanggaran_model->get_data_row($id);
			}
		}
		//$param['opt_kategori']		= $this->kategori_model->get_opt('Pilih Kategori');
		$param['main_content']		= 'pelanggaran/edit';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function submit($id = '')
	{
		$data_post = $this->input->post();
		if(empty($id)){
			$this->form_validation->set_rules('nama_siswa', 'Nama Siswa', 'required');
			$this->form_validation->set_rules('subkategori', 'Deskripsi Pelanggaran', 'required');
			$this->form_validation->set_rules('point', 'Point Pelanggaran', 'required');
			$this->form_validation->set_rules('tanggal', 'Tanggal Pelanggaran', 'required');
			$this->form_validation->set_rules('tindak_lanjut', 'Tindak Lanjut', 'required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
			$this->form_validation->set_rules('guru', 'Guru', 'required');
		} else {
			$this->form_validation->set_rules('tanggal', 'Tanggal Pelanggaran', 'required');
			$this->form_validation->set_rules('tindak_lanjut', 'Tindak Lanjut', 'required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
		}
		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
			redirect('pelanggaran/form/' . $id);
		}
		else
		{
			if(empty($id)){
				$data = array(
					'nis' 		=> $data_post['nis'],
					'kelas' 		=> $data_post['kelas'],
					'tanggal_pelanggaran' 		=> $data_post['tanggal'],
					'subkategori' 		=> $data_post['subkategori'],
					'point_pelanggaran' 		=> $data_post['point'],
					'tindak_lanjut' 		=> $data_post['tindak_lanjut'],
					'keterangan' 		=> $data_post['keterangan'],
					'guru_id' 		=> $data_post['guru']
				);	
				$this->pelanggaran_model->insert($data);	
				$this->session->set_flashdata('msg', suc_msg('Data berhasil disimpan.'));
			}else{
				$data = array(
					'tanggal_pelanggaran' 		=> $data_post['tanggal'],
					'tindak_lanjut' 		=> $data_post['tindak_lanjut'],
					'keterangan' 		=> $data_post['keterangan']
				);	
				$this->pelanggaran_model->update($data, $id);			
				$this->session->set_flashdata('msg', suc_msg('Data berhasil diperbaharui.'));
			}
		}
		redirect('pelanggaran');
	}

	public function hapus($id)
	{
		$proses = $this->pelanggaran_model->delete($id);
		$this->session->set_flashdata('msg', suc_msg('Data berhasil dihapus.'));
		redirect('pelanggaran');
	}
	
	public function get_subkategori()
	{
		$selected	= $this->input->get('selected');
		$kategori_id = $this->input->get('id_kategori');

		$result[''] = 'Pilih Sub Kategori';
		if(!empty($kategori_id))
		{
			$result 	= $this->subkategori_model->get_opt('Pilih Sub Kategori', $kategori_id);
		}

		echo form_dropdown('subkategori', $result, $selected, 'class="form-control" onchange="get_point(this.value)"');
	}		
	
	public function get_point()
	{
		$subkategori_id = $this->input->get('id_subkategori');

		$result= '';
		if(!empty($subkategori_id))
		{
			$result 	= $this->subkategori_model->get_point($subkategori_id);
		}

		echo $result;
	}
	public function search()
	{
		$keyword = $this->uri->segment(3);
		$new_keyword = urldecode($keyword);
		$data = $this->pelanggaran_model->search($new_keyword);	
		foreach($data->result() as $row)
		{
			//$result 	= $this->pelanggaran_model->get_opt('Pilih Guru', $row->sekolah_id);	
			$arr['query'] = $new_keyword;
			$arr['suggestions'][] = array(
				'value'	=>$row->nama.' | '.$row->jenjang.' '.$row->nama_jurusan.' '.$row->nama_kelas.' | '.$row->nama_sekolah,
				'nis'	=>$row->nis,
				'kelas'	=>$row->jenjang.' '.$row->nama_jurusan.' '.$row->nama_kelas,
				'sekolah'	=>$row->nama_sekolah,
				'sekolah_id'	=>$row->sekolah_id,
				//'guru'	=> form_dropdown('guru', $result, $selected, '', 'class="form-control"')
			);
		}
		echo json_encode($arr);
	}
	public function get_guru()
	{
		$sekolah_id = $this->input->get('sekolah_id');

		$result[''] = 'Pilih Guru';
		if(!empty($sekolah_id))
		{
			$result 	= $this->pelanggaran_model->get_opt('Pilih Guru', $sekolah_id);
		}

		echo form_dropdown('guru', $result, '', 'class="form-control"');
	}		
	
	public function cetak($id){
		$semua = array(
			'data' => $this->pelanggaran_model->cari_sekolah($id)
		);
        $this->load->view('pelanggaran/cetak',$semua);
	}
	public function laporan()
	{
		$param['sekolah']		= $this->input->get('sekolah');
		$param['kelas']			= $this->input->get('kelas');
		$param['tanggal_awal']	= $this->input->get('tanggal_awal');
		if(empty($param['tanggal_awal']))
		{
			$param['tanggal_awal'] = date('Y-m-d', strtotime('-30 DAYS'));
		}

		$param['tanggal_akhir']	= $this->input->get('tanggal_akhir');
		if(empty($param['tanggal_akhir']))
		{
			$param['tanggal_akhir'] = date('Y-m-d');
		}

		
		$uri_segment		= 3;
		$limit 				= 20;
		$param['limit']		= $limit;
		$param['offset']	= $this->uri->segment($uri_segment);
		$param['data']			= $this->pelanggaran_model->get_data($param)->result();

		unset($param['limit']);
		unset($param['offset']);
		$total_rows 			= $this->pelanggaran_model->get_data($param)->num_rows();
		$param['pagination']	= paging('pelanggaran/laporan', $total_rows, $limit, $uri_segment);		

		$param['opt_sekolah']	= $this->profil_sekolah_model->get_opt('Semua Sekolah');
		$param['sekolah_label']	= 'Semua Sekolah';
		if(!empty($param['sekolah']))
		{
			foreach($param['opt_sekolah'] as $key => $c)
			{
				if($key == $param['sekolah'])
				{
					$param['sekolah_label'] = $c;
					break;
				}
			}
		}

		$param['kelas_label']	= 'Semua Kelas';
		if(!empty($param['kelas']))
		{
			$data_kelas = $this->pengaturan_kelas_model->get_data(array('kelas_id' => $param['kelas']))->row();
			$param['kelas_label'] 	= $data_kelas->jenjang . ' ' . $data_kelas->nama_jurusan . ' ' . $data_kelas->nama;
		}
		$param['main_content']		= 'pelanggaran/laporan';
		$param['page_active'] 		= 'pelanggaran';
		$param['sub_page_active'] 	= 'pelanggaran/laporan';
		$this->templates->load('main_templates', $param);
	}
	
	public function laporan_per_siswa()
	{
		$param['nis']		= $this->input->get('nis');
		$param['tanggal_awal']	= $this->input->get('tanggal_awal');
		if(empty($param['tanggal_awal']))
		{
			$param['tanggal_awal'] = date('Y-m-d', strtotime('-30 DAYS'));
		}

		$param['tanggal_akhir']	= $this->input->get('tanggal_akhir');
		if(empty($param['tanggal_akhir']))
		{
			$param['tanggal_akhir'] = date('Y-m-d');
		}

		
		$uri_segment		= 3;
		$limit 				= 20;
		$param['limit']		= $limit;
		$param['offset']	= $this->uri->segment($uri_segment);
		$param['data']			= $this->pelanggaran_model->get_data($param)->result();
				

		unset($param['limit']);
		unset($param['offset']);
		$total_rows 			= $this->pelanggaran_model->get_data($param)->num_rows();
		$param['pagination']	= paging('pelanggaran/laporan_per_siswa', $total_rows, $limit, $uri_segment);		

		
		$param['main_content']		= 'pelanggaran/laporan_per_siswa';
		$param['page_active'] 		= 'pelanggaran';
		$param['sub_page_active'] 	= 'pelanggaran/laporan_per_siswa';
		$this->templates->load('main_templates', $param);
	}
	
	public function cetak_laporan_per_siswa($nis,$tanggal_awal,$tanggal_akhir){
		$param['nis']		= $nis;
		$param['tanggal_awal']	= $tanggal_awal;
		$param['tanggal_akhir']	= $tanggal_akhir;
		$semua = array(
			'data' => $this->pelanggaran_model->get_data($param)->result(),
			'tanggal_awal' => $tanggal_awal,
			'tanggal_akhir' => $tanggal_akhir
		);
        $this->load->view('pelanggaran/cetak_laporan_per_siswa',$semua);
	}
	
	public function cetak_laporan($tanggal_awal,$tanggal_akhir){
		$param['tanggal_awal']	= $tanggal_awal;
		$param['tanggal_akhir']	= $tanggal_akhir;
		$semua = array(
			'data' => $this->pelanggaran_model->get_data($param)->result(),
			'tanggal_awal' => $tanggal_awal,
			'tanggal_akhir' => $tanggal_akhir
		);
        $this->load->view('pelanggaran/cetak_laporan',$semua);
	}
	
}
