<?php
class Program_player_model extends CI_Model {

	public function __construct(){
		parent::__construct();

		$this->prefix = $this->db->dbprefix;
	}

	public function getSelect($default = 0, $name = "p_player_id", $class = "chosen-select"){//Dropdown List

		$language = $this->lang->language;
		$first = "--- ".$language['please_select']." ---";
		$rows = $this->get_data();
		//Debug($rows);
		$opt = array();
		$opt[]	= makeOption(0, $first);

		for($i=0;$i<count($rows);$i++){
			$row = @$rows[$i];
			$opt[]	= makeOption($row->p_player_id, $row->player_name);
		}
		return selectList($opt, $name, 'class="'.$class.'"', 'value', 'text',$default);
		//return MultiSelectList( $opt, $name, 'class="chosen-select"', 'value', 'text', $default);
	}

	function get_data($program_id = 0, $team = 1, $order_field = 'p_player_id', $order_type='asc'){

		//if($data == 'json') $this->db->select('p_player_id as DT_RowId');
		$this->db->select('*');
		$this->db->from('_program_player');
		//$this->db->where('status', 1);
		$this->db->where('program_id', $program_id);
		$this->db->where('team', $team);

		$this->db->order_by($order_field, $order_type);

		//if($listpage != 0) $this->db->limit($listpage, $limit_start);

		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_object();
	}

	public function get_max_id(){
		$this->db->select('max(p_player_id) as max_id');
		$this->db->from('_program_player');
		$query = $this->db->get();
		return $query->result_object();
	}

	public function get_count($league_id = 0){
		$this->db->select('count(p_player_id) as number_program_player');
		$this->db->from('_program_player');
		if($league_id > 0) $this->db->where('league_id', $league_id);
		$query = $this->db->get();
		return $query->result_object();
	}

	function store($id = 0, $data){

		if($id > 0){
			$this->db->where('p_player_id', $id);
			$this->db->update('_program_player', $data);
			$report = array();
			$report['error'] = $this->db->_error_number();
			$report['message'] = $this->db->_error_message();
			if($report !== 0){
				//Debug($this->db->last_query());
				return true;
			}else{
				return false;
			}
		}else{
			$insert = $this->db->insert('_program_player', $data);
			//Debug($this->db->last_query());
			return $insert;
		}
	}

	public function import(&$data = array()){
		$insert = $this->db->insert_batch('_program_player', $data);
		return $insert;
	}

	function delete_player($program_id, $team){

		$this->delete_admin($program_id, $team);

		/*$data = array(
			'status' => 9
		);
		$this->db->where('p_player_id', $id);
		$this->db->update('_program_player', $data);

		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}*/
	}

	function delete_admin($program_id, $team){
		$this->db->where('program_id', $program_id);
		$this->db->where('team', $team);
		$this->db->delete('_program_player');
	}

}
?>	
