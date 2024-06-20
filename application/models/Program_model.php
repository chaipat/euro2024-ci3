<?php
class Program_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
        
		$this->load->database();

		$this->prefix = $this->db->dbprefix;
    }

    public function get_status($id, $showdebug = 0){

    	$this->db->select('_program.`status`, concat(`hometeam_title`," vs ", `awayteam_title`) as `title`', false);
    	$this->db->from($this->prefix.'_program');
    	$this->db->where('program_id', $id);
    	$query = $this->db->get();
    	if($showdebug == 1) Debug($this->db->last_query());
    	return $query->result_array();
    }

    public function get_max_id(){

		$this->db->select('max(program_id) as max_id');
		$this->db->from($this->prefix.'_program');
		$this->db->where('program_id <', 1900000);
		$query = $this->db->get();
		$res = $query->result_array();
		return $res[0]['max_id'];
    }

    public function getSelect($default = 0, $name = "program_id", $site_id = 0){
    		
		$language = $this->lang->language;
		$first = "--- ".$language['please_select'].$language['match']." ---";
		
		if($site_id == 0) 
			$rows = $this->get_program(null, 0, 1);
		else
			$rows = $this->get_program(null, $site_id, 1);
		//Debug($rows);
		$opt = array();
		$opt[]	= makeOption(0,$first);
		for($i=0;$i<count($rows);$i++){
			$row = @$rows[$i];
			$opt[] = makeOption($row->program_id, $row->section_name);
		}
		return selectList($opt, $name, 'class="form-control"', 'value', 'text', $default);
    }

    public function get_program($program_id=null, $datebetween = null, $league_id = null, $active = '', $order = 0,  $showdebug = 0){

		$language = $this->lang->language['lang'];
		$prefix = $this->prefix;

		$this->db->select($this->prefix.'_program.*, league_name');
		$this->db->select('(SELECT `'.$prefix.'_team_formation`.`formation` FROM `'.$prefix.'_team_formation` WHERE `'.$prefix.'_team_formation`.`tfid`=`'.$prefix.'_program`.`hometeam_formation`) AS home_formation');
		$this->db->select('(SELECT `'.$prefix.'_team_formation`.`formation` FROM `'.$prefix.'_team_formation` WHERE `'.$prefix.'_team_formation`.`tfid`=`'.$prefix.'_program`.`awayteam_formation`) AS away_formation');
		$this->db->select($this->prefix.'_price.price_point, '.$prefix.'_price_num.num_name');
		$this->db->select($this->prefix.'_season.season_name, '.$prefix.'_season.season_name2');

		$this->db->from($this->prefix.'_program');
		$this->db->join($this->prefix.'_league', $this->prefix.'_program.league_id = '.$prefix.'_league.league_id', 'left');
		$this->db->join($this->prefix.'_season', $this->prefix.'_program.season = '.$prefix.'_season.season_id', 'left');
		//$this->db->join('_team_formation', '_team_formation.tfid = _program.hometeam_formation', 'left');
		$this->db->join($this->prefix.'_price', $this->prefix.'_program.price_id = '.$prefix.'_price.price_id', 'left');
		$this->db->join($this->prefix.'_price_num', $this->prefix.'_program.num_id = '.$prefix.'_price_num.num_id', 'left');

		if($program_id != null && $program_id > 0){
			$this->db->where($this->prefix.'_program.program_id', $program_id);
		}
		if($league_id != null && $league_id > 0){
			$this->db->where($this->prefix.'_program.league_id', $league_id);
		}

		if($active == 1){
			$this->db->where($this->prefix.'_program.status', 1);
		}else{
			//$this->db->where('lang', $language);
			$this->db->where($this->prefix.'_program.status !=', 9);
		}

		if($datebetween){
			$this->db->where($this->prefix.'_program.kickoff BETWEEN "'.$datebetween[0].'" AND "'.$datebetween[1].'"');
		}

		if($order == 1) {
			$this->db->where($this->prefix.'_program.order >', 0);
			$this->db->order_by($this->prefix.'_program.order', 'Asc');
		}else if($order == 2){
			$this->db->where($this->prefix.'_program.order IS NULL');
		}

		$this->db->order_by($this->prefix.'_program.kickoff', 'Asc');
		$this->db->order_by($this->prefix.'_program.program_id', 'Asc');
		//$this->db->limit($listpage, $limit_start);

		$query = $this->db->get();
		if($showdebug == 1) Debug($this->db->last_query());
		$data1 = $query->result_object();		
		return $data1;
    }

    public function chkupdate_data($table, $field ='id', &$data = array(), $showdebug = 0){

		$this->db->select('*');
    	$this->db->from($this->prefix.$table);
    	$this->db->where($field, $data[$field]);
    	$query = $this->db->get();

    	//Debug($this->db->last_query());
    	$res = $query->result_array();
    	if(!$res){
			$insert = $this->db->insert($table, $data);
			if($showdebug == 1) Debug($this->db->last_query());
			return $insert;
		}else {

			unset($data['hometeam_title']);
			unset($data['awayteam_title']);

			if($field == 'fix_id'){
				$this->update_fixid($data['fix_id'], $data);
			}else
				$this->update_program($data['program_id'], $data);	

			if($showdebug == 1) Debug($this->db->last_query());
			return $res;
			//return true;
		}
	}

	public function add_batch(&$data, $table = '_program', $debug = 0){

		if($debug == 1) Debug($data);
		$insert = $this->db->insert_batch($this->prefix.$table, $data);
		if($debug == 1) Debug($this->db->last_query());
		return $insert;
	}

	function chk_program($program_id, $data, $update_db = 0){

		$this->db->select('program_id');
    	$this->db->from($this->prefix.'_program');
    	$this->db->where('program_id', $program_id);
    	$query = $this->db->get();

		$res = $query->result_array();
    	if(!$res){

			if($update_db == 1){

				$data['create_date'] = date('Y-m-d H:i:s');
				$data['create_by'] = 1;
				$this->insert_program($data);
				Debug('Insert program '.$program_id.' '.$data['hometeam_title'].' '.$data['hometeam_point'].' '.$data['awayteam_point'].' '.$data['awayteam_title'].' '.$data['kickoff'].' '.$data['league_id']);
			}
			
		}else{

			// if($update_db == 1){

				$this->update_program($program_id, $data);
				Debug('Update program '.$program_id.' '.$data['hometeam_title'].' '.$data['hometeam_point'].' '.$data['awayteam_point'].' '.$data['awayteam_title'].' '.$data['kickoff'].' '.$data['league_id']);
			// }
		}
		// Debug($this->db->last_query());
		// return true;
	}

	function chk_program_fixid($fix_id, $data, $update_db = 0){

		$this->db->select('program_id');
    	$this->db->from($this->prefix.'_program');
    	$this->db->where('fix_id', $fix_id);
    	$query = $this->db->get();

		$res = $query->result_array();
    	if(!$res){

			$data['create_date'] = date('Y-m-d H:i:s');
			$data['create_by'] = 1;
			$this->insert_program($data);
			Debug('Insert program '.$fix_id.' '.$data['hometeam_title'].' '.$data['hometeam_point'].' '.$data['awayteam_point'].' '.$data['awayteam_title'].' '.$data['kickoff'].' '.$data['league_id']);
		}else{

			$program_id = $res[0]['program_id'];

			$this->update_program($program_id, $data);
			Debug('Update program '.$fix_id.' '.$data['hometeam_title'].' '.$data['hometeam_point'].' '.$data['awayteam_point'].' '.$data['awayteam_title'].' '.$data['kickoff'].' '.$data['league_id']);
		}
		Debug($this->db->last_query());
		// return true;
	}

    function insert_program($data, $showdebug = 0){

		$insert = $this->db->insert($this->prefix.'_program', $data);

		if($showdebug == 1) 
			Debug($this->db->last_query());
			
	    return $insert;
	}

    function update_program($program_id, $data, $showdebug = 0){

		$this->db->where('program_id', $program_id);
		$this->db->update($this->prefix.'_program', $data);

		if($showdebug == 1) 
			Debug($this->db->last_query());

		return true;
	}

	function insert_update_program2($data, $showdebug = 0){

		$sql = 'INSERT INTO `'.$this->prefix.'_program` 
		(`program_id`, `fix_id`, `league_id`, `kickoff`, `status`, `hometeam_id`, `hometeam_title`, `hometeam_point`, `awayteam_id`, `awayteam_title`, `awayteam_point`, `lastupdate_date`, `lastupdate_by`)
        VALUES 
		('.$data['program_id'].', '.$data['fix_id'].', '.$data['league_id'].', \''.$data['kickoff'].'\', \''.$data['status'].'\', '.$data['hometeam_id'].', \''.$data['hometeam_title'].'\', \''.$data['hometeam_point'].'\', \''.$data['awayteam_id'].'\', \''.$data['awayteam_title'].'\', \''.$data['awayteam_point'].'\', \''.$data['lastupdate_date'].'\', 0)
        ON DUPLICATE KEY UPDATE 
			`hometeam_point`=VALUES('.$data['hometeam_point'].'),
			`awayteam_point`=VALUES('.$data['awayteam_point'].'),
			`status`=VALUES('.$data['status'].'), 
            `kickoff`=VALUES('.$data['kickoff'].')';

		$query = $this->db->query($sql, $data);
	}

	function insert_update_program($program_id, $data, $showdebug = 0){

		$sql = 'INSERT INTO `'.$this->prefix.'_program` (`program_id`, `fix_id`, `season`, `league_id`, `stadium_id`, `kickoff`, `week`, `status`, `hometeam_id`, `hometeam_title`, `hometeam_point`, `awayteam_id`, `awayteam_title`, `awayteam_point`, `lastupdate_date`, `lastupdate_by`)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
            `kickoff`=VALUES('.$data['kickoff'].'), 
            `stadium_id`=VALUES('.$data['stadium_id'].'), 
            `status`=VALUES('.$data['status'].'), 
            `week`=VALUES('.$data['week'].')';

		$query = $this->db->query($sql, $data);
	}

	function chk_fixid($fix_id){

		$this->db->select('program_id, fix_id');
    	$this->db->from('_program');
    	$this->db->where('fix_id', $fix_id);

    	$query = $this->db->get();

    	$res = $query->result_array();
    	// if(!$res)
    	// 	return false;
    	// else
    	// 	return true;
		return $res;
	}

	function update_fixid($fix_id, $data, $showdebug = 0){

		$this->db->where('fix_id', $fix_id);
		$this->db->update('_program', $data);

		if($showdebug == 1) 
			Debug($this->db->last_query());
		// $report = array();
		// $report['error'] = $this->db->_error_number();
		// $report['message'] = $this->db->_error_message();
		// if($report !== 0){
		// 	return true;
		// }else{
		// 	return false;
		// }
		return true;
	}

	function status_program($program_id, $enable = 1){

		$data['status'] = $enable;
		$this->db->where('program_id', $program_id);
		$this->db->update('_program', $data);
		
		//echo $this->db->last_query()
		// $report = array();
		// $report['error'] = $this->db->_error_number();
		// $report['message'] = $this->db->_error_message();
		// if($report !== 0){
		// 	return true;
		// }else{
		// 	return false;
		// }
		return $res;
	}	

    function delete_program($program_id){

		$this->delete_program_by_admin($program_id);

		/*$data = array(
			'status' => 9
		);
		$this->db->where('program_id', $program_id);
		$this->db->update('_program', $data);
		
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}*/
	}

	function delete_program_by_admin($program_id){

		$this->db->where('program_id', $program_id);
		$this->db->delete('_program'); 
	}

	function chkupdate_program_ballnaja($match_id, $data, $showdebug = 0){

		// $this->db->select('ballnaja_id');
    	// $this->db->from($this->prefix.'_program_ballnaja');
    	// $this->db->where('ballnaja_id', $match_id);
    	// $query = $this->db->get();

		// $res = $query->result_array();
    	// if(!$res){

			$insert = $this->db->insert($this->prefix.'_program_ballnaja', $data);
			@Debug('Insert match '.$match_id.' '.$data['match_name'].' '.$data['datetime']);
		// }else{

	
		// 	$this->db->where('ballnaja_id', $match_id);
		// 	$this->db->update($this->prefix.'_program_ballnaja', $data);
		// 	@Debug('Update match '.$match_id.' '.$data['match_name'].' '.$data['datetime']);
		// }
		Debug($this->db->last_query());
		// return true;
	}
 
}
?>	
