<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Api{
	private $_ci;
	protected $_host;
	protected $_host_api;

	public function __construct () {

        $this->_ci =& get_instance();
		$this->_ci->config->load('euro2024');

        $this->_host = base_url();
		$this->_host_api = $this->_ci->config->item('host_api');
    }

	public function get_program($sel_date){

		$key = 'api-tables-'.$sel_date;
		$action = 'tables?date='.$sel_date;
		$res = $this->callApi($action, $key);
		
		return $res;
    }

	public function get_newid($newsid = 0){

		$key = 'api-newsid-'.$newsid;
		$action = 'news/'.$newsid;
		$res = $this->callApi($action, $key);
		
		return $res;
    }

	public function get_listnew($catid = 7, $sel_page = 1, $number = 10){

		$key = 'api-cat'.$catid.'-newslist';
		$action = 'news?category_id='.$catid.'&page='.$sel_page.'&limit='.$number;
		$res = $this->callApi($action, $key);
		
		return $res;
    }
    
    private function callApi($action, $key = null, $use_cache = true, $showdebug = false){

		$host = $this->_host_api;
		$curl = curl_init();
		$url = $host.$action;

		if($showdebug == true)
			echo $url;

		$opt = array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 3,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET'
		);

		curl_setopt_array($curl, $opt);
		$response = curl_exec($curl);

		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			if($use_cache){

				// $tmp = json_decode($this->ci->utils->getCacheRedis($key));

				$tmp = null;
				return $tmp;
			}else{
				return false;
			}
		} else {

			$res = json_decode($response);

			return $res;
		}
	}
}
?>