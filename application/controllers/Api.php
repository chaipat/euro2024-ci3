<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function __construct(){
        parent::__construct();

		$this->load->database();

		$this->load->model('program_model');
		$this->load->model('fixtures_model');
		$this->load->model('team_model');
		$this->load->helper('common');

		$this->tournament_id = $this->config->config['tournament_id'];
		$this->tournament = $this->config->config['tournament'];
        $this->season = $this->config->config['season'];

		$this->hostapi = 'https://worldcup2022-dev.ballnaja.com/';

	}

	public function index()
	{
		
	}

	public function fixture($sel_date = '')
	{
		header("Content-type: application/json; charset=utf-8");
		ob_start();

		$this->load->model('fixtures_model');

		$datebetween = null;
		$cur_date = date('Y-m-d');

		if($sel_date != ''){

			$date1 = $sel_date;
			$date2 = date('Y-m-d', strtotime($sel_date." 2 days"));
		}else{

			if($cur_date < '2022-11-20'){

				$date1 = '2022-11-20';
				$date2 = '2022-12-20';
			}else{

				$date1 = $cur_date;
				$date2 = date('Y-m-d', strtotime($cur_date." 2 days"));
			}
		}
		
		$datebetween[] = $date1;
		$datebetween[] = $date2;
		// Debug($datebetween);
		// die();
		$tournament_id = $this->tournament_id;

		$obj_list = $this->fixtures_model->get_data(0, 0, $tournament_id, $datebetween);

        // echo $this->db->last_query();
		// Debug($obj_list);
		// die();
		ob_clean();
		echo json_encode($obj_list);
		ob_end_flush();
	}

	public function welcome()
	{
		$this->load->view('welcome_message');
	}
}
