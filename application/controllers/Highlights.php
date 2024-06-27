<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Highlights extends CI_Controller {
	protected $tournament_id;
	protected $tournament;
	protected $season;
	protected $date_start;
	protected $datetime_start;

	public function __construct(){
        parent::__construct();

		// $this->load->librery('session');
		// $this->load->librery('api_sp');
		// $this->load->helper('url');
		// $this->load->helper('html');
		
		$this->load->model('highlights_model');

		// date_default_timezone_set("Asia/Bangkok");

		$this->tournament_id = $this->config->config['tournament_id'];
		$this->tournament = $this->config->config['tournament'];
        $this->season = $this->config->config['season'];
 		$this->date_start = $this->config->config['date_start'];
		$this->datetime_start = $this->config->config['datetime_start'];
	}

	public function index()
	{


		$res = $this->highlights_model->get_data($this->tournament_id);
		Debug($res);

	}

}
