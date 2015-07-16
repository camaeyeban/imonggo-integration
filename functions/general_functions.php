<?php

	date_default_timezone_set('UTC');

	
	/******************************************* POST TO IMONGGO *******************************************/
	function post_to_imonggo($url, $username, $password, $xml){
		$http_header = array(
			'Content-Type: application/xml; charset=UTF-8',
			'Accept: application/xml'
		);
	
		$options = array(
			CURLOPT_HTTPAUTH  	   => CURLAUTH_BASIC,
			CURLOPT_USERPWD		   => $username . ":" . $password,
			CURLOPT_RETURNTRANSFER => true,   // Will return the response, if false it print the response
			CURLOPT_FOLLOWLOCATION => true,   // follow redirects
			CURLOPT_MAXREDIRS      => 20,     // stop after 10 redirects
			CURLOPT_ENCODING       => "",     // handle compressed
			CURLOPT_USERAGENT      => "test", // name of client
			CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
			CURLOPT_CONNECTTIMEOUT => 520,    // time-out on connect
			CURLOPT_TIMEOUT        => 520,    // time-out on response
			CURLOPT_SSL_VERIFYPEER => false,	  // Disable SSL verification
			CURLOPT_POST		   => true,
			CURLOPT_HTTPHEADER	   => $http_header,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS	   => $xml
		);
		
		$ch = curl_init($url);
		curl_setopt_array($ch, $options);
		
		$result  = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		curl_close($ch);
		
		if($http_code == 422){
			echo "An un-processable entity was encountered (http status code: 422). A validation error occurred.<br>";
			echo "Description: " . $result;
		}
		else if($http_code == 500){
			echo "An internal server error occured (http status code: 500). An unexpected error occurred.<br>";
			echo "Description: " . $result . "<br>";
		}
		
		return $http_code;
	}
	
	
	/****************************************** PULL FROM IMONGGO ******************************************/
	function pull_from_imonggo($url, $username, $password){
		
		$options = array(
			CURLOPT_HTTPAUTH  	   => CURLAUTH_BASIC,
			CURLOPT_USERPWD		   => $username . ":" . $password,
			CURLOPT_RETURNTRANSFER => true,   // Will return the response, if false it print the response
			CURLOPT_HEADER         => false,  // don't return headers
			CURLOPT_FOLLOWLOCATION => true,   // follow redirects
			CURLOPT_FAILONERROR	   => 1,
			CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
			CURLOPT_ENCODING       => "",     // handle compressed
			CURLOPT_USERAGENT      => "test", // name of client
			CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
			CURLOPT_CONNECTTIMEOUT => 520,    // time-out on connect
			CURLOPT_TIMEOUT        => 520,    // time-out on response
			CURLOPT_SSL_VERIFYPEER => false	  // Disable SSL verification
		);
		
		$ch = curl_init($url);
		curl_setopt_array($ch, $options);
		$result = simplexml_load_string(curl_exec($ch));
		curl_close($ch);
		
		return $result;
	
	}
	
	
	/******************************************* POST TO 3DCART ********************************************/
	function post_to_3dcart($url, $http_header, $xml){
	
		$options = array(
			CURLOPT_RETURNTRANSFER => true,   // Will return the response, if false it print the response
			CURLOPT_FOLLOWLOCATION => true,   // follow redirects
			CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
			CURLOPT_ENCODING       => "",     // handle compressed
			CURLOPT_USERAGENT      => "test", // name of client
			CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
			CURLOPT_TIMEOUT        => 120,    // time-out on response
			CURLOPT_SSL_VERIFYPEER => false,	  // Disable SSL verification
			CURLOPT_POST		   => true,
			CURLOPT_HTTPHEADER	   => $http_header,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS	   => $xml
		);
		
		$ch = curl_init($url);
		curl_setopt_array($ch, $options);
		$result  = curl_exec($ch);
		curl_close($ch);
		
		echo $result;
		
	}
	
	
	/****************************************** PULL FROM 3DCART *******************************************/
	function pull_from_3dcart($url, $http_header){
	
		$options = array(
			CURLOPT_RETURNTRANSFER => true,   // Will return the response, if false it print the response
			CURLOPT_HTTPHEADER	   => $http_header,
			CURLOPT_FOLLOWLOCATION => true,   // follow redirects
			CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
			CURLOPT_ENCODING       => "",     // handle compressed
			CURLOPT_USERAGENT      => "camae", // name of client
			CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
			CURLOPT_CONNECTTIMEOUT => 520,    // time-out on connect
			CURLOPT_TIMEOUT        => 520,    // time-out on response
			CURLOPT_SSL_VERIFYPEER => false	  // Disable SSL verification
		);
		
		$ch = curl_init($url);
		curl_setopt_array($ch, $options);
		$result = simplexml_load_string(curl_exec($ch));
		curl_close($ch);
		
		return $result;
		
	}
	
?>