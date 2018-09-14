<?php 

namespace utils 
{
	require __DIR__ . '/vendor/autoload.php';


	const LOG_FILE = "php.log";

	function init(){

		unlink(LOG_FILE);

		return true;
	}

	function log_message($message, $withEcho = false){
		if($message != null){
			$date = date('Y-m-d\ H:i:s'); 
			error_log("$date : $message \n", 3,LOG_FILE);
			if($withEcho){
				echo("$message \n");
			}
		}
	}

	function change_ini(string $varname , string $newvalue){
		$handle = ini_set($varname, $newvalue);   
		if($handle === false || $handle === 00){
			return "Cannot set $varname";
		}
		elseif(is_string($handle)){
			if(empty($handle))
				$handle = "0";
			 return "$varname change from $handle to $newvalue";
		}

	}

	function download_file($path){
		log_message("Download file : $path");
		header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename="'.basename($path).'"');
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($path));
	    readfile($path);
		
	}	

	function download_file_with_curl($path, $target_file){
		$curl = new Curl\Curl();
		// open the file where the request response should be written
		$file_handle = fopen($target_file, 'w+');
		// pass it to the curl resource
		$curl->setOpt(CURLOPT_FILE, $file_handle);
		// do any type of request
		$curl->get($path);
		// disable writing to file
		$curl->setOpt(CURLOPT_FILE, null);
		// close the file for writing
		fclose($file_handle);
		return "Download file : $path"; 
	}


	class Form {

		static function input(string $input_type = 'text'){
			return "<input type=$input_type>"; 
		}
	}
}

?>
