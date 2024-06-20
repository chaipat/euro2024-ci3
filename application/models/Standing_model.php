<?php

class Standing_model extends CI_Model
{
    protected $prefix;
    public function __construct()
    {
        parent::__construct();

        $this->load->database();

		// $this->prefix = $this->db->dbprefix;
        $this->prefix = 'ba';
    }

    /*public function getSelect($default = 0, $name = "team_id", $id = "team_id", $class = "chosen-select"){//Dropdown List
    		
    		$language = $this->lang->language;
			$first = "--- ".$language['please_select']." ---";
	    	$rows = $this->get_standing();
			//Debug($rows);
	    	$opt = array();
	    	$opt[]	= makeOption(0, $first);

	    	for($i=0;$i<count($rows);$i++){
	    		$row = @$rows[$i];
	    		$opt[]	= makeOption($row->team_id, $row->team_name);
	    	}
	    	return selectList( $opt, $name, 'class="'.$class.'"', 'value', 'text',$default);
	    	//return MultiSelectList( $opt, $name, 'class="chosen-select"', 'value', 'text', $default);
    }*/

    function get_standing($tournament_id = 0, $season = '', $showdebug = 0)
    {
        $res = null;
        $w_season = "";

        if($season != 0 && $season != ''){
            $w_season = sprintf("AND season = '%s'", $season);
        }

        //GROUP BY ba_team.team_id
        $sql = "SELECT DISTINCT(ba_xml_standing.team_id), team_position, 
                COALESCE(NULLIF(ba_team.team_name, ''), `ba_xml_standing`.`team_name`) AS team_name, 
                COALESCE(NULLIF(ba_team.team_name_en, ''), `ba_xml_standing`.`team_name`) AS team_name_en, 
                group_name, group_id,
                recent_form, home_gp, home_w, home_d, home_l, home_gs, home_ga, away_gp, away_w, away_d, away_l, away_gs, away_ga, 
                overall_gp, overall_w, overall_d, overall_l, overall_gs, overall_ga, total_gd, total_p,
                `round`, tournament_id, season, description, team_status
                FROM `ba_xml_standing`
                LEFT JOIN `ba_team` ON ba_xml_standing.team_id = ba_team.team_id 
                WHERE tournament_id = %s  ".$w_season."
                AND `round` = (SELECT MAX(`round`) FROM `ba_xml_standing` WHERE `tournament_id` = '%s' ".$w_season.")
                ORDER BY team_position ASC, total_p DESC, total_gd DESC, team_position DESC";
        $sql = sprintf($sql, $tournament_id, $tournament_id);
        
        if($tournament_id > 0){
            $query = $this->db->query($sql);
            if ($showdebug == 1) 
                Debug($this->db->last_query());

            $res = $query->result_object();
        }        
        return $res;
    }

    function get_data($tournament_id = 0, $team_id = 0, $round = 0, $season = '', $showdebug = 0)
    {

        $this->db->select('*');
        $this->db->from('_standing');
        $this->db->join('_team', '_standing.team_id = _team.team_id', 'left');
        //$this->db->where('_standing.status', 1);
        $this->db->where('_standing.tournament_id', $tournament_id);

        if ($tournament_id > 0) $this->db->where('_standing.tournament_id', $tournament_id);
        if ($team_id > 0) $this->db->where('_standing.team_id', $team_id);
        if ($round > 0) $this->db->where('_standing.round', $round);

        $this->db->order_by('total_p', 'desc');
        $this->db->order_by('total_gd', 'desc');
        $query = $this->db->get();

        if ($showdebug == 1) Debug($this->db->last_query());
        return $query->result_object();
    }

    function get_xml_data($tournament_id = 0, $team_id = 0, $round = 0, $season = '', $showdebug = 0)
    {
        $this->db->select('*');
        $this->db->from('_xml_standing');
        $this->db->join('_team', '_xml_standing.team_id = '.$this->prefix.'_team.team_id', 'left');
        //$this->db->where('_standing.status', 1);
        // $this->db->where('ba_xml_standing.tournament_id', $tournament_id);

        if ($tournament_id > 0) $this->db->where('_xml_standing.tournament_id', $tournament_id);
        if ($team_id > 0) $this->db->where('_xml_standing.team_id', $team_id);
        if ($round > 0) $this->db->where('_xml_standing.round', $round);
        if ($season != '') $this->db->where('_xml_standing.season', $season);

        $this->db->order_by('group_id', 'asc');
        $this->db->order_by('team_position', 'asc');
        $query = $this->db->get();

        if ($showdebug == 1) Debug($this->db->last_query());
        return $query->result_object();
    }

    function get_group($tournament_id, $group_id = 0, $round = 0, $season = '', $showdebug = 0)
    {
        $this->db->select('*');
        $this->db->from('_xml_standing');
        $this->db->join('_team', '_xml_standing.team_id = '.$this->prefix.'_team.team_id', 'left');

        if ($tournament_id > 0) $this->db->where('_xml_standing.tournament_id', $tournament_id);
        if ($group_id > 0) $this->db->where('_xml_standing.group_id', $group_id);
        if ($round > 0) $this->db->where('_xml_standing.round', $round);
        if ($season != '') $this->db->where('_xml_standing.season', $season);

        // $this->db->order_by('group_id', 'asc');
        $this->db->order_by('team_position', 'asc');
        $query = $this->db->get();

        if ($showdebug == 1) Debug($this->db->last_query());
        return $query->result_object();
    }

    public function get_max_week($tournament_id = 1, $table = '_standing', $season = '')
    {
        $this->db->select('max(round) as round');
        $this->db->from($table);
        $this->db->where('tournament_id', $tournament_id);
        if($season != '')
            $this->db->where('season', $season);
        $query = $this->db->get();
        $res = $query->result_object();
        return $res[0]->round;
    }

    function addpoint(&$id, $week, $data, $tournament_id = 1)
    {

        if ($id > 0) {
            $this->db->where('standing_id', $id);
            $this->db->where('round', $week);
            $this->db->where('tournament_id', $tournament_id);
            //$this->db->set($data);
            if ($data)
                foreach ($data as $key => $val) {
                    $this->db->set($key, $val, false);
                }
            $this->db->update('_standing');
            //Debug($this->db->last_query());

            return true;
        }
    }

    function store($id, $data)
    {

        if ($id > 0) {
            $this->db->where('standing_id', $id);
            $this->db->update('_standing', $data);

            return true;

        } else {
            $insert = $this->db->insert('_standing', $data);
            //Debug($this->db->last_query());
            return $insert;
        }
    }

    function reset($week, $data)
    {
        if ($week > 0) {
            $this->db->where('round', $week);
            $this->db->update('_standing', $data);

            return true;
        }
    }

    public function import(&$data = array())
    {
        $insert = $this->db->insert_batch('_standing', $data);
        return $insert;
    }

    function delete_standing($id)
    {
        $this->db->where('table_id', $id);
        $this->db->delete('_standing');
    }

    function delete_team_xml_standing($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('_xml_standing');
    }
}

?>
