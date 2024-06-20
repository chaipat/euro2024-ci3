<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team extends CI_Controller {
	protected $_page = 'team';
	protected $_cache;

	public function __construct(){
        parent::__construct();

		$this->load->database();

		$this->load->model('tournament_model');
		$this->load->model('standing_model');
		$this->load->model('fixtures_model');
		$this->load->model('team_model');
		$this->load->library('api');
		$this->load->library('utils');
		$this->load->helper('common');

		$this->tournament_id = $this->config->config['tournament_id'];
		$this->tournament = $this->config->config['tournament'];
        $this->season = $this->config->config['season'];
 		$this->date_start = $this->config->config['date_start'];
		$this->datetime_start = $this->config->config['datetime_start'];

		$this->catid = $this->config->config['catid_news'];
		// $this->catid = $this->config->config['catid_other'];

		$this->profile_path = 'data/uploads/player/';
		$this->team_path = 'data/uploads/teamlogo/';
		$this->stadium_path = 'data/uploads/stadium/';
		$this->base_path = str_replace('application/controllers', '', __DIR__);

		if($this->input->get('cache') == 'disable'){
			$this->_cache = false;
		}else
			$this->_cache = true;
    }

	public function index()
	{
		$this->list(1);
	}

	public function list($curpage = 1)
	{
		$breadcrumb = $css = $js = array();
		$display_menu = $html = $wc_date_th = '';
		$team_id = 0;
		$d = 25;
		$h = $m = 0;
		$number_item = 9;

		$season = $this->season;
		$tournament_id = $this->tournament_id;
		$tournament_name = $this->tournament;
		//$user_agent = $this->input->user_agent;

		$cur_date = date('Y-m-d H:i:s');
		$datenow = date('Y-m-d');
		$sel_date = $datenow;

        $datebetween[] = $datenow.' 00:00:00';
        $datenext = date("Y-m-d", strtotime("+1 day"));
        $datebetween[] = $datenext.' 23:59:59';

		$date_prev = date('Y-m-d', strtotime($datenow.' -1 day'));
		$date_result[] = $date_prev;
		$date_result[] = $date_prev.' 23:59:59';

		$section = 'index';
		$cache_key_all = 'page_'.$this->_page.'_'.$section;
		$cache = $this->utils->getCache($cache_key_all);

		if($cache && $this->_cache){

			if($this->input->get('oncheck') == 1){
				echo "Cache On ".$cache_key_all."<hr>";
			}
			echo $cache.'<!-- Cache -->';
		}else{

			$get_teamlist = $this->team_model->get_data($tournament_id);
			// Debug($this->db->last_query());
			// Debug(count($get_teamlist));
			// Debug($get_teamlist);

			// $fixtures_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id);
			$fixtures_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id, $datebetween);
			// Debug($this->db->last_query());
			// Debug(count($fixtures_list));
			// die();

			//**** Result *******
			$result_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id, $date_result);
			
			$list_teams = $this->list_teams($get_teamlist);

			$widgets_program = $this->widgets_program($fixtures_list);
			$widgets_result = $this->widgets_result($result_list);

			$webtitle = 'ทีมฟุตบอลโลก 2022';
			$page_published_time = date('c' , strtotime('2022-11-01'));
			$page_lastupdated_date = date('c');
			
			$social_block = $this->social_block($webtitle);

			$keywords[] = 'ทีมฟุตบอลโลก';
			$keywords[] = 'ทีมฟุตบอลโลก 2022';
			$keywords[] = 'ทีมนักเตะฟุตบอลโลก';
			$keywords[] = 'ทีมฟุตบอลโลก';

			$meta = array(
				'title' => $webtitle,
				'description' => 'ทีมฟุตบอลโลก 2022 '._DESCRIPTION,
				'keywords' => $keywords,
				'page_image' => _COVER_WC2022,
				"page_published_time" => $page_published_time,
				"Author" => "Ballnaja",
				"Copyright" => "Ballnaja"
			);

			$css[] = 'jquery.fancybox.css';
			$js[] = 'jquery.fancybox.js';

			$data = array(
				"meta" => $meta,
				"webtitle" => $webtitle,
				"breadcrumb" => $breadcrumb,
				"menu" => $display_menu,
				"html" => $html,
				"list_teams" => $list_teams,
				"social_block" => $social_block,
				"widgets_program" => $widgets_program,
				"widgets_result" => $widgets_result,
				"css" => $css,
				"js" => $js,
				"page_lastupdated_date" => $page_lastupdated_date,
				"content_view" => 'team/list'
			);
			// $this->load->view('template-wc', $data);
			$html = $this->load->view('template-wc', $data, true);

			//cache to redis
			$this->utils->setCacheRedis($cache_key_all, $html);
			echo $html;
			$this->db->close();

		}
	}

	public function html()
	{
		// $this->load->view('html/team');
		$this->load->view('html/team-detail');
	}

	public function edit($team_id = 0)
	{
		$get_teamlist = $this->team_model->get_data($this->tournament_id, $team_id);
		// Debug($this->db->last_query());
		// Debug($get_teamlist);

		$team_name = ($get_teamlist[0]->team_name != '') ? $get_teamlist[0]->team_name:$get_teamlist[0]->team_name_en;
		$manager_name = ($get_teamlist[0]->manager_name_th != '') ? $get_teamlist[0]->manager_name_th:$get_teamlist[0]->manager_name;
		$manager_id = $get_teamlist[0]->manager_id;

		// $html = '';
		$html = '<div class="container text-center">';
		$html .= '<h1>'.$team_name.'</h1>';
		$html .= '<h2>'.$manager_name.'</h2>';
		
		$action = 'update_manager_name('.$manager_id.', '.$team_id.', "resman'.$manager_id.'")';

		$html .= "<div class='col-12'><input type='text' class='form-control' id='manager_name".$manager_id."' value='$manager_name' placeholder='manager name'></div>
		<div class='col-12'><div id='resman".$manager_id."'></div><button type='button' class='btn btn-primary' onclick='".$action."'>Update Manager</button></div>";

		// $obj_list = $this->standing_model->get_xml_data(intval($this->tournament_id), 0, 0);
		// $obj_list = $this->team_model->get_data($this->tournament_id);
		// Debug($this->db->last_query());
		// Debug($obj_list);
		// die();
		$get_player = $this->team_model->team_player($team_id);
		// Debug($get_player);
		// die();
		$number_item = count($get_player);

		$html .= '<div class="col-12">รายชื่อนักเตะ '.$number_item.' คน</div><hr>';

		
		for($i=0;$i<$number_item;$i++){

			$rows = $get_player[$i];
			// Debug($rows);
			$id = $rows->id;
			$profile_id = $rows->profile_id;
			$name = $rows->name;
			$position = $rows->position;
			$player_position = $rows->player_position;
			$age = $rows->age;
			$player_name = $rows->player_name;
			$player_name_th = $rows->player_name_th;
			$height = $rows->height;
			$weight = $rows->weight;
			$image = $rows->image;
			$current_team = $rows->current_team;

			$show_team = '';
	
			$ink = base_url('xml/import_team/debug/'.$team_id);
			$button_debug = anchor($ink, 'Debug Team', array('target' => '_blank'));

			$ink_import = base_url('xml/import_team/import/'.$team_id);
			$button_import = anchor($ink_import, 'Import Team', array('target' => '_blank'));

			$ink_query = base_url('xml/import_team/query/'.$team_id);
			$button_query = anchor($ink_query, 'Query Team', array('target' => '_blank'));

			$profile_file = $this->base_path.$this->profile_path.$team_id.'/'.$profile_id.'.txt';
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

				$player_img = $this->load_base64img_profile($img_profile, 100, 0, $player_name);
			}else{

				// $update_date = 1;
				$player_img = '<figure><img src="'.base_url(_NO_PLAYER).'" alt="No player"/></figure>';
			}

			$action = 'update_profile_name('.$profile_id.', '.$team_id.', "res'.$profile_id.'")';
			// $show_img = '<img src="'.$image_path.'" >';
			//<div class='col'>$button_debug $button_import $button_query</div>
			$num = $i + 1;
			$html .= "<div class='row'>
			<div class='col-2'>$num:$profile_id</div>
			<div class='col-2'>$player_img ($player_position)<br>$name <br>($current_team)</div>
			<div class='col-2'><input type='text' class='form-control' id='player_name".$profile_id."' name='player_name".$profile_id."' value='$player_name' placeholder='player_name'></div>
			<div class='col-2'><input type='text' class='form-control' id='player_name_th".$profile_id."' name='player_name_th".$profile_id."' value='$player_name_th' placeholder='ชื่อภาษาไทย'></div>
			<div class='col-2'><input type='text' class='form-control' id='height".$profile_id."' name='height".$profile_id."' value='$height' placeholder='height'></div>
			<div class='col-2'><input type='text' class='form-control' id='weight".$profile_id."' name='weight".$profile_id."' value='$weight' placeholder='weight'></div>
			<!-- <div class='col-6'><input type='text' class='form-control' id='image".$profile_id."' name='image".$profile_id."' value='$image' placeholder='image'></div> -->
			<div class='col-8'><div id='res".$profile_id."'></div><button type='button' class='btn btn-primary' onclick='".$action."'>Update ".$name."</button></div>
			<hr></div>";

			// $update_team = array(
			// 	'league_id' => 1056
			// );
			// $this->team_model->store($team_id, $update_team);
			// Debug($this->db->last_query());
		}
		$html .= '</div>';

		$webtitle= '';
		$data = array(
            "webtitle" => $webtitle.' '.$team_name,
            "breadcrumb" => null,
			"html" => $html,
			"content_view" => 'tool/blank'
        );
        $this->load->view('template', $data);
	}

	public function view_team($team_id = 0){
		
		$obj_list = $this->team_model->merge_data();
		// $obj_list = $this->team_model->get_data($this->tournament_id);
		// Debug($this->db->last_query());
		// Debug($obj_list);
		// die();

		$number_item = count($obj_list);
		for($i=0;$i<$number_item;$i++){

			$rows = $obj_list[$i];

			$id = $rows->team_id;
			$league_id = $rows->league_id;
			$team_name = $rows->team_name;
			$team_name_en = $rows->team_name_en;
			$logo = $rows->logo;
			$stadium_id = $rows->stadium_id;
			$stadium_name = $rows->stadium_name;
			$stadium_name_th = $rows->stadium_name_th;
			$location = $rows->location;
			$capacity = $rows->capacity;
			$manager_id = $rows->manager_id;
			
			$tournament_name = $rows->tournament_name;
			$tournament_name_en = $rows->tournament_name_en;
			$season = $rows->season;
			$image_path = $rows->image_path;

			$show_team = '';

			// if(file_exists($this->base_path.$this->stadium_path.$stadium_id)) {
			// 	unlink($this->base_path.$this->stadium_path.$stadium_id);
			// }

			// if(file_exists($this->base_path.$this->team_path.$team_id)) {
			// 	unlink($this->base_path.$this->team_path.$team_id);
			// }

			// $file_logo = base_url($full_path.$team_id);
			// echo "$file_logo<br>";
			// $show_file = file_get_contents($file_logo);
			// echo "$show_file<br>";
			// $show_team = $this->load_base64img($show_file);
			// echo file_get_contents($full_path.$team_id);

			$read_file = $this->team_path.$id.'.txt';
			// echo "<br> check pathfile ".$read_file."<br>";

			// if($id == 7084 && file_exists($read_file))
			// 	unlink($this->base_path.$read_file);

			if(file_exists($read_file)) {
				// echo "is file.";
				$file = fopen($read_file, 'r');
				//Output lines until EOF is reached
				while(!feof($file)) {
					// echo "reading...";
					$img_logo = fgets($file);
					// echo $img_logo. "<br>";
				}
				fclose($file);
				$show_team = $this->load_base64img($img_logo, 200, 200);
			}
			$show_img = '';
			if($image_path != '')
				$show_img = '<img src="'.$image_path.'" width="50" >';

			echo "$id $team_name $team_name_en ".$show_team." ".$show_img."<hr>";
			// echo "$id $team_name ".$show_team."<hr>";

			// $update_team = array(
			// 	'logo' => $image_path
			// );
			// $this->team_model->store($id, $update_team);
			// Debug($this->db->last_query());
		}
	}

	public function list_teams($get_teamlist)
	{
		$icon_clip = $icon_gallery = $show_team = $html = '';

		// Debug($get_teamlist);
		// die();

		$number_item = count($get_teamlist);
		for($i=0;$i<$number_item;$i++){

			$rows = $get_teamlist[$i];

			$team_id = $rows->team_id;
			$team_name = StripTxt($rows->team_name);
			$manager_name = trim($rows->manager_name);
			// $short_description = $rows->short_description;
			$tournament_name = $rows->tournament_name;
			$tournament_name_en = $rows->tournament_name_en;
			$short_name = $rows->short_name;
			$tournament_logo = $rows->tournament_logo;


			// $news_created = date('Y-m-d H:i', strtotime($created_at));
			// list($news_date, $news_time) = explode(' ', $news_created);
			// $news_date_th = DateTH($news_date);

			// $icon_clip = '<div class="icon-float-big"><img src="./assets/images/icon-video.png" alt="video"/></div>';
			// $icon_gallery = '<div class="icon-float-big"><img src="./assets/images/icon-gallery.png" alt="gallery"/></div>';
			// echo $this->base_path.$this->team_path.$team_id."<br>";
			
			if(file_exists($this->base_path.$this->team_path.$team_id.'.txt')) {

				$file = fopen($this->base_path.$this->team_path.$team_id.'.txt', 'r');
				//Output lines until EOF is reached
				while(! feof($file)) {
					$img_logo = fgets($file);
					// echo $img_logo. "<br>";
				}
				fclose($file);

				$show_team = $this->load_base64img($img_logo, 100, 0, $team_name);
			}

			// $img_cover_news = '<figure><img src="'.$img_url.'" alt="'.$title.'" title="'.$title.'" /></figure>';
			$link_team = base_url('team/detail/'.$team_id);

			$html .= '<div class="col"><a href="'.$link_team.'" target="_blank"><figure>'.$show_team.'</figure><strong>'.$team_name.'</strong></a></div>';

			// if($i == 5){
			// 	$html .= $this->load->view('widgets-rectangle2', null, true);
			// }

		}

		return $html;
	}

	public function view_tags($tags)
	{

		$output = '';
		if($tags)
			foreach($tags as $val){

				if(trim($val) != '')
					$output .= '<a href="#">'.$val.'</a>';
			}
		return $output;
	}

	public function widgets_program($fixtures_list)
	{

		$tmp = $html = $home_team = $logo_team1 = $logo_team2 = $away_team = '';
		
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
				$logo_team1 = $this->widgets_load_img($img_logo, 25, 15, $home_team);
			}

			if(file_exists($this->base_path.$this->team_path.$ateam_id.'.txt')) {

				$file = fopen($this->base_path.$this->team_path.$ateam_id.'.txt', 'r');
				//Output lines until EOF is reached
				while(! feof($file)) {
					$img_logo = fgets($file);
					// echo $img_logo. "<br>";
				}
				fclose($file);
				$logo_team2 = $this->widgets_load_img($img_logo, 25, 15, $away_team);
			}
			
			$link_teamhome = base_url('team/detail/'.$hteam_id);
			$link_teamaway = base_url('team/detail/'.$ateam_id);

			$view_analy = base_url('analyze/view/'.$program_id.'/'.$fix_id);
			$link_matchdetail = base_url('match/detail/'.$program_id.'/'.$fix_id);

			$html .= '<div>
				<span>
				<a href="'.$link_teamhome.'" target="_blank">'.$home_team.' '.$logo_team1.'</a> 
				<strong><a href="'.$view_analy.'" target="_blank">VS</a></strong> 
				<a href="'.$link_teamaway.'" target="_blank">'.$logo_team2.' '.$away_team.'</a></span> 
				<a href="'.$view_analy.'" target="_blank">'.$match_datetime_th.'</a> 
			</div>';

		}
		
		return $html;
	}

	public function widgets_result($result_list)
	{

		$tmp = $html = '';
		
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
				$logo_team1 = $this->widgets_load_img($img_logo, 25, 15, $home_team);
			}

			if(file_exists($this->base_path.$this->team_path.$ateam_id.'.txt')) {
				$file = fopen($this->base_path.$this->team_path.$ateam_id.'.txt', 'r');
				while(! feof($file)) {
					$img_logo = fgets($file);
				}
				fclose($file);
				$logo_team2 = $this->widgets_load_img($img_logo, 25, 15, $away_team);
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

	public function detail($team_id = 0)
	{
		$breadcrumb = array();
		$display_menu = $html = $wc_date_th = $show_team_logo = $page_image = '';

		$season = $this->season;
		$tournament_id = $this->tournament_id;
		$tournament_name = $this->tournament;
		//$user_agent = $this->input->user_agent;

		$cur_date = date('Y-m-d H:i:s');
		$datenow = date('Y-m-d');
		$sel_date = $datenow;

        $datebetween[] = $datenow.' 00:00:00';
        $datenext = date("Y-m-d", strtotime("+1 day"));
        $datebetween[] = $datenext.' 23:59:59';

		$section = 'detail';
		$cache_key = 'page_'.$this->_page.'_'.$section.'-team-'.$team_id;
		$cache = $this->utils->getCache($cache_key);
		
		if($cache && $this->_cache){

			if($this->input->get('oncheck') == 1){
				echo "Cache On ".$cache_key."<hr>";
			}
			echo $cache.'<!-- Cache -->';
		}else{

			$get_teamlist = $this->team_model->get_data($tournament_id, $team_id);
			// Debug($this->db->last_query());
			// Debug($get_teamlist);
			if(empty($get_teamlist)){

				redirect('/');
				die();
			}

			$team_name = $get_teamlist[0]->team_name;
			$team_name_en = $get_teamlist[0]->team_name_en;
			// $manager_name = $get_teamlist[0]->manager_name;
			$manager_name = ($get_teamlist[0]->manager_name_th != '') ? $get_teamlist[0]->manager_name_th:$get_teamlist[0]->manager_name;

			$tournament_name = $get_teamlist[0]->tournament_name;
			$tournament_name_en = $get_teamlist[0]->tournament_name_en;
			$short_name = $get_teamlist[0]->short_name;
			$season = $get_teamlist[0]->season;

			$create_date = $get_teamlist[0]->create_date;
			$lastupdate_date = $get_teamlist[0]->lastupdate_date;

			$get_player = $this->team_model->team_player($team_id);
			// Debug($get_player);
			// die();

			$res = $this->standing_model->get_xml_data(intval($tournament_id), intval($team_id));

			$group_id = $res[0]->group_id;
			$standing_list = $this->standing_model->get_group(intval($tournament_id), intval($group_id));
			// Debug($this->db->last_query());
			// Debug($standing_list);
			// die();
			$fixtures_list = $this->fixtures_model->get_data(0, $team_id, $this->tournament_id);
			// Debug($this->db->last_query());
			// Debug($fixtures_list);
			// die();
			$display_standing = $this->display_standing($standing_list);
			$display_program = $this->display_program($fixtures_list);
			$display_player = $this->display_player($get_teamlist, $get_player);

			if(file_exists($this->base_path.$this->team_path.$team_id.'.txt')) {
				$file = fopen($this->base_path.$this->team_path.$team_id.'.txt', 'r');
				while(! feof($file)) {
					$page_image = fgets($file);
				}
				fclose($file);
				$show_team_logo = $this->widgets_load_img($page_image, 80, 40);

				$page_image = 'data:image/jpeg;base64, '.$page_image;
			}

			$keywords[] = 'ทีมชาติ'.$team_name;
			$keywords[] = $team_name_en;
			$keywords[] = $manager_name;
			$keywords[] = $tournament_name;
			$keywords[] = $tournament_name_en;
			$keywords[] = $short_name;
			$keywords[] = $season;
			
			$webtitle = 'ทีมชาติ'.$team_name.' และ รายชื่อนักเตะ'.$team_name.$tournament_name.' '.$manager_name;
			$page_published_time = date('c' , strtotime($create_date));
			$page_lastupdated_date = date('c', strtotime($lastupdate_date));
			// $keywords = explode(',', $keyword);

			// $html = $this->news_detail($news_obj->data);
			$view_tags = $this->view_tags($keywords);
			// $script_fancybox = $this->script_fancybox();
			$social_block = $this->social_block($webtitle);

			$team_cover = './assets/images/'.$team_name_en.'-2022x620.webp';
			if(file_exists($team_cover)){
				$page_image = base_url($team_cover);
			}

			$meta = array(
				'title' => URLTitle($webtitle),
				'description' => 'ทีมชาติ'.$team_name.' '.$team_name_en.' '.$tournament_name.' '.$tournament_name_en.' '.$manager_name,
				'keywords' => $keywords,
				'page_image' => $page_image,
				"page_published_time" => $page_published_time,
				"Author" => "Ballnaja",
				"Copyright" => "Ballnaja"
			);
			// echo "<br>".$team_cover;
			// echo "<br>".$team_name_en;
			// Debug($meta);
			// die();

			$css[] = 'jquery.fancybox.css';
			$css[] = 'jquery.fancybox-thumbs.css';
			$js[] = 'jquery.fancybox.js';
			$js[] = 'jquery.fancybox-thumbs.js';

			$data = array(
				"meta" => $meta,
				"webtitle" => URLTitle($webtitle),
				"breadcrumb" => $breadcrumb,
				"menu" => $display_menu,
				// "news_date_th" => $news_date_th,
				// "news_time" => $news_time,
				"title" => $team_name,
				"team_name_en" => $team_name_en,
				"show_team_logo" => $show_team_logo,
				"display_standing" => $display_standing,
				"display_program" => $display_program,
				"display_player" => $display_player,
				"view_tags" => $view_tags,
				"social_block" => $social_block,
				"css" => $css,
				"js" => $js,
				"page_lastupdated_date" => $page_lastupdated_date,
				"content_view" => 'team/detail'
			);
			// $this->load->view('template-wc', $data);
			$html = $this->load->view('template-wc', $data, true);

			//cache to redis
			$this->utils->setCacheRedis($cache_key, $html);
			echo $html;
			$this->db->close();
		}
		
	}

	public function display_standing($obj_list)
	{
		$html = $tmp = '';
		$datenow = date('Y-m-d');
		if ($obj_list){
			$allitem = count($obj_list);
		
			for ($i = 0; $i < $allitem; $i++) {

				$rows = $obj_list[$i];

				$id = $rows->id;
				$country = $rows->country;
				$tournament_id = $rows->tournament_id;
				$tournament_name = $rows->tournament_name;
				$season = $rows->season;
				$round = $rows->round;
				$group_name = str_replace('Group', 'กลุ่ม', $rows->group_name);
				$group_id = $rows->group_id;
				$stage_id = $rows->stage_id;

				$team_position = $rows->team_position;
				$team_status = $rows->team_status;
				$team_id = $rows->team_id;
				$team_name = ($rows->team_name != '') ? $rows->team_name : $rows->team_name_en;
		
				$overall_gp = $rows->overall_gp;
				$overall_w = $rows->overall_w;
				$overall_d = $rows->overall_d;
				$overall_l = $rows->overall_l;
				$overall_gs = $rows->overall_gs;
				$overall_ga = $rows->overall_ga;
				$total_gd = $rows->total_gd;
				$total_p = $rows->total_p;
				$description = $rows->description;
				$recent_form = $rows->recent_form;

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
	
					$logo_team = $this->widgets_load_img($img_logo, 25, 15, $team_name);
				}
				$link_team = base_url('team/detail/'.$team_id);

				if($tmp == ''){

					$html .= '<table>
					<tbody>
						<tr>
							<td colspan="16"><h3>'.$group_name.'</h3></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>ทีมชาติ</td>
							<td>P</td>
							<td>W</td>
							<td>D</td>
							<td>L</td>
							<td>F</td>
							<td>A</td>
							<td>+/-</td>
							<td>Pts</td>
						</tr>';
					$tmp = $group_name;
				}else if($tmp != $group_name){

					$html .= '
					</tbody>
					</table>
					<a href="#">โปรแกรม ผลบอล '.$tmp.'</a>
					<table>
					<tbody>
						<tr>
							<td colspan="16"><h3>'.$group_name.'</h3></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>ทีมชาติ</td>
							<td>P</td>
							<td>W</td>
							<td>D</td>
							<td>L</td>
							<td>F</td>
							<td>A</td>
							<td>+/-</td>
							<td>Pts</td>
						</tr>';
					$tmp = $group_name;
				}

				$html .= '<tr>
					<td><a href="'.$link_team.'" target="_blank" >'.$logo_team.'</a></td>
					<td><a href="'.$link_team.'" target="_blank" >'.$team_name.'</a></td>
					<td>'.$overall_gp.'</td>
					<td>'.$overall_w.'</td>
					<td>'.$overall_d.'</td>
					<td>'.$overall_l.'</td>
					<td>'.$overall_gs.'</td>
					<td>'.$overall_ga.'</td>
					<td>'.$total_gd.'</td>
					<td>'.$total_p.'</td>
				<tr>';

			}

			$html .= '
			</tbody>
		  </table>';
		  //$html .= '<a href="#">โปรแกรม ผลบอล '.$tmp.'</a>';
		}

		return $html;
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
				$week = $rows->week;

				$ht_result = $rows->ht_result;
				$ft_result = $rows->ft_result;
				$et_result = $rows->et_result;
				$penalty = $rows->penalty;
				$result = $rows->result;

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

				if($stage_id != 10561027){
					$group_name = '';
				}

				if(file_exists($this->base_path.$this->team_path.$hometeam_id.'.txt')) {
					$file = fopen($this->base_path.$this->team_path.$hometeam_id.'.txt', 'r');
					while(! feof($file)) {
						$img_logo = fgets($file);
					}
					fclose($file);

					$logo_team1 = $this->widgets_load_img($img_logo, 25, 20, $hometeam_title);
				}
	
				if(file_exists($this->base_path.$this->team_path.$awayteam_id.'.txt')) {
					$file = fopen($this->base_path.$this->team_path.$awayteam_id.'.txt', 'r');

					while(! feof($file)) {
						$img_logo = fgets($file);
					}
					fclose($file);
					$logo_team2 = $this->widgets_load_img($img_logo, 25, 20, $awayteam_title);
				}

				if($tmp == ''){

					$html .= '<strong id="'.$sel_date.'">'.$show_date_th.'</strong>';
					$tmp = $sel_date;
				}else if($tmp != $sel_date){

					$html .= '<strong id="'.$sel_date.'">'.$show_date_th.'</strong>';
					$tmp = $sel_date;
				}

				if($program_status != 'FT' && $program_status != 'Pen.'){

					$time_score = $match_time;
				}else{
					
					if($ft_result != ''){

						$time_score = "FT<br>".$ft_result;

						if($penalty != ''){
							$time_score .= "<br>PEN<br>".$penalty;
						}else if($et_result != ''){
							$time_score .= "<br>".$et_result;
						}
						
					}else{

						$time_score = $hometeam_point.'-'.$awayteam_point;
					}
				}

				$class_endmatch = '';
				if($datenow == $sel_date){

					$class_endmatch = 'endmatch';
				}else if($datenow > $sel_date || $i == 0){
				
					$class_endmatch = 'endmatch';
				}

				$link_teamhome = base_url('team/detail/'.$hometeam_id);
				$link_teamaway = base_url('team/detail/'.$awayteam_id);
				$link_matchdetail = base_url('match/detail/'.$program_id.'/'.$fix_id);
				$link_analyzeview = base_url('analyze/view/'.$program_id.'/'.$fix_id);

				$html .= '
				<div class="match-list '.$class_endmatch.'">
					<div>
					<span><a href="'.$link_teamhome.'" target="_blank">'.$hometeam_title_th.' '.$logo_team1.'</a>
					<strong><a href="'.$link_matchdetail.'" target="_blank">'.$time_score.'</a></strong> 
					<a href="'.$link_teamaway.'" target="_blank" >'.$logo_team2.' '.$awayteam_title_th.'</a></span>
					<span>'.$group_name.'</span>
					<!-- <span>ช่องถ่ายทอด '.$channel_name.'</span> -->
					<span>สนาม '.$stadium_name.'</span>
					<a href="'.$link_analyzeview.'" target="_blank">วิเคราะห์ก่อนเกมส์</a>
					<!-- <a href="#">อ่านข่าว</a> -->
					</div>
				</div>';

			}
		}

		return $html;
	}

	public function display_player($team_list, $player_list){
		$html = $tmp = $manager_img = $manger_detail = $auto_get = '';
		$delay = 500;
		$update_date = 0;

		$team_id = $this->uri->segment(3);

		if($this->input->get('debug') == 1){
			$update_date = 1;
		}
		// Debug($team_list);
		// Debug($player_list);
		$manager_id = $team_list[0]->manager_id;
		// $manager_name = $team_list[0]->manager_name;
		$manager_name = ($team_list[0]->manager_name_th != '') ? $team_list[0]->manager_name_th:$team_list[0]->manager_name;

		$formation = $team_list[0]->formation;
		// $manager_img = '<figure><img src="assets/images/demo-pic-profile3.jpg" alt=""/></figure>';

		/*$manger_detail = '<div>
			<span>วันเกิด : 9 สิงหาคม 1947</span>
			<span>อายุ : 60</span>
			<span>สัญชาติ : อังกฤษ</span>
		</div>';*/

		$html = '<div class="profile-coach">
			'.$manager_img.'
			<h4><span>ผู้จัดการทีม</span>'.$manager_name.'</h4>
			'.$manger_detail.'
	  	</div>';

		// $html .= '<div class="profile-player">
		// <div>
		//   <span><div></div></span>
		//   <span>อายุ</span>
		//   <span>วันเกิด</span>
		//   <span>ส่วนสูง</span>
		//   <span>นำ้หนัก</span>
		// </div>';

		$num_player = count($player_list);
		for($i=0;$i<$num_player;$i++){

			$rows = $player_list[$i];

			// Debug($rows);
			// die();
			$id = $rows->id;
			$profile_id = $rows->profile_id;
			$player_name = ($rows->player_name_th != '') ? $rows->player_name_th : $rows->player_name;
			$number = $rows->number;
			$age = $rows->age;
			$position = $rows->position;
			$injured = $rows->injured;
			$minutes = $rows->minutes;
			$appearences = $rows->appearences;
			$lineups = $rows->lineups;
			$goals = $rows->goals;
			$assists = $rows->assists;
			$yellowcards = $rows->yellowcards;
			$redcards = $rows->redcards;
			$yellowred = $rows->yellowred;

			$player_position = $rows->player_position;
			$birthdate = $rows->birthdate;
			// $birthdate = ($rows->birthdate != '') ? date('d-m-Y', strtotime($rows->birthdate)) : '';
			$birthcountry = $rows->birthcountry;
			$birthplace = $rows->birthplace;
			$age = $rows->age;
			$height = $rows->height;
			$weight = $rows->weight;
			$image = $rows->image;
			$current_team = $rows->current_team;

			$head_position = '';
			
			if($tmp == ''){
				if(strtoupper($position) == 'G' || ucfirst($player_position) == 'Goalkeeper'){
					$head_position = 'ผู้รักษาประตู';
				}else if(strtoupper($position) == 'D' || ucfirst($player_position) == 'Defender'){
					$head_position = 'กองหลัง';
				}else if(strtoupper($position) == 'M'){
					$head_position = 'กองกลาง';
				}else if(strtoupper($position) == 'A'){
					$head_position = 'กองหน้า';
				}
				$html .= '
				<div class="profile-player">
					<div>
					<span><div>'.$head_position.'</div></span>
					<span>อายุ</span>
					<span>วันเกิด</span>
					<span>ส่วนสูง</span>
					<span>นำ้หนัก</span>
					</div>';
				$tmp = $position;
			}else if($tmp != strtoupper($position)){

				if(strtoupper($position) == 'G'){
					$head_position = 'ผู้รักษาประตู';
				}else if(strtoupper($position) == 'D'){
					$head_position = 'กองหลัง';
				}else if(strtoupper($position) == 'M'){
					$head_position = 'กองกลาง';
				}else if(strtoupper($position) == 'A'){
					$head_position = 'กองหน้า';
				}
				$html .= '</div>
				<div class="profile-player">
					<div>
					<span><div>'.$head_position.'</div></span>
					<span>อายุ</span>
					<span>วันเกิด</span>
					<span>ส่วนสูง</span>
					<span>นำ้หนัก</span>
					</div>';
				$tmp = $position;
			}

			if($player_name == '')
				$player_name = $rows->name;
				
			// if($birthdate == '01-01-1970')
			// 	$birthdate = '';

			if(file_exists($this->base_path.$this->profile_path.$profile_id)) {
				unlink($this->base_path.$this->profile_path.$profile_id);
			}

			if(file_exists($this->base_path.$this->profile_path.$profile_id.'.txt')) {
				unlink($this->base_path.$this->profile_path.$profile_id.'.txt');
			}

			$profile_file = $this->base_path.$this->profile_path.$team_id.'/'.$profile_id.'.txt';
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

				if(trim($img_profile) != '')
					$player_img = $this->load_base64img_profile($img_profile, 100, 0, $player_name);
				else{
					$update_date = 1;
					$player_img = '<figure><img src="'.base_url(_NO_PLAYER).'" alt="No player"/></figure>';
				}

			}else{

				$update_date = 1;
				$player_img = '<figure><img src="'.base_url(_NO_PLAYER).'" alt="No player"/></figure>';
			}

			// $img_cover_news = '<figure><img src="'.$img_url.'" alt="'.$title.'" title="'.$title.'" /></figure>';
			$link_profile = base_url('player/profile/'.$profile_id.'/'.$team_id);

			$html .= '<div>
				<span><a href="'.$link_profile.'">
				<span>'.$player_img.'</span>
				<span>'.$player_name.'</span><span class="small">('.$current_team.')</span></a><div class="hidden" id="res_profile'.$profile_id.'"></div></span>
				<span>'.$age.'</span>
				<span>'.$birthdate.'</span>
				<span>'.$height.'</span>
				<span>'.$weight.'</span>
			</div>';

			if($player_position == '' || $this->input->get('update') == 1){
				
				$update_profile = "call_update_profile(".$profile_id.", ".$team_id.", 'res_profile".$profile_id."')";
				$auto_get .= "\n setTimeout( ".$update_profile." , ".$delay."); \n";
				$delay += 500;
			}
			if($update_date == 1){
				
				$update_profile = "call_debug_profile(".$profile_id.", ".$team_id.", 'res_profile".$profile_id."')";
				$auto_get .= "\n setTimeout( ".$update_profile." , ".$delay."); \n";
				$delay += 500;
			}
		}
		$html .= '</div>';

		$html .= '
		<script>
		$(document).ready(function() {
			'.$auto_get.'
		});
		</script>';

		return $html;
	}

	public function profile($profile_id, $team_id = 0)
	{
		$breadcrumb = array();
		$display_menu = $html = $wc_date_th = $show_team_logo = $page_image = '';

		$season = $this->season;
		$tournament_id = $this->tournament_id;
		$tournament_name = $this->tournament;
		//$user_agent = $this->input->user_agent;

		$cur_date = date('Y-m-d H:i:s');
		$datenow = date('Y-m-d');
		$sel_date = $datenow;

        $datebetween[] = $datenow.' 00:00:00';
        $datenext = date("Y-m-d", strtotime("+1 day"));
        $datebetween[] = $datenext.' 23:59:59';

		$get_player = $this->team_model->team_player($team_id, $profile_id);
		// Debug($this->db->last_query());
		// Debug($get_player);
		// die();

		$team_name = $get_player[0]->team_name;
		$team_name_en = $get_player[0]->team_name_en;
		// $manager_name = $get_teamlist[0]->manager_name;
		// $manager_name = ($get_teamlist[0]->manager_name_th != '') ? $get_teamlist[0]->manager_name_th:$get_teamlist[0]->manager_name;

		// $tournament_name = $get_teamlist[0]->tournament_name;
		// $tournament_name_en = $get_teamlist[0]->tournament_name_en;
		// $short_name = $get_teamlist[0]->short_name;
		// $season = $get_teamlist[0]->season;

		// $create_date = $get_teamlist[0]->create_date;
		// $lastupdate_date = $get_teamlist[0]->lastupdate_date;

		// $get_player = $this->team_model->team_player($team_id);
		// Debug($get_player);
		// die();

		// $res = $this->standing_model->get_xml_data(intval($tournament_id), intval($team_id));

		// $group_id = $res[0]->group_id;
		// $standing_list = $this->standing_model->get_group(intval($tournament_id), intval($group_id));
		// Debug($this->db->last_query());
		// Debug($standing_list);
		// die();
		
		// $fixtures_list = $this->fixtures_model->get_data(0, $team_id, $this->tournament_id);
		// Debug($this->db->last_query());
		// Debug($fixtures_list);
		// die();

		// $display_standing = $this->display_standing($standing_list);
		// $display_program = $this->display_program($fixtures_list);
		$display_player = $this->display_profile($get_player);

		die();

		if(file_exists($this->base_path.$this->team_path.$team_id.'.txt')) {
			$file = fopen($this->base_path.$this->team_path.$team_id.'.txt', 'r');
			while(! feof($file)) {
				$page_image = fgets($file);
			}
			fclose($file);
			$show_team_logo = $this->widgets_load_img($page_image, 80, 40);
		}

		$keywords[] = 'ทีมชาติ'.$team_name;
		$keywords[] = $team_name_en;
		$keywords[] = $manager_name;
		$keywords[] = $tournament_name;
		$keywords[] = $tournament_name_en;
		$keywords[] = $short_name;
		$keywords[] = $season;
		
		$webtitle = 'ทีมชาติ'.$team_name;
		$page_published_time = date('c' , strtotime($create_date));
		$page_lastupdated_date = date('c', strtotime($lastupdate_date));
		// $keywords = explode(',', $keyword);

		// $html = $this->news_detail($news_obj->data);
		$view_tags = $this->view_tags($keywords);
		// $script_fancybox = $this->script_fancybox();
		$social_block = $this->social_block($webtitle);

		$meta = array(
			'title' => URLTitle($webtitle),
			'description' => 'ทีมชาติ'.$team_name.' '.$team_name_en.' '.$tournament_name.' '.$tournament_name_en.' '.$manager_name,
			'keywords' => $keywords,
			'page_image' => $page_image,
			"page_published_time" => $page_published_time,
			"Author" => "Ballnaja",
			"Copyright" => "Ballnaja"
		);

        $css[] = 'jquery.fancybox.css';
		$css[] = 'jquery.fancybox-thumbs.css';
        $js[] = 'jquery.fancybox.js';
		$js[] = 'jquery.fancybox-thumbs.js';

        $data = array(
			"meta" => $meta,
            "webtitle" => URLTitle($webtitle),
            "breadcrumb" => $breadcrumb,
			"menu" => $display_menu,
			// "news_date_th" => $news_date_th,
			// "news_time" => $news_time,
			"title" => $team_name,
			"show_team_logo" => $show_team_logo,
			"display_standing" => $display_standing,
			"display_program" => $display_program,
			"display_player" => $display_player,
			"view_tags" => $view_tags,
			"social_block" => $social_block,
			"css" => $css,
			"js" => $js,
			"page_lastupdated_date" => $page_lastupdated_date,
			"content_view" => 'team/detail'
        );
        $this->load->view('template-wc', $data);
		
	}

	function display_profile($get_player){
		$html = $tmp = $manager_img = $manger_detail = $auto_get = '';
		$delay = 500;
		$update_date = 0;

		$team_id = $this->uri->segment(4);

		if($this->input->get('debug') == 1){
			$update_date = 1;
		}
		Debug($get_player);
	}

	public function load_base64img_profile($src, $width = 100, $height = 0, $title = ''){
		//width:100px;height:100px;
		$output = "<figure><img class='base64image round_profile' width='".$width."' alt='".$title."' title='".$title."' src='data:image/jpeg;base64, $src' /></figure>";
      	return $output;
	}

	public function load_base64img($src, $width = 100, $height = 0, $title = ''){
		//width:100px;height:100px;
		$output = "<figure><img class='base64image round' width='".$width."' alt='".$title."' title='".$title."' src='data:image/jpeg;base64, $src' /></figure>";
      	return $output;
	}

	public function widgets_load_img($src, $width = 100, $height = 0, $title = ''){
		//width:100px;height:100px;
		$output = "<img class='base64image' width='".$width."' height='".$height."' alt='".$title."' title='".$title."' src='data:image/jpeg;base64, $src' />";
      	return $output;
	}
}
