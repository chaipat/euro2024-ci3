<?php
class Fixtures_model extends CI_Model {
	protected $prefix;
    public function __construct(){
		parent::__construct();

		$this->load->database();

		// $this->prefix = $this->db->dbprefix;
		$this->prefix = 'ba';
    }

    public function get_max_id(){
		
		$this->db->select('max(program_id) as max_id');
		$this->db->from($this->prefix.'_program');
		$this->db->where('program_id <', 1000000);
		$query = $this->db->get();
		$get_max_id =  $query->result_object(); 
		return $get_max_id[0]->max_id;
    }

    function get_table($tablename, $keyw = array()){

    	if($tablename == '_xml_bet_handicap'){
    		$this->db->select('*, ABS(point) as point_group');
    	}else{
    		$this->db->select('*');
    	}    	
    	$this->db->from($tablename);
    	
    	if($keyw){
    		foreach($keyw as $k => $v){
    			$this->db->where($k, $v);
    		}    		
    	}
    	//$this->db->limit($listpage, $limit_start);
    	$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_object();
    }

    function get_count($league_id = 0){

		//$this->db->count_all('_program');

    	$this->db->select('*');
    	$this->db->from($this->prefix.'_program');
    	$this->db->where('league_id', $league_id);
    	//$this->db->limit($listpage, $limit_start);

    	return $this->db->count_all_results();
    }

    function get_use_bet($fix_id = 0, $betcompany_id = 14, $oddtype_id = 4){

    	/*SELECT * FROM sp_xml_bet_handicap 
		WHERE oddtype_id = 4 AND betcompany_id = 14 AND fix_id = 1906179 
		ORDER BY IF( ABS(localteam-2) < ABS(visitorteam-2), ABS(localteam-2), ABS(visitorteam-2)) ASC*/

		$this->db->select('*, ABS(point) as point_group');
    	$this->db->from('_xml_bet_handicap');
    	$this->db->where('oddtype_id', $oddtype_id);
    	$this->db->where('betcompany_id', $betcompany_id);
    	$this->db->where('fix_id', $fix_id);
    	$this->db->order_by('IF( ABS(localteam-2) < ABS(visitorteam-2), ABS(localteam-2), ABS(visitorteam-2))', 'Asc');
    }

    function get_program_rate($program_id = 0, $showdebug = 0){
    	
    	$this->db->select('*');
    	$this->db->from($this->prefix.'_program_rate');
    	if($program_id != null && $program_id > 0){
			$this->db->where('program_id', $program_id);
		}
		$query = $this->db->get();
		//if($showdebug == 1) Debug($this->db->last_query());
		$res = $query->result_object();
		return $res;
    }

	function sel_date($stage_id){

		$this->db->select('DISTINCT (SELECT CAST(`kickoff` AS DATE)) as `sel_date`');
		$this->db->from('_program');
		$this->db->where('stage_id', $stage_id);
		$this->db->order_by('kickoff', 'Asc');

		$query = $this->db->get();
		// Debug($this->db->last_query());
		$res = $query->result_object();		
		return $res;
	}

    function get_data($program_id = 0, $team_id = 0, $league_id = 0, $datebetween = null, $stage_id = 0, $week = 0, $limit_start = 0, $listpage = 0, $order_field = 'number', $order_type='asc', $active = 1, $showdebug = 0){
		
		// $language = $this->lang->language['lang'];
		$prefix = $this->prefix;

		$this->db->select($prefix.'_program.*, CONVERT_TZ(`kickoff`, \'+00:00\', \'+07:00\') as `kickoff_th`, `'.$prefix.'_tournament`.`tournament_name`, `'.$prefix.'_tournament`.`tournament_name_en`, file_group, iscup');
		$this->db->select('(SELECT `'.$prefix.'_team`.`team_name` FROM `'.$prefix.'_team` WHERE `'.$prefix.'_team`.`team_id`=`'.$prefix.'_program`.`hometeam_id`) AS hometeam_title_th');
		$this->db->select('(SELECT `'.$prefix.'_team`.`logo` FROM `'.$prefix.'_team` WHERE `'.$prefix.'_team`.`team_id`=`'.$prefix.'_program`.`hometeam_id`) AS logo_hometeam');
		$this->db->select('(SELECT `'.$prefix.'_team_formation`.`formation` FROM `'.$prefix.'_team_formation` WHERE `'.$prefix.'_team_formation`.`tfid`=`'.$prefix.'_program`.`hometeam_formation`) AS home_formation');

		$this->db->select('(SELECT `'.$prefix.'_team`.`team_name` FROM `'.$prefix.'_team` WHERE `'.$prefix.'_team`.`team_id`=`'.$prefix.'_program`.`awayteam_id`) AS awayteam_title_th');
		$this->db->select('(SELECT `'.$prefix.'_team`.`logo` FROM `'.$prefix.'_team` WHERE `'.$prefix.'_team`.`team_id`=`'.$prefix.'_program`.`awayteam_id`) AS logo_awayteam');
		$this->db->select('(SELECT `'.$prefix.'_team_formation`.`formation` FROM `'.$prefix.'_team_formation` WHERE `'.$prefix.'_team_formation`.`tfid`=`'.$prefix.'_program`.`awayteam_formation`) AS away_formation');
		$this->db->select('(SELECT CAST(`kickoff` AS DATE)) as `sel_date`');

		$this->db->select($prefix.'_season.season_name, '.$prefix.'_season.season_name2');
		//$this->db->select('_program_rate.rate_home, _program_rate.rate_draw, _program_rate.rate_away');
		$this->db->select($prefix.'_stadium.stadium_name, '.$prefix.'_stadium.stadium_name_th');
		$this->db->select($prefix.'_channel.channel_name');
		$this->db->select($prefix.'_xml_standing.group_name, '.$prefix.'_xml_standing.group_id');
		$this->db->select($prefix.'_h2h.json');
		// $this->db->select($prefix.'_program_ballnaja.code, '.$prefix.'_program_ballnaja.logo as channel_logo, '.$prefix.'_program_ballnaja.name as channel_name, '.$prefix.'_program_ballnaja.link as channel_link');

		$this->db->from($prefix.'_program');
		$this->db->join($prefix.'_tournament', $prefix.'_program.league_id = '.$prefix.'_tournament.tournament_id', 'left');

		$this->db->join($prefix.'_season', $prefix.'_program.season = '.$prefix.'_season.season_id', 'left');
		//$this->db->join('_program_rate', '_program.program_id = _program_rate.program_id', 'left');
		$this->db->join($prefix.'_h2h', $prefix.'_program.hometeam_id = '.$prefix.'_h2h.home_id and '.$prefix.'_program.awayteam_id = '.$prefix.'_h2h.away_id and '.$prefix.'_program.league_id = '.$prefix.'_h2h.league_id', 'left');
		//$this->db->join('_team_formation', '_team_formation.tfid = _program.hometeam_formation', 'left');
		$this->db->join($prefix.'_stadium', $prefix.'_program.stadium_id = '.$prefix.'_stadium.stadium_id', 'left');
		$this->db->join($prefix.'_channel', $prefix.'_program.channel1 = '.$prefix.'_channel.channel_id', 'left');
		$this->db->join($prefix.'_xml_standing', $prefix.'_program.hometeam_id = '.$prefix.'_xml_standing.team_id AND '.$prefix.'_xml_standing.tournament_id = '.intval($league_id), 'left');
		// $this->db->join($prefix.'_program_ballnaja', $prefix.'_program.program_id = '.$prefix.'_program_ballnaja.program_id', 'left');

		if($program_id > 0){
			$this->db->where($prefix.'_program.program_id', $program_id);
		}

		if($league_id > 0){
			$this->db->where($prefix.'_program.league_id', $league_id);
		}

		if($stage_id > 0){
			$this->db->where($prefix.'_program.stage_id', $stage_id);
		}

		if($week > 0){
			$this->db->where($prefix.'_program.week', $week);
		}

		if($team_id > 0){
			$this->db->group_start();
				$this->db->where($prefix.'_program.hometeam_id', $team_id);
				$this->db->or_where($prefix.'_program.awayteam_id', $team_id);
			$this->db->group_end();
		}

		// $this->db->where($prefix.'_program.status', 1);
		// if($active == 1){

		// 	$this->db->where($prefix.'_program.status', 1);
		// }else{
			//$this->db->where('lang', $language);
			// $this->db->where($prefix.'_program.status !=', 9);
		// }

		if($datebetween){
			$this->db->where($prefix.'_program.kickoff BETWEEN \''.$datebetween[0].'\' AND \''.$datebetween[1].'\'');
		}

		$this->db->order_by($prefix.'_program.kickoff', 'Asc');
		$this->db->order_by($prefix.'_program.program_id', 'Asc');
		//$this->db->limit($listpage, $limit_start);

		$query = $this->db->get();
		if($showdebug == 1) Debug($this->db->last_query());
		$data1 = $query->result_object();		
		return $data1;
	}

	function get_databallnaja($program_id = 0, $showdebug = 0){
		
		// $language = $this->lang->language['lang'];
		$prefix = $this->prefix;

		$this->db->select($prefix.'_program_ballnaja.code, '.$prefix.'_program_ballnaja.logo as channel_logo, '.$prefix.'_program_ballnaja.name as channel_name, '.$prefix.'_program_ballnaja.link as channel_link');

		$this->db->from($prefix.'_program');
	
		$this->db->join($prefix.'_program_ballnaja', $prefix.'_program.program_id = '.$prefix.'_program_ballnaja.program_id');

		if($program_id > 0){
			$this->db->where($prefix.'_program.program_id', $program_id);
		}
		//$this->db->limit($listpage, $limit_start);

		$query = $this->db->get();
		if($showdebug == 1) Debug($this->db->last_query());
		$data1 = $query->result_object();		
		return $data1;
	}

	function get_match($date = '', $tournament_id = 0){

		$this->db->select('_program.*');
		$this->db->from('_program');

		if($tournament_id > 0) $this->db->where('_program.tournament_id', $tournament_id);
		if($date != '') $this->db->like('_program.kickoff', $date);

		$this->db->order_by('kickoff', 'asc');

		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_object();
	}
	
	function get_xml($tournament_id = 0, $sel_date = ''){
		
		$this->db->select('_xml_match.*');
		$this->db->select('_tournament.tournament_name, _tournament.tournament_name_en');
		$this->db->select('(SELECT `'.$this->prefix.'_team`.`team_name` FROM `'.$this->prefix.'_team` WHERE `'.$this->prefix.'_team`.`team_id`=`'.$this->prefix.'_xml_match`.`hteam_id`) AS home_team');
		$this->db->select('(SELECT `'.$this->prefix.'_team`.`team_name` FROM `'.$this->prefix.'_team` WHERE `'.$this->prefix.'_team`.`team_id`=`'.$this->prefix.'_xml_match`.`ateam_id`) AS away_team');
		$this->db->select('(SELECT CAST(`match_datetime` AS DATE)) as `sel_date`');

		$this->db->from($this->prefix.'_xml_match');
		$this->db->join($this->prefix.'_tournament', $this->prefix.'_xml_match.tournament_id = '.$this->prefix.'_tournament.tournament_id');
		// $this->db->join($this->prefix.'_team', $this->prefix.'_xml_match.tournament_id = '.$this->prefix.'_team.tournament_id');
		

		if($tournament_id > 0) 
			$this->db->where('_xml_match.tournament_id', $tournament_id);
		
		if($sel_date != '') 
			$this->db->like('match_datetime', $sel_date);

		$this->db->order_by('match_datetime', 'asc');

		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_object();
	}
    
    function store($id, $data){

		if($id > 0){

			$this->db->where('program_id', $id);
			$this->db->update($this->prefix.'_program', $data);
			
			return true;

		}else{
			$insert = $this->db->insert('_program', $data);
			//Debug($this->db->last_query());
			return $insert;
		}
	}

	//***********Program Ananly
	function get_program_analy($program_id){

		$this->db->select('*');
		$this->db->from($this->prefix.'_program_analy');

		if($program_id > 0) $this->db->where('program_id', $program_id);

		$this->db->order_by('order_number', 'asc');

		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_object();
	}

    function analy_store($id, $data){
		if($id > 0){
			$this->db->where('program_analy_id', $id);
			$this->db->update($this->prefix.'_program_analy', $data);

			return true;
				
		}else{
			$insert = $this->db->insert('_program_analy', $data);
			//Debug($this->db->last_query());
			return $insert;
		}
	}

	public function delete_analy($id){
		$this->db->where('program_analy_id', $id);
		$this->db->delete($this->prefix.'_program_analy');
		return true;
	}

	public function import(&$data = array()){
		$insert = $this->db->insert_batch($this->prefix.'_program', $data);
		return $insert;
	}

    function delete_team($id){

		$data = array(
			'status' => 9
		);
		$this->db->where('program_id', $id);
		$this->db->update($this->prefix.'_program', $data);
		
		return true;
	}

	function delete_admin($id){
		
		$this->db->where('program_id', $id);
		$this->db->delete($this->prefix/'_program');
	}

}
?>	
