<?php
class Price_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();

		$this->load->database();

		$this->prefix = $this->db->dbprefix;
    }

	public function Sel_interest($name, $id, $chk = 0){

		$interest = "<select name='$name'>";

		if($chk == 'ได้'){
			$interest .= "<option value='ได้' selected='selected'>ได้</option><option value='เสีย'>เสีย</option><option value='เสมอ'>เสมอ</option>";
		}else if($chk == 'เสีย'){
			$interest .= "<option value='ได้'>ได้</option><option value='เสีย' selected='selected'>เสีย</option><option value='เสมอ'>เสมอ</option>";
		}else{
			$interest .= "<option value='ได้'>ได้</option><option value='เสีย'>เสีย</option><option value='เสมอ' selected='selected'>เสมอ</option>";
		}
		$interest .= "</select>";
		return $interest;
	}

	public function getSelect($default = 0, $name = "price_name", $id = "price_name", $class = ""){//Dropdown List
		//chosen-select
		//form-control
		$language = $this->lang->language;
		$first = "--- ".$language['please_select']." ---";
		$rows = $this->get_data();
		//Debug($rows);
		$opt = array();
		$opt[]	= makeOption('', $first);

		for($i=0;$i<count($rows);$i++){
			$row = @$rows[$i];
			//$opt[]	= makeOption($row->price_point, $row->price_name);
			$opt[]	= makeOption($row->price_name, $row->price_name);
		}
		return selectList( $opt, $name, 'class="'.$class.'"', 'value', 'text',$default);
		//return MultiSelectList($opt, $name, 'class="chosen-select"', 'value', 'text', $default);
	}

    public function getSelectID($default = 0, $name = "price_id", $id = "price_id", $class = ""){//Dropdown List
    	//chosen-select
		//form-control
    	$language = $this->lang->language;
		$first = "--- ".$language['please_select']." ---";
	    $rows = $this->get_data();
		//Debug($rows);
	    $opt = array();
	    $opt[]	= makeOption('', $first);

	    for($i=0;$i<count($rows);$i++){
	    	$row = @$rows[$i];
	    	//$opt[]	= makeOption($row->price_point, $row->price_name);
			$opt[]	= makeOption($row->price_id, $row->price_name);
	    }
	    return selectList( $opt, $name, 'class="'.$class.'"', 'value', 'text',$default);
	    //return MultiSelectList($opt, $name, 'class="chosen-select"', 'value', 'text', $default);
    }

    public function getNumSelect($default = 0, $name = "num_id", $id = "num_id", $class = "form-control"){//Dropdown List
    	//chosen-select
    	$language = $this->lang->language;
		$first = "--- ".$language['please_select']." ---";
	    $rows = $this->get_num();
		//Debug($rows);
	    $opt = array();
	    $opt[]	= makeOption(0, $first);

	    for($i=0;$i<count($rows);$i++){
	    	$row = @$rows[$i];
			//$opt[]	= makeOption($row->num_name, $row->num_name);
	    	$opt[]	= makeOption($row->num_id, $row->num_name);
	    }
	    return selectList( $opt, $name, 'class="'.$class.'"', 'value', 'text',$default);
	    //return MultiSelectList($opt, $name, 'class="chosen-select"', 'value', 'text', $default);
    }

    function get_data($id = 0, $status = ''){

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
		return $query->result_object(); 
    }

    function store($id = 0, $data){

		if($id > 0){

			$this->db->where('price_id', $id);
			$this->db->update('_price', $data);

			//Debug($this->db->last_query());
			return true;					
		}else{
			$insert = $this->db->insert('_price', $data);
			//Debug($this->db->last_query());
			return $insert;
		}
	}

    function delete_price($id){

    	$this->delete_by_admin($id);
	}

	function delete_by_admin($id){
		
		$this->db->where('price_id', $id);
		$this->db->delete('_price'); 
	}
}
?>	
