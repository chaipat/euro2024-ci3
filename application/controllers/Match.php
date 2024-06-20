<?php
class Match extends CI_Controller {
	var $season = 9;

    public function __construct()    {
		parent::__construct();
		header('Content-type: text/html; charset=utf-8');

		// $this->load->library('session');
		// $this->load->library('genarate');
		$this->load->model('program_model');
		$this->load->model('fixtures_model');
		$this->load->model('match_model');
		$this->load->model('season_model');
		$this->load->model('tournament_model');
		$this->load->library('api');
		$this->load->helper('common');

        $this->tournament_id = $this->config->config['tournament_id'];
		$this->tournament = $this->config->config['tournament'];
        $this->season = $this->config->config['season'];
 		$this->date_start = $this->config->config['date_start'];
		$this->datetime_start = $this->config->config['datetime_start'];

		$this->catid = $this->config->config['catid_news'];
        
        $this->profile_path = 'data/uploads/player/';
		$this->team_path = 'data/uploads/teamlogo/';
		$this->stadium_path = 'data/uploads/stadium/';
		$this->base_path = str_replace('application/controllers', '', __DIR__);
    }

	public function index(){

		$obj_list = $data_list = $heading = $datebetween = $asset_css = $asset_js = array();
		$button_stat = $lineup = $bet = $import_statistics = $datekick = $price = $display_menu = $tournament_name = '';
		$postdata = null;
		$league_id = 0;
		$html = '';
		$season_id = $this->season;

		$cur_date = date('Y-m-d H:i:s');
		$datenow = date('Y-m-d');
		$sel_date = $datenow;

        $datebetween[] = $datenow.' 00:00:00';
        $datenext = date("Y-m-d", strtotime("+1 day"));
        $datebetween[] = $datenext.' 23:59:59';

        // $obj_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id, $datebetween);
        $obj_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id);
        // $obj_list = $this->fixtures_model->get_xml($this->tournament_id);
        // echo $this->db->last_query();
		// Debug($obj_list);
		// die();

		if(isset($obj_list[0]->tournament_name)){
			$tournament_name = $obj_list[0]->tournament_name;
		}

		$html = $this->display_program($obj_list);
		
		// $asset_css[] = 'datepicker.css';
		// $asset_css[] = 'daterangepicker.css';
		// $asset_js[] = 'date-time/moment.min.js';
		// $asset_js[] = 'date-time/daterangepicker.min.js';

		// $asset_js[] = 'chosen.jquery.min.js';
		// $asset_js[] = 'form.js';

		$breadcrumb[] = 'โปรแกรม';

		$webtitle = 'โปรแกรม'.$tournament_name;
		$page_published_time = date('c' , strtotime('2022-10-27'));
		$page_lastupdated_date = date('c');
		// $keywords = explode(',', _KEYWORD);
		$social_block = $this->social_block($webtitle);

		$keywords[] = 'ตารางบอลโลก';
		$keywords[] = 'โปรแกรมฟุตบอลโลก';
		$keywords[] = 'โปรแกรมฟุตบอลโลก 2022';
		$keywords[] = 'ผลบอลโลก';
		
		$meta = array(
			'title' => $webtitle,
			'description' => _DESCRIPTION,
			'keywords' => $keywords,
			'page_image' => _COVER_WC2022,
			"page_published_time" => $page_published_time,
			"Author" => "Ballnaja",
			"Copyright" => "Ballnaja"
		);

		$asset_css[] = 'jquery.fancybox.css';
		$asset_js[] = 'jquery.fancybox.js';

		$data = array(
			"meta" => $meta,
            "webtitle" => $webtitle,
            "breadcrumb" => $breadcrumb,
			"menu" => $display_menu,
			"head" => 'โปรแกรมบอลโลก ผลบอลโลก',
			"html" => $html,
			"social_block" => $social_block,
			"css" => $asset_css,
			"js" => $asset_js,
			"page_lastupdated_date" => $page_lastupdated_date,
			"breadcrumb" => $breadcrumb,
			"content_view" => 'fixtures/list'
		);
		//$this->parser->parse('template',$data);
        $this->load->view('template-wc',$data);
	}

	public function display_program($obj_list){
		$html = $tmp = '';

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
				}

				$class_endmatch = '';
				if($datenow == $sel_date){

					$class_endmatch = 'endmatch';
				}else if($datenow > $sel_date || $i == 0){
				
					$class_endmatch = 'endmatch';
				}

				$link_teamhome = base_url('team/detail/'.$hometeam_id);
				$link_teamaway = base_url('team/detail/'.$awayteam_id);

				$html .= '
				<div class="match-list '.$class_endmatch.'">
					<div>
					<span><a href="'.$link_teamhome.'" target="_blank" >'.$hometeam_title_th.' '.$logo_team1.'</a>
					<strong><a href="#">'.$time_score.'</a></strong> 
					<a href="'.$link_teamaway.'" target="_blank" >'.$logo_team2.' '.$awayteam_title_th.'</a></span>
					<span>'.$group_name.'</span>

					<span>สนาม '.$stadium_name.'</span>
					<!-- <a href="#">วิเคราะห์ก่อนเกมส์</a> -->
					<!-- <a href="#">อ่านข่าว</a> -->
					</div>
				</div>';

			}
		}

		return $html;
	}

	public function detail($program_id = 0)
	{
		$obj_list = $data_list = $heading = $datebetween = $date_result = $asset_css = $asset_js = array();
		$button_stat = $lineup = $bet = $import_statistics = $datekick = $price = $display_menu = $tournament_name = '';
		$event_pen = $man_of_match = $match_stat = $display_topscore = $display_topassist = $html = '';
		$postdata = $widgets_result = null;

		$this->load->model('team_model');

		$season_id = $this->season;

		$cur_date = date('Y-m-d H:i:s');
		$datenow = date('Y-m-d');

		$obj_list = $this->fixtures_model->get_data($program_id, 0, $this->tournament_id);

		if(empty($obj_list)){

			redirect('/');
			die();
		}

		$fix_id = $obj_list[0]->fix_id;
		$hometeam_id = $obj_list[0]->hometeam_id;
		$awayteam_id = $obj_list[0]->awayteam_id;

		$tournament_name = ($obj_list[0]->tournament_name != '') ? $obj_list[0]->tournament_name: $obj_list[0]->tournament_name_en;
		$hometeam_title = ($obj_list[0]->hometeam_title_th != '') ? $obj_list[0]->hometeam_title_th: $obj_list[0]->hometeam_title;
		$awayteam_title = ($obj_list[0]->awayteam_title_th != '') ? $obj_list[0]->awayteam_title_th: $obj_list[0]->awayteam_title;
		// Debug($obj_list);

		$teamhome_list = $this->team_model->get_data($this->tournament_id, $hometeam_id);
		$home_manager_name = ($teamhome_list[0]->manager_name_th != '') ? $teamhome_list[0]->manager_name_th: $teamhome_list[0]->manager_name;
		// Debug($teamhome_list);

		$teamaway_list = $this->team_model->get_data($this->tournament_id, $awayteam_id);
		$away_manager_name = ($teamaway_list[0]->manager_name_th != '') ? $teamaway_list[0]->manager_name_th: $teamaway_list[0]->manager_name;
		// Debug($teamaway_list);
		// die();

		$match_event = $this->match_model->getmatch_event($program_id);
		// Debug($this->db->last_query());
		// Debug($match_event);

		$match_penalties = $this->match_model->getmatch_penalties($fix_id);
		// Debug($this->db->last_query());
		// Debug($match_penalties);
		// die();

		$match_lineup = $this->match_model->getmatch_lineup($fix_id);
		// Debug($match_lineup);

		$match_substitutions = $this->match_model->getmatch_substitutions($fix_id);
		// Debug($match_substitutions);

		// die();
		
		$display_matchinfo = $this->display_matchinfo($obj_list);
		$display_match_event = $this->display_matchevent($obj_list[0], $match_event);

		if($match_penalties)
			$event_pen = $this->event_pen($obj_list[0], $match_penalties);

		// $man_of_match = $this->man_of_match();
		$lineup = $this->lineup($obj_list[0], $match_lineup, $match_substitutions, $home_manager_name, $away_manager_name, $match_event);
		// $match_stat = $this->match_stat();
		// die();
		
		$relate_content = $this->relate_content();

		$breadcrumb[] = 'ผลบอลสด การแข่งขันฟุตบอลโลก';

		$webtitle = 'ผลบอลสด การแข่งขันฟุตบอลโลก '.$hometeam_title.' กับ '.$awayteam_title;
		$page_published_time = date('c' , strtotime('2022-10-27'));
		$page_lastupdated_date = date('c');
		// $keywords = explode(',', _KEYWORD);
		$social_block = $this->social_block($webtitle);

		$keywords[] = 'ผลบอลสด';
		$keywords[] = 'ผลบอลสดฟุตบอลโลก 2022';
		$keywords[] = 'การแข่งขันฟุตบอลโลก 2022';
		$keywords[] = 'รายงานการแข่งขันฟุตบอลโลก';
		$keywords[] = 'โปรแกรมห์บอลโลก';

		$page_image = _COVER_WC2022;
		$program_cover = './assets/images/match/'.$program_id.'.webp';
    	if(file_exists($program_cover)){
			$page_image = base_url($program_cover);
		}
		
		$meta = array(
			'title' => $webtitle,
			'description' => $webtitle.' '._DESCRIPTION,
			'keywords' => $keywords,
			'page_image' => $page_image,
			"page_published_time" => $page_published_time,
			"Author" => "Ballnaja",
			"Copyright" => "Ballnaja"
		);

		$asset_css[] = 'jquery.fancybox.css';
		$asset_css[] = 'match.css';
		$asset_js[] = 'jquery.fancybox.js';

		$data = array(
			"meta" => $meta,
            "webtitle" => $webtitle,
            "breadcrumb" => $breadcrumb,
			"menu" => $display_menu,
			"head" => 'ผลบอลสดฟุตบอลโลก '.$hometeam_title.' กับ '.$awayteam_title,
			"display_matchinfo" => $display_matchinfo,
			"display_match_event" => $display_match_event,
			"event_pen" => $event_pen,
			"man_of_match" => $man_of_match,
			"lineup" => $lineup,
			"match_stat" => $match_stat,
			"social_block" => $social_block,
			"widgets_result" => $widgets_result,
			"relate_content" => $relate_content,
			"css" => $asset_css,
			"js" => $asset_js,
			"page_lastupdated_date" => $page_lastupdated_date,
			"breadcrumb" => $breadcrumb,
			"content_view" => 'match/detail'
		);
        $this->load->view('template-wc',$data);
		
	}

	public function display_matchinfo($obj_list)
	{
		$html = $tmp = '';
		$datenow = date('Y-m-d');
		$cur_date = date('Y-m-d H:i');

		if ($obj_list){

			$allitem = count($obj_list);
		
			for ($i = 0; $i < $allitem; $i++) {

				$rows = $obj_list[$i];
				// Debug($rows);
				// die();

				$program_id = $rows->program_id;
				$fix_id = $rows->fix_id;
				$stage_id = $rows->stage_id;
				$static_id = $rows->static_id;
				$league_id = $rows->league_id;
				$sel_date = $rows->sel_date;
				$stadium_id = $rows->stadium_id;

				$tournament_name = $rows->tournament_name;
				$tournament_name_en = $rows->tournament_name_en;
				$file_group = $rows->file_group;
				$season_name = $rows->season_name;
				$kickoff = $rows->kickoff;
				$kickoff_th = $rows->kickoff_th;
				$ft_result = $rows->ft_result;
				$et_result = $rows->et_result;
				$penalty = trim($rows->penalty);

				$week = $rows->week;
				$program_status = $rows->program_status;
				$stadium_name = ($rows->stadium_name_th != '') ? $rows->stadium_name_th : $rows->stadium_name;
				$channel_name = $rows->channel_name;
				$group_name = str_replace('Group', 'กลุ่ม', $rows->group_name);
				$group_id = $rows->group_id;
				
				$hometeam_id = $rows->hometeam_id;
				$logo_hometeam = $rows->logo_hometeam;
				$hometeam_title = ($rows->hometeam_title_th != '') ? $rows->hometeam_title_th : $rows->hometeam_title;
				// $hometeam_title_th = $rows->hometeam_title_th;
				$hometeam_point = $rows->hometeam_point;
				$hometeam_formation = $rows->hometeam_formation;

				$awayteam_id = $rows->awayteam_id;
				$logo_awayteam = $rows->logo_awayteam;
				$awayteam_title = ($rows->awayteam_title_th != '') ? $rows->awayteam_title_th : $rows->awayteam_title;
				// $awayteam_title_th = $rows->awayteam_title_th;
				$awayteam_point = $rows->awayteam_point;
				$awayteam_formation = $rows->awayteam_formation;

				$show_date = date('Y-m-d H:i', strtotime($kickoff_th));
				// $show_date = date('Y-m-d H:i', strtotime($kickoff.' +7 hour'));
				// list($wc_date, $wc_time) = explode(' ', $show_date);
				
				$match_time = date('H:i', strtotime($kickoff_th));
				// $match_time = date('H:i', strtotime($kickoff.' +7 hour'));
				// list($match_date, $match_time) = explode(' ', $kickoff);

				$show_date_th = DateTH($sel_date);

				$logo_team1 = $logo_team2 = $time_score = '';
				
				if($stage_id != 10561027){
					$group_name = '';
				}

				if(file_exists($this->base_path.$this->team_path.$hometeam_id.'.txt')) {

					$file = fopen($this->base_path.$this->team_path.$hometeam_id.'.txt', 'r');
					//Output lines until EOF is reached
					while(! feof($file)) {
						$img_logo = fgets($file);
						// echo $img_logo. "<br>";
					}
					fclose($file);
	
					$logo_team1 = $this->load_base64img($img_logo, 25, 20, $hometeam_title, 'base64image');
				}
	
				if(file_exists($this->base_path.$this->team_path.$awayteam_id.'.txt')) {
	
					$file = fopen($this->base_path.$this->team_path.$awayteam_id.'.txt', 'r');
					//Output lines until EOF is reached
					while(! feof($file)) {
						$img_logo = fgets($file);
						// echo $img_logo. "<br>";
					}
					fclose($file);
	
					$logo_team2 = $this->load_base64img($img_logo, 25, 20, $awayteam_title, 'base64image');
				}

				// if($tmp == ''){

				// 	$html .= '<strong id="'.$sel_date.'">'.$show_date_th.'</strong>';
				// 	$tmp = $sel_date;
				// }else if($tmp != $sel_date){

				// 	$html .= '<strong id="'.$sel_date.'">'.$show_date_th.'</strong>';
				// 	$tmp = $sel_date;
				// }
				// $time_score = $ft_result.'<br>'.$program_status;
				// $time_score = strtoupper($program_status);
				// $time_score = "(".strtotime($cur_date)." > ".strtotime($show_date).")";
				// if($program_status != 'FT'){
				if(strtotime($cur_date) > strtotime($show_date)){

					if($program_status != 'HT' && $program_status != 'FT' && $program_status != 'Pen.'){
						$program_status .= "'";
					}

					if($program_status == 'FT'){
					
						if($ft_result == '') 
							$ft_result = $hometeam_point.'-'.$awayteam_point;

						$time_score = $ft_result.'<br>'.$program_status;
					}else if($penalty != ''){

						$time_score = $ft_result.'<br>'.$penalty.'<br>'.$program_status;
					}else if($et_result != ''){

						$time_score = $ft_result.'<br>'.$et_result.'<br>'.$program_status;
					}else{
						
						$time_score = $hometeam_point.'-'.$awayteam_point.'<br>'.$program_status;
					}
					
				}else{

					$time_score = $match_time;
				}

				$class_endmatch = '';
				if($datenow == $sel_date){

					$class_endmatch = 'endmatch';
				}else if($datenow > $sel_date || $i == 0){
				
					$class_endmatch = 'endmatch';
				}

				$view_analy = base_url('analyze/view/'.$program_id.'/'.$fix_id);
				$link_teamhome = base_url('team/detail/'.$hometeam_id);
				$link_teamaway = base_url('team/detail/'.$awayteam_id);

				$channel = '';
				$channel_list = $this->fixtures_model->get_databallnaja($program_id);
				$num_channel = count($channel_list);
				for($j=0;$j<$num_channel;$j++){

					$channel_code = $channel_list[$j]->code;
					$channel_logo = $channel_list[$j]->channel_logo;
					$channel_name = $channel_list[$j]->channel_name;
					$channel_link = $channel_list[$j]->channel_link;

					if($channel_name != '')
						$channel .= anchor($channel_link, $channel_name, array('target' => '_blank'));
				}

				$html .= '<div>
				<span>'.$show_date_th.'</span>
				<span>
					<a href="'.$link_teamhome.'" target="_blank">'.$logo_team1.'<span>'.$hometeam_title.'</span></a>
					<strong><a href="'.$view_analy.'">'.$time_score.'</a></strong>
					<a href="'.$link_teamaway.'" target="_blank" ><span>'.$awayteam_title.'</span> '.$logo_team2.' </a>
				</span>
				<span>'.$group_name.'</span>
				<span>ช่องถ่ายทอดสดฟุตบอลโลก พร้อมลิ้งถ่ายทอดสด</span>
				'.$channel.'
				<span>สนาม '.$stadium_name.'</span>
				<a target="_blank" href="'.$view_analy.'">วิเคราะห์บอล</a>
			  	</div>';
			}

			$html .= '';
		}

		return $html;
	}

	public function display_matchevent($match_info, $obj_list){
		$html = '';

		$hometeam_id = $match_info->hometeam_id;
		$hometeam_title = $match_info->hometeam_title_th;

		$awayteam_id = $match_info->awayteam_id;
		$awayteam_title = $match_info->awayteam_title_th;
		
		$number = count($obj_list);
		for($i=0;$i<$number;$i++){

			$rows = $obj_list[$i];
			$event_name = $assist_name = '';

			$eventid = $rows->eventid;
			$type = $rows->type;
			$team = $rows->team;
			$minute = $rows->minute;
			$result = $rows->result;
			$playerid = $rows->playerid;
			$player_name = ($rows->player_name_th != '') ? $rows->player_name_th: $rows->player;
			$assistid = $rows->assistid;

			if($assistid == 0){
				$event_name = $rows->assist;
			}else
				$assist_name = ($rows->assist_name_th != '') ? $rows->assist_name_th: $rows->assist;

			if($team == 'localteam'){

				$team_id = $hometeam_id;
				$team_name = $hometeam_title;
			}else{

				$team_id = $awayteam_id;
				$team_name = $awayteam_title;
			}
			
			//********** Team *********/
			if(file_exists($this->base_path.$this->team_path.$team_id.'.txt')) {
				$file = fopen($this->base_path.$this->team_path.$team_id.'.txt', 'r');
				while(! feof($file)) {
					$img_logo = fgets($file);
				}
				fclose($file);
				$show_team = $this->load_base64img($img_logo, 25, 25, $team_name);
			}

			$link_team = base_url('team/detail/'.$team_id);
			$link_team = anchor($link_team, $show_team, array('target' => '_blank'));

			//********** Player *********/
			$profile_file = $this->base_path.$this->profile_path.$team_id.'/'.$playerid.'.txt';
			$update_date = 0;

			if(file_exists($profile_file)) {

				$file = fopen($profile_file, 'r');
				while(! feof($file)) {
					$img_profile = fgets($file);
				}
				fclose($file);

				if(trim($img_profile) != '')
					$player_img = $this->load_base64img_profile($img_profile, 25, 25, $player_name);
				else{
					$player_img = '<figure><img src="'.base_url(_NO_PLAYER).'" alt="No player"/></figure>';
				}

			}else{

				$player_img = '<figure><img src="'.base_url(_NO_PLAYER).'" alt="No player"/></figure>';
			}

			// $img_cover_news = '<figure><img src="'.$img_url.'" alt="'.$title.'" title="'.$title.'" /></figure>';
			$link_profile = base_url('player/profile/'.$playerid.'/'.$team_id);

			$link_player = anchor($link_profile, $player_img, array('target' => '_blank'));

			//********* Type */
			$icon_event = $this->event_type($type);

			if($assist_name != ''){
				$assist_name = "($assist_name)";
			}

			$html .= '<div>
				<strong>'.$minute.'\'</strong>
				<span>
				'.$link_team.'
				'.$link_player.'
				</span>
				<span>'.$icon_event.'</span>
				<p>'.$player_name.' <br>'.$assist_name.' '.$event_name.'</p>
			  </div>';
		}

		return $html;
	}
	
	private function event_type($type)
	{
		$output = '';

		switch($type){
			case "var" :
				$output = '<b class="red">'.strtoupper($type).'</b>';
				break;
			case "yellowcard" :
				$output = '<img src="'.base_url(_YELLOW).'" alt="'.$type.'" />';
				break;
			case "redcard" :
				$output = '<img src="'.base_url(_RED).'" alt="'.$type.'" />';
				break;
			case "yellowred" :
				$output = '<img src="'.base_url(_YELLOWRED).'" alt="'.$type.'" />';
				break;
			case "goal" :
				$output = '<img src="'.base_url(_GOAL).'" alt="'.$type.'" />';
				break;
			case "pen miss" :
				$output = '<img src="'.base_url(_MISS_PEN).'" alt="'.$type.'" />';
				break;
			case "assits" :
				$output = '<img src="'.base_url(_ASSITS).'" alt="'.$type.'" />';
				break;
			case "subst" :
				$output = '<img src="'.base_url(_CHG_IN).'" alt="'.$type.'" /><img src="'.base_url(_CHG_OUT).'" alt="'.$type.'" />';
				break;
			default :
				$output = '('.$type.')';
				break;
		}
		return $output;
	}

	private function pen_icon($type)
	{
		$output = '';

		switch($type){
			case "goal" :
				$output = '<img src="'.base_url(_PEN_OK).'" alt="'.$type.'" />';
				break;
			case "no" :
				$output = '<img src="'.base_url(_PEN_NO).'" alt="'.$type.'" />';
				break;

			default :
				$output = '<img src="'.base_url(_PEN_OK).'" alt="'.$type.'" />';
				break;
		}
		return $output;
	}

	public function event_pen($match_info, $obj_list){
		$html = $team = '';

		$hometeam_id = $match_info->hometeam_id;
		$hometeam_title = $match_info->hometeam_title_th;

		$awayteam_id = $match_info->awayteam_id;
		$awayteam_title = $match_info->awayteam_title_th;

		if(file_exists($this->base_path.$this->team_path.$hometeam_id.'.txt')) {
			$file = fopen($this->base_path.$this->team_path.$hometeam_id.'.txt', 'r');
			while(! feof($file)) {
				$img_logo = fgets($file);
			}
			fclose($file);
			$show_team1 = $this->load_base64img($img_logo, 25, 25, $hometeam_title);
		}

		if(file_exists($this->base_path.$this->team_path.$awayteam_id.'.txt')) {
			$file = fopen($this->base_path.$this->team_path.$awayteam_id.'.txt', 'r');
			while(! feof($file)) {
				$img_logo = fgets($file);
			}
			fclose($file);
			$show_team2 = $this->load_base64img($img_logo, 25, 25, $awayteam_title);
		}

		// Debug($obj_list);
		$num_pen = count($obj_list);

		$link_teamhome = base_url('team/detail/'.$hometeam_id);
		$link_teamaway = base_url('team/detail/'.$awayteam_id);

		if($num_pen > 0)
		$html = '<div class="penalty-head">
			<div><a href="'.$link_teamhome.'" target="_blank">'.$show_team1.'<span>'.$hometeam_title.'</span></a>
			<h2>ยิงจุดโทษ</h2>
			<a href="'.$link_teamaway.'" target="_blank">'.$show_team2.'<span>'.$awayteam_title.'</span></a></div>
			</div>
			<div class="penalty-list">';

		$display_local = $display_visit = '';

		for($i=0;$i<$num_pen;$i++){

			$rows = $obj_list[$i];

			$id =$rows->id;
			$match_id =$rows->match_id;
			$team =$rows->team;
			$minute =$rows->minute;
			$playerid =$rows->playerid;
			$score =$rows->score;
			$scored =$rows->scored;
			$player_name = ($rows->player_name_th != '') ? $rows->player_name_th: $rows->player;

			// $profile_file = $this->base_path.$this->profile_path.$team_id.'/'.$profile_id.'.txt';
			
			if($i == 0){
				$begin = $team;
			}

			if($begin == 'localteam'){

				if($team == 'localteam'){
					
					$profile_file = $this->base_path.$this->profile_path.$hometeam_id.'/'.$playerid.'.txt';
					if(file_exists($profile_file)) {
						$file = fopen($profile_file, 'r');
						while(! feof($file)) {
							$img_profile = fgets($file);
						}
						fclose($file);
		
						if(trim($img_profile) != '')
							$player_img = $this->load_base64img_profile($img_profile, 100, 0, $player_name);
						else{
							$player_img = '<figure><img src="'.base_url(_NO_PLAYER).'" alt="No player"/></figure>';
						}
					}else{
						$player_img = '<figure><img src="'.base_url(_NO_PLAYER).'" alt="No player"/></figure>';
					}

					$link_profile = base_url('player/profile/'.$playerid.'/'.$hometeam_id);

					$display_local = '<a href="'.$link_profile.'" target="_blank">'.$player_img.'<span>'.$player_name .'</span></a>';

					$display_icon_local = ($scored == 1) ? $this->pen_icon('goal'): $this->pen_icon('no');
					
				}else if($team == 'visitorteam'){

					$profile_file = $this->base_path.$this->profile_path.$awayteam_id.'/'.$playerid.'.txt';
					if(file_exists($profile_file)) {
						$file = fopen($profile_file, 'r');
						while(! feof($file)) {
							$img_profile = fgets($file);
						}
						fclose($file);
		
						if(trim($img_profile) != '')
							$player_img = $this->load_base64img_profile($img_profile, 100, 0, $player_name);
						else{
							$player_img = '<figure><img src="'.base_url(_NO_PLAYER).'" alt="No player"/></figure>';
						}
					}else{
						$player_img = '<figure><img src="'.base_url(_NO_PLAYER).'" alt="No player"/></figure>';
					}

					$link_profile = base_url('player/profile/'.$playerid.'/'.$awayteam_id);
					$display_visit = '<a href="'.$link_profile.'" target="_blank">'.$player_img.'<span>'.$player_name .'</span></a>';

					$display_icon_visit = ($scored == 1) ? $this->pen_icon('goal'): $this->pen_icon('no');

					$html .= '<div>'.$display_local.'<span>'.$display_icon_local.'<strong>'.$score.'</strong>'.$display_icon_visit.'</span>'.$display_visit.'</div>';
				}
			}else{

				if($team == 'localteam'){
					
					$profile_file = $this->base_path.$this->profile_path.$hometeam_id.'/'.$playerid.'.txt';
					if(file_exists($profile_file)) {
						$file = fopen($profile_file, 'r');
						while(! feof($file)) {
							$img_profile = fgets($file);
						}
						fclose($file);
		
						if(trim($img_profile) != '')
							$player_img = $this->load_base64img_profile($img_profile, 100, 0, $player_name);
						else{
							$player_img = '<figure><img src="'.base_url(_NO_PLAYER).'" alt="No player"/></figure>';
						}
					}else{
						$player_img = '<figure><img src="'.base_url(_NO_PLAYER).'" alt="No player"/></figure>';
					}

					$link_profile = base_url('player/profile/'.$playerid.'/'.$hometeam_id);

					$display_local = '<a href="'.$link_profile.'" target="_blank">'.$player_img.'<span>'.$player_name .'</span></a>';

					$display_icon_local = ($scored == 1) ? $this->pen_icon('goal'): $this->pen_icon('no');
					
					$html .= '<div>'.$display_local.'<span>'.$display_icon_local.'<strong>'.$score.'</strong>'.$display_icon_visit.'</span>'.$display_visit.'</div>';

				}else if($team == 'visitorteam'){

					$profile_file = $this->base_path.$this->profile_path.$awayteam_id.'/'.$playerid.'.txt';
					if(file_exists($profile_file)) {
						$file = fopen($profile_file, 'r');
						while(! feof($file)) {
							$img_profile = fgets($file);
						}
						fclose($file);
		
						if(trim($img_profile) != '')
							$player_img = $this->load_base64img_profile($img_profile, 100, 0, $player_name);
						else{
							$player_img = '<figure><img src="'.base_url(_NO_PLAYER).'" alt="No player"/></figure>';
						}
					}else{
						$player_img = '<figure><img src="'.base_url(_NO_PLAYER).'" alt="No player"/></figure>';
					}

					$link_profile = base_url('player/profile/'.$playerid.'/'.$awayteam_id);
					$display_visit = '<a href="'.$link_profile.'" target="_blank">'.$player_img.'<span>'.$player_name .'</span></a>';

					$display_icon_visit = ($scored == 1) ? $this->pen_icon('goal'): $this->pen_icon('no');

				}
			}



		}

		if($begin == 'localteam' && $team == 'localteam'){
			$html .= '<div>'.$display_local.'<span>'.$display_icon_local.'<strong>'.$score.'</strong></div>';
		} 

		// if($begin == 'visitorteam' && $team == 'visitorteam'){
		// }

		$html .= '</div>';

		return $html;
	}

	public function man_of_match(){
		$html = '';

		$html = '<div class="manofmatch">
			<a href="#" target="_blank"><figure><img src="'.base_url('assets/images').'/demo-pic-profile.jpg" alt=""/></figure></a>
			<h2>แมนออฟเดอะแมทซ์</h2>
			<span>
			<a href="#" target="_blank"><img src="'.base_url('assets/images').'/demo-team-icon.png" alt=""/> สวิตเซอร์แลนด์</a>
			<a href="#" target="_blank">ยาคุบ บลาสซีคอฟสกี้</a>
			</span>
		</div>';

		return $html;
	}

	public function lineup($match_info, $match_lineup, $match_substitutions, $home_manager_name, $away_manager_name, $match_event){
		$html = $show_team1 = $show_team2 = $show_homelineup = $show_awaylineup = '';
		// Debug($match_info);
		// Debug($match_lineup);
		// Debug($match_substitutions);
		$icon_in = img(base_url(_CHG_IN));
		$number_event = count($match_event);

		$hometeam_id = $match_info->hometeam_id;
		$hometeam_title = $match_info->hometeam_title_th;

		if(file_exists($this->base_path.$this->team_path.$hometeam_id.'.txt')) {
			$file = fopen($this->base_path.$this->team_path.$hometeam_id.'.txt', 'r');
			while(! feof($file)) {
				$img_logo = fgets($file);
			}
			fclose($file);
			$show_team1 = $this->load_base64img($img_logo, 25, 25, $hometeam_title);
		}


		$awayteam_id = $match_info->awayteam_id;
		$awayteam_title = $match_info->awayteam_title_th;

		if(file_exists($this->base_path.$this->team_path.$awayteam_id.'.txt')) {
			$file = fopen($this->base_path.$this->team_path.$awayteam_id.'.txt', 'r');
			while(! feof($file)) {
				$img_logo = fgets($file);
			}
			fclose($file);
			$show_team2 = $this->load_base64img($img_logo, 25, 25, $awayteam_title);
		}
		$stadium_name = ($match_info->stadium_name_th != '') ? $match_info->stadium_name_th: $match_info->stadium_name;

		$link_teamhome = base_url('team/detail/'.$hometeam_id);
		$link_teamaway = base_url('team/detail/'.$awayteam_id);

		$html = '<h2 class="t-lineup">ผู้จัดการทีม</h2>

		<div class="live-lineup">
	  
		  <div>
			<div>
			  
			  <div>
				<span>ผู้จักการทีม</span>
				<span>'.$home_manager_name.'</span>
			  </div>
			  
			</div>
		  </div>
	  
		  <div>
			<div>

			  <div>
				<span>ผู้จักการทีม</span>
				<span>'.$away_manager_name.'</span>
			  </div>

			</div>
		  </div>
	  
		</div>
	  
		<h2 class="t-lineup">รายชื่อผู้เล่นที่ลงสนาม</h2>
	  
		<div class="live-lineup">
			<div>
			<div>
				<h3><a href="'.$link_teamhome.'" target="_blank">'.$show_team1.' <span>'.$hometeam_title.'</span></a></h3>';

		// Debug($match_lineup);
		$number = count($match_lineup);
		// $number = 11;
		for($l=0;$l<$number;$l++){

			$data_rows = $match_lineup[$l];
			// Debug($data_rows);

			$team_id = $data_rows->team_id;
			$player_id = $data_rows->player_id;
			// $booking = $data_rows->booking;
			// $number = $data_rows->number;
			// $name = $data_rows->name;
			$player_name = ($data_rows->player_name_th != '') ? $data_rows->player_name_th: $data_rows->player_name;
			$player_position = $data_rows->player_position;

			if(ucfirst($player_position) == 'Goalkeeper'){
				$head_position = 'ผู้รักษาประตู';
			}else if(ucfirst($player_position) == 'Defender'){
				$head_position = 'กองหลัง';
			}else if(ucfirst($player_position) == 'Midfielder'){
				$head_position = 'กองกลาง';
			}else if(ucfirst($player_position) == 'Attacker'){
				$head_position = 'กองหน้า';
			}

			$profile_file = $this->base_path.$this->profile_path.$team_id.'/'.$player_id.'.txt';
			if(file_exists($profile_file)) {
				$file = fopen($profile_file, 'r');
				while(! feof($file)) {
					$img_profile = fgets($file);
				}
				fclose($file);

				if(trim($img_profile) != '')
					$player_img = $this->load_base64img_profile($img_profile, 50, 50, $player_name, 'round_profil50');
				else{
					$player_img = '<figure><img src="'.base_url(_NO_PLAYER).'" alt="No player"/></figure>';
				}
			}else{

				$player_img = '<figure><img src="'.base_url(_NO_PLAYER).'" alt="No player"/></figure>';
			}
			
			$link_profile = base_url('player/profile/'.$player_id.'/'.$team_id);

			$booking = '';
			for($i=0;$i<$number_event;$i++){

				$rows_eve = $match_event[$i];

				$eventid = $rows_eve->eventid;
				$type = $rows_eve->type;
				$minute = $rows_eve->minute;
				$eve_playerid = $rows_eve->playerid;
				$eve_assistid = $rows_eve->assistid;

				if($eve_playerid == $player_id || $eve_assistid == $player_id){

					if($type == 'goal' && $eve_assistid == $player_id){
						$booking .= $this->event_type('assits');
					}else
						$booking .= $this->event_type($type);
				}
			}

			if($team_id == $hometeam_id)
				$show_homelineup .= '<a href="'.$link_profile.'" target="_blank">'.$player_img.'<div><span>'.$head_position.'</span>
				<span>'.$player_name.'<br>'.$booking.'</span></div></a>';
			else
				$show_awaylineup .= '<a href="'.$link_profile.'" target="_blank">'.$player_img.'<div><span>'.$head_position.'</span>
				<span>'.$player_name.'<br>'.$booking.'</span></div></a>';

			// $html .= '<a href="'.$link_profile.'" target="_blank">'.$player_img.'<div><span>'.$head_position.'</span>
			// <span>'.$player_name.'<br>'.$booking.'</span></div></a>';

		}

		$html .= $show_homelineup;
		
		$html .= '</div>
			</div>
	  
			<div>
				<div>
				<h3><a href="'.$link_teamaway.'" target="_blank">'.$show_team2.' <span>'.$awayteam_title.'</span></a></h3>';
	  
		$html .= $show_awaylineup;
		
		$html .= '</div>
			</div>
		</div>
	  
		<h2 class="t-lineup">ผู้เล่นตัวสำรอง</h2>
	  
		<div class="live-lineup">
			<div>
				<div>
					<h3><a href="'.$link_teamhome.'" target="_blank">'.$show_team1.' <span>'.$hometeam_title.'</span></a></h3>';
		
		$show_homesub = $show_awaysub = '';
		// Debug($match_substitutions);
		$tmp = '';
		$tt = 0;
		$number = count($match_substitutions);
		for($l=0;$l<$number;$l++){

			$data_rows = $match_substitutions[$l];
			// Debug($data_rows);

			$team_id = $data_rows->team_id;
			$player_id = $data_rows->on_id;
			$on_name = $data_rows->on_name;
			$on_number = $data_rows->on_number;
			$on_booking = $data_rows->on_booking;
			$off_id = $data_rows->off_id;
			$off_name = $data_rows->off_name;
			$minute = $data_rows->minute;

			$player_name = ($data_rows->player_name_th != '') ? $data_rows->player_name_th: $data_rows->player_name;
			$player_position = $data_rows->player_position;

			if(ucfirst($player_position) == 'Goalkeeper'){
				$head_position = 'ผู้รักษาประตู';
			}else if(ucfirst($player_position) == 'Defender'){
				$head_position = 'กองหลัง';
			}else if(ucfirst($player_position) == 'Midfielder'){
				$head_position = 'กองกลาง';
			}else if(ucfirst($player_position) == 'Attacker'){
				$head_position = 'กองหน้า';
			}

			$profile_file = $this->base_path.$this->profile_path.$team_id.'/'.$player_id.'.txt';
			if(file_exists($profile_file)) {
				$file = fopen($profile_file, 'r');
				while(! feof($file)) {
					$img_profile = fgets($file);
				}
				fclose($file);

				if(trim($img_profile) != '')
					$player_img = $this->load_base64img_profile($img_profile, 50, 50, $player_name, 'round_profil50');
				else{
					$player_img = '<figure><img src="'.base_url(_NO_PLAYER).'" alt="No player"/></figure>';
				}
			}else{

				$player_img = '<figure><img src="'.base_url(_NO_PLAYER).'" alt="No player"/></figure>';
			}
			
			$link_profile = base_url('player/profile/'.$player_id.'/'.$team_id);

			$show_sub = '';
			if($minute != ''){
				// $show_sub = '<br>'.$icon_in.'('.$minute.')';
				$show_sub = '<br>';
			}
			
	
			$booking = '';
			for($i=0;$i<$number_event;$i++){

				$rows_eve = $match_event[$i];

				$eventid = $rows_eve->eventid;
				$type = $rows_eve->type;
				$minute = $rows_eve->minute;
				$eve_playerid = $rows_eve->playerid;
				
				if($eve_playerid == $player_id){
					$booking .= $this->event_type($type);
				}
			}

			if($booking != ''){

				if($hometeam_id == $team_id)
					$show_homesub .= '<a href="'.$link_profile.'" target="_blank">'.$player_img.'<div><span>'.$head_position.'</span>
					<span>'.$player_name.$show_sub.$booking.'</span></div></a>';
				else
					$show_awaysub .= '<a href="'.$link_profile.'" target="_blank">'.$player_img.'<div><span>'.$head_position.'</span>
					<span>'.$player_name.$show_sub.$booking.'</span></div></a>';				
			}

			// $html .= '<a href="'.$link_profile.'" target="_blank">'.$player_img.'<div><span>'.$head_position.'</span>
			// <span>'.$player_name.$show_sub.$booking.'</span></div></a>';
			$tmp = $team_id;
			$tt = $l;
			
		}
		
				$html .= $show_homesub;

				$html .= '
				</div>
			</div>
	  
			<div>
				<div>
					<h3><a href="'.$link_teamaway.'" target="_blank">'.$show_team2.' <span>'.$awayteam_title.'</span></a></h3>';
				
				$html .= $show_awaysub;

				$html .= '</div>
			</div>
		</div>';

		return $html;
	}

	public function match_stat(){
		$html = '';

		$html = ' <div class="live-event stat-match">
		<ul>
		<li>
		  <div><span><img src="'.base_url('assets/images').'/demo-team-icon.png" alt=""/> อังกฤษ</span></div><div><h2>สถิติ</h2></div><div><span><img src="'.base_url('assets/images').'/demo-team-icon.png" alt=""/> อังกฤษ</span></div>
		</li>
		<li><div><span style="width:40%;">10</span></div><div>ยิงตรงกรอบ</div><div><span style="width:20%;">5</span></div></li>
		<li><div><span style="width:40%;">60%</span></div><div>ครองบอล</div><div><span style="width:40%;">40%</span></div></li>
		<li><div><span style="width:60%;">10</span></div><div>เตะมุม</div><div><span style="width:60%;">10</span></div></li>
		<li><div><span style="width:20%;">10</span></div><div>ฟาล์ว</div><div><span style="width:90%;">10</span></div></li>
		<li><div><span style="width:70%;">10</span></div><div>ล้ำหน้า</div><div><span style="width:70%;">10</span></div></li>
		<li><div><span style="width:90%;">10</span></div><div>เปลี่ยนตัว</div><div><span style="width:20%;">10</span></div></li>
	  
		</ul>
		</div>';

		return $html;
	}

	public function relate_content()
	{
		$url = base_url(uri_string());
		$html = '<div class="relatecontent">
		<h2><a href="#" target="_blank">คลิปวิเคราะห์</a></h2>
		
		<a href="#" target="_blank">
		<figure>
		<div class="icon-float-small"><img src="../assets/images/icon-video.png" alt=""/></div>
	   <img src="../assets/images/demo-pic1.jpg" alt=""/></figure>
		<div>
		เจิดเชื่อรูนคู่ควร หอกเบอร์ 1สิงโต ลุยยูโร2016
		</div>
		</a>
		
		<a href="#" target="_blank">
		<figure>
		<div class="icon-float-small"><img src="../assets/images/icon-video.png" alt=""/></div>
	   <img src="../assets/images/demo-pic1.jpg" alt=""/></figure>
		<div>
		เจิดเชื่อรูนคู่ควร หอกเบอร์ 1สิงโต ลุยยูโร2016
		</div>
		</a>
		
		<a href="#" target="_blank">
		<figure>
		<div class="icon-float-small"><img src="../assets/images/icon-video.png" alt=""/></div>
	   <img src="../assets/images/demo-pic1.jpg" alt=""/></figure>
		<div>
		เจิดเชื่อรูนคู่ควร หอกเบอร์ 1สิงโต ลุยยูโร2016
		</div>
		</a>
		
		<a href="#" target="_blank">
		<figure>
		<div class="icon-float-small"><img src="../assets/images/icon-video.png" alt=""/></div>
	   <img src="../assets/images/demo-pic1.jpg" alt=""/></figure>
		<div>
		เจิดเชื่อรูนคู่ควร หอกเบอร์ 1สิงโต ลุยยูโร2016
		</div>
		</a>
		
		<a href="#" target="_blank">
		<figure>
		<div class="icon-float-small"><img src="../assets/images/icon-video.png" alt=""/></div>
	   <img src="../assets/images/demo-pic1.jpg" alt=""/></figure>
		<div>
		เจิดเชื่อรูนคู่ควร หอกเบอร์ 1สิงโต ลุยยูโร2016
		</div>
		</a>
		
		<a class="more-link-side" href="#" target="_blank">คลิปทั้งหมด</a>
		
		</div>
			 
		<div class="rectangle"> <a href="#" target="_blank"><img src="../assets/images/banner-300.jpg" alt=""/></a> </div>';

		//<a href="#" target="_blank" class="icon-envelop"></a>
		return $html;
	}
	
	public function social_block($title)
	{
		$url = base_url(uri_string());
		$facebook_share = 'https://www.facebook.com/sharer.php?u='.$url;
		$line_share = 'https://timeline.line.me/social-plugin/share?url='.$url;
		$twitter_share = 'https://twitter.com/intent/tweet?text='.$title.'&url='.$url.'&via=dooballnaja';
		$email_share = 'mailto://url='.$url;

		$html = '<strong>SHARE</strong>
        <a href="'.$facebook_share.'" target="_blank" rel="nofollow" class="icon-facebook"></a>
        <a href="'.$twitter_share.'" target="_blank" rel="nofollow" class="icon-twitter"></a>
        <a href="'.$line_share.'" target="_blank" rel="nofollow" class="icon-line"><i class="fa-brands fa-line"></i></a>';

		//<a href="#" target="_blank" class="icon-envelop"></a>
		return $html;
	}

	public function html()
	{
		$this->load->view('html/match-report');
	}

	public function import_to_program(){
		
		$this->load->library('genarate');
		$this->load->model('season_model');
		$this->load->model('match_model');
		$this->load->model('program_model');
		$this->load->model('price_model');
		$this->load->model('tournament_model');

		// $ListSelect = $this->genarate->user_menu($this->session->userdata('admin_type'));
		$language = $this->lang->language;

		$obj_list = $data_list = $heading = $datebetween = $asset_css = $asset_js = array();
		$button_stat = $lineup = $bet = $import_statistics = $datekick = $price = $file_group = $breadcrumb = '';
		$postdata = null;
		$league_id = 0;
		$season_id = $this->season;
		//$league_sel = '1:พรีเมียร์ลีก อังกฤษ:Premier League English';
		$datenow = date('Y-m-d');
		$create_date = date('Y-m-d H:i:s');

        $datebetween[] = $datenow.' 00:00:00';
        $datenext = date("Y-m-d", strtotime("+1 day"));
        $datebetween[] = $datenext.' 23:59:59';

		$webtitle = 'import XML to program';

        // $obj_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id, $datebetween);
        // $obj_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id);

        $obj_list = $this->fixtures_model->get_xml($this->tournament_id);
        // echo $this->db->last_query();
		// Debug($obj_list);
		// die();

		if ($obj_list) {
			$allitem = count($obj_list);
			if ($obj_list)
				for ($i = 0; $i < $allitem; $i++) {

					$rows = $obj_list[$i];
					$json = '';

					$match_id = $rows->match_id;
					$static_id = $rows->static_id;
					$tournament_id = $rows->tournament_id;
					$stadium_id = $rows->stadium_id;

					$hteam_id = $rows->hteam_id;
					$hteam = $rows->hteam;
					$score1 = $hgoals = $rows->hgoals;

					$ateam_id = $rows->ateam_id;
					$ateam = $rows->ateam;
					$score2 = $agoals = $rows->agoals;

					$tournament_name = ($rows->tournament_name == '') ? $rows->tournament_name_en:$rows->tournament_name;

					$data_list[$i]['fix_id'] = $match_id;
					$data_list[$i]['static_id'] = $static_id;
					$data_list[$i]['league_id'] = $tournament_id;
					$data_list[$i]['stadium_id'] = $stadium_id;
					$data_list[$i]['season'] = 1;

					$data_list[$i]['hometeam_id'] = $hteam_id;
					$data_list[$i]['hometeam_title'] = $hteam;
					$data_list[$i]['hometeam_point'] = $hgoals;

					$data_list[$i]['awayteam_id'] = $ateam_id;
					$data_list[$i]['awayteam_title'] = $ateam;
					$data_list[$i]['awayteam_point'] = $agoals;

					// $data_list[$i]['season'] = $rows->season_name2.'<br>'.$tournament_name;
					$data_list[$i]['kickoff'] = date('Y-m-d H:i:s', strtotime($rows->match_datetime.' +7 hour'));

					$data_list[$i]['week'] = $rows->week;
					$data_list[$i]['program_status'] = trim($rows->match_status);

					$data_list[$i]['create_date'] = $create_date;
					$data_list[$i]['create_by'] = 1;
					$data_list[$i]['status'] = 1;
					
					$res = $this->program_model->chk_fixid($match_id);
					// Debug($res);
					// Debug($this->db->last_query());
					Debug($data_list[$i]);

					if($res){

						unset($data_list[$i]['create_date']);
						unset($data_list[$i]['create_by']);

						// $data_list[$i]['lastupdate_date'] = $create_date;
						$data_list[$i]['lastupdate_by'] = 1;
						// echo "<hr>Update<hr>";
						$this->program_model->update_fixid($match_id, $data_list[$i]);

					}else{

						// echo "<hr>Insert<hr>";
						$this->program_model->insert_program($data_list[$i]);
					}
					Debug($this->db->last_query());
					echo "<hr>";
				}
		}
		// Debug($heading);
		// Debug($data_list);
		// die();

		// $data = array(
		// 	"list_data" => $data_list,
		// 	// "data_list" => $data_list,
		// 	"css" => $asset_css,
		// 	"js" => $asset_js,
		// 	"content_view" => 'fixtures/view',
		// 	"webtitle" => $webtitle
		// );
        // $this->load->view('template',$data);
	}

	public function update_stadium(){
		
		$this->load->library('genarate');
		$this->load->model('season_model');
		$this->load->model('match_model');
		$this->load->model('program_model');
		$this->load->model('stadium_model');
		$this->load->model('tournament_model');

		// $ListSelect = $this->genarate->user_menu($this->session->userdata('admin_type'));
		$language = $this->lang->language;

		$obj_list = $data_list = $heading = $datebetween = $asset_css = $asset_js = array();
		$button_stat = $lineup = $bet = $import_statistics = $datekick = $price = $file_group = $breadcrumb = '';
		$postdata = null;
		$league_id = 0;
		$season_id = $this->season;
		//$league_sel = '1:พรีเมียร์ลีก อังกฤษ:Premier League English';
		$datenow = date('Y-m-d');
		$create_date = date('Y-m-d H:i:s');

        $datebetween[] = $datenow.' 00:00:00';
        $datenext = date("Y-m-d", strtotime("+1 day"));
        $datebetween[] = $datenext.' 23:59:59';

		$webtitle = 'import XML to stadium';

        // $obj_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id, $datebetween);
        // $obj_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id);

        $obj_list = $this->fixtures_model->get_xml($this->tournament_id);
        // echo $this->db->last_query();
		// Debug($obj_list);
		// die();

		if ($obj_list) {
			$allitem = count($obj_list);
			if ($obj_list)
				for ($i = 0; $i < $allitem; $i++) {

					$rows = $obj_list[$i];
					$json = '';

					$stadium_id = $rows->stadium_id;
					$stadium = $rows->stadium;
					$attendance = $rows->attendance;

					$data_list[$i]['stadium_id'] = $stadium_id;
					$data_list[$i]['stadium_name'] = $stadium;
					$data_list[$i]['capacity'] = intval($attendance);
					
					$res = $this->stadium_model->get_name($stadium_id);
					// Debug($res);
					// Debug($this->db->last_query());
					Debug($data_list[$i]);

					if($res){

						// unset($data_list[$i]['create_date']);
						// unset($data_list[$i]['create_by']);

						// $data_list[$i]['lastupdate_date'] = $create_date;
						// $data_list[$i]['lastupdate_by'] = 1;
						// echo "<hr>Update<hr>";
						$this->stadium_model->store($stadium_id, $data_list[$i]);

					}else{

						// echo "<hr>Insert<hr>";
						$this->stadium_model->store(0, $data_list[$i]);
					}
					Debug($this->db->last_query());
					echo "<hr>";
				}
		}
		// Debug($heading);
		// Debug($data_list);
		// die();

	}

	public function load_base64img_profile($src, $width = 100, $height = 0, $title = '', $class = 'round_profil25'){
		//width:100px;height:100px;
		$show_height = '';

		if($height > 0)
			$show_height = 'height="'.$height.'"';

		$output = "<figure><img class='base64image ".$class."' width='".$width."' ".$show_height." alt='".$title."' title='".$title."' src='data:image/jpeg;base64, $src' /></figure>";
      	return $output;
	}

	public function load_base64img($src, $width = 25, $height = 15, $title = '', $class = 'base64image flag-round'){

		$output = "<img class='".$class."' width='".$width."' height='".$height."' alt='".$title."' title='".$title."' src='data:image/jpeg;base64, $src' />";
      	return $output;
	}
}