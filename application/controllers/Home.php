<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	protected $_page = 'home';
	protected $_cache;
	protected $tournament_id;
	protected $tournament;
	protected $season;
	protected $date_start;
	protected $datetime_start;
	protected $catid;
	protected $catid2;
	protected $profile_path;
	protected $team_path;
	protected $stadium_path;
	protected $base_path;

	public function __construct(){
        parent::__construct();

		$this->load->database();

		// $this->load->model('xml_model');
		$this->load->model('tournament_model');
		$this->load->model('standing_model');
		$this->load->model('fixtures_model');
		$this->load->library('api');
		// $this->load->library('utils');
		$this->load->helper('common');

		// Debug($this->config->config);
		// die();

		$this->tournament_id = $this->config->config['tournament_id'];
		$this->tournament = $this->config->config['tournament'];
        $this->season = $this->config->config['season'];
 		$this->date_start = $this->config->config['date_start'];
		$this->datetime_start = $this->config->config['datetime_start'];

		$this->catid = $this->config->config['catid_news'];
		$this->catid2 = $this->config->config['catid_other'];

		$this->profile_path = 'data/uploads/player/';
        $this->team_path = 'data/uploads/teamlogo/';
		$this->stadium_path = 'data/uploads/stadium/';
		$this->base_path = str_replace('application/controllers', '', __DIR__);

		$this->_cache = false;
		// if($this->input->get('cache') == 'disable'){
		// 	$this->_cache = false;
		// }else
		// 	$this->_cache = true;
    }

	public function index()
	{
		$breadcrumb = array();
		$display_menu = $html = $wc_date_th = $script_countdown = '';
		$team_id = 0;
		$d = 25;
		$h = $m = 0;
		$use_other = 0;

		$season = $this->season;
		$tournament_id = $this->tournament_id;
		$tournament_name = $this->tournament;
		//$user_agent = $this->input->user_agent;

		$cur_date = date('Y-m-d H:i:s');
		$datenow = date('Y-m-d');
		$sel_date = $datenow;

		$dateprev = date("Y-m-d", strtotime("-1 day"));
		$datenext = date("Y-m-d", strtotime("+1 day"));
        $datebetween[] = $dateprev.' 00:00:00';
        $datebetween[] = $datenext.' 23:59:59';

		$section = 'index';
		$cache_key_all = 'page_'.$this->_page.'_'.$section;
		$cache = null;
		// $cache = $this->utils->getCache($cache_key_all);

		// echo $cache_key_all."<hr>";
		// echo "(".$cache." && ".$this->_cache.")";

		if($cache && $this->_cache){

			if($this->input->get('oncheck') == 1){
				echo "Cache On ".$cache_key_all."<hr>";
			}
			echo $cache.'<!-- Cache -->';
		}else{

			$tournament_list = $this->tournament_model->get_data($tournament_id);
			// Debug($tournament_list);

			$thisround = $this->standing_model->get_max_week($tournament_id, '_xml_standing', $season);
			// echo $this->db->last_query();
			// Debug($thisround);

			//**** Standing *******
			// $obj = $this->standing_model->get_standing(intval($tournament_id), $season);
			$obj = $this->standing_model->get_xml_data(intval($tournament_id), $team_id, $thisround);
			// echo $this->db->last_query();
			// Debug($obj);
			// die();

			if($datenow < $this->date_start){
				$sel_date = $this->date_start;
			}

			//**** Program *******
			$fixtures_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id, $datebetween);
			// $fixtures_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id);

			// echo $this->db->last_query();
			// Debug($fixtures_list);
			// die();

			if(isset($fixtures_list[0]->kickoff)){

				$Open_worldcup = date('Y-m-d H:i:s', strtotime($fixtures_list[0]->kickoff.' +7 hour'));
				// $Open_worldcup = date('Y-m-d H:i:s', strtotime($fixtures_list[0]->kickoff));
				// Debug($Open_worldcup);
				list($wc_date, $wc_time) = explode(' ', $Open_worldcup);
				$wc_date_th = DateTH($wc_date);

				// $datenext = date("Y-m-d", strtotime("+5 day"));
				// echo date("l", strtotime($datenext));
				$wc_days = Get_daysTH($wc_date);
				// echo "<br>($wc_date, $wc_time) ($wc_date_th)($wc_days)<br>";

				// echo "<br>cur date ($cur_date)<br>";
				$check_date_open = $Open_worldcup;
				$check_date_open = date('Y-m-d H:i:s', strtotime($Open_worldcup.' +10 hour'));
				list($d, $h, $m, $s) = dateDiv($cur_date, $check_date_open);
				
				// echo "($d, $h, $m, $s)<br>";
				// die();
				// echo "($cur_date > $wc_date)";
				// echo $this->date_start;
			}
			// die();

			//**** News *******
			// $get_listnew = $this->api->get_listnew($this->catid, 1, 13);
			// $list_news = $get_listnew->data;
			$list_news = array();
			$number_news = count($list_news);

			// echo "(number_news=$number_news)<br>";
			if($number_news < 3){
				// $get_listnew_other = $this->api->get_listnew($this->catid2);
				// $list_news_other = $get_listnew_other->data;
				$list_news_other = array();
				$list_news = array_merge($list_news,$list_news_other);
				$number_news = count($list_news);
				// echo "(number_news=$number_news)<br>";
				$use_other = 1;
			}
			// Debug($list_news);
			// Debug($get_listnew_other);
			// die();

			//Check Start Worldcup
			if($cur_date > $this->date_start){

				$running_text = $this->headtext_running($wc_date_th, $fixtures_list);
			}else{
				$running_text = $this->headtext_countdown($wc_days.'ที่ '.$wc_date_th);
				$script_countdown = $this->script_countdown($d, $h, $m);			
			}

			$hightlight = $this->hightlight($list_news);

			//ดึงข่าวจาก other ถ้ามีข่าวไม่ถึง 6 ข่าว
			if($number_news < 6 && $use_other == 0){
				$get_listnew = $this->api->get_listnew($this->catid2);
				$list_news = $get_listnew->data;
			}
			$hightlight_5news = $this->hightlight_5news($list_news);
			// Debug($fixtures_list);
			// die();


			//If Program Empty
			$program_today = $this->program_today($tournament_list, $fixtures_list);
			if($program_today == ''){

				$dateprev = date("Y-m-d");
				$datenext = date("Y-m-d", strtotime("+2 day"));
				unset($datebetween);
				$datebetween[] = $dateprev.' 00:00:00';
				$datebetween[] = $datenext.' 23:59:59';
				$program_today_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id, $datebetween);
				$program_today = $this->program_today($tournament_list, $program_today_list);
				// echo $this->db->last_query();
				// echo "<hr>".$program_today;
				// Debug($program_today_list);
				// die();
			}

			// $block_clip_video = $this->block_clip_video();
			$block_standing = $this->block_standing($tournament_list, $obj);
			// $block_standing = $this->block_standing_final($tournament_list, $obj);
			// $block_column = $this->block_column();
			// $block_wallpaper = $this->block_wallpaper();

			$webtitle = _TITLE;
			$page_published_time = date('c' , strtotime('2022-10-27'));
			$page_lastupdated_date = date('c');
			$keywords = explode(',', _KEYWORD);

			$meta = array(
				'title' => $webtitle,
				'description' => _DESCRIPTION,
				'keywords' => $keywords,
				'page_image' => _COVER_WC2022,
				"page_published_time" => $page_published_time,
				"Author" => "Ballnaja",
				"Copyright" => "Ballnaja"
			);

			$css[] = 'timeTo.css';
			$css[] = 'jquery.fancybox.css';
			$js[] = 'jquery.fancybox.js';
			$js[] = 'jquery.time-to.js';

			$data = array(
				"meta" => $meta,
				"webtitle" => $webtitle,
				"breadcrumb" => $breadcrumb,
				"menu" => $display_menu,
				"html" => $html,
				"running_text" => $running_text,
				"script_countdown" => $script_countdown,
				"hightlight" => $hightlight,
				"hightlight_5news" => $hightlight_5news,
				"program_today" => $program_today,
				// "clip_video" => $block_clip_video,
				"block_standing" => $block_standing,
				// "block_column" => $block_column,
				// "block_wallpaper" => $block_wallpaper,
				"css" => $css,
				"js" => $js,
				"page_lastupdated_date" => $page_lastupdated_date,
				"content_view" => 'home/index'
			);
			// $this->load->view('template-wc', $data);
			$html = $this->load->view('template-wc', $data, true);

			//cache to redis
			// $this->utils->setCacheRedis($cache_key_all, $html);
			echo $html;
			$this->db->close();
		}
	}

	public function html()
	{
		$this->load->view('html/home');
	}

	public function headtext_countdown($wc_date_th){
		$html = '<section class="warpper bg-blue countdown">
			<div>
				<h1>นับถอยหลังสู่ฟุตบอลโลก <span>'.$wc_date_th.'</span></h1>
				<div id="countdown-3">
				<span id="date-str"></span><span id="date2-str"></span></div>
		  	</div>
		</section>';

		return $html;
	}

	public function script_countdown($days = 25, $hours = 0, $minutes = 0){

		$html = '<script>
		var date = getRelativeDate('.$days.', '.$hours.', '.$minutes.');
		document.getElementById(\'date2-str\').innerHTML = date.toString();
	
			console.log(\''.$days.', '.$hours.', '.$minutes.'\');

			$(\'#countdown-3\').timeTo({
				timeTo: date,
				displayDays: 2,
				theme: "black",
				displayCaptions: true,
				fontSize: 48,
				captionSize: 14
			});
		
			function getRelativeDate(days, hours, minutes){
				var date = new Date((new Date()).getTime() + 60000 /* milisec */ * 60 /* minutes */ * 24 /* hours */ * days /* days */);
		
				date.setHours(hours || 0);
				date.setMinutes(minutes || 0);
				date.setSeconds(0);
		
				return date;
			}
		</script>';
		return $html;
	}

	//Head result program
	public function headtext_running($wc_date_th, $fixtures_list){

		$html = '<section class="warpper bg-blue result-head">
			<div>
				<h1>ผลบอลโลก <span>วันที่ '.$wc_date_th.'</span></h1>
				<div>';

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
			$kickoff_th = $rows->kickoff_th;
			$ft_result = $rows->ft_result;
			$program_status = $rows->program_status;
			
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
			$link_matchdetail = base_url('match/detail/'.$program_id.'/'.$fix_id);

			if($ft_result == '')
				$ft_result = $hgoals.'-'.$agoals;

			if($program_status == 'FT' || $program_status == 'Pen.'){

				$ft_result = str_replace("[", "", $ft_result);
				$ft_result = str_replace("]", "", $ft_result);

				$html .= '<span> 
				<a href="'.$link_teamhome.'" target="_blank">'.$home_team.' '.$logo_team1.'</a> 
				<a href="'.$link_matchdetail.'" target="_blank">'.$ft_result.'</a> 
				<a href="'.$link_teamaway.'" target="_blank">'.$logo_team2.' '.$away_team.'</a> 
				</span>';
			}
		}

		$html .= '</div>
			<a href="'.base_url('fixtures').'" class="more-link">ผลบอลทั้งหมด</a>
			</div>
  		</section>';

		return $html;
	}

	public function hightlight($list_news)
	{
		$icon_clip = $icon_gallery = '';
		$html = '<ul class="slides">';

		$number_item = (count($list_news) > 5) ? 5 : count($list_news);
		for($i=0;$i<$number_item;$i++){

			$rows = $list_news[$i];

			$newsid = $rows->id;
			$title = StripTxt($rows->title);
			// $description = $rows->description;
			$short_description = StripTxt($rows->short_description);
			$created_at = $rows->created_at;
			$updated_at = $rows->updated_at;
			$keyword = $rows->keyword;

			$categories = $rows->categories;

			$img_url = $rows->media->url;

			// $icon_clip = '<div class="icon-float-big"><img src="./assets/images/icon-video.png" alt="video"/></div>';
			// $icon_gallery = '<div class="icon-float-big"><img src="./assets/images/icon-gallery.png" alt="gallery"/></div>';

			$img_cover_news = '<img src="'.$img_url.'" alt="'.$title.'" title="'.$title.'" />';

			$link_news = base_url('news/detail/'.$newsid);

			$html .= '<li><a href="'.$link_news.'" target="_blank">
			'.$icon_clip.'
			'.$icon_gallery.'
			'.$img_cover_news.'
			<div><strong>'.$title.'</strong> <span>'.$short_description.'</span></div>
			</a> </li>';

		}
		$html .= '</ul>';
		
		return $html;
	}

	public function hightlight_5news($list_news)
	{
		$html = $icon_clip = $icon_gallery = '';

		$number_item = (count($list_news) > 13) ? 13 : count($list_news);
		for($i=5;$i<$number_item;$i++){

			$rows = $list_news[$i];

			$newsid = $rows->id;
			$title = StripTxt($rows->title);
			// $description = StripTxt($rows->description);
			// $short_description = $rows->short_description;
			$created_at = $rows->created_at;
			$updated_at = $rows->updated_at;
			$keyword = $rows->keyword;

			$categories = $rows->categories;
			$img_url = $rows->media->url;

			// $icon_clip = '<div class="icon-float-small"><img src="./assets/images/icon-video.png" alt="video"/></div>';
			// $icon_gallery = '<div class="icon-float-small"><img src="./assets/images/icon-gallery.png" alt="gallery"/></div>';
			$img_cover_news = '<img src="'.$img_url.'" alt="'.$title.'" title="'.$title.'" />';

			$link_news = base_url('news/detail/'.$newsid);

			$html .= '
				<a href="'.$link_news.'" target="_blank">
					'.$icon_clip.$icon_gallery.$img_cover_news.'
					<strong>'.$title.'</strong> 
				</a>';
		}
		return $html;
	}

	public function program_today($tournament_list, $fixtures_list)
	{
		$tmp = $html = '';

		$cur_date = date('Y-m-d');
		
		$number_program = count($fixtures_list);

		for($i=0;$i<$number_program;$i++){

			$rows = $fixtures_list[$i];
			// Debug($rows);

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
			$kickoff_th = $rows->kickoff_th;
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

			// $match_datetime_th = date('H:i', strtotime($kickoff.' +7 hour'));
			$match_datetime_th = date('H:i', strtotime($kickoff_th));
			
			$chk_date = strtotime($sel_date.' +7 hour');
			$chk_curdate = strtotime($cur_date);
			// Debug($rows);

			if($chk_date >= $chk_curdate){

				// Debug($rows);

				if($tmp == ''){

					$program_today = DateTH($sel_date);
					$html .= '<h2>โปรแกรม '.$program_today.'</h2>';
					$tmp = $sel_date;
				}else if($tmp != $sel_date){
	
					$program_today = DateTH($sel_date);
					$html .= '<h2>โปรแกรม '.$program_today.'</h2>';
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
	
				$html .= '<div>
				<span>
				<a href="'.$link_teamhome.'" target="_blank">'.$home_team.' '.$logo_team1.'</a> <strong><a href="'.$view_analy.'" target="_blank">VS</a></strong> 
				<a href="'.$link_teamaway.'" target="_blank">'.$logo_team2.' '.$away_team.'</a>
				</span><a href="'.$view_analy.'" target="_blank">'.$match_datetime_th.'</a> 
				</div>';
			}

		}
		// die();
		return $html;
	}

	public function block_clip_video()
	{
		$html = '<section class="warpper bg-white video-home">
		<div class="rectangle"></div>
		<h3><a href="#">คลิปวีดีโอ</a></h3></section>';
		
		return $html;
	}

	public function block_standing($tournament_list, $data_list)
	{
		$arr_group = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H');

		$topscore = null;
		// $topscore = $this->block_topscore();

		$html = '<section class="warpper bg-blue score-salvo-home">
		<div>
		  <div class="score-table">
			<h4><a href="'.base_url('standing').'" target="_blank">ตารางคะแนน</a></h4>
			<div class="score-tab">';

		$html .= '<ul id="tab-menu">';

		$number_group = 8;
		for($i=0;$i<$number_group;$i++){

			if($i == 0){
				$html .= '<li class="tab-active"><span>กลุ่ม</span> '.$arr_group[$i].'</li>';
			}else{
				$html .= '<li><span>กลุ่ม</span> '.$arr_group[$i].'</li>';
			}
		}

		$html .= '</ul>';

		$tmp = '';
		$number_team = count($data_list);
		for($i=0;$i<$number_team;$i++){

			$id = $data_list[$i]->id;
			$country = $data_list[$i]->country;
			$tournament_id = $data_list[$i]->tournament_id;
			$tournament_name = $data_list[$i]->tournament_name;
			$season = $data_list[$i]->season;
			$week = $data_list[$i]->week;
			$round = $data_list[$i]->round;
			$group_name = $data_list[$i]->group_name;
			$group_id = $data_list[$i]->group_id;
			$stage_id = $data_list[$i]->stage_id;
			$team_position = $data_list[$i]->team_position;
			$team_status = $data_list[$i]->team_status;
			$team_id = $data_list[$i]->team_id;
			$team_name = $data_list[$i]->team_name;
			$team_name_en = $data_list[$i]->team_name_en;
			$logo = $data_list[$i]->logo;
			$recent_form = $data_list[$i]->recent_form;
			$overall_gp = $data_list[$i]->overall_gp;
			$overall_w = $data_list[$i]->overall_w;
			$overall_d = $data_list[$i]->overall_d;
			$overall_l = $data_list[$i]->overall_l;
			$overall_gs = $data_list[$i]->overall_gs;
			$overall_ga = $data_list[$i]->overall_ga;
			$total_gd = $data_list[$i]->total_gd;
			$total_p = $data_list[$i]->total_p;
			$description = $data_list[$i]->description;

			// if($logo != '')
			// 	$logo_team = '<a href="#"><img src="'.$logo.'" alt="'.$team_name.'" title="'.$team_name.'" /></a>';
			// else
			// 	$logo_team = '';

			if(file_exists($this->base_path.$this->team_path.$team_id.'.txt')) {

				$file = fopen($this->base_path.$this->team_path.$team_id.'.txt', 'r');
				//Output lines until EOF is reached
				while(! feof($file)) {
					$img_logo = fgets($file);
					// echo $img_logo. "<br>";
				}
				fclose($file);

				$logo_team = $this->load_base64img($img_logo, 25, 15, $team_name);
			}
			$link_team = base_url('team/detail/'.$team_id);
			
			if($tmp == ''){

				$html .= '<ul id="tab-data">
				<li class="score-group tab-active">
				  <table>
					<tbody>
					  <tr>
						<td></td>
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

				$html .= '</tbody>
					</table>
				</li>
				<li class="score-group">
					<table>
					<tbody>
						<tr>
						<td></td>
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

			$html .= '
				<tr>
					<td><a href="'.$link_team.'" target="_blank">'.$logo_team.'</a></td>
					<td><a href="'.$link_team.'" target="_blank">'.$team_name.'</a></td>
					<td>'.$overall_gp.'</td>
					<td>'.$overall_w.'</td>
					<td>'.$overall_d.'</td>
					<td>'.$overall_l.'</td>
					<td>'.$overall_gs.'</td>
					<td>'.$overall_ga.'</td>
					<td>'.$total_gd.'</td>
					<td>'.$total_p.'</td>
				</tr>';
		}

		$html .= '</tbody>
					</table>
				</li>
			</ul>';

		$html .= '</div>
			<a class="more-link" href="'.base_url('standing').'">ตารางคะแนนทั้งหมด</a> 
			</div>
			'.$topscore.'
	  </section>';
		
		return $html;
	}
	
	public function block_standing_final($tournament_list, $data_list){
		
		$topscore = null;
		// $topscore = $this->block_topscore();

		$html = '<section class="warpper bg-blue score-salvo-home">
		<div>
		  <div class="score-table">
			<h4><a href="#" target="_blank">ตารางคะแนน</a></h4>
			<div class="matchfinal">
			
			<div>
			<h2><span>รอบ</span> 16 ทีม</h2>
			<div>
			<h3><a href="#" target="_blank"><strong>1 : 0</strong></a></h3>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon.png" alt=""/><span>อังกฤษ</span>
			</a>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon2.png" alt=""/><span>สวิตเซอร์แลนด์</span>
			</a>
			</div>
			
				  <div>
			<h3><a href="#" target="_blank"> <span>25 มิย.</span> 20.00 น.<br>ช่อง 3 , ช่อง CTH</a></h3>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon.png" alt=""/><span>อังกฤษ</span>
			</a>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon2.png" alt=""/><span>สวิตเซอร์แลนด์</span>
			</a>
			</div>
			
				  <div>
			<h3><a href="#" target="_blank"> <span>25 มิย.</span> 20.00 น.<br>ช่อง 3 , ช่อง CTH</a></h3>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon.png" alt=""/><span>อังกฤษ</span>
			</a>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon2.png" alt=""/><span>สวิตเซอร์แลนด์</span>
			</a>
			</div>
			
				  <div>
			<h3><a href="#" target="_blank"> <span>25 มิย.</span> 20.00 น.<br>ช่อง 3 , ช่อง CTH</a></h3>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon.png" alt=""/><span>อังกฤษ</span>
			</a>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon2.png" alt=""/><span>สวิตเซอร์แลนด์</span>
			</a>
			</div>        </div>
			
			<div>
			<figure><img src="images/space.png" alt=""/></figure>
			<figure><img src="images/space.png" alt=""/></figure>
			<figure><img src="images/space.png" alt=""/></figure>
			<figure><img src="images/space.png" alt=""/></figure>
			</div>
			
			<div>
			<h2><span>รอบ</span> 8 ทีม</h2>
			<div>
			<h3><a href="#" target="_blank"><strong>1 : 0</strong></a></h3>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon.png" alt=""/><span>อังกฤษ</span>
			</a>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon2.png" alt=""/><span>สวิตเซอร์แลนด์</span>
			</a>
			</div>
			
			<img src="images/space.png" alt=""/>
			
				  <div>
			<h3><a href="#" target="_blank"> <span>25 มิย.</span> 20.00 น.<br>ช่อง 3 , ช่อง CTH</a></h3>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon.png" alt=""/><span>อังกฤษ</span>
			</a>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon2.png" alt=""/><span>สวิตเซอร์แลนด์</span>
			</a>
			</div>
			<img src="images/space.png" alt=""/>
				  <div>
			<h3><a href="#" target="_blank"> <span>25 มิย.</span> 20.00 น.<br>ช่อง 3 , ช่อง CTH</a></h3>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon.png" alt=""/><span>อังกฤษ</span>
			</a>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon2.png" alt=""/><span>สวิตเซอร์แลนด์</span>
			</a>
			</div>        </div>
			
			<div>
			<figure><img src="images/space.png" alt=""/></figure>
			</div>
			
			<div>
			<h2><span>รอบชิง</span></h2>
			<div>
			<h3><a href="#" target="_blank"><strong>1 : 1</strong><br>AET 6 : 4</a></h3>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon.png" alt=""/><span>อังกฤษ</span>
			</a>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon2.png" alt=""/><span>สวิตเซอร์แลนด์</span>
			</a>
			</div>
						 </div>
			
			<div>
			<figure><img src="images/space.png" alt=""/></figure>
			</div>
			
			<div>
			<h2><span>รอบ</span> 8 ทีม</h2>
			<div>
			<h3><a href="#" target="_blank"><strong>1 : 0</strong></a></h3>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon.png" alt=""/><span>อังกฤษ</span>
			</a>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon2.png" alt=""/><span>สวิตเซอร์แลนด์</span>
			</a>
			</div>
			
			<img src="images/space.png" alt=""/>
				  <div>
			<h3><a href="#" target="_blank"> <span>25 มิย.</span> 20.00 น.<br>ช่อง 3 , ช่อง CTH</a></h3>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon.png" alt=""/><span>อังกฤษ</span>
			</a>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon2.png" alt=""/><span>สวิตเซอร์แลนด์</span>
			</a>
			</div>
			<img src="images/space.png" alt=""/>
				  <div>
			<h3><a href="#" target="_blank"> <span>25 มิย.</span> 20.00 น.<br>ช่อง 3 , ช่อง CTH</a></h3>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon.png" alt=""/><span>อังกฤษ</span>
			</a>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon2.png" alt=""/><span>สวิตเซอร์แลนด์</span>
			</a>
			</div>        </div>
			
			 <div>
			<figure><img src="images/space.png" alt=""/></figure>
			<figure><img src="images/space.png" alt=""/></figure>
			<figure><img src="images/space.png" alt=""/></figure>
			<figure><img src="images/space.png" alt=""/></figure>
			</div>
			
			<div>
			<h2><span>รอบ</span> 16 ทีม</h2>
			<div>
			<h3><a href="#" target="_blank"><strong>1 : 0</strong></a></h3>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon.png" alt=""/><span>อังกฤษ</span>
			</a>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon2.png" alt=""/><span>สวิตเซอร์แลนด์</span>
			</a>
			</div>
			
				  <div>
			<h3><a href="#" target="_blank"> <span>25 มิย.</span> 20.00 น.<br>ช่อง 3 , ช่อง CTH</a></h3>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon.png" alt=""/><span>อังกฤษ</span>
			</a>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon2.png" alt=""/><span>สวิตเซอร์แลนด์</span>
			</a>
			</div>
			
				  <div>
			<h3><a href="#" target="_blank"> <span>25 มิย.</span> 20.00 น.<br>ช่อง 3 , ช่อง CTH</a></h3>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon.png" alt=""/><span>อังกฤษ</span>
			</a>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon2.png" alt=""/><span>สวิตเซอร์แลนด์</span>
			</a>
			</div>
			
				  <div>
			<h3><a href="#" target="_blank"> <span>25 มิย.</span> 20.00 น.<br>ช่อง 3 , ช่อง CTH</a></h3>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon.png" alt=""/><span>อังกฤษ</span>
			</a>
			<a href="#" target="_blank">
			<img src="images/demo-team-icon2.png" alt=""/><span>สวิตเซอร์แลนด์</span>
			</a>
			</div>        </div>
			
			<div>
			
			</div>
			
			</div>
			<a class="more-link" href="#">ตารางคะแนนทั้งหมด</a> </div>
				'.$topscore.'
			</section>';
		
		return $html;
	}

	public function block_topscore()
	{
		$this->load->model('topscore_model');

		$obj_list = $this->topscore_model->get_xml_topscorers(intval($this->tournament_id));
		
		$html = '<div class="salvo-small">
			<h4><a href="'.base_url('topscore').'">ดาวซัลโว</a></h4>';

			if ($obj_list){

				$allitem = (count($obj_list) > 3) ? 3 : count($obj_list);
				for ($i = 0; $i < $allitem; $i++) {
	
					$rows = $obj_list[$i];

					$id = $rows->id;
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

					$profile_file = $this->base_path.$this->profile_path.$team_id.'/'.$player_id.'.txt';
					if(file_exists($profile_file)) {

						$file = fopen($profile_file, 'r');
						while(! feof($file)) {
							$img_profile = fgets($file);
						}
						fclose($file);
						$player_image = $this->load_base64img_profile($img_profile, 100, 0, $player_name);
					}else{

						$player_image = '<figure><img src="'.base_url(_NO_PLAYER).'" alt="No player"/></figure>';
					}

					$link_team = base_url('team/detail/'.$team_id);
					$link_profile = base_url('player/profile/'.$player_id.'/'.$team_id);
					
					$html .= '<div> 
						<a href="'.$link_profile.'" target="_blank"><span>'.$player_image.'</span> 
						<span><strong>'.$goals.'</strong> ประตู</span></a> 
						<a href="'.$link_profile.'" target="_blank"><strong>'.$display_player.'</strong></a> 
						<a href="'.$link_team.'" target="_blank">'.$logo_team.'</a> 
					</div>';
				}
			}
			
			$html .= '<a class="more-link" href="'.base_url('topscore').'" target="_blank">ดาวซัลโวทั้งหมด</a></div>
		</div>';
		return $html;
	}

	public function block_column()
	{

		$html = '<section class="warpper bg-white column-home"><h5><a href="#">คอลัมน์</a></h5></section>';

		return $html;
	}

	public function block_wallpaper()
	{

		$html = '<section class="warpper bg-blue wallpaper-home">
			
	  	</section>';

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
