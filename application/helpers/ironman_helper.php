<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
##Function names##
force_ssl
generateUUID
generateOrderID
trimDropDown
*/
if ( ! function_exists('force_ssl'))
{
	function force_ssl() {		
		if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") {
			$url = "https://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			redirect($url);
			exit;		
		}	
	}
}


if ( ! function_exists('generateUUID'))
{
	function generateUUID(){
		$charid = md5(uniqid(rand(), true).time());
		$hyphen = chr(45);// "-"
		$uuid = substr($charid, 0, 8).$hyphen
		  .substr($charid, 8, 4).$hyphen
		  .substr($charid,12, 4).$hyphen
		  .substr($charid,16, 4).$hyphen
		  .substr($charid,20,12);
		return $uuid;
	}
}

if ( ! function_exists('generateOrderID'))
{
	function generateOrderID($current_serial){
		$rest_gn = substr(time(), -4, 6); 
		$rand_gn = rand(11111, 99999);
		$rand_b_gn=substr($rand_gn, 0, 4);
		$serial_gn=$current_serial+1;
		return $rest_gn.date("md").$rand_b_gn.$serial_gn;
	}
}

if ( ! function_exists('trimDropDown'))
{
	function trimDropDown($param){
		$dropdown = str_replace("_"," ",$param);
		return ucfirst($dropdown);
	}
}

if ( ! function_exists('randomPassword'))
{
	function randomPassword($length,$count, $characters) {
	 
	// $length - the length of the generated password
	// $count - number of passwords to be generated
	// $characters - types of characters to be used in the password
	 
	// define variables used within the function    
	    $symbols = array();
	    $passwords = array();
	    $used_symbols = '';
	    $pass = '';
	 
	// an array of different character types    
	    $symbols["lower_case"] = 'abcdefghijklmnopqrstuvwxyz';
	    $symbols["upper_case"] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $symbols["numbers"] = '1234567890';
	    $symbols["special_symbols"] = '!?~@#-_+<>[]{}';
	 
	    $characters = explode(",",$characters); // get characters types to be used for the passsword
	    foreach ($characters as $key=>$value) {
	        $used_symbols .= $symbols[$value]; // build a string with all characters
	    }
	    $symbols_length = strlen($used_symbols) - 1; //strlen starts from 0 so to get number of characters deduct 1
	     
	    for ($p = 0; $p < $count; $p++) {
	        $pass = '';
	        for ($i = 0; $i < $length; $i++) {
	            $n = rand(0, $symbols_length); // get a random character from the string with all characters
	            $pass .= $used_symbols[$n]; // add the character to the password string
	        }
	        $passwords[] = $pass;
	    }
	     
	    return $passwords; // return the generated password
	}
}

	if ( ! function_exists('AuthenticateServer'))
	{
		function AuthenticateServer() {

			$server = $_SERVER;
			
			//Server Params
			  if(isset($server['PHP_AUTH_USER'])){ //Check if Request is coming from PHP Server
			    $authuser = $server['PHP_AUTH_USER'];
			    $authpass = $server['PHP_AUTH_PW'];
			  }elseif(isset($server['HTTP_USER'])){ //Check if Request is coming from 3rd Party Tool
			    $authuser = $server['HTTP_USER'];
			    $authpass = $server['HTTP_PW'];
			  }elseif(isset($server['AUTH_USER'])){ //Check if Request is coming from 3rd Party Server
			    $authuser = $server['AUTH_USER'];
			    $authpass = $server['AUTH_PW'];
			  }else{ //If on parameters found
			     return 0;
			  }

			  if ($authuser =='developer@kabhishek18.com' && $authpass =='password987') {
				  $data ['authuser'] =$authuser;
				  $data ['authpass'] =$authpass;

			  return $data;
			}
		}	

	}


	if ( !function_exists('MessageAlertStatus')) {
		

		function MessageAlertStatus($success,$status,$message,$extra = []){
				  return array_merge([
				        'success' => $success,
				        'status' => $status,
				        'message' => $message
				    ],$extra);
		}
	}

?>