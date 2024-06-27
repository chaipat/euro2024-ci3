<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Topscore extends CI_Controller {

	protected $season_id;
	protected $season;
	protected $tournament_id;
	protected $tournament;
	protected $date_start;
	protected $datetime_start;
	
	protected $base_path;
	protected $profile_path;
	protected $team_path;
	protected $stadium_path;
	protected $_page = 'topscore';
	protected $_cache;

    public function __construct()    {
		parent::__construct();		

		// $this->load->library('session');
		// $this->load->library('genarate');
		$this->load->model('tournament_model');
		$this->load->model('season_model');
		$this->load->model('topscore_model');
		$this->load->model('fixtures_model');
		$this->load->library('utils');
		$this->load->helper('html');
		$this->load->helper('form');
		$this->load->helper('common');
		
		$this->season_id = '2022';

		$this->tournament_id = $this->config->config['tournament_id'];
		$this->tournament = $this->config->config['tournament'];
        $this->season = $this->config->config['season'];
 		$this->date_start = $this->config->config['date_start'];
		$this->datetime_start = $this->config->config['datetime_start'];

		$this->profile_path = 'data/uploads/player/';
        $this->team_path = 'data/uploads/teamlogo/';
		$this->stadium_path = 'data/uploads/stadium/';
		$this->base_path = str_replace('application/controllers', '', __DIR__);

		if($this->input->get('cache') == 'disable'){
			$this->_cache = false;
		}else
			$this->_cache = true;
    }

	public function index(){

		$obj_list = $data_list = $heading = $datebetween = $date_result = $asset_css = $asset_js = array();
		$button_stat = $lineup = $bet = $import_statistics = $datekick = $price = $display_menu = $tournament_name = '';
		$display_topscore = $display_topassist = '';
		$postdata = $date_result = null;
		$round = 0;
		$html = '';
		$season_id = $this->season;

		$cur_date = date('Y-m-d H:i:s');
		$datenow = date('Y-m-d');
		$sel_date = $datenow;

		$section = 'index';
		$cache_key_all = 'page_'.$this->_page.'_'.$section;
		$cache = $this->utils->getCache($cache_key_all);

		if($cache && $this->_cache){

			if($this->input->get('oncheck') == 1){
				echo "Cache On ".$cache_key_all."<hr>";
			}
			echo $cache.'<!-- Cache -->';
		}else{

			// $obj_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id);
			$tournament_id = $this->tournament_id;
			// $tournament_list = $this->tournament_model->get_data($tournament_id);
			$season_id = $this->season;
			// $tournament_id = 1204;

			$obj_list = $this->topscore_model->get_xml_topscorers(intval($tournament_id));
			$obj_list2 = $this->topscore_model->get_xml_topassist(intval($tournament_id));
			// echo $this->db->last_query();
			// Debug($obj_list);
			// Debug($obj_list2);
			// die();
			
			if($datenow < $this->date_start){

				$sel_date = $this->date_start;
				$sel_date2 = date('Y-m-d', strtotime($this->date_start.' +1 day'));

				$datebetween[] = $sel_date;
				$datebetween[] = $sel_date2.' 23:59:59';

				// $date_result[] = $sel_date;
				// $date_result[] = $sel_date;
			}else{

				$date_prev = date('Y-m-d', strtotime($datenow.' -1 day'));
				$date2 = date('Y-m-d', strtotime($datenow.' +1 day'));

				$datebetween[] = $datenow;
				$datebetween[] = $date2.' 23:59:59';

				$date_result[] = $date_prev;
				$date_result[] = $date_prev.' 23:59:59';
			}

			// echo "($datenow < ".$this->date_start.")";
			// Debug($datebetween);
			// echo "<hr>";
			// die();

			//**** Program *******
			$fixtures_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id, $datebetween);

			//**** Result *******
			$result_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id, $date_result);

			// echo $this->db->last_query();
			// Debug($result_list);
			// die();

			if(isset($obj_list[0]->tournament_name)){
				$tournament_name = $obj_list[0]->tournament_name;
			}

			$display_topscore = $this->display_topscore($obj_list);
			$display_topassist = $this->display_topassist($obj_list2);

			$widgets_program = $this->widgets_program($fixtures_list);
			$widgets_result = $this->widgets_result($result_list);
			
			$breadcrumb[] = 'ดาวซัลโวบอลโลก 2022';

			$webtitle = 'ดาวซัลโวบอลโลก 2022';
			$page_published_time = date('c' , strtotime('2022-10-27'));
			$page_lastupdated_date = date('c');
			// $keywords = explode(',', _KEYWORD);
			$social_block = $this->social_block($webtitle);

			$keywords[] = 'ดาวซัลโวบอลโลก 2022';
			$keywords[] = 'ดาวซัลโว';
			$keywords[] = 'แอสซิส';
			
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
				"head" => 'ดาวซัลโวบอลโลก 2022',
				"display_topscore" => $display_topscore,
				"display_topassist" => $display_topassist,
				"social_block" => $social_block,
				"widgets_program" => $widgets_program,
				"widgets_result" => $widgets_result,
				"script_topscore" => 'on',
				"css" => $asset_css,
				"js" => $asset_js,
				"page_lastupdated_date" => $page_lastupdated_date,
				"breadcrumb" => $breadcrumb,
				"content_view" => 'topscore/list'
			);
			// $this->load->view('template-euro',$data);
			$html = $this->load->view('template-euro', $data, true);

			//cache to redis
			$this->utils->setCacheRedis($cache_key_all, $html);
			echo $html;
			$this->db->close();
		}
	}

	public function html()
	{
		$this->load->view('html/topscores');
	}

	public function display_topscore($obj_list)
	{
		$html = $tmp = '';
		$datenow = date('Y-m-d');
		if ($obj_list){
			$allitem = count($obj_list);
		
			for ($i = 0; $i < $allitem; $i++) {

				$rows = $obj_list[$i];
				// Debug($rows);

				$id = $rows->id;
				// $tournament_id = $rows->tournament_id;
				// $tournament_name = $rows->tournament_name;
				$player_id = $rows->player_id;
				$player_name = $rows->player_name;
				
				$player_name_th = ($rows->name_th != '') ? $rows->name_th : $rows->name;
				// $group_name = str_replace('Group', 'กลุ่ม', $rows->group_name);
				$nationality = $rows->nationality;
				$position = $rows->position;
				$category = $rows->category;

				$age = $rows->age;
				$height = $rows->height;
				$weight = $rows->weight;
				$player_image = $rows->image;

				$team_id = $rows->team_id;
				$team_name = ($rows->team_name != '') ? $rows->team_name : $rows->team_name_en;
		
				$pos = $rows->pos;
				$goals = $rows->goals;
				$penalty_goals = $rows->penalty_goals;

				$logo_team = '';
				// $logo_team = '<img src="../assets/images/demo-team-icon.png" alt=""/>';

				if(file_exists($this->base_path.$this->team_path.$team_id.'.txt')) {
					$file = fopen($this->base_path.$this->team_path.$team_id.'.txt', 'r');
					//Output lines until EOF is reached
					while(! feof($file)) {
						$img_logo = fgets($file);
						// echo $img_logo. "<br>";
					}
					fclose($file);
					$logo_team = $this->load_base64img($img_logo, 20, 20, $team_name);
				}

				$display_player = ($player_name_th == '') ? $player_name : $player_name_th;

				// if($player_image != ''){
				// 	$player_image = '<img src="'.$player_image.'" alt="'.$display_player.'" />';
				// }else
				// 	$player_image = '<img src="'.base_url('assets/images/no_img.png').'" alt="'.$display_player.'" />';

				$profile_file = $this->base_path.$this->profile_path.$team_id.'/'.$player_id.'.txt';
				// echo $profile_file.'<br>';
				$update_date = 0;

				if(file_exists($profile_file)) {

					$file = fopen($profile_file, 'r');
					//Output lines until EOF is reached
					while(! feof($file)) {
						$img_profile = fgets($file);
						// echo $img_logo. "<br>";
					}
					fclose($file);

					$player_image = $this->load_base64img_profile($img_profile, 100, 0, $player_name);
				}else{

					// $update_date = 1;
					$player_image = '<figure><img src="'.base_url(_NO_PLAYER).'" alt="No player"/></figure>';
				}

				$link_team = base_url('team/detail/'.$team_id);
				$link_profile = base_url('player/profile/'.$player_id.'/'.$team_id);

				$html .= '
				<div>
					<a href="'.$link_profile.'" target="_blank">
					<span>'.$pos.'</span>
					<span>
						'.$player_image.'
					</span>
					<span>
						<strong>'.$goals.'</strong> ประตู </span>
					</a>
					<span>
					<a href="'.$link_profile.'" target="_blank">
						<strong>'.$display_player.'</strong>
					</a>
					<a href="'.$link_team.'" target="_blank">
						'.$logo_team.' '.$team_name.' </a>
					</span>
					<a href="'.$link_profile.'" target="_blank"> จุดโทษ <strong>'.$penalty_goals.'</strong></a> 
				</div>';

			}

			$html .= '';
		}

		return $html;
	}

	public function display_topassist($obj_list)
	{
		$html = $tmp = '';
		$datenow = date('Y-m-d');
		if ($obj_list){
			$allitem = count($obj_list);
		
			for ($i = 0; $i < $allitem; $i++) {

				$rows = $obj_list[$i];
				// Debug($rows);

				$id = $rows->id;
				// $tournament_id = $rows->tournament_id;
				// $tournament_name = $rows->tournament_name;
				$player_id = $rows->player_id;
				$player_name = $rows->player_name;
				
				$player_name_th = ($rows->name_th != '') ? $rows->name_th : $rows->name;
				// $group_name = str_replace('Group', 'กลุ่ม', $rows->group_name);
				$nationality = $rows->nationality;
				$position = $rows->position;
				$category = $rows->category;

				$age = $rows->age;
				$height = $rows->height;
				$weight = $rows->weight;
				$player_image = $rows->image;

				$team_id = $rows->team_id;
				$team_name = ($rows->team_name != '') ? $rows->team_name : $rows->team_name_en;
		
				$pos = $rows->pos;
				$assists = $rows->assists;

				$logo_team = '';
				// $logo_team = '<img src="../assets/images/demo-team-icon.png" alt=""/>';

				if(file_exists($this->base_path.$this->team_path.$team_id.'.txt')) {
					$file = fopen($this->base_path.$this->team_path.$team_id.'.txt', 'r');
					//Output lines until EOF is reached
					while(! feof($file)) {
						$img_logo = fgets($file);
						// echo $img_logo. "<br>";
					}
					fclose($file);
					$logo_team = $this->load_base64img($img_logo, 20, 20, $team_name);
				}

				$display_player = ($player_name_th == '') ? $player_name : $player_name_th;

				// if($player_image != ''){
				// 	$player_image = '<img src="'.$player_image.'" alt="'.$display_player.'" />';
				// }else
				// 	$player_image = '<img src="'.base_url('assets/images/no_img.png').'" alt="'.$display_player.'" />';

				$profile_file = $this->base_path.$this->profile_path.$team_id.'/'.$player_id.'.txt';
				// echo $profile_file.'<br>';
				$update_date = 0;

				if(file_exists($profile_file)) {

					$file = fopen($profile_file, 'r');
					//Output lines until EOF is reached
					while(! feof($file)) {
						$img_profile = fgets($file);
						// echo $img_logo. "<br>";
					}
					fclose($file);

					$player_image = $this->load_base64img_profile($img_profile, 100, 0, $player_name);
				}else{

					// $update_date = 1;
					$player_image = '<figure><img src="'.base_url(_NO_PLAYER).'" alt="No player"/></figure>';
				}

				$link_team = base_url('team/detail/'.$team_id);
				$link_profile = base_url('player/profile/'.$player_id.'/'.$team_id);

				$html .= '<div>
					<a href="'.$link_profile.'" target="_blank">
						<span>'.$pos.'</span>
						<span>
						'.$player_image.'
						</span>
						<span>
						<strong>'.$assists.'</strong> แอสซิส </span>
					</a>
					<span>
						<a href="'.$link_profile.'" target="_blank">
						<strong>'.$display_player.'</strong>
						</a>
						<a href="'.$link_team.'" target="_blank">'.$logo_team.' '.$team_name.'</a>
					</span>
					<!-- <a href="#" target="_blank">ยิงประตู <strong>7</strong> -->
					</a>
				</div>';

			}

			$html .= '';
		}

		return $html;
	}

	public function widgets_program($fixtures_list)
	{
		$tmp = $html = '';
		
		$number_program = (count($fixtures_list) > 4) ? 4 : count($fixtures_list);
		for($i=0;$i<$number_program;$i++){

			$rows = $fixtures_list[$i];

			$program_id = $rows->program_id;
			$fix_id = $rows->fix_id;
			$league_id = $rows->league_id;
			$static_id = $rows->static_id;
			$stadium_id = $rows->stadium_id;
			$stadium = $rows->stadium_name;

			$tournament_name = $rows->tournament_name;
			$tournament_name_en = $rows->tournament_name_en;
			$file_group = $rows->file_group;

			$group_name = $rows->group_name;
			$group_id = $rows->group_id;

			$kickoff = $rows->kickoff;
			$sel_date = $rows->sel_date;

			// $attendance = $rows->attendance;
			$referee = $rows->referee;

			$hteam_id = $rows->hometeam_id;
			$hteam = $rows->hometeam_title;
			$home_team = $rows->hometeam_title_th;
			$hgoals = $rows->hometeam_point;

			$ateam_id = $rows->awayteam_id;
			$ateam = $rows->awayteam_title;
			$away_team = $rows->awayteam_title_th;
			$agoals = $rows->awayteam_point;

			$match_datetime_th = date('H:i', strtotime($kickoff.' +7 hour'));
			// $match_datetime_th = date('H:i', strtotime($kickoff));

			if($tmp == ''){

				$program_today = DateTH($sel_date);
				$html .= '<span>'.$program_today.'</span>';
				$tmp = $sel_date;
			}else if($tmp != $sel_date){

				$program_today = DateTH($sel_date);
				$html .= '<span>'.$program_today.'</span>';
				$tmp = $sel_date;
			}

			if(file_exists($this->base_path.$this->team_path.$hteam_id.'.txt')) {

				$file = fopen($this->base_path.$this->team_path.$hteam_id.'.txt', 'r');
				//Output lines until EOF is reached
				while(! feof($file)) {
					$img_logo = fgets($file);
					// echo $img_logo. "<br>";
				}
				fclose($file);

				$logo_team1 = $this->load_base64img($img_logo, 25, 15, $home_team);
			}

			if(file_exists($this->base_path.$this->team_path.$ateam_id.'.txt')) {

				$file = fopen($this->base_path.$this->team_path.$ateam_id.'.txt', 'r');
				//Output lines until EOF is reached
				while(! feof($file)) {
					$img_logo = fgets($file);
					// echo $img_logo. "<br>";
				}
				fclose($file);

				$logo_team2 = $this->load_base64img($img_logo, 25, 15, $away_team);
			}

			$link_teamhome = base_url('team/detail/'.$hteam_id);
			$link_teamaway = base_url('team/detail/'.$ateam_id);

			$view_analy = base_url('analyze/view/'.$program_id.'/'.$fix_id);
			$link_matchdetail = base_url('match/detail/'.$program_id.'/'.$fix_id);

			$html .= '<div>
				<span>
				<a href="'.$link_teamhome.'" target="_blank">'.$home_team.' '.$logo_team1.'</a> <strong><a href="'.$view_analy.'" target="_blank">VS</a></strong> 
				<a href="'.$link_teamaway.'" target="_blank">'.$logo_team2.' '.$away_team.'</a></span> <a href="'.$view_analy.'" target="_blank">'.$match_datetime_th.'</a> 
			</div>';

		}
		
		return $html;
	}

	public function widgets_result($result_list)
	{

		$tmp = $html = '';
		// Debug($result_list);
		// die();
		// $url = base_url(uri_string()); 

		// if(isset($fixtures_list[0]->kickoff)){

			// $match_today = date('Y-m-d H:i:s', strtotime($fixtures_list[0]->match_datetime.' +7 hour'));
		// 	$match_today = date('Y-m-d H:i:s', strtotime($fixtures_list[0]->kickoff));
		// 	list($wc_date, $wc_time) = explode(' ', $match_today);
		// 	$program_today = DateTH($wc_date);
		// }
		// $html = '<h2>โปรแกรม '.$program_today.'</h2>';
		// $html = '<span>'.$program_today.'</span>';
		
		$number_program = (count($result_list) > 4) ? 4 : count($result_list);
		for($i=0;$i<$number_program;$i++){

			$rows = $result_list[$i];

			$program_id = $rows->program_id;
			$fix_id = $rows->fix_id;
			$league_id = $rows->league_id;
			$static_id = $rows->static_id;
			$stadium_id = $rows->stadium_id;
			$stadium = $rows->stadium_name;

			$tournament_name = $rows->tournament_name;
			$tournament_name_en = $rows->tournament_name_en;
			$file_group = $rows->file_group;

			$group_name = $rows->group_name;
			$group_id = $rows->group_id;

			$kickoff = $rows->kickoff;
			$sel_date = $rows->sel_date;

			// $attendance = $rows->attendance;
			$referee = $rows->referee;

			$hteam_id = $rows->hometeam_id;
			$hteam = $rows->hometeam_title;
			$home_team = $rows->hometeam_title_th;
			$hgoals = $rows->hometeam_point;

			$ateam_id = $rows->awayteam_id;
			$ateam = $rows->awayteam_title;
			$away_team = $rows->awayteam_title_th;
			$agoals = $rows->awayteam_point;

			$match_datetime_th = date('H:i', strtotime($kickoff.' +7 hour'));
			// $match_datetime_th = date('H:i', strtotime($kickoff));

			if($tmp == ''){

				$program_today = DateTH($sel_date);
				$html .= '<span>'.$program_today.'</span>';
				$tmp = $sel_date;
			}else if($tmp != $sel_date){

				$program_today = DateTH($sel_date);
				$html .= '<span>'.$program_today.'</span>';
				$tmp = $sel_date;
			}

			if(file_exists($this->base_path.$this->team_path.$hteam_id.'.txt')) {
				$file = fopen($this->base_path.$this->team_path.$hteam_id.'.txt', 'r');
				while(! feof($file)) {
					$img_logo = fgets($file);
				}
				fclose($file);
				$logo_team1 = $this->load_base64img($img_logo, 25, 15, $home_team);
			}

			if(file_exists($this->base_path.$this->team_path.$ateam_id.'.txt')) {
				$file = fopen($this->base_path.$this->team_path.$ateam_id.'.txt', 'r');
				while(! feof($file)) {
					$img_logo = fgets($file);
				}
				fclose($file);
				$logo_team2 = $this->load_base64img($img_logo, 25, 15, $away_team);
			}

			$link_teamhome = base_url('team/detail/'.$hteam_id);
			$link_teamaway = base_url('team/detail/'.$ateam_id);
			$view_analy = base_url('analyze/view/'.$program_id.'/'.$fix_id);
			$link_matchdetail = base_url('match/detail/'.$program_id.'/'.$fix_id);

			$html .= '<div> 
				<span><a href="'.$link_teamhome.'" target="_blank">'.$home_team.' '.$logo_team1.'</a> 
				<strong><a href="'.$link_matchdetail.'" target="_blank">'.$hgoals.' : '.$agoals.'</a></strong> 
				<a href="'.$link_teamaway.'" target="_blank">'.$logo_team2.' '.$away_team.'</a></span>
			</div>';

		}
		
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

	public function load_base64img_profile($src, $width = 100, $height = 0, $title = ''){
		//width:100px;height:100px;
		$output = "<figure><img class='base64image round_profile70' width='".$width."' alt='".$title."' title='".$title."' src='data:image/jpeg;base64, $src' /></figure>";
      	return $output;
	}

	public function load_base64img($src, $width = 25, $height = 15, $title = ''){

		$output = "<img class='base64image' width='".$width."' height='".$height."' alt='".$title."' title='".$title."' src='data:image/jpeg;base64, $src' />";
      	return $output;
	}
}