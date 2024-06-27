<?php
class Program_analy_model extends CI_Model {

	protected $prefix;
	public function __construct(){
		parent::__construct();

		$this->load->database();

		$this->prefix = $this->db->dbprefix;
	}

	function get_data($program_id = 0, $order_field = 'date', $order_type='desc',$listpage = 0){

		//if($data == 'json') $this->db->select('stat_id as DT_RowId');

		if($program_id != 0){
			$this->db->select('*');
			$this->db->from($this->prefix.'_program_analy');
			//$this->db->where('status', 1);
			$this->db->where('program_id', $program_id);

			// $this->db->order_by($order_field, $order_type);

			if($listpage != 0) $this->db->limit($listpage, 0);

			$query = $this->db->get();
			//Debug($this->db->last_query());
			return $query->result_object();
		}else
			return false;

	}

	function get_table($table = '_program_analy', $program_id = 0, $order_field = 'date', $order_type='desc', $listpage = 5){

		//if($data == 'json') $this->db->select('stat_id as DT_RowId');
		$this->db->select('*');
		$this->db->from($this->prefix.$table);

		if($program_id > 0){
			$this->db->where('program_id', $program_id);
		}

		//if($away_id > 0) $this->db->where('away_id', $away_id);
		// $this->db->order_by($order_field, $order_type);

		if($listpage != 0) $this->db->limit($listpage, 0);

		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_object();
	}

	function store($id, $data){

		if($id > 0){
			$this->db->where('program_id', $id);
			$this->db->update($this->prefix.'_program_analy', $data);
			
			return true;

		}else{
			$insert = $this->db->insert($this->prefix.'_program_analy', $data);
			//Debug($this->db->last_query());
			return $insert;
		}
	}

	function delete($program_id){

		$this->delete_admin($program_id);
	}

	function delete_admin($program_id){

		$this->db->where('program_id', $program_id);
		$this->db->delete($this->prefix.'_program_analy');
	}

}
?>	
