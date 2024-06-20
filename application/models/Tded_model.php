<?php
class Tded_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
		
        $this->load->database();
		$this->prefix = $this->db->dbprefix;
    }

    public function get_status($id, $showdebug = 0){
    	$this->db->select('status');
    	$this->db->from('_tded');
    	$this->db->where('tded_id', $id);
    	$query = $this->db->get();
    	if($showdebug == 1) Debug($this->db->last_query());
    	return $query->result_array();
    }

    public function get_max_id(){
		$this->db->select('max(tded_id) as max_id');
		$this->db->from('_tded');
		$query = $this->db->get();
		return $query->result_array(); 
    }

	public function GetResultSelect($default = 0, $name = "result"){

		$language = $this->lang->language;
		$first = "--- ".$language['please_select']." ---";

		$opt = array();
		$opt[]	= makeOption(0,$first);
		$opt[] = makeOption(1, 'ได้');
		$opt[] = makeOption(2, 'เสีย');
		$opt[] = makeOption(3, 'เจ๊า');
		return selectList($opt, $name, 'class="form-control selresult"', 'value', 'text', $default);
	}

	public function GetTrustSelect($default = 0, $name = "trust"){

		$language = $this->lang->language;
		$first = "--- ".$language['please_select'].' '.$language['trust']." ---";

		$opt = array();
		$opt[]	= makeOption(0,$first);
		for($i=0;$i<=5;$i++){
			$opt[] = makeOption($i, $i);
		}
		return selectList($opt, $name, 'class="form-control"', 'value', 'text', $default);
	}

    public function getSelect($default = 0, $name = "tded_id", $site_id = 0){
    		
    		$language = $this->lang->language;
    		$first = "--- ".$language['please_select'].' '.$language['tded']." ---";
    		
			$rows = $this->get_data();
			//Debug($rows);
	    	$opt = array();
	    	$opt[]	= makeOption(0,$first);
	    	for($i=0;$i<count($rows);$i++){
	    		$row = @$rows[$i];
	    		$opt[] = makeOption($row->tded_id, $row->section_name);
	    	}
	    	return selectList($opt, $name, 'class="form-control"', 'value', 'text', $default);
    }

    public function get_tded($tded_id=0, $date = null, $guru_id = 0, $program_id = 0, $league_id = 0, $active = '', $showdebug = 0){

		$language = $this->lang->language['lang'];
		$prefix = 'sp';

		$this->db->select('_tded.*, guru_name, league_name');
		$this->db->select('kickoff, hometeam_title, awayteam_title, hometeam_point, awayteam_point, favorite, price_point, num_name');
		//$this->db->select('(SELECT `'.$prefix.'_team_formation`.`formation` FROM `'.$prefix.'_team_formation` WHERE `'.$prefix.'_team_formation`.`tfid`=`'.$prefix.'_tded`.`hometeam_formation`) AS home_formation');
		//$this->db->select('(SELECT `'.$prefix.'_team_formation`.`formation` FROM `'.$prefix.'_team_formation` WHERE `'.$prefix.'_team_formation`.`tfid`=`'.$prefix.'_tded`.`awayteam_formation`) AS away_formation');
		//$this->db->select('_price.price_point, _price_num.num_name');
		$this->db->from('_tded');
		$this->db->join('_guru', '_tded.guru_id = _guru.guru_id', 'left');
		$this->db->join('_program', '_tded.program_id = _program.program_id', 'left');
		$this->db->join('_league', '_program.league_id = _league.league_id', 'left');		
		$this->db->join('_price', '_program.price_id = _price.price_id', 'left');
		$this->db->join('_price_num', '_program.num_id = _price_num.num_id', 'left');

		if($tded_id > 0){
			$this->db->where('_tded.tded_id', $tded_id);
		}

		if($guru_id > 0){
			$this->db->where('_tded.guru_id', $guru_id);
		}

		if($program_id > 0){
			$this->db->where('_tded.program_id', $program_id);
		}
		
		if($league_id > 0){
			$this->db->where('_tded.league_id', $league_id);
		}

		if($active != ''){
			$this->db->where('_tded.status', $active);
		}else{
			//$this->db->where('lang', $language);
			$this->db->where('_tded.status !=', 9);
		}

		if($date){
			$this->db->where('_tded.tded_date', $date);
			//$this->db->where($prefix.'_tded.tded_date', 'now()', false);
			//$this->db->where('_tded.tded_date BETWEEN "'.$datebetween[0].'" AND "'.$datebetween[1].'"');
		}

		$this->db->order_by('_tded.tded_date', 'Asc');
		$this->db->order_by('_tded.tded_id', 'Asc');
		//$this->db->limit($listpage, $limit_start);
		$query = $this->db->get();

		if($showdebug == 1) Debug($this->db->last_query());
		$data1 = $query->result_object();		
		return $data1;
    }

	public function add_batch(&$data, $table = '_tded', $debug = 0){
		if($debug == 1) Debug($data);
		$insert = $this->db->insert_batch($table, $data);
		if($debug == 1) Debug($this->db->last_query());
		return $insert;
	}

    function insert_tded($data, $showdebug = 0){
		$insert = $this->db->insert('_tded', $data);
		if($showdebug == 1) Debug($this->db->last_query());
	    return $insert;
	}

    function update_tded($tded_id, $data, $showdebug = 0){
		$this->db->where('tded_id', $tded_id);
		$this->db->update('_tded', $data);
		if($showdebug == 1) Debug($this->db->last_query());
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function status_tded($tded_id, $enable = 1){
		$data['status'] = $enable;
		$this->db->where('tded_id', $tded_id);
		$this->db->update('_tded', $data);
		
		//echo $this->db->last_query()
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}	

    function delete_tded($tded_id){

		$this->delete_tded_by_admin($tded_id);

		/*$data = array(
			'status' => 9
		);
		$this->db->where('tded_id', $tded_id);
		$this->db->update('_tded', $data);
		
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}*/
	}

	function delete_tded_by_admin($tded_id){
		$this->db->where('tded_id', $tded_id);
		$this->db->delete('_tded'); 
	}
 
}
?>	
