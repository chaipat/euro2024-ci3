<?php
class Analyze extends CI_Controller {
	protected $season_id;
	protected $season;
	protected $tournament_id;
	protected $tournament;
	protected $date_start;
	protected $datetime_start;
	protected $team_path;
	protected $stadium_path;
	protected $base_path;
	protected $_page = 'analyze';
	protected $_cache;

    public function __construct()    {
		parent::__construct();		

		// $this->load->library('session');
		// $this->load->library('genarate');
		$this->load->model('tournament_model');
		// $this->load->model('season_model');
		$this->load->model('program_stat_model');
		$this->load->model('program_analy_model');
		$this->load->model('fixtures_model');
		$this->load->library('utils');
		$this->load->helper('html');
		$this->load->helper('form');
		$this->load->helper('common');
		
		$this->season_id = '2024';

		$this->tournament_id = $this->config->config['tournament_id'];
		$this->tournament = $this->config->config['tournament'];
        $this->season = $this->config->config['season'];
 		$this->date_start = $this->config->config['date_start'];
		$this->datetime_start = $this->config->config['datetime_start'];

        $this->team_path = 'data/uploads/teamlogo/';
		$this->stadium_path = 'data/uploads/stadium/';
		$this->base_path = str_replace('application/controllers', '', __DIR__);

		$this->_cache = false;
    }

	public function index(){
		$obj_list = $data_list = $heading = $datebetween = $date_result = $asset_css = $asset_js = array();
		$button_stat = $lineup = $bet = $import_statistics = $datekick = $price = $display_menu = $tournament_name = '';
		$display_topscore = $display_topassist = '';
		$postdata = null;
		$round = 0;
		$html = '';
		$season_id = $this->season;

		$cur_date = date('Y-m-d H:i:s');
		$datenow = date('Y-m-d');
		$sel_date = $datenow;

		$section = 'index';
		$cache_key_all = 'page_'.$this->_page.'_'.$section;
		$cache = null;

		// echo $cache_key_all."<hr>";
		// echo "(".$cache." && ".$this->_cache.")";

		if($cache && $this->_cache){

			if($this->input->get('oncheck') == 1){
				echo "Cache On ".$cache_key_all."<hr>";
			}
			echo $cache.'<!-- Cache -->';
		}else{

			// $datebetween[] = $datenow.' 00:00:00';
			// $datenext = date("Y-m-d", strtotime("+1 day"));
			// $datebetween[] = $datenext.' 23:59:59';
			
			if($datenow < $this->date_start){

				$sel_date = $this->date_start;
				$sel_date2 = date('Y-m-d', strtotime($this->date_start.' +1 day'));

				$datebetween[] = $sel_date;
				$datebetween[] = $sel_date2.' 23:59:59';

				$date_result[] = $sel_date;
				$date_result[] = $sel_date;
			}else{

				$date_prev = date('Y-m-d', strtotime($datenow.' -1 day'));
				$date2 = date('Y-m-d', strtotime($datenow.' +1 day'));

				$datebetween[] = $datenow;
				$datebetween[] = $date2.' 23:59:59';

				$date_result[] = $date_prev;
				$date_result[] = $date_prev.' 23:59:59';
			}
			// Debug($date_result);
			// die();

			$show_date_th = DateTH($sel_date);

			$obj_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id, $datebetween);
			// $obj_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id);
			// echo $this->db->last_query();
			// Debug($obj_list);
			// die();

			// echo "($datenow < ".$this->date_start.")";
			// Debug($datebetween);
			// echo "<hr>";
			// die();

			//**** Program *******
			// $fixtures_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id, $datebetween);
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

			$display_analyze = $this->display_analyze($obj_list);

			// $widgets_program = $this->widgets_program($fixtures_list);
			$widgets_result = $this->widgets_result($result_list);
			
			$breadcrumb[] = 'วิเคราะห์ฟุตบอลโลก';

			$webtitle = 'วิเคราะห์บอลวันนี้ วิเคราะห์ฟุตบอลโลก';
			$page_published_time = date('c' , strtotime('2022-10-27'));
			$page_lastupdated_date = date('c');
			// $keywords = explode(',', _KEYWORD);
			$social_block = $this->social_block($webtitle);

			$keywords[] = 'วิเคราะห์ฟุตบอลโลก 2022';
			$keywords[] = 'วิเคราะห์ฟุตบอลโลก';
			$keywords[] = 'วิเคราะห์บอลโลก';
			$keywords[] = 'วิเคราะห์บอลวันนี้';
			
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
			$asset_css[] = 'analy.css?v=2';
			$asset_js[] = 'jquery.fancybox.js';

			$data = array(
				"meta" => $meta,
				"webtitle" => $webtitle,
				"breadcrumb" => $breadcrumb,
				"menu" => $display_menu,
				"head" => 'วิเคราะห์ฟุตบอลโลก 2022',
				"show_date_th" => $show_date_th,
				"display_analyze" => $display_analyze,
				"social_block" => $social_block,
				"widgets_result" => $widgets_result,
				"script_topscore" => 'on',
				"css" => $asset_css,
				"js" => $asset_js,
				"page_lastupdated_date" => $page_lastupdated_date,
				"breadcrumb" => $breadcrumb,
				"content_view" => 'analyze/list'
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
		// $this->load->view('html/consider');
		$this->load->view('html/consider-detail');
	}

	public function display_analyze($obj_list)
	{
		$html = $tmp = '';
		$datenow = date('Y-m-d');
		if ($obj_list){

			$allitem = count($obj_list);
		
			for ($i = 0; $i < $allitem; $i++) {

				$rows = $obj_list[$i];
				// Debug($rows);
				// die();

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

				// $show_date = date('Y-m-d H:i:s', strtotime($kickoff.' +7 hour'));
				// list($wc_date, $wc_time) = explode(' ', $show_date);
				
				// $match_time = date('H:i', strtotime($kickoff));
				$match_time = date('H:i', strtotime($kickoff.' +7 hour'));
				// list($match_date, $match_time) = explode(' ', $kickoff);

				$show_date_th = DateTH($sel_date);

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

				$view_analy = base_url('analyze/view/'.$program_id.'/'.$fix_id);
				$link_teamhome = base_url('team/detail/'.$hometeam_id);
				$link_teamaway = base_url('team/detail/'.$awayteam_id);

				//<a href="'.$link_teamhome.'" target="_blank" >'.$hometeam_title.' '.$logo_team1.'</a>
				//<a href="'.$link_teamaway.'" target="_blank" >'.$logo_team2.' '.$awayteam_title.'</a>

				$html .= '<div class="match-list '.$class_endmatch.'">
					<div>
					<h2><a href="'.$view_analy.'" target="_blank">วิเคราะห์ฟุตบอลโลก '.$hometeam_title.' กับ '.$awayteam_title.'</a></h2>
					<span>
						
						<strong>เวลา <a href="'.$view_analy.'" target="_blank">'.$match_time.'</a></strong>
						
					</span>
					<span>'.$group_name.'</span>
					<span>สนาม '.$stadium_name.'</span>
					<a href="'.$view_analy.'" target="_blank">วิเคราะห์บอล</a>
					</div>
				</div>';
			}

			$html .= '';
		}

		return $html;
	}

	public function view($program_id, $fixture_id = 0){
		$this->load->model('match_model');
		$this->load->model('team_model');
		$this->load->model('standing_model');

		$group_id = 0;
		$relate_team = $standing_list = null;
		$display_standing = '';
		$season = $this->season;
		$tournament_id = $this->tournament_id;
		$tournament_name = $this->tournament;

		$section = 'view';
		$cache_key = 'page_'.$this->_page.'_'.$section.'-match-'.$program_id;
		$cache = $this->utils->getCache($cache_key);

		$hour = date('H');

		//Cache หลังจาก 17:00 น.
		if($hour < 17){
			$this->_cache = false;
		}
		
		if($cache && $this->_cache){

			if($this->input->get('oncheck') == 1){
				echo "Cache On ".$cache_key."<hr>";
			}
			echo $cache.'<!-- Cache -->';
		}else{

			// $tournament_list = $this->tournament_model->get_data($tournament_id);
			$fixtures_list = $this->fixtures_model->get_data($program_id);
			$league_id = $fixtures_list[0]->league_id;
			$hometeam_id = $fixtures_list[0]->hometeam_id;
			$awayteam_id = $fixtures_list[0]->awayteam_id;
			// Debug($fixtures_list);
			// die();

			$res = $this->standing_model->get_xml_data(intval($tournament_id), intval($fixtures_list[0]->hometeam_id));
			if(isset($res[0]->group_id))
				$group_id = intval($res[0]->group_id);

			if($group_id > 0)
				$standing_list = $this->standing_model->get_group(intval($tournament_id), intval($group_id));

			// Debug($this->db->last_query());
			// Debug($standing_list);
			// die();

			$program_analy_list = $this->program_analy_model->get_data($program_id);
			// Debug($this->db->last_query());
			// Debug($program_analy_list);
			// $program_stat_list = $this->program_stat_model->get_data($program_id);
			// Debug($this->db->last_query());

			$match_h2h = $this->match_model->geth2h($hometeam_id, $awayteam_id, $league_id);
			// Debug($match_h2h);

			// Debug($tournament_list);
			// Debug($fixtures_list);
			// Debug($program_analy_list);
			// Debug($program_stat_list);
			// die();

			$display_analyze = $this->display_head_analyze($fixtures_list[0]);
			$stat = $this->display_stat($fixtures_list[0], $match_h2h);
			$considetail = $this->display_analyze_program_id($fixtures_list[0], $program_analy_list);
			
			if($standing_list)
				$display_standing = $this->display_standing($standing_list);

			$hometeam_title = ($fixtures_list[0]->hometeam_title_th != '') ? $fixtures_list[0]->hometeam_title_th : $fixtures_list[0]->hometeam_title;
			$awayteam_title = ($fixtures_list[0]->awayteam_title_th != '') ? $fixtures_list[0]->awayteam_title_th : $fixtures_list[0]->awayteam_title;

			$webtitle = 'วิเคราะห์ฟุตบอลโลก '.$hometeam_title.' กับ '.$awayteam_title;
			$page_published_time = date('c' , strtotime($fixtures_list[0]->create_date));
			$page_lastupdated_date = date('c', strtotime($fixtures_list[0]->lastupdate_date));
			
			$keywords[] = 'วิเคราะห์ฟุตบอลโลก';
			$keywords[] = 'วิเคราะห์ฟุตบอลโลก 2022';
			$keywords[] = $hometeam_title;
			$keywords[] = $awayteam_title;

			//************ Relate Team **************/
			$get_teamlist = $this->team_model->get_data($tournament_id);
			// Debug($keywords);
			// Debug($get_teamlist);
			$all_team = count($get_teamlist);
			$chk_relate_team = 0;
			if($keywords)
				foreach($keywords as $val){
					for($i=0;$i<$all_team;$i++){

						if(trim($val) == trim($get_teamlist[$i]->team_name)){
							$chk_relate_team = 1;
							$relate[] = $get_teamlist[$i]->team_id;
						}
					}
				}

			if(isset($relate)){
				foreach($relate as $val){
					$relate_team .= $this->relate_team($val);
				}
			}else
				$relate_team .= $this->relate_team();
			
			// echo $relate_team;
			// die();
			//************ Relate Team **************/

			$view_tags = $this->view_tags($keywords);
			// $script_fancybox = $this->script_fancybox();
			$social_block = $this->social_block($webtitle);

			$page_image = _COVER_WC2022;

			$program_cover = './assets/images/match/analy-'.$program_id.'.webp';
			if(file_exists($program_cover)){
				
				$page_image = base_url($program_cover);
			}else{

				$program_cover = './assets/images/match/'.$program_id.'.webp';
				if(file_exists($program_cover)){
					$page_image = base_url($program_cover);
				}				
			}


			$meta = array(
				'title' => URLTitle($webtitle),
				'description' => StripTxt($webtitle),
				'keywords' => $keywords,
				'page_image' => $page_image,
				"page_published_time" => $page_published_time,
				"Author" => "Ballnaja",
				"Copyright" => "Ballnaja"
			);

			$css[] = 'jquery.fancybox.css';
			$css[] = 'analy.css';
			// $css[] = 'jquery.fancybox-thumbs.css';
			$js[] = 'jquery.fancybox.js';
			// $js[] = 'jquery.fancybox-thumbs.js';

			$data = array(
				"meta" => $meta,
				"webtitle" => URLTitle($webtitle),
				"title" => StripTxt($webtitle),
				"display_analyze" => $display_analyze,
				"display_standing" => $display_standing,
				"considetail" => $considetail,
				"stat" => $stat,
				"relate_team" => $relate_team,
				"view_tags" => $view_tags,
				// "script_fancybox" => $script_fancybox,
				"social_block" => $social_block,
				"css" => $css,
				"js" => $js,
				"page_lastupdated_date" => $page_lastupdated_date,
				"content_view" => 'analyze/detail'
			);
			// $this->load->view('template-euro', $data);
			$html = $this->load->view('template-euro', $data, true);

			//cache to redis
			$this->utils->setCacheRedis($cache_key, $html);
			echo $html;
			$this->db->close();
		}
	}

	//แสดงข้อมุลวิเคราะห์ฟุตบอลโลก
	public function display_head_analyze($fixtures_list)
	{
		$html = $tmp = '';
		$datenow = date('Y-m-d');

		$program_id = $fixtures_list->program_id;
		$fix_id = $fixtures_list->fix_id;

		$hometeam_title = ($fixtures_list->hometeam_title_th != '') ? $fixtures_list->hometeam_title_th : $fixtures_list->hometeam_title;
		$awayteam_title = ($fixtures_list->awayteam_title_th != '') ? $fixtures_list->awayteam_title_th : $fixtures_list->awayteam_title;

		$hometeam_id = $fixtures_list->hometeam_id;
		$awayteam_id = $fixtures_list->awayteam_id;
		$stadium_id = $fixtures_list->stadium_id;

		$tournament_name = $fixtures_list->tournament_name;
		$tournament_name_en = $fixtures_list->tournament_name_en;
		$file_group = $fixtures_list->file_group;
		$season_name = $fixtures_list->season_name;

		$sel_date = $fixtures_list->sel_date;
		$kickoff = $fixtures_list->kickoff;
		$week = $fixtures_list->week;
		$program_status = $fixtures_list->program_status;
		$stadium_name = ($fixtures_list->stadium_name_th != '') ? $fixtures_list->stadium_name_th : $fixtures_list->stadium_name;

		$channel_name = $fixtures_list->channel_name;

		$group_name = (isset($fixtures_list->group_name)) ? str_replace('Group', 'กลุ่ม', $fixtures_list->group_name) : '';
		$group_id = $fixtures_list->group_id;
		
		$hometeam_point = $fixtures_list->hometeam_point;
		$hometeam_formation = $fixtures_list->hometeam_formation;

		$awayteam_point = $fixtures_list->awayteam_point;
		$awayteam_formation = $fixtures_list->awayteam_formation;

		// $show_date = date('Y-m-d H:i:s', strtotime($kickoff.' +7 hour'));
		// list($wc_date, $wc_time) = explode(' ', $show_date);
		
		// $match_time = date('H:i', strtotime($kickoff));
		$match_time = date('H:i', strtotime($kickoff.' +7 hour'));
		// list($match_date, $match_time) = explode(' ', $kickoff);

		$show_date_th = DateTH($sel_date);

		$logo_team1 = $logo_team2 = $time_score = '';

		if(file_exists($this->base_path.$this->team_path.$hometeam_id.'.txt')) {
			$file = fopen($this->base_path.$this->team_path.$hometeam_id.'.txt', 'r');
			while(! feof($file)) {
				$img_logo = fgets($file);
			}
			fclose($file);
			$logo_team1 = $this->load_base64img($img_logo, 25, 20, $hometeam_title);
		}

		if(file_exists($this->base_path.$this->team_path.$awayteam_id.'.txt')) {
			$file = fopen($this->base_path.$this->team_path.$awayteam_id.'.txt', 'r');
			while(! feof($file)) {
				$img_logo = fgets($file);
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
		}else
			$time_score = $hometeam_point.'-'.$awayteam_point;

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

		$team1 = base_url('team/detail/'.$hometeam_id);
		$team2 = base_url('team/detail/'.$awayteam_id);

		$view_analy = base_url('analyze/view/'.$program_id.'/'.$fix_id);
		$link_matchdetail = base_url('program/detail/'.$program_id.'/'.$fix_id);

		$html = '<div class="match-list consimatch">
			<div>
			<span>'.$show_date_th.'</span>
			<span>
				<a href="'.$team1.'" target="_blank">'.$hometeam_title.' '.$logo_team1.'</a>
				<strong>
				<a href="'.$link_matchdetail.'" target="_blank">'.$match_time.'</a>
				</strong>
				<a href="'.$team2.'" target="_blank">'.$logo_team2.' '.$awayteam_title.'</a>
			</span>
			<span>'.$group_name.'</span>
			<span>ช่องถ่ายทอดสดฟุตบอลโลก พร้อมลิ้งถ่ายทอดสด</span>
			'.$channel.'
			<span>สนาม '.$stadium_name.'</span>
			<a target="_blank" href="'.$link_matchdetail.'">ผลบอลสด</a>
			</div>
		</div>';

		return $html;
	}

	public function display_stat($fixtures_list, $program_stat_list){
		$html = $display_top50 = $display_overall = $display_leagues = $display_biggest_victory = $display_last10_team1 = $display_last10_team2 = '';

		$program_id = $fixtures_list->program_id;
		$fix_id = $fixtures_list->fix_id;

		$hometeam_title = ($fixtures_list->hometeam_title_th != '') ? $fixtures_list->hometeam_title_th : $fixtures_list->hometeam_title;
		$awayteam_title = ($fixtures_list->awayteam_title_th != '') ? $fixtures_list->awayteam_title_th : $fixtures_list->awayteam_title;

		$hometeam_id = $fixtures_list->hometeam_id;
		$awayteam_id = $fixtures_list->awayteam_id;

		if(isset($program_stat_list[0]->json)){

			// Debug($program_analy_list);
			// Debug($program_stat_list);
			$data_h2h = unserialize($program_stat_list[0]->json);
			// Debug($data_h2h);
			// die();

			$match_top50 = (isset($data_h2h['top50'])) ? $data_h2h['top50'] : null;
			$match_overall = (isset($data_h2h['overall'])) ? $data_h2h['overall'] : null;
			$match_leagues = (isset($data_h2h['leagues'])) ? $data_h2h['leagues'] : null;
			$match_goals = (isset($data_h2h['goals'])) ? $data_h2h['goals']: null;
			$match_biggest_victory = (isset($data_h2h['biggest_victory'])) ? $data_h2h['biggest_victory']: null;
			$match_biggest_defeat = (isset($data_h2h['biggest_defeat'])) ? $data_h2h['biggest_defeat']: null;

			$match_last5_home_team1 = $data_h2h['last5_home']['team1'];
			$match_last5_home_team2 = $data_h2h['last5_home']['team2'];
			$match_last5_away_team1 = $data_h2h['last5_away']['team1'];
			$match_last5_away_team2 = $data_h2h['last5_away']['team2'];

			//*********** Top 50 ***********
			// Debug($match_top50);
			
			if(isset($match_top50)){

				$count_match = (count($match_top50) > 10) ? 10 : count($match_top50);
				for($j=0;$j<$count_match;$j++){

					$rows_match = $match_top50[$j];

					$category = $rows_match['category'];
					$league = $this->translet_thaileague($rows_match['league']);
					$league_id = $rows_match['league_id'];
					$team1 = $rows_match['team1'];
					$id1 = $rows_match['id1'];
					$team2 = $rows_match['team2'];
					$id2 = $rows_match['id2'];
					$date = $rows_match['date'];
					$team1_score = $rows_match['team1_score'];
					$team2_score = $rows_match['team2_score'];
					$static_id = $rows_match['static_id'];

					if($id1 == $hometeam_id){

						$team1 = $hometeam_title;
						$team2 = $awayteam_title;
					}else{

						$team1 = $awayteam_title;
						$team2 = $hometeam_title;
					}

					//<div class="col-xs-2">'.$league.'</div>
					$display_top50 .= '<div class="row ">
						<div class="col-xs-3">'.$date.'</div>
						<div class="col-xs-9">('.$league.')</div>
						<div class="col-xs-12">
							<div class="row">
								<div class="col-xs-4">'.$team1.'</div>
								<div class="col-xs-3">'.$team1_score.'-'.$team2_score.'</div>
								<div class="col-xs-4">'.$team2.'</div>
							</div>
						</div>
						
					</div>';

				}
			}

			//*********** Overall ***********
			// Debug($match_overall);
			
			$display_overall .= '<div class="row margintop10">
					<div class="row border">
						<div class="col-xs-3 ">ทั้งหมด</div>
						<div class="col-xs-9 ">
							<div class="row ">
								<div class="col-xs-6">แมตย์</div><div class="col-xs-6">'.$match_overall['total']['games'].'</div>
							</div>
							<div class="row ">
								<div class="col-xs-6">'.$hometeam_title.' ชนะ</div><div class="col-xs-6">'.$match_overall['total']['team1_won'].'</div>
							</div>
							<div class="row ">
								<div class="col-xs-6">'.$awayteam_title.' ชนะ</div><div class="col-xs-6">'.$match_overall['total']['team2_won'].'</div>
							</div>
							<div class="row ">
								<div class="col-xs-6">เสมอ</div><div class="col-xs-6">'.$match_overall['total']['draws'].'</div>
							</div>
						</div>
					</div>

					<div class="row border">
						<div class="col-xs-3 ">เจ้าบ้าน</div>
						<div class="col-xs-9 ">
							<div class="row ">
								<div class="col-xs-6">'.$hometeam_title.'</div>
								<div class="col-xs-6">
									<div class="row ">
										<div class="col-xs-6">แมตย์</div>
										<div class="col-xs-6">'.$match_overall['home']['team1']['games'].'</div>
									</div>
									<div class="row ">
										<div class="col-xs-6">ชนะ</div>
										<div class="col-xs-6">'.$match_overall['home']['team1']['won'].'</div>
									</div>
									<div class="row ">
										<div class="col-xs-6">แพ้</div>
										<div class="col-xs-6">'.$match_overall['home']['team1']['lost'].'</div>
									</div>
									<div class="row ">
										<div class="col-xs-6">เสมอ</div>
										<div class="col-xs-6">'.$match_overall['home']['team1']['draws'].'</div>
									</div>
								</div>
							</div>
							<div class="row ">
								<div class="col-xs-6">'.$awayteam_title.'</div>
								<div class="col-xs-6">
									<div class="row ">
										<div class="col-xs-6">แมตย์</div>
										<div class="col-xs-6">'.$match_overall['home']['team2']['games'].'</div>
									</div>
									<div class="row ">
										<div class="col-xs-6">ชนะ</div>
										<div class="col-xs-6">'.$match_overall['home']['team2']['won'].'</div>
									</div>
									<div class="row ">
										<div class="col-xs-6">แพ้</div>
										<div class="col-xs-6">'.$match_overall['home']['team2']['lost'].'</div>
									</div>
									<div class="row ">
										<div class="col-xs-6">เสมอ</div>
										<div class="col-xs-6">'.$match_overall['home']['team2']['draws'].'</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row border">
						<div class="col-xs-3 ">นอกบ้าน</div>
						<div class="col-xs-9 ">
							<div class="row ">
								<div class="col-xs-6">'.$hometeam_title.'</div>
								<div class="col-xs-6">
									<div class="row ">
										<div class="col-xs-6">แมตย์</div>
										<div class="col-xs-6">'.$match_overall['away']['team1']['games'].'</div>
									</div>
									<div class="row ">
										<div class="col-xs-6">ขนะ</div>
										<div class="col-xs-6">'.$match_overall['away']['team1']['won'].'</div>
									</div>
									<div class="row ">
										<div class="col-xs-6">แพ้</div>
										<div class="col-xs-6">'.$match_overall['away']['team1']['lost'].'</div>
									</div>
									<div class="row ">
										<div class="col-xs-6">เสมอ</div>
										<div class="col-xs-6">'.$match_overall['away']['team1']['draws'].'</div>
									</div>
								</div>
							</div>
							<div class="row ">
								<div class="col-xs-6">'.$awayteam_title.'</div>
								<div class="col-xs-6">
									<div class="row ">
										<div class="col-xs-6">แมตย์</div>
										<div class="col-xs-6">'.$match_overall['home']['team2']['games'].'</div>
									</div>
									<div class="row ">
										<div class="col-xs-6">ชนะ</div>
										<div class="col-xs-6">'.$match_overall['home']['team2']['won'].'</div>
									</div>
									<div class="row ">
										<div class="col-xs-6">แพ้</div>
										<div class="col-xs-6">'.$match_overall['home']['team2']['lost'].'</div>
									</div>
									<div class="row ">
										<div class="col-xs-6">เสมอ</div>
										<div class="col-xs-6">'.$match_overall['home']['team2']['draws'].'</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>';

			//*********** leagues ***********
			// Debug($match_leagues);
			
			if(isset($match_leagues)){

				$display_leagues .= '<div class="row ">';
				$all_match_leagues = count($match_leagues);
				for($j=0;$j<$all_match_leagues;$j++){

					$name = $this->translet_thaileague($match_leagues[$j]['name']);
					$id = $match_leagues[$j]['id'];
					$games = $match_leagues[$j]['games'];
					$team1_won = $match_leagues[$j]['team1_won'];
					$team2_won = $match_leagues[$j]['team2_won'];
					$drawdraw = $match_leagues[$j]['drawdraw'];

					$display_leagues .= '<div class="row ">
						<div class="col-xs-12">'.$name.'</div>
						<div class="col-xs-12">
							<div class="row">
								<div class="col-xs-2">แมตย์</div>
								<div class="col-xs-4">'.$hometeam_title.' ชนะ</div>
								<div class="col-xs-4">'.$awayteam_title.' ชนะ</div>
								<div class="col-xs-2">เสมอ</div>
							</div>
							<div class="row">
								<div class="col-xs-2">'.$games.'</div>
								<div class="col-xs-4">'.$team1_won.'</div>
								<div class="col-xs-4">'.$team2_won.'</div>
								<div class="col-xs-2">'.$drawdraw.'</div>
							</div>
						</div>
					</div>';
				}
				$display_leagues .= '</div>';						
			}

			//*********** biggest_victory ***********
			// Debug($match_biggest_victory);
			
			if(isset($match_biggest_victory['team1'])){

				$team1_league_id = $match_biggest_victory['team1']['league_id'];
				$team1_id1 = $match_biggest_victory['team1']['id1'];
				$team1_id2 = $match_biggest_victory['team1']['id2'];
				$team1_date = $match_biggest_victory['team1']['date'];
				$team1 = $match_biggest_victory['team1']['team1'];
				$team2 = $match_biggest_victory['team1']['team2'];

				$display_biggest_victory .= '<div class="row ">
					<div class="col-xs-8">'.$match_biggest_victory['team1']['category'].' '.$this->translet_thaileague($match_biggest_victory['team1']['league']).'</div>
					<div class="col-xs-4">'.$team1_date.'</div>
					<div class="col-xs-5 right">'.$hometeam_title.'</div>
					<div class="col-xs-3">'.$match_biggest_victory['team1']['team1_score'].'-'.$match_biggest_victory['team1']['team2_score'].'</div>
					<div class="col-xs-4 left">'.$awayteam_title.'</div>
				</div>';
			}

			if(isset($match_biggest_victory['team2'])){

				$team2_league_id = $match_biggest_victory['team2']['league_id'];
				$team2_id1 = $match_biggest_victory['team2']['id1'];
				$team2_id2 = $match_biggest_victory['team2']['id2'];
				$team2_date = $match_biggest_victory['team2']['date'];
				$team1 = $match_biggest_victory['team2']['team1'];
				$team2 = $match_biggest_victory['team2']['team2'];

				$display_biggest_victory .= '<div class="row ">
					<div class="col-xs-8">'.$match_biggest_victory['team2']['category'].' '.$this->translet_thaileague($match_biggest_victory['team2']['league']).'</div>
					<div class="col-xs-4">'.$team2_date.'</div>
					<div class="col-xs-5">'.$awayteam_title.'</div>
					<div class="col-xs-3">'.$match_biggest_victory['team2']['team1_score'].'-'.$match_biggest_victory['team2']['team2_score'].'</div>
					<div class="col-xs-4">'.$hometeam_title.'</div>
				</div>';						
			}

			//*********** Last5_home_team1 ***********
			// Debug($match_last5_home_team1);

			$match_last10_team1 = array_merge($match_last5_home_team1, $match_last5_away_team1);
			sort($match_last10_team1);
			$new_match_list = null;

			$count_match = (count($match_last10_team1) > 10) ? 10 : count($match_last10_team1);
			for($j=0;$j<$count_match;$j++){

				$rows_match = $match_last10_team1[$j];
				
				$new_match_list[$j]['date_en'] = date('Y-m-d', strtotime($rows_match['date']));
				$new_match_list[$j]['date'] = $rows_match['date'];
				$new_match_list[$j]['category'] = $rows_match['category'];
				$new_match_list[$j]['league'] = $this->translet_thaileague($rows_match['league']);
				$new_match_list[$j]['league_id'] = $rows_match['league_id'];
				$new_match_list[$j]['team1'] = $rows_match['team1'];
				$new_match_list[$j]['id1'] = $rows_match['id1'];
				$new_match_list[$j]['team2'] = $rows_match['team2'];
				$new_match_list[$j]['id2'] = $rows_match['id2'];
				$new_match_list[$j]['team1_score'] = $rows_match['team1_score'];
				$new_match_list[$j]['team2_score'] = $rows_match['team2_score'];
				$new_match_list[$j]['static_id'] = $rows_match['static_id'];
				// echo "date_en=$date_en<br>";
			}
			rsort($new_match_list);

			$count_match = (count($new_match_list) > 5) ? 5 : count($new_match_list);
			for($j=0;$j<$count_match;$j++){

				$rows_match = $new_match_list[$j];

				$category = $rows_match['category'];
				$league = $this->translet_thaileague($rows_match['league']);
				$league_id = $rows_match['league_id'];
				$team1 = $rows_match['team1'];
				$id1 = $rows_match['id1'];
				$team2 = $rows_match['team2'];
				$id2 = $rows_match['id2'];
				$date = $rows_match['date'];
				$team1_score = $rows_match['team1_score'];
				$team2_score = $rows_match['team2_score'];
				$static_id = $rows_match['static_id'];

				$class_result = 'draw';
				//$hometeam_id, $awayteam_id 
				//$hometeam_title, $awayteam_title
				if($hometeam_id == $id1){

					$team1 = $hometeam_title;
					if($team1_score > $team2_score)
						$class_result = 'win';
					else if($team1_score < $team2_score)
						$class_result = 'lost';

				}else if($hometeam_id == $id2){

					$team2 = $hometeam_title;
					if($team1_score < $team2_score)
						$class_result = 'win';
					else if($team1_score > $team2_score)
						$class_result = 'lost';
				}

				$display_last10_team1 .= '<div class="row ">
					<div class="col-xs-6">'.$date.'</div>
					<div class="col-xs-6">'.$league.'</div>
					<div class="col-xs-12"><div class="row">
						<div class="col-xs-5">'.$team1.'</div><div class="col-xs-3"><span class="'.$class_result.'">'.$team1_score.'-'.$team2_score.'</span></div><div class="col-xs-4">'.$team2.'</div>
					</div></div>
				</div>';
			}

			//*********** last5_home_team2 ***********
			// Debug($match_last5_home_team2);
			$match_last10_team2 = array_merge($match_last5_home_team2, $match_last5_away_team2);
			sort($match_last10_team2);
			unset($new_match_list);

			$count_match = (count($match_last10_team2) > 10) ? 10 : count($match_last10_team2);
			for($j=0;$j<$count_match;$j++){

				$rows_match = $match_last10_team2[$j];
				
				$new_match_list[$j]['date_en'] = date('Y-m-d', strtotime($rows_match['date']));
				$new_match_list[$j]['date'] = $rows_match['date'];
				$new_match_list[$j]['category'] = $rows_match['category'];
				$new_match_list[$j]['league'] = $this->translet_thaileague($rows_match['league']);
				$new_match_list[$j]['league_id'] = $rows_match['league_id'];
				$new_match_list[$j]['team1'] = $rows_match['team1'];
				$new_match_list[$j]['id1'] = $rows_match['id1'];
				$new_match_list[$j]['team2'] = $rows_match['team2'];
				$new_match_list[$j]['id2'] = $rows_match['id2'];
				$new_match_list[$j]['team1_score'] = $rows_match['team1_score'];
				$new_match_list[$j]['team2_score'] = $rows_match['team2_score'];
				$new_match_list[$j]['static_id'] = $rows_match['static_id'];
				// echo "date_en=$date_en<br>";
			}
			rsort($new_match_list);
			
			$count_match = (count($new_match_list) > 5) ? 5 : count($new_match_list);
			for($j=0;$j<$count_match;$j++){

				$rows_match = $new_match_list[$j];

				$category = $rows_match['category'];
				$league = $this->translet_thaileague($rows_match['league']);
				$league_id = $rows_match['league_id'];
				$team1 = $rows_match['team1'];
				$id1 = $rows_match['id1'];
				$team2 = $rows_match['team2'];
				$id2 = $rows_match['id2'];
				$date = $rows_match['date'];
				$team1_score = $rows_match['team1_score'];
				$team2_score = $rows_match['team2_score'];
				$static_id = $rows_match['static_id'];

				$class_result = 'draw';
				//$hometeam_id, $awayteam_id 
				//$hometeam_title, $awayteam_title
				if($awayteam_id == $id1){

					$team1 = $awayteam_title;
					if($team1_score > $team2_score)
						$class_result = 'win';
					else if($team1_score < $team2_score)
						$class_result = 'lost';

				}else if($awayteam_id == $id2){

					$team2 = $awayteam_title;
					if($team1_score < $team2_score)
						$class_result = 'win';
					else if($team1_score > $team2_score)
						$class_result = 'lost';
				}			

				$display_last10_team2 .= '<div class="row ">
					<div class="col-xs-6">'.$date.'</div>
					<div class="col-xs-6">'.$league.'</div>
					<div class="col-xs-12"><div class="row">
						<div class="col-xs-5">'.$team1.'</div><div class="col-xs-3"><span class="'.$class_result.'">'.$team1_score.'-'.$team2_score.'</span></div><div class="col-xs-4">'.$team2.'</div>
					</div></div>
				</div>';
			}

			//*********** last5_away_team1 ***********
			// Debug($match_last5_away_team1);
			/*
			$count_match = (count($match_last5_away_team1) > 10) ? 10 : count($match_last5_away_team1);
			for($j=0;$j<$count_match;$j++){

				$rows_match = $match_last5_away_team1[$j];

				$category = $rows_match['category'];
				$league = $this->translet_thaileague($rows_match['league']);
				$league_id = $rows_match['league_id'];
				$team1 = $rows_match['team1'];
				$id1 = $rows_match['id1'];
				$team2 = $rows_match['team2'];
				$id2 = $rows_match['id2'];
				$date = $rows_match['date'];
				$team1_score = $rows_match['team1_score'];
				$team2_score = $rows_match['team2_score'];
				$static_id = $rows_match['static_id'];

				$display_last5_away_team1 .= '<div class="row ">
					<div class="col-xs-2">'.$date.'</div>
						<div class="col-xs-8"><div class="row">
							<div class="col-xs-4">'.$team1.'</div><div class="col-xs-4">'.$team1_score.'-'.$team2_score.'</div><div class="col-xs-4">'.$hometeam_title.'</div>
						</div></div>
					<div class="col-xs-2">'.$league.'</div>
				</div>';
			}
			*/
			//*********** last5_away_team2 ***********
			// Debug($match_last5_away_team2);
			/*
			$count_match = (count($match_last5_away_team2) > 10) ? 10 : count($match_last5_away_team2);
			for($j=0;$j<$count_match;$j++){

				$rows_match = $match_last5_away_team2[$j];

				$category = $rows_match['category'];
				$league = $this->translet_thaileague($rows_match['league']);
				$league_id = $rows_match['league_id'];
				$team1 = $rows_match['team1'];
				$id1 = $rows_match['id1'];
				$team2 = $rows_match['team2'];
				$id2 = $rows_match['id2'];
				$date = $rows_match['date'];
				$team1_score = $rows_match['team1_score'];
				$team2_score = $rows_match['team2_score'];
				$static_id = $rows_match['static_id'];

				$display_last5_away_team2 .= '<div class="row ">
					<div class="col-xs-2">'.$date.'</div>
						<div class="col-xs-8"><div class="row">
							<div class="col-xs-4">'.$team1.'</div><div class="col-xs-4">'.$team1_score.'-'.$team2_score.'</div><div class="col-xs-4">'.$awayteam_title.'</div>
						</div></div>
					<div class="col-xs-2">'.$league.'</div>
				</div>';
			}
			*/
			
		}
		

		$html = '<div class="considetail">';

		if($display_top50 != '')
			$html .= '<div class="row border">
					<div class="col-xs-12 center"><h2>ผลงานการพบกันของทั้งสองทีม</h2></div>
					'.$display_top50.'
				</div>';

		if($display_overall != '')
			$html .= '<div class="row">
					<div class="col-xs-12 center"><h2>การพบกันทั้งหมด</h2></div>
					'.$display_overall.'
				</div>';

		if($display_leagues != '')
			$html .= '<div class="row border">
					<div class="col-xs-12 center"><h2>ลีก</h2></div>
					'.$display_leagues.'
				</div>';

		if($display_biggest_victory != '')
			$html .= '<div class="row border">
					<div class="col-xs-12 center"><h2>ชนะมากสุด</h2></div>
					'.$display_biggest_victory.'
				</div>';
		
		if($display_last10_team1 != '')
			$html .= '<div class="row border">
					<div class="col-xs-12 center"><h2>ล่าสุดของ '.$hometeam_title.'</h2></div>
					'.$display_last10_team1.'
				</div>';

		if($display_last10_team2 != '')
			$html .= '<div class="row border">
					<div class="col-xs-12 center"><h2>ล่าสุดของ '.$awayteam_title.'</h2></div>
					'.$display_last10_team2.'
				</div>';

		/*if($display_last5_away_team1 != '')
			$html .= '<div class="row border">
					<div class="col-xs-12 center"><h2>เล่นนอกบ้านล่าสุดของ '.$hometeam_title.'</h2></div>
					'.$display_last5_away_team1.'
				</div>';

		if($display_last5_away_team1 != '')
			$html .= '<div class="row border">
					<div class="col-xs-12 center"><h2>เล่นนอกบ้านล่าสุดของ '.$awayteam_title.'</h2></div>
					'.$display_last5_away_team2.'
				</div>';*/

		$html .= '</div>';

		return $html;
	}

	//แสดงผลการวิเคราะห์ข้อมูล
	public function display_analyze_program_id($fixtures_list, $program_analy_list){
		$html = $list_player = '';
		$analy_home = $analy_away = $player_home = $player_away = $vision = $predict = $interesting = '';

		$program_id = $fixtures_list->program_id;
		$fix_id = $fixtures_list->fix_id;

		$hometeam_title = ($fixtures_list->hometeam_title_th != '') ? $fixtures_list->hometeam_title_th : $fixtures_list->hometeam_title;
		$awayteam_title = ($fixtures_list->awayteam_title_th != '') ? $fixtures_list->awayteam_title_th : $fixtures_list->awayteam_title;

		$hometeam_id = $fixtures_list->hometeam_id;
		$awayteam_id = $fixtures_list->awayteam_id;

		// Debug($program_analy_list);
		// Debug($program_stat_list);
		// $data_h2h = unserialize($program_stat_list[0]->json);
		// Debug($data_h2h);

		if(isset($program_analy_list[0]->analy_home))
			$analy_home = str_replace("\n", "<br>", $program_analy_list[0]->analy_home);
		
		if(isset($program_analy_list[0]->analy_away))
			$analy_away = str_replace("\n", "<br>", $program_analy_list[0]->analy_away);

		if(isset($program_analy_list[0]->player_home))
			$player_home = $program_analy_list[0]->player_home;

		if(isset($program_analy_list[0]->player_away))
			$player_away = $program_analy_list[0]->player_away;

		if(isset($program_analy_list[0]->vision))
			$vision = str_replace("\n", "<br>", $program_analy_list[0]->vision);

		$review = @$program_analy_list[0]->review;
		
		if(isset($program_analy_list[0]->predict))
			$predict = $program_analy_list[0]->predict;

		if(isset($program_analy_list[0]->interesting))
			$interesting = str_replace("\n", "<br>", $program_analy_list[0]->interesting);

		if($player_home != '' && $player_away != ''){

			$list_player = '<div class="considetail-vs">
			<h3>รายชื่อผู้เล่นที่คาดว่าจะลงสนาม</h3>
			<div>
			  <p>
				<strong>'.$hometeam_title.'</strong>
				'.$player_home.'
			  </p>
			  <p>
				<strong>'.$awayteam_title.'</strong>
				'.$player_away.'
			  </p>
			</div>
		  </div>';

		}

		$html = '<div class="considetail-vs">
        <h3>เปรียบเทียบความพร้อมของทีม</h3>
        <div>
          <p>
            <strong>'.$hometeam_title.'</strong> '.$analy_home.'
          </p>
          <p>
            <strong>'.$awayteam_title.'</strong> '.$analy_away.'
          </p>
        </div>
      </div>
      '.$list_player.'
      <div class="considetail-vs consigame">
        <h3>ความน่าจะเป็นของเกม</h3>
        <div>
          <p>'.$vision.' </p>
        </div>
      </div>
      <div class="considetail-vs consiscore">
        <h3>สกอร์ที่คาด</h3>
        <div>
          <p>'.$predict.'</p>
        </div>
      </div>';

	  	if($interesting != ''){
			$html .= '<div class="considetail-vs consigame">
				<h3>ข้อมูลที่น่าสนใจ</h3>
				<div>
				<p>'.$interesting.' </p>
				</div>
			</div>';
	  	}

		return $html;
	}

	public function display_standing($obj_list)
	{
		$html = $tmp = '';
		$datenow = date('Y-m-d');

		$group_name = str_replace('Group', 'ตารางคะแนนกลุ่ม', $obj_list[0]->group_name);
		
		$html = '<h3>'.$group_name.'</h3>';
		$html .= '<table>
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
				// $group_name = str_replace('Group', 'ตารางคะแนนกลุ่ม', $rows->group_name);
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

				$html .= '<tr>
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
				<tr>';
			}

			$html .= '
			</tbody>
		  </table>';
		  //$html .= '<a href="#">โปรแกรม ผลบอล '.$tmp.'</a>';
		}

		return $html;
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

			$link_teamhome = base_url('team/detail/'.$hteam_id);
			$link_teamaway = base_url('team/detail/'.$ateam_id);
			$view_analy = base_url('analyze/view/'.$program_id.'/'.$fix_id);
			$link_matchdetail = base_url('program/detail/'.$program_id.'/'.$fix_id);

			$html .= '<div>
				<span>
				<a href="'.$link_teamhome.'" target="_blank">'.$home_team.' '.$logo_team1.'</a> <strong><a href="'.$link_matchdetail.'" target="_blank">VS</a></strong> 
				<a href="'.$link_teamaway.'" target="_blank">'.$logo_team2.' '.$away_team.'</a></span> <a href="'.$link_matchdetail.'" target="_blank">'.$match_datetime_th.'</a> 
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
			$link_matchdetail = base_url('program/detail/'.$program_id.'/'.$fix_id);

			$html .= '<div> 
				<span><a href="'.$link_teamhome.'" target="_blank">'.$home_team.' '.$logo_team1.'</a> 
				<strong><a href="'.$link_matchdetail.'" target="_blank">'.$hgoals.' : '.$agoals.'</a></strong> 
				<a href="'.$link_teamaway.'" target="_blank">'.$logo_team2.' '.$away_team.'</a></span>
			</div>';

		}
		
		return $html;
	}

	function edit($program_id){

		$cur_date = date('Y-m-d H:i:s');

		$fixtures_list = $this->fixtures_model->get_data($program_id);

		$program_analy_list = $this->program_analy_model->get_data($program_id);
		// Debug($this->db->last_query());
		// Debug($program_analy_list);
		// die();
		if(empty($program_analy_list)){

			$data_update = array(
				'program_id' => $program_id,
				'create_date' => $cur_date
			);
			$this->program_analy_model->store(0, $data_update);
			// Debug($this->db->last_query());
			redirect('analyze/edit/'.$program_id);
			die();
		}

		$html = $this->display_program_analy_edit($program_id, $program_analy_list, $fixtures_list);
		// echo "<hr>".$html;

		$data = array(
			"meta" => null,
            "webtitle" => 'วิเคราะห์ฟุตบอลโลก',
			"head" => 'วิเคราะห์ฟุตบอลโลก',
			"html" => $html,
			"content_view" => 'tool/blank'
		);
        $this->load->view('template',$data);
	}

	public function display_program_analy_edit($program_id, $data, $fixtures_list){

		$league_id = $fixtures_list[0]->league_id;
		$hometeam_id = $fixtures_list[0]->hometeam_id;
		$awayteam_id = $fixtures_list[0]->awayteam_id;

		$hometeam_title_th = $fixtures_list[0]->hometeam_title_th;
		$awayteam_title_th = $fixtures_list[0]->awayteam_title_th;

		$stadium_id = $fixtures_list[0]->stadium_id;
		$stadium_name = $fixtures_list[0]->stadium_name;
		$stadium_name_th = $fixtures_list[0]->stadium_name_th;

		// Debug($data);
		// Debug($fixtures_list);
		$fix_id = $fixtures_list[0]->fix_id;

		$analy_home = $data[0]->analy_home;
		$analy_away = $data[0]->analy_away;
		$player_home = $data[0]->player_home;
		$player_away = $data[0]->player_away;
		$vision = $data[0]->vision;
		$review = $data[0]->review;
		$predict = $data[0]->predict;
		$interesting = $data[0]->interesting;

		$view_analy = base_url('analyze/view/'.$program_id.'/'.$fix_id);

		$show_view = anchor($view_analy, '<button type="button" class="btn btn-warning form-control"> ดูการวิเคราะห์ </button>', array('target' => '_blank'));

		$html = '
		<div class="container">
			<div class="row">
				<h3>สนาม</h3>
				<div class="mb-3">'.$stadium_name.'</div>
				<div class="mb-3">
					<label for="stadium_name" class="form-label"><strong>'.$stadium_name_th.'</strong></label>
					<input type="text" class="form-control" id="stadium_name" name="stadium_name" value="'.$stadium_name_th.'" >
				</div>
			</div>
			<div class="row">
				<div id="res_stadium" class="mb-3"></div>
			</div>
			<div class="row"><button type="button" class="btn btn-primary" onclick="update_stadium('.$stadium_id.')"> บันทึกข้อมูลสนาม </button></div>
			<div class="row">
				<h3>เปรียบเทียบความพร้อมของทีม</h3>
				<div class="mb-3">
					<label for="analy_home" class="form-label"><strong>'.$hometeam_title_th.'</strong></label>
					<textarea class="form-control" id="analy_home" name="analy_home" rows="3">'.$analy_home.'</textarea>
				</div>
				<div class="mb-3">
					<label for="analy_away" class="form-label"><strong>'.$awayteam_title_th.'</strong></label>
					<textarea class="form-control" id="analy_away" name="analy_away" rows="3">'.$analy_away.'</textarea>
				</div>
			</div>
			<div class="row">
				<h3>รายชื่อผู้เล่นที่คาดว่าจะลงสนาม</h3>
				<div class="mb-3">
					<label for="player_home" class="form-label"><strong>'.$hometeam_title_th.'</strong></label>
					<textarea class="form-control" id="player_home" name="player_home" rows="3">'.$player_home.'</textarea>
				</div>
				<div class="mb-3">
					<label for="player_away" class="form-label"><strong>'.$awayteam_title_th.'</strong></label>
					<textarea class="form-control" id="player_away" name="player_away" rows="3">'.$player_away.'</textarea>
				</div>
			</div>
			<div class="row">
				<h3>ความน่าจะเป็นของเกม</h3>
				<div class="mb-3">
					<label for="vision" class="form-label"><strong>ความน่าจะเป็นของเกม</strong></label>
					<textarea class="form-control" id="vision" name="vision" rows="3">'.$vision.'</textarea>
				</div>
			</div>
			<div class="row">
				<h3>สกอร์ที่คาด</h3>
				<div class="mb-3">
					<label for="predict" class="form-label"><strong>สกอร์ที่คาด</strong></label>
					<textarea class="form-control" id="predict" name="predict" rows="3">'.$predict.'</textarea>
				</div>
			</div>
			<div class="row">
				<h3>ข้อมูลที่น่าสนใจ</h3>
				<div class="mb-3">
					<label for="interesting" class="form-label"><strong>ข้อมูลที่น่าสนใจ</strong></label>
					<textarea class="form-control" id="interesting" name="interesting" rows="3">'.$interesting.'</textarea>
				</div>
			</div>
			<div class="row">
				<div id="res" class="mb-3"></div>
			</div>
			<div class="row">
				<div class="col">
					<input type="hidden" name="program_id" id="program_id" value="'.$program_id.'" >
					<button type="button" class="btn btn-primary form-control" onclick="update_analy('.$program_id.')"> บันทึกข้อมูลการวิเคราะห์ </button>
				</div>
				<div class="col">'.$show_view.'</div>
			</div>
			
		</div>';


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
		$this->load->model('team_model');

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

		$html = '<div class="teammini"><a href="'.base_url('team/detail/'.$team_id).'">'.$logo_team.'<strong> ทีมชาติ'.$team_name.'</strong></a></div>';
		// echo $html;
		return $html;
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

	public function translet_thaileague($txt){

		$output = '';

		if($txt == 'Friendly'){
			$output = 'กระชับมิตร';
		}else if($txt == 'WC Qualifying South America'){
			$output = 'คัดเลือกฟุตบอลโลก โซนอเมริกาใต้';
		}else if($txt == 'WC Qualifying Asia'){
			$output = 'คัดเลือกฟุตบอลโลก โซนเอเชีย';
		}else if($txt == 'WC Qualifying Europe'){
			$output = 'คัดเลือกฟุตบอลโลก โซนยุโรป';
		}else if($txt == 'WC Qualifying Africa'){
			$output = 'คัดเลือกฟุตบอลโลก โซนแอฟริกา';
		}else
			$output = $txt;

		return $output;
	}
}