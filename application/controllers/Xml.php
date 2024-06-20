<?php
error_reporting(E_ALL);
ini_set("display_errors", "1");

class Xml extends CI_Controller {

	var $xml_host = 'http://www.goalserve.com/getfeed/c37c46d4313e43a899a3a3d00cbd454b/';
	var $xml_host2 = 'http://www.goalserve.com/getfeed/ff0aa5cf9aca4b778bef284498fe9907/';

	/*var $xml_team = 'uploads/XML/team.xml';
	var $xml_h2h = 'uploads/XML/h2h.xml';
	var $xml_player = 'uploads/XML/player.xml';
	var $xml_fixtures_results = 'uploads/XML/fixtures-results.xml';
	var $xml_live_commentaries = 'uploads/XML/live_commentaries.xml';
	var $xml_standings = 'uploads/XML/standings.xml';
	var $xml_topscorers = 'uploads/XML/topscorers.xml';
	var $xml_odds = 'uploads/XML/odds2.xml';*/

	var $xml_team = 'soccerstats/team/';
	var $xml_h2h = 'h2h/9249/9002';	//'h2h/9249/9002'
	var $xml_player = 'soccerstats/player/';
	var $xml_fixtures_results = 'soccerfixtures/england/PremierLeague';
	var $xml_live_commentaries_epl = 'commentaries/epl.xml';

	var $xml_standings = 'standings/fifa_worldcup.xml';
	var $xml_topscorers = 'topscorers/worldcup';
	var $xml_odds = 'getodds/soccer?cat=worldcup';

	// var $xml_standings = 'standings/england.xml';
	// var $xml_topscorers = 'topscorers/england';
	// var $xml_odds = 'getodds/soccer?cat=england';
	
	//News
	var $xml_news_yesterday = 'soccernew/d-1';	//all yesterday games from all leagues
	var $xml_news_tomorrow = 'soccernew/d1';	//all tomorrow games from all leagues
	var $xml_news_lives = 'soccernew/home';		//all livescores from all leagues
	var $xml_england_shedule = 'soccernew/england_shedule';
	var $xml_historical = 'soccerhistory/england/PremierLeague-2011-2012';

	var $prefix;

    public function __construct()    {
		parent::__construct();
		
		$this->load->model('xml_model');
		$this->load->library('user_agent');
		$this->load->library('genarate');

		$this->load->helper('url');
		$this->load->helper('asset');
		$this->load->helper('logs');
		$this->load->helper('xml');
		$this->load->helper('form');
		$this->load->helper('debug');
		$this->load->helper('common');

		$this->tournament_id = $this->config->config['tournament_id'];
		$this->tournament = $this->config->config['tournament'];
        $this->season = $this->config->config['season'];
 		$this->date_start = $this->config->config['date_start'];
		$this->datetime_start = $this->config->config['datetime_start'];
		$this->prefix = 'ba';

		$this->hostapi = 'https://worldcup2022-dev.ballnaja.com/';
		//$this->load->helper('directory');
		//$this->load->helper('file');

        //if(!$this->session->userdata('is_logged_in')){
            //redirect(base_url());
        //}
    }

	public function index(){
		$this->load->model('team_model');

		//$ListSelect = $this->genarate->user_menu($this->session->userdata('admin_type'));
		$data = '';
		$list_fixture = $list_fixture_debug = array();
		
		$user_agent = $this->input->user_agent;
		$host = $this->config->config['www'];

		// if($this->agent->is_mobile()){

		// }else{
		
		// }

		$date_1 = date('Y-m-d', strtotime(" -1 days"));
		$date_2 = date('Y-m-d', strtotime(" -2 days"));

		if($this->input->get('s') == 1){

			$get_teamlist = $this->team_model->get_data($this->tournament_id);
			// Debug($get_teamlist);
			
			$list_worldcup[] = anchor(base_url('xml/import_soccernew/json/worldcup?cat=1056'), 'soccernew', array('target' => '_blank', 'title' => 'soccernew'));
			$list_worldcup[] = anchor(base_url('xml/import_soccernew/json/worldcup_shedule?cat=1056'), 'worldcup_shedule', array('target' => '_blank', 'title' => 'Update worldcup_shedule'));
			$list_worldcup[] = anchor(base_url('xml/import_standings/debug/fifa_worldcup.xml'), 'standings', array('target' => '_blank', 'title' => 'standings'));
			$list_worldcup[] = anchor(base_url('xml/import_fixtures_results/json/worldcup'), 'fixtures', array('target' => '_blank', 'title' => 'fixtures'));
			$list_worldcup[] = anchor(base_url('xml/import_topscorers/json/topscore'), 'Topscore', array('target' => '_blank', 'title' => 'Topscore'));
			$list_worldcup[] = anchor(base_url('xml/import_topscorers/json/worldcup_assists'), 'Topassists', array('target' => '_blank', 'title' => 'Topassists'));
			$list_worldcup[] = anchor(base_url('xml/import_fixtures_results/debug/odds'), 'odds', array('target' => '_blank', 'title' => 'odds'));
			$list_worldcup[] = anchor(base_url('xml/import_fixtures_results/debug/history'), 'history', array('target' => '_blank', 'title' => 'history'));
			$list_worldcup[] = anchor(base_url('xml/import_highlights/json/d-1'), 'highlights', array('target' => '_blank', 'title' => 'highlights'));
			
			$data .= '<div class="col-lg-6">
				<div class="panel panel-danger">
					<div class="panel-heading">
						Worldcup 2022
					</div>
					<div class="panel-body">
						<div class="col-lg-12">'.$list_worldcup[0].'</div>
						<div class="col-lg-12">'.$list_worldcup[1].'</div>
						<div class="col-lg-12">'.$list_worldcup[2].'</div>
						<div class="col-lg-12">'.$list_worldcup[3].'</div>
						<div class="col-lg-12">'.$list_worldcup[4].'</div>
						<div class="col-lg-12">'.$list_worldcup[5].'</div>
						<div class="col-lg-12">'.$list_worldcup[6].'</div>
						<div class="col-lg-12">'.$list_worldcup[7].'</div>
						<div class="col-lg-12">'.$list_worldcup[8].'</div>
					</div>
				</div>
			</div>';


			unset($list_fixture);
			unset($list_fixture_debug);

			$list_fixture[] = anchor(base_url('xml/import_soccernew/query/home?cat=1056'), 'Worldcup', array('target' => '_blank', 'title' => 'query'));
			$list_fixture[] = anchor(base_url('xml/import_soccernew/query/d-1?cat=1056'), 'Worldcup D-1', array('target' => '_blank', 'title' => 'query'));
			$list_fixture[] = anchor(base_url('xml/import_soccernew/query/d-2?cat=1056'), 'Worldcup D-2', array('target' => '_blank', 'title' => 'query'));
			$list_fixture[] = anchor(base_url('xml/import_soccernew/query/d-3?cat=1056'), 'Worldcup D-3', array('target' => '_blank', 'title' => 'query'));
			$list_fixture[] = anchor(base_url('xml/import_soccernew/query/d-4?cat=1056'), 'Worldcup D-4', array('target' => '_blank', 'title' => 'query'));
			$list_fixture[] = anchor(base_url('xml/import_soccernew/query/d-5?cat=1056'), 'Worldcup D-5', array('target' => '_blank', 'title' => 'query'));
			$list_fixture[] = anchor(base_url('xml/import_soccernew/query/d-6?cat=1056'), 'Worldcup D-6', array('target' => '_blank', 'title' => 'query'));
			$list_fixture[] = anchor(base_url('xml/import_soccernew/query/d-7?cat=1056'), 'Worldcup D-7', array('target' => '_blank', 'title' => 'query'));
			$list_fixture[] = anchor(base_url('xml/import_match_event/query/home?cat=1056'), 'Event Now', array('target' => '_blank', 'title' => 'Event'));
			$list_fixture[] = anchor(base_url('xml/import_match_event/query/home?cat=1056&sel_date='.$date_1), 'Event D-1', array('target' => '_blank', 'title' => 'Event'));
			$list_fixture[] = anchor(base_url('xml/import_match_event/query/home?cat=1056&sel_date='.$date_2), 'Event D-2', array('target' => '_blank', 'title' => 'Event'));
			$list_fixture[] = anchor(base_url('xml/import_fixtures_results/query/worldcup'), 'Update Lineup', array('target' => '_blank', 'title' => 'Lineup'));

			$list_fixture_debug[] = anchor(base_url('xml/import_soccernew/query/d1?cat=1056'), 'Worldcup D+1', array('target' => '_blank', 'title' => 'query'));
			$list_fixture_debug[] = anchor(base_url('xml/import_soccernew/query/d2?cat=1056'), 'Worldcup D+2', array('target' => '_blank', 'title' => 'query'));
			$list_fixture_debug[] = anchor(base_url('xml/import_soccernew/query/d3?cat=1056'), 'Worldcup D+3', array('target' => '_blank', 'title' => 'query'));
			$list_fixture_debug[] = anchor(base_url('xml/import_soccernew/query/d4?cat=1056'), 'Worldcup D+4', array('target' => '_blank', 'title' => 'query'));
			$list_fixture_debug[] = anchor(base_url('xml/import_soccernew/query/d5?cat=1056'), 'Worldcup D+5', array('target' => '_blank', 'title' => 'query'));
			$list_fixture_debug[] = anchor(base_url('xml/import_soccernew/query/d6?cat=1056'), 'Worldcup D+6', array('target' => '_blank', 'title' => 'query'));
			$list_fixture_debug[] = anchor(base_url('xml/import_soccernew/query/d7?cat=1056'), 'Worldcup D+7', array('target' => '_blank', 'title' => 'query'));
			$list_fixture_debug[] = anchor(base_url('xml/import_standings/debug/fifa_worldcup.xml?query=1'), 'Standings', array('target' => '_blank', 'title' => 'query'));
			$list_fixture_debug[] = anchor(base_url('xml/import_topscorers/query/worldcup'), 'Topscorers', array('target' => '_blank', 'title' => 'query'));
			$list_fixture_debug[] = anchor(base_url('xml/import_topassits/query/worldcup_assists'), 'Topassits', array('target' => '_blank', 'title' => 'query'));
			$list_fixture_debug[] = anchor(base_url('xml/import_topcards/query/worldcup_cards'), 'Topcards', array('target' => '_blank', 'title' => 'query'));
			$list_fixture_debug[] = anchor(base_url('xml/import_highlights/query/d-1'), 'Highlights', array('target' => '_blank', 'title' => 'Highlights'));

			$data .= '<div class="col-lg-6">
						<div class="panel panel-primary">
							<div class="panel-heading">
								Fixture & Event Worldcup
							</div>
							<div class="panel-body">
								<div class="col-lg-6">Prev</div><div class="col-lg-6">Next</div>';

			$allteam = count($list_fixture);
			for($i=0;$i<$allteam; $i++){

				$data .= '<div class="col-lg-6">'.$list_fixture[$i].'</div>';
				if(isset($list_fixture_debug[$i]))
					$data .= '<div class="col-lg-6">'.$list_fixture_debug[$i].'</div>';

				// $data .= '<p class="text-primary"><a href="'.base_url('xml/import_team/import/'.$team_id).'" target=_blank>'.$logo_img.' '.$team_name.'</a></p>';
			}

			$data .= '</div>
					</div>
				</div>';


			/*$list_soccernew[] = anchor(base_url('xml/import_soccernew/query/home'), 'Now', array('target' => '_blank', 'title' => 'Now'));
			$list_soccernew[] = anchor(base_url('xml/import_soccernew/query/d1'), 'D1', array('target' => '_blank', 'title' => 'd1'));
			$list_soccernew[] = anchor(base_url('xml/import_soccernew/query/d2'), 'D2', array('target' => '_blank', 'title' => 'd2'));
			$list_soccernew[] = anchor(base_url('xml/import_soccernew/query/d3'), 'D3', array('target' => '_blank', 'title' => 'd3'));
			$list_soccernew[] = anchor(base_url('xml/import_soccernew/query/d4'), 'D4', array('target' => '_blank', 'title' => 'd4'));
			$list_soccernew[] = anchor(base_url('xml/import_soccernew/query/d5'), 'D5', array('target' => '_blank', 'title' => 'd5'));
			$list_soccernew[] = anchor(base_url('xml/import_soccernew/query/d6'), 'D6', array('target' => '_blank', 'title' => 'd6'));
			$list_soccernew[] = anchor(base_url('xml/import_soccernew/query/d7'), 'D7', array('target' => '_blank', 'title' => 'd7'));

			$list_soccernew[] = anchor(base_url('xml/import_soccernew/query/d-1'), 'D-1', array('target' => '_blank', 'title' => 'd-1'));
			$list_soccernew[] = anchor(base_url('xml/import_soccernew/query/d-2'), 'D-2', array('target' => '_blank', 'title' => 'd-2'));
			$list_soccernew[] = anchor(base_url('xml/import_soccernew/query/d-3'), 'D-3', array('target' => '_blank', 'title' => 'd-3'));
			$list_soccernew[] = anchor(base_url('xml/import_soccernew/query/d-4'), 'D-4', array('target' => '_blank', 'title' => 'd-4'));
			$list_soccernew[] = anchor(base_url('xml/import_soccernew/query/d-5'), 'D-5', array('target' => '_blank', 'title' => 'd-5'));
			$list_soccernew[] = anchor(base_url('xml/import_soccernew/query/d-6'), 'D-6', array('target' => '_blank', 'title' => 'd-6'));
			$list_soccernew[] = anchor(base_url('xml/import_soccernew/query/d-7'), 'D-7', array('target' => '_blank', 'title' => 'd-7'));

			$data .= '<div class="col-lg-6">
				<div class="panel panel-primary">
					<div class="panel-heading">
						Soccernew
					</div>
					<div class="panel-body">
						<div class="col-lg-12">'.$list_soccernew[0].'</div>
				
						<div class="col-lg-6">'.$list_soccernew[8].'</div><div class="col-lg-6">'.$list_soccernew[1].'</div>
						<div class="col-lg-6">'.$list_soccernew[9].'</div><div class="col-lg-6">'.$list_soccernew[2].'</div>
						<div class="col-lg-6">'.$list_soccernew[10].'</div><div class="col-lg-6">'.$list_soccernew[3].'</div>
						<div class="col-lg-6">'.$list_soccernew[11].'</div><div class="col-lg-6">'.$list_soccernew[4].'</div>
						<div class="col-lg-6">'.$list_soccernew[12].'</div><div class="col-lg-6">'.$list_soccernew[5].'</div>
						<div class="col-lg-6">'.$list_soccernew[13].'</div><div class="col-lg-6">'.$list_soccernew[6].'</div>
						<div class="col-lg-6">'.$list_soccernew[14].'</div><div class="col-lg-6">'.$list_soccernew[7].'</div>
					</div>
				</div>
			</div>';*/

			//********** List Team ***********/
			$data .= '<div class="col-lg-6">
			<div class="panel panel-primary">
				<div class="panel-heading">
					Json
				</div>
				<div class="panel-body">';

			$allteam = count($get_teamlist);
			$data .= $allteam.' team';
			for($i=0;$i<$allteam; $i++){

				$team_list = $get_teamlist[$i];

				$team_id = $team_list->team_id;
				$league_id = $team_list->league_id;
				$team_name = ($team_list->team_name != '') ? $team_list->team_name:$team_list->team_name_en;
				$logo = $team_list->logo;
				$stadium_id = $team_list->stadium_id;
				$manager_id = $team_list->manager_id;
				$manager_name = $team_list->manager_name;

				$image_properties = array(
					'src'   => $logo,
					'alt'   => $team_name,
					'class' => 'post_images',
					'width' => '80',
					'height'=> '50',
					'rel'   => 'lightbox'
				);
				$logo_img = img($image_properties);

				$data .= '<p class="text-primary"><a href="'.base_url('xml/import_team/json/'.$team_id).'" target=_blank>'.$logo_img.' '.$team_name.'</a></p>';

			}
			/*$data .= '<p class="text-primary"><a href="'.base_url('xml/import_team/json').'" target=_blank>'.base_url('xml/import_team').'</a></p>
				<p class="text-primary"><a href="'.base_url('xml/import_h2h/json').'" target=_blank>'.base_url('xml/import_h2h').'</a></p>
				<p class="text-primary"><a href="'.base_url('xml/import_player/json').'" target=_blank>'.base_url('xml/import_player').'</a></p>
				<p class="text-primary"><a href="'.base_url('xml/import_fixtures_results/json').'" target=_blank>'.base_url('xml/import_fixtures_results').'</a></p>
				<p class="text-primary"><a href="'.base_url('xml/import_live_commentaries/json').'" target=_blank>'.base_url('xml/import_live_commentaries').'</a></p>
				<p class="text-primary"><a href="'.base_url('xml/import_standings/json').'" target=_blank>'.base_url('xml/import_standings').'</a></p>
				<p class="text-primary"><a href="'.base_url('xml/import_topscorers/json').'" target=_blank>'.base_url('xml/import_topscorers').'</a></p>
				<p class="text-primary"><a href="'.base_url('xml/import_odds/json').'" target=_blank>'.base_url('xml/import_odds').'</a></p>';*/

			$data .= '</div>
						</div>
					</div>';
			//********** Team ***********/

			//********** IMPORT TEAM ***********/
			$data .= '<div class="col-lg-6">
			<div class="panel panel-warning">
				<div class="panel-heading">
					IMPORT TEAM
				</div>
				<div class="panel-body">';

			$allteam = count($get_teamlist);
			$data .= $allteam.' team';
			for($i=0;$i<$allteam; $i++){

				$team_list = $get_teamlist[$i];

				$team_id = $team_list->team_id;
				$league_id = $team_list->league_id;
				$team_name = ($team_list->team_name != '') ? $team_list->team_name:$team_list->team_name_en;
				$logo = $team_list->logo;
				$stadium_id = $team_list->stadium_id;
				$manager_id = $team_list->manager_id;
				$manager_name = $team_list->manager_name;

				$image_properties = array(
					'src'   => $logo,
					'alt'   => $team_name,
					'class' => 'post_images',
					'width' => '80',
					'height'=> '50',
					'rel'   => 'lightbox'
				);
				$logo_img = img($image_properties);

				$data .= '<p class="text-primary"><a href="'.base_url('xml/import_team/import/'.$team_id).'" target=_blank>'.$logo_img.' '.$team_name.'</a></p>';
			}

			$data .= '</div>
						</div>
					</div>';
			//********** IMPORT TEAM ***********/

			$data .= '<div class="col-lg-6">
						<div class="panel panel-success">
							<div class="panel-heading">
								XML XSL
							</div>
							<div class="panel-body">
				<p class="text-primary"><a href="'.$host.'stat/xml/livescore.xml" target=_blank>'.$host.'stat/xml/livescore.xml</a></p>
				<p class="text-primary"><a href="'.$host.'stat/xml/player.xml" target=_blank>'.$host.'stat/xml/player.xml</a></p>
				<p class="text-primary"><a href="'.$host.'stat/xml/team.xml" target=_blank>'.$host.'stat/xml/team.xml</a></p>
				<p class="text-primary"><a href="'.$host.'stat/xml/videohighlights.xml" target=_blank>'.$host.'stat/xml/videohighlights.xml</a></p>
				<p class="text-primary"><a href="'.$host.'stat/xml/h2h.xml" target=_blank>'.$host.'stat/xml/h2h.xml</a></p>
				<p class="text-primary"><a href="'.$host.'stat/xml/standings.xml" target=_blank>'.$host.'stat/xml/standings.xml</a></p>
				<p class="text-primary"><a href="'.$host.'stat/xml/topscorers.xml" target=_blank>'.$host.'stat/xml/topscorers.xml</a></p>
				<p class="text-primary"><a href="'.$host.'stat/xml/live_commentaries.xml" target=_blank>'.$host.'stat/xml/live_commentaries.xml</a></p>
				<p class="text-primary"><a href="'.$host.'stat/xml/odds.xml" target=_blank>'.$host.'stat/xml/odds.xml</a></p>
				<p class="text-primary"><a href="'.$host.'stat/xml/odds2.xml" target=_blank>'.$host.'stat/xml/odds2.xml</a></p>
				<p class="text-primary"><a href="'.$host.'stat/xml/fixtures-results.xml" target=_blank>'.$host.'stat/xml/fixtures-results.xml</a></p>
			</div>
						</div>
					</div>';
			//Import data
			$data .= '<div class="col-lg-6">
						<div class="panel panel-warning">
							<div class="panel-heading">
								Import
							</div>
							<div class="panel-body">
				<p class="text-primary"><a href="'.base_url('xml/import_team/').'" target=_blank>'.base_url('xml/import_team/import/[team_id]').'</a></p>
				<p class="text-primary"><a href="'.base_url('xml/import_h2h/').'" target=_blank>'.base_url('xml/import_h2h').'</a></p>
				<p class="text-primary"><a href="'.base_url('xml/import_player/').'" target=_blank>'.base_url('xml/import_player').'</a></p>
				<p class="text-primary"><a href="'.base_url('xml/import_fixtures_results/').'" target=_blank>'.base_url('xml/import_fixtures_results/import/[league]').'</a></p>
				<p class="text-primary"><a href="'.base_url('xml/import_live_commentaries/').'" target=_blank>'.base_url('xml/import_live_commentaries').'</a></p>
				<p class="text-primary"><a href="'.base_url('xml/import_standings/').'" target=_blank>'.base_url('xml/import_standings').'</a></p>
				<p class="text-primary"><a href="'.base_url('xml/import_topscorers/').'" target=_blank>'.base_url('xml/import_topscorers').'</a></p>
				<p class="text-primary"><a href="'.base_url('xml/import_odds/').'" target=_blank>'.base_url('xml/import_odds').'</a></p>
			</div>
						</div>
					</div>';

			
			unset($list_fixture);
			unset($list_fixture_debug);

			$list_fixture[] = anchor(base_url('xml/import_fixtures_results/import/english'), 'English', array('target' => '_blank', 'title' => 'Import'));
			$list_fixture[] = anchor(base_url('xml/import_fixtures_results/import/spain'), 'Spain', array('target' => '_blank', 'title' => 'Import'));
			$list_fixture[] = anchor(base_url('xml/import_fixtures_results/import/germany'), 'Germany', array('target' => '_blank', 'title' => 'Import'));
			$list_fixture[] = anchor(base_url('xml/import_fixtures_results/import/italy'), 'Italy', array('target' => '_blank', 'title' => 'Import'));
			$list_fixture[] = anchor(base_url('xml/import_fixtures_results/import/france'), 'France', array('target' => '_blank', 'title' => 'Import'));
			$list_fixture[] = anchor(base_url('xml/import_fixtures_results/import/championship'), 'แชมป์เปี้ยนชิพ อังกฤษ', array('target' => '_blank', 'title' => 'Import'));

			$list_fixture_debug[] = anchor(base_url('xml/import_fixtures_results/debug/english'), 'English', array('target' => '_blank', 'title' => 'Debug'));
			$list_fixture_debug[] = anchor(base_url('xml/import_fixtures_results/debug/spain'), 'Spain', array('target' => '_blank', 'title' => 'Debug'));
			$list_fixture_debug[] = anchor(base_url('xml/import_fixtures_results/debug/germany'), 'Germany', array('target' => '_blank', 'title' => 'Debug'));
			$list_fixture_debug[] = anchor(base_url('xml/import_fixtures_results/debug/italy'), 'Italy', array('target' => '_blank', 'title' => 'Debug'));
			$list_fixture_debug[] = anchor(base_url('xml/import_fixtures_results/debug/france'), 'France', array('target' => '_blank', 'title' => 'Debug'));
			$list_fixture_debug[] = anchor(base_url('xml/import_fixtures_results/debug/championship'), 'แชมป์เปี้ยนชิพ อังกฤษ', array('target' => '_blank', 'title' => 'Debug'));

			$data .= '<div class="col-lg-6">
						<div class="panel panel-danger">
							<div class="panel-heading">
								Import Fixture
							</div>
							<div class="panel-body">
								<div class="col-lg-6">Import</div><div class="col-lg-6">Debug</div>
								<div class="col-lg-6">'.$list_fixture[0].'</div><div class="col-lg-6">'.$list_fixture_debug[0].'</div>
								<div class="col-lg-6">'.$list_fixture[1].'</div><div class="col-lg-6">'.$list_fixture_debug[1].'</div>
								<div class="col-lg-6">'.$list_fixture[2].'</div><div class="col-lg-6">'.$list_fixture_debug[2].'</div>
								<div class="col-lg-6">'.$list_fixture[3].'</div><div class="col-lg-6">'.$list_fixture_debug[3].'</div>
								<div class="col-lg-6">'.$list_fixture[4].'</div><div class="col-lg-6">'.$list_fixture_debug[4].'</div>
								<div class="col-lg-6">'.$list_fixture[5].'</div><div class="col-lg-6">'.$list_fixture_debug[5].'</div>
				
							</div>
						</div>
					</div>';

			unset($list_fixture);
			unset($list_fixture_debug);

			$list_fixture[] = anchor(base_url('xml/import_soccernew/query/home?cat=1204'), 'English', array('target' => '_blank', 'title' => 'query'));
			$list_fixture[] = anchor(base_url('xml/import_soccernew/query/d-1?cat=1204'), 'English D-1', array('target' => '_blank', 'title' => 'query'));
			$list_fixture[] = anchor(base_url('xml/import_soccernew/query/d-2?cat=1204'), 'English D-2', array('target' => '_blank', 'title' => 'query'));
			$list_fixture[] = anchor(base_url('xml/import_soccernew/query/d-3?cat=1204'), 'English D-3', array('target' => '_blank', 'title' => 'query'));
			$list_fixture[] = anchor(base_url('xml/import_soccernew/query/d-4?cat=1204'), 'English D-4', array('target' => '_blank', 'title' => 'query'));
			$list_fixture[] = anchor(base_url('xml/import_soccernew/query/d-5?cat=1204'), 'English D-5', array('target' => '_blank', 'title' => 'query'));
			$list_fixture[] = anchor(base_url('xml/import_soccernew/query/d-6?cat=1204'), 'English D-6', array('target' => '_blank', 'title' => 'query'));
			$list_fixture[] = anchor(base_url('xml/import_soccernew/query/d-7?cat=1204'), 'English D-7', array('target' => '_blank', 'title' => 'query'));
			$list_fixture[] = anchor(base_url('xml/import_match_event/query/d-1?cat=1204'), 'Event D-1', array('target' => '_blank', 'title' => 'Event'));
			$list_fixture[] = anchor(base_url('xml/import_match_event/query/d-2?cat=1204'), 'Event D-2', array('target' => '_blank', 'title' => 'Event'));

			$list_fixture_debug[] = anchor(base_url('xml/import_soccernew/query/d1?cat=1204'), 'English D+1', array('target' => '_blank', 'title' => 'query'));
			$list_fixture_debug[] = anchor(base_url('xml/import_soccernew/query/d2?cat=1204'), 'English D+2', array('target' => '_blank', 'title' => 'query'));
			$list_fixture_debug[] = anchor(base_url('xml/import_soccernew/query/d3?cat=1204'), 'English D+3', array('target' => '_blank', 'title' => 'query'));
			$list_fixture_debug[] = anchor(base_url('xml/import_soccernew/query/d4?cat=1204'), 'English D+4', array('target' => '_blank', 'title' => 'query'));
			$list_fixture_debug[] = anchor(base_url('xml/import_soccernew/query/d5?cat=1204'), 'English D+5', array('target' => '_blank', 'title' => 'query'));
			$list_fixture_debug[] = anchor(base_url('xml/import_soccernew/query/d6?cat=1204'), 'English D+6', array('target' => '_blank', 'title' => 'query'));
			$list_fixture_debug[] = anchor(base_url('xml/import_soccernew/query/d7?cat=1204'), 'English D+7', array('target' => '_blank', 'title' => 'query'));
			$list_fixture_debug[] = anchor(base_url('xml/import_topscorers/query/england'), 'English Topscorers', array('target' => '_blank', 'title' => 'query'));
			$list_fixture_debug[] = anchor(base_url('xml/import_topassits/query/england_assists'), 'English Topassits', array('target' => '_blank', 'title' => 'query'));
			$list_fixture_debug[] = anchor(base_url('xml/import_topcards/query/england_cards'), 'English Topcards', array('target' => '_blank', 'title' => 'query'));


			$data .= '<div class="col-lg-6">
						<div class="panel panel-success">
							<div class="panel-heading">
								Fixture & Event English
							</div>
							<div class="panel-body">
								<div class="col-lg-6">Prev</div><div class="col-lg-6">Next</div>';

			$allteam = count($list_fixture);
			for($i=0;$i<$allteam; $i++){

				// $image_properties = array(
				// 	'src'   => $logo,
				// 	'alt'   => $team_name,
				// 	'class' => 'post_images',
				// 	'width' => '80',
				// 	'height'=> '50',
				// 	'rel'   => 'lightbox'
				// );
				// $logo_img = img($image_properties);

				$data .= '<div class="col-lg-6">'.$list_fixture[$i].'</div>';
				if(isset($list_fixture_debug[$i]))
					$data .= '<div class="col-lg-6">'.$list_fixture_debug[$i].'</div>';

				// $data .= '<p class="text-primary"><a href="'.base_url('xml/import_team/import/'.$team_id).'" target=_blank>'.$logo_img.' '.$team_name.'</a></p>';
			}

			$data .= '</div>
					</div>
				</div>';

			$webtitle = 'XML data';

			$data = array(			
				//"ListSelect" => $ListSelect,
				"webtitle" => $webtitle,
				"user_agent" => $user_agent,
				"head" => $webtitle,
				"data" => $data,
				"content_view" => 'tool/view'			
			);
			$this->load->view('template',$data);

		}else{

			RefreshTo(base_url(), 1);
		}

	}

	public function getXML(){

		$data = array(			
			//"webtitle" => $webtitle,
			//"user_agent" => $user_agent,
			//"head" => $webtitle,
			//"data" => $data,
			"content_view" => 'tool/input'			
		);
		$this->load->view('template',$data);
	}

	public function savefile(){

		$url = '';

		if ($this->input->server('REQUEST_METHOD') === 'POST'){

			$data_store = array();
			$datainput = $this->input->post();
			$datenow = date('Y-m-d');
			//Debug($datainput);

			if(isset($datainput['url'])){
				//Debug($datainput);

				$url = $datainput['url'];
				$filename = $datainput['filename'];

				$res = $this->genarate->get_curl($url);
				//echo $res;

				$res = SaveFile($res, $filename, false, 'xml/'.$datenow.'/');
				echo "Save file $res success.";
			}

			echo "<META http-equiv='refresh' content='3;URL=".base_url('xml/getXML')."'>";
			exit();

		}
	}

	public function loadxml($file = ''){

		if($file == '') $file = 'fixtures-results.xml';
		$url = base_url('uploads/XML/'.$file );
		//Debug($url);
		$xmlelement =  simplexml_load_file($url);
		Debug($xmlelement);
		//$xmlelement =  $this->api_model->get_curl($url);
		//$string = xml_convert($xmlelement);
		//Debug($string);
	}

	public function load_base64img($src, $width = 0, $height = 0){
		//width:100px;height:100px;
		$html = "<img style='display:block;' class='base64image' src='data:image/jpeg;base64, $src' />";
      	return $html;
	}

	public function import_team(){

		$this->load->library('parser');
		$this->load->model('coach_model');

		if($this->uri->segment(3) == 'json') header('Content-Type: application/json');
		$query = $import = $debug = $player = $coach_id = 0;
		$html = '';
		
		$item_data = $team_data = $stadium = $team_league = $player_team = $tmp = $player_team_tmp = array();
		if($this->uri->segment(3) == 'query') $query = 1;
		if($this->uri->segment(3) == 'import') $import = 1;
		if($this->uri->segment(3) == 'debug') $debug = 1;
		if($this->uri->segment(3) == 'player') $player = 1;

		if($this->uri->segment(4) == '') 
			$teamid = 9260;
		else
			$teamid = $this->uri->segment(4);

		$league_id = $this->uri->segment(5);

		$feed_team = $this->xml_host.$this->xml_team.$teamid;
		if($debug == 1){

			Debug($feed_team);
		}
		$obj_data = $this->xml_model->get_team($feed_team);
		//Debug($obj_data);
		//die();
		if($obj_data){
			foreach($obj_data as $key => $val){
				if($key == 'venue_image' || $key == 'image'){

					$item_data[$key] = $val;
					$path = ($key == 'venue_image') ? 'uploads/stadium/' : 'uploads/teamlogo/';
					$img = $this->load_base64img($val);
					//if($debug == 1) $palyer_data[$key] = $img;

					$file_team = $team_data['team_id'].'.txt';
					$file_stadium = $stadium['stadium_id'].'.txt';

					//***** Save to File *******/
					/*
					if($key == 'image')
						SaveFile($val, $file_team, false, $path);
					else
						SaveFile($val, $file_stadium, false, $path);
					*/

				}else if($key == 'coach'){

					if(is_array($val)){

						$coach_id = $val[0]['id'];
						$coach_team_id = $val[0]['team_id'];
						$coach_name = $val[0]['name'];

						if($query == 1 || $import == 1){
							$res = $this->coach_model->get_data($coach_id);
							if(empty($res)){

								$this->coach_model->store(0, $val[0]);
								echo "<br>".$this->db->last_query();
							}

							$res = $this->coach_model->get_data($coach_id, '_coach');
							if(empty($res)){

								$this->coach_model->store(0, $val[0], '_coach');
								echo "<br>".$this->db->last_query()."<hr>";
							}
						}
					}
				/*}else if($key == 'player_team'){

					$all_player = count($val);
					for($i=0;$i<$all_player;$i++){

						$rows = $val[$i];
						Debug($rows);
					}
					die();


				}else if($key == 'statistics'){*/

				}else{
					//echo "$key => $val<br>";
					if(is_array($val)){
		
						foreach($val as $key2 => $val2){
							$tmp[$key2] = $val2;
						}
					
					}else{

						$team_data[$key] = $val;
						if($key == 'venue_id') $stadium['stadium_id'] = $val;
						if($key == 'venue_name') $stadium['stadium_name'] = $val;
						if($key == 'venue_address') $stadium['location'] = $val;
						if($key == 'venue_capacity') $stadium['capacity'] = $val;
						$item_data[$key] = $val;
					}
				}
			}

			//Debug($team_data);
			//die();

			//$this->xml_model->update('_stadium', 'stadium_id', $stadium);
			/****************check stadium*****************/
			if($query == 1 || $import == 1){
				//echo "Check Stadium ".$stadium['stadium_name']." Importing...<br>";
				$res = $this->xml_model->chkupdate_stadium($stadium);
				//Debug($this->db->last_query());

			}
		}

		//Debug($tmp);
		//echo "<hr>";
		//$json_data = json_encode($xmlelement);
		//$json_data = json_decode($json_data);
		//unset($player_team_tmp);

		$player_team_tmp = $obj_data['squad']['player_team'];
		// echo "<li>player_team (".count($player_team_tmp).")</li><hr>";
		// Debug($player_team_tmp);
		// die();

		for($i=0;$i<count($player_team_tmp);$i++){

			$player_team[$i]['profile_id'] = intval($player_team_tmp[$i]['id']);
			$player_team[$i]['team_id'] = intval($player_team_tmp[$i]['team_id']);
			$player_team[$i]['name'] = (string)$player_team_tmp[$i]['name'];
			$player_team[$i]['number'] = (string)$player_team_tmp[$i]['number'];
			$player_team[$i]['age'] = intval($player_team_tmp[$i]['age']);
			$player_team[$i]['position'] = (string)$player_team_tmp[$i]['position'];
			// $player_team[$i]['minutes'] = $player_team_tmp[$i]['minutes'];
			// $player_team[$i]['appearences'] = $player_team_tmp[$i]['appearences'];
			// $player_team[$i]['injured'] = (string)$player_team_tmp[$i]['injured'];

			// $player_team[$i]['lineups'] = $player_team_tmp[$i]['lineups'];
			// $player_team[$i]['substitute_in'] = intval($player_team_tmp[$i]['substitute_in']);
			// $player_team[$i]['substitute_out'] = intval($player_team_tmp[$i]['substitute_out']);
			// $player_team[$i]['substitutes_on_bench'] = intval($player_team_tmp[$i]['substitutes_on_bench']);
			// $player_team[$i]['goals'] = intval($player_team_tmp[$i]['goals']);
			// $player_team[$i]['assists'] = intval($player_team_tmp[$i]['assists']);
			// $player_team[$i]['yellowcards'] = intval($player_team_tmp[$i]['yellowcards']);
			// $player_team[$i]['yellowred'] = intval($player_team_tmp[$i]['yellowred']);
			// $player_team[$i]['redcards'] = intval($player_team_tmp[$i]['redcards']);

			//$player_team[$i]['lastupdate_date'] = date('Y-m-d H:i:s');
			if($query == 1){

				if($player_team[$i]['profile_id'] > 0){

					$this->xml_model->update('_xml_team_player', 'profile_id', $player_team[$i]);
					Debug($this->db->last_query());
					$this->xml_model->update('_team_player', 'profile_id', $player_team[$i]);
					Debug($this->db->last_query());					
				}

			}
		}

		// if(unset($team_id))
		$team_id = $teamid;

		if($import == 1){

			$data_clear['team_id'] = $teamid;
			$this->xml_model->delete_data('_xml_team_player', $data_clear);
			Debug($this->db->last_query());
			$this->xml_model->delete_data('_team_player', $data_clear);
			Debug($this->db->last_query());
			//Debug($player_team);
			// die();
			// debug($player_team[0]);
			$res = $this->xml_model->ChkActive('_xml_team_player', 'team_id', $player_team[0]);
			// Debug($this->db->last_query());
			// debug($res);
			// die();
			if(!$res) {

				$this->xml_model->import_batch('_xml_team_player', $player_team);
				Debug($this->db->last_query());
				$this->xml_model->import_batch('_team_player', $player_team);
				Debug($this->db->last_query());
				//Debug($player_team);
				//die();
			}
		}

		if($debug == 1){

			$item_data['venue_image'] = $this->load_base64img($obj_data['venue_image']);
			$item_data['image'] = $this->load_base64img($obj_data['image']);
		}

		$item_data['coach'] = $obj_data['coach'];
		$item_data['player_team'] = $player_team;

		if(isset($obj_data['transfers']['player_in'])) $item_data['transfers']['player_in'] = $obj_data['transfers']['player_in'];
		if(isset($obj_data['transfers']['player_out'])) $item_data['transfers']['player_out'] = $obj_data['transfers']['player_out'];
		//$item_data['scoring_minutes'] = $obj_data['scoring_minutes'];
		if(isset($obj_data['statistics'])) $item_data['statistics'] = $obj_data['statistics'];
		if(isset($obj_data['sidelined'])) $item_data['sidelined'] = $obj_data['sidelined'];

		$coach = $obj_data['coach'];

		if(isset($obj_data['transfers']['player_in'])) $player_in = $obj_data['transfers']['player_in']; else $player_in = null;
		if(isset($obj_data['transfers']['player_out'])) $player_out = $obj_data['transfers']['player_out']; else $player_out = null;
		
		$sidelined = (isset($obj_data['sidelined'])) ? $obj_data['sidelined']:null;
		$statistics = (isset($obj_data['statistics'])) ? $obj_data['statistics']:null;

		//$team['league_id'] = $teamid;
		//$team['league_id'] = $teamid;

		//if(isset($obj_data['statistics'])){
			//echo "statistics";
			//Debug($obj_data['statistics']);
		//}

		/**************Create Table*****************/
		//echo "<hr>Start Alter table<hr>";
		//Debug($obj_data);	
		//$this->xml_model->create_table('team', $obj_data);

		//$this->xml_model->create_table('statistics', $obj_data);
		/*********************************************/

		/************** INSERT DB Team**************/
		if($import == 1){
			$this->load->model('team_model');

			if(intval($league_id) == 0) 
				$league_id = 1056;

			//Debug($team_data); //team_data
			$input_team = array(
				'team_id' => $team_data['team_id'],
				'team_name_en' => $team_data['name'],
				'league_id' => intval($league_id),
				'manager_id' => intval($coach_id),
				'stadium_id' => intval($team_data['venue_id'])
			);
			//Debug($input_team);
			//die();

			$chkteam = $this->team_model->get_data(0, $team_data['team_id']);
			if(!$chkteam) {
				//$this->xml_model->import('_xml_team', $team_data);
				$this->xml_model->import('_team', $input_team);
			}else{
				$this->xml_model->update('_team', 'team_id', $input_team);
			}
		}
		//if($query == 1){
			//$this->xml_model->update('_xml_team', $team_data);
			//$this->xml_model->update('_team', 'team_id', $team_data);
		//}

		/************** table _team_league **************/
		// echo "<hr>team_league<br>";
		for($i=0;$i<count($tmp);$i++){

			$team_league['league_id'] = @intval($tmp[$i]);
			$team_league['team_id'] = intval($teamid);
			// Debug($team_league);
			if($import == 1 && $team_league['league_id'] > 0 && $team_league['team_id'] > 0){

				$res = $this->xml_model->get_team_league($team_league['team_id'], $team_league['league_id']);
				if(empty($res)){
					$this->xml_model->import('_team_league', $team_league);
					Debug($this->db->last_query());
				}
			}
		}

		/************** Player**************/
		if($import == 1){

			//$this->xml_model->import_batch('_xml_team_player', $player_team);
			//Debug($player_team);
			//$this->xml_model->import_batch('_team_player', $player_team);
			//Debug($this->db->last_query());
			//$this->xml_model->chkupdate_team('_team_player', 'id', $player_team);	//Insert
			//$this->xml_model->update('_team_player', 'id', $player_team);	//Update
		
			/************** Coach**************/
			//Debug($coach); //coach

			if(isset($coach))
				if(isset($coach['name'])) {
					$this->xml_model->import_batch('_xml_coach', $coach);
					Debug($this->db->last_query());
				}

			/************** Transfers**************/
			//Debug($player_in); //transfers player_in
			if(isset($player_in)){
				$this->xml_model->import_batch('_xml_transfers', $player_in);
				Debug($this->db->last_query());
			}

			//Debug($player_out); //transfers player_out
			if(isset($player_out)){
				$this->xml_model->import_batch('_xml_transfers', $player_out);
				Debug($this->db->last_query());
			}

			/************** Transfers**************/
			//Debug($statistics);
			if(isset($statistics['scoring_minutes'])){
				$this->xml_model->import_batch('_xml_scoring_minutes', $statistics['scoring_minutes']);
				Debug($this->db->last_query());
			}
			UNSET($statistics['scoring_minutes']);
		}
		if($import == 1 || $query == 1){

			/************** Statistics **************/
			echo "<br>(statistics)<br>";
			Debug($statistics);
			if(isset($statistics)){

				if(empty($statistics['team_id'])){
					$statistics['team_id'] = intval($teamid);
				}

				if(!$this->xml_model->ChkActive('_xml_statistics', 'team_id', $statistics)) {
					
					$this->xml_model->import('_xml_statistics', $statistics);
					Debug($this->db->last_query());
				}else{

					$this->xml_model->update('_xml_statistics', 'team_id', $statistics);
					Debug($this->db->last_query());
				}
			}

			/************** Sidelined **************/
			//Debug($sidelined);
			if(isset($sidelined)){

				//Delete
				$data_clear['team_id'] = intval($teamid);
				$this->xml_model->delete_data('_xml_sidelined', $data_clear);
				
				//Import Data new
				$this->xml_model->import_batch('_xml_sidelined', $sidelined);
				Debug($this->db->last_query());
			}
		}

		//Update and Import Player team
		if($player == 1 && $player_team){
			$this->load->model('player_model');

			$html = '<table width="90%" align=center border=1 style="background-color: white;">';
			//Debug($player_team);
			$script = '<script type="text/javascript">
			$( document ).ready(function() {';

			$allplayer = count($player_team);
			for($i=0;$i<$allplayer;$i++){
				
				$res_player = $this->player_model->get_profile($player_team_tmp[$i]['id']);
				if($res_player)
					$waiting = '<span class="blue">Exists Player</span>';
				else
					$waiting = '<span class="red">waiting...</span>';

				if(!$res_player)
					$gen_player = base_url('xml/import_player/import/'.$player_team_tmp[$i]['id']);
				else
					$gen_player = base_url('xml/import_player/query/'.$player_team_tmp[$i]['id']);

				$btn_gen = '<a href="'.$gen_player.'" target="_blank" class="btn btn-default" title="Gen player">
                        <i class="ace-icon fa fa-futbol-o bigger-130"></i></a>';

				$html .= '<tr>
				<td>'.$player_team_tmp[$i]['id'].'</td>
				<td>'.$player_team_tmp[$i]['number'].'</td>
				<td>'.$player_team_tmp[$i]['name'].' </td>
				<td>'.$player_team_tmp[$i]['age'].'</td>
				<td>'.$player_team_tmp[$i]['position'].'</td>
				<td> Import '.$btn_gen.'</td>
				<td><div id="player'.$player_team_tmp[$i]['id'].'">'.$waiting.'</div></td>
				</tr>';

				if(!$res_player){
					$script .= '
					chkplayer('.$player_team_tmp[$i]['id'].');';
				}else{
					$script .= '
					chkupdate('.$player_team_tmp[$i]['id'].');';
				}
			}
			$script .= '
			});
			function chkplayer(id){
				//alert("'.base_url("xml/import_player/import").'/" + id);
				$.ajax({
					type: \'POST\',
					url: "'.base_url("xml/import_player/import").'/" + id,					
					cache: false,
					success: function(data){
						$(\'#player\' + id).html(data);
					}
				});
			}			
			function chkupdate(id){
				//alert("'.base_url("xml/import_player/query").'/" + id);
				$.ajax({
					type: \'POST\',
					url: "'.base_url("xml/import_player/query").'/" + id,					
					cache: false,
					success: function(data){
						$(\'#player\' + id).html(data);
					}
				});
			}
			</script>';
			$html .= '</table>';
		}

		if($debug == 1){
			Debug($item_data);
		}

		if($item_data){
			$item['head']['code'] = 200;
			$item['head']['msg'] = 'Success';
		}else{
			$item['head']['code'] = 404;
			$item['head']['msg'] = 'File not found';
		}
				
		if($this->uri->segment(3) == 'json'){
			$item['data'] = $item_data;
			echo json_encode($item);
		}
		$language = $this->lang->language;

		if($player == 1){
			$ListSelect = $this->genarate->user_menu($this->session->userdata('admin_type'));
			$data = array(
				"html" => $html,
				"script" => $script,
				"breadcrumb" => 'Player',
				"content_view" => 'xml/view',
				"ListSelect" => $ListSelect,
			);
			$this->parser->parse('template',$data);
    	}
	}

	public function import_h2h(){

		header("Access-Control-Allow-Origin: *");
		//header('Cache-Control: no-cache');

		$this->load->model('program_stat_model');

		if($this->uri->segment(3) == 'json') header('Content-Type: application/json');
		$query = $debug = $program_id = 0;

		if($this->uri->segment(3) == 'query') $query = 1;
		if($this->uri->segment(3) == 'debug') $debug = 1;

		$home_id = $this->uri->segment(4);
		$away_id = $this->uri->segment(5);
		$league_id = $this->uri->segment(6);
		//$feed_fixtures = base_url('uploads/XML/fixtures-results.xml');

		//if($query == 1)

		if($home_id != '' && $away_id != ''){
			$feed_h2h = $this->xml_host.'h2h/'.$home_id.'/'.$away_id;
		}else
			$feed_h2h = $this->xml_host.$this->xml_h2h;

		if($debug == 1) Debug($feed_h2h);

		$obj_data = $xml_data = array();
		$xml_data = $this->xml_model->get_h2h($feed_h2h);

		if($this->input->get('program_id')){
			$program_id = $this->input->get('program_id');
		}

		//echo "<hr>";
		//Debug($xml_data);
		//die();
		/*if($xml_data){
			foreach($xml_data as $key => $val){
				if($key == 'venue_image' || $key == 'image'){
					$path = ($key == 'venue_image') ? 'stadium' : 'teamlogo';
					echo $this->load_base64img($val);
					SaveFile($val, $team_data['team_id'], false, $path);
				}else{
					if(is_array($val))
						echo "$key => Array<br>";
					else{						
						$data = $val;
						//echo "$key => $data<br>";
						$obj_data[$key] = $data;
					}
				}
			}
		}*/

		if($debug == 1){
			echo "<hr>";
			Debug($xml_data);
		}

		if($query == 1 && $xml_data){

			echo "<hr>";
			$obj_data['home_id'] = $home_id;
			$obj_data['away_id'] = $away_id;
			$obj_data['league_id'] = $league_id;
			$obj_data['match_id'] = intval($program_id);
			$obj_data['json'] = serialize($xml_data);

			//Debug($obj_data);
			try{
				//$res = $this->xml_model->import('_h2h', $obj_data);
				$res = $this->xml_model->chk_h2h($home_id, $away_id, $league_id, $obj_data);
				// Debug($this->db->last_query());
				if($res){
					Debug($obj_data);
					echo "Success...";
				} 

			} catch (SoapFault $fault) {
				die('Duplicate data...');
			}
		}

		if($xml_data){
			$item['head']['code'] = 200;
			$item['head']['msg'] = 'Success';
		}else{
			$item['head']['code'] = 404;
			$item['head']['msg'] = 'File not found';
		}
		
		$item['data'] = $xml_data;
		if($this->uri->segment(3) == 'json') echo json_encode($item);
	}

	public function import_player(){

		if($this->uri->segment(3) == 'json') header('Content-Type: application/json');

		$query = $import = $debug = 0;
		if($this->uri->segment(3) == 'query') $query = 1;
		if($this->uri->segment(3) == 'import') $import = 1;
		if($this->uri->segment(3) == 'debug') $debug = 1;

		if($this->uri->segment(4) == '') 
			$playerid = 193;
		else
			$playerid = $this->uri->segment(4);

		if($this->uri->segment(5) == '') 
			$team_id = 0;
		else
			$team_id = $this->uri->segment(5);

		$feed_url = $this->xml_host.$this->xml_player.$playerid;
		$palyer_data = $xml_data = array();
		if($debug == 1){
			Debug($feed_url);
		}
		$xml_data = $this->xml_model->get_player($feed_url);
		//echo "<hr>";
		//Debug($xml_data['player']);

		if($xml_data){
			foreach($xml_data as $key => $val){
				//echo "<b>$key</b><hr>";
				//if(!is_array($val)) echo "$key => $val<br>";
				//if($key == 'image'){
					//$path = 'uploads/player/';
					//$img = $this->load_base64img($val);
					//echo "$key => $img<br>";
					//echo $path.$val;
					//SaveFile($val, $palyer_data['id'], false, $path);
				//}else{
					if(is_array($val)){
						//echo "$key => Array<br>";
						foreach($val as $key2 => $val2){
							//echo "$key2 => $val2<br>";
							if($key2 == 'image'){

								if($team_id > 0){

									$path = 'uploads/player/'.$team_id.'/';
								}else{

									// $path = 'uploads/player/'.$palyer_data['teamid'].'/';
									$path = 'uploads/player/';									
								}



								$filename = $val2;
								$img = $this->load_base64img($filename);
								//echo "$key => $img<br>";
								//echo $path.$val2;
								SaveFile($val2, $palyer_data['id'].'.txt', false, $path);
								if($debug == 1) $palyer_data[$key2] = $img;
							}else{
								$palyer_data[$key2] = $val2;
							}
						}
					}else{
						//$data = $val;
						//echo "$key => $data<br>";
						$palyer_data[$key] = $data;
					}
				//}
			}
		}
		
		if($debug == 1){
			echo "<hr>";
			Debug($palyer_data);
		}

		if($import == 1){

			$sidelined = $palyer_data['sidelined'];
			$statistic = $palyer_data['statistic'];
			$statistic_cups = $palyer_data['statistic_cups'];
			$statistic_cups_intl = $palyer_data['statistic_cups_intl'];
			$statistic_intl = $palyer_data['statistic_intl'];
			$trophies = $palyer_data['trophies'];
			$transfers = $palyer_data['transfers'];
			$overall_clubs = $palyer_data['overall_clubs'];

			unset($palyer_data['image']);
			unset($palyer_data['sidelined']);
			unset($palyer_data['statistic']);
			unset($palyer_data['statistic_cups']);
			unset($palyer_data['statistic_cups_intl']);
			unset($palyer_data['statistic_intl']);
			unset($palyer_data['trophies']);
			unset($palyer_data['transfers']);
			unset($palyer_data['overall_clubs']);

			$palyer_data['birthdate'] = date('Y-m-d', strtotime(str_replace('/', '.', $palyer_data['birthdate'])));
			// Debug($palyer_data);

			$palyer_data['profile_id'] = $palyer_data['id'];
			unset($palyer_data['id']);
			$this->xml_model->import('_xml_player_profile', $palyer_data);

			$palyer_data['create_date'] = date('Y-m-d H:i:s');
			$palyer_data['create_by'] = $this->session->userdata('admin_id');

			//Import Databases
			$this->xml_model->import('_player_profile', $palyer_data);
			echo '<span class="green"><b>Import Success</b></span>';
		}

		if($query == 1){

			$palyer_data['profile_id'] = $palyer_data['id'];
			unset($palyer_data['id']);
			//Update data
			// Debug($palyer_data);
			list($d, $m, $y) = explode('/', $palyer_data['birthdate']);
			// $palyer_data['birthdate'] = date('Y-m-d', strtotime($palyer_data['birthdate']));
			$palyer_data['birthdate'] = "$y-$m-$d";

			$sidelined = $palyer_data['sidelined'];
			$statistic = $palyer_data['statistic'];			
			$statistic_cups = $palyer_data['statistic_cups'];
			$statistic_cups_intl = $palyer_data['statistic_cups_intl'];
			$statistic_intl = $palyer_data['statistic_intl'];
			$trophies = $palyer_data['trophies'];
			$transfers = $palyer_data['transfers'];
			$overall_clubs = $palyer_data['overall_clubs'];

			unset($palyer_data['sidelined']);
			unset($palyer_data['statistic']);
			unset($palyer_data['statistic_cups']);
			unset($palyer_data['statistic_cups_intl']);
			unset($palyer_data['statistic_intl']);
			unset($palyer_data['trophies']);
			unset($palyer_data['transfers']);
			unset($palyer_data['overall_clubs']);
			Debug($palyer_data);

			$this->xml_model->chkupdate_data('_player_profile', 'profile_id', $palyer_data);
			echo '<span class="green"><b>Update Success</b></span>';
			// Debug($this->db->last_query());
		}

		if($palyer_data){
			$item['head']['code'] = 200;
			$item['head']['msg'] = 'Success';
		}else{
			$item['head']['code'] = 404;
			$item['head']['msg'] = 'File not found';
		}
		
		$item['data'] = $palyer_data;
		if($this->uri->segment(3) == 'json') echo json_encode($item);
	}

	public function import_fixtures_results(){
		$import_match = $xml_data = $stadium = $team = $data_update = $data_program = array();
		$number_match = $query = $debug = $import = $tournament_id = 0;
		$cat = $tournament_name = $season = $country = '';
		$week = $referee = $match_goals = null;

		if($this->uri->segment(3) == 'json') header('Content-Type: application/json');

		switch($this->uri->segment(4)){

			case 'france': $feed_url = $this->xml_host.'soccerfixtures/france/Ligue1'; break;
			case 'germany': $feed_url = $this->xml_host.'soccerfixtures/germany/Bundesliga'; break;
			case 'italy': $feed_url = $this->xml_host.'soccerfixtures/italy/SerieA'; break;
			case 'spain': $feed_url = $this->xml_host.'soccerfixtures/spain/Primera'; break;
			case 'championship': $feed_url = $this->xml_host.'soccerfixtures/england/Championship'; break;
			case 'english': $feed_url = $this->xml_host.$this->xml_fixtures_results; break;
			case 'SerieB': $feed_url = $this->xml_host.'soccerfixtures/italy/SerieB'; break;
			case 'france-Ligue2': $feed_url = $this->xml_host.'soccerfixtures/france/Ligue2'; break;
			case 'spain-Segunda': $feed_url = $this->xml_host.'soccerfixtures/spain/Segunda'; break;
			case 'scotland-Division1': $feed_url = $this->xml_host.'soccerfixtures/scotland/Division1'; break;
			case 'holland-Eredivisie': $feed_url = $this->xml_host.'soccerfixtures/holland/Eredivisie'; break;
			case 'portugal-PortugueseLiga': $feed_url = $this->xml_host.'soccerfixtures/portugal/PortugueseLiga'; break;
			case 'thailand-PremierLeague': $feed_url = $this->xml_host.'soccerfixtures/thailand/PremierLeague'; break;
			case 'worldcup': $feed_url = $this->xml_host.'soccerfixtures/worldcup/WorldCup'; break;
			default:

				$feed_url = $this->xml_host.$this->xml_fixtures_results; break;
				// $feed_url = $this->xml_host.'soccerfixtures/england/'.trim($this->uri->segment(4)); break;
			break;
		}

		if($this->uri->segment(3) == 'debug') $debug = 1;		
		if($this->uri->segment(3) == 'import') $import = 1;
		if($this->uri->segment(3) == 'query') $query = 1;
		
		if($this->input->get('cat')) 
			$cat = $this->input->get('cat');

		if($cat != '')
			$feed_url .= '?cat='.$cat;

		// Debug($this);
		// Debug($feed_url);
		// die();
		$xml_data = $this->xml_model->get_fixtures_results($feed_url, $query);

		if($debug == 1){

			Debug($feed_url);
			// Debug($xml_data);
			//echo json_encode($xml_data);
		}
		// die();

		if($xml_data){
			foreach($xml_data as $key => $val){

				//echo "<b>$key => $val</b><hr>";
				if($key == 'country') $country = $val;
				if($key == 'id') $tournament_id = $val;
				if($key == 'league') $tournament_name = $val;
				if($key == 'season') $season = $val;

				if(is_array($val)){ //week

					$i=0;
					// Debug($val);
					// die();
					$allm = count($val);
					
					for($i=0;$i<$allm;$i++){

						$stage_name = $val[$i]['stage_name'];
						$stage_round = $val[$i]['stage_round'];
						$gid = $val[$i]['gid'];
						$stage_id = $val[$i]['stage_id'];
						$is_current = $val[$i]['is_current'];
						
						// Debug($val[$i]['stage_name']);
						// Debug($val[$i]['stage_round']);
						// Debug($val[$i]['stage_id']);

						if(isset($val[$i]['week'])){ //week
							// Debug($val[$i]['week']);

							$num_week = count($val[$i]['week']);
							// echo "($num_week)<br>";
							for($j=0;$j<$num_week;$j++){

								$week[$j] = $val[$i]['week'][$j];

								$number_match = $all_match = count($week[$j]['match']);
								// echo "($all_match)<br>";
								// Debug($week[$j]['match']);

								for($k=0;$k<$all_match;$k++){
									$goals = $lineups = $substitutions = $match_lineup = null;
									$rows = $week[$j]['match'][$k];
									// Debug($rows);

									$data_update['match_id'] = $rows['id'];
									$data_update['stage_id'] = $stage_id;
									$data_update['sport'] = 'soccer';
									$data_update['tournament_id'] = $tournament_id;
									$data_update['tournament_name'] = $tournament_name;
									$data_update['week'] = $rows['week'];
									$data_update['match_status'] = $rows['status'];
									$data_update['match_datetime'] = date('Y-m-d H:i:s', strtotime(str_replace('.', '-', ($rows['date'] . " " . $rows['time']))));
									$data_update['static_id'] = $rows['static_id'];

									if($rows['venue_id'] > 0){
										$stadium['stadium_id'] = $data_update['stadium_id'] = intval($rows['venue_id']);
										$stadium['stadium_name'] = $data_update['stadium'] = $rows['venue'];										
									}

									// $data_update['attendance'] = $rows['attendance'];
									$data_update['time'] = $rows['time'];
									// $data_update['referee'] = $rows['referee'];
									$data_update['hteam_id'] = $rows['localteam']['id'];
									$data_update['hteam'] = $rows['localteam']['name'];

									if($rows['localteam']['score'] != '')
										$data_update['hgoals'] = $rows['localteam']['score'];

									$data_update['ateam_id'] = $rows['visitorteam']['id'];
									$data_update['ateam'] = $rows['visitorteam']['name'];

									if($rows['visitorteam']['score'] != '')
										$data_update['agoals'] = $rows['visitorteam']['score'];

									$stadium['stadium_city'] = $rows['venue_city'];


									// if(isset($rows['goals']))
										$goals = @$rows['goals'];
									
									// if(isset($rows['lineups']))
										$lineups = @$rows['lineups'];

									// if(isset($rows['substitutions']))
										// $substitutions = $rows['substitutions'];
									
									// Debug($goals);
									// Debug($lineups);
									// Debug($substitutions);
									// die();

									if(isset($lineups['home_player'])){

										unset($match_lineup);
										// Debug($lineups['home_player']);
										$number_player = count($lineups['home_player']);
										for($l=0;$l<$number_player;$l++){

											$player = $lineups['home_player'][$l];

											$match_lineup[$l]['match_id'] = $data_update['match_id'];
											$match_lineup[$l]['team_id'] = $data_update['hteam_id'];
											$match_lineup[$l]['player_id'] = $player['id'];
											$match_lineup[$l]['number'] = $player['number'];
											$match_lineup[$l]['name'] = $player['name'];
											$match_lineup[$l]['booking'] = $player['booking'];

											// Debug($match_lineup);
											// $this->xml_model->chkupdate_data('_xml_match_lineup', );
										}
										if($query == 1){
											$data_del['match_id'] = $data_update['match_id'];
											$this->xml_model->delete_data('_xml_match_lineup', $data_del);

											$this->xml_model->import_batch('_xml_match_lineup', $match_lineup);
											Debug($this->db->last_query());
											Debug('Import _xml_match_lineup home');
										}
									}

									if(isset($lineups['away_player'])){

										unset($match_lineup);
										// Debug($lineups['away_player']);
										$number_player = count($lineups['away_player']);
										for($l=0;$l<$number_player;$l++){

											$player = $lineups['away_player'][$l];

											$match_lineup[$l]['match_id'] = $data_update['match_id'];
											$match_lineup[$l]['team_id'] = $data_update['ateam_id'];
											$match_lineup[$l]['player_id'] = $player['id'];
											$match_lineup[$l]['number'] = $player['number'];
											$match_lineup[$l]['name'] = $player['name'];
											$match_lineup[$l]['booking'] = $player['booking'];

											// Debug($match_lineup);
										}
										if($query == 1){
											// $data_del['match_id'] = $data_update['match_id'];
											// $this->xml_model->delete_data('_xml_match_lineup', $data_del);

											$this->xml_model->import_batch('_xml_match_lineup', $match_lineup);
											Debug($this->db->last_query());
											Debug('Import _xml_match_lineup away');
										}
									}

									//substitutions
									if(isset($rows['substitutions']['home_player'])){

										unset($substitutions);
										// Debug($lineups['home_player']);
										$number_player = count($rows['substitutions']['home_player']);
										for($l=0;$l<$number_player;$l++){
											
											$player = $rows['substitutions']['home_player'][$l];

											$substitutions[$l]['match_id'] = $data_update['match_id'];
											$substitutions[$l]['static_id'] = $data_update['static_id'];
											$substitutions[$l]['tournament_id'] = $tournament_id;
											$substitutions[$l]['team_id'] = $data_update['hteam_id'];
											$substitutions[$l]['on_id'] = $player['player_in_id'];
											$substitutions[$l]['on_number'] = $player['player_in_number'];
											$substitutions[$l]['on_name'] = $player['player_in_name'];
											$substitutions[$l]['on_booking'] = $player['player_in_booking'];
											$substitutions[$l]['off_name'] = $player['player_out_name'];
											$substitutions[$l]['minute'] = $player['minute'];

											// Debug($substitutions);
										}
										if($query == 1){
											$data_del['match_id'] = $data_update['match_id'];
											$this->xml_model->delete_data('_xml_match_substitutions', $data_del);

											$this->xml_model->import_batch('_xml_match_substitutions', $substitutions);
											Debug($this->db->last_query());
											Debug('Import _xml_match_substitutions home');
										}
									}

									if(isset($rows['substitutions']['away_player'])){

										unset($substitutions);
										// Debug($lineups['home_player']);
										$number_player = count($rows['substitutions']['away_player']);
										for($l=0;$l<$number_player;$l++){

											$player = $rows['substitutions']['away_player'][$l];

											$substitutions[$l]['match_id'] = $data_update['match_id'];
											$substitutions[$l]['static_id'] = $data_update['static_id'];
											$substitutions[$l]['tournament_id'] = $tournament_id;
											$substitutions[$l]['team_id'] = $data_update['ateam_id'];
											$substitutions[$l]['on_id'] = $player['player_in_id'];
											$substitutions[$l]['on_number'] = $player['player_in_number'];
											$substitutions[$l]['on_name'] = $player['player_in_name'];
											$substitutions[$l]['on_booking'] = $player['player_in_booking'];
											$substitutions[$l]['off_name'] = $player['player_out_name'];
											$substitutions[$l]['minute'] = $player['minute'];

											// Debug($substitutions);
										}
										if($query == 1){
											// $data_del['match_id'] = $data_update['match_id'];
											// $this->xml_model->delete_data('_xml_match_substitutions', $data_del);

											$this->xml_model->import_batch('_xml_match_substitutions', $substitutions);
											Debug($this->db->last_query());
											Debug('Import _xml_match_substitutions away');
										}

									}

									if(isset($rows['referee_id'])){
										unset($referee);
										$referee['referee_id'] = $rows['referee_id'];
										$referee['referee_name'] = $rows['referee_name'];

										if($query == 1){
											// Debug($referee);

											if($referee['referee_id'] > 0){
												$this->xml_model->chkupdate_data('_referee', 'referee_id', $referee);
												Debug($this->db->last_query());		
											}
										}

										if(@$referee['referee_id'] > 0){
											$data_update['referee_id'] = $rows['referee_id'];
											$data_update['referee'] = $rows['referee_name'];
										}

									}

									// die();

									if($debug == 1){

										Debug($data_update);
										// Debug($stadium);
									}

									//*********************Update DATA
									if($query == 1){

										if($stadium['stadium_id'] > 0){

											$this->xml_model->chkupdate_stadium($stadium);
											Debug($this->db->last_query());
										}

										if($data_update['match_id'] > 0){

											$this->xml_model->chkupdate_program($data_update['match_id'], $data_update);
											Debug($this->db->last_query());
										}
										
										unset($stadium);
										unset($data_update);
									}
								}

								/*********************Update DATA
								if($query == 1){

									if($week[$i]['match'][$j]['match_id'] > 0){

										Debug($week[$i]['match'][$j]);
										echo "Update...";
										$this->xml_model->update('_xml_match', 'match_id', $week[$i]['match'][$j]);
										Debug($this->db->last_query());
									}
								}*/

							}

						}

						if(isset($val[$i]['match'])){ //match

							$number_match = $all_match = count($val[$i]['match']);
							// echo "($all_match)<br>";
							// Debug($week[$j]['match']);

							for($k=0;$k<$all_match;$k++){
								$goals = $lineups = $substitutions = $match_lineup = null;
								$rows = $val[$i]['match'][$k];
								// Debug($rows);

								$data_update['match_id'] = $rows['id'];
								$data_update['stage_id'] = $stage_id;
								$data_update['sport'] = 'soccer';
								$data_update['tournament_id'] = $tournament_id;
								$data_update['tournament_name'] = $tournament_name;
								// $data_update['week'] = $rows['week'];
								$data_update['match_status'] = $rows['status'];
								$data_update['match_datetime'] = date('Y-m-d H:i:s', strtotime(str_replace('.', '-', ($rows['date'] . " " . $rows['time']))));
								$data_update['static_id'] = $rows['static_id'];

								if($rows['venue_id'] > 0){
									$stadium['stadium_id'] = $data_update['stadium_id'] = intval($rows['venue_id']);
									$stadium['stadium_name'] = $data_update['stadium'] = $rows['venue'];										
								}

								// $data_update['attendance'] = $rows['attendance'];
								$data_update['time'] = $rows['time'];
								// $data_update['referee'] = $rows['referee'];
								$data_update['hteam_id'] = $rows['localteam']['id'];
								$data_update['hteam'] = $rows['localteam']['name'];

								if($rows['localteam']['score'] != '')
									$data_update['hgoals'] = $rows['localteam']['score'];

								$data_update['ateam_id'] = $rows['visitorteam']['id'];
								$data_update['ateam'] = $rows['visitorteam']['name'];

								if($rows['visitorteam']['score'] != '')
									$data_update['agoals'] = $rows['visitorteam']['score'];

								// $stadium['stadium_city'] = $rows['venue_city'];


								// if(isset($rows['goals']))
									$goals = @$rows['goals'];
								
								if(isset($goals)){
									unset($match_goals);
									// Debug($goals);

									$match_goals[0]['match_id'] = intval($data_update['match_id']);

									$chk_match_goals = $this->xml_model->ChkActive('_xml_match_goals', 'match_id', $match_goals[0]);
									Debug($this->db->last_query());
									// die();

									if(empty($chk_match_goals)){

										$num_goals = count($goals);
										for($g=0;$g<$num_goals;$g++){

											$match_goals[$g]['match_id'] = intval($data_update['match_id']);
											$match_goals[$g]['team'] = $goals[$g]['team'];
											$match_goals[$g]['minute'] = $goals[$g]['minute'];
											$match_goals[$g]['playerid'] = intval($goals[$g]['playerid']);
											$match_goals[$g]['player'] = $goals[$g]['player'];
											$match_goals[$g]['score'] = $goals[$g]['score'];
											$match_goals[$g]['assistid'] = intval($goals[$g]['assistid']);
											$match_goals[$g]['assist'] = $goals[$g]['assist'];

										}

										$this->xml_model->import_batch('_xml_match_goals', $match_goals);
										Debug($this->db->last_query());
									}

									// die();
								}

								// if(isset($rows['lineups']))
									$lineups = @$rows['lineups'];

								// if(isset($rows['substitutions']))
									// $substitutions = $rows['substitutions'];
								
								// Debug($goals);
								// Debug($lineups);
								// Debug($substitutions);
								// die();

								if(isset($lineups['home_player'])){

									unset($match_lineup);
									// Debug($lineups['home_player']);
									$number_player = count($lineups['home_player']);
									for($l=0;$l<$number_player;$l++){

										$player = $lineups['home_player'][$l];

										$match_lineup[$l]['match_id'] = $data_update['match_id'];
										$match_lineup[$l]['team_id'] = $data_update['hteam_id'];
										$match_lineup[$l]['player_id'] = $player['id'];
										$match_lineup[$l]['number'] = $player['number'];
										$match_lineup[$l]['name'] = $player['name'];
										$match_lineup[$l]['booking'] = $player['booking'];

										// Debug($match_lineup);
										// $this->xml_model->chkupdate_data('_xml_match_lineup', );
									}
									if($query == 1){

										$data_del['match_id'] = $data_update['match_id'];
										$this->xml_model->delete_data('_xml_match_lineup', $data_del);
										Debug($this->db->last_query());

										$this->xml_model->import_batch('_xml_match_lineup', $match_lineup);
										Debug($this->db->last_query());
										Debug('Import _xml_match_lineup home');
									}
								}

								if(isset($lineups['away_player'])){

									unset($match_lineup);
									// Debug($lineups['away_player']);
									$number_player = count($lineups['away_player']);
									for($l=0;$l<$number_player;$l++){

										$player = $lineups['away_player'][$l];

										$match_lineup[$l]['match_id'] = $data_update['match_id'];
										$match_lineup[$l]['team_id'] = $data_update['ateam_id'];
										$match_lineup[$l]['player_id'] = $player['id'];
										$match_lineup[$l]['number'] = $player['number'];
										$match_lineup[$l]['name'] = $player['name'];
										$match_lineup[$l]['booking'] = $player['booking'];

										// Debug($match_lineup);
									}
									if($query == 1){
										// $data_del['match_id'] = $data_update['match_id'];
										// $this->xml_model->delete_data('_xml_match_lineup', $data_del);

										$this->xml_model->import_batch('_xml_match_lineup', $match_lineup);
										Debug($this->db->last_query());
										Debug('Import _xml_match_lineup away');
									}
								}

								//substitutions
								if(isset($rows['substitutions']['home_player'])){

									unset($substitutions);
									// Debug($lineups['home_player']);
									$number_player = count($rows['substitutions']['home_player']);
									for($l=0;$l<$number_player;$l++){
										
										$player = $rows['substitutions']['home_player'][$l];

										$substitutions[$l]['match_id'] = $data_update['match_id'];
										$substitutions[$l]['static_id'] = $data_update['static_id'];
										$substitutions[$l]['tournament_id'] = $tournament_id;
										$substitutions[$l]['team_id'] = $data_update['hteam_id'];
										$substitutions[$l]['on_id'] = $player['player_in_id'];
										$substitutions[$l]['on_number'] = $player['player_in_number'];
										$substitutions[$l]['on_name'] = $player['player_in_name'];
										$substitutions[$l]['on_booking'] = $player['player_in_booking'];
										$substitutions[$l]['off_name'] = $player['player_out_name'];
										$substitutions[$l]['minute'] = $player['minute'];

										// Debug($substitutions);
									}
									if($query == 1){

										$data_del['match_id'] = $data_update['match_id'];
										$this->xml_model->delete_data('_xml_match_substitutions', $data_del);
										Debug($this->db->last_query());

										$this->xml_model->import_batch('_xml_match_substitutions', $substitutions);
										Debug($this->db->last_query());
										Debug('Import _xml_match_substitutions home');
									}
								}

								if(isset($rows['substitutions']['away_player'])){

									unset($substitutions);
									// Debug($lineups['home_player']);
									$number_player = count($rows['substitutions']['away_player']);
									for($l=0;$l<$number_player;$l++){

										$player = $rows['substitutions']['away_player'][$l];

										$substitutions[$l]['match_id'] = $data_update['match_id'];
										$substitutions[$l]['static_id'] = $data_update['static_id'];
										$substitutions[$l]['tournament_id'] = $tournament_id;
										$substitutions[$l]['team_id'] = $data_update['ateam_id'];
										$substitutions[$l]['on_id'] = $player['player_in_id'];
										$substitutions[$l]['on_number'] = $player['player_in_number'];
										$substitutions[$l]['on_name'] = $player['player_in_name'];
										$substitutions[$l]['on_booking'] = $player['player_in_booking'];
										$substitutions[$l]['off_name'] = $player['player_out_name'];
										$substitutions[$l]['minute'] = $player['minute'];

										// Debug($substitutions);
									}
									if($query == 1){
										// $data_del['match_id'] = $data_update['match_id'];
										// $this->xml_model->delete_data('_xml_match_substitutions', $data_del);

										$this->xml_model->import_batch('_xml_match_substitutions', $substitutions);
										Debug($this->db->last_query());
										Debug('Import _xml_match_substitutions away');
									}
								}

								if(isset($rows['referee_id'])){
									unset($referee);
									$referee['referee_id'] = $rows['referee_id'];
									$referee['referee_name'] = $rows['referee_name'];

									// Debug($referee);
									if($referee['referee_id'] > 0){
										$this->xml_model->chkupdate_data('_referee', 'referee_id', $referee);
										Debug($this->db->last_query());		
									}

									if($referee['referee_id'] > 0){
										$data_update['referee_id'] = $rows['referee_id'];
										$data_update['referee'] = $rows['referee_name'];
									}

								}
								// die();

								if($debug == 1){

									Debug($data_update);
									// Debug($stadium);
								}

								//*********************Update DATA
								if($query == 1){

									if(@$stadium['stadium_id'] > 0){

										$this->xml_model->chkupdate_stadium($stadium);
										Debug($this->db->last_query());
									}

									if($data_update['match_id'] > 0){

										$this->xml_model->chkupdate_program($data_update['match_id'], $data_update);
										Debug($this->db->last_query());
									}
									
									unset($stadium);
									unset($data_update);
								}
							}

							/*********************Update DATA
							if($query == 1){

								if($week[$i]['match'][$j]['match_id'] > 0){

									Debug($week[$i]['match'][$j]);
									echo "Update...";
									$this->xml_model->update('_xml_match', 'match_id', $week[$i]['match'][$j]);
									Debug($this->db->last_query());
								}
							}*/

						

							// if($debug == 1)
							// 	Debug($week[$i]['match']);
							//die();

							//*********************Import DATA
							if($import == 1){

								echo "<hr>Importing...<br>";
								//Debug($week[$i]['match']);
								$this->xml_model->import_batch($this->prefix.'_xml_match', $week[$i]['match']);
								Debug($this->db->last_query());
							}

						}

						// Debug($week[$i]['match']);

						if($debug == 1 || $query == 1) echo "<hr>Update $number_match success.";
						if($import == 1) echo "<hr>Import $number_match success.";

					}
				}

			}
		}

		if($import == 1){
			$lnk = base_url('fixtures/update/'.$team["tournament_id"].'/import');
			echo "<br><a href='".$lnk."' target=_blank>Import To match fixture</a><br>";
		}

		if($debug == 1) echo "<hr>";



		if($week){
			$item['head']['code'] = 200;
			$item['head']['msg'] = 'Success';
			$item['head']['total'] = count($week);
		}else{
			$item['head']['code'] = 404;
			$item['head']['msg'] = 'File not found';
		}
		
		$item['data'] = $week;
		if($this->uri->segment(3) == 'json') echo json_encode($item);
	}

	public function import_live_commentaries(){
		//http://backend.tded.local/xml/import_live_commentaries/debug/epl/fix_id
		if($this->uri->segment(3) == 'json') header('Content-Type: application/json');

		$query = 0;
		$debug = 0;

		if($this->uri->segment(3) == 'query') $query = 1;
		if($this->uri->segment(3) == 'debug') $debug = 1;

		if($this->uri->segment(4) == 'spain') 
			$feed_url = $this->xml_host.'commentaries/spain.xml';
		else if($this->uri->segment(4) == 'germany') 
			$feed_url = $this->xml_host.'commentaries/germany.xml';
		else if($this->uri->segment(4) == 'italy') 
			$feed_url = $this->xml_host.'commentaries/italy.xml';
		else if($this->uri->segment(4) == 'facup') 
			$feed_url = $this->xml_host.'commentaries/facup.xml';
		else if($this->uri->segment(4) == 'england')
			$feed_url = $this->xml_host.$this->xml_live_commentaries_epl; //commentaries/epl.xml
		else
			$feed_url = $this->xml_host.'commentaries/'.$this->uri->segment(4).'.xml';

		if($this->uri->segment(5)) $fix_id = $this->uri->segment(5);
		if($this->uri->segment(6)) $fix_id = $this->uri->segment(6);

		//$feed_url = $this->xml_host.$this->xml_live_commentaries;
		if($debug == 1) Debug($feed_url);

		$import_match = $xml_data = $stadium = array();
		$xml_data = $this->xml_model->get_live_commentaries($feed_url);

		if($debug == 1) Debug($xml_data);

		if($xml_data){
			$item['head']['code'] = 200;
			$item['head']['msg'] = 'Success';
			//$item['head']['total'] = count($xml_data);
		}else{
			$item['head']['code'] = 404;
			$item['head']['msg'] = 'File not found';
		}
		
		$item['data'] = $xml_data;
		if($this->uri->segment(3) == 'json') echo json_encode($item);
	}

	public function import_standings(){
		//$this->load->model('standing_model');

		if($this->uri->segment(3) == 'json') header('Content-Type: application/json');

		$query = $debug = $import = $num_team = 0;
		$item = $xml_data = $standings = array();

		$table = '_xml_standing';

		if($this->uri->segment(3) == 'import') $import = 1;
		if($this->uri->segment(3) == 'query') $query = 1;
		if($this->uri->segment(3) == 'debug') $debug = 1;

		if($this->uri->segment(4)){
			$feed_url = $this->xml_host.'standings/'.$this->uri->segment(4);
		}else{
			$feed_url = $this->xml_host.$this->xml_standings;
		}
		
		// Debug($feed_url);
		// die();
		$xml_data = $this->xml_model->get_standings($feed_url);
		if($xml_data){
			$item['head']['code'] = 200;
			$item['head']['msg'] = 'Success';			
			$item['head']['url'] = $feed_url;
			$item['head']['total'] = count($xml_data);
		}else{
			$item['head']['code'] = 404;
			$item['head']['msg'] = 'File not found';
		}

		$item['head']['method'] = 'view';

		$item['data'] = $xml_data;

		if($debug == 1){
			Debug($feed_url);
			Debug($xml_data);
			die();
		}

		// if($query == 1 || $debug == 1){

			// Debug($xml_data);

			$num_group = count($xml_data['group']);
			for($i=0;$i<$num_group;$i++){
				
				$group = $xml_data['group'][$i];
				// Debug($group);

				$group_fullname = $group['name'];
				$group_date = $group['date'];
				$season = $group['season'];
				// $group_round = $group['round'];
				// $group_name = $group['group'];
				// $group_id = $group['groupId'];
				// $stage_id = $group['stage_id'];
				$tournament_id = intval($group['id']);
				$is_current = $group['is_current'];
				// die();
				// $standings[$i]['country_id'] = ($country == "England") ? 250 : 0;

				$team_list = $group['team'];
				$number_team = count($team_list);
				
				
				if($debug == 1){
					echo "(number_team = $number_team)<br>";
					Debug($team_list);
				}

				// $number_team = $num_team + $number_team;
				for($j=0;$j<$number_team;$j++){

					if($debug == 1){
						// Debug($team_list[$j]);
					}

					$standings[$j]['country'] = $team_list[$j]['country'];
					$standings[$j]['tournament_id'] = $tournament_id;
					$standings[$j]['tournament_name'] = $group_fullname;
					$standings[$j]['season'] = $season;
					$standings[$j]['round'] = $group['round'];
					$standings[$j]['stage_id'] = $group['stage_id'];
					$standings[$j]['group_id'] = intval($group['groupId']);
					$standings[$j]['group_name'] = $group['group'];
					
					$standings[$j]['team_position'] = $team_list[$j]['team_position'];
					$standings[$j]['team_status'] = $team_list[$j]['team_status'];

					$standings[$j]['team_id'] = intval($team_list[$j]['team_id']);
					$standings[$j]['team_name'] = $team_list[$j]['team_name'];

					$standings[$j]['overall_gp'] = $team_list[$j]['overall_gp'];
					$standings[$j]['overall_w'] = $team_list[$j]['overall_w'];
					$standings[$j]['overall_d'] = $team_list[$j]['overall_d'];
					$standings[$j]['overall_l'] = $team_list[$j]['overall_l'];
					$standings[$j]['overall_gs'] = $team_list[$j]['overall_gs'];
					$standings[$j]['overall_ga'] = $team_list[$j]['overall_ga'];

					$standings[$j]['home_gp'] = $team_list[$j]['home_gp'];
					$standings[$j]['home_w'] = $team_list[$j]['home_w'];
					$standings[$j]['home_d'] = $team_list[$j]['home_d'];
					$standings[$j]['home_l'] = $team_list[$j]['home_l'];
					$standings[$j]['home_gs'] = $team_list[$j]['home_gs'];
					$standings[$j]['home_ga'] = $team_list[$j]['home_ga'];

					$standings[$j]['away_gp'] = $team_list[$j]['away_gp'];
					$standings[$j]['away_w'] = $team_list[$j]['away_w'];
					$standings[$j]['away_d'] = $team_list[$j]['away_d'];
					$standings[$j]['away_l'] = $team_list[$j]['away_l'];
					$standings[$j]['away_gs'] = $team_list[$j]['away_gs'];
					$standings[$j]['away_ga'] = $team_list[$j]['away_ga'];

					$standings[$j]['total_gd'] = $team_list[$j]['total_gd'];
					$standings[$j]['total_p'] = $team_list[$j]['total_p'];

					$standings[$j]['recent_form'] = $team_list[$j]['recent_form'];
					$standings[$j]['description'] = $team_list[$j]['description'];

					$standings[$j]['lastupdate_date'] = $team_list[$j]['lastupdate_date'];
					// $standings[$j]['status'] = 1;
					
					// Debug($standings[$j]);
					
					if($query == 1){

						// Debug($standings[$j]);
						// die();

						// $this->xml_model->chkupdate_xml_standing($standings[$j]['tournament_id'], $standings[$j]['team_id'], $standings[$j]);
						// $res = $this->xml_model->get_xml_standing($standings[$j]['team_id'], $standings[$j]['tournament_id']);
						// Debug($this->db->last_query());
						// Debug($res);
						
						// die();
					}
					$num_team++;

				}
			}

			$item['data'] = $standings;
			//Debug($standings);		

			if($import == 1){

				$item['head']['method'] = 'add';
				$this->xml_model->get_standings($feed_url, 1);
				// $this->xml_model->import($table, $standings);
				$this->xml_model->import_batch($table, $standings);
				//$this->standing_model->import($standings);
				//echo "Import success.";
				Debug($this->db->last_query());
			}
		// }

		if($this->uri->segment(3) == 'json') echo json_encode($item);
		// echo json_encode($item);
		// Debug($item);
	}

	public function import_topscorers(){	
		
		if($this->uri->segment(3) == 'json') header('Content-Type: application/json');

		$query = 0;
		$debug = 0;
		$table = '_xml_topscorers';

		if($this->uri->segment(3) == 'query') $query = 1;
		if($this->uri->segment(3) == 'debug') $debug = 1;

		if($this->uri->segment(4) != ''){
			$league = trim($this->uri->segment(4));
		}

		$item = $xml_data = $standings = array();

		$feed_url = $this->xml_host.$this->xml_topscorers;
		$xml_data = $this->xml_model->get_topscorers($feed_url);
		
		if($debug == 1){
			Debug($feed_url);
			Debug($xml_data);
		} 

		if($xml_data){
			$item['head']['code'] = 200;
			$item['head']['msg'] = 'Success';
			$item['head']['total'] = count($xml_data);
		}else{
			$item['head']['code'] = 404;
			$item['head']['msg'] = 'File not found';
		}

		if($query == 1){

			$item['head']['method'] = 'add';
			// $this->xml_model->get_standings($feed_url, 1);
			// $this->xml_model->import($table, $xml_data);

			// Debug($xml_data);
			$this->xml_model->delete_data($table, array('tournament_id' => intval($xml_data[0]['tournament_id'])));
			// Debug($this->db->last_query());
			$this->xml_model->import_batch($table, $xml_data);
			echo "Import success.";
			// Debug($this->db->last_query());
		}
		
		$item['data'] = $xml_data;
		if($this->uri->segment(3) == 'json') echo json_encode($item);
	}

	public function import_topassits(){	
		
		if($this->uri->segment(3) == 'json') header('Content-Type: application/json');

		$query = 0;
		$debug = 0;
		$table = '_xml_topassist';

		if($this->uri->segment(3) == 'query') $query = 1;
		if($this->uri->segment(3) == 'debug') $debug = 1;

		$item = $xml_data = $standings = array();

		$feed_url = $this->xml_host.$this->xml_topscorers;
		if($this->uri->segment(4)){
			$feed_url = $this->xml_host.'topscorers/'.$this->uri->segment(4);
		}

		$xml_data = $this->xml_model->get_topassits($feed_url);
		
		if($debug == 1){
			Debug($feed_url);
			Debug($xml_data);
		}

		if($xml_data){
			$item['head']['code'] = 200;
			$item['head']['msg'] = 'Success';
			$item['head']['total'] = count($xml_data);
		}else{
			$item['head']['code'] = 404;
			$item['head']['msg'] = 'File not found';
		}

		if($query == 1){

			$item['head']['method'] = 'add';
			// $this->xml_model->get_standings($feed_url, 1);
			// $this->xml_model->import($table, $xml_data);

			// Debug($xml_data);
			$this->xml_model->delete_data($table, array('tournament_id' => intval($xml_data[0]['tournament_id'])));
			// Debug($this->db->last_query());
			$this->xml_model->import_batch($table, $xml_data);
			echo "Import success.";
			// Debug($this->db->last_query());
		}
		
		$item['data'] = $xml_data;
		if($this->uri->segment(3) == 'json') echo json_encode($item);
	}

	public function import_topcards(){	
		
		if($this->uri->segment(3) == 'json') header('Content-Type: application/json');

		$query = 0;
		$debug = 0;
		$table = '_xml_topcard';

		if($this->uri->segment(3) == 'query') $query = 1;
		if($this->uri->segment(3) == 'debug') $debug = 1;

		$item = $xml_data = $standings = array();

		$feed_url = $this->xml_host.$this->xml_topscorers;
		$xml_data = $this->xml_model->get_topcards($feed_url);
		
		if($debug == 1) Debug($xml_data);

		if($xml_data){
			$item['head']['code'] = 200;
			$item['head']['msg'] = 'Success';
			$item['head']['total'] = count($xml_data);
		}else{
			$item['head']['code'] = 404;
			$item['head']['msg'] = 'File not found';
		}

		if($query == 1){

			$item['head']['method'] = 'add';
			// $this->xml_model->get_standings($feed_url, 1);
			// $this->xml_model->import($table, $xml_data);

			// Debug($xml_data);
			$this->xml_model->delete_data($table, array('tournament_id' => intval($xml_data[0]['tournament_id'])));
			// Debug($this->db->last_query());
			$this->xml_model->import_batch($table, $xml_data);
			echo "Import success.";
			// Debug($this->db->last_query());
		}
		
		$item['data'] = $xml_data;
		if($this->uri->segment(3) == 'json') echo json_encode($item);
	}

	public function import_odds(){

		header("Access-Control-Allow-Origin: ".base_url());

		if($this->uri->segment(3) == 'json') header('Content-Type: application/json');
		$query = $debug = $import = $league_id = 0;
		if($this->uri->segment(3) == 'import') $import = 1;
		if($this->uri->segment(3) == 'query') $query = 1;
		if($this->uri->segment(3) == 'debug') $debug = 1;
		$item = $xml_data = array();

		//soccer?cat=england&match=2160521&league=1205

		if($this->uri->segment(5)) $league_id = $this->uri->segment(5);
		if($this->uri->segment(6)) $match_id = $this->uri->segment(6);

		if($this->uri->segment(4)){

			if($this->uri->segment(6)){
				$feed_url = $this->xml_host.'getodds/soccer?cat='.$this->uri->segment(4).'&league='.$league_id.'&match='.$match_id;
			}else if($this->uri->segment(5)){
				$feed_url = $this->xml_host.'getodds/soccer?cat='.$this->uri->segment(4).'&league='.$league_id;
			}else
				$feed_url = $this->xml_host.'getodds/soccer?cat='.$this->uri->segment(4);
		}else
			$feed_url = $this->xml_host.$this->xml_odds; //getodds/soccer?cat=england

		//Debug($feed_url);

		if($query == 1) echo "Starting import data...";

		$xml_data = $this->xml_model->get_odds($feed_url, $query);

		//Debug($xml_data);
		//die();
		echo "league_id = $league_id<br>";
		echo "match_id = $match_id<br>";
		
		if($xml_data)
			for($i=1;$i<count($xml_data);$i++){

				echo "league_id = $league_id<br>";

				if($league_id == $xml_data[$i]['id']){

					echo "<br>".$xml_data[$i]['id'].' '.$xml_data[$i]['name'];

					$matches = $xml_data[$i]['matches']['match'];
					$type = $xml_data[$i]['type'];

					$allmatch = count($matches);

					//Debug($matches);
					//die();
					for($j=0;$j<$allmatch;$j++){					
						$alltype = count($type);
						$match_id = $matches[$j]['match_id'];
						$fix_id = $matches[$j]['fix_id'];
						$static_id = $matches[$j]['static_id'];

						foreach($type as $key => $val){
							$type_id = $key;
							$allitem = count($val);

							for($k=0;$k<$allitem;$k++){

								unset($item);

								$item["match_id"] = $match_id;
								$item["fix_id"] = $fix_id;
								$item["static_id"] = $static_id;
								$item["oddtype_id"] = $type_id;
								$item["betcompany_id"] = $val[$k]['betcompany_id'];
								if(isset($val[$k]['home_win'])) $item["home_win"] = $val[$k]['home_win'];
								if(isset($val[$k]['home_win'])) $item["away_win"] = $val[$k]['away_win'];
								if(isset($val[$k]['draw'])) $item["draw"] = $val[$k]['draw'];

								$chkfield = array(
									'fix_id' => $fix_id,
									'betcompany_id' => $item["betcompany_id"],
									'oddtype_id' => $item["oddtype_id"]
								);

								//Debug($chkfield);

								//if($type_id == 1 || $type_id == 5 || $type_id == 33 || $type_id == 34){
								if($type_id == 1){

									echo "Check update data.<br>";
									$this->xml_model->chkupdate_array('_xml_bet_three_way_result', $chkfield, $item);
									Debug($this->db->last_query());
								}
							}
						}
						//Debug($matches);
						//Debug($type);
						//echo count
					}
				}

			}
		//	if($debug == 1 || $query == 1) Debug($xml_data);

		if($query == 1) echo "<br>Success...";
		
		if($xml_data){
			$item['head']['code'] = 200;
			$item['head']['msg'] = 'Success';
			$item['head']['total'] = count($xml_data);
		}else{
			$item['head']['code'] = 404;
			$item['head']['msg'] = 'File not found';
		}
		
		$item['data'] = $xml_data;
		if($this->uri->segment(3) == 'json') echo json_encode($item);
	}

	public function import_soccernew(){

		// header("Access-Control-Allow-Origin: *");
		//header('Access-Control-Allow-Origin: '.base_url());  //I have also tried the * wildcard and get the same response
	    //header("Access-Control-Allow-Credentials: true");
	    //header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
	    //header('Access-Control-Max-Age: 1000');
	    //header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
	    // header('Cache-Control: no-cache');
    	//header('Pragma: no-cache');

		if($this->uri->segment(3) == 'json') header('Content-Type: application/json  charset=utf-8');

		$this->load->model('tournament_model');
		$this->load->model('program_model');

		$query = $import = $debug = $number_match = $all = 0;
		$cat = '';
		$xml_data = $item = $obj_item = $leagues_arr = $matches = $view_match = $xml_match = array();

		if($this->uri->segment(3) == 'query') $query = 1;
		if($this->uri->segment(3) == 'import') $import = 1;
		if($this->uri->segment(3) == 'debug') $debug = 1;
		
		if($this->uri->segment(5) == 'all') 
			$all = 1;

		if($this->uri->segment(4) == 'd1') 
			$feed_url = $this->xml_host.$this->xml_news_tomorrow;
		else if($this->uri->segment(4) == 'd-1') 
			$feed_url = $this->xml_host.$this->xml_news_yesterday;
		else if(($this->uri->segment(4) == 'lives') || ($this->uri->segment(4) == 'home')) 
			$feed_url = $this->xml_host.$this->xml_news_lives;
		else if(!$this->uri->segment(4))
			$feed_url = $this->xml_host.$this->xml_news_lives;
		else{

			switch($this->uri->segment(4)){
				case 'worldcup': $feed_url = $this->xml_host.'soccernew/worldcup'; break;
				case 'worldcup_shedule': $feed_url = $this->xml_host.'soccernew/worldcup_shedule'; break;
				default:

					$feed_url = $this->xml_host.'soccernew/'.trim($this->uri->segment(4));
					// $feed_url = $this->xml_host.$this->xml_fixtures_results; break;
					// $feed_url = $this->xml_host.'soccernew/england/'.trim($this->uri->segment(4)); break;
				break;
			}

			// $feed_url = $this->xml_host.'soccernew/'.$this->uri->segment(4).'?cat='.$cat;
			//$feed_shedule_url = $this->xml_host.'soccernew/'.$this->uri->segment(4).'_shedule?cat='.$cat;
		}

		if($this->input->get('cat')) $cat = $this->input->get('cat');
		if($cat != '')
			$feed_url .= '?cat='.$cat;

		if($debug == 1) Debug($feed_url);

		$xml_data = $this->xml_model->get_soccernew($feed_url);
		//Debug($xml_data);
		//die();

		if($xml_data){
			$league = $xml_data['category'];
			$all_league = count($league);
			//echo "all_league = $all_league<br>";
			$j = $ii = 0;
			for($i=0;$i<$all_league;$i++){

				$obj_item[$i]['tournament_id'] = $league[$i]['id'];
				$obj_item[$i]['tournament_name_en'] = $league[$i]['name'];
				$obj_item[$i]['file_group'] = $league[$i]['file_group'];	//Country league

				//$obj_item[$i]['iscup'] = ($league[$i]['iscup'] === "False") ? 0:1;
				$obj_item[$i]['iscup'] = $league[$i]['iscup'];
				
				if($import == 1){
				 	$obj_item[$i]['status'] = 1;
					$obj_item[$i]['create_date'] = date('Y-m-d H:i:s');
				}

				if($query == 1){
					$obj_item[$i]['lastupdate_date'] = date('Y-m-d H:i:s');
				}
				//Add new tournament if no data
				if($import == 1) $this->xml_model->chkupdate_data('_tournament', 'tournament_id', $obj_item[$i]);
				//Exam. 1397 = Spain: Copa Del Rey

				//Update data tournament
				//if($query == 1) $this->xml_model->update('_tournament', 'tournament_id', $obj_item[$i]);

				//Debug($obj_item[$i]);

				/*********************ตรวจสอบว่า ใช้งาน League นี้หรือไม่********/
				$arr = array();
				unset($chklegue);
				$arr['tournament_id'] = $league[$i]['id'];
				//$arr['status'] = 1;
				$chklegue = $this->xml_model->ChkActive('_tournament', 'tournament_id', $arr);
				
				if(isset($chklegue[0]['tournament_id'])){
					if($chklegue[0]['tournament_id'] == $league[$i]['id']){
					//if(($league[$i]['id'] == 1204) || ($league[$i]['id'] == 1397)){
					
						$count_matches = $league[$i]['matches']['count_match'];
						//Debug($count_matches);
						for($j=0;$j<$count_matches;$j++){
							//Debug($league[$i]['id']['matches']['match'][$j]);

							$view_match = $league[$i]['matches']['match'];
							// Debug($view_match);

							$league_id = $league[$i]['id'];

							$matches[$league_id][$j]['id'] = $view_match[$j]['id'];
							$matches[$league_id][$j]['date'] = $view_match[$j]['date'];
							$matches[$league_id][$j]['formatted_date'] = $view_match[$j]['formatted_date'];
							$matches[$league_id][$j]['status'] = $view_match[$j]['status'];
							$matches[$league_id][$j]['time'] = $view_match[$j]['time'];
							$matches[$league_id][$j]['static_id'] = $view_match[$j]['static_id'];
							//$matches[$league_id][$j]['static_id'] = $view_match[$j]['static_id'];
							$matches[$league_id][$j]['fix_id'] = $view_match[$j]['fix_id'];

							$matches[$league_id][$j]['home']['id'] = $view_match[$j]['localteam']['id'];
							$matches[$league_id][$j]['home']['name'] = $view_match[$j]['localteam']['name'];
							$matches[$league_id][$j]['home']['goals'] = $view_match[$j]['localteam']['goals'];

							$matches[$league_id][$j]['away']['id'] = $view_match[$j]['visitorteam']['id'];
							$matches[$league_id][$j]['away']['name'] = $view_match[$j]['visitorteam']['name'];
							$matches[$league_id][$j]['away']['goals'] = $view_match[$j]['visitorteam']['goals'];

							if(isset($view_match[$j]['events']))
								$matches[$league_id][$j]['events'] = $view_match[$j]['events'];

							$matches[$league_id][$j]['ht'] = $view_match[$j]['ht'];

							if(isset($view_match[$j]['ft']))
								$matches[$league_id][$j]['ft'] = $view_match[$j]['ft'];

							if(isset($view_match[$j]['et']))
								$matches[$league_id][$j]['et'] = $view_match[$j]['et'];

							if(isset($view_match[$j]['penalty']))
								$matches[$league_id][$j]['penalty'] = $view_match[$j]['penalty'];

							// Debug($view_match);
							// echo "($cat, $query)<hr>";

							// if($cat != '' && $query = 1){
							if($query == 1){

								//$formatted_date = str_replace(".", "/", $matches[$league_id][$j]['formatted_date']);
								list($d, $m, $y) = explode(".", $matches[$league_id][$j]['formatted_date']);

								$formatted_date = "$y-$m-$d";
								$time = $matches[$league_id][$j]['time'];
								$datetime = date('Y-m-d', strtotime($formatted_date)).' '.$time;
								//Debug($formatted_date);

								if($matches[$league_id][$j]['home']['goals'] != '?')
									$result_match = $matches[$league_id][$j]['home']['goals'].'-'.$matches[$league_id][$j]['away']['goals'];
								else
									$result_match = '';

								$dataupdate = array(
									'kickoff' => $datetime,
									'program_id' => $matches[$league_id][$j]['id'],
									'fix_id' => $matches[$league_id][$j]['fix_id'],
									'static_id' => $matches[$league_id][$j]['static_id'],
									'program_status' => $matches[$league_id][$j]['status'],
									'league_id' => $league_id,
									'hometeam_id' => $matches[$league_id][$j]['home']['id'],
									'hometeam_title' => $matches[$league_id][$j]['home']['name'],
									'hometeam_point' => $matches[$league_id][$j]['home']['goals'],
									'awayteam_id' => $matches[$league_id][$j]['away']['id'],
									'awayteam_title' => $matches[$league_id][$j]['away']['name'],
									'awayteam_point' => $matches[$league_id][$j]['away']['goals'],
									'ht_result' => $matches[$league_id][$j]['ht'],
									'result' => $result_match,
									'status' => 1,
									'lastupdate_date' => date('Y-m-d H:i:s')
								);

								if(isset($matches[$league_id][$j]['ft'])){
									$dataupdate['ft_result'] = $matches[$league_id][$j]['ft'];
								}

								if(isset($matches[$league_id][$j]['et'])){
									$dataupdate['et_result'] = $matches[$league_id][$j]['et'];
								}

								if(isset($matches[$league_id][$j]['penalty'])){
									$dataupdate['penalty'] = $matches[$league_id][$j]['penalty'];
								}

								// echo "(fix_id = ".$matches[$league_id][$j]['fix_id'].")<hr>";
								// Debug($dataupdate);
								// die();

								$xml_match = array(
									'match_id' => $matches[$league_id][$j]['fix_id'],
									'sport' => 'soccer',
									'tournament_id' => $league_id,
									'tournament_name' => $obj_item[$i]['tournament_name_en'],
	
									'match_status' => $matches[$league_id][$j]['status'],
									'match_datetime' => $datetime,
									'static_id' => $matches[$league_id][$j]['static_id'],

									'time' => $matches[$league_id][$j]['time'],
									'hteam_id' => $matches[$league_id][$j]['home']['id'],
									'hteam' => $matches[$league_id][$j]['home']['name'],
									'hgoals' => $matches[$league_id][$j]['home']['goals'],
									'ateam_id' => $matches[$league_id][$j]['away']['id'],
									'ateam' => $matches[$league_id][$j]['away']['name'],
									'agoals' => $matches[$league_id][$j]['away']['goals'],
									'lastupdate_date' => date('Y-m-d H:i:s')
								);
								// Debug($xml_match);
								// die();

								echo "<hr>";
								//Program worldcup 2022 only
								// if($league_id == 1056){

									$chk_program = $this->program_model->chk_program($matches[$league_id][$j]['id'], $dataupdate);
									if(empty($chk_program)){

										$this->program_model->chk_program_fixid($matches[$league_id][$j]['fix_id'], $dataupdate);
										Debug($this->db->last_query());
									}
									

									// $this->program_model->insert_program($dataupdate);
									// $this->program_model->update_fixid($matches[$league_id][$j]['fix_id'], $dataupdate, 1);
									// $this->program_model->insert_update_program2($dataupdate);

									$this->xml_model->chkupdate_program($matches[$league_id][$j]['fix_id'], $xml_match);
									Debug($this->db->last_query());
									$number_match++;
								// }

								//Check match event
								$all_event = @count($matches[$league_id][$j]['events']);

								for($k=0;$k<$all_event;$k++){

									$rows_event = $matches[$league_id][$j]['events'][$k];

									$xml_match = array(
										'eventid' => $rows_event['eventid'],
										'type' => $rows_event['type'],		//goal, yellowcard, subst, var
										'team' => $rows_event['team'],		//localteam, visitorteam
										'minute' => $rows_event['minute'],
										'result' => $rows_event['result'],
										'playerid' => intval($rows_event['playerid']),
										'player' => $rows_event['player'],
										'assistid' => intval($rows_event['assistid']),
										'assist' => $rows_event['assist'],
										'match_id' => $matches[$league_id][$j]['fix_id'],
										'program_id' => $matches[$league_id][$j]['id']
									);
									// Debug($xml_match);

									$this->xml_model->chkupdate_event($rows_event['eventid'], $xml_match);
								}


							}
						}
						// Debug($matches);
						// die();
						$leagues_arr[$ii] = $obj_item[$i];
						$leagues_arr[$ii]['matches'] = $matches;
						$ii++;
					}
				}else{
					unset($league[$i]);
				}
			}		
		}

		if($debug == 1){
			if($all == 1){

				echo "<br>".count($xml_data['category'])."<br>";
				Debug($xml_data['category']);
			}else{

				echo "<br>".count($leagues_arr)."<br>";
				Debug($leagues_arr);
			}

			// Debug('all match : '.$number_match);
		}
		if($query == 1){
			
			Debug('all match : '.$number_match);
		}

		if($xml_data){

			$item['head']['code'] = 200;
			$item['head']['msg'] = 'Success';
			if($all == 1)
				$item['head']['total'] = count($xml_data['category']);
			else
				$item['head']['total'] = count($leagues_arr);
		}else{

			$item['head']['code'] = 404;
			$item['head']['msg'] = 'File not found';
		}
		
		if($all == 1)
			$item['data'] = $xml_data['category'];
		else
			$item['data'] = $leagues_arr;

		if($this->uri->segment(3) == 'json') echo json_encode($item);	
	}

	public function import_match_event($query = 1, $days = 'home'){

		$import_match = $xml_data = $stadium = $team = $data_update = $data_program = array();
		$number_match = $query = $debug = $import = $tournament_id = 0;
		$cat = $tournament_name = $season = $country = '';
		$week = $referee = $match_datetime = null;
		$match_id = 0;
		$debug = $json = 0;
		$host = base_url();
		
		$create_at = date('Y-m-d H:i:s');
		$program_id = 0;
		$sel_date = '';

		$action = 'xml/import_fixtures_results/json/worldcup';
		$link_chkdata = base_url($action);
		// echo $link_chkdata."<hr>";

		if($this->uri->segment(3) == 'query') $query = 1;
		if($program_id == 0 && $sel_date == ''){

			$hour = date('H');
			if($hour <= 5){

				$sel_date = date('Y-m-d', strtotime('-1 days'));
			}else
				$sel_date = date('Y-m-d');
		}

		if($this->input->get('sel_date') != ''){
			
			$sel_date = $this->input->get('sel_date');
		}

		$this->update_match_lineup($sel_date);

		/*
		$res = $this->callApi($action, null, false, $host, true);
		// Debug($res);
		if($res->head->code == 200){

			// Debug($res);
			$stage = $res->data;
			$num_stage = count($stage);
			for($i=0;$i<$num_stage;$i++){

				$matchs = $stage[$i]->match;
				$num_match = count($matchs);
				for($j=0;$j<$num_match;$j++){

					$obj = $matchs[$j];

					$match_id = intval($obj->id);
					$static_id = $obj->static_id;
					$group_id = $obj->group_id;
					$week = $obj->week;
					$match_date = $obj->date;
					$match_time = $obj->time;
					$match_status = $obj->status;
					
					$localteam_id = $obj->localteam->id;
					$localteam_name = $obj->localteam->name;
					$localteam_score = $obj->localteam->score;
					$localteam_ft_score = $obj->localteam->ft_score;
					$localteam_et_score = $obj->localteam->et_score;
					$localteam_pen_score = $obj->localteam->pen_score;

					$visitorteam_id = $obj->visitorteam->id;
					$visitorteam_name = $obj->visitorteam->name;
					$visitorteam_score = $obj->visitorteam->score;
					$visitorteam_ft_score = $obj->visitorteam->ft_score;
					$visitorteam_et_score = $obj->visitorteam->et_score;
					$visitorteam_pen_score = $obj->visitorteam->pen_score;

					$halftime = $obj->halftime;
					$fulltime = $localteam_ft_score.'-'.$visitorteam_ft_score;
					$referee_id = $obj->referee_id;
					$referee_name = $obj->referee_name;

					$goals = $lineups = $substitutions = $data_del = $match_lineup = null;

					if(isset($obj->goals)) $goals = $obj->goals;
					if(isset($obj->lineups)) $lineups = $obj->lineups;
					if(isset($obj->substitutions)) $substitutions = $obj->substitutions;

					$chk_date = date('d.m.Y', strtotime($sel_date));
					
					//Update match lineup
					if($program_id > 0 && $program_id == $match_id){

						// Debug($lineups);
						// Debug('match_id='.$match_id);
						if(isset($lineups->home_player)){

							$match_lineup['match_id'] = $match_id;
							$chk_match = $this->xml_model->ChkActive('_xml_match_lineup', 'match_id', $match_lineup);
							// Debug($this->db->last_query());
							// Debug($chk_match);
							if(empty($chk_match)){

								unset($match_lineup);
								$number_player = count($lineups->home_player);
								for($k=0;$k<$number_player;$k++){

									$home_player = $lineups->home_player[$k];

									$match_lineup[$k]['match_id'] = $match_id;
									$match_lineup[$k]['team_id'] = $localteam_id;
									$match_lineup[$k]['player_id'] = $home_player->id;
									$match_lineup[$k]['number'] = $home_player->number;
									$match_lineup[$k]['name'] = $home_player->name;
									$match_lineup[$k]['booking'] = $home_player->booking;
									$match_lineup[$k]['create_at'] = $create_at;

									
								}
								// Debug($match_lineup);
								$this->xml_model->import_batch('_xml_match_lineup', $match_lineup);
								// Debug($this->db->last_query());
								Debug('Import '.$match_id.' _xml_match_lineup home');

								unset($match_lineup);
								$number_player = count($lineups->away_player);
								for($k=0;$k<$number_player;$k++){

									$away_player = $lineups->away_player[$k];

									$match_lineup[$k]['match_id'] = $match_id;
									$match_lineup[$k]['team_id'] = $visitorteam_id;
									$match_lineup[$k]['player_id'] = $away_player->id;
									$match_lineup[$k]['number'] = $away_player->number;
									$match_lineup[$k]['name'] = $away_player->name;
									$match_lineup[$k]['booking'] = $away_player->booking;
									$match_lineup[$k]['create_at'] = $create_at;
									
								}
								// Debug($match_lineup);
								$this->xml_model->import_batch('_xml_match_lineup', $match_lineup);
								// Debug($this->db->last_query());
								Debug('Import '.$match_id.' _xml_match_lineup away');
							}else{

								echo "Already have a lineup player.<br>";
							}

						}else{

							echo "Not Update lineup.<br>";
						}

						if(isset($substitutions->home_player)){

							// Debug($substitutions);

							unset($match_lineup);
							$match_lineup['match_id'] = $match_id;
							$chk_match = $this->xml_model->ChkActive('_xml_match_substitutions', 'match_id', $match_lineup);
							// Debug($this->db->last_query());
							// Debug($chk_match);
							if(empty($chk_match)){

								unset($match_lineup);
								unset($home_player);
								$number_player = count($substitutions->home_player);
								for($k=0;$k<$number_player;$k++){
									
									$home_player = $substitutions->home_player[$k];

									$match_lineup[$k]['match_id'] = $match_id;
									$match_lineup[$k]['static_id'] = $static_id;
									$match_lineup[$k]['tournament_id'] = $this->tournament_id;
									$match_lineup[$k]['team_id'] = $localteam_id;
									$match_lineup[$k]['on_id'] = $home_player->player_in_id;
									$match_lineup[$k]['on_number'] = $home_player->player_in_number;
									$match_lineup[$k]['on_name'] = $home_player->player_in_name;
									$match_lineup[$k]['on_booking'] = $home_player->player_in_booking;
									$match_lineup[$k]['off_name'] = $home_player->player_out_name;
									$match_lineup[$k]['minute'] = $home_player->minute;

									// Debug($substitutions);
								}
								// if($query == 1){
								// 	$data_del['match_id'] = $data_update['match_id'];
								// 	$this->xml_model->delete_data('_xml_match_substitutions', $data_del);

									$this->xml_model->import_batch('_xml_match_substitutions', $match_lineup);
									// Debug($this->db->last_query());
									Debug('Import '.$match_id.' _xml_match_substitutions home');
								// }

								unset($match_lineup);
								unset($away_player);
								$number_player = count($substitutions->away_player);
								for($k=0;$k<$number_player;$k++){
									
									$away_player = $substitutions->away_player[$k];

									$match_lineup[$k]['match_id'] = $match_id;
									$match_lineup[$k]['static_id'] = $static_id;
									$match_lineup[$k]['tournament_id'] = $this->tournament_id;
									$match_lineup[$k]['team_id'] = $visitorteam_id;
									$match_lineup[$k]['on_id'] = $away_player->player_in_id;
									$match_lineup[$k]['on_number'] = $away_player->player_in_number;
									$match_lineup[$k]['on_name'] = $away_player->player_in_name;
									$match_lineup[$k]['on_booking'] = $away_player->player_in_booking;
									$match_lineup[$k]['off_name'] = $away_player->player_out_name;
									$match_lineup[$k]['minute'] = $away_player->minute;

									// Debug($substitutions);
								}
								$this->xml_model->import_batch('_xml_match_substitutions', $match_lineup);
								// Debug($this->db->last_query());
								Debug('Import '.$match_id.' _xml_match_substitutions away');
							}else{

								echo "Already have a substitutions player.<br>";
							}

						}else{

							echo "Not Update substitutions.<br>";
						}
						
					
					}else if(($chk_date != '') && ($chk_date == $match_date)){

						echo '<hr>('.$chk_date.') (match_id = '.$match_id.')<br>';

						// Debug($lineups);
						// Debug('match_id='.$match_id);
						if(isset($lineups->home_player)){

							$match_lineup['match_id'] = $match_id;
							$chk_match = $this->xml_model->ChkActive('_xml_match_lineup', 'match_id', $match_lineup);
							// Debug($this->db->last_query());
							// Debug($chk_match);
							if(empty($chk_match)){

								unset($match_lineup);
								$number_player = count($lineups->home_player);
								for($k=0;$k<$number_player;$k++){

									$home_player = $lineups->home_player[$k];

									$match_lineup[$k]['match_id'] = $match_id;
									$match_lineup[$k]['team_id'] = $localteam_id;
									$match_lineup[$k]['player_id'] = $home_player->id;
									$match_lineup[$k]['number'] = $home_player->number;
									$match_lineup[$k]['name'] = $home_player->name;
									$match_lineup[$k]['booking'] = $home_player->booking;
									$match_lineup[$k]['create_at'] = $create_at;

									
								}
								// Debug($match_lineup);
								$this->xml_model->import_batch('_xml_match_lineup', $match_lineup);
								// Debug($this->db->last_query());
								Debug('Import '.$match_id.' _xml_match_lineup home');

								unset($match_lineup);
								$number_player = count($lineups->away_player);
								for($k=0;$k<$number_player;$k++){

									$away_player = $lineups->away_player[$k];

									$match_lineup[$k]['match_id'] = $match_id;
									$match_lineup[$k]['team_id'] = $visitorteam_id;
									$match_lineup[$k]['player_id'] = $away_player->id;
									$match_lineup[$k]['number'] = $away_player->number;
									$match_lineup[$k]['name'] = $away_player->name;
									$match_lineup[$k]['booking'] = $away_player->booking;
									$match_lineup[$k]['create_at'] = $create_at;
									
								}
								// Debug($match_lineup);
								$this->xml_model->import_batch('_xml_match_lineup', $match_lineup);
								// Debug($this->db->last_query());
								Debug('Import '.$match_id.' _xml_match_lineup away');
							}else{

								echo "Already have a lineup player.<br>";
							}

						}else{

							echo "Not Update lineup.<br>";
						}

						if(isset($substitutions->home_player)){

							// Debug($substitutions);

							unset($match_lineup);
							$match_lineup['match_id'] = $match_id;
							$chk_match = $this->xml_model->ChkActive('_xml_match_substitutions', 'match_id', $match_lineup);
							// Debug($this->db->last_query());
							// Debug($chk_match);
							if(empty($chk_match)){

								unset($match_lineup);
								unset($home_player);
								$number_player = count($substitutions->home_player);
								for($k=0;$k<$number_player;$k++){
									
									$home_player = $substitutions->home_player[$k];

									$match_lineup[$k]['match_id'] = $match_id;
									$match_lineup[$k]['static_id'] = $static_id;
									$match_lineup[$k]['tournament_id'] = $this->tournament_id;
									$match_lineup[$k]['team_id'] = $localteam_id;
									$match_lineup[$k]['on_id'] = $home_player->player_in_id;
									$match_lineup[$k]['on_number'] = $home_player->player_in_number;
									$match_lineup[$k]['on_name'] = $home_player->player_in_name;
									$match_lineup[$k]['on_booking'] = $home_player->player_in_booking;
									$match_lineup[$k]['off_name'] = $home_player->player_out_name;
									$match_lineup[$k]['minute'] = $home_player->minute;

									// Debug($substitutions);
								}
								// if($query == 1){
								// 	$data_del['match_id'] = $data_update['match_id'];
								// 	$this->xml_model->delete_data('_xml_match_substitutions', $data_del);

									$this->xml_model->import_batch('_xml_match_substitutions', $match_lineup);
									// Debug($this->db->last_query());
									Debug('Import '.$match_id.' _xml_match_substitutions home');
								// }

								unset($match_lineup);
								unset($away_player);
								$number_player = count($substitutions->away_player);
								for($k=0;$k<$number_player;$k++){
									
									$away_player = $substitutions->away_player[$k];

									$match_lineup[$k]['match_id'] = $match_id;
									$match_lineup[$k]['static_id'] = $static_id;
									$match_lineup[$k]['tournament_id'] = $this->tournament_id;
									$match_lineup[$k]['team_id'] = $visitorteam_id;
									$match_lineup[$k]['on_id'] = $away_player->player_in_id;
									$match_lineup[$k]['on_number'] = $away_player->player_in_number;
									$match_lineup[$k]['on_name'] = $away_player->player_in_name;
									$match_lineup[$k]['on_booking'] = $away_player->player_in_booking;
									$match_lineup[$k]['off_name'] = $away_player->player_out_name;
									$match_lineup[$k]['minute'] = $away_player->minute;

									// Debug($substitutions);
								}
								$this->xml_model->import_batch('_xml_match_substitutions', $match_lineup);
								// Debug($this->db->last_query());
								Debug('Import '.$match_id.' _xml_match_substitutions away');
							}else{

								echo "Already have a substitutions player.<br>";
							}

						}else{

							echo "Not Update substitutions.<br>";
						}
					}

				}

			}
		
		}
		*/

	}

	public function update_match_lineup($sel_date = ''){
		$import_match = $xml_data = $stadium = $team = $data_update = $data_program = array();
		$number_match = $query = $debug = $import = $tournament_id = 0;
		$cat = $tournament_name = $season = $country = $msg = '';
		$week = $referee = $match_datetime = null;
		$match_id = 0;
		$debug = $json = 0;
		$host = base_url();
		
		$create_at = date('Y-m-d H:i:s');
		$program_id = 0;
		$sel_date = '';

		if($program_id == 0 && $sel_date == ''){
			$sel_date = date('Y-m-d');
		}

		if($this->input->get('sel_date') != ''){
			
			$sel_date = $this->input->get('sel_date');
		}

		//********** Get XML
		$feed_url = $this->xml_host.'soccerfixtures/worldcup/WorldCup';
		$query = 1;
		$cat = 1056;
		$feed_url .= '?cat='.$cat;

		// Debug($this);
		// Debug($feed_url);
		// die();
		$xml_data = $this->xml_model->get_fixtures_results($feed_url);

		if($debug == 1){
			Debug($feed_url);
			// Debug($xml_data);
			//echo json_encode($xml_data);
		}
		// die();

		if($xml_data){
			foreach($xml_data as $key => $val){

				//echo "<b>$key => $val</b><hr>";
				if($key == 'country') $country = $val;
				if($key == 'id') $tournament_id = $val;
				if($key == 'league') $tournament_name = $val;
				if($key == 'season') $season = $val;

				if(is_array($val)){ //week

					$i=0;
					// Debug($val);
					// die();
					$allm = count($val);
					
					for($i=0;$i<$allm;$i++){

						$stage_name = $val[$i]['stage_name'];
						$stage_round = $val[$i]['stage_round'];
						$gid = $val[$i]['gid'];
						$stage_id = $val[$i]['stage_id'];
						$is_current = $val[$i]['is_current'];
						
						// Debug($val[$i]['stage_name']);
						// Debug($val[$i]['stage_round']);
						// Debug($val[$i]['stage_id']);

						if(isset($val[$i]['week'])){ //week
							// Debug($val[$i]['week']);

							$num_week = count($val[$i]['week']);
							// echo "($num_week)<br>";
							for($j=0;$j<$num_week;$j++){

								$week[$j] = $val[$i]['week'][$j];

								$number_match = $all_match = count($week[$j]['match']);
								// echo "($all_match)<br>";
								// Debug($week[$j]['match']);

								for($k=0;$k<$all_match;$k++){
									$goals = $lineups = $substitutions = $match_lineup = null;
									$rows = $week[$j]['match'][$k];
									// Debug($rows);

									$data_update['match_id'] = $rows['id'];
									$data_update['stage_id'] = $stage_id;
									$data_update['sport'] = 'soccer';
									$data_update['tournament_id'] = $tournament_id;
									$data_update['tournament_name'] = $tournament_name;
									$data_update['week'] = $rows['week'];
									$data_update['match_status'] = $rows['status'];
									$data_update['match_datetime'] = date('Y-m-d H:i', strtotime(str_replace('.', '-', ($rows['date'] . " " . $rows['time']))));
									$data_update['static_id'] = $rows['static_id'];

									$match_datetime = $rows['date'];

									if($rows['venue_id'] > 0){
										$stadium['stadium_id'] = $data_update['stadium_id'] = intval($rows['venue_id']);
										$stadium['stadium_name'] = $data_update['stadium'] = $rows['venue'];										
									}

									// $data_update['attendance'] = $rows['attendance'];
									$data_update['time'] = $rows['time'];
									// $data_update['referee'] = $rows['referee'];
									$data_update['hteam_id'] = $rows['localteam']['id'];
									$data_update['hteam'] = $rows['localteam']['name'];

									if($rows['localteam']['score'] != '')
										$data_update['hgoals'] = $rows['localteam']['score'];

									$data_update['ateam_id'] = $rows['visitorteam']['id'];
									$data_update['ateam'] = $rows['visitorteam']['name'];

									if($rows['visitorteam']['score'] != '')
										$data_update['agoals'] = $rows['visitorteam']['score'];

									$stadium['stadium_city'] = $rows['venue_city'];

									// if(isset($rows['goals']))
										$goals = @$rows['goals'];
									
									// if(isset($rows['lineups']))
										$lineups = @$rows['lineups'];

									// if(isset($rows['substitutions']))
										// $substitutions = $rows['substitutions'];
									
									// Debug($goals);
									// Debug($lineups);
									// Debug($substitutions);
									// die();

									$chk_date = date('d.m.Y', strtotime($sel_date));

									if(($chk_date != '') && ($chk_date == $match_datetime)){
										
										Debug($data_update);

										Debug("($stage_name)(week)($chk_date)(".$data_update['match_id'].")");

										echo "<br>lineups->home_player<br>";
										// Debug($lineups['home_player']);
										$chk_match = $this->xml_model->ChkActive('_xml_match_lineup', 'match_id', $data_update);
										// Debug($this->db->last_query());
										// Debug($lineups['home_player']);
										// die();

										if(isset($lineups['home_player'])){

											// Debug($lineups['home_player']);
											$number_player = count($lineups['home_player']);
											for($l=0;$l<$number_player;$l++){

												$player = $lineups['home_player'][$l];

												unset($match_lineup);
												$match_lineup[$l]['match_id'] = $data_update['match_id'];
												$match_lineup[$l]['team_id'] = $data_update['hteam_id'];
												$match_lineup[$l]['player_id'] = $player['id'];
												$match_lineup[$l]['number'] = $player['number'];
												$match_lineup[$l]['name'] = $player['name'];
												$match_lineup[$l]['booking'] = $player['booking'];

												// echo "<br>".$data_update['match_id']."<br>";
												// Debug($match_lineup[$l]);
												// Debug($chk_match);
												// $query = 0;
												if($query == 1){
													// $match_lineup['match_id'] = $data_update['match_id'];
													// $chk_match = $this->xml_model->ChkActive('_xml_match_lineup', 'match_id', $match_lineup[$l]);

													if(empty($chk_match)){

														$this->xml_model->import('_xml_match_lineup', $match_lineup[$l]);
														// Debug($this->db->last_query());
														$msg = 'Import '.$data_update['match_id'].' _xml_match_lineup home';
													}else{

														$msg =  "Already have a lineup home player.<br>";
													}
												}
											}
											echo $msg;
										}
										// die();

										echo "<br>lineups->away_player<br>";
										// Debug($lineups['away_player']);

										if(isset($lineups['away_player'])){

											// $chk_match = $this->xml_model->ChkActive('_xml_match_lineup', 'match_id', $data_update);

											// Debug($lineups['away_player']);
											$number_player = count($lineups['away_player']);
											for($l=0;$l<$number_player;$l++){

												$player = $lineups['away_player'][$l];
												
												unset($match_lineup);
												$match_lineup[$l]['match_id'] = $data_update['match_id'];
												$match_lineup[$l]['team_id'] = $data_update['ateam_id'];
												$match_lineup[$l]['player_id'] = $player['id'];
												$match_lineup[$l]['number'] = $player['number'];
												$match_lineup[$l]['name'] = $player['name'];
												$match_lineup[$l]['booking'] = $player['booking'];

												// Debug($match_lineup);
												if($query == 1){
													// $match_lineup['match_id'] = $data_update['match_id'];
													// $chk_match = $this->xml_model->ChkActive('_xml_match_lineup', 'match_id', $match_lineup[$l]);

													if(empty($chk_match)){

														$this->xml_model->import('_xml_match_lineup', $match_lineup[$l]);
														// Debug($this->db->last_query());
														$msg = 'Import '.$data_update['match_id'].' _xml_match_lineup away';
													}else{

														$msg = "Already have a lineup away player.<br>";
													}	
												}
											}
											echo $msg;
										}

										echo "<br>substitutions->home_player<br>";
										// Debug($rows['substitutions']['home_player']);

										//substitutions
										if(isset($rows['substitutions']['home_player'])){

											unset($substitutions);
											$chk_match = $this->xml_model->ChkActive('_xml_match_substitutions', 'match_id', $data_update);

											// Debug($lineups['home_player']);
											$number_player = count($rows['substitutions']['home_player']);
											for($l=0;$l<$number_player;$l++){
												
												$player = $rows['substitutions']['home_player'][$l];

												$substitutions[$l]['match_id'] = $data_update['match_id'];
												$substitutions[$l]['static_id'] = $data_update['static_id'];
												$substitutions[$l]['tournament_id'] = $tournament_id;
												$substitutions[$l]['team_id'] = $data_update['hteam_id'];
												$substitutions[$l]['on_id'] = $player['player_in_id'];
												$substitutions[$l]['on_number'] = $player['player_in_number'];
												$substitutions[$l]['on_name'] = $player['player_in_name'];
												$substitutions[$l]['on_booking'] = $player['player_in_booking'];
												$substitutions[$l]['off_name'] = $player['player_out_name'];
												$substitutions[$l]['minute'] = $player['minute'];

												// Debug($substitutions);
												if($query == 1){
													
													if(empty($chk_match)){
		
														$this->xml_model->import('_xml_match_substitutions', $substitutions[$l]);
														// Debug($this->db->last_query());
														$msg = 'Import '.$data_update['match_id'].' _xml_match_substitutions home';
													}else{
		
														$msg = "Already have a substitutions home player.<br>";
													}
												}
											}
											echo $msg;
										}

										echo "<br>substitutions->away_player<br>";
										// Debug($rows['substitutions']['away_player']);

										if(isset($rows['substitutions']['away_player'])){

											unset($substitutions);
											// $chk_match = $this->xml_model->ChkActive('_xml_match_substitutions', 'match_id', $data_update);
											// Debug($lineups['home_player']);
											$number_player = count($rows['substitutions']['away_player']);
											for($l=0;$l<$number_player;$l++){

												$player = $rows['substitutions']['away_player'][$l];

												$substitutions[$l]['match_id'] = $data_update['match_id'];
												$substitutions[$l]['static_id'] = $data_update['static_id'];
												$substitutions[$l]['tournament_id'] = $tournament_id;
												$substitutions[$l]['team_id'] = $data_update['ateam_id'];
												$substitutions[$l]['on_id'] = $player['player_in_id'];
												$substitutions[$l]['on_number'] = $player['player_in_number'];
												$substitutions[$l]['on_name'] = $player['player_in_name'];
												$substitutions[$l]['on_booking'] = $player['player_in_booking'];
												$substitutions[$l]['off_name'] = $player['player_out_name'];
												$substitutions[$l]['minute'] = $player['minute'];

												// Debug($substitutions);
												if($query == 1){

													// $chk_match = $this->xml_model->ChkActive('_xml_match_substitutions', 'match_id', $substitutions[$l]);
													if(empty($chk_match)){
		
														$this->xml_model->import('_xml_match_substitutions', $substitutions[$l]);
														// Debug($this->db->last_query());
														$msg = 'Import '.$data_update['match_id'].' _xml_match_substitutions away';
													}else{
		
														$msg = "Already have a substitutions away player.<br>";
													}
												}
											}
											echo $msg;
										}

										echo "<hr>";
									}

									// if(isset($rows['referee_id'])){
									// 	unset($referee);
									// 	$referee['referee_id'] = $rows['referee_id'];
									// 	$referee['referee_name'] = $rows['referee_name'];

									// 	if($query == 1){
									// 		// Debug($referee);

									// 		if($referee['referee_id'] > 0){
									// 			$this->xml_model->chkupdate_data('_referee', 'referee_id', $referee);
									// 			Debug($this->db->last_query());		
									// 		}
									// 	}

									// 	if(@$referee['referee_id'] > 0){
									// 		$data_update['referee_id'] = $rows['referee_id'];
									// 		$data_update['referee'] = $rows['referee_name'];
									// 	}

									// }

									// die();

									if($debug == 1){

										Debug($data_update);
										// Debug($stadium);
									}

									//*********************Update DATA
									if($query == 1){

										// if($stadium['stadium_id'] > 0){

										// 	$this->xml_model->chkupdate_stadium($stadium);
										// 	Debug($this->db->last_query());
										// }

										// if($data_update['match_id'] > 0){

										// 	$this->xml_model->chkupdate_program($data_update['match_id'], $data_update);
										// 	Debug($this->db->last_query());
										// }
										
										unset($stadium);
										unset($data_update);
									}
								}

							}

						}

						if(isset($val[$i]['match'])){ //match

							$number_match = $all_match = count($val[$i]['match']);
							// echo "($all_match)<br>";
							// Debug($week[$j]['match']);

							for($k=0;$k<$all_match;$k++){
								$goals = $lineups = $substitutions = $match_lineup = null;
								$rows = $val[$i]['match'][$k];
								// Debug($rows);

								$data_update['match_id'] = $rows['id'];
								$data_update['stage_id'] = $stage_id;
								$data_update['sport'] = 'soccer';
								$data_update['tournament_id'] = $tournament_id;
								$data_update['tournament_name'] = $tournament_name;
								// $data_update['week'] = $rows['week'];
								$data_update['match_status'] = $rows['status'];
								$data_update['match_datetime'] = date('Y-m-d H:i:s', strtotime(str_replace('.', '-', ($rows['date'] . " " . $rows['time']))));
								$data_update['static_id'] = $rows['static_id'];

								$match_datetime = $rows['date'];

								if($rows['venue_id'] > 0){
									$stadium['stadium_id'] = $data_update['stadium_id'] = intval($rows['venue_id']);
									$stadium['stadium_name'] = $data_update['stadium'] = $rows['venue'];										
								}

								// $data_update['attendance'] = $rows['attendance'];
								$data_update['time'] = $rows['time'];
								// $data_update['referee'] = $rows['referee'];
								$data_update['hteam_id'] = $rows['localteam']['id'];
								$data_update['hteam'] = $rows['localteam']['name'];

								if($rows['localteam']['score'] != '')
									$data_update['hgoals'] = $rows['localteam']['score'];

								$data_update['ateam_id'] = $rows['visitorteam']['id'];
								$data_update['ateam'] = $rows['visitorteam']['name'];

								if($rows['visitorteam']['score'] != '')
									$data_update['agoals'] = $rows['visitorteam']['score'];

								// $stadium['stadium_city'] = $rows['venue_city'];


								// if(isset($rows['goals']))
									$goals = @$rows['goals'];
								
								// if(isset($rows['lineups']))
									$lineups = @$rows['lineups'];

								// if(isset($rows['substitutions']))
									// $substitutions = $rows['substitutions'];
								
								// Debug($goals);
								// Debug($lineups);
								// Debug($substitutions);
								// die();

								$chk_date = date('d.m.Y', strtotime($sel_date));
								
								if(($chk_date != '') && ($chk_date == $match_datetime)){

									Debug("($stage_name)($chk_date)(".$data_update['match_id'].")");
									$msg = '';
									// Debug($data_update['match_id']);
									// Debug($rows);

									/********** Delete Data _xml_match_lineup & _xml_match_substitutions
									$data_del = null;
									$data_del['match_id'] = intval($data_update['match_id']);
									$this->xml_model->delete_data('_xml_match_lineup', $data_del);
									Debug($this->db->last_query());
									$this->xml_model->delete_data('_xml_match_substitutions', $data_del);
									Debug($this->db->last_query());
									*/
									$chk_player = null;

									if(isset($lineups['home_player'])){

										// Debug($lineups['home_player']);
										$number_player = count($lineups['home_player']);
										for($l=0;$l<$number_player;$l++){

											$player = $lineups['home_player'][$l];
											unset($match_lineup);

											$match_lineup[$l]['match_id'] = $data_update['match_id'];
											$match_lineup[$l]['team_id'] = $data_update['hteam_id'];
											$match_lineup[$l]['player_id'] = $player['id'];
											$match_lineup[$l]['number'] = $player['number'];
											$match_lineup[$l]['name'] = $player['name'];
											$match_lineup[$l]['booking'] = $player['booking'];

											// Debug($match_lineup);
											if($query == 1){

												$chk_match = $this->xml_model->chkupdate_match_lineup($match_lineup[$l]);
												// Debug($this->db->last_query());

												if(empty($chk_match)){

													$this->xml_model->import('_xml_match_lineup', $match_lineup[$l]);
													// Debug($this->db->last_query());
													$msg = '<br>Import '.$data_update['match_id'].' _xml_match_lineup home '.$player['id'];
												}else{

													$msg = "<br>Already have a lineup home player.<br>";
												}
											}
										}
										echo $msg;
									}

									$msg = '';
									if(isset($lineups['away_player'])){

										// Debug($lineups['away_player']);
										$number_player = count($lineups['away_player']);
										for($l=0;$l<$number_player;$l++){

											$player = $lineups['away_player'][$l];
											unset($match_lineup);

											$match_lineup[$l]['match_id'] = $data_update['match_id'];
											$match_lineup[$l]['team_id'] = $data_update['ateam_id'];
											$match_lineup[$l]['player_id'] = $player['id'];
											$match_lineup[$l]['number'] = $player['number'];
											$match_lineup[$l]['name'] = $player['name'];
											$match_lineup[$l]['booking'] = $player['booking'];

											// Debug($match_lineup);
											if($query == 1){

												$chk_match = $this->xml_model->chkupdate_match_lineup($match_lineup[$l]);
												// Debug($this->db->last_query());

												if(empty($chk_match)){

													$this->xml_model->import('_xml_match_lineup', $match_lineup[$l]);
													// Debug($this->db->last_query());
													$msg = '<br>Import '.$data_update['match_id'].' _xml_match_lineup away '.$player['id'];
												}else{

													$msg = "<br>Already have a lineup away player.<br>";
												}
											}
										}
										echo $msg;
									}

									//substitutions
									$msg = '';
									if(isset($rows['substitutions']['home_player'])){

										$number_player = count($rows['substitutions']['home_player']);
										for($l=0;$l<$number_player;$l++){
											
											$player = $rows['substitutions']['home_player'][$l];
											unset($substitutions);

											$substitutions[$l]['match_id'] = intval($data_update['match_id']);
											$substitutions[$l]['static_id'] = $data_update['static_id'];
											$substitutions[$l]['tournament_id'] = $tournament_id;
											$substitutions[$l]['team_id'] = intval($data_update['hteam_id']);
											$substitutions[$l]['on_id'] = intval($player['player_in_id']);
											$substitutions[$l]['on_number'] = $player['player_in_number'];
											$substitutions[$l]['on_name'] = $player['player_in_name'];
											$substitutions[$l]['on_booking'] = $player['player_in_booking'];
											$substitutions[$l]['off_name'] = $player['player_out_name'];
											$substitutions[$l]['minute'] = $player['minute'];

											// Debug($substitutions);
											if($query == 1){

												$chk_match = $this->xml_model->chkupdate_match_substitutions($substitutions[$l]);
												// Debug($this->db->last_query());

												if(empty($chk_match)){

													$this->xml_model->import('_xml_match_substitutions', $substitutions[$l]);
													// Debug($this->db->last_query());
													$msg = '<br>Import '.$data_update['match_id'].' _xml_match_substitutions home '.intval($player['player_in_id']);
												}else{

													$msg = "<br>Already have a substitutions home player.<br>";
												}
											}
										}
										echo $msg;
									}

									$msg = '';
									if(isset($rows['substitutions']['away_player'])){
										
										$number_player = count($rows['substitutions']['away_player']);
										for($l=0;$l<$number_player;$l++){

											$player = $rows['substitutions']['away_player'][$l];
											unset($substitutions);

											$substitutions[$l]['match_id'] = intval($data_update['match_id']);
											$substitutions[$l]['static_id'] = $data_update['static_id'];
											$substitutions[$l]['tournament_id'] = $tournament_id;
											$substitutions[$l]['team_id'] = intval($data_update['ateam_id']);
											$substitutions[$l]['on_id'] = intval($player['player_in_id']);
											$substitutions[$l]['on_number'] = $player['player_in_number'];
											$substitutions[$l]['on_name'] = $player['player_in_name'];
											$substitutions[$l]['on_booking'] = $player['player_in_booking'];
											$substitutions[$l]['off_name'] = $player['player_out_name'];
											$substitutions[$l]['minute'] = $player['minute'];

											// Debug($substitutions);
											if($query == 1){

												$chk_match = $this->xml_model->chkupdate_match_substitutions($substitutions[$l]);
												// Debug($this->db->last_query());

												if(empty($chk_match)){

													$this->xml_model->import('_xml_match_substitutions', $substitutions[$l]);
													// Debug($this->db->last_query());
													$msg = '<br>Import '.$data_update['match_id'].' _xml_match_substitutions away '.intval($player['player_in_id']);
												}else{

													$msg = "<br>Already have a substitutions away player.<br>";
												}
											}
										}
									}
									echo $msg;

									if(isset($rows['penalties'])){

										$match_pen = $chk_match = null;
										Debug($rows['penalties']);

										$chk_match = $this->xml_model->chkupdate_match_penalties($data_update);
										// Debug($this->db->last_query());
										// Debug($chk_match);

										$number_penalties = count($rows['penalties']);
										for($l=0;$l<$number_penalties;$l++){

											$penalties = $rows['penalties'][$l];
											unset($match_pen);
											// Debug($penalties);

											$match_pen[$l]['match_id'] = $data_update['match_id'];
											$match_pen[$l]['team'] = $penalties['team'];
											$match_pen[$l]['minute'] = $penalties['minute'];
											$match_pen[$l]['playerid'] = $penalties['playerid'];
											$match_pen[$l]['player'] = $penalties['player'];
											$match_pen[$l]['score'] = $penalties['score'];
											$match_pen[$l]['scored'] = ($penalties['scored'] == 'True') ? 1:0;

											// Debug($match_pen[$l]);
											if($query == 1){

												if(empty($chk_match)){

													$this->xml_model->import('_xml_match_penalties', $match_pen[$l]);
													// Debug($this->db->last_query());
													$msg = '<br>Import '.$data_update['match_id'].' _xml_match_lineup '.$penalties['player'];
												}else{

													$msg = "<br>Already have a penalties player.<br>";
												}
											}
										}
										echo $msg;
									}
									echo "<hr>";
								}

								if($debug == 1){
									Debug($data_update);
									// Debug($stadium);
								}

								//*********************Update DATA
								if($query == 1){

									if(@$stadium['stadium_id'] > 0){
										// $this->xml_model->chkupdate_stadium($stadium);
										// Debug($this->db->last_query());
									}

									if($data_update['match_id'] > 0){
										// $this->xml_model->chkupdate_program($data_update['match_id'], $data_update);
										// Debug($this->db->last_query());
									}
									unset($stadium);
									unset($data_update);
								}
							}

							/*********************Update DATA
							if($query == 1){

								if($week[$i]['match'][$j]['match_id'] > 0){

									Debug($week[$i]['match'][$j]);
									echo "Update...";
									$this->xml_model->update('_xml_match', 'match_id', $week[$i]['match'][$j]);
									Debug($this->db->last_query());
								}
							}*/

						}
						
						
					}
				}

			}
		}
	}

	public function update_match_event(){

		if($this->uri->segment(3) == 'json') header('Content-Type: application/json  charset=utf-8');

		$this->load->model('tournament_model');
		$this->load->model('program_model');

		$query = $import = $debug = $number_match = $all = 0;
		$cat = '';
		$xml_data = $item = $obj_item = $leagues_arr = $matches = $view_match = $xml_match = array();

		if($this->uri->segment(3) == 'query') $query = 1;
		if($this->uri->segment(3) == 'import') $import = 1;
		if($this->uri->segment(3) == 'debug') $debug = 1;
		
		if($this->uri->segment(5) == 'all') 
			$all = 1;

		if($this->uri->segment(4) == 'd1') 
			$feed_url = $this->xml_host.$this->xml_news_tomorrow;
		else if($this->uri->segment(4) == 'd-1') 
			$feed_url = $this->xml_host.$this->xml_news_yesterday;
		else if(($this->uri->segment(4) == 'lives') || ($this->uri->segment(4) == 'home')) 
			$feed_url = $this->xml_host.$this->xml_news_lives;
		else if(!$this->uri->segment(4))
			$feed_url = $this->xml_host.$this->xml_news_lives;
		else{

			switch($this->uri->segment(4)){
				case 'worldcup': $feed_url = $this->xml_host.'soccernew/worldcup'; break;
				case 'worldcup_shedule': $feed_url = $this->xml_host.'soccernew/worldcup_shedule'; break;
				default:

					$feed_url = $this->xml_host.'soccernew/'.trim($this->uri->segment(4));
					// $feed_url = $this->xml_host.$this->xml_fixtures_results; break;
					// $feed_url = $this->xml_host.'soccernew/england/'.trim($this->uri->segment(4)); break;
				break;
			}

			// $feed_url = $this->xml_host.'soccernew/'.$this->uri->segment(4).'?cat='.$cat;
			//$feed_shedule_url = $this->xml_host.'soccernew/'.$this->uri->segment(4).'_shedule?cat='.$cat;
		}

		if($this->input->get('cat')) $cat = $this->input->get('cat');
		if($cat != '')
			$feed_url .= '?cat='.$cat;

		if($debug == 1) Debug($feed_url);

		$xml_data = $this->xml_model->get_soccernew($feed_url);

		if($debug == 1){
			// Debug($xml_data);
		}
		//die();

		if($xml_data){
			$league = $xml_data['category'];
			$all_league = count($league);
			//echo "all_league = $all_league<br>";
			$j = $ii = 0;
			for($i=0;$i<$all_league;$i++){

				$obj_item[$i]['tournament_id'] = $league[$i]['id'];
				$obj_item[$i]['tournament_name_en'] = $league[$i]['name'];
				$obj_item[$i]['file_group'] = $league[$i]['file_group'];	//Country league

				//$obj_item[$i]['iscup'] = ($league[$i]['iscup'] === "False") ? 0:1;
				$obj_item[$i]['iscup'] = $league[$i]['iscup'];
				
				if($import == 1){
				 	$obj_item[$i]['status'] = 1;
					$obj_item[$i]['create_date'] = date('Y-m-d H:i:s');
				}

				if($query == 1){
					$obj_item[$i]['lastupdate_date'] = date('Y-m-d H:i:s');
				}
				//Add new tournament if no data
				if($import == 1) $this->xml_model->chkupdate_data('_tournament', 'tournament_id', $obj_item[$i]);
				//Exam. 1397 = Spain: Copa Del Rey

				//Update data tournament
				//if($query == 1) $this->xml_model->update('_tournament', 'tournament_id', $obj_item[$i]);

				//Debug($obj_item[$i]);

				/*********************ตรวจสอบว่า ใช้งาน League นี้หรือไม่********/
				$arr = array();
				unset($chklegue);
				$arr['tournament_id'] = $league[$i]['id'];
				//$arr['status'] = 1;
				$chklegue = $this->xml_model->ChkActive('_tournament', 'tournament_id', $arr);
				
				if(isset($chklegue[0]['tournament_id'])){
					if($chklegue[0]['tournament_id'] == $league[$i]['id']){
					//if(($league[$i]['id'] == 1204) || ($league[$i]['id'] == 1397)){
					
						$count_matches = $league[$i]['matches']['count_match'];
						//Debug($count_matches);
						for($j=0;$j<$count_matches;$j++){
							//Debug($league[$i]['id']['matches']['match'][$j]);

							$view_match = $league[$i]['matches']['match'];
							// Debug($view_match);

							$league_id = $league[$i]['id'];

							$matches[$league_id][$j]['id'] = $view_match[$j]['id'];
							$matches[$league_id][$j]['date'] = $view_match[$j]['date'];
							$matches[$league_id][$j]['formatted_date'] = $view_match[$j]['formatted_date'];
							$matches[$league_id][$j]['status'] = $view_match[$j]['status'];
							$matches[$league_id][$j]['time'] = $view_match[$j]['time'];
							$matches[$league_id][$j]['static_id'] = $view_match[$j]['static_id'];
							//$matches[$league_id][$j]['static_id'] = $view_match[$j]['static_id'];
							$matches[$league_id][$j]['fix_id'] = $view_match[$j]['fix_id'];

							$matches[$league_id][$j]['home']['id'] = $view_match[$j]['localteam']['id'];
							$matches[$league_id][$j]['home']['name'] = $view_match[$j]['localteam']['name'];
							$matches[$league_id][$j]['home']['goals'] = $view_match[$j]['localteam']['goals'];

							$matches[$league_id][$j]['away']['id'] = $view_match[$j]['visitorteam']['id'];
							$matches[$league_id][$j]['away']['name'] = $view_match[$j]['visitorteam']['name'];
							$matches[$league_id][$j]['away']['goals'] = $view_match[$j]['visitorteam']['goals'];

							$matches[$league_id][$j]['ht'] = $view_match[$j]['ht'];
							
							$matches[$league_id][$j]['events'] = @$view_match[$j]['events'];

							// Debug($view_match);
							// echo "($cat, $query)<hr>";

							// if($cat != '' && $query = 1){
							if($query == 1){

								//$formatted_date = str_replace(".", "/", $matches[$league_id][$j]['formatted_date']);
								list($d, $m, $y) = explode(".", $matches[$league_id][$j]['formatted_date']);

								$formatted_date = "$y-$m-$d";
								$time = $matches[$league_id][$j]['time'];
								$datetime = date('Y-m-d', strtotime($formatted_date)).' '.$time;
								//Debug($formatted_date);

								if($matches[$league_id][$j]['home']['goals'] != '?')
									$result_match = $matches[$league_id][$j]['home']['goals'].'-'.$matches[$league_id][$j]['away']['goals'];
								else
									$result_match = '';


								$all_event = @count($matches[$league_id][$j]['events']);

								for($k=0;$k<$all_event;$k++){

									$rows_event = $matches[$league_id][$j]['events'][$k];

									$xml_match = array(
										'eventid' => $rows_event['eventid'],
										'type' => $rows_event['type'],		//goal, yellowcard, subst, var
										'team' => $rows_event['team'],		//localteam, visitorteam
										'minute' => $rows_event['minute'],
										'result' => $rows_event['result'],
										'playerid' => intval($rows_event['playerid']),
										'player' => $rows_event['player'],
										'assistid' => intval($rows_event['assistid']),
										'assist' => $rows_event['assist'],
										'match_id' => $matches[$league_id][$j]['fix_id'],
										'program_id' => $matches[$league_id][$j]['id']
									);
									// Debug($xml_match);

									$this->xml_model->chkupdate_event($rows_event['eventid'], $xml_match);
								}

								
								// Debug($xml_match);
								// die();

								//Program worldcup 2022 only
								// if($league_id == 1056){

									// $this->program_model->chk_program($matches[$league_id][$j]['id'], $dataupdate);

									// $this->xml_model->chkupdate_program($matches[$league_id][$j]['fix_id'], $xml_match);
									// Debug($this->db->last_query());
									$number_match++;
								// }

							}
						}
						// Debug($matches);
						// die();
						$leagues_arr[$ii] = $obj_item[$i];
						$leagues_arr[$ii]['matches'] = $matches;
						$ii++;
					}
				}else{
					unset($league[$i]);
				}
			}		
		}

		if($debug == 1){
			if($all == 1){

				echo "<br>".count($xml_data['category'])."<br>";
				Debug($xml_data['category']);
			}else{

				echo "<br>".count($leagues_arr)."<br>";
				Debug($leagues_arr);
			}

			// Debug('all match : '.$number_match);
		}
		if($query == 1){
			
			Debug('all match : '.$number_match);
		}

		if($xml_data){

			$item['head']['code'] = 200;
			$item['head']['msg'] = 'Success';
			if($all == 1)
				$item['head']['total'] = count($xml_data['category']);
			else
				$item['head']['total'] = count($leagues_arr);
		}else{

			$item['head']['code'] = 404;
			$item['head']['msg'] = 'File not found';
		}
		
		if($all == 1)
			$item['data'] = $xml_data['category'];
		else
			$item['data'] = $leagues_arr;

		if($this->uri->segment(3) == 'json') echo json_encode($item);	
	}

	public function import_highlights(){	
		$data_store = $chk_match = $chk_data = null;

		if($this->uri->segment(3) == 'json') header('Content-Type: application/json');

		$query = 0;
		$debug = 0;
		$table = '_highlights';

		if($this->uri->segment(3) == 'query') $query = 1;
		if($this->uri->segment(3) == 'debug') $debug = 1;

		$item = $xml_data = $standings = array();

		switch($this->uri->segment(4)){

			case 'home': $feed_url = $this->xml_host.'soccerhighlights/home'; break;
			case 'd-1': $feed_url = $this->xml_host.'soccerhighlights/d-1'; break;
			case 'd-2': $feed_url = $this->xml_host.'soccerhighlights/d-2'; break;
			case 'd-3': $feed_url = $this->xml_host.'soccerhighlights/d-3'; break;
			case 'd-4': $feed_url = $this->xml_host.'soccerhighlights/d-4'; break;
			case 'd-5': $feed_url = $this->xml_host.'soccerhighlights/d-5'; break;
			case 'd-6': $feed_url = $this->xml_host.'soccerhighlights/d-6'; break;
			case 'd-7': $feed_url = $this->xml_host.'soccerhighlights/d-7'; break;
			default:

				// $feed_url = $this->xml_host.'soccerhighlights/'.trim($this->uri->segment(4));
				$feed_url = $this->xml_host.'soccerhighlights/d-1';
				// $feed_url = 'https://www.goalserve.com/getfeed/c37c46d4313e43a899a3a3d00cbd454b/soccerhighlights/d-1';

			break;
		}

		// $feed_url = 'https://www.goalserve.com/getfeed/c37c46d4313e43a899a3a3d00cbd454b/soccerhighlights/d-1';
		// $feed_url = $this->xml_host.$this->xml_topscorers;

		$xml_data = $this->xml_model->get_highlights($feed_url);
		
		if($debug == 1) Debug($xml_data);

		if($xml_data){
			$league = $xml_data['category'];
			$all_league = count($league);
			//echo "all_league = $all_league<br>";
			$j = $ii = 0;
			for($i=0;$i<$all_league;$i++){

				$obj_item[$i]['tournament_id'] = $league[$i]['id'];
				$obj_item[$i]['tournament_name_en'] = $league[$i]['name'];
				
				$num_matches = count($league[$i]['matches']['match']);
				for($j=0;$j<$num_matches;$j++){

					$matchs = $league[$i]['matches']['match'][$j];

					$match_id = $matchs['id'];
					$match_status = $matchs['status'];
					$formatted_date = $matchs['formatted_date'];
					$time = $matchs['time'];
					$static_id = $matchs['static_id'];
					$fix_id = $matchs['fix_id'];

					$localteam_id = $matchs['localteam']['id'];
					$localteam_name = $matchs['localteam']['name'];
					$localteam_goals = $matchs['localteam']['goals'];

					$visitorteam_id = $matchs['visitorteam']['id'];
					$visitorteam_name = $matchs['visitorteam']['name'];
					$visitorteam_goals = $matchs['visitorteam']['goals'];

					// chkupdate_highlights
					$chk_data['match_id'] = $match_id;
					$chk_match = $this->xml_model->chkupdate_highlights($chk_data);

					if(isset($matchs['videos'])){
						
						// $videos = $matchs['videos'];
						$num_videos = count($matchs['videos']);
						for($k=0;$k<$num_videos;$k++){

							$data_store[$k]['match_id'] = $match_id;
							$data_store[$k]['fix_id'] = $fix_id;
							$data_store[$k]['static_id'] = $static_id;
							$data_store[$k]['tournament_id'] = $obj_item[$i]['tournament_id'];
							$data_store[$k]['tournament_name'] = $obj_item[$i]['tournament_name_en'];
							$data_store[$k]['match_date'] = date('Y-m-d', strtotime($formatted_date));
							$data_store[$k]['match_time'] = $time;
							$data_store[$k]['match_status'] = $match_status;
							$data_store[$k]['localteam_id'] = $localteam_id;
							$data_store[$k]['localteam_name'] = $localteam_name;
							$data_store[$k]['localteam_goals'] = $localteam_goals;
							$data_store[$k]['visitorteam_id'] = $visitorteam_id;
							$data_store[$k]['visitorteam_name'] = $visitorteam_name;
							$data_store[$k]['visitorteam_goals'] = $visitorteam_goals;

							$data_store[$k]['videos_item'] = $matchs['videos'][$k]['value'];

							if($query == 1){
								
								if(empty($chk_match)){
									// $this->xml_model->import('_highlights', $data_store[$k]);
									// Debug($this->db->last_query());
								}
							}
							
						}
					}

					// $files = $matchs['files'];
					$num_files = count($matchs['files']);
					for($k=0;$k<$num_files;$k++){

						$data_store[$k]['match_id'] = $match_id;
						$data_store[$k]['fix_id'] = $fix_id;
						$data_store[$k]['static_id'] = $static_id;
						$data_store[$k]['tournament_id'] = $obj_item[$i]['tournament_id'];
						$data_store[$k]['tournament_name'] = $obj_item[$i]['tournament_name_en'];
						$data_store[$k]['match_date'] = date('Y-m-d', strtotime($formatted_date));
						$data_store[$k]['match_time'] = $time;
						$data_store[$k]['match_status'] = $match_status;
						$data_store[$k]['localteam_id'] = $localteam_id;
						$data_store[$k]['localteam_name'] = $localteam_name;
						$data_store[$k]['localteam_goals'] = $localteam_goals;
						$data_store[$k]['visitorteam_id'] = $visitorteam_id;
						$data_store[$k]['visitorteam_name'] = $visitorteam_name;
						$data_store[$k]['visitorteam_goals'] = $visitorteam_goals;

						$data_store[$k]['files_item'] = $matchs['files'][$k]['value'];

						if($query == 1){
							
							if(empty($chk_match)){
								$this->xml_model->import('_highlights', $data_store[$k]);
								Debug($this->db->last_query());
							}
						}
						
					}

					unset($data_store);

					if(isset($matchs['clips'])){
					
						$num_clips = count($matchs['clips']);
						for($k=0;$k<$num_clips;$k++){

							$data_store[$k]['match_id'] = $match_id;
							$data_store[$k]['fix_id'] = $fix_id;
							$data_store[$k]['static_id'] = $static_id;
							$data_store[$k]['tournament_id'] = $obj_item[$i]['tournament_id'];
							$data_store[$k]['tournament_name'] = $obj_item[$i]['tournament_name_en'];
							$data_store[$k]['match_date'] = date('Y-m-d', strtotime($formatted_date));
							$data_store[$k]['match_time'] = $time;
							$data_store[$k]['match_status'] = $match_status;
							$data_store[$k]['localteam_id'] = $localteam_id;
							$data_store[$k]['localteam_name'] = $localteam_name;
							$data_store[$k]['localteam_goals'] = $localteam_goals;
							$data_store[$k]['visitorteam_id'] = $visitorteam_id;
							$data_store[$k]['visitorteam_name'] = $visitorteam_name;
							$data_store[$k]['visitorteam_goals'] = $visitorteam_goals;

							$data_store[$k]['clips_item'] = $matchs['clips'][$k]['value'];
							$data_store[$k]['score'] = $matchs['clips'][$k]['score'];
							$data_store[$k]['minute'] = $matchs['clips'][$k]['minute'];
							$data_store[$k]['player_id'] = $matchs['clips'][$k]['playerId'];
							$data_store[$k]['player'] = $matchs['clips'][$k]['player'];

							if($query == 1){

								if(empty($chk_match)){
									$this->xml_model->import('_highlights', $data_store[$k]);
									Debug($this->db->last_query());
								}
							}
						}
					}
					
				}

			}
		}

		if($xml_data){
			$item['head']['code'] = 200;
			$item['head']['msg'] = 'Success';
			$item['head']['total'] = count($xml_data);
		}else{
			$item['head']['code'] = 404;
			$item['head']['msg'] = 'File not found';
		}

		if($query == 1){

			$item['head']['method'] = 'add';
			// $this->xml_model->get_standings($feed_url, 1);
			// $this->xml_model->import($table, $xml_data);

			// Debug($xml_data);
			// $this->xml_model->delete_data($table, array('tournament_id' => intval($xml_data[0]['tournament_id'])));
			// Debug($this->db->last_query());
			// $this->xml_model->import_batch($table, $xml_data);
			echo "Import success.";
			// Debug($this->db->last_query());
		}
		
		$item['data'] = $xml_data;
		if($this->uri->segment(3) == 'json') echo json_encode($item);
	}

	private function callApi($action, $key = null, $use_cache = true, $host = '', $json_decode = true, $showdebug = false){

		if($host == ''){

			$host = $this->hostapi;
		}
		
		$curl = curl_init();
		$url = $host.$action;

		if($showdebug == true)
			echo $url;

		$opt = array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 3,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET'
		);

		curl_setopt_array($curl, $opt);
		$response = curl_exec($curl);

		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			if($use_cache){
				return json_decode($this->ci->utils->getCacheRedis($key));
			}else{
				return false;
			}
		} else {

			if($json_decode == true){

				$res = json_decode($response);
			}else
				$res = $response;
			

			return $res;
		}
	}
}