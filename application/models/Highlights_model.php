<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Highlights_model extends CI_Model
{
    protected $prefix;
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
		$this->prefix = $this->db->dbprefix;
    }

    function get_data($tournament_id = 0, $team_id = 0, $season = '', $showdebug = 0)
    {
        $prefix = $this->prefix;
        
        $this->db->select('*');
        $this->db->select('(SELECT `'.$prefix.'_team`.`team_name` FROM `'.$prefix.'_team` WHERE `'.$prefix.'_team`.`team_id`=`'.$prefix.'_highlights`.`localteam_id`) AS localteam_th');
        $this->db->select('(SELECT `'.$prefix.'_team`.`team_name` FROM `'.$prefix.'_team` WHERE `'.$prefix.'_team`.`team_id`=`'.$prefix.'_highlights`.`visitorteam_id`) AS visitorteam_th');
		
        $this->db->from('_highlights');
        // $this->db->join('_team', '_highlights.team_id = _team.team_id', 'left');
        // $this->db->where('_highlights.tournament_id', $tournament_id);

        if ($tournament_id > 0) $this->db->where('_highlights.tournament_id', $tournament_id);
        if ($team_id > 0) $this->db->where('_highlights.team_id', $team_id);
        if ($season > 0) $this->db->where('_highlights.season', $season);

        $this->db->order_by('match_time', 'desc');
        $this->db->order_by('match_date', 'desc');
        $query = $this->db->get();

        if ($showdebug == 1) Debug($this->db->last_query());
        return $query->result_object();
    }

    function store($id, $data = array(), $table = '_highlights', $field = 'highlight_id'){

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

    public function import(&$data = array()){

        $insert = $this->db->insert_batch('_highlights', $data);
        return $insert;
    }

    function delete_standing($id){

        $this->db->where('highlight_id', $id);
        $this->db->delete('_highlights');
    }

}

?>
