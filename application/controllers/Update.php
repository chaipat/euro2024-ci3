<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Update extends CI_Controller {
	protected $season;
	protected $tournament_id;
	protected $tournament;
	protected $date_start;
	protected $datetime_start;
	protected $team_path;
	protected $stadium_path;
	protected $base_path;
	protected $_page = 'team';
	protected $_cache;
	protected $hostapi;
	protected $token;
	public function __construct(){
        parent::__construct();

		$this->load->database();

		$this->load->model('team_model');
		$this->load->helper('common');

		$this->tournament_id = $this->config->config['tournament_id'];
		$this->tournament = $this->config->config['tournament'];
        $this->season = $this->config->config['season'];
 		$this->date_start = $this->config->config['date_start'];
		$this->datetime_start = $this->config->config['datetime_start'];

		$this->team_path = 'data/uploads/teamlogo/';
		$this->stadium_path = 'data/uploads/stadium/';
		$this->base_path = str_replace('application/controllers', '', __DIR__);

		$this->hostapi = '';

		$this->token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ0b2tlbiB3b3JsZGN1cCAyMDIyIiwiaWF0IjoxNjY4MjI0Nzk4LCJleHAiOjE2NzI0NTgzOTgsImF1ZCI6Imh0dHBzOi8vd29ybGRjdXAyMDIyLmJhbGxuYWphLmNvbSIsInN1YiI6IuC4n-C4uOC4leC4muC4reC4peC5guC4peC4gSAyMDIyIn0.JkJSAWfcFEIVi0wKAjDZsP-gisoHeqAns_tTe3whphg';
	}

	public function index()
	{
		// redirect('/');
	}

	public function create_jwt()
	{

		/*
		http://jwtbuilder.jamiekurtz.com/
		{
			"iss": "token worldcup 2022",
			"iat": 1668224798,
			"exp": 1672458398,
			"aud": "https://worldcup2022.ballnaja.com",
			"sub": "ฟุตบอลโลก 2022"
		}
		key = qwertyuiopasdfghjklzxcvbnm123456
		HS256
		*/

	}

	//Update stat player by match
	public function match_event($program_id){
		$this->load->model('match_model');
		$this->load->model('team_model');
		$this->load->model('fixtures_model');

		$match_event = $match_lineup = $match_substitutions = array();

		$obj_list = $this->fixtures_model->get_data($program_id, 0, $this->tournament_id);
		// Debug($obj_list);

		$fix_id = $obj_list[0]->fix_id;

		$match_event = $this->match_model->getmatch_event($program_id);
		// Debug($this->db->last_query());
		// Debug($match_event);

		$match_lineup = $this->match_model->getmatch_lineup($fix_id);
		// Debug($match_lineup);
		// die();

		$match_substitutions = $this->match_model->getmatch_substitutions($fix_id);
		// Debug($match_substitutions);

		//************ Add stat lineup */
		echo "<h1>Update stat match player</h1>";

		echo "<h2>Lineup</h2>";
		$add_minute = 90;
		$number = count($match_lineup);
		for($i=0;$i<$number;$i++){

			$rows = $match_lineup[$i];

			$match_id = $rows->match_id;
			$team_id = $rows->team_id;
			$player_id = $rows->player_id;
			$booking = $rows->booking;
			$player_name_th = $rows->player_name_th;

			if($player_id > 0){

				echo "<hr><p>".$booking." (".$player_id.") ".$player_name_th."</p>";

				$this->team_model->add_stat_player($player_id, 'appearences');
				$this->team_model->add_stat_player($player_id, 'lineups');
				$this->team_model->add_minute($player_id, $add_minute);
			}
				
		}

		//************ Add stat substitutions */
		echo "<hr><h2>Substitutions</h2>";
		unset($rows);
		$number = count($match_substitutions);
		for($i=0;$i<$number;$i++){

			$rows = $match_substitutions[$i];
			// Debug($rows);

			$match_id = $rows->match_id;
			$team_id = $rows->team_id;
			$player_id = intval($rows->on_id);
			$booking = trim($rows->on_booking);
			$minute = intval($rows->minute);
			$player_name_th = $rows->player_name_th;
			
			// Debug($player_id.' '.$minute);
			$add_minute = 0;

			if($player_id > 0 && $minute > 0){

				$add_minute = 90 - $minute;
				// echo "<hr><p>".$booking." ".$minute." (".$player_id.") ".$player_name_th."</p>";

				$this->team_model->add_stat_player($player_id, 'appearences');
				$this->team_model->add_stat_player($player_id, 'substitute_in');
				// $this->team_model->add_minute($player_id, $add_minute);

			}
		}

		//************ Add stat match event */
		echo "<hr><h2>Match Event</h2>";
		unset($rows);
		$number = count($match_event);
		for($i=0;$i<$number;$i++){

			$rows = $match_event[$i];
			$event_name = $assist_name = '';

			// Debug($rows);

			$eventid = $rows->eventid;
			$type = $rows->type;
			$team = $rows->team;
			$minute = $rows->minute;
			$result = $rows->result;
			$playerid = $rows->playerid;
			$player_name = ($rows->player_name_th != '') ? $rows->player_name_th: $rows->player;
			$assistid = $rows->assistid;
			$assist = $rows->assist;

			if($assistid == 0){
				$event_name = $rows->assist;
			}else
				$assist_name = ($rows->assist_name_th != '') ? $rows->assist_name_th: $rows->assist;

			echo "<hr>";
			$icon_event = $this->event_type($type, $playerid, $assistid);
			// if($team == 'localteam'){

			// 	$team_id = $hometeam_id;
			// 	$team_name = $hometeam_title;
			// }else{

			// 	$team_id = $awayteam_id;
			// 	$team_name = $awayteam_title;
			// }

			echo "<p>".$icon_event." (".$playerid.") ".$player_name."</p>";

			if($assistid > 0 && $type == 'goal'){

				$icon_event = '<img src="'.base_url(_ASSITS).'" height="20" alt="assits" />';
				echo "<p>".$icon_event." (".$assistid.") ".$assist."</p>";
				
				if($assistid > 0)
					$this->team_model->add_stat_player($assistid, 'assists');
			}elseif($type == 'subst'){

				if($playerid > 0 && $assistid > 0){
					$add_minute = 90 - $minute;

					//Player IN
					$this->team_model->add_minute($playerid, $add_minute);

					//Player OUT
					$del_minute = $add_minute * -1;
					$this->team_model->add_minute($assistid, $del_minute);
				}
			}
			// $data_update[$playerid]['goals'] 
			// $this->team_model->add_stat_player($playerid);
		}

	}

	//Check Update lineup match by match_id
	public function match_lineup($program_id = 0, $sel_date = ''){
		$this->load->model('xml_model');

		$match_id = 0;
		$host = base_url();
		$action = 'xml/import_fixtures_results/json/euro';
		$link_chkdata = base_url($action);
		$create_at = date('Y-m-d H:i:s');
		// echo $link_chkdata."<hr>";

		if($program_id == 0 && $sel_date == ''){
			$sel_date = date('Y-m-d');
		}
		echo $action;

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

								/*if($query == 1){
									$data_del['match_id'] = $match_id;
									$this->xml_model->delete_data('_xml_match_lineup', $data_del);

									$this->xml_model->import_batch('_xml_match_lineup', $match_lineup);
									Debug($this->db->last_query());
									Debug('Import _xml_match_lineup home');
								}*/

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

								/*if($query == 1){
									$data_del['match_id'] = $match_id;
									$this->xml_model->delete_data('_xml_match_lineup', $data_del);

									$this->xml_model->import_batch('_xml_match_lineup', $match_lineup);
									Debug($this->db->last_query());
									Debug('Import _xml_match_lineup home');
								}*/

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

	}

	private function event_type($type, $playerid, $assistid)
	{
		$this->load->model('team_model');
		$output = '';

		switch($type){
			case "var" :
				$output = '('.$type.')';
				break;
			case "yellowcard" :
				$output = '<img src="'.base_url(_YELLOW).'" height="20" alt="'.$type.'" />';
				if($playerid > 0)
					$this->team_model->add_stat_player($playerid, 'yellowcards');
				break;
			case "redcard" :
				$output = '<img src="'.base_url(_RED).'" height="20" alt="'.$type.'" />';
				if($playerid > 0)
					$this->team_model->add_stat_player($playerid, 'redcards');
				break;
			case "goal" :
				$output = '<img src="'.base_url(_GOAL).'" height="20" alt="'.$type.'" />';
				if($playerid > 0)
					$this->team_model->add_stat_player($playerid, 'goals');
				break;
			// case "subst" :
				// $output = '<img src="'.base_url(_CHG_IN).'" alt="'.$type.'" /><img src="'.base_url(_CHG_OUT).'" alt="'.$type.'" />';
				// if($playerid > 0 && $assistid > 0){

				// }
				// break;
			default :
				$output = '('.$type.')';
				break;
		}
		return $output;
	}

	public function fixture($sel_date = '')
	{
		$this->load->model('fixtures_model');

		$datebetween = null;
		$cur_date = date('Y-m-d');

		if($sel_date != ''){

			if($sel_date == 'all'){

				$date1 = '2022-11-20';
				$date2 = '2022-12-20';
			}else{
				
				$date1 = $sel_date;
				$date2 = date('Y-m-d', strtotime($sel_date." 2 days"));
			}
			
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
        // $obj_list = $this->fixtures_model->get_xml($this->tournament_id);

        // echo $this->db->last_query();
		// Debug($obj_list);
		// die();
		$html = $this->display_program($obj_list);
		// echo "<hr>".$html;

		$data = array(
			"meta" => null,
            "webtitle" => 'fixtures',
			"head" => 'โปรแกรมบอลยูโร ผลบอลยูโร',
			"html" => $html,
			"content_view" => 'tool/blank'
		);
        $this->load->view('template',$data);
	}

	public function display_program($obj_list){
		$html = $tmp = '';

		$datenow = date('Y-m-d');

		if($this->input->get('reset_stat') == 1){

			$this->team_model->reset_stat_player();
			Debug($this->db->last_query());
		}
		// $html = anchor();

		if ($obj_list){

			$allitem = count($obj_list);
		
			for ($i = 0; $i < $allitem; $i++) {

				$rows = $obj_list[$i];
				// Debug($rows);

				$program_id = $rows->program_id;
				$fix_id = $rows->fix_id;
				$static_id = $rows->static_id;
				$league_id = $rows->league_id;
				$sel_date = $rows->sel_date;
				$stadium_id = $rows->stadium_id;

				// $tournament_name = $rows->tournament_name;
				// $tournament_name_en = $rows->tournament_name_en;
				$file_group = $rows->file_group;
				$season_name = $rows->season_name;
				$kickoff = $rows->kickoff;
				$week = $rows->week;
				$program_status = $rows->program_status;
				$stadium_name = ($rows->stadium_name_th != '') ? $rows->stadium_name_th : $rows->stadium_name;
				$channel_name = $rows->channel_name;
				$group_name = str_replace('Group', 'กลุ่ม', $rows->group_name);
				$group_id = $rows->group_id;
				
				$hometeam_id = $rows->hometeam_id;
				$logo_hometeam = $rows->logo_hometeam;
				$hometeam_title = $rows->hometeam_title;
				$hometeam_title_th = $rows->hometeam_title_th;
				$hometeam_point = $rows->hometeam_point;
				$hometeam_formation = $rows->hometeam_formation;

				$awayteam_id = $rows->awayteam_id;
				$logo_awayteam = $rows->logo_awayteam;
				$awayteam_title = $rows->awayteam_title;
				$awayteam_title_th = $rows->awayteam_title_th;
				$awayteam_point = $rows->awayteam_point;
				$awayteam_formation = $rows->awayteam_formation;

				$tournament_name = ($rows->tournament_name != '') ? $rows->tournament_name : $rows->tournament_name_en;

				// $show_date = date('Y-m-d H:i:s', strtotime($kickoff.' +7 hour'));
				// list($wc_date, $wc_time) = explode(' ', $show_date);
				
				// $match_time = date('H:i', strtotime($kickoff));

				/*if($kickoff > '2022-11-27'){

					$match_time = date('H:i', strtotime($kickoff));
					$kickoff = date('Y-m-d H:i:s', strtotime($kickoff.' +7 hour'));

					// $update['kickoff'] = $kickoff;
					// $this->fixtures_model->store($program_id, $update);
					// Debug($this->db->last_query());

				}else{

					$match_time = date('H:i', strtotime($kickoff.' +7 hour'));
				}*/
				$match_time = date('H:i', strtotime($kickoff.' +7 hour'));
				
				// list($match_date, $match_time) = explode(' ', $kickoff);

				$show_date_th = DateTH($sel_date);

				$logo_team1 = $logo_team2 = $time_score = '';

				// if($logo_team1 != '')
				// 	$logo_team1 = '<img src="'.$logo_hometeam.'" alt="'.$hometeam_title_th.'"/>';
				
				// if($logo_team2 != '')
				// 	$logo_team2 = '<img src="'.$logo_awayteam.'" alt="'.$awayteam_title_th.'"/>';


				if(file_exists($this->base_path.$this->team_path.$hometeam_id.'.txt')) {

					$file = fopen($this->base_path.$this->team_path.$hometeam_id.'.txt', 'r');
					//Output lines until EOF is reached
					while(! feof($file)) {
						$img_logo = fgets($file);
						// echo $img_logo. "<br>";
					}
					fclose($file);
	
					$logo_team1 = $this->load_base64img($img_logo, 25, 20, $hometeam_title);
				}
	
				if(file_exists($this->base_path.$this->team_path.$awayteam_id.'.txt')) {
	
					$file = fopen($this->base_path.$this->team_path.$awayteam_id.'.txt', 'r');
					//Output lines until EOF is reached
					while(! feof($file)) {
						$img_logo = fgets($file);
						// echo $img_logo. "<br>";
					}
					fclose($file);
	
					$logo_team2 = $this->load_base64img($img_logo, 25, 20, $awayteam_title);
				}

				// update/match_lineup/0/2022-11-27
				$lnk_lineupdays = base_url('update/match_lineup/0/'.$sel_date);
				$update_lineupdays = anchor($lnk_lineupdays, '<button class="btn btn-primary">Update lineup</button>', array('target' => '_blank'));

				if($tmp == ''){

					$html .= '<strong id="'.$sel_date.'">'.$tournament_name.' วันที่ '.$show_date_th.'</strong> '.$update_lineupdays;
					$tmp = $sel_date;
				}else if($tmp != $sel_date){

					$html .= '<strong id="'.$sel_date.'">'.$tournament_name.' วันที่ '.$show_date_th.'</strong> '.$update_lineupdays;
					$tmp = $sel_date;
				}

				if($program_status != 'FT'){

					$time_score = $match_time;
					$show_vs = $time_score;
				}else
					$show_vs = $hometeam_point.'-'.$awayteam_point;

				$class_endmatch = '';
				if($datenow == $sel_date){

					$class_endmatch = 'endmatch';
				}else if($datenow > $sel_date || $i == 0){
				
					$class_endmatch = 'endmatch';
				}

				$link_home = base_url('team/detail/'.$hometeam_id);
				$link_away = base_url('team/detail/'.$awayteam_id);
				$link_match_detail = base_url('update/fixture_detail/'.$program_id.'/'.$fix_id);

				$show_vs = anchor($link_match_detail, $show_vs, array('target' => '_blank'));

				$update_stat = base_url('update/match_event/'.$program_id);
				$update_player = anchor($update_stat, '<button class="btn btn-primary">Update stat</button>', array('target' => '_blank'));
				$lnk_lineup = base_url('update/match_lineup/'.$fix_id);
				$update_lineup = anchor($lnk_lineup, '<button class="btn btn-primary">Update lineup</button>', array('target' => '_blank'));

				

				$html .= '
				<div class="row match-list '.$class_endmatch.'">
					<div class="col-6 right">
						<div class="row">
							<div class="col-5 right"><span><a href="'.$link_home.'" target=_blank >'.$hometeam_title_th.' '.$logo_team1.'</a></div>
							<div class="col-2 center"><strong>'.$show_vs.'</strong></div>
							<div class="col-5 left"><a href="'.$link_away.'" target=_blank >'.$logo_team2.' '.$awayteam_title_th.'</a></span></div>
						</div>
					</div>
					<div class="col-6">
						<span>'.$group_name.' '.$program_id.' '.$update_player.' '.$update_lineup.'</span>
					</div>
				</div>';

			}
		}

		return $html;
	}

	function getmatch_tournament(){
		$this->load->model('match_model');
		$this->load->model('team_model');
		$this->load->model('fixtures_model');

		$list_match_tournament = $this->match_model->getmatch_tournament($this->tournament_id);
		// Debug($list_match_tournament);
		$html = $this->display_mathch_xml($list_match_tournament);

		$data = array(
			"meta" => null,
            "webtitle" => 'fixtures',
			"head" => 'โปรแกรมบอลยูโร ผลบอลยูโร',
			"html" => $html,
			"content_view" => 'tool/blank'
		);
        $this->load->view('template',$data);

	}

	public function display_mathch_xml($obj_list){
		$html = $tmp = '';

		$datenow = date('Y-m-d');

		// if($this->input->get('reset_stat') == 1){

		// 	$this->team_model->reset_stat_player();
		// 	Debug($this->db->last_query());
		// }
		// $html = anchor();

		if ($obj_list){

			$allitem = count($obj_list);
		
			for ($i = 0; $i < $allitem; $i++) {

				$rows = $obj_list[$i];
				// Debug($rows);

				$program_id = $match_id = $rows->match_id;
				$stage_id = $rows->stage_id;

				$league_id = $rows->tournament_id;
				$sel_date = $rows->match_datetime;
				$stadium_id = $rows->stadium_id;


				// $file_group = $rows->file_group;
				$season_name = $this->season;
				$kickoff = $rows->match_datetime;
				$week = $rows->week;
				$program_status = $rows->match_status;
				$stadium_id = $rows->stadium_id;
				$stadium_name = ($rows->stadium != '') ? $rows->stadium : '';
				$channel_name = '';
				$group_name = '';
				$group_id = 0;
				
				$hometeam_id = $rows->hteam_id;
				$logo_hometeam = '';
				$hometeam_title = $rows->hteam;
				$hometeam_title_th = $rows->hteam;
				$hometeam_point = $rows->hgoals;
				$hometeam_formation = '';

				$awayteam_id = $rows->ateam_id;
				$logo_awayteam = '';
				$awayteam_title = $rows->ateam;
				$awayteam_title_th = $rows->ateam;
				$awayteam_point = $rows->agoals;
				$awayteam_formation = '';

				$referee_id = $rows->referee_id;

				$tournament_name = $this->tournament;

				$match_time = date('H:i', strtotime($kickoff.' +7 hour'));
				// list($match_date, $match_time) = explode(' ', $kickoff);

				// $show_date_th = DateTH($sel_date);
				$show_date_th = $sel_date;

				$logo_team1 = $logo_team2 = $time_score = '';

				if(file_exists($this->base_path.$this->team_path.$hometeam_id.'.txt')) {

					$file = fopen($this->base_path.$this->team_path.$hometeam_id.'.txt', 'r');
					//Output lines until EOF is reached
					while(! feof($file)) {
						$img_logo = fgets($file);
						// echo $img_logo. "<br>";
					}
					fclose($file);
	
					$logo_team1 = $this->load_base64img($img_logo, 25, 20, $hometeam_title);
				}
	
				if(file_exists($this->base_path.$this->team_path.$awayteam_id.'.txt')) {
	
					$file = fopen($this->base_path.$this->team_path.$awayteam_id.'.txt', 'r');
					//Output lines until EOF is reached
					while(! feof($file)) {
						$img_logo = fgets($file);
						// echo $img_logo. "<br>";
					}
					fclose($file);
	
					$logo_team2 = $this->load_base64img($img_logo, 25, 20, $awayteam_title);
				}

				// update/match_lineup/0/2022-11-27
				$lnk_lineupdays = base_url('update/match_lineup/0/'.$sel_date);
				$update_lineupdays = anchor($lnk_lineupdays, '<button class="btn btn-primary">Update lineup</button>', array('target' => '_blank'));

				if($tmp == ''){

					$html .= '<strong id="'.$sel_date.'">'.$tournament_name.' วันที่ '.$show_date_th.'</strong> ';
					$tmp = $sel_date;
				}else if($tmp != $sel_date){

					$html .= '<strong id="'.$sel_date.'">'.$tournament_name.' วันที่ '.$show_date_th.'</strong> ';
					$tmp = $sel_date;
				}

				if($program_status != 'FT'){

					$time_score = $match_time;
					$show_vs = $time_score;
				}else
					$show_vs = $hometeam_point.'-'.$awayteam_point;

				$class_endmatch = '';
				if($datenow == $sel_date){

					$class_endmatch = 'endmatch';
				}else if($datenow > $sel_date || $i == 0){
				
					$class_endmatch = 'endmatch';
				}

				$link_home = base_url('team/detail/'.$hometeam_id);
				$link_away = base_url('team/detail/'.$awayteam_id);
				$link_match_detail = base_url('update/fixture_detail/'.$program_id.'/'.$match_id);

				$show_vs = anchor($link_match_detail, $show_vs, array('target' => '_blank'));

				$update_stat = base_url('update/match_event/'.$program_id);
				$update_player = anchor($update_stat, '<button class="btn btn-primary">Update stat</button>', array('target' => '_blank'));
				$lnk_lineup = base_url('update/match_lineup/'.$match_id);
				$update_lineup = anchor($lnk_lineup, '<button class="btn btn-primary">Update lineup</button>', array('target' => '_blank'));

				
				$html .= '
				<div class="row match-list '.$class_endmatch.'">
					<div class="col-6 right">
						<div class="row">
							<div class="col-5 right"><span><a href="'.$link_home.'" target=_blank >'.$hometeam_title_th.' '.$logo_team1.'</a></div>
							<div class="col-2 center"><strong>'.$show_vs.'</strong></div>
							<div class="col-5 left"><a href="'.$link_away.'" target=_blank >'.$logo_team2.' '.$awayteam_title_th.'</a></span></div>
						</div>
					</div>
					<div class="col-6">
						<span>'.$group_name.' program_id:'.$program_id.' </span>
					</div>
				</div>';


				$data_update = array(
					'stage_id' => $stage_id,
					'season' => $this->season,
					'stadium_id' => $stadium_id,
					'week' => $week,
					'referee_id' => $referee_id,
				);
				$this->fixtures_model->store_fixid($program_id, $data_update);

				$html .= '<div class="row match-list '.$class_endmatch.'">'.
				$this->db->last_query()
				.'</div>';
			}
		}

		return $html;
	}


	public function fixture_detail($id)
	{
		$this->load->model('fixtures_model');

		// $tournament_id = 1204;
		// $datebetween[] = '2022-11-12';
		// $datebetween[] = '2022-11-14';

		$obj_list = $this->fixtures_model->get_data($id);
        // $obj_list = $this->fixtures_model->get_xml($this->tournament_id);

        // echo $this->db->last_query();
		// Debug($obj_list);
		// die();
		$html = $this->display_program_detail($obj_list);
		// echo "<hr>".$html;

		$data = array(
			"meta" => null,
            "webtitle" => 'fixtures',
			"head" => 'โปรแกรมบอลโลก ผลบอลโลก',
			"html" => $html,
			"content_view" => 'tool/blank'
		);
        $this->load->view('template',$data);
	}

	public function display_program_detail($obj_list){
		$html = $tmp = $display_top50 = $display_overall = $display_leagues = $display_biggest_victory = $display_last5_home_team1 = $display_last5_home_team2 = $display_last5_away_team1 = $display_last5_away_team2 = '';

		$this->load->model('match_model');
		$datenow = date('Y-m-d');


		if ($obj_list){

			$allitem = count($obj_list);
			for ($i = 0; $i < $allitem; $i++) {

				$rows = $obj_list[$i];
				// Debug($rows);

				$program_id = $rows->program_id;
				$fix_id = $rows->fix_id;
				$static_id = $rows->static_id;
				$league_id = $rows->league_id;
				$sel_date = $rows->sel_date;
				$stadium_id = $rows->stadium_id;

				$tournament_name = $rows->tournament_name;
				$tournament_name_en = $rows->tournament_name_en;
				$file_group = $rows->file_group;
				$season_name = $rows->season_name;
				$kickoff = $rows->kickoff;
				$week = $rows->week;
				$program_status = $rows->program_status;
				$stadium_name = ($rows->stadium_name_th != '') ? $rows->stadium_name_th : $rows->stadium_name;
				$channel_name = $rows->channel_name;
				$group_name = str_replace('Group', 'กลุ่ม', $rows->group_name);
				$group_id = $rows->group_id;
				
				$hometeam_id = $rows->hometeam_id;
				$logo_hometeam = $rows->logo_hometeam;
				$hometeam_title = $rows->hometeam_title;
				$hometeam_title_th = $rows->hometeam_title_th;
				$hometeam_point = $rows->hometeam_point;
				$hometeam_formation = $rows->hometeam_formation;

				$awayteam_id = $rows->awayteam_id;
				$logo_awayteam = $rows->logo_awayteam;
				$awayteam_title = $rows->awayteam_title;
				$awayteam_title_th = $rows->awayteam_title_th;
				$awayteam_point = $rows->awayteam_point;
				$awayteam_formation = $rows->awayteam_formation;

				// $show_date = date('Y-m-d H:i:s', strtotime($kickoff.' +7 hour'));
				// list($wc_date, $wc_time) = explode(' ', $show_date);
				
				// $match_time = date('H:i', strtotime($kickoff));
				$match_time = date('H:i', strtotime($kickoff.' +7 hour'));
				// list($match_date, $match_time) = explode(' ', $kickoff);

				$show_date_th = DateTH($sel_date);

				$logo_team1 = $logo_team2 = $time_score = '';

				// if($logo_team1 != '')
				// 	$logo_team1 = '<img src="'.$logo_hometeam.'" alt="'.$hometeam_title_th.'"/>';
				
				// if($logo_team2 != '')
				// 	$logo_team2 = '<img src="'.$logo_awayteam.'" alt="'.$awayteam_title_th.'"/>';


				if(file_exists($this->base_path.$this->team_path.$hometeam_id.'.txt')) {

					$file = fopen($this->base_path.$this->team_path.$hometeam_id.'.txt', 'r');
					//Output lines until EOF is reached
					while(! feof($file)) {
						$img_logo = fgets($file);
						// echo $img_logo. "<br>";
					}
					fclose($file);
	
					$logo_team1 = $this->load_base64img($img_logo, 25, 20, $hometeam_title);
				}
	
				if(file_exists($this->base_path.$this->team_path.$awayteam_id.'.txt')) {
	
					$file = fopen($this->base_path.$this->team_path.$awayteam_id.'.txt', 'r');
					//Output lines until EOF is reached
					while(! feof($file)) {
						$img_logo = fgets($file);
						// echo $img_logo. "<br>";
					}
					fclose($file);
	
					$logo_team2 = $this->load_base64img($img_logo, 25, 20, $awayteam_title);
				}

				if($tmp == ''){

					$html .= '<strong id="'.$sel_date.'">'.$show_date_th.'</strong>';
					$tmp = $sel_date;
				}else if($tmp != $sel_date){

					$html .= '<strong id="'.$sel_date.'">'.$show_date_th.'</strong>';
					$tmp = $sel_date;
				}

				if($program_status != 'FT'){

					$time_score = $match_time;
					$show_vs = $time_score;
				}else
					$show_vs = $hometeam_point.'-'.$awayteam_point;

				$class_endmatch = '';
				if($datenow == $sel_date){

					$class_endmatch = 'endmatch';
				}else if($datenow > $sel_date || $i == 0){
				
					$class_endmatch = 'endmatch';
				}
				
				$link_match_detail = base_url('update/fixture_detail/'.$program_id.'/'.$fix_id);
				$link_getfeed_h2h = 'https://www.goalserve.com/getfeed/c37c46d4313e43a899a3a3d00cbd454b/h2h/'.$hometeam_id.'/'.$awayteam_id;
				$link_match_h2h = base_url('xml/import_h2h/query/'.$hometeam_id.'/'.$awayteam_id.'/'.$league_id).'?program_id='.$program_id;

				$link_edit = base_url('analyze/edit/'.$program_id.'/'.$fix_id);
				$update_edit = '<div class="row">'.anchor($link_edit, 'วิเคราะห์ฟุตบอลโลก '.$hometeam_title.' vs '.$awayteam_title, array('target' => '_blank')).'</div>';

				$match_h2h = $this->match_model->geth2h($hometeam_id, $awayteam_id, $league_id);
				$update_h2h = '<div class="row">'.anchor($link_match_h2h, 'Update h2h', array('target' => '_blank')).'</div>';
				// Debug($match_h2h);
				if($match_h2h){

					// $update_h2h = '';
					
					$match_h2h_arr = unserialize($match_h2h[0]->json);
					// Debug($match_h2h_arr);

					$match_top50 = (isset($match_h2h_arr['top50'])) ? $match_h2h_arr['top50']: null;
					$match_overall = (isset($match_h2h_arr['overall'])) ? $match_h2h_arr['overall']: null;
					$match_leagues = (isset($match_h2h_arr['leagues'])) ? $match_h2h_arr['leagues']: null;
					$match_biggest_victory = (isset($match_h2h_arr['biggest_victory'])) ? $match_h2h_arr['biggest_victory'] : null;
					$match_biggest_defeat = (isset($match_h2h_arr['biggest_defeat'])) ? $match_h2h_arr['biggest_defeat']: null;
					$match_last5_home_team1 = $match_h2h_arr['last5_home']['team1'];
					$match_last5_home_team2 = $match_h2h_arr['last5_home']['team2'];
					$match_last5_away_team1 = $match_h2h_arr['last5_away']['team1'];
					$match_last5_away_team2 = $match_h2h_arr['last5_away']['team2'];

					//*********** Top 50 ***********
					// Debug($match_top50);
					$display_top50 = '';

					if(isset($match_top50)){

						$count_match = (count($match_top50) > 10) ? 10 : count($match_top50);
						for($j=0;$j<$count_match;$j++){

							$rows_match = $match_top50[$j];

							$category = $rows_match['category'];
							$league = $rows_match['league'];
							$league_id = $rows_match['league_id'];
							$team1 = $rows_match['team1'];
							$id1 = $rows_match['id1'];
							$team2 = $rows_match['team2'];
							$id2 = $rows_match['id2'];
							$date = $rows_match['date'];
							$team1_score = $rows_match['team1_score'];
							$team2_score = $rows_match['team2_score'];
							$static_id = $rows_match['static_id'];

							$display_top50 .= '<div class="row border">
								<div class="col-2">'.$date.'</div>
									<div class="col-8"><div class="row">
										<div class="col-4">'.$team1.'</div><div class="col-4">'.$team1_score.'-'.$team2_score.'</div><div class="col-4">'.$team2.'</div>
									</div></div>
								<div class="col-2">'.$league.'</div>
							</div>';

						}
					}


					//*********** Overall ***********
					// Debug($match_overall);
					$display_overall = '';
					$display_overall .= '<div class="row border">
							<div class="col-3 border">total</div>
							<div class="col-9 border">
								<div class="row border">
									<div class="col-6">games</div><div class="col-6">'.$match_overall['total']['games'].'</div>
								</div>
								<div class="row border">
									<div class="col-6">team1_won</div><div class="col-6">'.$match_overall['total']['team1_won'].'</div>
								</div>
								<div class="row border">
									<div class="col-6">team2_won</div><div class="col-6">'.$match_overall['total']['team2_won'].'</div>
								</div>
								<div class="row border">
									<div class="col-6">draws</div><div class="col-6">'.$match_overall['total']['draws'].'</div>
								</div>
							</div>

							<div class="col-3 border">Home</div>
							<div class="col-9 border">
								<div class="row border">
									<div class="col-6">team1</div>
									<div class="col-6">
										<div class="row border">
											<div class="col-6">games</div>
											<div class="col-6">'.$match_overall['home']['team1']['games'].'</div>
										</div>
										<div class="row border">
											<div class="col-6">won</div>
											<div class="col-6">'.$match_overall['home']['team1']['won'].'</div>
										</div>
										<div class="row border">
											<div class="col-6">lost</div>
											<div class="col-6">'.$match_overall['home']['team1']['lost'].'</div>
										</div>
										<div class="row border">
											<div class="col-6">draws</div>
											<div class="col-6">'.$match_overall['home']['team1']['draws'].'</div>
										</div>
									</div>
								</div>
								<div class="row border">
									<div class="col-6">team2</div>
									<div class="col-6">
										<div class="row border">
											<div class="col-6">games</div>
											<div class="col-6">'.$match_overall['home']['team2']['games'].'</div>
										</div>
										<div class="row border">
											<div class="col-6">won</div>
											<div class="col-6">'.$match_overall['home']['team2']['won'].'</div>
										</div>
										<div class="row border">
											<div class="col-6">lost</div>
											<div class="col-6">'.$match_overall['home']['team2']['lost'].'</div>
										</div>
										<div class="row border">
											<div class="col-6">draws</div>
											<div class="col-6">'.$match_overall['home']['team2']['draws'].'</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-3 border">Away</div>
							<div class="col-9 border">
								<div class="row border">
									<div class="col-6">team1</div>
									<div class="col-6">
										<div class="row border">
											<div class="col-6">games</div>
											<div class="col-6">'.$match_overall['away']['team1']['games'].'</div>
										</div>
										<div class="row border">
											<div class="col-6">won</div>
											<div class="col-6">'.$match_overall['away']['team1']['won'].'</div>
										</div>
										<div class="row border">
											<div class="col-6">lost</div>
											<div class="col-6">'.$match_overall['away']['team1']['lost'].'</div>
										</div>
										<div class="row border">
											<div class="col-6">draws</div>
											<div class="col-6">'.$match_overall['away']['team1']['draws'].'</div>
										</div>
									</div>
								</div>
								<div class="row border">
									<div class="col-6">team2</div>
									<div class="col-6">
										<div class="row border">
											<div class="col-6">games</div>
											<div class="col-6">'.$match_overall['home']['team2']['games'].'</div>
										</div>
										<div class="row border">
											<div class="col-6">won</div>
											<div class="col-6">'.$match_overall['home']['team2']['won'].'</div>
										</div>
										<div class="row border">
											<div class="col-6">lost</div>
											<div class="col-6">'.$match_overall['home']['team2']['lost'].'</div>
										</div>
										<div class="row border">
											<div class="col-6">draws</div>
											<div class="col-6">'.$match_overall['home']['team2']['draws'].'</div>
										</div>
									</div>
								</div>
							</div>
						</div>';

					//*********** leagues ***********
					// Debug($match_leagues);
					$display_leagues = '';
					if(isset($match_leagues)){

						$display_leagues .= '<div class="row border">';
						$all_match_leagues = count($match_leagues);
						for($j=0;$j<$all_match_leagues;$j++){

							$name = $match_leagues[$j]['name'];
							$id = $match_leagues[$j]['id'];
							$games = $match_leagues[$j]['games'];
							$team1_won = $match_leagues[$j]['team1_won'];
							$team2_won = $match_leagues[$j]['team2_won'];
							$drawdraw = $match_leagues[$j]['drawdraw'];

							$display_leagues .= '<div class="row border">
								<div class="col-12">'.$name.'</div>
								<div class="col-12">
									<div class="row">
										<div class="col-2">Games</div>
										<div class="col-2">Team1 won</div>
										<div class="col-2">Team2 won</div>
										<div class="col-2">Draw</div>
									</div>
									<div class="row">
										<div class="col-2">'.$games.'</div>
										<div class="col-2">'.$team1_won.'</div>
										<div class="col-2">'.$team2_won.'</div>
										<div class="col-2">'.$drawdraw.'</div>
									</div>
								</div>
							</div>';
						}
						$display_leagues .= '</div>';						
					}


					//*********** biggest_victory ***********
					// Debug($match_biggest_victory);
					$display_biggest_victory = '';

					if(isset($match_biggest_victory['team1'])){

						$team1_league_id = $match_biggest_victory['team1']['league_id'];
						$team1_id1 = $match_biggest_victory['team1']['id1'];
						$team1_id2 = $match_biggest_victory['team1']['id2'];
						$team1_date = $match_biggest_victory['team1']['date'];

						$display_biggest_victory .= '<div class="row border">
							<div class="col-3">'.$match_biggest_victory['team1']['category'].' '.$match_biggest_victory['team1']['league'].'<br>'.$team1_date.'</div>
							<div class="col-3">'.$match_biggest_victory['team1']['team1'].'</div>
							<div class="col-3">'.$match_biggest_victory['team1']['team1_score'].'-'.$match_biggest_victory['team1']['team2_score'].'</div>
							<div class="col-3">'.$match_biggest_victory['team1']['team2'].'</div>
						</div>';
					}

					if(isset($match_biggest_victory['team2'])){

						$team2_league_id = $match_biggest_victory['team2']['league_id'];
						$team2_id1 = $match_biggest_victory['team2']['id1'];
						$team2_id2 = $match_biggest_victory['team2']['id2'];
						$team2_date = $match_biggest_victory['team2']['date'];

						$display_biggest_victory .= '<div class="row border">
							<div class="col-3">'.$match_biggest_victory['team2']['category'].' '.$match_biggest_victory['team2']['league'].'<br>'.$team2_date.'</div>
							<div class="col-3">'.$match_biggest_victory['team2']['team1'].'</div>
							<div class="col-3">'.$match_biggest_victory['team2']['team1_score'].'-'.$match_biggest_victory['team2']['team2_score'].'</div>
							<div class="col-3">'.$match_biggest_victory['team2']['team2'].'</div>
						</div>';						
					}

					//*********** biggest_defeat ***********
					// Debug($match_biggest_defeat);
					$display_biggest_defeat = '';

					//*********** Last5_home_team1 ***********
					// Debug($match_last5_home_team1);
					$display_last5_home_team1 = '';
					$count_match = (count($match_last5_home_team1) > 10) ? 10 : count($match_last5_home_team1);
					for($j=0;$j<$count_match;$j++){

						$rows_match = $match_last5_home_team1[$j];

						$category = $rows_match['category'];
						$league = $rows_match['league'];
						$league_id = $rows_match['league_id'];
						$team1 = $rows_match['team1'];
						$id1 = $rows_match['id1'];
						$team2 = $rows_match['team2'];
						$id2 = $rows_match['id2'];
						$date = $rows_match['date'];
						$team1_score = $rows_match['team1_score'];
						$team2_score = $rows_match['team2_score'];
						$static_id = $rows_match['static_id'];

						$display_last5_home_team1 .= '<div class="row border">
							<div class="col-2">'.$date.'</div>
								<div class="col-8"><div class="row">
									<div class="col-4">'.$team1.'</div><div class="col-4">'.$team1_score.'-'.$team2_score.'</div><div class="col-4">'.$team2.'</div>
								</div></div>
							<div class="col-2">'.$league.'</div>
						</div>';
					}

					//*********** last5_home_team2 ***********
					// Debug($match_last5_home_team2);
					$display_last5_home_team2 = '';
					$count_match = (count($match_last5_home_team2) > 10) ? 10 : count($match_last5_home_team2);
					for($j=0;$j<$count_match;$j++){

						$rows_match = $match_last5_home_team2[$j];

						$category = $rows_match['category'];
						$league = $rows_match['league'];
						$league_id = $rows_match['league_id'];
						$team1 = $rows_match['team1'];
						$id1 = $rows_match['id1'];
						$team2 = $rows_match['team2'];
						$id2 = $rows_match['id2'];
						$date = $rows_match['date'];
						$team1_score = $rows_match['team1_score'];
						$team2_score = $rows_match['team2_score'];
						$static_id = $rows_match['static_id'];

						$display_last5_home_team2 .= '<div class="row border">
							<div class="col-2">'.$date.'</div>
								<div class="col-8"><div class="row">
									<div class="col-4">'.$team1.'</div><div class="col-4">'.$team1_score.'-'.$team2_score.'</div><div class="col-4">'.$team2.'</div>
								</div></div>
							<div class="col-2">'.$league.'</div>
						</div>';
					}

					//*********** last5_away_team1 ***********
					// Debug($match_last5_away_team1);
					$display_last5_away_team1 = '';
					$count_match = (count($match_last5_away_team1) > 10) ? 10 : count($match_last5_away_team1);
					for($j=0;$j<$count_match;$j++){

						$rows_match = $match_last5_away_team1[$j];

						$category = $rows_match['category'];
						$league = $rows_match['league'];
						$league_id = $rows_match['league_id'];
						$team1 = $rows_match['team1'];
						$id1 = $rows_match['id1'];
						$team2 = $rows_match['team2'];
						$id2 = $rows_match['id2'];
						$date = $rows_match['date'];
						$team1_score = $rows_match['team1_score'];
						$team2_score = $rows_match['team2_score'];
						$static_id = $rows_match['static_id'];

						$display_last5_away_team1 .= '<div class="row border">
							<div class="col-2">'.$date.'</div>
								<div class="col-8"><div class="row">
									<div class="col-4">'.$team1.'</div><div class="col-4">'.$team1_score.'-'.$team2_score.'</div><div class="col-4">'.$team2.'</div>
								</div></div>
							<div class="col-2">'.$league.'</div>
						</div>';
					}

					//*********** last5_away_team2 ***********
					// Debug($match_last5_away_team2);
					$display_last5_away_team2 = '';
					$count_match = (count($match_last5_away_team2) > 10) ? 10 : count($match_last5_away_team2);
					for($j=0;$j<$count_match;$j++){

						$rows_match = $match_last5_away_team2[$j];

						$category = $rows_match['category'];
						$league = $rows_match['league'];
						$league_id = $rows_match['league_id'];
						$team1 = $rows_match['team1'];
						$id1 = $rows_match['id1'];
						$team2 = $rows_match['team2'];
						$id2 = $rows_match['id2'];
						$date = $rows_match['date'];
						$team1_score = $rows_match['team1_score'];
						$team2_score = $rows_match['team2_score'];
						$static_id = $rows_match['static_id'];

						$display_last5_away_team2 .= '<div class="row border">
							<div class="col-2">'.$date.'</div>
								<div class="col-8"><div class="row">
									<div class="col-4">'.$team1.'</div><div class="col-4">'.$team1_score.'-'.$team2_score.'</div><div class="col-4">'.$team2.'</div>
								</div></div>
							<div class="col-2">'.$league.'</div>
						</div>';
					}

				}

				$html .= '
				<div class="row match-list '.$class_endmatch.'">
					<div class="col-6 right">
						<div class="row">
							<div class="col-5 right"><span>'.$hometeam_title_th.' '.$logo_team1.' ('.$hometeam_id.')</div>
							<div class="col-2 center"><strong>'.$show_vs.'</strong></div>
							<div class="col-5 left">('.$awayteam_id.') '.$logo_team2.' '.$awayteam_title_th.'</span></div>
						</div>
					</div>
					<div class="col-6">
						<div class="row">program_id = '.$program_id.'</div>
						<div class="row">fix_id '.$fix_id.'</div>
						<div class="row">static_id '.$static_id.'</div>
						<div class="row">league_id '.$league_id.'</div>
						<div class="row">stadium_id '.$stadium_id.'</div>
						<div class="row">kickoff '.$kickoff.'</div>
						'.$update_h2h.$update_edit.'
					</div>
				</div>
				<div class="row">
					<div class="col-12 center"><h2>Top 10</h2></div>
					'.$display_top50.'
				</div>
				<div class="row">
					<div class="col-12 center"><h2>Overall</h2></div>
					'.$display_overall.'
				</div>
				<div class="row">
					<div class="col-12 center"><h2>League</h2></div>
					'.$display_leagues.'
				</div>
				<div class="row">
					<div class="col-12 center"><h2>Biggest victory</h2></div>
					'.$display_biggest_victory.'
				</div>
				<div class="row">
					<div class="col-12 center"><h2>Last5 Home team1</h2></div>
					'.$display_last5_home_team1.'
				</div>
				<div class="row">
					<div class="col-12 center"><h2>Last5 Home team2</h2></div>
					'.$display_last5_home_team2.'
				</div>
				<div class="row">
					<div class="col-12 center"><h2>Last5 Away team1</h2></div>
					'.$display_last5_away_team1.'
				</div>
				<div class="row">
					<div class="col-12 center"><h2>Last5 Away team2</h2></div>
					'.$display_last5_away_team2.'
				</div>';
				
			}
		}

		return $html;
	}

	public function chk_team_player()
	{
		echo '<table width="50%" border=1>
			<tbody>
				<tr>
					<th>No.</th>
					<th>ID</th>
					<th>Name</th>
					<th>Number</th>
					<th>Check</th>
				</tr>';

		$res = null;
		$team_id = 0;
		$res = $this->team_model->get_data($this->tournament_id, $team_id);

		$num = count($res);
		for($i=0;$i<$num;$i++){

			$rows = $res[$i];

			$team_id = $rows->team_id;
			$team_name = $rows->team_name;

			$team_player = $this->team_model->team_player($team_id);

			$num_player = count($team_player);

			// @$res[$i]->num_player = $num_player;
			$no = $i + 1;

			// echo "<div>$team_id</div><div>$team_name</div><div>($num_player)</div><hr>";
			echo '<tr><td>'.$no.'</td><td>'.$team_id.'</td><td>'.$team_name.'</td><td class="center">'.$num_player.'</td><td class="center"> </td></tr>';
		}
		// Debug($res);
		
		echo '</tbody></table>';
	}

	public function team_list()
	{
		header("Content-type: application/json; charset=utf-8");
		ob_start();

		$res = null;
		$team_id = 0;
		$res = $this->team_model->get_data($this->tournament_id, $team_id);

		$num = count($res);
		for($i=0;$i<$num;$i++){

			$rows = $res[$i];

			$team_id = $rows->team_id;
			$team_name = $rows->team_name;

			$team_player = $this->team_model->team_player($team_id);

			$num_player = count($team_player);

			@$res[$i]->num_player = $num_player;
		}
		// Debug($res);

		ob_clean();
		echo json_encode($res);
		ob_end_flush();
	}

	public function player_list($team_id = 0)
	{
		header("Content-type: application/json; charset=utf-8");
		ob_start();

		$res = null;
		if($team_id > 0)
			$res = $this->team_model->team_player($team_id);

		ob_clean();
		echo json_encode($res);
		ob_end_flush();
	}

	public function player_detail($profile_id = 0)
	{
		header("Content-type: application/json; charset=utf-8");
		ob_start();

		$res = null;
		if($profile_id > 0)
			$res = $this->team_model->team_player(0, $profile_id);

		ob_clean();
		echo json_encode($res);
		ob_end_flush();
	}

	public function team()
	{


		$html = '<table width="80%" border=1>
			<tbody>
				<tr>
					<th>No.</th>
					<th>ID</th>
					<th>Name</th>
					<th>Name TH</th>
					<th>Number</th>
				</tr>';
		
		$res = null;
		$team_id = 0;
		$res = $this->team_model->get_data($this->tournament_id, $team_id);

		$num = count($res);
		for($i=0;$i<$num;$i++){

			$rows = $res[$i];

			// Debug($rows);

			$team_id = $rows->team_id;
			$team_name = $rows->team_name;
			$team_name_en = $rows->team_name_en;

			$action = 'update_team_name('.$team_id.', \'res'.$team_id.'\');';

			$team_player = $this->team_model->team_player($team_id);

			$num_player = count($team_player);

			// @$res[$i]->num_player = $num_player;

			$no = $i + 1;
			$html .= '<tr><td>'.$no.'</td><td>'.$team_id.'</td><td>'.$team_name_en.'</td>
			<td><input type="text" class="form-control" id="team_name'.$team_id.'" value="'.$team_name.'" placeholder="ชื่อภาษาไทย">
			<button type="button" class="btn btn-primary" onclick="'.$action.'">Update</button></td>
			<td>'.$num_player.'</td><td><div id="res'.$team_id.'"></div></td></tr>
			';
		}
		// Debug($res);
		$html .= '</tbody></table>';

		$webtitle= '';
		$data = array(
            "webtitle" => 'Update Team euro 2024',
            "breadcrumb" => null,
			"html" => $html,
			"content_view" => 'tool/blank'
        );
        $this->load->view('template', $data);
	}

	//Update รายชื่อในทีม
	public function team_player($team_id = 0)
	{
		$update_data = null;

		$res = $this->team_model->team_player($team_id);
		$all = count($res);
		for($i=0;$i<$all;$i++){

			$rows = $res[$i];
			Debug($rows);
			$profile_id = $rows->profile_id;
			$player_name = $rows->player_name;
			$player_name_th = (isset($rows->player_name_th)) ? trim($rows->player_name_th) : '-';
			
			// Debug($player_name_th);

			if($player_name_th == ''){

				// $action = 'update/player_detail/'.$profile_id;
				// $res_data = $this->callApi($action);

				// echo "<br>$action<br>";
				// Debug($res_data);

				// if($res_data[0]->player_name_th != ''){

				/*
					unset($update_data);

					echo "Update ".$res_data[0]->player_name_th." <br>";
					$update_data['player_name_th'] = $player_name;
					$update_data['name_th'] = ($rows->name_th == '') ? trim($rows->name) : $update_data['name_th'];
					$this->team_model->update_profile($profile_id, $update_data);
					Debug($this->db->last_query());
				*/

				// }
			}

		}
	}

	public function highlights($sel_date = '')
	{
		$this->load->model('match_model');

		$datebetween = null;
		$start_date = date('Y-m-d', strtotime('-1 days'));

		if($sel_date != ''){

			if($sel_date == 'all'){

				$date1 = '2022-11-20';
				$date2 = '2022-12-20';
			}else{
				
				$date1 = $sel_date;
				$date2 = $sel_date;
				// $date2 = date('Y-m-d', strtotime($sel_date." 1 days"));
			}
			
		}else{

			if($start_date < '2022-11-20'){

				$date1 = '2022-11-20';
				$date2 = '2022-12-20';
			}else{

				$date1 = $start_date;
				$date2 = date('Y-m-d', strtotime($start_date." 2 days"));
			}
		}
		
		$datebetween[] = $date1;
		$datebetween[] = $date2;

		$obj_list = $this->match_model->getmatch_highlights($datebetween);

        // echo $this->db->last_query();
		// Debug($obj_list);
		// die();
		$html = $this->display_highlights($obj_list);
		// echo "<hr>".$html;

		$data = array(
			"meta" => null,
            "webtitle" => 'highlights',
			"head" => 'ไฮไลท์บอลโลก '.$sel_date,
			"html" => $html,
			"content_view" => 'tool/blank'
		);
        $this->load->view('template',$data);
	}

	private function display_highlights($obj_list){
		$html = '';
		// Debug($obj_list);
		$all = count($obj_list);
		for($i=0;$i<$all;$i++){

			$display_files = $display_clips = '';
			$rows = $obj_list[$i];

			$match_date = $rows->match_date;
			$match_time = $rows->match_time;
			$match_status = $rows->match_status;
			$localteam_name = $rows->localteam_name;
			$localteam_goals = $rows->localteam_goals;
			$visitorteam_name = $rows->visitorteam_name;
			$visitorteam_goals = $rows->visitorteam_goals;
			$files_item = $rows->files_item;
			$clips_item = $rows->clips_item;

			$show_vs = $localteam_goals.'-'.$visitorteam_goals;

			if($files_item != '')
				$display_files = anchor($files_item, $files_item, array('target' => '_blank'));

			if($clips_item != '')
				$display_clips = anchor($clips_item, $clips_item, array('target' => '_blank'));

			$html .= '
				<div class="row match-list>
					<div class="col-6 right">
						<div class="row">
							<div class="col-5 right"><span>'.$localteam_name.'</div>
							<div class="col-2 center"><strong>'.$show_vs.'</strong></div>
							<div class="col-5 left">'.$visitorteam_name.'</span></div>
						</div>
					</div>
					<div class="col-6">
						<span>'.$display_files.' '.$display_clips.'</span>
					</div>
				</div>';
		}
		return $html;
	}

	public function test_call($id)
	{

		$action = 'update/player_detail/'.$id;
		$res = $this->callApi($action);
		Debug($res);

	}

	// Update Team Name
	function update_team($team_id){

		if($this->input->server('REQUEST_METHOD') === 'POST'){

			// Debug($this->input->post());
			// $profile_id = $this->input->post('profile_id');
			$team_name = trim($this->input->post('team_name'));

			$data_update = array('team_name' => $team_name);
			$this->team_model->store($team_id, $data_update);
			// Debug($this->db->last_query());

			echo 'Update '.$team_name.' Success.';
		}else{

			echo 'Error Method.';
		}
	}

	// Update Player Name
	function profile_name($profile_id){

		if($this->input->server('REQUEST_METHOD') === 'POST'){

			// Debug($this->input->post());
			// $profile_id = $this->input->post('profile_id');
			$player_name_th = trim($this->input->post('player_name_th'));

			$data_update = array('name_th' => $player_name_th);
			$this->team_model->update_profile($profile_id, $data_update);
			// Debug($this->db->last_query());

			echo 'Update '.$player_name_th.' Success.';
		}
	}

	//Save manager
	function manager_name($manager_id){

		if($this->input->server('REQUEST_METHOD') === 'POST'){

			$manager_name = trim($this->input->post('manager_name'));

			if($manager_id > 0){
				$data_update = array('name_th' => $manager_name);
				$this->team_model->update_manager($manager_id, $data_update);
				// Debug($this->db->last_query());

				echo 'Update '.$manager_name.' Success.';
			}else
				echo 'Error update Fail.';

		}
	}

	//Save stadium
	function update_stadium($stadium_id){
		$this->load->model('stadium_model');

		if($this->input->server('REQUEST_METHOD') === 'POST'){

			$stadium_name = trim($this->input->post('stadium_name'));

			if($stadium_id > 0){

				$data_update = array('stadium_name_th' => $stadium_name);
				$this->stadium_model->store($stadium_id, $data_update);
				// Debug($this->db->last_query());

				echo 'Update '.$stadium_name.' Success.';
			}else
				echo 'Error update Fail.';

		}
	}

	//Save program analy
	function program_analy($program_id){
		$this->load->model('program_analy_model');

		if($this->input->server('REQUEST_METHOD') === 'POST'){

			if($program_id > 0){

				$data_update = $this->input->post();
				// Debug($data_update);

				$this->program_analy_model->store($program_id, $data_update);
				// Debug($this->db->last_query());

				echo 'Update program id = '.$program_id.' Success.';
			}else
				echo 'Error update Fail.';
		}
	}

	private function callApi($action, $key = null, $use_cache = false, $host = '', $json_decode = true, $showdebug = false){

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


		// return $response;
		// die();

		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			
			return false;
			
		} else {

			if($json_decode == true){

				$res = json_decode($response);
			}else
				$res = $response;
			

			return $res;
		}
	}

	public function load_base64img($src, $width = 100, $height = 0, $title = ''){
		//width:100px;height:100px;
		$output = "<figure><img class='base64image round' width='".$width."' alt='".$title."' title='".$title."' src='data:image/jpeg;base64, $src' /></figure>";
      	return $output;
	}
}
