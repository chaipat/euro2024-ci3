<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Utils{
	protected $ci;
	var $country_list;

	public function __construct () {
		$this->ci =& get_instance();
		$this->ci->config->load('setting');

	}

	public function preUrlStr($string, $replace = '-', $default_text = 'winbig'){

		$string = trim($string);
		if(empty($string)){
			return $default_text;
		}
		$string = preg_replace('!\s+!', ' ', $string);
		$string = preg_replace('!\s+!', $replace, $string);
		$string = urlencode(strtolower($string));

		return $string;
	}

	public function htmlCompress($buffer){
		$buffer = preg_replace('/<!--(.|\s)*?-->/', '', $buffer);

	    // remove mutiline js css comment
	    // refer this source http://stackoverflow.com/questions/643113/regex-to-strip-comments-and-multi-line-comments-and-empty-lines
	    $buffer = preg_replace('!/\*.*?\*/!s', '', $buffer);

	    // remove single line comment
	    $buffer = preg_replace('#^\s*//.+$#m', "", $buffer);
	    $buffer = preg_replace('~^\h*//\h*$~m', '', $buffer);

	    // remove inline comment
	    $buffer = preg_replace('/\s+\/\/[^\n]+/m', '', $buffer);
	    $buffer = preg_replace('/(?<=;)\s+\/\/[^\n]+/m', '', $buffer);

	    // remove empty single line
	    // refer this link http://stackoverflow.com/questions/34689674/php-regex-remove-inline-comment-only/34689766?noredirect=1#comment57127722_34689766
	    $buffer = preg_replace('/(?<=;)\s+\/\/[^\n]+/m', '', $buffer);

	    // remove newline
	    $buffer = str_replace(array("\r\n", "\r", "\n", "\t"), " ", $buffer);

	    // remove spaces
	    $buffer =  preg_replace('!\s+!', ' ', $buffer);
	    $buffer = trim($buffer);
	    return $buffer;
	}

	public function getRedisinfo(){

		$prefix = $this->ci->config->item('radis_prefix');
		$object = $this->connectRedis();

		// Debug($prefix);
		// Debug($this->ci->config->config['radis_server']);

		if($object){
			return $object;
		}else{
			return false;
		}

	}

	public function getCache($key, $layout = ''){

		$prefix = $this->ci->config->item('radis_prefix');
		$cache = $this->getCacheRedis($key);

		if($cache){
			return $cache;
		}else{
			return false;
		}

	}

	public function getCacheRedis($key){

		/*
		$prefix = $this->ci->config->item('radis_prefix');
		$object = $this->connectRedis();

		if($object){
			
		    $data = $object->GET($prefix.$key);
			return $data;
			
		}else{
		    //$this->logs('error', 'Redis Error: getCacheRedis function'.$e->getMessage());
			return false;
		}
			*/
	}

	public function setCacheRedis($key, $data, $expire = null){

		$prefix = $this->ci->config->item('radis_prefix');

		if($expire == null){
			$expire = 60*60*1;	//seconds is empty expire 1 hour
		}

		$object = $this->connectRedis();
		if($object){
		    //set expire time
		    if(!is_null($expire)){

		    	$object->SETEX($prefix.$key, $expire, $data);
		    }else{
				
		    	$object->SET($prefix.$key, $data);
		    }
		    return true;
		} else{
		    //$this->logs('error', 'Redis Error: setCacheRedis function '.$e->getMessage());
		    return false;
		}

	}

	public function getRedisAll(){
		
		$prefix = $this->ci->config->item('radis_prefix');
		$object = $this->connectRedis();

		if($object){
			
		    $data = $object->keys($prefix.'*');
			return $data;
			
		}else{
		    //$this->logs('error', 'Redis Error: getCacheRedis function'.$e->getMessage());
			return false;
			
		}
	}

	public function deleteRedis($key, $opt = ''){

		try{
			$prefix = $this->ci->config->item('radis_prefix');
			$object = $this->connectRedis();
			if($object){
				// deleting the value from redis
				if($opt == 'noprefix')
					$data = $object->del($key);
				else
					$data = $object->del($prefix.$key);

			    return $data;
			}else{
			    //$this->logs('error', 'Redis Error: getCacheRedis function'.$e->getMessage());
			    return false;
			}

		}catch( Exception $e ){
			echo $e->getMessage();
		}
	}

	public function flushAll(){

		// try{
		// 	$object = $this->connectRedis();
		// 	if($object){
		// 		// deleting the value from redis
		// 	    $data = $object->flushAll();
		// 	    return $data;
		// 	}else{
		// 	    //$this->logs('error', 'Redis Error: getCacheRedis function'.$e->getMessage());
		// 	    return false;
		// 	}

		// }catch( Exception $e ){
		// 	echo $e->getMessage();
		// }
	}

	private function connectRedis(){

		$radis_server = $this->ci->config->item('radis_server');
		$radis_port = $this->ci->config->item('radis_port');
		$radis_auth = $this->ci->config->item('radis_auth');

		// Debug($radis_server);
		// Debug($radis_port);
		// Debug($radis_auth);
		// Debug($this->ci->config->config['radis_server']);

        try {
			
			if(class_exists('Redis')){

				$redis = new Redis();
				//param : server, port, timeout
				//$redis->connect('localhost', 6379, 2);
				$redis->connect($radis_server, $radis_port);
				if($radis_auth != '') $redis->auth($radis_auth);				
			}else
				return false;


        	return $redis;
        } catch (Exception $e) {
        	return false;
        }
	}

	public function number($num){
		return number_format($num, 0, '.', ',');
	}

	public function shortNumber($num) {
        $x = round($num);
        if($x >= 1000){
        	$x_number_format = number_format($x);
			$x_array = explode(',', $x_number_format);
			$x_parts = array('k', 'm', 'b', 't');
			$x_count_parts = count($x_array) - 1;
			$x_display = $x;
			$x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
			$x_display .= $x_parts[$x_count_parts - 1];
			return $x_display;
        }else{
        	return $x;
        }
    }

	private function getThaiDay($d){
		$day = array(
			'Sun' => 'อาทิตย์',
			'Mon' => 'จันทร์',
			'Tue' => 'อังคาร',
			'Wed' => 'พุธ',
			'Thu' => 'พฤหัส',
			'Fri' => 'ศุกร์',
			'Sat' => 'เสาร์',
		);
		return $day[$d];
	}

	private function getThaiMonth($m){
		$month = array(
			'01' => 'มกราคม',
			'02' => 'กุมภาพันธ์',
			'03' => 'มีนาคม',
			'04' => 'เมษายน',
			'05' => 'พฤษภาคม',
			'06' => 'มิถุนายน',
			'07' => 'กรกฎาคม',
			'08' => 'สิงหาคม',
			'09' => 'กันยายน',
			'10' => 'ตุลาคม',
			'11' => 'พฤศจิกายน',
			'12' => 'ธันวาคม'
		);
		return $month[$m];
	}

	private function getThaiShortMonth($m){
		$month = array(
			'01' => 'ม.ค.',
			'02' => 'ก.พ.',
			'03' => 'มี.ค.',
			'04' => 'เม.ย.',
			'05' => 'พ.ค.',
			'06' => 'มิ.ย.',
			'07' => 'ก.ค.',
			'08' => 'ส.ค.',
			'09' => 'ก.ย.',
			'10' => 'ต.ค.',
			'11' => 'พ.ย',
			'12' => 'ธ.ค.'
		);
		return $month[$m];
	}

	public function dateThaiFormat($d, $format = 'Y-m-d H:i:s'){
		/*
		d - 1-31
		D - จันทร์
		M - มกราคม
		m - ม.ค
		Y - 2559
		y - 59

		H - 0-24 hour
		i - 00-59 min
		s - 00-59 sec
		*/
		$day = date('D', strtotime($d));
		$date = date('j', strtotime($d));
		$month = date('m', strtotime($d));
		$year = date('Y', strtotime($d));

		$hour = date('H', strtotime($d));
		$min = date('i', strtotime($d));
		$sec = date('s', strtotime($d));

		$thaiDay = $this->getThaiDay($day);
		$thaiMonth = $this->getThaiMonth($month);
		$thaiShortMonth = $this->getThaiShortMonth($month);
		$thaiYear = $year+543;
		$shortThaiYear = substr($thaiYear, -2);

		$format = str_replace('d', $date, $format);
		$format = str_replace('D', $thaiDay, $format);
		$format = str_replace('M', $thaiMonth, $format);
		$format = str_replace('m', $thaiShortMonth, $format);
		$format = str_replace('Y', $thaiYear, $format);
		$format = str_replace('y', $shortThaiYear, $format);

		$format = str_replace('H', $hour, $format);
		$format = str_replace('i', $min, $format);
		$format = str_replace('s', $sec, $format);
		return $format;
	}

	public function logs($level, $message, $page = ''){
		$file = APPPATH.'logs/logs.txt';

		if(filesize($file) > 1048576){ // more than 1 MB
			copy($file, APPPATH.'logs/logs-'.date('Y-m-d_H-i-s').'.txt');
			file_put_contents($file, '');
		}

		$message = trim(preg_replace('/\s+/', ' ', $message));
		$text = date('Y-m-d H:i:s')." - ".$level." - ".$message."\n";
		file_put_contents($file, $text, FILE_APPEND);
	}
}
?>
