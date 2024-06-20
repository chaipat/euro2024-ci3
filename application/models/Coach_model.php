<?php
class Coach_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();

		$this->load->database();

		$this->prefix = $this->db->dbprefix;
	}

    public function get_data($coach_id = 0, $table = '_xml_coach'){

		$this->db->select('*');
		$this->db->from($table);

		if($coach_id != null && $coach_id != 0){
			$this->db->where('id', $coach_id);
		}

		// $this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result_object(); 
	}

	function store($coach_id = 0, $data = null, $table = '_xml_coach'){

		if($coach_id > 0){

			$this->db->where('id', $coach_id);
			$this->db->update($table, $data);

			// if($showdebug == 1) echo $this->db->last_query();
			return true;
						
		}else{

			$insert = $this->db->insert($table, $data);
			// if($showdebug == 1) echo $this->db->last_query();
			return $insert;
		}
	}
	
}