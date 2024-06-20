<?php
class Match_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
        
		$this->load->database();

		$this->prefix = $this->db->dbprefix;
    }

    public function get_status($id){
    	$this->db->select('section_name, order_by, status');
    	$this->db->from('_section');
    	$this->db->where('section_id', $id);
    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	return $query->result_array();
    }

    public function getSelect($default = 0, $name = "section_id", $site_id = 0){
    		
    	$language = $this->lang->language;
    	$first = "--- ".$language['please_select'].$language['category']." ---";
    		
		if($site_id == 0)
			$rows = $this->get_ss(null, 0, 1);
		else
			$rows = $this->get_ss(null, $site_id, 1);
		//Debug($rows);
	    $opt = array();
	    $opt[]	= makeOption(0,$first);

		$all = $obj_list->header->total_rows;

	    for($i=0;$i<$all;$i++){
	    	//$row = @$rows[$i];
			$data = $obj_list->body[$i]->season;
			$opt[]	= makeOption($data, $data);
	    }
	    return selectList($opt, $name, 'class="form-control"', 'value', 'text', $default);
    }

	public function geth2h($home_id, $away_id, $league_id = 0){
		$res = null;
		
		if($home_id > 0 && $away_id > 0){
			$this->db->select('*');
			$this->db->from('_h2h');

			$this->db->where('home_id', $home_id);
			$this->db->where('away_id', $away_id);

			if($league_id > 0)
				$this->db->where('league_id', $league_id);

			$query = $this->db->get();

			$res = $query->result_object();			
		}

		return $res;
    }

	public function getmatch_highlights($sel_date, $match_id = 0){
		$res = null;
		
		if($sel_date != '' || $match_id > 0){

			$this->db->select('*');
			$this->db->from('_highlights');

			if(is_array($sel_date)){
				$this->db->where('match_date BETWEEN \''.$sel_date[0].'\' AND \''.$sel_date[1].'\'');
			}else if($sel_date != '')
				$this->db->where('match_date', $sel_date);

			if($match_id > 0)
				$this->db->where('match_id', intval($match_id));

			$query = $this->db->get();

			$res = $query->result_object();			
		}

		return $res;
    }

	public function getmatch_event($program_id, $match_id = 0){
		$res = null;
		
		if($program_id > 0 || $match_id > 0){

			$this->db->select('_xml_match_event.*');
			$this->db->select('_player_profile.position as player_position, _player_profile.name as player_name, _player_profile.name_th as player_name_th');
			$this->db->select('assist.position as assist_position, assist.name as assist_name, assist.name_th as assist_name_th');
			$this->db->from('_xml_match_event');
			$this->db->join('_player_profile', '_player_profile.profile_id = '.$this->prefix.'_xml_match_event.playerid', 'left');
			$this->db->join('_player_profile assist', 'assist.profile_id = '.$this->prefix.'_xml_match_event.assistid', 'left');

			if($program_id > 0)
				$this->db->where('program_id', intval($program_id));

			if($match_id > 0)
				$this->db->where('match_id', intval($match_id));

			$query = $this->db->get();

			$res = $query->result_object();			
		}

		return $res;
    }

	public function getmatch_penalties($match_id){
		$res = null;
		
		if($match_id > 0){

			$this->db->select('_xml_match_penalties.*');
			$this->db->select('_player_profile.position as player_position, _player_profile.name as player_name, _player_profile.name_th as player_name_th');
			$this->db->from('_xml_match_penalties');
			$this->db->join('_player_profile', '_player_profile.profile_id = '.$this->prefix.'_xml_match_penalties.playerid', 'left');

			if($match_id > 0)
				$this->db->where('match_id', intval($match_id));

			$query = $this->db->get();

			$res = $query->result_object();			
		}

		return $res;
    }

	public function getmatch_lineup($match_id, $team_id = 0){
		$res = null;
		
		if($match_id > 0){

			$this->db->select('_xml_match_lineup.*');
			$this->db->select('_player_profile.position as player_position, _player_profile.name as player_name, _player_profile.name_th as player_name_th');
			$this->db->from('_xml_match_lineup');
			$this->db->join('_player_profile', '_player_profile.profile_id = '.$this->prefix.'_xml_match_lineup.player_id', 'left');

			if($match_id > 0)
				$this->db->where('match_id', intval($match_id));

			if($team_id > 0)
				$this->db->where('team_id', intval($team_id));

			$query = $this->db->get();

			$res = $query->result_object();			
		}

		return $res;
    }

	public function getmatch_substitutions($match_id){
		$res = null;
		
		if($match_id > 0){

			$this->db->select('_xml_match_substitutions.*');
			$this->db->select('_player_profile.position as player_position, _player_profile.name as player_name, _player_profile.name_th as player_name_th');

			$this->db->from('_xml_match_substitutions');
			$this->db->join('_player_profile', '_player_profile.profile_id = '.$this->prefix.'_xml_match_substitutions.on_id', 'left');

			if($match_id > 0)
				$this->db->where('match_id', intval($match_id));

			$query = $this->db->get();

			$res = $query->result_object();			
		}

		return $res;
    }

    public function getData($program_id = 0, $table = "_program"){
    		
    	$this->db->select('*');
    	$this->db->from($table);
    	$this->db->where('program_id', intval($program_id));
    	$query = $this->db->get();

		$res = $query->result_object();
		return $res;
    }

	public function save_import($ref_id =0,&$data = array()){
		//$this->db->delete('_match_temp', array('ref_id' => $ref_id));
		$insert = $this->db->insert_batch('_match_temp', $data);
		return $insert;
	}

    function store($ref_id = 0, $data, $showdebug = 0){

		if($ref_id == 0){

			$insert = $this->db->insert('_match_temp', $data);
		}else {
			$this->db->where('games_id', intval($ref_id));
			$this->db->update('_match_temp', $data);

			// if ($showdebug == 1) Debug($this->db->last_query());
			// $report = array();
			// $report['error'] = $this->db->_error_number();
			// $report['message'] = $this->db->_error_message();
			// if ($report !== 0) {
			// 	return true;
			// } else {
			// 	return false;
			// }
		}
	}
 
}
?>	
