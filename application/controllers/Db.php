<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Db extends CI_Controller {
	function __construct()
	{
		parent::__construct();

		if($_SERVER['HTTP_HOST'] == 'localhost')
			$this->db = $this->load->database('default', TRUE);
		else
			$this->db = $this->load->database('production', TRUE);

		$this->load->helper('common');
		$this->load->model('db_model');
	}

	function index(){

		$this->showtable();
	}

	function showtable(){

		$prefix_old = 'sp_';
		$prefix_new = 'ba_';

		echo "$prefix_old ==> $prefix_new<br>";

		$list_table = $this->db_model->showtable();

		if($list_table){
			for($i = 0;$i < count($list_table);$i++){

				$pattern = "/".$prefix_old."/i";
				echo "<br>".$list_table[$i];
				/*if (preg_match($pattern, $list_table[$i])) {
					//$new_table = preg_replace($pattern, $prefix_new', $list_table[$i]);
					//echo " ==> ".$new_table;
					//$rename_new = $this->db_model->rename_tb($list_table[$i], $new_table);
					//echo "<br>$rename_new";
					//echo " : <font color='red'>A match was found</font>.";
				} else {
					//echo " : A match was not found.";
				}*/
			}
		}

	}

	function rename_db(){

		$prefix_old = 'sp_';
		$prefix_new = 'ba_';

		$list_table = $this->db_model->showtable();
		//Debug($this->db->data_cache);
		// Debug($list_table);
		
		if ($list_table) {
			for ($i = 0; $i < count($list_table); $i++) {

				$pattern = "/".$prefix_old."/i";
				//echo "<br>".$list_table[$i];
				if (preg_match($pattern, $list_table[$i])) {
					$new_table = preg_replace($pattern, $prefix_new, $list_table[$i]);
					//echo " ==> ".$new_table;
					$rename_new = $this->db_model->rename_tb($list_table[$i], $new_table);
					echo "<br>$rename_new";
					echo " : <font color='red'>A match was found</font>.";
				} else {
					//echo " : A match was not found.";
				}
			}
		}

	}

	function chg_engine(){

		$list_table = $this->db_model->showtable();
		//Debug($this->db->data_cache);
		//Debug($list_table);
		if ($list_table) {
			for ($i = 0; $i < count($list_table); $i++) {
				//$pattern = "/".$prefix_old."/i";
				//echo "<br>".$list_table[$i];
				$alter_table = $this->db_model->chg_engine($list_table[$i]);
				echo "<br>$alter_table";

			}
		}
	}

	function show_table_status(){

		$res = $this->db_model->showtable_status();
		foreach ($res->result() as $row){
			echo $row->Name.":";
			echo $row->Engine.":";
			echo $row->Collation.":";
			echo $row->Comment;

			echo "<hr>";
		}
	}
}