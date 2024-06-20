<?php
class League_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
        
		$this->load->database();

		$this->prefix = $this->db->dbprefix;
    }

    public function get_status($id){

    	$this->db->select('league_id, league_name, status');
    	$this->db->from($this->prefix.'_league');
    	if($id) $this->db->where('league_id', $id);
    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	return $query->result_object();
    }

    public function get_maxid(){

    	$this->db->select('max(league_id) as maxid');
    	$this->db->from($this->prefix.'_league');    	
    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	return $query->result_object();
    }

    public function getSelect($default = 0, $name = "league_id", $class = "form-control chosen-select"){
    		
    		$language = $this->lang->language;
    		//$first = "--- ".$language['please_select'].' '.$language['league']." ---";
    		$first = "--- ".$language['all'].' '.$language['league']." ---";
			//$rows = $this->get_allleague();
			$rows = $this->get_league();
			//Debug($rows);
	    	$opt = array();
	    	
	    	$opt[]	= makeOption(0,$first);
			//if($rows->header->code == 200) {
				//for ($i = 0; $i < $rows->header->total_rows; $i++) {
				for ($i = 0; $i < count($rows); $i++) {

					$row = @$rows[$i];
					$league_id = $row->league_id;
					$league_name = $row->league_name;
					$league_name_en = '';
					//$TypeSeason = $season;        //TypeSeason = 1[2015], TypeSeason = 2 [2015-2016]
					$value = "$league_id:$league_name:$league_name_en";

					if($league_id == 0)
						$opt[] = makeOption($league_id, $first);
					else
						$opt[] = makeOption($value, $league_name);
				}
			//}
	    	return selectList($opt, $name, 'class="'.$class.'"', 'value', 'text', $default);
    }
     
   public function get_league($league_id = ''){
   		$prefix = $this->prefix;

    	$this->db->select('*');
    	$this->db->select('(SELECT `'.$prefix.'_admin`.`admin_username` FROM `'.$prefix.'_admin` WHERE `'.$prefix.'_admin`.`admin_id`=`'.$prefix.'_league`.`create_by`) AS create_by_name');
		$this->db->select('(SELECT `'.$prefix.'_admin`.`admin_username` FROM `'.$prefix.'_admin` WHERE `'.$prefix.'_admin`.`admin_id`=`'.$prefix.'_league`.`lastupdate_by`) AS lastupdate_by_name');
		
    	$this->db->from($this->prefix.'_league');

    	if($league_id != '') $this->db->where('league_id', $league_id);

    	$this->db->order_by('order', 'ASC');
    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	return $query->result_object();
    }

	public function get_allleague(){

		$url_league = 'http://www.siamsport.co.th/rss/api_epl/all_typesportsub.php';
		$league_list = json_decode($this->api_model->get_curl($url_league));
		//Debug($league_list);

		return $league_list;
	}

    function store($league_id=0, $data, $showdebug = 0){

    	if($league_id > 0){
			$this->db->where('league_id', intval($league_id));
			$this->db->update($this->prefix.'_league', $data);
			
			if($showdebug == 1) 
				Debug($this->db->last_query());

			return true;
    	}else{
			$result = $this->db->insert($this->prefix.'_league', $data);
			if($showdebug == 1) Debug($this->db->last_query());
		}
	    return $result;
	}

	public function add_batch(&$data = array(), $table = '_league', $debug = 0){
		if($debug == 1) Debug($data);
		$insert = $this->db->insert_batch($table, $data);
		if($debug == 1) Debug($this->db->last_query());
		return $insert;
	}

	function delete($id){

		if($this->session->userdata('admin_id') <= 2) {

			$this->delete_admin($id);

		}else{

			$data = array(
				'status' => 9
			);
			$this->db->where('league_id', $id);
			$this->db->update($this->prefix.'_league', $data);

			return true;
		}
	}

	function delete_admin($id){

		$this->db->where('league_id', $id);
		$this->db->delete($this->prefix.'_league');
	}
}
?>	
