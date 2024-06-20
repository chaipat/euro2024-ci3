<?php
class Fixtures extends CI_Controller {
	var $season = 9;
	protected $_page = 'fixtures';
	protected $_cache;

    public function __construct()    {
		parent::__construct();
		header('Content-type: text/html; charset=utf-8');

		// $this->load->library('session');
		// $this->load->library('genarate');
		$this->load->model('program_model');
		$this->load->model('fixtures_model');
		$this->load->model('season_model');
		$this->load->model('tournament_model');
		$this->load->library('api');
		$this->load->library('utils');
		$this->load->helper('common');

        $this->tournament_id = $this->config->config['tournament_id'];
		$this->tournament = $this->config->config['tournament'];
        $this->season = $this->config->config['season'];
 		$this->date_start = $this->config->config['date_start'];
		$this->datetime_start = $this->config->config['datetime_start'];

		$this->catid = $this->config->config['catid_news'];
        
        $this->team_path = 'data/uploads/teamlogo/';
		$this->stadium_path = 'data/uploads/stadium/';
		$this->base_path = str_replace('application/controllers', '', __DIR__);

		if($this->input->get('cache') == 'disable'){
			$this->_cache = false;
		}else
			$this->_cache = true;
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
		$stage_id = 10561027;
		$week = 2;

        $dateprev = date("Y-m-d", strtotime("-1 day"));
        $datebetween[] = $dateprev.' 00:00:00';
        $datenext = date("Y-m-d", strtotime("+1 day"));
        $datebetween[] = $datenext.' 23:59:59';

		if($datenow >= '2022-12-15'){

			$stage_id = 10561444;
			$week = 0;
		}else if($datenow >= '2022-12-13'){

			$stage_id = 10561089;
			$week = 0;
		}else if($datenow >= '2022-12-09'){

			$stage_id = 10562591;
			$week = 0;
		}else if($datenow >= '2022-12-03'){

			$stage_id = 10561511;
			$week = 0;
		}else if($datenow >= '2022-11-29'){

			$stage_id = 10561027;
			$week = 3;
		}else{

			$stage_id = 10561027;
			$week = 2;
		}

		if($this->input->get('stage')){
			$stage_id = intval($this->input->get('stage'));
		}

		if($this->input->get('w')){
			$week = $this->input->get('w');
		}

		if($stage_id != 10561027) $week = 0;

		
		$section = 'index';
		$cache_key_all = 'page_'.$this->_page.'_'.$section.'-'.$stage_id.'-'.$week;
		$cache = $this->utils->getCache($cache_key_all);

		if($cache && $this->_cache){

			if($this->input->get('oncheck') == 1){
				echo "Cache On ".$cache_key_all."<hr>";
			}
			echo $cache.'<!-- Cache -->';
		}else{

			// $sel_date = $this->fixtures_model->sel_date($stage_id);
			// Debug($sel_date);

			$obj_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id, null, $stage_id, $week);
			// $obj_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id, $datebetween);

			if($stage_id == 10561444)
				$obj_list2 = $this->fixtures_model->get_data(0, 0, $this->tournament_id, null, 10561069);

			// $obj_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id);
			// $obj_list = $this->fixtures_model->get_xml($this->tournament_id);
			// echo $this->db->last_query();
			// Debug($obj_list);
			// die();

			if(isset($obj_list[0]->tournament_name)){
				$tournament_name = $obj_list[0]->tournament_name;
			}

			// echo "($stage_id)<br>";
			//Final round
			if($stage_id == 10561444){
				$html = $this->display_program($obj_list2);
				$html .= $this->display_program($obj_list);
			}else{
				$html = $this->display_program($obj_list);
			}

			$breadcrumb[] = 'โปรแกรมบอลโลก';

			$webtitle = 'โปรแกรม'.$tournament_name.' ช่องถ่ายทอดสดฟุตบอลโลก พร้อมลิ้งถ่ายทอดสด';
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
				'description' => $webtitle.'ฟุตบอลโลก 2022 '._DESCRIPTION,
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
				"sel_date" => $sel_date,
				"html" => $html,
				"stage_id" => $stage_id,
				"week" => $week,
				"social_block" => $social_block,
				"css" => $asset_css,
				"js" => $asset_js,
				"page_lastupdated_date" => $page_lastupdated_date,
				"breadcrumb" => $breadcrumb,
				"content_view" => 'fixtures/list'
			);
			// $this->load->view('template-wc',$data);
			$html = $this->load->view('template-wc', $data, true);

			//cache to redis
			$this->utils->setCacheRedis($cache_key_all, $html);
			echo $html;
			$this->db->close();
		}
	}

	public function display_program($obj_list){
		$html = $tmp = '';
		$num_banner = 0;
		$datenow = date('Y-m-d');

		if ($obj_list){

			$allitem = count($obj_list);
			// echo "($allitem)<br>";
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

				$ft_result = $rows->ft_result;
				$et_result = $rows->et_result;
				$penalty = $rows->penalty;

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

				// $channel_code = $rows->code;
				// $channel_logo = $rows->channel_logo;
				// $channel_name = $rows->channel_name;
				// $channel_link = $rows->channel_link;

				// $show_date = date('Y-m-d H:i:s', strtotime($kickoff.' +7 hour'));
				// list($wc_date, $wc_time) = explode(' ', $show_date);
				
				$match_time = date('H:i', strtotime($kickoff_th));
				// $match_time = date('H:i', strtotime($kickoff.' +7 hour'));
				// list($match_date, $match_time) = explode(' ', $kickoff);

				$show_date_th = DateTH($sel_date);

				$logo_team1 = $logo_team2 = $time_score = '';

				if($stage_id != 10561027){
					$group_name = '';
				}

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

						$time_score = $hometeam_point.' - '.$awayteam_point;
					}
					

				}

				$class_endmatch = $add_banner = '';
				if($datenow == $sel_date){

					$num_banner++;
					$class_endmatch = 'endmatch';
					$add_banner = '<div id="banner'.$num_banner.'" class="banner"></div>';
				}else if($datenow > $sel_date || $i == 0){
				
					$class_endmatch = 'endmatch';
				}

		
				$link_teamhome = ($hometeam_id > 0) ? base_url('team/detail/'.$hometeam_id): '#';
				$link_teamaway = ($awayteam_id > 0) ? base_url('team/detail/'.$awayteam_id): '#';

				$view_analy = base_url('analyze/view/'.$program_id.'/'.$fix_id);
				$view_match = base_url('match/detail/'.$program_id.'/'.$fix_id);

				if($time_score != 'FT'){

					$match_vs = anchor($view_match, $time_score, array('target' => '_blank'));
					// $match_vs = $time_score;

				}else{

					// $match_vs = anchor($view_match, $time_score, array('target' => '_blank'));
					$match_vs = $time_score;
				}

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

				if($hometeam_title_th == '')
					$hometeam_title_th = $hometeam_title;

				if($awayteam_title_th == '')
					$awayteam_title_th = $awayteam_title;

				$html .= '
				<div class="match-list '.$class_endmatch.'">
					<div>
					<span><a href="'.$link_teamhome.'" target="_blank" >'.$hometeam_title_th.' '.$logo_team1.'</a> <strong>'.$match_vs.'</strong> 
					<a href="'.$link_teamaway.'" target="_blank" >'.$logo_team2.' '.$awayteam_title_th.'</a></span>
					<span>'.$group_name.'</span>
					<span>ช่องถ่ายทอดสดฟุตบอลโลก พร้อมลิ้งถ่ายทอดสด</span>
					'.$channel.'
					<span>สนาม '.$stadium_name.'</span>					
					<a href="'.$view_analy.'" target="_blank">วิเคราะห์บอล</a>
					<a href="'.$view_match.'" target="_blank">ผลบอลสด</a>
					</div>
				</div>'.$add_banner;

			}
		}
		// die();
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
		// Debug(count($obj_list));
		// Debug($obj_list);
		// die();

		if ($obj_list) {
			$allitem = count($obj_list);
			if ($obj_list)
				for ($i = 0; $i < $allitem; $i++) {

					$rows = $obj_list[$i];
					$json = '';

					$match_id = $rows->match_id;

					if($rows->stage_id > 0){
						$stage_id = $rows->stage_id;
					}
					$static_id = $rows->static_id;

					$tournament_id = $rows->tournament_id;
					$stadium_id = $rows->stadium_id;
					$referee_id = $rows->referee_id;
					$referee = $rows->referee;

					$hteam_id = $rows->hteam_id;
					$hteam = $rows->hteam;
					$score1 = $hgoals = $rows->hgoals;

					$ateam_id = $rows->ateam_id;
					$ateam = $rows->ateam;
					$score2 = $agoals = $rows->agoals;

					$tournament_name = ($rows->tournament_name == '') ? $rows->tournament_name_en:$rows->tournament_name;

					$data_list[$i]['fix_id'] = $match_id;
					$data_list[$i]['stage_id'] = $stage_id;
					$data_list[$i]['static_id'] = $static_id;
					$data_list[$i]['league_id'] = $tournament_id;
					$data_list[$i]['stadium_id'] = $stadium_id;
					$data_list[$i]['season'] = 2022;

					$data_list[$i]['hometeam_id'] = $hteam_id;
					$data_list[$i]['hometeam_title'] = $hteam;
					// $data_list[$i]['hometeam_point'] = $hgoals;

					$data_list[$i]['awayteam_id'] = $ateam_id;
					$data_list[$i]['awayteam_title'] = $ateam;
					// $data_list[$i]['awayteam_point'] = $agoals;

					// $data_list[$i]['season'] = $rows->season_name2.'<br>'.$tournament_name;
					$data_list[$i]['kickoff'] = date('Y-m-d H:i:s', strtotime($rows->match_datetime.' +7 hour'));
					// $data_list[$i]['kickoff'] = date('Y-m-d H:i:s', strtotime($rows->match_datetime));

					if($referee_id > 0){

						$data_list[$i]['referee_id'] = $referee_id;
						$data_list[$i]['referee'] = $referee;
					}


					$data_list[$i]['week'] = $rows->week;
					$data_list[$i]['program_status'] = trim($rows->match_status);

					$data_list[$i]['create_date'] = $create_date;
					$data_list[$i]['create_by'] = 1;
					$data_list[$i]['status'] = 1;
					
					$res = $this->program_model->chk_fixid(intval($match_id));
					// Debug($res);
					// Debug($this->db->last_query());
					// Debug($data_list[$i]);
					// die();

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
						// Debug($this->db->last_query());
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

	public function load_base64img($src, $width = 25, $height = 15, $title = ''){

		$output = "<img class='base64image' width='".$width."' height='".$height."' alt='".$title."' title='".$title."' src='data:image/jpeg;base64, $src' />";
      	return $output;
	}
}