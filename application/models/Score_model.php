<?php
class Score_model extends CI_Model {
 
	protected $prefix;
    public function __construct(){
		parent::__construct();

		$this->load->database();

		$this->prefix = $this->db->dbprefix;
    }

	public function SelScore($default = '', $name = "score_id", $id = "score_id", $class = "form-control"){//Dropdown List
		$language = $this->lang->language;
		//$first = "--- ".$language['please_select']." ---";
		$opt = array();
		//$opt[]	= makeOption('', $first);
		for($i=0;$i<20;$i++){
			$row = @$rows[$i];
			$opt[]	= makeOption($i, $i);
		}
		return selectList( $opt, $name, 'class="'.$class.'"', 'value', 'text',$default);
	}

    /*public function getSelect($default = 0, $name = "price_id", $id = "price_id", $class = "form-control"){//Dropdown List
    	//chosen-select
    	$language = $this->lang->language;
		$first = "--- ".$language['please_select']." ---";
	    $rows = $this->get_data();
		//Debug($rows);
	    $opt = array();
	    $opt[]	= makeOption('', $first);

	    for($i=0;$i<count($rows);$i++){
	    	$row = @$rows[$i];
	    	$opt[]	= makeOption($row->price_point, $row->price_name);
	    }
	    return selectList( $opt, $name, 'class="'.$class.'"', 'value', 'text',$default);
	    //return MultiSelectList($opt, $name, 'class="chosen-select"', 'value', 'text', $default);
    }*/

    /*function get_data($id = 0, $status = ''){
		$this->db->select('*');
		$this->db->from('_price');
		if($status != '') $this->db->where('status', $status);
		if($id > 0) $this->db->where('price_id', $id);
		$this->db->order_by('price_id', 'Asc');
		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_object();
	}

    function get_num($id = 0, $status = ''){
		$this->db->select('*');
		$this->db->from('_price_num');
		if($status != '') $this->db->where('status', $status);
		if($id > 0) $this->db->where('num_id', $id);
		$this->db->order_by('num_id', 'Asc');
		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_object();
	}

    public function get_max_id(){
		$this->db->select('max(price_id) as max_id');
		$this->db->from('_price');
		$query = $this->db->get();
		return $query->result_array(); 
    }

    function store($id = 0, $data){

			if($id > 0){
					$this->db->where('price_id', $id);
					$this->db->update('_price', $data);
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
					$insert = $this->db->insert('_price', $data);
					//Debug($this->db->last_query());
					return $insert;
			}
	}

	function delete_by_admin($id){
		$this->db->where('price_id', $id);
		$this->db->delete('_price'); 
	}*/
}
?>	
