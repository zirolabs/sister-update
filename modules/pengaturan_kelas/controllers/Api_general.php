<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_general extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('pengaturan_kelas_model');
	}

	public function index()
	{
		$respon		= array(
			'status'	=> '201',
			'msg'		=> 'Data tidak ditemukan',
			'data'		=> array()
		);
		$data_post  = $this->input->post();

		$data 	= $this->pengaturan_kelas_model->get_opt('', @$data_post['sekolah_id'], $data_post);
		$result = array();
		foreach($data as $key => $c)
		{
			$result[] = array(
				'value' => $key,
				'label'	=> $c
			);
		}

		if(!empty($result))
		{
			$respon		= array(
				'status'	=> '200',
				'msg'		=> 'Data ditemukan',
				'data'		=> $result
			);			
		}
		echo json_encode($respon);
	}
}

?>