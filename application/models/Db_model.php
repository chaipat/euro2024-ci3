<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Db_model extends CI_Model {
    function __construct(){
        //parent::__construct();
        $this->load->database();
    }
    
    public function showtable(){
    	//$this->db->query('SHOW TABLES');
    	//$query = $this->db->get();
		$tables = $this->db->list_tables();
    	return $tables;
    }

    public function rename_tb($old_table, $new_table){

    	//Debug($this->db);
        //$this->db->rename_table($old_table, $new_table);
        $sql = "RENAME TABLE $old_table TO $new_table;";
        $res = $this->db->query($sql);
        return $res;
    }

    public function chg_engine($table){
        $sql = "ALTER TABLE $table ENGINE = INNODB;";
        $this->db->query($sql);
        return $sql;
    }

    public function showtable_status($db = 'winbigslot_slotxo'){
    	$sql = 'SHOW TABLE STATUS FROM `'.$db.'` WHERE ENGINE IS NOT NULL;';
		$query = $this->db->query($sql);
    	return $query;
    }

    public function show_full_field($table){
    	$sql = 'SHOW FULL FIELDS FROM `'.$table.'`;';
		$query = $this->db->query($sql);
    	return $query;
    }

    public function show_full_column($table){
    	$sql = 'SHOW FULL COLUMNS FROM `'.$table.'`;';
		$query = $this->db->query($sql);
    	return $query;
    }

    public function show_keys($table){
    	$sql = 'SHOW KEYS FROM `'.$table.'`;';
		$query = $this->db->query($sql);
    	return $query;
    }

    public function show_create_table($table){
    	$sql = 'SHOW CREATE TABLE FROM `'.$table.'`;';
		$query = $this->db->query($sql);
    	return $query;
    }

    public function get_query($sql){
		$query = $this->db->query($sql);
    	return $query;
    }

    function store($table = '', $data, $field = 'id', $id = 0, $showdebug = 0){

		if($table != ''){

			if($id > 0){

				$this->db->where($field, $id);
				$this->db->update($table, $data);
				return true;

			}else{

				$this->db->insert($table, $data);
				if($showdebug == 1) Debug($this->db->last_query());
				return $this->db->insert_id();
			}
		}

	}
}