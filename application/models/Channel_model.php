<?php
class Channel_model extends CI_Model {

	protected $prefix;
	public function __construct(){
		parent::__construct();
		
		$this->load->database();

		$this->prefix = $this->db->dbprefix;
	}

	public function get_status($id){
		//$language = $this->lang->language['lang'];
		$this->db->select('status');
		$this->db->from($this->prefix.'_channel');
		$this->db->where('channel_id2', $id);
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result_array();
	}

	public function get_max_order(){
		//$language = $this->lang->language['lang'];
		$this->db->select('max(order_by) as max_order');
		$this->db->from($this->prefix.'_channel');
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_max_id(){
		//$language = $this->lang->language['lang'];
		$this->db->select('max(channel_id) as max_id');
		$this->db->from($this->prefix.'_channel');
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function validate_channel($keyword){

		$this->db->select('*');
		$this->db->from($this->prefix.'_channel');
		$this->db->where('channel_name', trim($keyword));
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result_object();

	}

	public function getSelect($default = 0, $name = "channel_id", $first = ""){
		$language = $this->lang->language;
		$first = "--- ".$language['please_select']." ---";

		$rows = $this->get_content_all();
		$opt = array();

		if($first != "" ) $opt[]	= makeOption(0, $first);
		//$opt[]	= makeOption(0,$first);

		for($i=0;$i<count($rows);$i++){
			$row = @$rows[$i];
			$opt[]	= makeOption($row['channel_id'], $row['channel_name']);
		}
		//return selectList( $opt, $name, 'class="chosen-select"', 'value', 'text',$default);
		//return MultiSelectList( $opt, $name, 'class="chosen-select"', 'value', 'text',$default);
		return selectList( $opt, $name, 'class="form-control"', 'value', 'text', $default);
	}

	public function get_content_id($channel_id){

		$language = $this->lang->language['lang'];
		$this->db->select('*');
		$this->db->from($this->prefix.'_channel');
		$this->db->where('channel_id', $channel_id);
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result_array();

	}

	public function get_content_all($order_by = 'channel_name', $order_type = 'asc'){

		$language = $this->lang->language['lang'];
		$this->db->select('*');
		$this->db->from($this->prefix.'_channel');
		//$this->db->where('channel_id', $channel_id);
		//$this->db->where('lang', $language);

		$this->db->order_by($order_by, $order_type);

		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result_array();
	}

	public function get_content($status=null, $order_by='channel_id', $order_type='Asc', $limit_start = 0, $listpage = 0){

		$language = $this->lang->language['lang'];

		$this->db->select($this->prefix.'_channel.channel_id');
		$this->db->select('channel_name');
		$this->db->select('channel_icon');
		$this->db->select('create_date');
		$this->db->select('order_by');
		$this->db->select('status');

		$this->db->from($this->prefix.'_channel');

		//$this->db->where('lang', $language);
		$this->db->order_by($order_by, $order_type);
		if($listpage > 0) $this->db->limit($listpage, $limit_start);
		//$this->db->query('SELECT * FROM `sd_channel`');

		$query = $this->db->get();
		//Debug($this->db->last_query());

		//return $query->result_object();
		return $query->result_array();
	}

	function count_channel($channel_id=null, $search_string=null, $order=null){

		$this->db->select('*');
		$this->db->from($this->prefix.'_channel');
		if($channel_id != null && $channel_id != 0){
			$this->db->where('channel_id', $channel_id);
		}
		if($search_string){
			$this->db->like('channel_name', $search_string);
		}

		if($order){
			$this->db->order_by($order, 'Asc');
		}

		$query = $this->db->get();
		return $query->num_rows();
	}

	function store($id = 0, $data){

		if($id > 0){
			$this->db->where('channel_id', $id);
			$this->db->update($this->prefix.'_channel', $data);
	
			return true;

		}else{
			$insert = $this->db->insert($this->prefix.'_channel', $data);
			//echo $this->db->last_query();
			return $insert;
		}
	}

	public function getall_channel_pair($ref_type = 1){

		//$language = $this->lang->language['lang'];
		$this->db->select('tp.*, t.channel_name');
		$this->db->from($this->prefix.'_channel_pair tp');
		$this->db->join($this->prefix.'_channel t', 'tp.channel_id=t.channel_id and t.status = 1');
		//$this->db->where('tp.ref_id', $ref_id);
		$this->db->where('tp.ref_type', $ref_type);

		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result_object();

	}

	public function get_channel_pair($ref_id, $ref_type = 1){

		//$language = $this->lang->language['lang'];
		$this->db->select('tp.*, t.channel_name');
		$this->db->from($this->prefix.'_channel_pair tp');
		$this->db->join($this->prefix.'_channel t', 'tp.channel_id=t.channel_id and t.status = 1');
		$this->db->where('tp.ref_id', $ref_id);
		$this->db->where('tp.ref_type', $ref_type);

		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result_object();

	}

	function store_channel_pair($data, $clear = 1){

		if($clear == 1){
			$i=0;
			$this->db->where('ref_id', $data[0]['ref_id']);
			$this->db->where('ref_type', $data[0]['ref_type']);
			$this->db->delete($this->prefix.'_channel_pair');
		}

		//$this->_ci->db->insert_batch("_admin_access_menu",$obj);	
		$insert = $this->db->insert_batch($this->prefix.'_channel_pair', $data);
		//pair_id, channel_id, ref_id, ref_type, create_date
		return $insert;
	}

	function update_order($channel_id, $order = 1){

		$data['order_by'] = $order;
		$this->db->where('channel_id2', $channel_id);
		$this->db->update($this->prefix.'_channel', $data);
		//Debug($this->db->last_query());

		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function status_channel($channel_id, $enable = 1){

		$data['status'] = $enable;
		$this->db->where('channel_id', $channel_id);
		$this->db->update($this->prefix.'_channel', $data);
		//echo $this->db->last_query();

		return true;
	}

	function remove_img($channel_id, $dataobj = null){

		if($dataobj == null)
			$dataobj = array(
				'channel_icon' => ''
			);

		$this->db->where('channel_id2', $channel_id);
		$this->db->update($this->prefix.'_channel', $dataobj);

		return true;
	}

	function delete_channel($channel_id){

		$data = array(
			'status' => 2
		);
		$this->db->where('channel_id2', $channel_id);
		$this->db->update($this->prefix.'_channel', $data);

		return true;
	}

	function delete_channel_by_admin($channel_id){
		$this->db->where('channel_id2', $channel_id);
		$this->db->delete($this->prefix.'_channel');
	}

	function delete_tag($tag_text, $ref_type = 7){

		$this->db->select('tp.*, t.tag_text');
		$this->db->from('_tag_pair tp');
		$this->db->join('_tag t', 'tp.tag_id=t.tag_id and t.status = 1');
		$this->db->where('t.tag_text', $tag_text);
		$this->db->where('tp.ref_type', $ref_type);

		$query = $this->db->get();
		$result =  $query->result_object();

		if($result){

			$pair_id = $result[0]->pair_id;
			//Debug($pair_id);
			$this->db->where('pair_id', $pair_id);
			$this->db->delete('_tag_pair');
			//Debug($this->db->last_query());
		}
		return $result;
	}
}
?>	
