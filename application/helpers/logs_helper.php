<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('SaveLogs')){
	function SaveLogs($data, $filename = 'data', $encode = false, $pathfile = '', $test = 0){

		if($pathfile == '') $pathfile = date('Y-m-d');
		//if($pathfile == '') $pathfile = 'users_access/';

		if (!is_dir("data")) mkdir("data", 0777,true);
		if($test == 0){
			if (!is_dir("data/".$pathfile)) mkdir("data/".$pathfile, 0777, true);
			$logs_files = "data/".$pathfile.'/'.$filename.".log";
		}else{
			if (!is_dir("../data/".$pathfile)) mkdir("../data/".$pathfile, 0777, true);
			$logs_files = "../data/".$pathfile.'/'.$filename.".log";
		}
		//$json_files = "json/".date('y-m-d').".json";

		if($encode == true)
			$data_json = json_encode($data);
		else
			$data_json = $data;

		if(file_exists($logs_files)){

			$objFopen=fopen($logs_files,'a');
			fwrite($objFopen, $data_json);
			fclose($objFopen);				
		}else{
			
			$objFopen=fopen($logs_files,'w');
			fwrite($objFopen, $data_json);
			fclose($objFopen);
		}
		//echo "Save file $logs_files";
	}
}

if ( ! function_exists('SaveFile')){
	function SaveFile($data, $filename, $encode = false, $pathfile = ''){

		if($pathfile == '') $pathfile = 'data/';

		if (!is_dir('data/'.$pathfile)) mkdir('data/'.$pathfile, 0777, true);
		
		if($pathfile != '') $pathfile .= '/';
		$logs_files = 'data/'.$pathfile.$filename;

		if($encode == true)
			$data_json = json_encode($data);
		else
			$data_json = $data;				

		$objFopen=fopen($logs_files,'w');
		fwrite($objFopen, $data_json);
		fclose($objFopen);
		
		/*if(file_exists($logs_files)){
			$objFopen=fopen($logs_files,'a');
			fwrite($objFopen, $data_json);
			fclose($objFopen);				
		}else{
			$objFopen=fopen($logs_files,'w');
			fwrite($objFopen, $data_json);
			fclose($objFopen);
		}*/

		return $logs_files;
	}
}

if ( ! function_exists('LoadLogs')){
	function LoadLogs($filename = 'historical.txt', $pathfile = 'xml'){

		if($pathfile == '') $pathfile = date('Y-m-d');
		//if($pathfile == '') $pathfile = 'users_access/';

		$load_files = "./$pathfile/$filename";

		if(file_exists($load_files)){
			$objFopen=fopen($load_files,'r');
			$res = fread($objFopen, filesize($load_files));
			fclose($objFopen);
		}
		//echo "Save file $logs_files";
		return $res;
	}
}

if (!function_exists('mail_error')){
	function mail_error($errno, $errstr, $errfile, $errline) {

		$myemail = '';
		$message = "[Error $errno] $errstr - Error on line $errline in file $errfile";
		error_log($message); // writes the error to the log file
		mail($myemail, 'I have an error', $message);
	}
	//set_error_handler('mail_error', E_ALL^E_NOTICE);
}