<?php
class Stadium_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
        
		$this->load->database();

		$this->prefix = $this->db->dbprefix;
    }

    public function get_name($id){

    	$this->db->select('stadium_id, stadium_name, stadium_name_th');
    	$this->db->from($this->prefix.'_stadium');
    	$this->db->where('stadium_id', $id);
    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	return $query->result_object();
    }

 	public function get_data($stadium_id = ''){

		$this->db->select('*');
    	$this->db->from($this->prefix.'_stadium');

    	if($stadium_id != '') $this->db->where('stadium_id', $stadium_id);
    	// $this->db->where('status', 1);

    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	return $query->result_object();
	}

	function store($id = 0, $data){

        if ($id > 0) {

            $this->db->where('stadium_id', $id);
            $this->db->update('_stadium', $data);
			//Debug($this->db->last_query());
			return true;

        } else {

            $insert = $this->db->insert('_stadium', $data);
            //Debug($this->db->last_query());
            return $insert;
        }
    }
}
?>	
