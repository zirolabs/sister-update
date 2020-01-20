<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_general extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('pengaturan_semester_model');
	}

	public function index()
	{
		$respon		= array(
			'status'	=> '201',
			'msg'		=> 'Data tidak ditemukan',
			'data'		=> array()
		);
		$data_post  = $this->input->post();

		$data 	= $this->pengaturan_semester_model->get_opt();
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