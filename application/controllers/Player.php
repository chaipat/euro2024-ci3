<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Player extends CI_Controller {
	protected $season;
	protected $season_id;
	protected $tournament_id;
	protected $tournament;
	protected $date_start;
	protected $datetime_start;
	protected $team_path;
	protected $stadium_path;
	protected $base_path;
	protected $profile_path;
	protected $_page = 'ยสฟัำพ';
	protected $_cache;

    public function __construct()    {
		parent::__construct();		

		// $this->load->library('session');
		// $this->load->library('genarate');
		$this->load->model('tournament_model');
		$this->load->model('season_model');
		$this->load->model('standing_model');
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
		$postdata = null;
		$round = 0;
		$html = '';
		$season_id = $this->season;

		$cur_date = date('Y-m-d H:i:s');
		$datenow = date('Y-m-d');
		$sel_date = $datenow;

        // $obj_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id);
        $tournament_id = $this->tournament_id;
		// $tournament_list = $this->tournament_model->get_data($tournament_id);
		$season_id = $this->season;

		$thisweek = $this->standing_model->get_max_week($tournament_id, '_xml_standing', $season_id);
		// echo $this->db->last_query();
		// Debug($thisweek);		
		$week = ($this->input->get('week')) ? $this->input->get('week') : $thisweek;

		$obj_list = $this->standing_model->get_xml_data(intval($tournament_id), 0, $round);
        // echo $this->db->last_query();
		Debug($obj_list);
		die();
		
		if($datenow < $this->date_start){

			$sel_date = $this->date_start;
			$sel_date2 = date('Y-m-d', strtotime($this->date_start.' +1 day'));

			$datebetween[] = $sel_date;
			$datebetween[] = $sel_date2.' 23:59:59';

			$date_result[] = $sel_date;
			$date_result[] = $sel_date;
		}else{

			$date_result = date('Y-m-d', strtotime($datenow.' -1 day'));
			$date2 = date('Y-m-d', strtotime($datenow.' +1 day'));

			$datebetween[] = $datenow;
			$datebetween[] = $date2.' 23:59:59';

			$date_result[] = $date_result;
			$date_result[] = $date_result.' 23:59:59';
		}


		// echo "($datenow < ".$this->date_start.")";
		// Debug($datebetween);
		// echo "<hr>";
		// die();

		//**** Program *******
		$fixtures_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id, $datebetween);
		// $fixtures_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id);

        // echo $this->db->last_query();
		// Debug($fixtures_list);
		// die();

		//**** Result *******
		$result_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id, $date_result);

      	// echo $this->db->last_query();
		// Debug($result_list);
		// die();

		if(isset($obj_list[0]->tournament_name)){
			$tournament_name = $obj_list[0]->tournament_name;
		}

		$html = $this->display_standing($obj_list);

		$widgets_program = $this->widgets_program($fixtures_list);
		$widgets_result = $this->widgets_result($result_list);
		
		$breadcrumb[] = 'ตารางคะแนน';

		$webtitle = 'ตารางคะแนน'.$tournament_name;
		$page_published_time = date('c' , strtotime('2022-10-27'));
		$page_lastupdated_date = date('c');
		// $keywords = explode(',', _KEYWORD);
		$social_block = $this->social_block($webtitle);

		$keywords[] = 'ตารางคะแนนบอลยูโร 2024';
		$keywords[] = 'ตารางคะแนน';
		$keywords[] = 'ตารางฟุตบอลยูโร';
		
		$meta = array(
			'title' => $webtitle,
			'description' => _DESCRIPTION,
			'keywords' => $keywords,
			'page_image' => _COVER_WC2022,
			"page_published_time" => $page_published_time,
			"Author" => "",
			"Copyright" => ""
		);

		$asset_css[] = 'jquery.fancybox.css';
		$asset_js[] = 'jquery.fancybox.js';

		$data = array(
			"meta" => $meta,
            "webtitle" => $webtitle,
            "breadcrumb" => $breadcrumb,
			"menu" => $display_menu,
			"head" => 'ตารางคะแนนบอลยูโร',
			"html" => $html,
			"social_block" => $social_block,
			"widgets_program" => $widgets_program,
			"widgets_result" => $widgets_result,
			"css" => $asset_css,
			"js" => $asset_js,
			"page_lastupdated_date" => $page_lastupdated_date,
			"breadcrumb" => $breadcrumb,
			"content_view" => 'standing/view'
		);
		//$this->parser->parse('template',$data);
        $this->load->view('template-euro',$data);
	}

	public function html()
	{
		$this->load->view('html/player');
	}

	public function profile($profile_id, $team_id = 0)
	{
		$this->load->model('team_model');

		$breadcrumb = array();
		$display_menu = $html = $wc_date_th = $show_team_logo = $page_image = $display_standing = $display_program = '';
		$round = $relate_team = null;

		$season = $this->season;
		$tournament_id = $this->tournament_id;
		$tournament_name = $this->tournament;
		//$user_agent = $this->input->user_agent;

		$cur_date = date('Y-m-d H:i:s');
		$datenow = date('Y-m-d');
		// $sel_date = $datenow;

		$section = 'profile';
		$cache_key = 'page_'.$this->_page.'_'.$section.'-'.$profile_id;
		$cache = $this->utils->getCache($cache_key);
		
		if($cache && $this->_cache){

			if($this->input->get('oncheck') == 1){
				echo "Cache On ".$cache_key."<hr>";
			}
			echo $cache.'<!-- Cache -->';
		}else{

			$tournament_id = $this->tournament_id;
			$tournament_list = $this->tournament_model->get_data($tournament_id);
			$season_id = $this->season;
			// Debug($tournament_list);

			// $thisweek = $this->standing_model->get_max_week($tournament_id, '_xml_standing', $season_id);	
			// $week = ($this->input->get('week')) ? $this->input->get('week') : $thisweek;
			// $obj_list = $this->standing_model->get_xml_data(intval($tournament_id), 0, $round);
			// Debug($obj_list);

			$get_player = $this->team_model->team_player($team_id, $profile_id);
			// Debug($this->db->last_query());
			// Debug($get_player);
			// die();
			if(empty($get_player)){

				redirect('/');
				die();
			}

			$player_name = ($get_player[0]->player_name_th != '') ? $get_player[0]->player_name_th: $get_player[0]->player_name;
			$team_name = $get_player[0]->team_name;
			$team_name_en = $get_player[0]->team_name_en;

			$tournament_name = ($tournament_list[0]->tournament_name != '') ? $tournament_list[0]->tournament_name: $tournament_list[0]->tournament_name_en;
			$tournament_name_en = $tournament_list[0]->tournament_name_en;
			$short_name = $tournament_list[0]->short_name;
			$season = $tournament_list[0]->season;

			$create_date = isset($get_player[0]->create_date) ? $get_player[0]->create_date : '-';
			$lastupdate_date = ($get_player[0]->lastupdate_date != '') ? $get_player[0]->lastupdate_date: $cur_date;

			// $display_standing = $this->display_standing($standing_list);
			// $display_program = $this->display_program($fixtures_list);
			$display_player = $this->display_profile($get_player);

			// die();
			$relate[] = $team_id;

			if($relate){
				foreach($relate as $val){
					$relate_team .= $this->relate_team($val);
				}
			}else
				$relate_team .= $this->relate_team();

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
			// $keywords[] = $manager_name;
			// $keywords[] = $tournament_name;
			// $keywords[] = $tournament_name_en;
			// $keywords[] = $short_name;
			// $keywords[] = $season;
			
			$webtitle = $player_name.' ทีมชาติ '.$team_name;
			$page_published_time = date('c' , strtotime($create_date));
			$page_lastupdated_date = date('c', strtotime($lastupdate_date));
			// $keywords = explode(',', $keyword);

			// $html = $this->news_detail($news_obj->data);
			$view_tags = $this->view_tags($keywords);
			// $script_fancybox = $this->script_fancybox();
			$social_block = $this->social_block($webtitle);

			$meta = array(
				'title' => URLTitle($webtitle),
				'description' => $player_name.' ทีมชาติ'.$team_name.' '.$team_name_en.' '.$tournament_name.' '.$tournament_name_en,
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
				"relate_team" => $relate_team,
				"social_block" => $social_block,
				"css" => $css,
				"js" => $js,
				"page_lastupdated_date" => $page_lastupdated_date,
				"content_view" => 'player/detail'
			);
			// $this->load->view('template-euro', $data);
			$html = $this->load->view('template-euro', $data, true);

			//cache to redis
			$this->utils->setCacheRedis($cache_key, $html);
			echo $html;
			$this->db->close();
		}
		
	}

	function display_profile($get_player){
		$html = $tmp = $manager_img = $manger_detail = $auto_get = '';
		$delay = 500;
		$update_date = 0;

		$profile_id = $get_player[0]->profile_id;
		$team_id = $this->uri->segment(4);

		if($this->input->get('debug') == 1){
			$update_date = 1;
		}
		// Debug($get_player);

		$profile_id = $get_player[0]->profile_id;
		// $player_name = ($get_player[0]->player_name_th != '') ? $get_player[0]->player_name_th: $get_player[0]->player_name;
		$player_name = $get_player[0]->player_name;
		$player_name_th = $get_player[0]->player_name_th;

		$team_name = $get_player[0]->team_name;
		$team_name_en = $get_player[0]->team_name_en;

		$player_position = $get_player[0]->player_position;
		$birthdate = $get_player[0]->birthdate;
		$birthcountry = $get_player[0]->birthcountry;
		$birthplace = $get_player[0]->birthplace;
		$height = $get_player[0]->height;
		$weight = $get_player[0]->weight;
		$age = $get_player[0]->age;
		$current_team = $get_player[0]->current_team;

		$number = $get_player[0]->number;
		$position = $get_player[0]->position;
		$injured = $get_player[0]->injured;
		$minutes = $get_player[0]->minutes;
		$appearences = $get_player[0]->appearences;
		$lineups = $get_player[0]->lineups;
		$substitute_in = $get_player[0]->substitute_in;
		$substitute_out = $get_player[0]->substitute_out;
		$substitutes_on_bench = $get_player[0]->substitutes_on_bench;
		$goals = $get_player[0]->goals;
		$assists = $get_player[0]->assists;
		$yellowcards = $get_player[0]->yellowcards;
		$yellowred = $get_player[0]->yellowred;
		$redcards = $get_player[0]->redcards;

		// $minutes = $appearences = $lineups = $substitute_in = $goals = $assists = $yellowcards = $redcards = 0;

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

		if(strtoupper($position) == 'G'){
			$head_position = 'ผู้รักษาประตู';
		}else if(strtoupper($position) == 'D'){
			$head_position = 'กองหลัง';
		}else if(strtoupper($position) == 'M'){
			$head_position = 'กองกลาง';
		}else if(strtoupper($position) == 'A'){
			$head_position = 'กองหน้า';
		}

		$birthdate_th = DateTH($birthdate);

		// $img_cover_news = '<figure><img src="'.$img_url.'" alt="'.$title.'" title="'.$title.'" /></figure>';
		$link_profile = base_url('player/profile/'.$profile_id.'/'.$team_id);

		$show_height = ($height) ? str_replace('cm', 'ซม.', $height) : 'ซม.';
		$show_weight = ($weight) ? str_replace('kg', 'กก.', $weight) : 'กก.';
		$html .= '<div>
			'.$player_img.'
			<h1><span>'.$player_name_th.'</span>'.$player_name.'</h1>
		</div>
		
		<h2>ข้อมูลทั่วไป</h2>
		<ul>
			<li><strong>ตำแหน่ง</strong><span>: '.$head_position.'</span></li>
			<li><strong>ทีมชาติ</strong><span>: '.$team_name.'</span></li>
			<li><strong>วันเกิด</strong><span>: '.$birthdate_th.'</span></li>
			<li><strong>อายุ</strong><span>: '.$age.' ปี</span></li>
			<li><strong>ส่วนสูง</strong><span>: '.$show_height.'</span></li>
			<li><strong>น้ำหนัก</strong><span>: '.$show_weight.'</span></li>
		</ul>
		
		<h2>สถิติในฟุตบอล 2022</h2>
		<ul>
			<li><strong>ลง</strong><span>: '.$minutes.' นาที</span></li>
			<li><strong>ลงเล่น</strong><span>: '.$appearences.' ครั้ง</span></li>
			<li><strong>ตัวจริง</strong><span>: '.$lineups.' ครั้ง</span></li>
			<li><strong>ตัวสำรอง</strong><span>: '.$substitute_in.' ครั้ง</span></li>
			<li><strong>ใบเหลือง</strong><span>: '.$yellowcards.' ใบ</span></li>
			<li><strong>ใบแดง</strong><span>: '.$redcards.' ใบ</span></li>
			<li><strong>ยิงประตู</strong><span>: '.$goals.' ประตู</span></li>
			<li><strong>แอสซิส</strong><span>: '.$assists.' ครั้ง</span></li>
		</ul>
		</div>';

	  return $html;
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
	
					$logo_team = $this->load_base64img($img_logo, 25, 15, $team_name);
				}

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
					<td><a href="#">'.$logo_team.'</a></td>
					<td><a href="#">'.$team_name.'</a></td>
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
		  </table>
		  <a href="#">โปรแกรม ผลบอล '.$tmp.'</a>';
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

		$tmp = $html = '';
		// Debug($fixtures_list);
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

			// $logo_team1 = '<img src="./assets/images/team-'.$hteam_id.'.png" alt="'.$home_team.'"/>';
			// $logo_team2 = '<img src="./assets/images/team-'.$ateam_id.'.png" alt="'.$away_team.'"/>';
			// $logo_team1 = $logo_team2 = '';

			$html .= '<div>
				<span>
				<a href="#">'.$home_team.' '.$logo_team1.'</a> <strong><a href="#">VS</a></strong> 
				<a href="#">'.$logo_team2.' '.$away_team.'</a></span> <a href="#">'.$match_datetime_th.'</a> 
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

			// $logo_team1 = '<img src="./assets/images/team-'.$hteam_id.'.png" alt="'.$home_team.'"/>';
			// $logo_team2 = '<img src="./assets/images/team-'.$ateam_id.'.png" alt="'.$away_team.'"/>';
			// $logo_team1 = $logo_team2 = '';

			/*$html .= '<div>
				<span>
				<a href="#">'.$home_team.' '.$logo_team1.'</a> <strong><a href="#">VS</a></strong> 
				<a href="#">'.$logo_team2.' '.$away_team.'</a></span> <a href="#">'.$match_datetime_th.'</a> 
			</div>';*/

			$html .= '<div> 
				<span><a href="#" target="_blank">'.$home_team.' '.$logo_team1.'</a> 
				<strong><a href="#" target="_blank">'.$hgoals.' : '.$agoals.'</a></strong> 
				<a href="#" target="_blank">'.$logo_team2.' '.$away_team.'</a></span>
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

	public function relate_team($team_id = 8981){
		$html = $logo_team = '';

		$res = $this->team_model->get_data(0, $team_id);

		$team_name = ($res[0]->team_name != '') ? $res[0]->team_name : $res[0]->team_name_en;

		if(file_exists($this->base_path.$this->team_path.$team_id.'.txt')) {

			$file = fopen($this->base_path.$this->team_path.$team_id.'.txt', 'r');
			while(! feof($file)) {
				$img_logo = fgets($file);
			}
			fclose($file);
			$logo_team = $this->widgets_load_img($img_logo, 25, 15, 'ทีมชาติ'.$team_name);
		}

		$link_team = base_url('team/detail/'.$team_id);

		$html = '<div class="teammini"><a href="'.$link_team.'" target="_blank">'.$logo_team.'<strong> ทีมชาติ'.$team_name.'</strong></a></div>';
		// echo $html;
		return $html;
	}

	public function load_base64img_profile($src, $width = 100, $height = 0, $title = ''){
		//width:100px;height:100px;
		$output = "<figure><img class='base64image round_profile100' width='".$width."' alt='".$title."' title='".$title."' src='data:image/jpeg;base64, $src' /></figure>";
      	return $output;
	}

	public function load_base64img($src, $width = 25, $height = 15, $title = ''){

		$output = "<img class='base64image' width='".$width."' height='".$height."' alt='".$title."' title='".$title."' src='data:image/jpeg;base64, $src' />";
      	return $output;
	}

	public function widgets_load_img($src, $width = 100, $height = 0, $title = ''){
		//width:100px;height:100px;
		$output = "<img class='base64image' width='".$width."' height='".$height."' alt='".$title."' title='".$title."' src='data:image/jpeg;base64, $src' />";
      	return $output;
	}
}