<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends CI_Controller {

	public function __construct(){
        parent::__construct();

		$this->load->database();

		$this->load->model('tournament_model');
		$this->load->model('standing_model');
		$this->load->model('fixtures_model');
		$this->load->model('team_model');
		$this->load->library('api');
		$this->load->helper('common');

		$this->tournament_id = $this->config->config['tournament_id'];
		$this->tournament = $this->config->config['tournament'];
        $this->season = $this->config->config['season'];
 		$this->date_start = $this->config->config['date_start'];
		$this->datetime_start = $this->config->config['datetime_start'];

		$this->catid = $this->config->config['catid_news'];
		// $this->catid = $this->config->config['catid_other'];

		$this->team_path = 'data/uploads/teamlogo/';
		$this->stadium_path = 'data/uploads/stadium/';
		$this->base_path = str_replace('application/controllers', '', __DIR__);
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


		$get_listnew = $this->api->get_listnew($this->catid, $curpage, $number_item);
		// Debug($get_listnew);
		// die();
        
		$hightlight = $this->hightlight($get_listnew);
		$list_news = $this->list_news($get_listnew);
		$list_paging = $this->list_paging($get_listnew, $curpage);

        // $program_today = $this->program_today($tournament_list, $fixtures_list);
		// $block_standing = $this->block_standing($tournament_list, $obj);

		$webtitle = 'ข่าวฟุตบอลโลก 2022';
		$page_published_time = date('c' , strtotime('2022-11-01'));
		$page_lastupdated_date = date('c');
		
		$keywords[] = 'ข่าวฟุตบอลโลก';
		$keywords[] = 'ข่าวฟุตบอลโลก 2022';
		$keywords[] = 'ข่าวนักเตะฟุตบอลโลก';
		$keywords[] = 'ทีมฟุตบอลโลก';

		$meta = array(
			'title' => $webtitle,
			'description' => 'ข่าวฟุตบอลโลก 2022 '._DESCRIPTION,
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
			"hightlight" => $hightlight,
			"list_news" => $list_news,
			"list_paging" => $list_paging,
			"css" => $css,
			"js" => $js,
			"page_lastupdated_date" => $page_lastupdated_date,
			"content_view" => 'news/list'
        );
        $this->load->view('template-euro', $data);
		// $this->load->view('home/index');
	}

	public function html()
	{
		$this->load->view('html/news-detail');
		// $this->load->view('html/news-list');
	}

	public function hightlight($get_listnew)
	{
		$icon_clip = $icon_gallery = '';
		$html = '';

		if(isset($get_listnew->data[0])){

			$rows = $get_listnew->data[0];

			$newsid = $rows->id;
			$title = StripTxt($rows->title);
			// $description = StripTxt($rows->description);
			// $short_description = $rows->short_description;
			$created_at = $rows->created_at;
			$updated_at = $rows->updated_at;
			$keyword = $rows->keyword;

			$categories = $rows->categories;

			$img_url = $rows->media->url;

			$news_created = date('Y-m-d H:i', strtotime($created_at));
			list($news_date, $news_time) = explode(' ', $news_created);
			$news_date_th = DateTH($news_date);

			// $icon_clip = '<div class="icon-float-big"><img src="./assets/images/icon-video.png" alt="video"/></div>';
			// $icon_gallery = '<div class="icon-float-big"><img src="./assets/images/icon-gallery.png" alt="gallery"/></div>';

			$img_cover_news = '<figure><img src="'.$img_url.'" alt="'.$title.'" title="'.$title.'" /></figure>';

			$link_news = base_url('news/detail/'.$newsid);

			$html .= '<a href="'.$link_news.'" target="_blank">
				'.$icon_clip.$icon_gallery.$img_cover_news.'
			</a>
			<a href="'.$link_news.'" target="_blank">
				<span>'.$news_date_th.' '.$news_time.'</span>
				<h2>'.$title.'</h2>
			</a>';

		}
		return $html;
	}

	public function list_news($get_listnew)
	{
		$icon_clip = $icon_gallery = '';
		$html = '';

		$number_item = count($get_listnew->data);
		for($i=1;$i<$number_item;$i++){

			$rows = $get_listnew->data[$i];
			$newsid = $rows->id;
			$title = StripTxt($rows->title);
			// $description = StripTxt($rows->description);
			// $short_description = $rows->short_description;
			$created_at = $rows->created_at;
			$updated_at = $rows->updated_at;
			$keyword = $rows->keyword;

			$categories = $rows->categories;

			$img_url = $rows->media->url;

			$news_created = date('Y-m-d H:i', strtotime($created_at));
			list($news_date, $news_time) = explode(' ', $news_created);
			$news_date_th = DateTH($news_date);

			// $icon_clip = '<div class="icon-float-big"><img src="./assets/images/icon-video.png" alt="video"/></div>';
			// $icon_gallery = '<div class="icon-float-big"><img src="./assets/images/icon-gallery.png" alt="gallery"/></div>';

			$img_cover_news = '<figure><img src="'.$img_url.'" alt="'.$title.'" title="'.$title.'" /></figure>';
			$link_news = base_url('news/detail/'.$newsid);

			$html .= '<a href="'.$link_news.'" target="_blank">
			'.$icon_clip.$icon_gallery.$img_cover_news.'
			<span>'.$news_date_th.' '.$news_time.'</span>
			<h3>'.$title.'</h3>
			</a>';

			if($i == 5){
				$html .= $this->load->view('widgets-rectangle2', null, true);
			}
		}

		if($i < 5){
			$html .= $this->load->view('widgets-rectangle2', null, true);
		}

		return $html;
	}

	public function list_paging($get_listnew, $curpage)
	{
		$html = '<div class="warpper paging bg-white">';

		$get_listnew->meta->current_page;
		$get_listnew->meta->total;
		$list_item = $get_listnew->meta->links;

		$number_item = count($list_item);
		$last_item = $number_item - 1;
		for($i=0;$i<$number_item;$i++){

			$news_page = base_url('news/list/'.$i);

			$rows = $list_item[$i];
			if($rows->label == '&laquo; Previous'){

				$html .= '<a href="'.base_url('news').'">&lsaquo;&lsaquo;</a>';

			}else if($rows->label == 'Next &raquo;'){

				$news_page = base_url('news/list/'.($i - 1));

				$html .= '<a href="'.$news_page.'">&rsaquo;&rsaquo;</a>';

			}else{

				if(trim($rows->active == 1))
					$html .= '<a href="#" class="pageactive">'.$rows->label.'</a>';
				else
					$html .= '<a href="'.$news_page.'">'.$rows->label.'</a>';				
			}
		}
		$html .= '</div>';

	  return $html;
	}

	public function program_today($tournament_list, $fixtures_list)
	{
		if(isset($fixtures_list[0]->match_datetime)){

			$match_today = date('Y-m-d H:i:s', strtotime($fixtures_list[0]->match_datetime.' +7 hour'));
			list($wc_date, $wc_time) = explode(' ', $match_today);

			$program_today = DateTH($wc_date);
		}
		$html = '<h2>โปรแกรม '.$program_today.'</h2>';

		$number_program = (count($fixtures_list) > 4) ? 4 : count($fixtures_list);
		for($i=0;$i<$number_program;$i++){

			$rows = $fixtures_list[$i];

			$match_id = $rows->match_id;
			$sport = $rows->sport;
			$tournament_id = $rows->tournament_id;
			$match_datetime = $rows->match_datetime;
			$static_id = $rows->static_id;
			$stadium_id = $rows->stadium_id;
			$stadium = $rows->stadium;
			$attendance = $rows->attendance;
			$referee = $rows->referee;
			$hteam_id = $rows->hteam_id;
			$hteam = $rows->hteam;
			$home_team = $rows->home_team;
			$hgoals = $rows->hgoals;
			$ateam_id = $rows->ateam_id;
			$ateam = $rows->ateam;
			$away_team = $rows->away_team;
			$agoals = $rows->agoals;

			// $match_datetime_th = date('H:i', strtotime($match_datetime.' +7 hour'));
			$match_datetime_th = date('H:i', strtotime($match_datetime));

			// $logo_team1 = '<img src="./assets/images/team-'.$hteam_id.'.png" alt="'.$home_team.'"/>';
			// $logo_team2 = '<img src="./assets/images/team-'.$ateam_id.'.png" alt="'.$away_team.'"/>';
			$logo_team1 = $logo_team2 = '';

			$link_teamhome = base_url('team/detail/'.$hteam_id);
			$link_teamaway = base_url('team/detail/'.$ateam_id);

			$html .= '<div>
				<span>
				<a href="'.$link_teamhome.'" target="_blank">'.$home_team.' '.$logo_team1.'</a> <strong><a href="#">VS</a></strong> 
				<a href="'.$link_teamaway.'" target="_blank">'.$logo_team2.' '.$away_team.'</a></span> <a href="#">'.$match_datetime_th.'</a> 
			</div>';

		}

		/*$html .= '<div> <span><a href="#" target="_blank">อังกฤษ <img src="./assets/images/demo-team-icon.png" alt=""/></a> <strong><a href="#" target="_blank">VS</a></strong> <a href="#" target="_blank"><img src="./assets/images/demo-team-icon.png" alt=""/> อังกฤษ</a></span> <a href="#" target="_blank">03 : 00</a> </div>

		<div> <span><a href="#" target="_blank">อังกฤษ <img src="./assets/images/demo-team-icon.png" alt=""/></a> <strong><a href="#" target="_blank">VS</a></strong> <a href="#" target="_blank"><img src="./assets/images/demo-team-icon.png" alt=""/> อังกฤษ</a></span> <a href="#" target="_blank">03 : 00</a> </div>

		<div> <span><a href="#" target="_blank">อังกฤษ <img src="./assets/images/demo-team-icon.png" alt=""/></a> <strong><a href="#" target="_blank">VS</a></strong> <a href="#" target="_blank"><img src="./assets/images/demo-team-icon.png" alt=""/> อังกฤษ</a></span> <a href="#" target="_blank">03 : 00</a> </div>';*/
		
		return $html;
	}

	public function block_standing($tournament_list, $data_list)
	{
		$arr_group = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H');

		$topscore = $this->block_topscore();

		$html = '<section class="warpper bg-blue score-salvo-home">
		<div>
		  <div class="score-table">
			<h4><a href="#" target="_blank">ตารางคะแนน</a></h4>
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

			$link_team = base_url('team/detail/'.$team_id);

			if($logo != '')
				$logo_team = '<img src="'.$logo.'" alt="'.$team_name.'" title="'.$team_name.'" />';
			else
				$logo_team = '';

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

	public function script_fancybox()
	{
		$html = '$(".fancybox").fancybox({
			helpers:  {
				thumbs : {
					width: 100,
					height: 60
				}
			}
		});';
		return $html;
	}

	public function news_detail($news_obj)
	{
		$html = $icon_clip = $icon_gallery = '';

		$rows = $news_obj;
		// Debug($rows);

		$newsid = $rows->id;
		$title = StripTxt($rows->title);
		$description = $rows->description;
		$short_description = $rows->short_description;
		$created_at = $rows->created_at;
		$updated_at = $rows->updated_at;
		$keyword = $rows->keyword;

		$categories = $rows->categories;
		$img_url = $rows->media->url;

		// $icon_clip = '<div class="icon-float-small"><img src="./assets/images/icon-video.png" alt="video"/></div>';
		// $icon_gallery = '<div class="icon-float-small"><img src="./assets/images/icon-gallery.png" alt="gallery"/></div>';
		$img_cover_news = '<img src="'.$img_url.'" alt="'.$title.'" title="'.$title.'" />';
		$link_news = base_url('news/detail/'.$newsid);

		// $rep_img = '<figure><a class="fancybox" rel="gallery" href="$1"><span>zoom</span><img src="$1" alt="'.strip_tags($title).'" data-caption="'.strip_tags($title).'" /></a></figure>';
		$rep_img = '<a class="fancybox" data-fancybox="gallery" href="$1"><span>zoom</span><img src="$1" alt="'.strip_tags($title).'" data-caption="'.strip_tags($title).'" title="'.strip_tags($title).'" /></a>';

		$description = preg_replace("/<img[^>]*src *= *[\"']?([^\"']*)[^>]+>/i", $rep_img, $description);

		$html .= $description;

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

	public function detail($newsid = 0)
	{
		$breadcrumb = $relate = $css = $js = array();
		$display_menu = $html = $wc_date_th = $relate_team = '';
		$team_id = $chk_relate_team = 0;
		$d = 25;
		$h = $m = 0;

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

		$tournament_list = $this->tournament_model->get_data($tournament_id);
		// Debug($tournament_list);
		// $thisround = $this->standing_model->get_max_week($tournament_id, '_xml_standing', $season);
		// $obj = $this->standing_model->get_xml_data(intval($tournament_id), $team_id, $thisround);
		// echo $this->db->last_query();
		// Debug($obj);
		// die();
		$get_teamlist = $this->team_model->get_data($tournament_id);

		if($datenow < $this->date_start){
			$sel_date = $this->date_start;
		}

		$news_obj = $this->api->get_newid($newsid);
		// Debug($news_obj);
		// die();

		//Program
		$fixtures_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id, $datebetween);

		//Result
		$result_list = $this->fixtures_model->get_data(0, 0, $this->tournament_id, $date_result);

		$news_updated = date('Y-m-d H:i', strtotime($news_obj->data->updated_at));
		list($news_date, $news_time) = explode(' ', $news_updated);
		$news_date_th = DateTH($news_date);
		
		$webtitle = $news_obj->data->title;
		$page_published_time = date('c' , strtotime($news_obj->data->created_at));
		$page_lastupdated_date = date('c', strtotime($news_obj->data->updated_at));
		$keywords = explode(',', $news_obj->data->keyword);

		$html = $this->news_detail($news_obj->data);

		//************ Relate Team **************/
		// Debug($keywords);
		// Debug($get_teamlist);
		$all_team = count($get_teamlist);
		$chk_relate_team = 0;
		if($keywords)
			foreach($keywords as $val){
				for($i=0;$i<$all_team;$i++){

					if((trim($val) == trim($get_teamlist[$i]->team_name)) || (trim($val) == trim('ทีมชาติ'.$get_teamlist[$i]->team_name))){
						$chk_relate_team = 1;
						$relate[] = $get_teamlist[$i]->team_id;
					}
				}
			}

		if($relate){
			foreach($relate as $val){
				$relate_team .= $this->relate_team($val);
			}
		}else
			$relate_team .= $this->relate_team();
		
		// echo $relate_team;
		// die();
		//************ Relate Team **************/

		$widgets_program = $this->widgets_program($fixtures_list);
		$widgets_result = $this->widgets_result($result_list);

		$view_tags = $this->view_tags($keywords);
		$script_fancybox = $this->script_fancybox();
		$social_block = $this->social_block($webtitle);

		$meta = array(
			'title' => URLTitle($webtitle),
			'description' => StripTxt($news_obj->data->short_description),
			'keywords' => $keywords,
			'page_image' => $news_obj->data->media->url,
			"page_published_time" => $page_published_time,
			"Author" => "Ballnaja",
			"Copyright" => "Ballnaja"
		);
		
        // $css[] = 'jquery.fancybox.css';
		// $css[] = 'jquery.fancybox-thumbs.css';
        $css[] = 'fancybox.css';

        // $js[] = 'jquery.fancybox.js';
		// $js[] = 'jquery.fancybox-thumbs.js';
        $js[] = 'fancybox.umd.js';

        $data = array(
			"meta" => $meta,
            "webtitle" => URLTitle($webtitle),
            "breadcrumb" => $breadcrumb,
			"menu" => $display_menu,
			"news_date_th" => $news_date_th,
			"news_time" => $news_time,
			"title" => StripTxt($webtitle),
			"html" => $html,
			"relate_team" => $relate_team,
			"view_tags" => $view_tags,
			"script_fancybox" => $script_fancybox,
			"social_block" => $social_block,
			"widgets_program" => $widgets_program,
			"widgets_result" => $widgets_result,
			"css" => $css,
			"js" => $js,
			"page_lastupdated_date" => $page_lastupdated_date,
			"content_view" => 'news/detail'
        );
        $this->load->view('template-euro', $data);
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

	public function load_base64img($src, $width = 100, $height = 0, $title = ''){
		//width:100px;height:100px;
		$output = "<img class='base64image round' width='".$width."' alt='".$title."' title='".$title."' src='data:image/jpeg;base64, $src' />";
      	return $output;
	}

	public function widgets_load_img($src, $width = 100, $height = 0, $title = ''){
		//width:100px;height:100px;
		$output = "<img class='base64image' width='".$width."' height='".$height."' alt='".$title."' title='".$title."' src='data:image/jpeg;base64, $src' />";
      	return $output;
	}

}
