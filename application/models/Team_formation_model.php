<?php
class Team_formation_model extends CI_Model {

	public function __construct(){
		parent::__construct();

		$this->load->database();

		$this->prefix = $this->db->dbprefix;
	}

	public function getSelect($default = 0, $name = "tfid", $class = "form-control chosen-select"){//Dropdown List

		$language = $this->lang->language;
		$first = "--- ".$language['please_select']." ---";
		$rows = $this->get_data();
		//Debug($rows);
		$opt = array();
		$opt[]	= makeOption(0, $first);

		for($i=0;$i<count($rows);$i++){
			$row = @$rows[$i];
			$opt[]	= makeOption($row->tfid, $row->formation);
		}

		// if($this->agent->is_mobile()){
		// 	$class = "form-control";
		// }else{
			//$class = "chosen-select";
		// }
		return selectList($opt, $name, 'class="'.$class.'"', 'value', 'text',$default);
		//return MultiSelectList( $opt, $name, 'class="chosen-select"', 'value', 'text', $default);
	}

	function get_data($league_id = 0, $limit_start = 0, $listpage = 0, $order_field = 'tfid', $order_type='asc'){

		//if($data == 'json') $this->db->select('tfid as DT_RowId');
		$this->db->select('*');
		$this->db->from($this->prefix.'_team_formation');
		$this->db->where('status', 1);

		$this->db->order_by($order_field, $order_type);

		if($listpage != 0) $this->db->limit($listpage, $limit_start);

		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_object();
	}

	public function get_max_id(){
		$this->db->select('max(tfid) as max_id');
		$this->db->from($this->prefix.'_team_formation');
		$query = $this->db->get();
		return $query->result_object();
	}

	public function get_count($league_id = 0){
		$this->db->select('count(tfid) as number_team_formation');
		$this->db->from($this->prefix.'_team_formation');
		if($league_id > 0) $this->db->where('league_id', $league_id);
		$query = $this->db->get();
		return $query->result_object();
	}

	function store($id = 0, $data){

		if($id > 0){
			$this->db->where('tfid', $id);
			$this->db->update($this->prefix.'_team_formation', $data);
	
			return true;

		}else{
			$insert = $this->db->insert($this->prefix.'_team_formation', $data);
			//Debug($this->db->last_query());
			return $insert;
		}
	}

	public function import(&$data = array()){
		$insert = $this->db->insert_batch($this->prefix.'_team_formation', $data);
		return $insert;
	}

	function delete_team_formation($id){

		$data = array(
			'status' => 9
		);
		$this->db->where('tfid', $id);
		$this->db->update($this->prefix.'_team_formation', $data);

		return true;
	}

	function delete_admin($id){
		$this->db->where('tfid', $id);
		$this->db->delete($this->prefix.'_team_formation');
	}

}
?>	
