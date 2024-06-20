<?php
class Rate_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();

		$this->load->database();

		$this->prefix = $this->db->dbprefix;
    }

    function get_data($program_id = 0, $status = ''){

		$this->db->select('*');
		$this->db->from($this->prefix.'_program_rate');
		if($status != '') $this->db->where('status', $status);
		if($program_id > 0) $this->db->where('program_id', $program_id);
		$this->db->order_by('program_id', 'Asc');
		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_object();
	}

    function store($program_id = 0, $data){

		if($program_id > 0){
			$this->db->where('program_id', $program_id);
			$this->db->update($this->prefix.'_program_rate', $data);
			
			return true;
		}else{
			$insert = $this->db->insert($this->prefix.'_program_rate', $data);
			//Debug($this->db->last_query());
			return $insert;
		}
	}

    function delete_program_rate($program_id){
    	$this->delete_by_admin($program_id);
	}

	function delete_by_admin($program_id){
		$this->db->where('program_id', $program_id);
		$this->db->delete($this->prefix.'_program_rate'); 
	}
}
?>	
