<?php
class Season_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
        
		$this->load->database();

		$this->prefix = $this->db->dbprefix;
    }

    public function get_status($id){

    	$this->db->select('section_name, order_by, status');
    	$this->db->from($this->prefix.'_section');
    	$this->db->where('section_id', $id);
    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	return $query->result_array();
    }

    public function getSelect($default = 0, $name = "season_id2"){
    		
    		// $language = $this->lang->language;
    		$first = "--- กรุณาเลือกปี ---";
			$rows = $this->get_season();

			//Debug($rows);
	    	$opt = array();
	    	$opt[]	= makeOption(0,$first);

			/*if($rows->header->code == 200) {
				for ($i = 0; $i < $rows->header->total_rows; $i++) {
					$row = @$rows->body[$i];
					$season = $row->season;     //TypeSeason = 1[2015], TypeSeason = 2 [2015-2016]

					$opt[] = makeOption($season, $season);
				}
			}*/
			for ($i = 0; $i < count($rows); $i++) {
					$row = $rows[$i];
					$season_id = $row->season_id;
					$season_name = $row->season_name;
					$season_name2 = trim($row->season_name2);

					$opt[] = makeOption($season_name2, $season_name2);
			}

	    	return selectList($opt, $name, 'class="form-control"', 'value', 'text', $default);
    }

	public function getSelect1($default = 0, $name = "season_id1"){

		// $language = $this->lang->language;
		$first = "--- กรุณาเลือกปี ---";
		$rows = $this->get_ss();

		//Debug($rows);
		$opt = array();
		$opt[]	= makeOption(0,$first);

		if($rows->header->code == 200) {
			for ($i = 0; $i < $rows->header->total_rows; $i++) {
				$row = @$rows->body[$i];
				$season_obj = explode("-",$row->season);     //TypeSeason = 1[2015], TypeSeason = 2 [2015-2016]

				$opt[] = makeOption($season_obj[0], $season_obj[0]);
			}
		}

		return selectList($opt, $name, 'class="form-control"', 'value', 'text', $default);
	}

	public function get_ss(){
		
		$url_ss = 'http://www.siamsport.co.th/rss/api_epl/season.php';
	   	$listObj = json_decode($this->api_model->get_curl($url_ss));
		return $listObj;
	}

 	public function get_season($season_id = ''){

		$this->db->select('*');
    	$this->db->from($this->prefix.'_season');

    	if($season_id != '') $this->db->where('season_id', $season_id);
    	$this->db->where('status', 1);

    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	return $query->result_object();
	}
}
?>	
