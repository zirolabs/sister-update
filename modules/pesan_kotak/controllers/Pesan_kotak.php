<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pesan_kotak extends CI_Controller
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

		$this->load->model('pesan_kotak_model');
		$this->page_active 		= 'pesan';
		$this->sub_page_active 	= 'pesan_kotak';
	}

	public function index()
	{
		$limit 				= 25;
		$uri_segment		= 3;
		$filter = array(
			'limit'			=> $limit,
			'offset'		=> $this->uri->segment($uri_segment),
			'user_id'		=> $this->login_uid
		);

		$param['data']			= $this->pesan_kotak_model->get_data($filter)->result();

		$param['pagination'] = '';
		if(!empty($param['data']))
		{
			unset($filter['limit']);
			unset($filter['offset']);
			$total_rows 			= $this->pesan_kotak_model->get_data($filter)->num_rows();
			$param['pagination']	= paging('pesan_kotak/index', $total_rows, $limit, $uri_segment);			
		}

		$param['main_content']		= 'table';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);
	}

	public function detail($id)
	{
		$param['pesan_id']		= $id;
		$param['data']			= $this->pesan_kotak_model->get_data($param)->row();

		if(empty($param['data']))
		{
			show_404();
		}

		$param['detail']		= $this->pesan_kotak_model->get_detail($id)->result();

		$param['saya'] = array();
		$param['kamu'] = array();
		if($param['data']->user_id_1 == $this->login_uid)
		{
			$param['saya'] = (object) array(
				'user_id'	=> $param['data']->user_id_1,
				'nama'		=> $param['data']->nama_user_1,
				'email'		=> $param['data']->email_user_1,
				'no_hp'		=> $param['data']->no_hp_user_1,
				'foto'		=> $param['data']->foto_user_1,
				'fcm'		=> $param['data']->fcm_user_1,
				'target'	=> $param['data']->target
			);
			$param['kamu'] = (object) array(
				'user_id'	=> $param['data']->user_id_2,
				'nama'		=> $param['data']->nama_user_2,
				'email'		=> $param['data']->email_user_2,
				'no_hp'		=> $param['data']->no_hp_user_2,
				'foto'		=> $param['data']->foto_user_2,
				'fcm'		=> $param['data']->fcm_user_2,
				'target'	=> $param['data']->target
			);
		}
		else
		{
			$param['saya'] = (object) array(
				'user_id'	=> $param['data']->user_id_2,
				'nama'		=> $param['data']->nama_user_2,
				'email'		=> $param['data']->email_user_2,
				'no_hp'		=> $param['data']->no_hp_user_2,
				'foto'		=> $param['data']->foto_user_2,
				'fcm'		=> $param['data']->fcm_user_2,
				'target'	=> $param['data']->target
			);
			$param['kamu'] = (object) array(
				'user_id'	=> $param['data']->user_id_1,
				'nama'		=> $param['data']->nama_user_1,
				'email'		=> $param['data']->email_user_1,
				'no_hp'		=> $param['data']->no_hp_user_1,
				'foto'		=> $param['data']->foto_user_1,
				'fcm'		=> $param['data']->fcm_user_1,
				'target'	=> $param['data']->target
			);
		}

		$param['main_content']		= 'detail';
		$param['page_active'] 		= $this->page_active;
		$param['sub_page_active'] 	= $this->sub_page_active;
		$this->templates->load('main_templates', $param);		
	}

	function kirim($id = '')
	{
		if(empty($id))
		{
			show_404();
		}

		$data_post = $this->input->post();
		$this->form_validation->set_rules('isi', 'Isi', 'required');
		if($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('msg', err_msg(validation_errors()));
			$this->session->set_flashdata('last_data', $data_post);
		}
		else
		{
			$penerima_id 	= $data_post['penerima'];
			$target 		= $data_post['target'];
			$fcm 			= $data_post['fcm'];

			$gambar 		= '';
			if(!empty($_FILES['userfiles']['tmp_name']))
			{
				$config['upload_path']      = './uploads/pesan/';
	            $config['allowed_types']    = 'jpg|png';
	 			$config['max_size'] 		= '2048';

	            if (!is_dir($config['upload_path']))
	            {
	                mkdir($config['upload_path']);
	            }

	            $this->load->library('upload', $config);
	            if (!$this->upload->do_upload('userfiles'))
	            {
					$this->session->set_flashdata('msg', err_msg($this->upload->display_errors()));
					$this->session->set_flashdata('last_data', $data_post);
					redirect('pesan_kotak/detail/' . $id);
	            }
	            else
	            {
	            	$data_upload 	= $this->upload->data();
	            	$gambar			= $config['upload_path'] . $data_upload['file_name'];
	            }
			}			

			$param_pesan = array(
				'user_id'		=> $this->login_uid,
				'isi'			=> $data_post['isi'],
				'gambar'		=> $gambar,
				'waktu_kirim'	=> date('Y-m-d H:i:s')
			);

			if($this->pesan_kotak_model->insert($param_pesan, $this->login_uid, $penerima_id, $target))
			{
				if(!empty($fcm))
				{
					$isi_notifikasi = substr($param_pesan['isi'], 0, 150);
					if($target == 'wali siswa')
					{
						$this->fcm->insertNotifikasiWali($penerima_id, 'Pesan Baru', $isi_notifikasi, $fcm, 'pesan');						
						$this->sms->insertNotifikasiWali($penerima_id, $isi_notifikasi);
					}
					else
					{
						$this->fcm->insertNotifikasiUser($penerima_id, 'Pesan Baru', $isi_notifikasi, $fcm, 'pesan');
						$this->sms->insertNotifikasiUser($penerima_id, $isi_notifikasi);
					}						
				}
			}
			$this->session->set_flashdata('msg', suc_msg('Pesan berhasil dikirim.'));			
		}
		redirect('pesan_kotak/detail/' . $id);		
	}
}
