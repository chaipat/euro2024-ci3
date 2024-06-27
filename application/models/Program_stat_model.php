<?php
class Program_stat_model extends CI_Model {

	protected $prefix;
	public function __construct(){
		parent::__construct();

		$this->load->database();

		$this->prefix = $this->db->dbprefix;
	}

	function get_data($program_id = 0, $home_id = 0, $order_field = 'date', $order_type='desc',$listpage = 5){

		//if($data == 'json') $this->db->select('stat_id as DT_RowId');
		$this->db->select('*');
		$this->db->from($this->prefix.'_program_stat');
		//$this->db->where('status', 1);
		$this->db->where('program_id', $program_id);

		if($home_id > 0){
			$this->db->where('home_id', $home_id);
			$this->db->or_where('away_id', $home_id);

		}
		//if($away_id > 0) $this->db->where('away_id', $away_id);

		$this->db->order_by($order_field, $order_type);

		if($listpage != 0) $this->db->limit($listpage, 0);

		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_object();
	}

	function get_table($table, $program_id = 0, $team_id = 0, $order_field = 'date', $order_type='desc', $listpage = 5){

		//if($data == 'json') $this->db->select('stat_id as DT_RowId');
		$this->db->select('*');
		$this->db->from($this->prefix.$table);

		if($program_id > 0){
			$this->db->where('program_id', $program_id);
		}

		if($team_id > 0){
			$this->db->where('home_id', $team_id);
			$this->db->or_where('away_id', $team_id);

		}
		//if($away_id > 0) $this->db->where('away_id', $away_id);

		$this->db->order_by($order_field, $order_type);

		if($listpage != 0) $this->db->limit($listpage, 0);

		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_object();
	}

	function get_h2h($hometeam_id, $awayteam_id, $league_id){

		$this->db->select('json as data');
		$this->db->from($this->prefix.'_h2h');
		
		$this->db->where('home_id', $hometeam_id);
		$this->db->where('away_id', $awayteam_id);
		$this->db->where('league_id', $league_id);
		//$this->db->order_by($order_field, $order_type);
		//if($listpage != 0) $this->db->limit($listpage, $limit_start);

		$query = $this->db->get();
		$res = $query->result_object();

		if(isset($res[0]->data)) {
			return $res[0]->data;
		}else{
			return null;
		}
	}

	function clear_h2h($hometeam_id, $awayteam_id, $league_id){		
		$this->db->where('home_id', intval($hometeam_id));
		$this->db->where('away_id', intval($awayteam_id));
		$this->db->where('league_id', intval($league_id));
		$this->db->delete($this->prefix.'_h2h');
	}

	function clear_program_stat($program_id, $hometeam_id, $awayteam_id){
		$this->db->where('program_id', intval($program_id));
		$this->db->where('home_id', intval($hometeam_id));
		$this->db->where('away_id', intval($awayteam_id));
		$this->db->delete($this->prefix.'_program_stat');
	}

	function clear_program_stat_home($hometeam_id){
		$this->db->where('home_id', intval($hometeam_id));
		$this->db->delete($this->prefix.'_program_stat');
	}

	function clear_program_stat_away($awayteam_id){
		$this->db->where('away_id', intval($awayteam_id));
		$this->db->delete($this->prefix.'_program_stat');
	}

	public function get_max_id(){
		$this->db->select('max(stat_id) as max_id');
		$this->db->from($this->prefix.'_program_stat');
		$query = $this->db->get();
		return $query->result_object();
	}

	function store($id, $data){

		if($id > 0){
			$this->db->where('stat_id', $id);
			$this->db->update($this->prefix.'_program_stat', $data);
			
			return true;

		}else{
			$insert = $this->db->insert($this->prefix.'_program_stat', $data);
			//Debug($this->db->last_query());
			return $insert;
		}
	}

	public function import(&$data = array(), $table = '_program_stat'){
		$insert = $this->db->insert_batch($this->prefix.$table, $data);
		return $insert;
	}

	function delete_stat_id($stat_id){
		$this->db->where('stat_id', $stat_id);
		$this->db->delete($this->prefix.'_program_stat');
	}

	function delete($program_id, $team = 0){

		$this->delete_admin($program_id, $team = 0);

		/*$data = array(
			'status' => 9
		);
		$this->db->where('stat_id', $id);
		$this->db->update('_program_stat', $data);

		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}*/
	}

	function delete_admin($program_id, $team = 0){
		$this->db->where('program_id', $program_id);
		if($team > 0) $this->db->where('team', $team);
		$this->db->delete($this->prefix.'_program_stat');
	}

}
?>	
