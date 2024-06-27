<?php
class Standing extends CI_Controller {

	protected $season_id;
	protected $season;
	protected $tournament_id;
	protected $tournament;
	protected $date_start;
	protected $datetime_start;
	protected $team_path;
	protected $stadium_path;
	protected $base_path;
	protected $_page = 'standing';
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
		$postdata = null;
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

			$thisweek = $this->standing_model->get_max_week($tournament_id, '_xml_standing', $season_id);
			// echo $this->db->last_query();
			// Debug($thisweek);		
			$week = ($this->input->get('week')) ? $this->input->get('week') : $thisweek;

			$obj_list = $this->standing_model->get_xml_data(intval($tournament_id), 0, $round);
			// echo $this->db->last_query();
			// Debug($obj_list);
			// die();
			
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

			$webtitle = 'ตารางคะแนนฟุตบอลยูโร 2024 อับเดตล่าสุด';
			$page_published_time = date('c' , strtotime('2022-10-27'));
			$page_lastupdated_date = date('c');
			// $keywords = explode(',', _KEYWORD);
			$social_block = $this->social_block($webtitle);

			$keywords[] = 'ตารางคะแนนฟุตบอลยูโร 2024';
			$keywords[] = 'ตารางคะแนน';
			$keywords[] = 'ตารางบอลยูโร';
			$keywords[] = $tournament_name;
			
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
				"head" => 'ตารางคะแนนฟุตบอลยูโร 2024',
				"html" => $html,
				"social_block" => null,
				"widgets_program" => null,
				"widgets_result" => null,
				"css" => $asset_css,
				"js" => $asset_js,
				"page_lastupdated_date" => $page_lastupdated_date,
				"breadcrumb" => $breadcrumb,
				"content_view" => 'standing/view'
			);
			// $this->load->view('template-wc',$data);
			$html = $this->load->view('template-euro', $data, true);

			//cache to redis
			$this->utils->setCacheRedis($cache_key_all, $html);
			echo $html;
			$this->db->close();
		}
	}

	public function html()
	{
		$this->load->view('html/standing');
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
				$link_team = base_url('team/detail/'.$team_id);

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
				
					<a href="'.base_url('fixtures').'" target="_blank">โปรแกรมบอล ผลบอล '.$tmp.'</a>
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
		  </table>
		  <a href="#">โปรแกรม ผลบอล '.$tmp.'</a>';
		}

		return $html;
	}

	public function widgets_program($fixtures_list)
	{

		$tmp = $html = '';
		$logo_team1 = $logo_team2 = null;
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
				<a href="'.$link_teamhome.'" target="_blank">'.$home_team.' '.$logo_team1.'</a> <strong><a href="'.$view_analy.'" target="_blank">VS</a></strong> 
				<a href="'.$link_teamaway.'" target="_blank">'.$logo_team2.' '.$away_team.'</a>
				</span><a href="'.$view_analy.'" target="_blank">'.$match_datetime_th.'</a> 
			</div>';

		}
		
		return $html;
	}

	public function widgets_result($result_list)
	{

		$tmp = $html = '';
		$logo_team1 = $logo_team2 = null;
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

	public function load_base64img($src, $width = 25, $height = 15, $title = ''){

		$output = "<img class='base64image' width='".$width."' height='".$height."' alt='".$title."' title='".$title."' src='data:image/jpeg;base64, $src' />";
      	return $output;
	}
}