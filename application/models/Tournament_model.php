<?php
class Tournament_model extends CI_Model {
	protected $prefix;
    public function __construct(){
		parent::__construct();

		$this->load->database();

		// $this->prefix = $this->db->dbprefix;
		$this->prefix = 'ba';
    }

    public function get_status($id){

    	$this->db->select('tournament_name_en, status');
    	$this->db->from($this->prefix.'_tournament');
    	$this->db->where('tournament_id', $id);
    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	return $query->result_array();
    }

	public function get_max_id(){
		$this->db->select('max(tournament_id) as max_id');
		$this->db->from($this->prefix.'_tournament');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result_array();
	}

	public function get_group(){
		$this->db->select('file_group');
		$this->db->from($this->prefix.'_tournament');
		$this->db->group_by('file_group');
		$query = $this->db->get();
		return $query->result_object();
	}

    public function getSelect($default = 0, $limit = 0, $name = "tournament_id", $class = "form-control chosen-select"){
    		
    	// $language = $this->lang->language;
    	$first = "--- กรุณาเลือกหมวดหมู่ ---";

		$rows = $this->get_data(0, 1, '', '', $limit);			
		//Debug($rows);
	    $opt = array();
	    $opt[]	= makeOption(0,$first);
	    for($i=0;$i<count($rows);$i++){
	    	$row = @$rows[$i];
	    	$tournament_name = ($row->tournament_name != '') ? $row->tournament_name : $row->tournament_name_en;
	    	$opt[]	= makeOption($row->tournament_id, $tournament_name);
	    }
	    return selectList($opt, $name, 'class="'.$class.'"', 'value', 'text', $default);
    }
     
   	public function get_data($tournament_id = 0, $active = 0, $order_by = '', $order_type = '', $listpage = 0, $showdebug = 0){

		//$language = $this->lang->language['lang'];
   		// $prefix = 'sp';
		$this->db->select($this->prefix.'_tournament.*');
		//$this->db->select('(SELECT `'.$prefix.'_admin`.`admin_username` FROM `'.$prefix.'_admin` WHERE `'.$prefix.'_admin`.`admin_id`=`'.$prefix.'_tournament`.`create_by`) AS create_by_name');
		//$this->db->select('(SELECT `'.$prefix.'_admin`.`admin_username` FROM `'.$prefix.'_admin` WHERE `'.$prefix.'_admin`.`admin_id`=`'.$prefix.'_tournament`.`lastupdate_by`) AS lastupdate_by_name');
		$this->db->from($this->prefix.'_tournament');
		//$this->db->join('_site', '_tournament.site_id = _site.site_id', 'left');

		if($tournament_id != null && $tournament_id > 0){
			$this->db->where($this->prefix.'_tournament.tournament_id', $tournament_id);
		}

		if($active == 1){
			$this->db->where('status', 1);
		}else{
			//$this->db->where('lang', $language);
			$this->db->where('status !=', 9);
		}

		if($order_by == ''){
			$this->db->order_by('order', 'Asc');
			$this->db->order_by('tournament_name_en', 'Asc');
		}else{
			$this->db->order_by($order_by, $order_type);
		}

		if($listpage > 0)
			$this->db->limit($listpage, 0);

		$query = $this->db->get();
		
		if($showdebug == 1) Debug($this->db->last_query());

		$data1 = $query->result_object();		
		return $data1;
    }

	public function standing($tournament_id = 0){
		$this->db->select('*');
		$this->db->from($this->prefix.'_xml_standing');
		if($tournament_id > 0) $this->db->where('tournament_id', $tournament_id);
		$query = $this->db->get();
		return $query->result_object();
	}

    function store($tournament_id = 0, $data = array(), $showdebug = 0){

    	if($tournament_id == 0){

    		$insert = $this->db->insert($this->prefix.'_tournament', $data);
	    	return $this->db->insert_id();
    	}else{

			$this->db->where('tournament_id', intval($tournament_id));
			$this->db->update($this->prefix.'_tournament', $data);
			
			if($showdebug == 1)
				Debug($this->db->last_query());

			return true;		
    	}
	}

	function inactive99(){
		$data = array(
			"order" => 99
		);
		$this->db->where('status', 0);
		$this->db->update($this->prefix.'_tournament', $data);

		return true;
	}

	/*********************tournament_group******************************/
	public function get_group_list($tournament_group_id = 0, $active = 0, $showdebug = 0){

   		$prefix = $this->prefix;
		$this->db->select('*');		
		$this->db->from('_tournament_group');
		//$this->db->join('_site', '_tournament.site_id = _site.site_id', 'left');

		if($tournament_group_id > 0){
			$this->db->where('_tournament_group.tournament_group_id', $tournament_group_id);
		}
		if($active == 1){
			$this->db->where('_tournament_group.status', 1);
		}else{
			$this->db->where('_tournament_group.status !=', 9);
		}
		$this->db->order_by('_tournament_group.file_group', 'Asc');
		$query = $this->db->get();	

		if($showdebug == 1) 
			Debug($this->db->last_query());
		
		return $query->result_object();
    }

    function store_group($tournament_group_id = 0, $data = array(), $showdebug = 0){
    	
		if($tournament_group_id == 0){

    		$this->db->truncate('_tournament_group');
    		$insert = $this->db->insert_batch('_tournament_group', $data);
	    	return $this->db->insert_id();
    	}else{

			$this->db->where('tournament_group_id', intval($tournament_group_id));
			$this->db->update('_tournament_group', $data);

			if($showdebug == 1) 
				Debug($this->db->last_query());

			return true; 		
    	}
	}

	public function chk_league($tournament_id){

		if($tournament_id == 1204){//พรีเมียร์ลีก อังกฤษ
			return false;
		}else if($tournament_id == 1229){//บุนเดสลีกา เยอรมัน
			return false;
		}else if($tournament_id == 1269){//กัลโช่ เซเรียอา อิตาลี
			return false;
		}else if($tournament_id == 1399){//ลาลีกา สเปน
			return false;
		}else if($tournament_id == 1221){//ลีกเอิง ฝรั่งเศส
			return false;
		}else
			return true;

	}
 
}
?>	
