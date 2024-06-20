<?php
class Team_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();

		$this->load->database();

		$this->prefix = $this->db->dbprefix;
    }

    public function getSelect($default = 0, $name = "team_id", $id = "team_id", $class = "chosen-select"){//Dropdown List
    		
    		$language = $this->lang->language;
			$first = "--- ".$language['please_select']." ---";
	    	$rows = $this->get_data();
			//Debug($rows);
	    	$opt = array();
	    	$opt[]	= makeOption(0, $first);

	    	for($i=0;$i<count($rows);$i++){
	    		$row = @$rows[$i];
	    		$opt[]	= makeOption($row->team_id, $row->team_name);
	    	}
	    	return selectList( $opt, $name, 'class="'.$class.'"', 'value', 'text',$default);
	    	//return MultiSelectList( $opt, $name, 'class="chosen-select"', 'value', 'text', $default);
    }

    public function getSel($default = 0, $league_id = 1415, $name = 'team_id', $class = "chosen-select"){//Dropdown List
    		
    		$language = $this->lang->language;
			$first = "--- ".$language['please_select']." ---";
	    	$rows = $this->get_data($league_id);
			//Debug($rows);
	    	$opt = array();
	    	$opt[]	= makeOption(0, $first);

	    	for($i=0;$i<count($rows);$i++){
	    		$row = @$rows[$i];
	    		$opt[]	= makeOption($row->team_id, $row->team_name);
	    	}
	    	return selectList( $opt, $name, 'class="'.$class.'"', 'value', 'text', $default);
	    	//return MultiSelectList( $opt, $name, 'class="chosen-select"', 'value', 'text', $default);
    }

    function get_data($league_id = 0, $team_id = 0, $limit_start = 0, $listpage = 0, $order_field = 'team_name', $order_type='asc'){
		
		$prefix = $this->prefix;

		// echo "($league_id, $team_id)";
		// die();

		//if($data == 'json') $this->db->select('team_id as DT_RowId');
		// $this->db->select($prefix.'_team.team_id', $prefix.'_team.team_name', $prefix.'_team.team_name_en');
		// $this->db->select($prefix.'_team.manager_id', $prefix.'_coach.name as manager_name');
		$this->db->select($prefix.'_team.*');
		$this->db->select($prefix.'_stadium.*');
		$this->db->select($prefix.'_coach.name as manager_name, '.$prefix.'_coach.name_th as manager_name_th');
		$this->db->select($prefix.'_tournament.tournament_name, '.$prefix.'_tournament.tournament_name_en, '.$prefix.'_tournament.short_name, '.$prefix.'_tournament.color, '.$prefix.'_tournament.season, '.$prefix.'_tournament.logo as tournament_logo, '.$prefix.'_stadium.stadium_name, '.$prefix.'_stadium.stadium_name_th, '.$prefix.'_stadium.location, '.$prefix.'_stadium.capacity');
	
		// $this->db->select('(SELECT `'.$prefix.'_admin`.`admin_username` FROM `'.$prefix.'_admin` WHERE `'.$prefix.'_admin`.`admin_id`=`'.$prefix.'_team`.`create_by`) AS create_by_name');
		// $this->db->select('(SELECT `'.$prefix.'_admin`.`admin_username` FROM `'.$prefix.'_admin` WHERE `'.$prefix.'_admin`.`admin_id`=`'.$prefix.'_team`.`lastupdate_by`) AS lastupdate_by_name');		
		
		$this->db->from($prefix.'_team');
		//$this->db->join('_league', '_league.league_id = _team.league_id', 'left');
		$this->db->join($prefix.'_tournament', $prefix.'_tournament.tournament_id = '.$prefix.'_team.league_id', 'left');
		$this->db->join($prefix.'_stadium', $prefix.'_stadium.stadium_id = '.$prefix.'_team.stadium_id', 'left');
		$this->db->join($prefix.'_coach', $prefix.'_coach.id = '.$prefix.'_team.manager_id', 'left');

		$this->db->where($prefix.'_team.status', 1);

		if($league_id > 0) $this->db->where($prefix.'_team.league_id', $league_id);
		if($team_id > 0) $this->db->where($prefix.'_team.team_id', $team_id);

		if($team_id == 0) $this->db->order_by($order_field, $order_type);

		if($listpage != 0) $this->db->limit($listpage, $limit_start);

		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_object();
	}

	function get_leagues($league_id = 0, $team_id = 0, $limit_start = 0, $listpage = 0, $order_field = 'team_name', $order_type='asc'){
		
		$prefix = $this->prefix;

		//if($data == 'json') $this->db->select('team_id as DT_RowId');
		$this->db->select('_team.*, '.$prefix.'_stadium.*');
		$this->db->select('_tournament.tournament_name, '.$prefix.'_tournament.tournament_name_en, '.$prefix.'_tournament.short_name, '.$prefix.'_tournament.color, '.$prefix.'_tournament.season, '.$prefix.'_tournament.logo as tournament_logo, '.$prefix.'_stadium.stadium_name, '.$prefix.'_stadium.stadium_name_th, '.$prefix.'_stadium.location, '.$prefix.'_stadium.capacity');
		// $this->db->select('sp_countries.image_path');
		$this->db->select('(SELECT `'.$prefix.'_admin`.`admin_username` FROM `'.$prefix.'_admin` WHERE `'.$prefix.'_admin`.`admin_id`=`'.$prefix.'_team`.`create_by`) AS create_by_name');
		$this->db->select('(SELECT `'.$prefix.'_admin`.`admin_username` FROM `'.$prefix.'_admin` WHERE `'.$prefix.'_admin`.`admin_id`=`'.$prefix.'_team`.`lastupdate_by`) AS lastupdate_by_name');		
		
		$this->db->from('_team');
		//$this->db->join('_league', '_league.league_id = _team.league_id', 'left');
		$this->db->join('_tournament', $prefix.'_tournament.tournament_id = '.$prefix.'_team.league_id', 'left');
		$this->db->join('_stadium', $prefix.'_stadium.stadium_id = '.$prefix.'_team.stadium_id', 'left');
		// $this->db->join('sp_countries', 'sp_countries.id = '.$prefix.'_team.team_id', 'left');

		$this->db->where($prefix.'_team.status', 1);

		if($league_id > 0) 
			$this->db->where($prefix.'_team.league_id', $league_id);

		if($team_id > 0) 
			$this->db->where($prefix.'_team.team_id', $team_id);

		if($team_id == 0) 
			$this->db->order_by($order_field, $order_type);

		if($listpage != 0) 
			$this->db->limit($listpage, $limit_start);

		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_object();
	}

	public function merge_data(){

		$arr[] = '1056';

		$sql = 'SELECT `ba_team`.*, `ba_stadium`.*, `ba_tournament`.`tournament_name`, `ba_tournament`.`tournament_name_en`, `ba_tournament`.`short_name`, `ba_tournament`.`color`, `ba_tournament`.`season`, `ba_tournament`.`logo` as `tournament_logo`, `ba_stadium`.`stadium_name`, `ba_stadium`.`stadium_name_th`, `ba_stadium`.`location`, `ba_stadium`.`capacity`, (SELECT `ba_admin`.`admin_username` FROM `ba_admin` WHERE `ba_admin`.`admin_id`=`ba_team`.`create_by`) AS create_by_name, (SELECT `ba_admin`.`admin_username` FROM `ba_admin` WHERE `ba_admin`.`admin_id`=`ba_team`.`lastupdate_by`) AS lastupdate_by_name,
		`sp_countries`.image_path
		FROM `ba_team`
		LEFT JOIN `ba_tournament` ON `ba_tournament`.`tournament_id` = `ba_team`.`league_id`
		LEFT JOIN `ba_stadium` ON `ba_stadium`.`stadium_id` = `ba_team`.`stadium_id`
		LEFT JOIN `sp_countries` ON `sp_countries`.`name` = `ba_team`.`team_name_en`
		WHERE `ba_team`.`status` = 1
		AND `ba_team`.`league_id` = ?
		ORDER BY `team_name` ASC';

		$query = $this->db->query($sql, $arr);

		$result = $query->result_object();		
		return $result;

	}

    public function get_max_id(){
		$this->db->select('max(team_id) as max_id');
		$this->db->from('_team');
		$query = $this->db->get();
		return $query->result_object(); 
    }

    public function get_count($league_id = 0){
		$this->db->select('count(team_id) as number_team');
		$this->db->from('_team');
		if($league_id > 0) $this->db->where('league_id', $league_id);
		$query = $this->db->get();
		return $query->result_object(); 
    }

    /*****************************/
    public function team_player($team_id = 0, $profile_id = 0){

		$this->db->select('_team_player.*');
		$this->db->select('_player_profile.position as player_position, _player_profile.name as player_name, _player_profile.name_th as player_name_th');
		$this->db->select('_player_profile.birthdate, _player_profile.birthcountry, _player_profile.birthplace');
		$this->db->select('_player_profile.age, _player_profile.height, _player_profile.weight, _player_profile.image');
		$this->db->select('_player_profile.team as current_team');
		$this->db->select('_team.team_name as team_name, _team.team_name_en as team_name_en');
		$this->db->from('_team_player');
		$this->db->join('_player_profile', $this->prefix.'_player_profile.profile_id = '.$this->prefix.'_team_player.profile_id', 'left');
		$this->db->join('_team', $this->prefix.'_team.team_id = '.$this->prefix.'_team_player.team_id', 'left');
		
		if($team_id > 0) $this->db->where('_team_player.team_id', $team_id);
		if($profile_id > 0) $this->db->where('_team_player.profile_id', $profile_id);

		$query = $this->db->get();
		return $query->result_object(); 
    }
	
    public function scoring_minutes($team_id = 0){
		$this->db->select('*');
		$this->db->from('_xml_scoring_minutes');
		if($team_id > 0) $this->db->where('team_id', $team_id);
		$query = $this->db->get();
		return $query->result_object(); 
    }

    public function statistics($team_id = 0){
		$this->db->select('*');
		$this->db->from('_xml_statistics');
		if($team_id > 0) $this->db->where('team_id', $team_id);
		$query = $this->db->get();
		return $query->result_object(); 
    }

    public function transfers($team_id = 0){
		$this->db->select('*');
		$this->db->from('_xml_transfers');
		if($team_id > 0) $this->db->where('team_id', $team_id);
		$query = $this->db->get();
		return $query->result_object(); 
    }

    public function sidelined($team_id = 0){
		$this->db->select('*');
		$this->db->from('_xml_sidelined');
		if($team_id > 0) $this->db->where('team_id', $team_id);
		$query = $this->db->get();
		return $query->result_object(); 
    }

    /*****************************/
    function store($id, $data){

		if($id > 0){
			$this->db->where('team_id', $id);
			$this->db->update('_team', $data);
			
			return true;
				
		}else{
			$insert = $this->db->insert('_team', $data);
			//Debug($this->db->last_query());
			return $insert;
		}
	}

	function store_stadium($id, $data){

		if($id > 0){
			$this->db->where('stadium_id', $id);
			$this->db->update('_stadium', $data);

			return true;				
		}else{
			$insert = $this->db->insert('_team', $data);
			//Debug($this->db->last_query());
			return $insert;
		}
	}

	function add_stat_player($profile_id, $field){

		if($profile_id > 0){

			$this->db->set($field, $field.' + 1', FALSE);
			$this->db->where('profile_id', $profile_id);
			// $this->db->where('team_id', $team_id);
			$this->db->update('_team_player');
			Debug($this->db->last_query());
			return true;
		}else
			return false;
	}

	function add_minute($profile_id, $add_minute){

		if($profile_id > 0){

			$this->db->set('minutes', 'minutes + '.$add_minute, FALSE);
			$this->db->where('profile_id', $profile_id);
			// $this->db->where('team_id', $team_id);
			$this->db->update('_team_player');
			Debug($this->db->last_query());
			return true;
		}else
			return false;
	}

	function update_team_player($id, $data){

		if($id > 0){

			$this->db->where('profile_id', $id);
			$this->db->update('_team_player', $data);

			return true;
		}else
			return false;
	}

	function update_profile($id, $data){

		if($id > 0){

			$this->db->where('profile_id', $id);
			$this->db->update('_player_profile', $data);

			return true;
		}else
			return false;
	}

	function update_manager($id, $data){

		if($id > 0){

			$this->db->where('id', $id);
			$this->db->update('_coach', $data);

			return true;
		}else
			return false;
	}

	public function import(&$data = array()){
		$insert = $this->db->insert_batch('_team', $data);
		return $insert;
	}

	function reset_stat_player(){

		//UPDATE `ba_team_player` SET `injured`=0,`minutes`=0,`appearences`=0,`lineups`=0,`substitute_in`=0,`substitute_out`=0,`substitutes_on_bench`=0,`goals`=0,`assists`=0,`yellowcards`=0,`yellowred`=0,`redcards`=0;
		
		$this->db->set('injured', 0, FALSE);
		$this->db->set('minutes', 0, FALSE);
		$this->db->set('appearences', 0, FALSE);
		$this->db->set('lineups', 0, FALSE);
		$this->db->set('substitute_in', 0, FALSE);
		$this->db->set('substitute_out', 0, FALSE);
		$this->db->set('substitutes_on_bench', 0, FALSE);
		$this->db->set('goals', 0, FALSE);
		$this->db->set('assists', 0, FALSE);
		$this->db->set('yellowcards', 0, FALSE);
		$this->db->set('yellowred', 0, FALSE);
		$this->db->set('redcards', 0, FALSE);

		$this->db->update('_team_player');

		return true;

	}    
	
	function delete_team($id){

		$data = array(
			'status' => 9
		);
		$this->db->where('team_id', $id);
		$this->db->update('_team', $data);

		return true;
	}

	function delete_admin($id){
		$this->db->where('team_id', $id);
		$this->db->delete('_team');
	}

}
?>	
