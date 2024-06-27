<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dev extends CI_Controller {

	public function __construct(){
        parent::__construct();

		// $this->load->librery('session');
		// $this->load->librery('api_sp');
		// $this->load->helper('url');
		// $this->load->helper('html');
		date_default_timezone_set("Asia/Bangkok");
	}

	public function index()
	{
		// $this->load->librery('session');
		$this->load->view('dev/grid');
	}

	public function sport()
	{
		// $this->load->librery('session');
		$this->load->view('html/sport');
	}

	public function db()
	{
		$use_db = '';

		if($this->input->get('s') == 1){
			// if($_SERVER['HTTP_HOST'] == 'localhost')
			// 	$this->db = $this->load->database('default', TRUE);
			// else
			// 	$this->db = $this->load->database('production', TRUE);
			$this->load->database();

			Debug(ENVIRONMENT);
			
			echo '<br>HTTP_HOST : '.$_SERVER['HTTP_HOST'];
			echo '<br>Version : '.$this->db->conn_id->server_version;
			echo '<br>use_db : '.$use_db;

			
			
			Debug('Host_info:'.$this->db->conn_id->host_info);
			Debug('Server_info:'.$this->db->conn_id->server_info);
			Debug('Server_version:'.$this->db->conn_id->server_version);
			Debug('Client_info:'.$this->db->conn_id->client_info);
			Debug('Client_version:'.$this->db->conn_id->client_version);
			Debug('DB:'.$this->db->database);

			// Debug($this->db->conn_id->stat);
			// Debug($this->db);
		}
	}

	public function config()
	{
		if($this->input->get('s') == 1){
			Debug($this->config->config);
		}
	}
	
	public function env()
	{
		if($this->input->get('s') == 1){
			echo "<pre>";
			echo ENVIRONMENT;
			echo "</pre>";
		}
	}	

	public function info()
	{
		if($this->input->get('s') == 1){
			// Debug($_SERVER);
			phpinfo();
		}
	}

	public function sweetalert()
	{
		$this->load->view('tool/sweetalert2');
	}

	public function error()
	{
		// $this->load->view('errors/html/error_wc');
		$this->load->view('html/error');
	}

	public function fileupload()
	{
		Debug($this->input->post());
	}

	public function form_upload()
	{
		$this->load->helper('common');
		// $this->load->model('db_model');
		$curdate = date('Y-m-d');
		$res_chkfb = null;
		
		/*if($this->input->get('k') != ''){

			$decryption = base64_decode($this->input->get('k'));

			list($u, $r_date, $t) = explode(':', $decryption);
			// $str_encrypted = $this->Encrypted($username.':'.date("Y-m-d").':'.$token);
			$chk_fb = array(
				'amount' => '',
				'Date(create_date)' => $curdate,
				'token' => $t,
				'username' => $u
			);
			
			if($chk_fb)
				$res_chkfb = $this->db_model->get_content('_log_transection', '*', $chk_fb );
		}*/

		// Debug($this->db->last_query());
		// Debug($chk_fb);
		// Debug($res_chkfb);
		// die();
		$data = array(
			'webtitle' => 'Upload File',
			'other_list' => $res_chkfb,
		);
		$this->load->view('tool/fileupload', $data);
	}

	public function curdate()
	{
		$updated_at = date('Y-m-d H:i:s');

		echo "<br>updated_at:".$updated_at;

		$curdate_timestamp = strtotime("+5 hours", strtotime($updated_at));
		$curdate_thai = date('Y-m-d H:i', $curdate_timestamp);

		echo "<br>curdate_thai:".$curdate_thai;

		$yesterday = date('Y-m-d H:i', strtotime("-1 days"));
		echo "<br>yesterday:".$yesterday;
	}

	function chkdate(){
		//กำหนด timezone ประเทศไทย
		// date_default_timezone_set('Asia/Bangkok');

		echo "วันที่ปัจจุบัน : ".date('Y-m-d');
		echo "<hr>";
		echo "เดือนที่แล้ว";
		echo "<br>";
		echo "วันที่เริ่มต้น : " .date("Y-m-d", strtotime("first day of previous month"));
		echo "<br>";
		echo "วันสุดท้ายของเดือน : " .date("Y-m-d", strtotime("last day of previous month"));
		
		echo "<hr>";
		echo "เดือนปัจจุบัน";
		echo "<br>";
		echo "วันที่เริ่มต้น : " .date("Y-m-d", strtotime("first day of this month"));
		echo "<br>";
		echo "วันสุดท้ายของเดือน : " .date("Y-m-d", strtotime("last day of this month"));
		
		echo "<hr>";
		echo "เดือนหน้า";
		echo "<br>";
		echo "วันที่เริ่มต้น : " .date("Y-m-d", strtotime("first day of next month"));
		echo "<br>";
		echo "วันสุดท้ายของเดือน : " .date("Y-m-d", strtotime("last day of next month"));
		
		echo "<hr>";
		echo "เปลียนรูปแบบการแสดงผลวันที่เป็น ว/ด/ป";
		echo "<br>";
		echo "เช่น ".date("d/m/Y");
	}

	public function countdown()
	{

		$datenow = date('Y-m-d H:i:s');
		$wc2022 = date("Y-m-d H:i:s", strtotime('2022-11-20 23:00:00'));

		// $res_days = $this->datediff($datenow, $wc2022);
		// $res_hour = $this->hourdiff($datenow, $wc2022);
		// $res_min = $this->mindiff($datenow, $wc2022);

		// echo $res_days.'d '.$res_hour.'h '.$res_min.'m';

		echo "Date Diff = ".$this->DateDiff($datenow, $wc2022)."<br>";
		echo "Time Diff = ".$this->TimeDiff("00:00","19:00")."<br>";
		echo "Date Time Diff = ".$this->DateTimeDiff($wc2022, $wc2022)."<br>";
	}

	//Update Channel program
	public function get_data($sel_date = ''){
		$this->load->library('api');
		$this->load->model('program_model');

		$list_obj = null;

		if($sel_date == '')
			$sel_date = date('Y-m-d');

		$res = $this->api->get_program($sel_date);
		
		$date = $res->data[0]->date;
		$leagues = $res->data[0]->leagues;

		// Debug($res->data[0]->leagues);
		$num_league = count($res->data[0]->leagues);
		for($i=0;$i<$num_league;$i++){

			if($res->data[0]->leagues[$i]->league == 'ฟุตบอลโลก 2022'){

				$match_list = $res->data[0]->leagues[$i]->match;
				$num_match = count($match_list);
				for($j=0;$j<$num_match;$j++){

					$rows = $match_list[$j];

					$ballnaja_id = $rows->id;
					$match_name = $rows->match_name;
					$league_id = $rows->league_id;
					$datetime = $rows->datetime;
					$channels = $rows->channels;
					// $id = $rows->id;

					$num_channel = count($channels);
					for($k=0;$k<$num_channel;$k++){

						$link = 'https://www.ballnaja.com/live/'.$ballnaja_id.'/'.$channels[$k]->code;

						$list_obj[$k]['ballnaja_id'] = $rows->id;
						// $list_obj[$k]['s'] = $channels[$k]->s;
						$list_obj[$k]['code'] = $channels[$k]->code;
						$list_obj[$k]['logo'] = $channels[$k]->logo;
						$list_obj[$k]['name'] = $channels[$k]->name;
						$list_obj[$k]['match_name'] = $match_name;
						$list_obj[$k]['datetime'] = $datetime;
						$list_obj[$k]['link'] = $link;
						
						Debug($list_obj[$k]);

						$this->program_model->chkupdate_program_ballnaja($rows->id, $list_obj[$k]);
					}

				}

			}

		}

		// Debug($list_obj);

	}
	

	/*function datediff($start, $end) {

		$datediff = strtotime($end) - strtotime($start);

		// return floor($datediff / (60 * 60 * 24));
		return ($datediff / (60 * 60 * 24));
	}

	function hourdiff($start, $end) {

		$datediff = strtotime($end) - strtotime($start);

		return floor($datediff / (60 * 60 * 24 * 60));
	}

	function mindiff($start, $end) {

		$datediff = strtotime($end) - strtotime($start);

		return floor($datediff / (60 * 60 * 24 * 60 * 60));
	}*/

	function lineup($fix_id, $team_id){
		$this->load->model('match_model');
		$html = '';

		$match_lineup = $this->match_model->getmatch_lineup($fix_id, $team_id);
		// Debug($match_lineup);
		$number = count($match_lineup);
		// $number = 11;
		for($l=0;$l<$number;$l++){

			$data_rows = $match_lineup[$l];

			$player_name = $data_rows->player_name_th;

			$html .= $player_name.", ";
		}

		echo $html;

	}

	function topassists(){
		$this->load->model('topscore_model');
		$html = '';
		$data_store = null;
		$tournament_id = 1056;
		$cur_date = date('Y-m-d H:i:s');

		$get_standing = $this->topscore_model->get_topassists();
		// Debug($this->db->last_query());
		// Debug($get_standing);
		$number = count($get_standing);

		for($i=0;$i<$number;$i++){

			$data_rows = $get_standing[$i];

			$pos = $i + 1;
			unset($data_store);
			
			$data_store['player_id'] = $data_rows->profile_id;
			$data_store['player_name'] = $data_rows->name;
			$data_store['team_id'] = $data_rows->team_id;
			$data_store['team'] = $data_rows->team_name;
			$data_store['assists'] = $data_rows->sum_assists;
			// $data_store['lastupdate_date'] = $cur_date;
			
			Debug($data_store);
			// $html .= $player_name.", ";
			$this->topscore_model->store_xml_top($tournament_id, $pos, $data_store, '_xml_topassist');
			Debug($this->db->last_query());
		}

		echo $html;

	}

	function topscore(){
		$this->load->model('topscore_model');
		$html = '';
		$data_store = null;
		$tournament_id = 1056;
		$cur_date = date('Y-m-d H:i:s');

		$get_standing = $this->topscore_model->get_topscore();
		// Debug($this->db->last_query());
		// Debug($get_standing);
		$number = count($get_standing);

		for($i=0;$i<$number;$i++){

			$data_rows = $get_standing[$i];

			$pos = $i + 1;
			unset($data_store);
			
			$data_store['player_id'] = $data_rows->profile_id;
			$data_store['player_name'] = $data_rows->name;
			$data_store['team_id'] = $data_rows->team_id;
			$data_store['team'] = $data_rows->team_name;
			$data_store['goals'] = $data_rows->sum_goals;
			// $data_store['lastupdate_date'] = $cur_date;
			
			Debug($data_store);
			// $html .= $player_name.", ";
			// $this->topscore_model->store_xml_top($tournament_id, $pos, $data_store, '_xml_topscorers');
			// Debug($this->db->last_query());
		}

		echo $html;

	}

	function DateDiff($strDate1,$strDate2)
	{
		return (strtotime($strDate2) - strtotime($strDate1))/  ( 60 * 60 * 24 );  // 1 day = 60*60*24
	}

	function TimeDiff($strTime1,$strTime2)
	{
		return (strtotime($strTime2) - strtotime($strTime1))/  ( 60 * 60 ); // 1 Hour =  60*60
	}

	function DateTimeDiff($strDateTime1,$strDateTime2)
	{
		return (strtotime($strDateTime2) - strtotime($strDateTime1))/  ( 60 * 60 ); // 1 Hour =  60*60
	}


	function dateDiv($t1,$t2){ // ส่งวันที่ที่ต้องการเปรียบเทียบ ในรูปแบบ มาตรฐาน 2006-03-27 21:39:12

		$t1Arr = $this->splitTime($t1);
		$t2Arr = $this->splitTime($t2);
		
		$Time1=mktime($t1Arr["h"], $t1Arr["m"], $t1Arr["s"], $t1Arr["M"], $t1Arr["D"], $t1Arr["Y"]);
		$Time2=mktime($t2Arr["h"], $t2Arr["m"], $t2Arr["s"], $t2Arr["M"], $t2Arr["D"], $t2Arr["Y"]);
		$TimeDiv=abs($Time2-$Time1);

		// $Time["D"]=intval($TimeDiv/86400); // จำนวนวัน
		// $Time["H"]=intval(($TimeDiv%86400)/3600); // จำนวน ชั่วโมง
		// $Time["M"]=intval((($TimeDiv%86400)%3600)/60); // จำนวน นาที
		// $Time["S"]=intval(((($TimeDiv%86400)%3600)%60)); // จำนวน วินาที

		$Time[]=intval($TimeDiv/86400); // จำนวนวัน
		$Time[]=intval(($TimeDiv%86400)/3600); // จำนวน ชั่วโมง
		$Time[]=intval((($TimeDiv%86400)%3600)/60); // จำนวน นาที
		$Time[]=intval(((($TimeDiv%86400)%3600)%60)); // จำนวน วินาที

		return $Time;
	}

	function splitTime($time){ // เวลาในรูปแบบ มาตรฐาน 2006-03-27 21:39:12 
		$timeArr["Y"]= substr($time,2,2);
		$timeArr["M"]= substr($time,5,2);
		$timeArr["D"]= substr($time,8,2);
		$timeArr["h"]= substr($time,11,2);
		$timeArr["m"]= substr($time,14,2);
		$timeArr["s"]= substr($time,17,2);
		return $timeArr;
	}

	function show_diff(){

		$this->load->helper('common');

		$rdate  =  mktime(17,0,0,10,27,2022);
		$ftart  =  mktime(23,0,0,11,20,2022);

	 	$online = $rdate - $ftart;
		
		$day = intval( $online / 86400 ); // จำนวนวัน
		$hours = intval( ( $online % 86400 ) / 3600 ); // จำนวน ชั่วโมง
		$mins = intval( ( ( $online % 86400 ) % 3600 ) / 60 ); // จำนวน นาที
		$secs = intval( ( ( ( $online % 86400 ) % 3600) % 60 ) ); // จำนวน วินาที
	   
	   	print "$online --  $day d --  $hours h --  $mins m --  $secs s";

		//------------------------------ ตัวอย่างการใช้งาน
		$t1="2022-10-27 17:20:00";
		$t2="2022-11-20 23:00:00";

		print "<br> $t1 <br> $t2  <br>  ";
		$time = dateDiv($t1,$t2);
		print_r($time);
	}

	public function match_formation()
	{
		$this->load->view('dev/match-formation');
	}

	public function fancybox()
	{
		$this->load->view('tool/fancybox');
	}

	/*** REDIS ***/
	function set_key($page = 'dashboard', $section = 'index', $html = ''){
		$this->load->library('utils');

		$cache_key = 'page_'.$page.'_'.$section;

		$this->utils->setCacheRedis($cache_key, $html);

		echo "SET $cache_key success.";

	}

	function del_key($page = 'dashboard', $section = 'index'){
		$this->load->library('utils');

		$cache_key = 'page_'.$page.'_'.$section;

		$cache = $this->utils->deleteRedis($cache_key);

		echo "DELETE $cache_key success.";
	}

	function get_key($page = 'dashboard', $section = 'index'){
		$this->load->library('utils');

		$cache_key = 'page_'.$page.'_'.$section;

		if($this->input->get('cache_key')){
			$cache_key = $this->input->get('cache_key');
		}

		// $html = 'Test Set redis.';
		// $this->utils->setCacheRedis($cache_key, $html);

		$cache = $this->utils->getCache($cache_key);
		//$cache = false;

		echo "GET $cache_key<hr>";

		if($cache){
			if(is_object($cache))
				Debug($cache);
			else
				echo $cache;
		}else{
			echo "No Cache";
		}
	}

	function get_key_all(){
		$this->load->library('utils');

		$cache = $this->utils->getRedisAll();
		if($cache){
			Debug($cache);
		}else{
			echo "No Cache";
		}
	}

	function del_keyall(){
		$this->load->library('utils');

		$cache = $this->utils->getRedisAll();
		if($cache){
			// Debug($cache);
			foreach($cache as $num => $val){

				$cache = $this->utils->deleteRedis($val, 'noprefix');
				echo "DELETE $num => $val<br>";
			}

		}else{
			echo "No Cache";
		}
	}

	function getRedisinfo(){
		$this->load->library('utils');

		$res = $this->utils->getRedisinfo();
		Debug($res);
	}
}
