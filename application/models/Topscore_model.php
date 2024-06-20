<?php

class Topscore_model extends CI_Model
{
    protected $prefix;

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
		// $this->prefix = $this->db->dbprefix;
        $this->prefix = 'ba';
    }

    function get_topscore()
    {
        $res = null;
        $w_season = "";

        // if($season != 0 && $season != ''){
        //     $w_season = sprintf("AND season = '%s'", $season);
        // }

     
        $sql = "SELECT sum(`ba_team_player`.`goals`) as `sum_goals`, `ba_team_player`.`profile_id`, `ba_team_player`.`name`, `ba_team_player`.`team_id`, `ba_team`.`team_name`
        FROM `ba_team_player`  
        LEFT JOIN `ba_team` ON `ba_team`.`team_id` = `ba_team_player`.`team_id`
        GROUP BY `ba_team_player`.`profile_id`
        ORDER BY `sum_goals` DESC
        LIMIT 15";
        // $sql = sprintf($sql, $tournament_id, $tournament_id);
 
        $query = $this->db->query($sql);
        
        // Debug($this->db->last_query());

        $res = $query->result_object();
              
        return $res;
    }

    function get_topassists()
    {
        $res = null;
        $w_season = "";

        // if($season != 0 && $season != ''){
        //     $w_season = sprintf("AND season = '%s'", $season);
        // }

     
        $sql = "SELECT sum(`ba_team_player`.`assists`) as `sum_assists`, `ba_team_player`.`profile_id`, `ba_team_player`.`name`, `ba_team_player`.`team_id`, `ba_team`.`team_name`
        FROM `ba_team_player`  
        LEFT JOIN `ba_team` ON `ba_team`.`team_id` = `ba_team_player`.`team_id`
        GROUP BY `ba_team_player`.`profile_id`
        ORDER BY `sum_assists` DESC
        LIMIT 15";
        // $sql = sprintf($sql, $tournament_id, $tournament_id);
 
        $query = $this->db->query($sql);
        
        // Debug($this->db->last_query());

        $res = $query->result_object();
              
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

    function get_xml_topscorers($tournament_id = 0, $team_id = 0, $round = 0, $season = '', $showdebug = 0)
    {
        $this->db->select('_xml_topscorers.id, _xml_topscorers.player_id, _xml_topscorers.player_name, _xml_topscorers.team as cur_team, _xml_topscorers.pos, _xml_topscorers.goals, _xml_topscorers.penalty_goals');
        $this->db->select('_team.team_id, _team.team_name, _team.team_name_en');
        $this->db->select('_player_profile.*');
        $this->db->from('_xml_topscorers');
        $this->db->join('_team_player', '_xml_topscorers.player_id = '.$this->prefix.'_team_player.profile_id', 'left');
        $this->db->join('_team', '_team_player.team_id = '.$this->prefix.'_team.team_id', 'left');
        $this->db->join('_player_profile', '_xml_topscorers.player_id = '.$this->prefix.'_player_profile.profile_id', 'left');
        //$this->db->where('_standing.status', 1);
        // $this->db->where('ba_xml_standing.tournament_id', $tournament_id);

        if ($tournament_id > 0) $this->db->where('_xml_topscorers.tournament_id', $tournament_id);
        if ($team_id > 0) $this->db->where('_xml_topscorers.team_id', $team_id);

        // if ($season != '') $this->db->where('_xml_topscorers.season', $season);

        // $this->db->order_by('group_id', 'asc');
        $this->db->order_by('_xml_topscorers.pos', 'asc');
        $query = $this->db->get();

        if ($showdebug == 1) Debug($this->db->last_query());
        return $query->result_object();
    }

    function get_xml_topassist($tournament_id = 0, $team_id = 0, $round = 0, $season = '', $showdebug = 0)
    {
        $this->db->select('_xml_topassist.id, _xml_topassist.player_id, _xml_topassist.player_name, _xml_topassist.team as cur_team, _xml_topassist.pos, _xml_topassist.assists');
        $this->db->select('_team.*');
        $this->db->select('_player_profile.*');
        $this->db->from('_xml_topassist');
        $this->db->join('_team_player', '_xml_topassist.player_id = '.$this->prefix.'_team_player.profile_id', 'left');
        $this->db->join('_team', '_team_player.team_id = '.$this->prefix.'_team.team_id', 'left');
        $this->db->join('_player_profile', '_xml_topassist.player_id = '.$this->prefix.'_player_profile.profile_id', 'left');
        //$this->db->where('_standing.status', 1);
        // $this->db->where('ba_xml_standing.tournament_id', $tournament_id);

        if ($tournament_id > 0) $this->db->where('_xml_topassist.tournament_id', $tournament_id);
        if ($team_id > 0) $this->db->where('_xml_topassist.team_id', $team_id);

        // if ($season != '') $this->db->where('_xml_topassist.season', $season);

        // $this->db->order_by('group_id', 'asc');
        $this->db->order_by('pos', 'asc');
        $query = $this->db->get();

        if ($showdebug == 1) Debug($this->db->last_query());
        return $query->result_object();
    }

    function store_xml_top($tournament_id = 0, $pos = 0, $data, $table = '_xml_topassist'){

        if ($tournament_id > 0 && $pos > 0) {
            
            $this->db->where('tournament_id', $tournament_id);
            $this->db->where('pos', $pos);

            $this->db->update($table, $data);

            return true;

        } else {

            $insert = $this->db->insert($table, $data);
            //Debug($this->db->last_query());
            return $insert;
        }
    }

    function store($id = 0, $data, $table = '_standing', $field = 'standing_id'){

        if ($id > 0) {

            $this->db->where($field, $id);
            $this->db->update($table, $data);

            return true;

        } else {

            $insert = $this->db->insert($table, $data);
            //Debug($this->db->last_query());
            return $insert;
        }
    }

    function reset($week = 0, $data){
        if ($week > 0) {
            $this->db->where('round', $week);
            $this->db->update('_standing', $data);

            return true;
        }
    }

    public function import(&$data = array()){
        $insert = $this->db->insert_batch('_standing', $data);
        return $insert;
    }

    function delete_standing($id){
        $this->db->where('table_id', $id);
        $this->db->delete('_standing');
    }

}

?>
