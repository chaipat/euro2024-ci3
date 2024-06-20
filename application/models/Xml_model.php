<?php
class Xml_model extends CI_Model {
	var $prefix;

	public function __construct(){
		parent::__construct();

		$this->load->database();
		$this->prefix = $this->db->dbprefix;

	}

	public function chkupdate_data($table, $field ='id', &$data = array()){

		$this->db->select('*');
		$this->db->from($this->prefix.$table);
		$this->db->where($field, $data[$field]);
		$query = $this->db->get();

		//Debug($this->db->last_query());
		$res = $query->result_array();
		if(!$res){
			$insert = $this->db->insert($table, $data);
			//Debug($this->db->last_query());
			return $insert;
		}else{

			$insert = $this->update($table, $field, $data);
			//Debug($this->db->last_query());
			return $insert;
		}
	}

	public function delete_data($table, $keyw = array()){

		if($keyw){
			foreach($keyw as $k => $v){
				$this->db->where($k, $v);
			}
		}
		$this->db->delete($table);
		//Debug($this->db->last_query());
	}

	public function chkupdate_array($table, $keyw = array(), &$data = array()){

		$this->db->select('*');
		$this->db->from($table);
		if($keyw){
			foreach($keyw as $k => $v){
				$this->db->where($k, $v);
			}
		}
		$query = $this->db->get();
		//Debug($this->db->last_query());
		$res = $query->result_array();

		if(!$res){
			$insert = $this->db->insert($table, $data);
			//Debug($this->db->last_query());
			return $insert;
		}
	}

	public function chkupdate_stage(&$data = array()){

		$this->db->select('*');
		$this->db->from('_xml_stage');
		$this->db->where('stage_id', $data['stage_id']);
		$query = $this->db->get();
		$res = $query->result_array();

		if(!$res){

			$insert = $this->db->insert('_xml_stage', $data);
			// Debug($this->db->last_query());
			// return $insert;
		}else{

			// $this->update('_xml_stage', 'stage_id', $data);
			// Debug($this->db->last_query());
		}
	}

	public function chkupdate_stadium(&$data = array()){

		$this->db->select('*');
		$this->db->from('_stadium');
		$this->db->where('stadium_id', $data['stadium_id']);
		$query = $this->db->get();
		$res = $query->result_array();

		if(!$res){

			$insert = $this->db->insert('_stadium', $data);
			// Debug($this->db->last_query());
			// return $insert;
		}else{

			$this->update('_stadium', 'stadium_id', $data);
			// Debug($this->db->last_query());
		}
	}

	public function chkupdate_team($table, $field ='team_id', &$data = array()){

		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($field, $data[$field]);
		$query = $this->db->get();
		$res = $query->result_array();

		if(!$res){

			$insert = $this->db->insert($table, $data);
			Debug($this->db->last_query());
			// return $insert;
		}else{

			$this->update($table, $field, $data);
			Debug($this->db->last_query());

		}
	}

	function chkupdate_program($match_id, $data, $showdebug = 0){

		$this->db->select('match_id');
    	$this->db->from($this->prefix.'_xml_match');
    	$this->db->where('match_id', $match_id);
    	$query = $this->db->get();

		$res = $query->result_array();
    	if(!$res){

			// $data['create_date'] = date('Y-m-d H:i:s');
			// $data['create_by'] = 1;
			
			// $this->insert_program($data);
			$insert = $this->db->insert($this->prefix.'_xml_match', $data);
			@Debug('Insert match '.$match_id.' '.$data['hteam'].' '.$data['hgoals'].' '.$data['agoals'].' '.$data['ateam'].' '.$data['match_datetime'].' '.$data['tournament_id']);
		}else{

			// $this->update_program($match_id, $data);
			$this->db->where('match_id', $match_id);
			$this->db->update($this->prefix.'_xml_match', $data);
			@Debug('Update match '.$match_id.' '.$data['hteam'].' '.$data['hgoals'].' '.$data['agoals'].' '.$data['ateam'].' '.$data['match_datetime'].' '.$data['tournament_id']);
		}
		// Debug($this->db->last_query());
		// return true;
	}

	function chkupdate_event($eventid, $data, $showdebug = 0){

		$this->db->select('eventid');
    	$this->db->from($this->prefix.'_xml_match_event');
    	$this->db->where('eventid', $eventid);
    	$query = $this->db->get();

		$res = $query->result_array();
    	if(!$res){

			$insert = $this->db->insert($this->prefix.'_xml_match_event', $data);
			Debug('Insert match event '.$eventid.' '.$data['type'].' '.$data['team'].' '.$data['player']);
		}else{

			$this->db->where('eventid', $eventid);
			$this->db->update($this->prefix.'_xml_match_event', $data);
			Debug('Update match event '.$eventid.' '.$data['type'].' '.$data['team'].' '.$data['player']);
		}
		// Debug($this->db->last_query());
		// return true;
	}

	function chkupdate_match_lineup($data, $showdebug = 0){

		$this->db->select('*');
    	$this->db->from($this->prefix.'_xml_match_lineup');
    	$this->db->where('match_id', $data['match_id']);
		$this->db->where('team_id', $data['team_id']);
		$this->db->where('player_id', $data['player_id']);

    	$query = $this->db->get();

		$res = $query->result_object();

		return $res;
	}

	function chkupdate_match_substitutions($data, $showdebug = 0){

		$this->db->select('*');
    	$this->db->from($this->prefix.'_xml_match_substitutions');
    	$this->db->where('match_id', $data['match_id']);
		$this->db->where('team_id', $data['team_id']);
		$this->db->where('on_id', $data['on_id']);

    	$query = $this->db->get();

		$res = $query->result_object();

		return $res;
	}

	function chkupdate_match_penalties($data, $showdebug = 0){

		$this->db->select('*');
    	$this->db->from($this->prefix.'_xml_match_penalties');
    	$this->db->where('match_id', $data['match_id']);
		// $this->db->where('team', $data['team']);
		// $this->db->where('playerid', $data['playerid']);

    	$query = $this->db->get();

		$res = $query->result_object();

		return $res;
	}

	function chkupdate_highlights($data, $showdebug = 0){

		$this->db->select('*');
    	$this->db->from($this->prefix.'_highlights');
    	$this->db->where('match_id', $data['match_id']);
		// $this->db->where('team', $data['team']);
		// $this->db->where('playerid', $data['playerid']);

    	$query = $this->db->get();

		$res = $query->result_object();

		return $res;
	}

	function chkupdate_xml_standing($tournament_id, $team_id, $data, $showdebug = 0){

		if($tournament_id > 0 && $team_id > 0){

			$this->db->select('*');
			$this->db->from($this->prefix.'_xml_standing');
			$this->db->where('tournament_id', $tournament_id);
			$this->db->where('team_id', $team_id);
			$query = $this->db->get();

			$res = $query->result_array();
			if(!$res){

				// $insert = $this->db->insert($this->prefix.'_xml_standing', $data);
				Debug('Insert _xml_standing '.$team_id.' '.$data['team_name']);
			}else{

				$this->db->where('tournament_id', $tournament_id);
				$this->db->where('team_id', $team_id);
				$this->db->update($this->prefix.'_xml_standing', $data);
				Debug('Update _xml_standing '.$team_id.' '.$data['team_name']);
			}
			// Debug($this->db->last_query());
			// return true;			
		}

	}

	public function chk_h2h($home_id, $away_id, $league_id, $data){

		$this->db->select('*');
		$this->db->from('_h2h');
		$this->db->where('home_id', $home_id);
		$this->db->where('away_id', $away_id);
		$this->db->where('league_id', $league_id);
		$query = $this->db->get();
		$res = $query->result_array();

		if(!$res){

			$insert = $this->db->insert('_h2h', $data);
			return $insert;
		}else{

			$this->db->where('home_id', $home_id);
			$this->db->where('away_id', $away_id);
			$this->db->where('league_id', $league_id);
			$this->db->update('_h2h', $data);
			return true;
		}
	}

	public function update($table, $field = 'id', &$data = array(), $showdebug = 1){

		$this->db->where($field, intval($data[$field]));
		unset($data[$field]);
		$this->db->update($table, $data);

		if($showdebug == 1) 
			Debug($this->db->last_query());

		return true;
	}

	public function ChkActive($table, $field ='team_id', &$data = array()){

		$this->db->select($field);
		$this->db->from($this->prefix.$table);
		$this->db->where($field, $data[$field]);
		//$this->db->where('status', 1);
		$query = $this->db->get();
		//$res = $query->result_array();
		//Debug($this->db->last_query());
		return $query->result_array();
	}

	public function import($table, &$data = array()){
		$res = $this->db->insert($table, $data);
		// if (!$res){
		//     $errNo   = $this->db->_error_number();
		//     $errMess = $this->db->_error_message();  
		// }
		// return array('ErrorN' => $errNo, 'ErrorM' => $errMess);
		return $res;
	}

	public function import_batch($table, &$data = array()){

		// Debug($this->prefix.$table);
		// Debug($data);
		// die();
		$res = $this->db->insert_batch($this->prefix.$table, $data);
		
		// if (!$res){
		//     $errNo   = $this->db->_error_number();
		//     $errMess = $this->db->_error_message();  
		// }
		// return array('ErrorN' => $errNo, 'ErrorM' => $errMess);
		return $res;
	}

	public function create_table($table_name = 'team', $data){

		//echo 'create table '.$table_name.'';
		//Debug($data);
		$field = $playerfield = $player_team = $datacoach = $player_in = $player_out = $stat_field = $scoring_minutes = $sidelined ='';
		$prefix = 'sp_xml_';
		$table_name = $prefix.$table_name;
		//Debug($data['name']);

		$table = $temp = array();
		//`id` int(11) NOT NULL AUTO_INCREMENT,
		//$table['sp_xml_team'] = '';

		switch($table_name){
			case 'sp_xml_team' :
				$table['sp_xml_player'] = 'CREATE TABLE `sp_xml_player` ( %s
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8"';
				$table['sp_xml_coach'] = 'CREATE TABLE `sp_xml_coach` ( %s
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8"';
				$table['sp_xml_transfers'] = 'CREATE TABLE `sp_xml_transfers` ( %s
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';

				$table['sp_xml_statistics'] = 'CREATE TABLE `sp_xml_statistics` (
					%s 
				  	PRIMARY KEY (`stat_id`)		  
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';
				$table['sp_xml_scoring_minutes'] = 'CREATE TABLE `sp_xml_scoring_minutes` (
		    		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		    		`team_id` BIGINT(20) NOT NULL,
		    		%s
					PRIMARY KEY (`id`)		  
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';

				$table['sp_xml_sidelined'] = 'CREATE TABLE `sp_xml_sidelined` (
		    		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		    		`team_id` BIGINT(20) NOT NULL,
		    		%s
		    		PRIMARY KEY (`id`)		  
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';

				if($data){
					foreach($data as $key => $val){
						if(is_array($val)){

							if($key == 'squad'){
								foreach($val as $key2 => $val2){
									//echo "$key2 => ".count($val2)."<br>"; //key2 = 'player_team'
									//for($i=0;$i<count($val2);$i++){
									$i = 0;
									$player_team = $val[$key2][$i];
									//Debug($player_team);
									foreach($player_team as $field_player => $data){
										//echo "$field_player => ".$data."<br>";
										if($field_player == 'id'){
											$playerfield .= ' `'.trim($field_player).'` int(11) NOT NULL AUTO_INCREMENT,';
											$playerfield .= ' `team_id` BIGINT(20) NOT NULL,';
										}else
											$playerfield .= ' `'.trim($field_player).'` varchar(150) NOT NULL,';
									}
									//Debug($playerfield);
									//echo "<hr>";
									//}//for
								}
								$table['sp_xml_player'] = sprintf($table['sp_xml_player'], $playerfield);
							}

							if($key == 'coach'){
								foreach($val as $key2 => $val2){
									//echo "$key2 => ".count($val2)."<br>"; //key2 = 'player_team'
									//for($i=0;$i<count($val2);$i++){
									//$i = 0;
									$coach = $val[$key2];
									foreach($coach as $field_player => $data){
										if($field_player == 'id'){
											$datacoach .= ' `'.trim($field_player).'` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,';
											$datacoach .= ' `team_id` BIGINT(20) NOT NULL,';
										}else
											$datacoach .= ' `'.trim($field_player).'` varchar(150) NOT NULL,';
									}

									//Debug($playerfield);
									//echo "<hr>";
									//}//for
								}
								$table['sp_xml_coach'] = sprintf($table['sp_xml_coach'], $datacoach);
							}

							if($key == 'transfers'){// transfers in
								foreach($val as $key2 => $val2){

									$i = 0;
									//for($i=0;$i<count($val2);$i++){
									$obj = $val[$key2][$i];
									//Debug($obj);
									foreach($obj as $field_player => $data){
										//echo "$field_player => ".$data."<br>";
										if($field_player == 'id'){
											$player_in .= ' `'.trim($field_player).'` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,';
											$player_in .= ' `parent_team` BIGINT(20) NOT NULL,';
										}else
											$player_in .= ' `'.trim($field_player).'` varchar(150) NOT NULL,';
									}
									//$player_in .= ' `from` varchar(150) NOT NULL,';
									//Debug($playerfield);
									//echo "<hr>";
									//}//for
								}
								$table['sp_xml_transfers'] = sprintf($table['sp_xml_transfers'], $player_in);
							}

							if($key == 'statistics'){
								//Debug($val);
								//die();
								$stat_field = ' `stat_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,';
								foreach($val as $key2 => $val2){
									//echo "$key2 => ".count($val2)."<br>";
									switch($key2){
										case "team_id" :
											$stat_field .= ' `'.trim($key2).'` BIGINT(20) NOT NULL,';
											break;
										case "rank" :
											$stat_field .= ' `'.trim($key2).'` varchar(5) NOT NULL,';
											break;
										case "win" :
										case "draw" :
										case "lost" :
										case "goals_for" :
										case "goals_against" :
										case "clean_sheet" :
										case "avg_goals_per_game_scored" :
										case "avg_goals_per_game_conceded" :
										case "avg_first_goal_scored" :
										case "avg_first_goal_conceded" :
										case "failed_to_score" :
											foreach($val2 as $key3 => $val3){
												$stat_field .= ' `'.trim($key2.'_'.$key3).'` varchar(5) NOT NULL,';
											}
											break;
										case "scoring_minutes" :

											//Debug($val);
											$num = count($val2);
											//Debug($num);
											if(is_array($val2)){
												$i = 0;
												//for($i=0;$i<$num;$i++){
												foreach($val2[$i] as $key3 => $val3){
													$scoring_minutes .= ' `'.trim($key3).'` varchar(5) NOT NULL,';
												}
												//$val2[$i]['min'];
												//}
											}
											$table['sp_xml_scoring_minutes'] = sprintf($table['sp_xml_scoring_minutes'], $scoring_minutes);
											//echo '<hr>'.$table['sp_xml_scoring_minutes'];
											//$this->db->query($table['sp_xml_scoring_minutes']);
											break;
										default :
											$stat_field .= ' `'.trim($key2).'` varchar(5) NOT NULL,';
											break;
									}
								}
								//Debug($stat_field);
								$table['sp_xml_statistics'] = sprintf($table['sp_xml_statistics'], $stat_field);
								//echo '<hr>'.$table['sp_xml_statistics'];
								//$this->db->query($table['sp_xml_statistics']);
							}

							//sidelined
							if($key == 'sidelined'){
								//Debug($val);
								foreach($val[0] as $key2 => $val2){
									//echo "$key2 => $val2";
									$sidelined .= ' `'.trim($key2).'` varchar(150) NOT NULL,';
								}
								$table['sp_xml_sidelined'] = sprintf($table['sp_xml_sidelined'], $sidelined);
								//echo '<hr>'.$table['sp_xml_sidelined'];
								//$this->db->query($table['sp_xml_sidelined']);
							}

						}else{
							if($key != 'venue_image' && $key != 'image'){
								//echo "$key => $val<br>";
								$field .= ' `'.trim($key).'` varchar(150) NOT NULL,';
							}
						}
					}
				}
				//Debug($table);

				//DROP TABLE IF EXISTS `$table_name`;
				/*$sql = "CREATE TABLE `".$table_name."` (
                  ".$field."
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
                echo $sql;*/
				//$this->db->query($sql);

				//$table['sp_xml_team'] = sprintf($table['sp_xml_team'], $field);
				//$table['sp_xml_team'] = $sql;

				/*UNIQUE KEY `email` (`admin_email`),
				  KEY `index` (`admin_type_id`,`admin_id`,`admin_email`,`status`)*/

				//foreach($table as $key => $val){
				//echo $val."<hr>";
				//$this->db->query($val);
				//}
				//SaveFile($data, $filename);
				break;
			case 'sp_xml_h2h_top50' :
				/*********Create Script Table********/
				$table[$table_name] = 'CREATE TABLE `'.$table_name.'` (
		    		`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,		    		
		    		%s
		    		PRIMARY KEY (`id`)
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';
				//Debug($data);
				$num = count($data);
				$i = 0;
				foreach($data[$i] as $key => $val){
					$field .= ' `'.trim($key).'` varchar(150) NOT NULL,';
				}
				$table[$table_name] = sprintf($table[$table_name], $field);
				//Debug($table[$table_name]);
				//$this->db->query($table[$table_name]);
				/*********Store Data********/
				break;
		}
	}

	public function getSelect_Soccernew($default = '', $name = "link_api"){
		//$language = $this->lang->language;
		//$first = "--- ".$language['please_select'].$language['match']." ---";
		$opt = array();
		$rows = $this->Data_Soccernew();
		//$opt[]	= makeOption('',$first);
		if($default == '') $default = 'http://www.goalserve.com/getfeed/a15771d2777e49e5a476e26ce2d36eba/soccernew/home';
		foreach($rows as $key => $val){
			$opt[] = makeOption($val, $key);
		}
		return selectList($opt, $name, 'class="form-control" id="link_api"', 'value', 'text', $default);
	}

	public function Data_Soccernew(){
		$arr = array();
		$arr['ย้อนหลัง 7 วัน'] = 'http://www.goalserve.com/getfeed/a15771d2777e49e5a476e26ce2d36eba/soccernew/d-7';
		$arr['ย้อนหลัง 6 วัน'] = 'http://www.goalserve.com/getfeed/a15771d2777e49e5a476e26ce2d36eba/soccernew/d-6';
		$arr['ย้อนหลัง 5 วัน'] = 'http://www.goalserve.com/getfeed/a15771d2777e49e5a476e26ce2d36eba/soccernew/d-5';
		$arr['ย้อนหลัง 4 วัน'] = 'http://www.goalserve.com/getfeed/a15771d2777e49e5a476e26ce2d36eba/soccernew/d-4';
		$arr['ย้อนหลัง 3 วัน'] = 'http://www.goalserve.com/getfeed/a15771d2777e49e5a476e26ce2d36eba/soccernew/d-3';
		$arr['ย้อนหลัง 2 วัน'] = 'http://www.goalserve.com/getfeed/a15771d2777e49e5a476e26ce2d36eba/soccernew/d-2';
		$arr['เมื่อวานนี้'] = 'http://www.goalserve.com/getfeed/a15771d2777e49e5a476e26ce2d36eba/soccernew/d-1';
		$arr['วันนี้'] = 'http://www.goalserve.com/getfeed/a15771d2777e49e5a476e26ce2d36eba/soccernew/home';
		$arr['พรุ่งนี้'] = 'http://www.goalserve.com/getfeed/a15771d2777e49e5a476e26ce2d36eba/soccernew/d1';
		$arr['อีก 2 วัน'] = 'http://www.goalserve.com/getfeed/a15771d2777e49e5a476e26ce2d36eba/soccernew/d2';
		$arr['อีก 3 วัน'] = 'http://www.goalserve.com/getfeed/a15771d2777e49e5a476e26ce2d36eba/soccernew/d3';
		$arr['อีก 4 วัน'] = 'http://www.goalserve.com/getfeed/a15771d2777e49e5a476e26ce2d36eba/soccernew/d4';
		$arr['อีก 5 วัน'] = 'http://www.goalserve.com/getfeed/a15771d2777e49e5a476e26ce2d36eba/soccernew/d5';
		$arr['อีก 6 วัน'] = 'http://www.goalserve.com/getfeed/a15771d2777e49e5a476e26ce2d36eba/soccernew/d6';
		$arr['อีก 7 วัน'] = 'http://www.goalserve.com/getfeed/a15771d2777e49e5a476e26ce2d36eba/soccernew/d7';
		return $arr;
	}

	public function get_team($url_feed, $query = 0){

		$arr = $temp = array();
		$xmlelement =  simplexml_load_file($url_feed);
		if($xmlelement)
			foreach($xmlelement as $key1 => $val1){
				//echo "<hr>$key1 => $val1<br>";
				$arr['team_id'] = (int)$val1['id'];
				$arr['national_team'] = ($val1['is_national_team'] == 'False') ? 0 : 1;
				if($key1 == 'team'){
					foreach($val1 as $key2 => $val2){
						//echo "$key2 => $val2<br>";
						//Debug(count($val2->player));
						if(count($val2->player) > 0){
							//echo "key2=$key2";
							//Debug($val2);
						}

						//if($key2 == 'squad' || $key2 == 'coach' || $key2 == 'transfers'){
						//echo 'venue_image=<img src="'.$val2.'">';
						//foreach($val2 as $key3 => $val3){
						switch ($key2){
							case 'name':  $arr['name'] = (string)$val2;  break;
							case 'fullname':  $arr['fullname'] = (string)$val2;  break;
							case 'country':  $arr['country'] = (string)$val2;  break;
							case 'founded':  $arr['founded'] = (string)$val2;  break;
							case 'venue_name':  $arr['venue_name'] = (string)$val2;  break;
							case 'venue_id':  $arr['venue_id'] = (string)$val2;  break;
							case 'venue_capacity':  $arr['venue_capacity'] = (string)$val2;  break;
							case 'venue_image':  $arr['venue_image'] = (string)$val2;  break;
							case 'image':  $arr['image'] = (string)$val2;  break;
							//case 'venue_surface':  $venue_surface = $val2;  break;
							case 'venue_address':  $arr['venue_address'] = (string)$val2;  break;
							case 'leagues':
								$a=0;
								foreach($val2 as $key3 => $val3){
									$arr['leagues'][$a] = (string)$val3;
									$a++;
								}
								break;
							case 'squad':
								$squad = $val2;
								foreach($squad as $key3 => $val3){
									switch ($key3){
										case 'player':
											//$player_atr = $xmlelement->team->squad->player->attributes();
											$all_player = $xmlelement->team->squad->player;
											//echo "squad->player<br>";
											//Debug($all_player);
											for($i=0;$i<count($all_player);$i++){
												$player_atr = $all_player[$i]->attributes();
												$arr['squad']['player_team'][$i]['team_id'] = (string)$arr['team_id'];
												$arr['squad']['player_team'][$i]['id'] = (string)$player_atr->id;
												$arr['squad']['player_team'][$i]['name'] = (string)$player_atr->name;
												$arr['squad']['player_team'][$i]['number'] = (string)$player_atr->number;
												$arr['squad']['player_team'][$i]['age'] = (string)$player_atr->age;
												$arr['squad']['player_team'][$i]['position'] = (string)$player_atr->position;
												$arr['squad']['player_team'][$i]['injured'] = (string)$player_atr->injured;
												$arr['squad']['player_team'][$i]['minutes'] = (string)$player_atr->minutes;
												$arr['squad']['player_team'][$i]['appearences'] = (string)$player_atr->appearences;
												$arr['squad']['player_team'][$i]['lineups'] = (string)$player_atr->lineups;
												$arr['squad']['player_team'][$i]['substitute_in'] = (string)$player_atr->substitute_in;
												$arr['squad']['player_team'][$i]['substitute_out'] = (string)$player_atr->substitute_out;
												$arr['squad']['player_team'][$i]['substitutes_on_bench'] = (string)$player_atr->substitutes_on_bench;
												$arr['squad']['player_team'][$i]['goals'] = (string)$player_atr->goals;
												$arr['squad']['player_team'][$i]['assists'] = (string)$player_atr->assists;
												$arr['squad']['player_team'][$i]['yellowcards'] = (string)$player_atr->yellowcards;
												$arr['squad']['player_team'][$i]['yellowred'] = (string)$player_atr->yellowred;
												$arr['squad']['player_team'][$i]['redcards'] = (string)$player_atr->redcards;
											}
											break;
									}
								}
								break;
							case 'coach':
								$coach_arr = $xmlelement->team->coach;
								for($i=0;$i<count($coach_arr);$i++){
									$coach = $coach_arr[$i]->attributes();
									$arr['coach'][$i]['id'] = (string)$coach->id;
									$arr['coach'][$i]['team_id'] = (string)$arr['team_id'];
									$arr['coach'][$i]['name'] = (string)$coach->name;
								}
								break;
							case 'transfers':
								//$transfers = $xmlelement->team->transfers;
								$transfersin = $xmlelement->team->transfers->in->player;
								for($i=0;$i<count($transfersin);$i++){
									$player_atr = $transfersin[$i]->attributes();
									$arr['transfers']['player_in'][$i]['team_id'] = (string)$arr['team_id'];
									$arr['transfers']['player_in'][$i]['player_id'] = (string)$player_atr->id;
									$arr['transfers']['player_in'][$i]['name'] = (string)$player_atr->name;
									$arr['transfers']['player_in'][$i]['date'] = (string)$player_atr->date;
									$arr['transfers']['player_in'][$i]['age'] = (string)$player_atr->age;
									$arr['transfers']['player_in'][$i]['position'] = (string)$player_atr->position;
									$arr['transfers']['player_in'][$i]['from'] = (string)$player_atr->from;
									$arr['transfers']['player_in'][$i]['team_id'] = (string)$player_atr->team_id;
									$arr['transfers']['player_in'][$i]['type'] = (string)$player_atr->type;
								}

								$transfersout = $xmlelement->team->transfers->out->player;
								for($i=0;$i<count($transfersout);$i++){
									$player_atr = $transfersout[$i]->attributes();
									$arr['transfers']['player_out'][$i]['team_id'] = (string)$arr['team_id'];
									$arr['transfers']['player_out'][$i]['player_id'] = (string)$player_atr->id;
									$arr['transfers']['player_out'][$i]['name'] = (string)(string)$player_atr->name;
									$arr['transfers']['player_out'][$i]['date'] = (string)$player_atr->date;
									$arr['transfers']['player_out'][$i]['age'] = (string)$player_atr->age;
									$arr['transfers']['player_out'][$i]['position'] = (string)$player_atr->position;
									$arr['transfers']['player_out'][$i]['to'] = (string)$player_atr->to;
									$arr['transfers']['player_out'][$i]['team_id'] = (string)$player_atr->team_id;
									$arr['transfers']['player_out'][$i]['type'] = (string)$player_atr->type;
								}
								break;

							case 'statistics':
								//$arr['statistics'] = $val2;
								//Debug($val2);
								if($val2)
									foreach($val2 as $stat_key => $stat_val){
										//echo "($stat_key => $stat_val)<br>";
										switch($stat_key){
											case "rank" :
												$arr['statistics']['team_id'] = (string)$arr['team_id'];
												$arr['statistics'][$stat_key] = (string)$stat_val['total'];
												break;
											case "win" :
											case "draw" :
											case "lost" :
											case "goals_for" :
											case "goals_against" :
											case "clean_sheet" :
											case "avg_goals_per_game_scored" :
											case "avg_goals_per_game_conceded" :
											case "avg_first_goal_scored" :
											case "avg_first_goal_conceded" :
											case "failed_to_score" :
												//$arr['statistics'][$stat_key]['total'] = (string)$stat_val['total'];
												//$arr['statistics'][$stat_key]['home'] = (string)$stat_val['home'];
												//$arr['statistics'][$stat_key]['away'] = (string)$stat_val['away'];
												$arr['statistics'][$stat_key.'_total'] = (string)$stat_val['total'];
												$arr['statistics'][$stat_key.'_home'] = (string)$stat_val['home'];
												$arr['statistics'][$stat_key.'_away'] = (string)$stat_val['away'];
												break;
											case "scoring_minutes" :

												//Debug($stat_val);
												$num = count($stat_val);
												//Debug($num);
												$scoring_minutes = $xmlelement->team->statistics->scoring_minutes;
												unset($temp);
												//Debug($scoring_minutes);
												for($i=0;$i<$num;$i++){
													$temp = $scoring_minutes->period[$i]->attributes();
													$arr['statistics'][$stat_key][$i]['team_id'] = (string)$arr['team_id'];
													$arr['statistics'][$stat_key][$i]['min'] = (string)$temp['min'];
													$arr['statistics'][$stat_key][$i]['pct'] = (string)$temp['pct'];
													$arr['statistics'][$stat_key][$i]['count'] = (string)$temp['count'];
												}
												break;
										}
									}
								//echo "<hr>";
								//Debug($arr['statistics']);
								break;
							case 'sidelined':

								$sidelined = $val2;
								//Debug($sidelined);
								foreach($sidelined as $key3 => $val3){
									//echo "$key3 => $val3<br>";
									switch ($key3){
										case 'player':
											$all_player = $xmlelement->team->sidelined->player;
											//echo "key2=$key2, key3=$key3";
											//Debug($all_player);
											for($i=0;$i<count($all_player);$i++){
												//echo "[$i]";
												$player_atr = $all_player[$i]->attributes();
												$arr['sidelined'][$i]['team_id'] = (string)$arr['team_id'];
												$arr['sidelined'][$i]['player_id'] = (string)$player_atr->id;
												$arr['sidelined'][$i]['name'] = (string)$player_atr->name;
												$arr['sidelined'][$i]['description'] = (string)$player_atr->description;
												$arr['sidelined'][$i]['startdate'] = (string)$player_atr->startdate;
												$arr['sidelined'][$i]['enddate'] = (string)$player_atr->enddate;
											}
											break;
									}
								}

								break;
						}
						//} foreach
						//}
					}
				}
			}
		return $arr;
	}

	//soccerfixtures/worldcup/WorldCup
	public function get_fixtures_results($url_feed, $query = 0 ,$opt = 1){
		$arr = $temp = $field = array();
		$xmlelement =  simplexml_load_file($url_feed);
		// Debug($xmlelement);
		// die();
		if($xmlelement){

			if($opt == 1){
				foreach($xmlelement as $key1 => $val1){
					//echo "<hr>$key1 => $val1<br>";
					//Debug($val1);
					$arr['country'] = (string)$xmlelement['country'];

					if($key1 == 'tournament'){

						$arr['id'] = (string)$val1['id'];
						$arr['league'] = (string)$val1['league'];
						$arr['season'] = (string)$val1['season'];

						//$arr['stage_id'] = (string)$val1['stage_id'];
						// Debug($arr);
						// Debug($val1->stage);
						// die();

						$num_stage = count($val1->stage);
						// echo $num_stage;
						for($s=0;$s<$num_stage;$s++){

							if(isset($val1->stage[$s])){

								$arr['stage'][$s]['stage_name'] = (string)$val1->stage[$s]['name'];
								$arr['stage'][$s]['stage_round'] = (string)$val1->stage[$s]['round'];
								$arr['stage'][$s]['gid'] = (string)$val1->stage[$s]['gid'];
								$arr['stage'][$s]['stage_id'] = (string)$val1->stage[$s]['stage_id'];
								$arr['stage'][$s]['is_current'] = (string)$val1->stage[$s]['is_current'];
								// Debug($arr);
								// die();
								$stage[$s]['stage_id'] = $arr['stage'][$s]['stage_id'];
								$stage[$s]['stage_name'] = $arr['stage'][$s]['stage_name'];
								$stage[$s]['stage_round'] = $arr['stage'][$s]['stage_round'];

								if($query == 1){
									$this->chkupdate_stage($stage[$s]);
								}
							}

							if(isset($val1->stage[$s]->week)){

								// Debug($val1->stage[$s]->week);
								
								
								$allweek = count($val1->stage[$s]->week);
								// echo "allweek ($allweek)<br>";
								// Debug($val1->stage[$s]->week);

								$match_number = 0;
								for($i=0;$i<$allweek;$i++){

									$week_number = (string)$val1->stage[$s]->week[$i]['number'];
									// echo "week_number ($week_number)<br>";

									$all_match = count($val1->stage[$s]->week[$i]->match);
									// echo $all_match;
									// Debug($val1->stage[$s]->week->match);
									// die();

									// unset($arr['stage'][$s]['match']);

									// $all_match += $match_number;

									for($j=0;$j<$all_match;$j++){

										$match_arr = $val1->stage[$s]->week[$i]->match[$j];
										// Debug($match_arr);

										$arr['stage'][$s]['week'][$i]['match'][$j]['date'] = (string)$match_arr['date'];
										$arr['stage'][$s]['week'][$i]['match'][$j]['time'] = (string)$match_arr['time'];
										$arr['stage'][$s]['week'][$i]['match'][$j]['status'] = (string)$match_arr['status'];
										$arr['stage'][$s]['week'][$i]['match'][$j]['venue'] = (string)$match_arr['venue'];
										$arr['stage'][$s]['week'][$i]['match'][$j]['venue_id'] = (string)$match_arr['venue_id'];
										$arr['stage'][$s]['week'][$i]['match'][$j]['venue_city'] = (string)$match_arr['venue_city'];
										$arr['stage'][$s]['week'][$i]['match'][$j]['static_id'] = (string)$match_arr['static_id'];
										$arr['stage'][$s]['week'][$i]['match'][$j]['group_id'] = (string)$match_arr['groupId'];
										$arr['stage'][$s]['week'][$i]['match'][$j]['id'] = (string)$match_arr['id'];
										$arr['stage'][$s]['week'][$i]['match'][$j]['week'] = intval($week_number);

										$localteam = $match_arr->localteam;
										
										$arr['stage'][$s]['week'][$i]['match'][$j]['localteam']['id'] = (string)$localteam['id'];
										$arr['stage'][$s]['week'][$i]['match'][$j]['localteam']['name'] = (string)$localteam['name'];
										$arr['stage'][$s]['week'][$i]['match'][$j]['localteam']['score'] = (string)$localteam['score'];
										$arr['stage'][$s]['week'][$i]['match'][$j]['localteam']['ft_score'] = (string)$localteam['ft_score'];
										$arr['stage'][$s]['week'][$i]['match'][$j]['localteam']['et_score'] = (string)$localteam['et_score'];
										$arr['stage'][$s]['week'][$i]['match'][$j]['localteam']['pen_score'] = (string)$localteam['pen_score'];

										$visitorteam = $match_arr->visitorteam;

										$arr['stage'][$s]['week'][$i]['match'][$j]['visitorteam']['id'] = (string)$visitorteam['id'];
										$arr['stage'][$s]['week'][$i]['match'][$j]['visitorteam']['name'] = (string)$visitorteam['name'];
										$arr['stage'][$s]['week'][$i]['match'][$j]['visitorteam']['score'] = (string)$visitorteam['score'];
										$arr['stage'][$s]['week'][$i]['match'][$j]['visitorteam']['ft_score'] = (string)$visitorteam['ft_score'];
										$arr['stage'][$s]['week'][$i]['match'][$j]['visitorteam']['et_score'] = (string)$visitorteam['et_score'];
										$arr['stage'][$s]['week'][$i]['match'][$j]['visitorteam']['pen_score'] = (string)$visitorteam['pen_score'];

										$halftime = $match_arr->halftime;
										$arr['stage'][$s]['week'][$i]['match'][$j]['halftime'] = (string)$halftime->score;

										//Goals
										if(isset($match_arr->goals->goal)){

											$allgoals = count($match_arr->goals->goal);
											//Debug($allgoals);
											for($k=0;$k<$allgoals;$k++){

												$goals = $match_arr->goals->goal[$k];
												// Debug($goals);

												$arr['stage'][$s]['week'][$i]['match'][$j]['goals'][$k]['team'] = (string)$goals['team'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['goals'][$k]['minute'] = (string)$goals['minute'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['goals'][$k]['player'] = (string)$goals['player'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['goals'][$k]['score'] = (string)$goals['score'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['goals'][$k]['playerid'] = (string)$goals['playerid'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['goals'][$k]['assist'] = (string)$goals['assist'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['goals'][$k]['assistid'] = (string)$goals['assistid'];
											}
										}

										//LineUP Home
										if(isset($match_arr->lineups->localteam->player)){

											$allplayer = count($match_arr->lineups->localteam->player);
											//Debug($allplayer);
											for($k=0;$k<$allplayer;$k++){

												$lineups = $match_arr->lineups->localteam->player[$k];
												// Debug($lineups);

												$arr['stage'][$s]['week'][$i]['match'][$j]['lineups']['home_player'][$k]['id'] = (string)$lineups['id'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['lineups']['home_player'][$k]['number'] = (string)$lineups['number'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['lineups']['home_player'][$k]['name'] = (string)$lineups['name'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['lineups']['home_player'][$k]['booking'] = (string)$lineups['booking'];
											}										
										}
										// die();

										//LineUP Away
										if(isset($match_arr->lineups->visitorteam->player)){
											
											$allplayer = count($match_arr->lineups->visitorteam->player);
											//Debug($allplayer);
											for($k=0;$k<$allplayer;$k++){

												$lineups = $match_arr->lineups->visitorteam->player[$k];

												$arr['stage'][$s]['week'][$i]['match'][$j]['lineups']['away_player'][$k]['id'] = (string)$lineups['id'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['lineups']['away_player'][$k]['number'] = (string)$lineups['number'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['lineups']['away_player'][$k]['name'] = (string)$lineups['name'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['lineups']['away_player'][$k]['booking'] = (string)$lineups['booking'];
											}
										}

										//Substitutions Home
										if(isset($match_arr->substitutions->localteam->substitution)){
										
											$allplayer = count($match_arr->substitutions->localteam->substitution);
											//Debug($allplayer);
											for($k=0;$k<$allplayer;$k++){

												$sub = $match_arr->substitutions->localteam->substitution[$k];

												$arr['stage'][$s]['week'][$i]['match'][$j]['substitutions']['home_player'][$k]['player_in_id'] = (string)$sub['player_in_id'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['substitutions']['home_player'][$k]['player_in_number'] = (string)$sub['player_in_number'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['substitutions']['home_player'][$k]['player_in_name'] = (string)$sub['player_in_name'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['substitutions']['home_player'][$k]['player_in_booking'] = (string)$sub['player_in_booking'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['substitutions']['home_player'][$k]['player_out_name'] = (string)$sub['player_out_name'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['substitutions']['home_player'][$k]['minute'] = (string)$sub['minute'];
											}
										}

										//Substitutions Away
										if(isset($match_arr->substitutions->visitorteam->substitution)){
											
											$allplayer = count($match_arr->substitutions->visitorteam->substitution);
											for($k=0;$k<$allplayer;$k++){

												$sub = $match_arr->substitutions->visitorteam->substitution[$k];
												
												$arr['stage'][$s]['week'][$i]['match'][$j]['substitutions']['away_player'][$k]['player_in_id'] = (string)$sub['player_in_id'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['substitutions']['away_player'][$k]['player_in_number'] = (string)$sub['player_in_number'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['substitutions']['away_player'][$k]['player_in_name'] = (string)$sub['player_in_name'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['substitutions']['away_player'][$k]['player_in_booking'] = (string)$sub['player_in_booking'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['substitutions']['away_player'][$k]['player_out_name'] = (string)$sub['player_out_name'];
												$arr['stage'][$s]['week'][$i]['match'][$j]['substitutions']['away_player'][$k]['minute'] = (string)$sub['minute'];
											}
										}

										//Referee
										if(isset($match_arr->referee)){
											
											$arr['stage'][$s]['week'][$i]['match'][$j]['referee_id'] = (string)$match_arr->referee['id'];
											$arr['stage'][$s]['week'][$i]['match'][$j]['referee_name'] = (string)$match_arr->referee['name'];

										}

										// Debug($arr['stage'][$s]['week'][$i]['match'][$j]);
										$match_number++;
									}

									// Debug($arr['stage'][$s]['week'][$i]['match']);
									
								}

							}else{

								//$val1->stage[$s]->match
								$all_match = count($val1->stage[$s]->match);
								for($i=0;$i<$all_match;$i++){

									$match_arr = $val1->stage[$s]->match[$i];
									// Debug($match_arr);

									$arr['stage'][$s]['match'][$i]['date'] = (string)$match_arr['date'];
									$arr['stage'][$s]['match'][$i]['time'] = (string)$match_arr['time'];
									$arr['stage'][$s]['match'][$i]['status'] = (string)$match_arr['status'];
									$arr['stage'][$s]['match'][$i]['venue'] = (string)$match_arr['venue'];
									$arr['stage'][$s]['match'][$i]['venue_id'] = (string)$match_arr['venue_id'];
									$arr['stage'][$s]['match'][$i]['static_id'] = (string)$match_arr['static_id'];
									$arr['stage'][$s]['match'][$i]['id'] = (string)$match_arr['id'];

									$localteam = $match_arr->localteam;
									$arr['stage'][$s]['match'][$i]['localteam']['id'] = (string)$localteam['id'];
									$arr['stage'][$s]['match'][$i]['localteam']['name'] = (string)$localteam['name'];
									$arr['stage'][$s]['match'][$i]['localteam']['score'] = (string)$localteam['score'];
									$arr['stage'][$s]['match'][$i]['localteam']['ft_score'] = (string)$localteam['ft_score'];
									$arr['stage'][$s]['match'][$i]['localteam']['et_score'] = (string)$localteam['et_score'];
									$arr['stage'][$s]['match'][$i]['localteam']['pen_score'] = (string)$localteam['pen_score'];

									$visitorteam = $match_arr->visitorteam;
									$arr['stage'][$s]['match'][$i]['visitorteam']['id'] = (string)$visitorteam['id'];
									$arr['stage'][$s]['match'][$i]['visitorteam']['name'] = (string)$visitorteam['name'];
									$arr['stage'][$s]['match'][$i]['visitorteam']['score'] = (string)$visitorteam['score'];
									$arr['stage'][$s]['match'][$i]['visitorteam']['ft_score'] = (string)$visitorteam['ft_score'];
									$arr['stage'][$s]['match'][$i]['visitorteam']['et_score'] = (string)$visitorteam['et_score'];
									$arr['stage'][$s]['match'][$i]['visitorteam']['pen_score'] = (string)$visitorteam['pen_score'];

									$halftime = $match_arr->halftime;
									$arr['stage'][$s]['match'][$i]['halftime'] = (string)$halftime['score'];

									$allgoals = count($match_arr->goals->goal);
									//Debug($allgoals);
									for($k=0;$k<$allgoals;$k++){

										$goals = $match_arr->goals->goal[$k];
										$arr['stage'][$s]['match'][$i]['goals'][$k]['team'] = (string)$goals['team'];
										$arr['stage'][$s]['match'][$i]['goals'][$k]['minute'] = (string)$goals['minute'];
										$arr['stage'][$s]['match'][$i]['goals'][$k]['player'] = (string)$goals['player'];
										$arr['stage'][$s]['match'][$i]['goals'][$k]['score'] = (string)$goals['score'];
										$arr['stage'][$s]['match'][$i]['goals'][$k]['playerid'] = (string)$goals['playerid'];
										$arr['stage'][$s]['match'][$i]['goals'][$k]['assist'] = (string)$goals['assist'];
										$arr['stage'][$s]['match'][$i]['goals'][$k]['assistid'] = (string)$goals['assistid'];
									}

									//LineUP Home
									if(isset($match_arr->lineups->localteam->player)){
										// Debug($match_arr->lineups->localteam->player);

										$allplayer = count($match_arr->lineups->localteam->player);
										//Debug($allplayer);
										for($k=0;$k<$allplayer;$k++){

											$lineups = $match_arr->lineups->localteam->player[$k];
											// Debug($lineups);
											$arr['stage'][$s]['match'][$i]['lineups']['home_player'][$k]['id'] = (string)$lineups['id'];
											$arr['stage'][$s]['match'][$i]['lineups']['home_player'][$k]['number'] = (string)$lineups['number'];
											$arr['stage'][$s]['match'][$i]['lineups']['home_player'][$k]['name'] = (string)$lineups['name'];
											$arr['stage'][$s]['match'][$i]['lineups']['home_player'][$k]['booking'] = (string)$lineups['booking'];
											// Debug($arr['stage'][$s]['match'][$i]['lineups']['home_player'][$k]);
										}										
									}
									// die();

									//LineUP Away
									if(isset($match_arr->lineups->visitorteam->player)){
										
										$allplayer = count($match_arr->lineups->visitorteam->player);
										//Debug($allplayer);
										for($k=0;$k<$allplayer;$k++){

											$lineups = $match_arr->lineups->visitorteam->player[$k];
											// Debug($lineups);

											$arr['stage'][$s]['match'][$i]['lineups']['away_player'][$k]['id'] = (string)$lineups['id'];
											$arr['stage'][$s]['match'][$i]['lineups']['away_player'][$k]['number'] = (string)$lineups['number'];
											$arr['stage'][$s]['match'][$i]['lineups']['away_player'][$k]['name'] = (string)$lineups['name'];
											$arr['stage'][$s]['match'][$i]['lineups']['away_player'][$k]['booking'] = (string)$lineups['booking'];
										}
									}
									// die();

									//Substitutions Home
									if(isset($match_arr->substitutions->localteam->substitution)){
									
										$allplayer = count($match_arr->substitutions->localteam->substitution);
										//Debug($allplayer);
										for($k=0;$k<$allplayer;$k++){

											$sub = $match_arr->substitutions->localteam->substitution[$k];
											$arr['stage'][$s]['match'][$i]['substitutions']['home_player'][$k]['player_in_id'] = (string)$sub['player_in_id'];
											$arr['stage'][$s]['match'][$i]['substitutions']['home_player'][$k]['player_in_number'] = (string)$sub['player_in_number'];
											$arr['stage'][$s]['match'][$i]['substitutions']['home_player'][$k]['player_in_name'] = (string)$sub['player_in_name'];
											$arr['stage'][$s]['match'][$i]['substitutions']['home_player'][$k]['player_in_booking'] = (string)$sub['player_in_booking'];
											$arr['stage'][$s]['match'][$i]['substitutions']['home_player'][$k]['player_out_name'] = (string)$sub['player_out_name'];
											$arr['stage'][$s]['match'][$i]['substitutions']['home_player'][$k]['minute'] = (string)$sub['minute'];
										}
									}

									//Substitutions Away
									if(isset($match_arr->substitutions->visitorteam->substitution)){
										
										$allplayer = count($match_arr->substitutions->visitorteam->substitution);

										for($k=0;$k<$allplayer;$k++){

											$sub = $match_arr->substitutions->visitorteam->substitution[$k];
											$arr['stage'][$s]['match'][$i]['substitutions']['away_player'][$k]['player_in_id'] = (string)$sub['player_in_id'];
											$arr['stage'][$s]['match'][$i]['substitutions']['away_player'][$k]['player_in_number'] = (string)$sub['player_in_number'];
											$arr['stage'][$s]['match'][$i]['substitutions']['away_player'][$k]['player_in_name'] = (string)$sub['player_in_name'];
											$arr['stage'][$s]['match'][$i]['substitutions']['away_player'][$k]['player_in_booking'] = (string)$sub['player_in_booking'];
											$arr['stage'][$s]['match'][$i]['substitutions']['away_player'][$k]['player_out_name'] = (string)$sub['player_out_name'];
											$arr['stage'][$s]['match'][$i]['substitutions']['away_player'][$k]['minute'] = (string)$sub['minute'];
										}
									}

									//Penalties
									if(isset($match_arr->penalties)){
										
										// Debug($match_arr->penalties->goal);
										$allpen = count($match_arr->penalties->goal);

										for($k=0;$k<$allpen;$k++){

											$penalties = $match_arr->penalties->goal[$k];

											$arr['stage'][$s]['match'][$i]['penalties'][$k]['team'] = (string)$penalties['team'];
											$arr['stage'][$s]['match'][$i]['penalties'][$k]['minute'] = (string)$penalties['minute'];
											$arr['stage'][$s]['match'][$i]['penalties'][$k]['playerid'] = (string)$penalties['playerid'];
											$arr['stage'][$s]['match'][$i]['penalties'][$k]['player'] = (string)$penalties['player'];
											$arr['stage'][$s]['match'][$i]['penalties'][$k]['score'] = (string)$penalties['score'];
											$arr['stage'][$s]['match'][$i]['penalties'][$k]['scored'] = (string)$penalties['scored'];
										}
									}
								}
							}
							// Debug($arr);
						}
					}
				}
			}else{
				foreach($xmlelement as $key1 => $val1){
					$arr['country'] = (string)$xmlelement['country'];
					if($key1 == 'tournament'){
						$arr['id'] = (string)$val1['id'];
						$arr['league'] = (string)$val1['league'];
						$arr['season'] = (string)$val1['season'];
						//$arr['stage_id'] = (string)$val1['stage_id'];

						if(isset($val1['stage_id'])) $arr['stage_id'] = (string)$val1['stage_id'];

						$rows = count($val1->stage);

						if($rows > 0 ){
							if(isset($val1->stage[$rows-1]->week)){
								$week_obj = $val1->stage[$rows-1];
								$allweek = count($week_obj);
								$arr['countweek'] = (int)$allweek;
							}else{
								$week_obj = $val1;
								$allweek = count($week_obj);
								$arr['countweek'] = (int)$allweek;
							}
						}

						//$allweek = count($val1);
						//$arr['countweek'] = (int)$allweek;
						//foreach($val1 as $key2 => $val2){
						for($i=0;$i<$allweek;$i++){
							$week_arr = $week_obj->week[$i]->attributes();
							//Debug($week_arr);
							$arr['week'][$i]['number'] = (string)$week_arr->number;
							$allmatch = count($week_obj->week[$i]);
							//Debug($allmatch);
							for($j=0;$j<$allmatch;$j++){
								$match_arr = $week_obj->week[$i]->match[$j]->attributes();
								$arr['week'][$i]['match'][$j]['date'] = (string)$match_arr->date;
								$arr['week'][$i]['match'][$j]['time'] = (string)$match_arr->time;
								$arr['week'][$i]['match'][$j]['status'] = (string)$match_arr->status;
								$arr['week'][$i]['match'][$j]['venue'] = (string)$match_arr->venue;
								$arr['week'][$i]['match'][$j]['venue_id'] = (string)$match_arr->venue_id;
								$arr['week'][$i]['match'][$j]['static_id'] = (string)$match_arr->static_id;
								$arr['week'][$i]['match'][$j]['id'] = (string)$match_arr->id;

								$localteam = $week_obj->week[$i]->match[$j]->localteam->attributes();
								$arr['week'][$i]['match'][$j]['localteam']['id'] = (string)$localteam->id;
								$arr['week'][$i]['match'][$j]['localteam']['name'] = (string)$localteam->name;
								$arr['week'][$i]['match'][$j]['localteam']['score'] = (string)$localteam->score;
								$arr['week'][$i]['match'][$j]['localteam']['ft_score'] = (string)$localteam->ft_score;
								$arr['week'][$i]['match'][$j]['localteam']['et_score'] = (string)$localteam->et_score;
								$arr['week'][$i]['match'][$j]['localteam']['pen_score'] = (string)$localteam->pen_score;

								$visitorteam = $week_obj->week[$i]->match[$j]->visitorteam->attributes();
								$arr['week'][$i]['match'][$j]['visitorteam']['id'] = (string)$visitorteam->id;
								$arr['week'][$i]['match'][$j]['visitorteam']['name'] = (string)$visitorteam->name;
								$arr['week'][$i]['match'][$j]['visitorteam']['score'] = (string)$visitorteam->score;
								$arr['week'][$i]['match'][$j]['visitorteam']['ft_score'] = (string)$visitorteam->ft_score;
								$arr['week'][$i]['match'][$j]['visitorteam']['et_score'] = (string)$visitorteam->et_score;
								$arr['week'][$i]['match'][$j]['visitorteam']['pen_score'] = (string)$visitorteam->pen_score;

								$halftime = $week_obj->week[$i]->match[$j]->halftime->attributes();
								$arr['week'][$i]['match'][$j]['halftime'] = (string)$halftime->score;

								//$allgoals = count($xmlelement->$key1->week[$i]->match[$j]->goals->goal);
								$allgoals = 0;
								//Debug($allgoals);
								for($k=0;$k<$allgoals;$k++){
									$goals = $week_obj->week[$i]->match[$j]->goals->goal[$k]->attributes();
									$arr['week'][$i]['match'][$j]['goals'][$k]['team'] = (string)$goals->team;
									$arr['week'][$i]['match'][$j]['goals'][$k]['minute'] = (string)$goals->minute;
									$arr['week'][$i]['match'][$j]['goals'][$k]['player'] = (string)$goals->player;
									$arr['week'][$i]['match'][$j]['goals'][$k]['score'] = (string)$goals->score;
									$arr['week'][$i]['match'][$j]['goals'][$k]['playerid'] = (string)$goals->playerid;
									$arr['week'][$i]['match'][$j]['goals'][$k]['assist'] = (string)$goals->assist;
									$arr['week'][$i]['match'][$j]['goals'][$k]['assistid'] = (string)$goals->assistid;
								}

								//LineUP Home
								//$allplayer = count($xmlelement->$key1->week[$i]->match[$j]->lineups->localteam->player);
								$allplayer = 0;

								//Debug($allplayer);
								for($k=0;$k<$allplayer;$k++){
									$lineups = $week_obj->week[$i]->match[$j]->lineups->localteam->player[$k]->attributes();
									$arr['week'][$i]['match'][$j]['lineups']['home_player'][$k]['id'] = (string)$lineups->id;
									$arr['week'][$i]['match'][$j]['lineups']['home_player'][$k]['number'] = (string)$lineups->number;
									$arr['week'][$i]['match'][$j]['lineups']['home_player'][$k]['name'] = (string)$lineups->name;
									$arr['week'][$i]['match'][$j]['lineups']['home_player'][$k]['booking'] = (string)$lineups->booking;
								}

								//LineUP Away
								//$allplayer = count($xmlelement->$key1->week[$i]->match[$j]->lineups->visitorteam->player);
								//Debug($allplayer);
								for($k=0;$k<$allplayer;$k++){
									$lineups = $week_obj->week[$i]->match[$j]->lineups->visitorteam->player[$k]->attributes();
									$arr['week'][$i]['match'][$j]['lineups']['away_player'][$k]['id'] = (string)$lineups->id;
									$arr['week'][$i]['match'][$j]['lineups']['away_player'][$k]['number'] = (string)$lineups->number;
									$arr['week'][$i]['match'][$j]['lineups']['away_player'][$k]['name'] = (string)$lineups->name;
									$arr['week'][$i]['match'][$j]['lineups']['away_player'][$k]['booking'] = (string)$lineups->booking;
								}

								//Substitutions Home
								//$allplayer = count($xmlelement->$key1->week[$i]->match[$j]->substitutions->localteam->substitution);
								//Debug($allplayer);
								for($k=0;$k<$allplayer;$k++){
									$sub = $week_obj->week[$i]->match[$j]->substitutions->localteam->substitution[$k]->attributes();
									$arr['week'][$i]['match'][$j]['substitutions']['home_player'][$k]['player_in_id'] = (string)$sub->player_in_id;
									$arr['week'][$i]['match'][$j]['substitutions']['home_player'][$k]['player_in_number'] = (string)$sub->player_in_number;
									$arr['week'][$i]['match'][$j]['substitutions']['home_player'][$k]['player_in_name'] = (string)$sub->player_in_name;
									$arr['week'][$i]['match'][$j]['substitutions']['home_player'][$k]['player_in_booking'] = (string)$sub->player_in_booking;
									$arr['week'][$i]['match'][$j]['substitutions']['home_player'][$k]['player_out_name'] = (string)$sub->player_out_name;
									$arr['week'][$i]['match'][$j]['substitutions']['home_player'][$k]['minute'] = (string)$sub->minute;
								}

								//Substitutions Away
								//$allplayer = count($xmlelement->$key1->week[$i]->match[$j]->substitutions->visitorteam->substitution);
								for($k=0;$k<$allplayer;$k++){
									$sub = $week_obj->week[$i]->match[$j]->substitutions->visitorteam->substitution[$k]->attributes();
									$arr['week'][$i]['match'][$j]['substitutions']['away_player'][$k]['player_in_id'] = (string)$sub->player_in_id;
									$arr['week'][$i]['match'][$j]['substitutions']['away_player'][$k]['player_in_number'] = (string)$sub->player_in_number;
									$arr['week'][$i]['match'][$j]['substitutions']['away_player'][$k]['player_in_name'] = (string)$sub->player_in_name;
									$arr['week'][$i]['match'][$j]['substitutions']['away_player'][$k]['player_in_booking'] = (string)$sub->player_in_booking;
									$arr['week'][$i]['match'][$j]['substitutions']['away_player'][$k]['player_out_name'] = (string)$sub->player_out_name;
									$arr['week'][$i]['match'][$j]['substitutions']['away_player'][$k]['minute'] = (string)$sub->minute;
								}

							}
						}
					}
				}			}
		}
		return $arr;
	}

	public function get_h2h($url_feed, $query = 0){

		$arr = $temp = $field = array();
		$xmlelement =  simplexml_load_file($url_feed);

		//Debug($xmlelement);
		//die();
		if($xmlelement){
			foreach($xmlelement as $key1 => $val1){
				//echo "<hr>$key1 => $val1<br>";
				//Debug($val1);
				$arr['team1'] = (int)$xmlelement['team1'];
				$arr['team2'] = (int)$xmlelement['team2'];
				switch($key1){
					case 'top50' :
						//echo "<hr>top50<br>";					
						$num = count($val1);
						//Debug($num);
						//Debug($val1);						
						for($i=0;$i<$num;$i++){
							$temp = $val1->match[$i]->attributes();
							$arr['top50'][$i]['category'] = (string)$temp['category'];
							$arr['top50'][$i]['league'] = (string)$temp['league'];
							$arr['top50'][$i]['league_id'] = (string)$temp['league_id'];
							$arr['top50'][$i]['team1'] = (string)$temp['team1'];
							$arr['top50'][$i]['id1'] = (string)$temp['id1'];
							$arr['top50'][$i]['team2'] = (string)$temp['team2'];
							$arr['top50'][$i]['id2'] = (string)$temp['id2'];
							$arr['top50'][$i]['date'] = (string)$temp['date'];
							$arr['top50'][$i]['team1_score'] = (string)$temp['team1_score'];
							$arr['top50'][$i]['team2_score'] = (string)$temp['team2_score'];
							$arr['top50'][$i]['static_id'] = (string)$temp['static_id'];
						}
						//$this->create_table('h2h_top50', $arr['top50']);
						break;
					case 'overall' :
						//echo "<hr>overall<br>";
						//Debug($val1);
						foreach($val1 as $key2 => $val2){
							//echo "$key2<br>";
							switch($key2){
								case 'total' :
									//Debug($val2);
									//$num = count($val2);
									unset($temp);
									$temp = $val2->total[0]->attributes();
									$arr['overall']['total']['games'] = (string)$temp['games'];
									$temp = $val2->total[1]->attributes();
									$arr['overall']['total']['team1_won'] = (string)$temp['team1_won'];
									$temp = $val2->total[2]->attributes();
									$arr['overall']['total']['team2_won'] = (string)$temp['team2_won'];
									$temp = $val2->total[3]->attributes();
									$arr['overall']['total']['draws'] = (string)$temp['draws'];
									break;
								case 'home' :

									//Debug($val2);
									$num = count($val2->team1);
									unset($temp);
									//Debug($num);
									for($i=0;$i<$num;$i++){
										$temp = $val2->team1[$i]->attributes();
										if($i == 0) $arr['overall']['home']['team1']['games'] = (string)$temp['games'];
										if($i == 1) $arr['overall']['home']['team1']['won'] = (string)$temp['won'];
										if($i == 2) $arr['overall']['home']['team1']['lost'] = (string)$temp['lost'];
										if($i == 3) $arr['overall']['home']['team1']['draws'] = (string)$temp['draws'];
									}
									$num = count($val2->team2);
									unset($temp);
									//Debug($num);
									for($i=0;$i<$num;$i++){
										$temp = $val2->team2[$i]->attributes();
										if($i == 0) $arr['overall']['home']['team2']['games'] = (string)$temp['games'];
										if($i == 1) $arr['overall']['home']['team2']['won'] = (string)$temp['won'];
										if($i == 2) $arr['overall']['home']['team2']['lost'] = (string)$temp['lost'];
										if($i == 3) $arr['overall']['home']['team2']['draws'] = (string)$temp['draws'];
									}

									break;
								case 'away' :
									//Debug($val2);
									$num = count($val2->team1);
									unset($temp);

									for($i=0;$i<$num;$i++){
										$temp = $val2->team1[$i]->attributes();
										if($i == 0) $arr['overall']['away']['team1']['games'] = (string)$temp['games'];
										if($i == 1) $arr['overall']['away']['team1']['won'] = (string)$temp['won'];
										if($i == 2) $arr['overall']['away']['team1']['lost'] = (string)$temp['lost'];
										if($i == 3) $arr['overall']['away']['team1']['draws'] = (string)$temp['draws'];
									}
									$num = count($val2->team2);
									unset($temp);

									for($i=0;$i<$num;$i++){
										$temp = $val2->team2[$i]->attributes();
										if($i == 0) $arr['overall']['away']['team2']['games'] = (string)$temp['games'];
										if($i == 1) $arr['overall']['away']['team2']['won'] = (string)$temp['won'];
										if($i == 2) $arr['overall']['away']['team2']['lost'] = (string)$temp['lost'];
										if($i == 3) $arr['overall']['away']['team2']['draws'] = (string)$temp['draws'];
									}
									break;
							}
						}
						break;
					case 'leagues' :
						//echo "<hr>leagues<br>";					
						$num = count($val1);
						//Debug($num);
						//Debug($val1);
						unset($temp);
						for($i=0;$i<$num;$i++){
							$temp = $val1->league[$i]->attributes();
							$arr['leagues'][$i]['name'] = (string)$temp['name'];
							$arr['leagues'][$i]['id'] = (string)$temp['id'];
							$arr['leagues'][$i]['games'] = (string)$temp['games'];
							$arr['leagues'][$i]['team1_won'] = (string)$temp['team1_won'];
							$arr['leagues'][$i]['team2_won'] = (string)$temp['team2_won'];
							$arr['leagues'][$i]['drawdraw'] = (string)$temp['draw'];
						}

						break;
					case 'goals' :
						//echo "<hr>goals<br>";					
						$num = count($val1);
						//Debug($num);
						//Debug($val1);
						unset($temp);
						foreach($val1 as $key2 => $val2){
							//echo "$key2<br>";
							switch($key2){
								case 'total' :
									//Debug($val2);
									$num = count($val2->total);
									unset($temp);
									for($i=0;$i<$num;$i++){
										$temp = $val2->total[$i]->attributes();
										if($i == 0) $arr['goals']['total']['team1_scored'] = (string)$temp['team1_scored'];
										if($i == 1) $arr['goals']['total']['team1_conceded'] = (string)$temp['team1_conceded'];
										if($i == 2) $arr['goals']['total']['team2_scored'] = (string)$temp['team2_scored'];
										if($i == 3) $arr['goals']['total']['team2_conceded'] = (string)$temp['team2_conceded'];
									}
									break;
								case 'home' :
									//Debug($val2);
									$num = count($val2->home);
									unset($temp);
									for($i=0;$i<$num;$i++){
										$temp = $val2->home[$i]->attributes();
										if($i == 0) $arr['goals']['home']['team1_scored'] = (string)$temp['team1_scored'];
										if($i == 1) $arr['goals']['home']['team1_conceded'] = (string)$temp['team1_conceded'];
										if($i == 2) $arr['goals']['home']['team2_scored'] = (string)$temp['team2_scored'];
										if($i == 3) $arr['goals']['home']['team2_conceded'] = (string)$temp['team2_conceded'];
									}
									break;
								case 'away' :
									//Debug($val2);
									$num = count($val2->away);
									unset($temp);
									for($i=0;$i<$num;$i++){
										$temp = $val2->away[$i]->attributes();
										if($i == 0) $arr['goals']['away']['team1_scored'] = (string)$temp['team1_scored'];
										if($i == 1) $arr['goals']['away']['team1_conceded'] = (string)$temp['team1_conceded'];
										if($i == 2) $arr['goals']['away']['team2_scored'] = (string)$temp['team2_scored'];
										if($i == 3) $arr['goals']['away']['team2_conceded'] = (string)$temp['team2_conceded'];
									}
									break;
							}
						}
						break;
					case 'biggest_victory' :
						//echo "<hr>biggest_victory<br>";					
						$num = count($val1);
						//Debug($num);
						//Debug($val1);
						//die();
						unset($temp);
						foreach($val1 as $key2 => $val2){
							//echo "$key2<br>";
							switch($key2){
								case 'team1' :
									//Debug($val2);
									//die();

									if(isset($xmlelement->biggest_victory->team1->match)){

										$temp = $xmlelement->biggest_victory->team1->match->attributes();
										//Debug($temp);

										$arr['biggest_victory']['team1']['category'] = (string)$temp['category'];
										$arr['biggest_victory']['team1']['league'] = (string)$temp['league'];
										$arr['biggest_victory']['team1']['league_id'] = (string)$temp['league_id'];
										$arr['biggest_victory']['team1']['team1'] = (string)$temp['team1'];
										$arr['biggest_victory']['team1']['id1'] = (string)$temp['id1'];
										$arr['biggest_victory']['team1']['team2'] = (string)$temp['team2'];
										$arr['biggest_victory']['team1']['id2'] = (string)$temp['id2'];
										$arr['biggest_victory']['team1']['date'] = (string)$temp['date'];
										$arr['biggest_victory']['team1']['team1_score'] = (string)$temp['team1_score'];
										$arr['biggest_victory']['team1']['team2_score'] = (string)$temp['team2_score'];
										$arr['biggest_victory']['team1']['static_id'] = (string)$temp['static_id'];
									}

									break;
								case 'team2' :

									if(isset($xmlelement->biggest_victory->team2->match)){

										$temp = $xmlelement->biggest_victory->team2->match->attributes();
										//Debug($temp);
										$arr['biggest_victory']['team2']['category'] = (string)$temp['category'];
										$arr['biggest_victory']['team2']['league'] = (string)$temp['league'];
										$arr['biggest_victory']['team2']['league_id'] = (string)$temp['league_id'];
										$arr['biggest_victory']['team2']['team1'] = (string)$temp['team1'];
										$arr['biggest_victory']['team2']['id1'] = (string)$temp['id1'];
										$arr['biggest_victory']['team2']['team2'] = (string)$temp['team2'];
										$arr['biggest_victory']['team2']['id2'] = (string)$temp['id2'];
										$arr['biggest_victory']['team2']['date'] = (string)$temp['date'];
										$arr['biggest_victory']['team2']['team1_score'] = (string)$temp['team1_score'];
										$arr['biggest_victory']['team2']['team2_score'] = (string)$temp['team2_score'];
										$arr['biggest_victory']['team2']['static_id'] = (string)$temp['static_id'];
									}
									break;
							}
						}
						break;
					case 'biggest_defeat' :
						//echo "<hr>biggest_defeat<br>";
						$num = count($val1);
						//Debug($num);
						//Debug($val1);
						unset($temp);
						unset($temp);
						foreach($val1 as $key2 => $val2){
							//echo "$key2<br>";
							switch($key2){
								case 'team1' :
									//Debug($val2);
									if(isset($xmlelement->biggest_defeat->team1->match)){
										$temp = $xmlelement->biggest_defeat->team1->match->attributes();
										//Debug($temp);
										$arr['biggest_defeat']['team1']['category'] = (string)$temp['category'];
										$arr['biggest_defeat']['team1']['league'] = (string)$temp['league'];
										$arr['biggest_defeat']['team1']['league_id'] = (string)$temp['league_id'];
										$arr['biggest_defeat']['team1']['team1'] = (string)$temp['team1'];
										$arr['biggest_defeat']['team1']['id1'] = (string)$temp['id1'];
										$arr['biggest_defeat']['team1']['team2'] = (string)$temp['team2'];
										$arr['biggest_defeat']['team1']['id2'] = (string)$temp['id2'];
										$arr['biggest_defeat']['team1']['date'] = (string)$temp['date'];
										$arr['biggest_defeat']['team1']['team1_score'] = (string)$temp['team1_score'];
										$arr['biggest_defeat']['team1']['team2_score'] = (string)$temp['team2_score'];
										$arr['biggest_defeat']['team1']['static_id'] = (string)$temp['static_id'];
									}

									break;
								case 'team2' :

									if(isset($xmlelement->biggest_defeat->team2->match)){
										$temp = $xmlelement->biggest_defeat->team2->match->attributes();
										//Debug($temp);
										$arr['biggest_defeat']['team2']['category'] = (string)$temp['category'];
										$arr['biggest_defeat']['team2']['league'] = (string)$temp['league'];
										$arr['biggest_defeat']['team2']['league_id'] = (string)$temp['league_id'];
										$arr['biggest_defeat']['team2']['team1'] = (string)$temp['team1'];
										$arr['biggest_defeat']['team2']['id1'] = (string)$temp['id1'];
										$arr['biggest_defeat']['team2']['team2'] = (string)$temp['team2'];
										$arr['biggest_defeat']['team2']['id2'] = (string)$temp['id2'];
										$arr['biggest_defeat']['team2']['date'] = (string)$temp['date'];
										$arr['biggest_defeat']['team2']['team1_score'] = (string)$temp['team1_score'];
										$arr['biggest_defeat']['team2']['team2_score'] = (string)$temp['team2_score'];
										$arr['biggest_defeat']['team2']['static_id'] = (string)$temp['static_id'];
									}
									break;
							}
						}
						break;
					case 'last5_home' :
						//echo "<hr>last5_home<br>";					
						$num = count($val1);
						//Debug($num);
						//Debug($val1);
						unset($temp);
						foreach($val1 as $key2 => $val2){
							//echo "$key2<br>";
							switch($key2){
								case 'team1' :
									//Debug($val2);
									$allmatch = count($xmlelement->last5_home->team1->match);
									//Debug($allmatch);
									for($i=0;$i<$allmatch;$i++){
										$temp = $xmlelement->last5_home->team1->match[$i]->attributes();
										//Debug($temp);
										$arr['last5_home']['team1'][$i]['category'] = (string)$temp['category'];
										$arr['last5_home']['team1'][$i]['league'] = (string)$temp['league'];
										$arr['last5_home']['team1'][$i]['league_id'] = (string)$temp['league_id'];
										$arr['last5_home']['team1'][$i]['date'] = (string)$temp['date'];
										$arr['last5_home']['team1'][$i]['team1'] = (string)$temp['team1'];
										$arr['last5_home']['team1'][$i]['id1'] = (string)$temp['id1'];
										$arr['last5_home']['team1'][$i]['team2'] = (string)$temp['team2'];
										$arr['last5_home']['team1'][$i]['id2'] = (string)$temp['id2'];
										$arr['last5_home']['team1'][$i]['team1_score'] = (string)$temp['team1_score'];
										$arr['last5_home']['team1'][$i]['team2_score'] = (string)$temp['team2_score'];
										$arr['last5_home']['team1'][$i]['static_id'] = (string)$temp['static_id'];
									}

									break;
								case 'team2' :
									$allmatch = count($xmlelement->last5_home->team1->match);
									//Debug($allmatch);
									for($i=0;$i<$allmatch;$i++){
										$temp = $xmlelement->last5_home->team2->match[$i]->attributes();
										//Debug($temp);
										$arr['last5_home']['team2'][$i]['category'] = (string)$temp['category'];
										$arr['last5_home']['team2'][$i]['league'] = (string)$temp['league'];
										$arr['last5_home']['team2'][$i]['league_id'] = (string)$temp['league_id'];
										$arr['last5_home']['team2'][$i]['date'] = (string)$temp['date'];
										$arr['last5_home']['team2'][$i]['team1'] = (string)$temp['team1'];
										$arr['last5_home']['team2'][$i]['id1'] = (string)$temp['id1'];
										$arr['last5_home']['team2'][$i]['team2'] = (string)$temp['team2'];
										$arr['last5_home']['team2'][$i]['id2'] = (string)$temp['id2'];

										$arr['last5_home']['team2'][$i]['team1_score'] = (string)$temp['team1_score'];
										$arr['last5_home']['team2'][$i]['team2_score'] = (string)$temp['team2_score'];
										$arr['last5_home']['team2'][$i]['static_id'] = (string)$temp['static_id'];
									}
									break;
							}
						}
						break;
					case 'last5_away' :
						//echo "<hr>last5_away<br>";					
						$num = count($val1);
						//Debug($num);
						//Debug($val1);
						unset($temp);
						foreach($val1 as $key2 => $val2){
							//echo "$key2<br>";
							switch($key2){
								case 'team1' :
									//Debug($val2);
									$allmatch = count($xmlelement->last5_away->team1->match);
									//Debug($allmatch);
									for($i=0;$i<$allmatch;$i++){
										$temp = $xmlelement->last5_away->team1->match[$i]->attributes();
										//Debug($temp);
										$arr['last5_away']['team1'][$i]['category'] = (string)$temp['category'];
										$arr['last5_away']['team1'][$i]['league'] = (string)$temp['league'];
										$arr['last5_away']['team1'][$i]['league_id'] = (string)$temp['league_id'];
										$arr['last5_away']['team1'][$i]['date'] = (string)$temp['date'];
										$arr['last5_away']['team1'][$i]['team1'] = (string)$temp['team1'];
										$arr['last5_away']['team1'][$i]['id1'] = (string)$temp['id1'];
										$arr['last5_away']['team1'][$i]['team2'] = (string)$temp['team2'];
										$arr['last5_away']['team1'][$i]['id2'] = (string)$temp['id2'];
										$arr['last5_away']['team1'][$i]['team1_score'] = (string)$temp['team1_score'];
										$arr['last5_away']['team1'][$i]['team2_score'] = (string)$temp['team2_score'];
										$arr['last5_away']['team1'][$i]['static_id'] = (string)$temp['static_id'];
									}

									break;
								case 'team2' :
									$allmatch = count($xmlelement->last5_away->team1->match);
									//Debug($allmatch);
									for($i=0;$i<$allmatch;$i++){
										$temp = $xmlelement->last5_away->team2->match[$i]->attributes();
										//Debug($temp);
										$arr['last5_away']['team2'][$i]['category'] = (string)$temp['category'];
										$arr['last5_away']['team2'][$i]['league'] = (string)$temp['league'];
										$arr['last5_away']['team2'][$i]['league_id'] = (string)$temp['league_id'];
										$arr['last5_away']['team2'][$i]['date'] = (string)$temp['date'];
										$arr['last5_away']['team2'][$i]['team1'] = (string)$temp['team1'];
										$arr['last5_away']['team2'][$i]['id1'] = (string)$temp['id1'];
										$arr['last5_away']['team2'][$i]['team2'] = (string)$temp['team2'];
										$arr['last5_away']['team2'][$i]['id2'] = (string)$temp['id2'];
										$arr['last5_away']['team2'][$i]['team1_score'] = (string)$temp['team1_score'];
										$arr['last5_away']['team2'][$i]['team2_score'] = (string)$temp['team2_score'];
										$arr['last5_away']['team2'][$i]['static_id'] = (string)$temp['static_id'];
									}
									break;
							}
						}
						break;
				}
			}
		}
		return $arr;
	}

	public function get_player($url_feed, $query = 0){
		$arr = $temp = $field = array();
		$xmlelement =  simplexml_load_file($url_feed);
		//Debug($xmlelement);
		if($xmlelement){
			foreach($xmlelement as $key1 => $val1){
				$arr[$key1]['id'] = (string)$val1['id'];
				$arr[$key1]['common_name'] = (string)$val1['common_name'];
				$arr[$key1]['category'] = (string)$xmlelement['category'];
				//echo "$key1 => $val1";
				foreach($val1 as $key2 => $val2){
					//echo "$key2 => $val2";
					$number = count($val2);
					if($key2 == 'image'){
						$arr[$key1]['image'] = (string)$val2;

					}else if(is_array($val2) || $number>0){
						//echo "$key2 => $val2<br>";
						//$allmatch = count($xmlelement->last5_home->team1->match);
						// Debug($number);
						// Debug($val2);

						for($i=0;$i<$number;$i++){
							$temp = $val2->club[$i];
							//Debug($temp);
							$arr['statistic'][$key2][$i]['name'] = @(string)$temp['name'];
							$arr['statistic'][$key2][$i]['id'] = @(string)$temp['id'];
							$arr['statistic'][$key2][$i]['league'] = @(string)$temp['league'];
							$arr['statistic'][$key2][$i]['league_id'] = @(string)$temp['league_id'];
							$arr['statistic'][$key2][$i]['season'] = @(string)$temp['season'];
							$arr['statistic'][$key2][$i]['minutes'] = @(string)$temp['minutes'];
							$arr['statistic'][$key2][$i]['appearences'] = @(string)$temp['appearences'];
							$arr['statistic'][$key2][$i]['lineups'] = @(string)$temp['lineups'];
							$arr['statistic'][$key2][$i]['substitute_in'] = @(string)$temp['substitute_in'];
							$arr['statistic'][$key2][$i]['substitute_out'] = @(string)$temp['substitute_out'];
							$arr['statistic'][$key2][$i]['substitutes_on_bench'] = @(string)$temp['substitutes_on_bench'];
							$arr['statistic'][$key2][$i]['goals'] = @(string)$temp['goals'];
							$arr['statistic'][$key2][$i]['yellowcards'] = @(string)$temp['yellowcards'];
							$arr['statistic'][$key2][$i]['yellowred'] = @(string)$temp['yellowred'];
							$arr['statistic'][$key2][$i]['redcards'] = @(string)$temp['redcards'];
							// Debug($arr['statistic'][$key2][$i]);
						}
					}else{
						$arr[$key1][$key2] = (string)$val2;
					}
				}
			}
		}
		//Debug($arr);
		return $arr;
	}

	public function get_live_commentaries($url_feed, $query = 0){

		$arr = $temp = $field = array();
		//$xmlelement =  simplexml_load_file($url_feed);

		//$xmlfile = fopen($url_feed, "r") or die("Unable to open file!");
		//$xmlstr = fread($xmlfile,filesize($url_feed));
		//fclose($xmlfile);
		//$commentaries = new SimpleXMLElement($xmlstr);
		$commentaries =  simplexml_load_file($url_feed);

		$param_match = array();
		$param_match["sport"] = (string)$commentaries['sport'];
		$param_match["lastupdate_date"] = strtotime(str_replace('.', '-', $commentaries['updated']));

		$tournament = $commentaries->tournament;
		$param_match["tournament_id"] = (string)$tournament['id'];
		$param_match["tournament_name"] = (string)$tournament['name'];

		$match = $tournament->match;
		$param_match["match_id"] = (string)$match['id'];
		$param_match["static_id"] = (string)$match['static_id'];
		$param_match["match_status"] = (string)$match['status'];
		$param_match["match_datetime"] = strtotime(str_replace('.', '-', ($match['formatted_date'] . " " . $match['time'])));

		$matchinfo = $match->matchinfo;
		$param_match["stadium"] = (string)$matchinfo->stadium['name'];
		$param_match["attendance"] = (string)$matchinfo->attendance['name'];
		$param_match["time"] = (string)$matchinfo->time['name'];
		$param_match["referee"] = (string)$matchinfo->time['referee'];

		$localteam = $match->localteam;
		$param_match["hteam_id"] = (string)$localteam['id'];
		$param_match["hteam"] = (string)$localteam['name'];
		$param_match["hgoals"] = (string)$localteam['goals'];

		$visitorteam = $match->visitorteam;
		$param_match["ateam_id"] = (string)$visitorteam['id'];
		$param_match["ateam"] = (string)$visitorteam['name'];
		$param_match["agoals"] = (string)$visitorteam['goals'];

		$teams = $match->teams;
		$substitutes = $match->substitutes;
		//Debug($substitutes);


		//***************localteam****************
		$param_match_player = array();
		$param_match_player["match_id"] = $param_match["match_id"];
		$param_match_player["static_id"] = $param_match["static_id"];
		$param_match_player["tournament_id"] = $param_match["tournament_id"];

		//Local Team
		$param_match_player["team_id"] = $param_match["hteam_id"];
		$match_player['localteam'] = $this->getPlayer($teams->localteam, $param_match_player, 0);

		//Home Substitutes
		$match_player['localteam']['substitutes'] = $this->getPlayer($substitutes->localteam, $param_match_player, 1);

		//Visitor Team
		$param_match_player["team_id"] = $param_match["ateam_id"];
		$match_player['visitorteam'] = $this->getPlayer($teams->visitorteam, $param_match_player, 0);

		//Visitor Substitutes
		$match_player['visitorteam']['substitutes'] = $this->getPlayer($substitutes->visitorteam, $param_match_player, 1);

		$substitutions = $match->substitutions;
		$param_match_substitutions = array();
		$param_match_substitutions["match_id"] = $param_match["match_id"];
		$param_match_substitutions["static_id"] = $param_match["static_id"];
		$param_match_substitutions["tournament_id"] = $param_match["tournament_id"];

		//Substitution Local Team
		$param_match_substitutions["team_id"] = $param_match["hteam_id"];
		$match_substitutions['localteam'] = $this->getSubstitutionsPlayer($substitutions->localteam, $param_match_substitutions);

		//Substitution Visitor Team
		$param_match_substitutions["team_id"] = $param_match["ateam_id"];
		$match_substitutions['visitorteam'] = $this->getSubstitutionsPlayer($substitutions->visitorteam, $param_match_substitutions);

		//Match Commentaries
		$commentaries = $match->commentaries;
		$param_match_commentaries = array();
		$param_match_commentaries["match_id"] = $param_match["match_id"];
		$param_match_commentaries["static_id"] = $param_match["static_id"];
		$param_match_commentaries["tournament_id"] = $param_match["tournament_id"];
		$match_commentaries = $this->getMatchCommentaries($commentaries, $param_match_commentaries);

		$player_stats = $match->player_stats;
		$param_match_player_statistic = array();
		$param_match_player_statistic["match_id"] = $param_match["match_id"];
		$param_match_player_statistic["static_id"] = $param_match["static_id"];
		$param_match_player_statistic["tournament_id"] = $param_match["tournament_id"];

		//Home Player Statistic
		$param_match_player_statistic["team_id"] = $param_match["hteam_id"];
		$match_player_statistic['localteam'] = $this->getPlayerStatistic($player_stats->localteam, $param_match_player_statistic);

		//Away Player Statistic
		$param_match_player_statistic["team_id"] = $param_match["ateam_id"];
		$match_player_statistic['visitorteam'] = $this->getPlayerStatistic($player_stats->visitorteam, $param_match_player_statistic);

		$dataupdate['match_id'] = $param_match['match_id'];
		$dataupdate['lastupdate_date'] = date('Y-m-d H:i:s', $param_match['lastupdate_date']);
		$dataupdate['static_id'] = $param_match['static_id'];
		$dataupdate['match_status'] = ($param_match['match_status'] == 'Full-time') ? 'FT' : '-';
		if($param_match['attendance'] != '') $dataupdate['attendance'] = $param_match['attendance'];
		$dataupdate['referee'] = $param_match['referee'];
		$dataupdate['hgoals'] = $param_match['hgoals'];
		$dataupdate['agoals'] = $param_match['agoals'];

		//Save Update _xml_match
		if($query == 1) $this->update('_xml_match', 'match_id', $dataupdate);

		//Debug($param_match);		
		//Debug($param_match_player);
		//Debug($param_match_substitutions);
		//Debug($param_match_commentaries);
		//Debug($param_match_player_statistic);

		$param_match['match_player'] = $match_player;
		$param_match['match_substitutions'] = $match_substitutions;
		$param_match['match_commentaries'] = $match_commentaries;
		$param_match['match_player_statistic'] = $match_player_statistic;

		if(isset($match->halftime['score'])){

			//$param_match['halftime'] = (string)$match->halftime['score'];
			$halftime = str_replace('[', '', str_replace(']', '', (string)$match->halftime['score']));
			$hl_arr = explode("-", $halftime);
			$param_match['halftime']['home'] = $hl_arr[0];
			$param_match['halftime']['away'] = $hl_arr[1];
		}

		return $param_match;
	}

	function getPlayer($parent_node, $param_match_player, $substitutes, $query = 0){
		$i = 0;
		$match_player = array();
		foreach ($parent_node->player as $player) {
			$match_player[$i]["match_id"] = $param_match_player["match_id"];
			$match_player[$i]["static_id"] = $param_match_player["static_id"];
			$match_player[$i]["tournament_id"] = $param_match_player["tournament_id"];
			$match_player[$i]["team_id"] = $param_match_player["team_id"];
			$match_player[$i]["player_id"] = (string)$player["id"];
			$match_player[$i]["player_position"] = (string)$player["pos"];
			$match_player[$i]["player_name"] = (string)$player["name"];
			$match_player[$i]["player_number"] = (string)$player["number"];
			$match_player[$i]["substitutes"] = (string)$substitutes;
			$i++;
		}
		//echo "<br>getPlayer<br>";
		//if($this->uri->segment(3) == 'debug') Debug($match_player);
		if($query == 1) $this->xml_model->import_batch('_xml_match_player', $match_player);
		return $match_player;
	}

	function getPlayerStatistic($parent_node, $param_match_player_statistic, $query = 0) {
		$i = 0;
		$player_statistic = array();
		if($parent_node->player)
			foreach ($parent_node->player as $player) {

				$player_statistic[$i]["match_id"] = $param_match_player_statistic["match_id"];
				$player_statistic[$i]["static_id"] = $param_match_player_statistic["static_id"];
				$player_statistic[$i]["tournament_id"] = $param_match_player_statistic["tournament_id"];
				$player_statistic[$i]["team_id"] = $param_match_player_statistic["team_id"];

				$player_statistic[$i]["player_id"] = (string)$player["id"];
				$player_statistic[$i]["player_number"] = (string)$player["num"];
				$player_statistic[$i]["player_name"] = (string)$player["name"];
				$player_statistic[$i]["player_position"] = (string)$player["pos"];
				$player_statistic[$i]["posx"] = $player["posx"] == "" ? 0 : (string)$player["posx"];
				$player_statistic[$i]["posy"] = $player["posy"] == "" ? 0 : (string)$player["posy"];
				$player_statistic[$i]["shots_total"] = $player["shots_total"] == "" ? 0 : (string)$player["shots_total"];
				$player_statistic[$i]["shots_on_goal"] = $player["shots_on_goal"] == "" ? 0 : (string)$player["shots_on_goal"];
				$player_statistic[$i]["goals"] = $player["goals"] == "" ? 0 : (string)$player["goals"];
				$player_statistic[$i]["assists"] = $player["assists"] == "" ? 0 : (string)$player["assists"];
				$player_statistic[$i]["offsides"] = $player["offsides"] == "" ? 0 : (string)$player["offsides"];
				$player_statistic[$i]["fouls_drawn"] = $player["fouls_drawn"] == "" ? 0 : (string)$player["fouls_drawn"];
				$player_statistic[$i]["fouls_commited"] = $player["fouls_commited"] == "" ? 0 : (string)$player["fouls_commited"];
				$player_statistic[$i]["saves"] = $player["saves"] == "" ? 0 : (string)$player["saves"];
				$player_statistic[$i]["yellowcards"] = $player["yellowcards"] == "" ? 0 : (string)$player["yellowcards"];
				$player_statistic[$i]["redcards"] = $player["redcards"] == "" ? 0 : (string)$player["redcards"];
				$i++;
			}
		//Debug($param_match_player_statistic);
		//echo "<br>getPlayerStatistic<br>";
		//if($this->uri->segment(3) == 'debug') Debug($player_statistic);
		if($query == 1) $this->xml_model->import_batch('_xml_match_player_statistic', $player_statistic);
		return $player_statistic;
	}

	function getSubstitutionsPlayer($parent_node, $param_match_substitutions, $query = 0) {
		$i = 0;
		$match_substitutions = array();
		foreach ($parent_node->substitution as $substitution) {

			$match_substitutions[$i]["match_id"] = $param_match_substitutions["match_id"];
			$match_substitutions[$i]["static_id"] = $param_match_substitutions["static_id"];
			$match_substitutions[$i]["tournament_id"] = $param_match_substitutions["tournament_id"];
			$match_substitutions[$i]["team_id"] = $param_match_substitutions["team_id"];

			$match_substitutions[$i]["on_id"] = $substitution["on_id"] == ""? 0 : (string)$substitution["on_id"];
			$match_substitutions[$i]["on"] = (string)$substitution["on"];
			$match_substitutions[$i]["off_id"] = $substitution["off_id"] == ""? 0 : (string)$substitution["off_id"];
			$match_substitutions[$i]["off"] = (string)$substitution["off"];
			$match_substitutions[$i]["minute"] = (string)$substitution["minute"];
			$i++;
		}
		//echo "<br>getSubstitutionsPlayer<br>";
		//if($this->uri->segment(3) == 'debug') Debug($match_substitutions);
		if($query == 1) $this->xml_model->import_batch('_xml_match_substitutions', $match_substitutions);
		return $match_substitutions;
	}

	function getMatchCommentaries($parent_node, $param_match_commentaries, $query = 0) {
		$i = 0;
		$match_commentaries = array();
		//Debug($param_match_commentaries);
		foreach ($parent_node->comment as $comment) {

			$match_commentaries[$i]["match_id"] = $param_match_commentaries["match_id"];
			$match_commentaries[$i]["static_id"] = $param_match_commentaries["static_id"];
			$match_commentaries[$i]["tournament_id"] = $param_match_commentaries["tournament_id"];
			//$match_commentaries[$i]["team_id"] = $param_match_commentaries["team_id"];

			$match_commentaries[$i]["comment_id"] = (string)$comment["id"];
			$match_commentaries[$i]["important"] = strtolower($comment["important"]) == "true" ? 1 : 0;
			$match_commentaries[$i]["isgoal"] = strtolower($comment["isgoal"]) == "true" ? 1 : 0;
			$match_commentaries[$i]["minute"] = $comment["minute"] == "" ? 0 : str_replace('\'', '', (string)$comment["minute"]);
			$match_commentaries[$i]["comment"] = (string)$comment["comment"];
			$i++;
		}
		//echo "<br>getMatchCommentaries<br>";
		//if($this->uri->segment(3) == 'debug') Debug($match_commentaries);
		if($query == 1) $this->xml_model->import_batch('_xml_match_commentaries', $match_commentaries);
		return $match_commentaries;
	}

	public function get_standings($url_feed, $query = 0){

		//$xmlfile = fopen($url_feed, "r") or die("Unable to open file!");
		//$xmlstr = fread($xmlfile,filesize($url_feed));
		//fclose($xmlfile);

		//$standings = new SimpleXMLElement($xmlstr);
		$standings =  simplexml_load_file($url_feed);

		$tournament = $standings->tournament;
		$num_group =  count($tournament);
		$i = $j = $query = 0;
		$param = array();

		if($this->input->get('query') == 1){
			$query = 1;
		}

		// if($tournament)
		// foreach ($tournament as $team) {

		for($i=0;$i<$num_group;$i++){

			$tournament_obj = $tournament[$i];
			$param['group'][$i]['name'] = (string)$tournament_obj['name'];
			$param['group'][$i]['date'] = (string)$tournament_obj['date'];
			$param['group'][$i]['season'] = (string)$tournament_obj['season'];
			$param['group'][$i]['round'] = (string)$tournament_obj['round'];
			$param['group'][$i]['group'] = (string)$tournament_obj['group'];
			$param['group'][$i]['groupId'] = (string)$tournament_obj['groupId'];
			$param['group'][$i]['stage_id'] = (string)$tournament_obj['stage_id'];
			$param['group'][$i]['id'] = (string)$tournament_obj['id'];
			$param['group'][$i]['is_current'] = (string)$tournament_obj['is_current'];

			// Debug($tournament_obj);

			// if($i == 1)
			// 	die();
			$j = 0;

			foreach ($tournament_obj->team as $team) {

				$param['group'][$i]['team'][$j]["country"] = (string)$standings['country'];
				$param['group'][$i]['team'][$j]["lastupdate_date"] = strtotime(str_replace('/', '-', (string)$standings['timestamp']));
				$param['group'][$i]['team'][$j]["tournament_id"] = (string)$tournament['id'];
				$param['group'][$i]['team'][$j]["tournament_name"] = $param['group'][$i]['name'];
				$param['group'][$i]['team'][$j]["season"] = $param['group'][$i]['season'];
				$param['group'][$i]['team'][$j]["round"] = $param['group'][$i]['round'];
				$param['group'][$i]['team'][$j]["stage_id"] = (string)$tournament['stage_id'];
				$param['group'][$i]['team'][$j]["group_id"] = $param['group'][$i]['groupId'];
				$param['group'][$i]['team'][$j]["group_name"] = $param['group'][$i]['group'];

				$param['group'][$i]['team'][$j]["team_position"] = (string)$team['position'];
				$param['group'][$i]['team'][$j]["team_status"] = (string)$team['status'];
				$param['group'][$i]['team'][$j]["team_id"] = (string)$team['id'];
				$param['group'][$i]['team'][$j]["team_name"] = (string)$team['name'];
				$param['group'][$i]['team'][$j]["recent_form"] = (string)$team['recent_form'];
				$param['group'][$i]['team'][$j]["description"] = (string)$team->description['value'];

				$param['group'][$i]['team'][$j]["overall_gp"] = intval((string)$team->overall['gp']);
				$param['group'][$i]['team'][$j]["overall_w"] = intval((string)$team->overall['w']);
				$param['group'][$i]['team'][$j]["overall_d"] = intval((string)$team->overall['d']);
				$param['group'][$i]['team'][$j]["overall_l"] = intval((string)$team->overall['l']);
				$param['group'][$i]['team'][$j]["overall_gs"] = intval((string)$team->overall['gs']);
				$param['group'][$i]['team'][$j]["overall_ga"] = intval((string)$team->overall['ga']);

				$param['group'][$i]['team'][$j]["home_gp"] = intval((string)$team->home['gp']);
				$param['group'][$i]['team'][$j]["home_w"] = intval((string)$team->home['w']);
				$param['group'][$i]['team'][$j]["home_d"] = intval((string)$team->home['d']);
				$param['group'][$i]['team'][$j]["home_l"] = intval((string)$team->home['l']);
				$param['group'][$i]['team'][$j]["home_gs"] = intval((string)$team->home['gs']);
				$param['group'][$i]['team'][$j]["home_ga"] = intval((string)$team->home['ga']);

				$param['group'][$i]['team'][$j]["away_gp"] = intval((string)$team->away['gp']);
				$param['group'][$i]['team'][$j]["away_w"] = intval((string)$team->away['w']);
				$param['group'][$i]['team'][$j]["away_d"] = intval((string)$team->away['d']);
				$param['group'][$i]['team'][$j]["away_l"] = intval((string)$team->away['l']);
				$param['group'][$i]['team'][$j]["away_gs"] = intval((string)$team->away['gs']);
				$param['group'][$i]['team'][$j]["away_ga"] = intval((string)$team->away['ga']);

				$param['group'][$i]['team'][$j]["total_gd"] = (string)$team->total['gd'];
				$param['group'][$i]['team'][$j]["total_p"] = intval((string)$team->total['p']);
				
				$param['group'][$i]['team'][$j]["lastupdate_date"] = date('Y-m-d H:i:s');
			
				if($query == 1){

					$this->chkupdate_xml_standing($param['group'][$i]['team'][$j]["tournament_id"], $param['group'][$i]['team'][$j]["team_id"], $param['group'][$i]['team'][$j]);
				}
				$j++;
			}

		}
		// Debug($param);
		// die();
		//Save to _xml_standing
		// if($query == 1) $this->import_batch('_xml_standing', $param);

		return $param;
	}

	public function get_topscorers($url_feed, $query = 0){

		$i=0;
		$param = array();

		//$xmlfile = fopen($url_feed, "r") or die("Unable to open file!");
		//$xmlstr = fread($xmlfile,filesize($url_feed));
		//fclose($xmlfile);
		//$topscorers = new SimpleXMLElement($xmlstr);

		$topscorers =  simplexml_load_file($url_feed);

		$sport = $topscorers['sport'];
		$tournament_id = (string)$topscorers->tournament['id'];
		$stage_id = (string)$topscorers->tournament['stage_id'];
		$tournament_name = (string)$topscorers->tournament['name'];

		foreach ($topscorers->tournament->player as $player) {

			$param[$i]['player_id'] = (string)$player['id'];
			$param[$i]['sport'] = 'soccer';
			$param[$i]['tournament_id'] = $tournament_id;
			$param[$i]['stage_id'] = $stage_id;
			$param[$i]['tournament_name'] = $tournament_name;

			$param[$i]['pos'] = (string)$player['pos'];
			$param[$i]['player_name'] = (string)$player['name'];
			$param[$i]['team_id'] = (string)$player['team_id'];
			$param[$i]['team'] = (string)$player['team'];
			$param[$i]['goals'] = (string)$player['goals'];
			$param[$i]['penalty_goals'] = (string)$player['penalty_goals'];
			$i++;
		}
		//Debug($param);
		//Save to _xml_topscorers
		if($query == 1) $this->import_batch('_xml_topscorers', $param);

		return $param;
	}

	public function get_topassits($url_feed, $query = 0){

		$i=0;
		$param = array();

		//$xmlfile = fopen($url_feed, "r") or die("Unable to open file!");
		//$xmlstr = fread($xmlfile,filesize($url_feed));
		//fclose($xmlfile);
		//$topscorers = new SimpleXMLElement($xmlstr);

		$topscorers =  simplexml_load_file($url_feed);

		$sport = $topscorers['sport'];
		$tournament_id = (string)$topscorers->tournament['id'];
		$stage_id = (string)$topscorers->tournament['stage_id'];
		$tournament_name = (string)$topscorers->tournament['name'];

		foreach ($topscorers->tournament->player as $player) {

			$param[$i]['player_id'] = (string)$player['id'];
			$param[$i]['sport'] = 'soccer';
			$param[$i]['tournament_id'] = $tournament_id;
			$param[$i]['stage_id'] = $stage_id;
			$param[$i]['tournament_name'] = $tournament_name;

			$param[$i]['pos'] = (string)$player['pos'];
			$param[$i]['player_name'] = (string)$player['name'];
			$param[$i]['team_id'] = (string)$player['team_id'];
			$param[$i]['team'] = (string)$player['team'];
			$param[$i]['assists'] = (string)$player['assists'];
			$i++;
		}
		//Debug($param);
		//Save to _xml_topscorers
		if($query == 1) $this->import_batch('_xml_topscorers', $param);

		return $param;
	}

	public function get_topcards($url_feed, $query = 0){

		$i=0;
		$param = array();

		//$xmlfile = fopen($url_feed, "r") or die("Unable to open file!");
		//$xmlstr = fread($xmlfile,filesize($url_feed));
		//fclose($xmlfile);
		//$topscorers = new SimpleXMLElement($xmlstr);

		$topscorers =  simplexml_load_file($url_feed);

		$sport = $topscorers['sport'];
		$tournament_id = (string)$topscorers->tournament['id'];
		$stage_id = (string)$topscorers->tournament['stage_id'];
		$tournament_name = (string)$topscorers->tournament['name'];

		foreach ($topscorers->tournament->player as $player) {

			$param[$i]['player_id'] = (string)$player['id'];
			$param[$i]['sport'] = 'soccer';
			$param[$i]['tournament_id'] = $tournament_id;
			$param[$i]['stage_id'] = $stage_id;
			$param[$i]['tournament_name'] = $tournament_name;

			$param[$i]['pos'] = (string)$player['pos'];
			$param[$i]['player_name'] = (string)$player['name'];
			$param[$i]['team_id'] = (string)$player['team_id'];
			$param[$i]['team'] = (string)$player['team'];
			$param[$i]['yellowcards'] = (string)$player['yellowcards'];
			$param[$i]['redcards'] = (string)$player['redcards'];
			$i++;
		}
		//Debug($param);
		//Save to _xml_topscorers
		if($query == 1) $this->import_batch('_xml_topscorers', $param);

		return $param;
	}

	public function get_odds($url_feed, $query = 0){

		//$query = 1;		
		$i = $type_id = $debug = 0;
		$obj = $param = $param_odds = $table = $data = $matches = array();

		$table['sp_xml_oddtype'] = '';
		$table['sp_xml_bet_three_way_result'] = '';
		$table['sp_xml_bet_home_away'] = '';
		$table['sp_xml_bet_over_under'] = '';
		$table['sp_xml_bet_handicap'] = '';
		$table['sp_xml_bet_btts'] = '';
		$table['sp_xml_bet_double_chance'] = '';
		$table['sp_xml_bet_3wayhandicap'] = '';
		$table['sp_xml_bet_to_qualify'] = '';

		if($this->uri->segment(3) == 'debug') $debug = 1;
		//Debug($url_feed);

		/*$xmlfile = fopen($url_feed, "r") or die("Unable to open file!");		
		$xmlstr = fread($xmlfile,filesize($url_feed));
		fclose($xmlfile);*/
		//$xmlstr =  $this->api_model->get_curl($url_feed);
		//$odds = new SimpleXMLElement($xmlstr);

		echo "<br>".anchor($url_feed, $url_feed)." : $query<br>";

		try{
			$odds =  simplexml_load_file($url_feed);
		} catch (SoapFault $fault) {
			die('Can not load XML.');
		}
		//Debug($odds);
		//die();

		$sport = $odds['sport'];
		//$tournament_id = (string)$odds->tournament['id'];
		//$stage_id = (string)$odds->tournament['stage_id'];
		//$tournament_name = (string)$odds->tournament['name'];
		$category = $odds->category;
		$allcat = count($category);
		//$allcat = 1;

		for($i=0;$i<$allcat;$i++){

			$param[$i]['id'] = (string)$category[$i]['id'];
			$param[$i]['name'] = (string)$category[$i]['name'];
			$param[$i]['file_group'] = (string)$category[$i]['file_group'];
			$param[$i]['iscup'] = (string)$category[$i]['iscup'];

			$param[$i]['matches']['date'] = (string)$category[$i]->matches['date'];
			$param[$i]['matches']['formatted_date'] = (string)$category[$i]->matches['formatted_date'];

			$matches = $category[$i]->matches->match;

			//count($param[$i]['matches']);
			$allmatch = count($matches);
			//die();
			for($j=0;$j<$allmatch;$j++){

				//$param[$i]['matches']['match'][$j]['id'] = (string)$matches[$j]['id'];

				$param[$i]['matches']['match'][$j]['status'] = (string)$matches[$j]['status'];
				$param[$i]['matches']['match'][$j]['date'] = (string)$matches[$j]['date'];
				$param[$i]['matches']['match'][$j]['formatted_date'] = (string)$matches[$j]['formatted_date'];
				$param[$i]['matches']['match'][$j]['time'] = (string)$matches[$j]['time'];

				$static_id = $param[$i]['matches'][$j]['match']['static_id'] = (string)$matches[$j]['static_id'];
				$fix_id = $param[$i]['matches'][$j]['match']['fix_id'] = (string)$matches[$j]['fix_id'];
				$match_id = $param[$i]['matches'][$j]['match']['id'] = (string)$matches[$j]['id'];

				$param[$i]['matches']['match'][$j]['match_id'] = $match_id;
				$param[$i]['matches']['match'][$j]['fix_id'] = $fix_id;
				$param[$i]['matches']['match'][$j]['static_id'] = $static_id;

				$param[$i]['matches']['match'][$j]['home_id'] = (string)$matches[$j]->localteam['id'];
				$param[$i]['matches']['match'][$j]['home_name'] = (string)$matches[$j]->localteam['name'];
				$param[$i]['matches']['match'][$j][$j]['home_goals'] = (string)$matches[$j]->localteam['goals'];
				$param[$i]['matches']['match'][$j]['away_id'] = (string)$matches[$j]->visitorteam ['id'];
				$param[$i]['matches']['match'][$j]['away_name'] = (string)$matches[$j]->visitorteam ['name'];
				$param[$i]['matches']['match'][$j]['away_goals'] = (string)$matches[$j]->visitorteam ['goals'];

				if(isset($matches[$j]->events) )
					$param[$i]['matches']['match'][$j]['events'] = (string)$matches[$j]->events;

				if(isset($matches[$j]->ht['score']) )
					$param[$i]['matches']['match'][$j]['ht'] = (string)$matches[$j]->ht['score'];

				//$param[$i]['matches']['match']['odds'] = (string)$matches[$j]->ht['score'];

				foreach ($matches[$j]->odds->type as $type) {
					$type_id = (string)$type['id'];
					//Debug('Type '.$type_id);
					//echo '<hr>';
					if($type_id == 1 || $type_id == 5 || $type_id == 33 || $type_id == 34) {

						//3Way Result
						//3Way Result 1st Half
						//Team To Score First
						//Team To Score Last
						$itemall = count($type);
						$param_odd = array();
						$n=0;
						foreach ($type->bookmaker as $bookmaker) {
							$bookmaker_id = (string)$bookmaker['id'];

							$param_odd[$n]["match_id"] = $match_id;
							$param_odd[$n]["fix_id"] = $fix_id;
							$param_odd[$n]["static_id"] = $static_id;
							$param_odd[$n]["oddtype_id"] = (string)$type_id;
							$param_odd[$n]["betcompany_id"] = (string)$bookmaker_id;

							foreach ($bookmaker->odd as $odd) {
								if($odd['name'] == 1){
									$param_odd[$n]["home_win"] = (string)$odd['value'];
								} else if($odd['name'] == 2) {
									$param_odd[$n]["away_win"] = (string)$odd['value'];
								} else if($odd['name'] == 'X') {
									$param_odd[$n]["draw"] = (string)$odd['value'];
								}
							}

							if($query == 1){
								$data = array(
									'fix_id' => $fix_id,
									'betcompany_id' => $param_odd[$n]["betcompany_id"],
									'oddtype_id' => $param_odd[$n]["oddtype_id"]
								);
								//Debug($data);
								$this->chkupdate_array('_xml_bet_three_way_result', $data, $param_odd[$n]);
								//Debug($this->db->last_query());
							}
							$n++;
						}
						//die();
						//Debug($param_odd);

						//if($debug == 1) Debug($param_odd);
						$table['sp_xml_bet_three_way_result'] = $param_odd;
						//$this->import('_xml_bet_three_way_result', $param_odd);
						/*if($query == 1){
							$this->import_batch('_xml_bet_three_way_result', $param_odd);
							Debug($this->db->last_query());
						}*/
						$param_odds[$type_id] = $param_odd;

					} else if($type_id == 2) {

						//Home/Away
						$itemall = count($type);
						$param_odd = array();
						$n=0;

						foreach ($type->bookmaker as $bookmaker) {
							$bookmaker_id = (string)$bookmaker['id'];

							$param_odd[$n]["match_id"] = $match_id;
							$param_odd[$n]["fix_id"] = $fix_id;
							$param_odd[$n]["static_id"] = $static_id;
							$param_odd[$n]["oddtype_id"] = (string)$type_id;
							$param_odd[$n]["betcompany_id"] = (string)$bookmaker_id;

							foreach ($bookmaker->odd as $odd) {
								if($odd['name'] == 1) {
									$param_odd[$n]["home_win"] = (string)$odd['value'];
								} else if($odd['name'] == 2) {
									$param_odd[$n]["away_win"] = (string)$odd['value'];
								}
							}
							if($query == 1){
								$data = array(
									'fix_id' => $fix_id,
									'betcompany_id' => $param_odd[$n]["betcompany_id"],
									'oddtype_id' => $param_odd[$n]["oddtype_id"]
								);
								$this->chkupdate_array('sp_xml_bet_home_away', $data, $param_odd[$n]);
								//Debug($this->db->last_query());
							}
							$n++;
						}
						//if($debug == 1) Debug($param_odd);
						$table['sp_xml_bet_home_away'] = $param_odd;
						/*if($query == 1){
							$this->import_batch('sp_xml_bet_home_away', $param_odd);
							Debug($this->db->last_query());
						}*/
						$param_odds[$type_id] = $param_odd;

					} else if($type_id == 3 || $type_id == 7 || $type_id == 29) {

						//Over/Under
						//Over/Under 1st Half
						//Over/Under 2nd Half
						$itemall = count($type);
						$param_odd = array();
						$n=0;
						foreach ($type->bookmaker as $bookmaker) {
							$bookmaker_id = (string)$bookmaker['id'];

							$param_odd[$n]["match_id"] = $match_id;
							$param_odd[$n]["fix_id"] = $fix_id;
							$param_odd[$n]["static_id"] = $static_id;
							$param_odd[$n]["oddtype_id"] = (string)$type_id;
							$param_odd[$n]["betcompany_id"] = (string)$bookmaker_id;

							foreach ($bookmaker->total as $total) {

								$point = (string)$total['name'];
								$main = (string)$total['main'];

								$param_odd[$n]["point"] = $point;
								$param_odd[$n]["main"] = $main;

								foreach ($total->odd as $odd) {
									if($odd['name'] == 'Under') {
										$param_odd[$n]["under"] = (string)$odd['value'];
									} else if($odd['name'] == 'Over') {
										$param_odd[$n]["over"] = (string)$odd['value'];
									}
								}
							}
							if($query == 1){
								$data = array(
									'fix_id' => $fix_id,
									'betcompany_id' => $param_odd[$n]["betcompany_id"],
									'oddtype_id' => $param_odd[$n]["oddtype_id"]
								);
								$this->chkupdate_array('sp_xml_bet_over_under', $data, $param_odd[$n]);
								//Debug($this->db->last_query());
							}
							$n++;
						}
						//if($debug == 1) Debug($param_odd);
						$table['sp_xml_bet_over_under'] = $param_odd;
						/*if($query == 1){
							$this->import_batch('sp_xml_bet_over_under', $param_odd);
							Debug($this->db->last_query());
						}*/
						$param_odds[$type_id] = $param_odd;

					} else if($type_id == 4 || $type_id == 79) {

						//Handicap
						//3Way Handicap
						$itemall = count($type);
						$param_odd = array();
						$n=0;

						//Debug($type->bookmaker);
						//die();
						foreach ($type->bookmaker as $bookmaker) {

							$bookmaker_id = (string)$bookmaker['id'];
							$param_odd[$n]["match_id"] = $match_id;
							$param_odd[$n]["fix_id"] = $fix_id;
							$param_odd[$n]["static_id"] = $static_id;
							$param_odd[$n]["oddtype_id"] = (string)$type_id;
							$param_odd[$n]["betcompany_id"] = (string)$bookmaker_id;

							foreach ($bookmaker->handicap as $handicap) {

								$point = (string)$handicap['name'];
								$main = (string)$handicap['main'];
								$param_odd[$n]["point"] = $point;
								$param_odd[$n]["main"] = $main;

								foreach ($handicap->odd as $odd) {
									if($odd['name'] == 1) {
										$param_odd[$n]["localteam"] = (string)$odd['value'];
										$param_odd[$n]["visitorteam"] = '';
									} else if($odd['name'] == 2) {
										$param_odd[$n]["visitorteam"] = (string)$odd['value'];
										$param_odd[$n]["localteam"] = '';
									} else if($odd['name'] == 'X') {
										$param_odd[$n]["draw"] = (string)$odd['value'];
									}
								}

								if($query == 1){
									if($type_id == 4)
										$tablename = 'sp_xml_bet_handicap';
									else
										$tablename = 'sp_xml_bet_3wayhandicap';
									$data = array(
										'fix_id' => $fix_id,
										'betcompany_id' => $param_odd[$n]["betcompany_id"],
										'oddtype_id' => $param_odd[$n]["oddtype_id"]
									);

									//if($param_odd[$n]["betcompany_id"] == 14){
									//Delete Data
									//$this->delete_data($tablename, $data);
									//$this->chkupdate_array($tablename, $data, $param_odd[$n]);
									//Debug($param_odd[$n]);
									$this->import($tablename, $param_odd[$n]);
									//}
								}
								//Insert DB Here
							}
							$n++;
						}
						//$this->delete_data($tablename, $data);
						//$this->import_batch($tablename, $param_odd);
						//Debug($this->db->last_query());

						//if($debug == 1) Debug($param_odd);
						/*if($type_id == 4){
							$table['sp_xml_bet_handicap'] = $param_odd;
							if($query == 1){
								$this->import_batch('sp_xml_bet_handicap', $param_odd); //if($type_id == 4)
								Debug($this->db->last_query());
							}
						}else{
							$table['sp_xml_bet_3wayhandicap'] = $param_odd;
							if($query == 1){
								$this->import_batch('sp_xml_bet_3wayhandicap', $param_odd); //if($type_id == 79)
								Debug($this->db->last_query());
							}
						}*/
						$param_odds[$type_id] = $param_odd;

					}else if($type_id == 25){

						//Both Teams to Score
						$itemall = count($type);
						$param_odd = array();
						$n=0;
						foreach ($type->bookmaker as $bookmaker) {
							$bookmaker_id = (string)$bookmaker['id'];
							$param_odd[$n]["match_id"] = $match_id;
							$param_odd[$n]["fix_id"] = $fix_id;
							$param_odd[$n]["static_id"] = $static_id;
							$param_odd[$n]["oddtype_id"] = (string)$type_id;
							$param_odd[$n]["betcompany_id"] = (string)$bookmaker_id;
							foreach ($bookmaker->odd as $odd) {
								if($odd['name'] == 'Yes') {
									$param_odd[$n]["yes"] = (string)$odd['value'];
								} else if($odd['name'] == 'No') {
									$param_odd[$n]["no"] = (string)$odd['value'];
								}
							}
							if($query == 1){
								$data = array(
									'fix_id' => $fix_id,
									'betcompany_id' => $param_odd[$n]["betcompany_id"],
									'oddtype_id' => $param_odd[$n]["oddtype_id"]
								);
								$this->chkupdate_array('sp_xml_bet_btts', $data, $param_odd[$n]);
								//Debug($this->db->last_query());
							}
							$n++;
						}
						//if($debug == 1) Debug($param_odd);
						/*$table['sp_xml_bet_btts'] = $param_odd;
						if($query == 1){
							$this->import_batch('sp_xml_bet_btts', $param_odd);
							Debug($this->db->last_query());
						}*/
						$param_odds[$type_id] = $param_odd;

					} else if($type_id == 30) {

						//Double Chance
						$itemall = count($type);
						$param_odd = array();
						$n=0;
						foreach($type->bookmaker as $bookmaker){
							$bookmaker_id = (string)$bookmaker['id'];

							$param_odd[$n]["match_id"] = $match_id;
							$param_odd[$n]["fix_id"] = $fix_id;
							$param_odd[$n]["static_id"] = $static_id;
							$param_odd[$n]["oddtype_id"] = (string)$type_id;
							$param_odd[$n]["betcompany_id"] = (string)$bookmaker_id;

							foreach ($bookmaker->odd as $odd) {
								if($odd['name'] == '1X') {
									$param_odd[$n]["onex"] = (string)$odd['value'];
								} else if($odd['name'] == 'X2') {
									$param_odd[$n]["xtwo"] = (string)$odd['value'];
								} else if($odd['name'] == '12') {
									$param_odd[$n]["onetwo"] = (string)$odd['value'];
								}
							}
							if($query == 1){
								$data = array(
									'fix_id' => $fix_id,
									'betcompany_id' => $param_odd[$n]["betcompany_id"],
									'oddtype_id' => $param_odd[$n]["oddtype_id"]
								);
								$this->chkupdate_array('sp_xml_bet_double_chance', $data, $param_odd[$n]);
								//Debug($this->db->last_query());
							}
							$n++;
						}
						//if($debug == 1) Debug($param_odd);
						/*$table['sp_xml_bet_double_chance'] = $param_odd;					
						if($query == 1){
							$this->import_batch('sp_xml_bet_double_chance', $param_odd);					
							Debug($this->db->last_query());
						}*/
						$param_odds[$type_id] = $param_odd;

					}else if($type_id == 32) {

						//To Qualify
						$itemall = count($type);
						$param_odd = array();
						$n=0;
						foreach($type->bookmaker as $bookmaker){
							$bookmaker_id = (string)$bookmaker['id'];

							$param_odd[$n]["match_id"] = $match_id;
							$param_odd[$n]["fix_id"] = $fix_id;
							$param_odd[$n]["static_id"] = $static_id;
							$param_odd[$n]["oddtype_id"] = (string)$type_id;
							$param_odd[$n]["betcompany_id"] = (string)$bookmaker_id;

							foreach ($bookmaker->odd as $odd) {
								if($odd['name'] == '1') {
									$param_odd[$n]["home"] = (string)$odd['value'];
								} else if($odd['name'] == '2') {
									$param_odd[$n]["away"] = (string)$odd['value'];
								}
							}
							if($query == 1){
								$data = array(
									'fix_id' => $fix_id,
									'betcompany_id' => $param_odd[$n]["betcompany_id"],
									'oddtype_id' => $param_odd[$n]["oddtype_id"]
								);
								$this->chkupdate_array('sp_xml_bet_to_qualify', $data, $param_odd[$n]);
								//Debug($this->db->last_query());
							}
							$n++;
						}
						//if($debug == 1) Debug($param_odd);
						/*$table['sp_xml_bet_to_qualify'] = $param_odd;
						if($query == 1){
							$this->import_batch('sp_xml_bet_to_qualify', $param_odd);
							Debug($this->db->last_query());
						}*/
						$param_odds[$type_id] = $param_odd;

					}else{
						//echo "[$type_id]";
					}
				}

			}


			//echo count($param[$i]['matches']['match']).'<br>';
			$param[$i]['type'] = $param_odds;
		}
		return $param;
	}

	public function get_soccernew($url_feed, $query = 0){

		$arr = $temp = $param = array();
		//Debug($url_feed);
		try {
			$xmlelement = simplexml_load_file($url_feed);
		}catch(Exception $e){
			echo $e->getMessage();
			die();
		}
		$i = 0;
		//Debug($xmlelement);
		//die();
		if($xmlelement)
			//foreach($xmlelement as $key1 => $val1){
			for($i=0;$i<count($xmlelement->category);$i++){

				$cat = $xmlelement->category;
				$param['category'][$i]['id'] = (string)$cat[$i]['id'];
				$param['category'][$i]['name'] = (string)$cat[$i]['name'];
				$param['category'][$i]['file_group'] = (string)$cat[$i]['file_group'];

				//$param['category'][$i]['iscup'] = (string)($cat[$i]['iscup'] == "True") ? 0:1;
				$param['category'][$i]['iscup'] = (string)$cat[$i]['iscup'];

				//if($param['category'][$i]['id'] == 2607){// Select Leaguage

				$param['category'][$i]['matches']['date'] = (string)$cat[$i]->matches['date'];
				$param['category'][$i]['matches']['formatted_date'] = (string)$cat[$i]->matches['formatted_date'];
				$param['category'][$i]['matches']['count_match'] = count($cat[$i]->matches->match);

				for($j=0;$j<$param['category'][$i]['matches']['count_match'];$j++){

					$param['category'][$i]['matches']['match'][$j]['id'] = (string)$cat[$i]->matches->match[$j]['id'];
					$param['category'][$i]['matches']['match'][$j]['status'] = (string)$cat[$i]->matches->match[$j]['status'];
					$param['category'][$i]['matches']['match'][$j]['date'] = (string)$cat[$i]->matches->match[$j]['date'];
					$param['category'][$i]['matches']['match'][$j]['formatted_date'] = (string)$cat[$i]->matches->match[$j]['formatted_date'];
					$param['category'][$i]['matches']['match'][$j]['time'] = (string)$cat[$i]->matches->match[$j]['time'];
					$param['category'][$i]['matches']['match'][$j]['static_id'] = (string)$cat[$i]->matches->match[$j]['static_id'];
					$param['category'][$i]['matches']['match'][$j]['fix_id'] = (string)$cat[$i]->matches->match[$j]['fix_id'];

					$param['category'][$i]['matches']['match'][$j]['localteam']['id'] = (string)$cat[$i]->matches->match[$j]->localteam['id'];
					$param['category'][$i]['matches']['match'][$j]['localteam']['name'] = (string)$cat[$i]->matches->match[$j]->localteam['name'];
					$param['category'][$i]['matches']['match'][$j]['localteam']['goals'] = (string)$cat[$i]->matches->match[$j]->localteam['goals'];
					$param['category'][$i]['matches']['match'][$j]['visitorteam']['id'] = (string)$cat[$i]->matches->match[$j]->visitorteam['id'];
					$param['category'][$i]['matches']['match'][$j]['visitorteam']['name'] = (string)$cat[$i]->matches->match[$j]->visitorteam['name'];
					$param['category'][$i]['matches']['match'][$j]['visitorteam']['goals'] = (string)$cat[$i]->matches->match[$j]->visitorteam['goals'];

					if(isset($cat[$i]->matches->match[$j]->events)){
						
						$events = $cat[$i]->matches->match[$j]->events->event;
						for($k=0;$k<count($events);$k++){

							$param['category'][$i]['matches']['match'][$j]['events'][$k]['eventid'] = (string)$events[$k]['eventid'];
							$param['category'][$i]['matches']['match'][$j]['events'][$k]['type'] = (string)$events[$k]['type'];
							$param['category'][$i]['matches']['match'][$j]['events'][$k]['team'] = (string)$events[$k]['team'];
							$param['category'][$i]['matches']['match'][$j]['events'][$k]['minute'] = (string)$events[$k]['minute'];
							$param['category'][$i]['matches']['match'][$j]['events'][$k]['result'] = (string)$events[$k]['result'];
							$param['category'][$i]['matches']['match'][$j]['events'][$k]['playerid'] = (string)$events[$k]['playerId'];
							$param['category'][$i]['matches']['match'][$j]['events'][$k]['player'] = (string)$events[$k]['player'];
							$param['category'][$i]['matches']['match'][$j]['events'][$k]['assistid'] = (string)$events[$k]['assistid'];
							$param['category'][$i]['matches']['match'][$j]['events'][$k]['assist'] = (string)$events[$k]['assist'];
						}
						//$param['category'][$i]['matches']['match'][$j]['events'] = $cat[$i]->matches->match[$j]->events;
					}

					if(isset($cat[$i]->matches->match[$j]->ht))
						$param['category'][$i]['matches']['match'][$j]['ht'] = (string)$cat[$i]->matches->match[$j]->ht['score'];

					if(isset($cat[$i]->matches->match[$j]->ft))
						$param['category'][$i]['matches']['match'][$j]['ft'] = (string)$cat[$i]->matches->match[$j]->ft['score'];

					if(isset($cat[$i]->matches->match[$j]->et))
						$param['category'][$i]['matches']['match'][$j]['et'] = (string)$cat[$i]->matches->match[$j]->et['score'];

					if(isset($cat[$i]->matches->match[$j]->penalty)){

						$penalty_localteam = (string)$cat[$i]->matches->match[$j]->penalty['localteam'];
						$penalty_visitorteam = (string)$cat[$i]->matches->match[$j]->penalty['visitorteam'];
						$penalty_result =  "$penalty_localteam-$penalty_visitorteam";

						$param['category'][$i]['matches']['match'][$j]['penalty'] = $penalty_result;

					}
						
				}

				//}

			}
		//Debug($param);
		//die();
		return $param;
	}

	public function get_highlights($url_feed, $query = 0){

		$arr = $temp = $param = array();
		//Debug($url_feed);
		try {
			$xmlelement = simplexml_load_file($url_feed);
		}catch(Exception $e){
			echo $e->getMessage();
			die();
		}
		$i = 0;
		// Debug($xmlelement);
		// die();
		if($xmlelement)
			//foreach($xmlelement as $key1 => $val1){
			for($i=0;$i<count($xmlelement->category);$i++){

				$sport = (string)$xmlelement->sport;
				$cat = $xmlelement->category;

				$param['category'][$i]['id'] = (string)$cat[$i]['id'];
				$param['category'][$i]['sport'] = (string)$sport;
				$param['category'][$i]['name'] = (string)$cat[$i]['name'];


				//if($param['category'][$i]['id'] == 2607){// Select Leaguage

				$param['category'][$i]['matches']['date'] = (string)$cat[$i]->matches['date'];
				$param['category'][$i]['matches']['formatted_date'] = (string)$cat[$i]->matches['formatted_date'];

				$param['category'][$i]['matches']['count_match'] = count($cat[$i]->matches->match);

				for($j=0;$j<$param['category'][$i]['matches']['count_match'];$j++){

					$param['category'][$i]['matches']['match'][$j]['id'] = (string)$cat[$i]->matches->match[$j]['id'];
					$param['category'][$i]['matches']['match'][$j]['status'] = (string)$cat[$i]->matches->match[$j]['status'];
					$param['category'][$i]['matches']['match'][$j]['date'] = (string)$cat[$i]->matches->match[$j]['date'];
					$param['category'][$i]['matches']['match'][$j]['formatted_date'] = (string)$cat[$i]->matches->match[$j]['formatted_date'];
					$param['category'][$i]['matches']['match'][$j]['time'] = (string)$cat[$i]->matches->match[$j]['time'];
					$param['category'][$i]['matches']['match'][$j]['static_id'] = (string)$cat[$i]->matches->match[$j]['static_id'];
					$param['category'][$i]['matches']['match'][$j]['fix_id'] = (string)$cat[$i]->matches->match[$j]['fix_id'];

					$param['category'][$i]['matches']['match'][$j]['localteam']['id'] = (string)$cat[$i]->matches->match[$j]->localteam['id'];
					$param['category'][$i]['matches']['match'][$j]['localteam']['name'] = (string)$cat[$i]->matches->match[$j]->localteam['name'];
					$param['category'][$i]['matches']['match'][$j]['localteam']['goals'] = (string)$cat[$i]->matches->match[$j]->localteam['goals'];

					$param['category'][$i]['matches']['match'][$j]['visitorteam']['id'] = (string)$cat[$i]->matches->match[$j]->visitorteam['id'];
					$param['category'][$i]['matches']['match'][$j]['visitorteam']['name'] = (string)$cat[$i]->matches->match[$j]->visitorteam['name'];
					$param['category'][$i]['matches']['match'][$j]['visitorteam']['goals'] = (string)$cat[$i]->matches->match[$j]->visitorteam['goals'];

					// $param['category'][$i]['matches']['match'][$j]['files'] = $cat[$i]->matches->match[$j]->files->item;
					if(isset($cat[$i]->matches->match[$j]->files)){
						
						$files_item = $cat[$i]->matches->match[$j]->files->item;
						for($k=0;$k<count($files_item);$k++){

							$param['category'][$i]['matches']['match'][$j]['files'][$k]['name'] = (string)$files_item[$k]['name'];
							$param['category'][$i]['matches']['match'][$j]['files'][$k]['value'] = (string)$files_item[$k];
						}
						//$param['category'][$i]['matches']['match'][$j]['events'] = $cat[$i]->matches->match[$j]->events;
					}

					// $param['category'][$i]['matches']['match'][$j]['clips'] = $cat[$i]->matches->match[$j]->clips->item;
					if(isset($cat[$i]->matches->match[$j]->clips)){
						
						$clips_item = $cat[$i]->matches->match[$j]->clips->item;
						for($k=0;$k<count($clips_item);$k++){

							$param['category'][$i]['matches']['match'][$j]['clips'][$k]['type'] = (string)$clips_item[$k]['type'];
							$param['category'][$i]['matches']['match'][$j]['clips'][$k]['team'] = (string)$clips_item[$k]['team'];
							$param['category'][$i]['matches']['match'][$j]['clips'][$k]['score'] = (string)$clips_item[$k]['score'];
							$param['category'][$i]['matches']['match'][$j]['clips'][$k]['minute'] = (string)$clips_item[$k]['minute'];
							$param['category'][$i]['matches']['match'][$j]['clips'][$k]['playerId'] = (string)$clips_item[$k]['playerId'];
							$param['category'][$i]['matches']['match'][$j]['clips'][$k]['player'] = (string)$clips_item[$k]['player'];
							$param['category'][$i]['matches']['match'][$j]['clips'][$k]['value'] = (string)$clips_item[$k];
						}
						//$param['category'][$i]['matches']['match'][$j]['events'] = $cat[$i]->matches->match[$j]->events;
					}
				}

				//}

			}
		//Debug($param);
		//die();
		return $param;
	}

	public function get_xml_standing($team_id, $tournament_id){

		$this->db->select('*');
		$this->db->from($this->prefix.'_xml_standing');
		$this->db->where('team_id', $team_id);
		$this->db->where('tournament_id', $tournament_id);

		$query = $this->db->get();

		return $query->result_array();
	}

	public function get_team_league($team_id, $league_id){

		$this->db->select('*');
		$this->db->from($this->prefix.'_team_league');
		$this->db->where('team_id', $team_id);
		$this->db->where('league_id', $league_id);

		$query = $this->db->get();

		return $query->result_array();
	}

}
?>	
