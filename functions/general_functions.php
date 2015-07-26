<?php

	/* set time zone to UTC since it is the standard time zone used by Imonggo */
	date_default_timezone_set('UTC');

	
	function get_imonggo_token($url){
		$http_header = array(
			'Content-Type: application/xml; charset=UTF-8',
			'Accept: application/xml'
		);
		
		$options = array(
			CURLOPT_RETURNTRANSFER => true,   // Will return the response, if false it print the response
			CURLOPT_HEADER         => false,  // don't return headers
			CURLOPT_FOLLOWLOCATION => true,   // follow redirects
			CURLOPT_FAILONERROR	   => 1,
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
		
		if($result != null)
			return $result->api_token;
		else
			return null;
	}
	
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
			CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
			CURLOPT_ENCODING       => "",     // handle compressed
			CURLOPT_USERAGENT      => "camae", // name of client
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
		$result  = simplexml_load_string(curl_exec($ch));
		/*
		echo $result;
		echo htmlentities($result);
		print_r($result);
		echo
		*/
		//$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		return $result;
		
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
	
	
	/****************************************** PULL FROM IMONGGO ******************************************/
	function pull_image($url, $username, $password){
		
		$options = array(
			CURLOPT_HTTPAUTH  	   => CURLAUTH_BASIC,
			CURLOPT_USERPWD		   => $username . ":" . $password,
			CURLOPT_RETURNTRANSFER => true,   // Will return the response, if false it print the response
			CURLOPT_HEADER         => false,  // don't return headers
			CURLOPT_FOLLOWLOCATION => true,   // follow redirects
			CURLOPT_FAILONERROR	   => 1,
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
		$content = file_get_contents($ch);
		$data = file_get_contents($url, false, $context);
		curl_close($ch);
		echo $content;
		return $content;
	
	}
	
	
	/******************************************* POST TO 3DCART ********************************************/
	function post_to_3dcart($url, $http_header, $xml){
	
		$options = array(
			CURLOPT_RETURNTRANSFER => true,   // Will return the response, if false it print the response
			CURLOPT_FOLLOWLOCATION => true,   // follow redirects
			CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
			CURLOPT_ENCODING       => "",     // handle compressed
			CURLOPT_USERAGENT      => "camae", // name of client
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
		
		return $result;
		
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
			CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
			CURLOPT_TIMEOUT        => 120,    // time-out on response
			CURLOPT_SSL_VERIFYPEER => false	  // Disable SSL verification
		);
		
		$ch = curl_init($url);
		curl_setopt_array($ch, $options);
		$result = simplexml_load_string(curl_exec($ch));
		curl_close($ch);
		
		return $result;
		
	}
	
	
	/******************************************* PUT TO 3DCART ********************************************/
	function put_to_3dcart($url, $http_header, $xml){
	
		$options = array(
			CURLOPT_RETURNTRANSFER => true,   // Will return the response, if false it print the response
			CURLOPT_FOLLOWLOCATION => true,   // follow redirects
			CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
			CURLOPT_ENCODING       => "",     // handle compressed
			CURLOPT_USERAGENT      => "camae", // name of client
			CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
			CURLOPT_TIMEOUT        => 120,    // time-out on response
			CURLOPT_SSL_VERIFYPEER => false,	  // Disable SSL verification
			CURLOPT_CUSTOMREQUEST  => "PUT",
			CURLOPT_HTTPHEADER	   => $http_header,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS	   => $xml
		);
		
		$ch = curl_init($url);
		curl_setopt_array($ch, $options);
		$result  = curl_exec($ch);
		curl_close($ch);
		
		return $result;
		
	}
	/****************************************** DELETE IN 3DCART *******************************************/
	function delete_in_3dcart($url, $http_header){
	
		$options = array(
			CURLOPT_RETURNTRANSFER => true,   // Will return the response, if false it print the response
			CURLOPT_HTTPHEADER	   => $http_header,
			CURLOPT_FOLLOWLOCATION => true,   // follow redirects
			CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
			CURLOPT_ENCODING       => "",     // handle compressed
			CURLOPT_USERAGENT      => "camae", // name of client
			CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
			CURLOPT_TIMEOUT        => 120,    // time-out on response
			CURLOPT_SSL_VERIFYPEER => false,  // Disable SSL verification
			CURLOPT_CUSTOMREQUEST  => 'DELETE'
		);
		
		$ch = curl_init($url);
		curl_setopt_array($ch, $options);
		$result = curl_exec($ch);
		curl_close($ch);
		
		return $result;
		
	}
	
	
	/****************************************** DELETE IN IMONGGO *******************************************/
	function delete_in_imonggo($url, $username, $password){
		$http_header = array(
			'Content-Type: application/xml; charset=UTF-8',
			'Accept: application/xml'
		);
	
		$options = array(
			CURLOPT_HTTPAUTH  	   => CURLAUTH_BASIC,
			CURLOPT_USERPWD		   => $username . ":" . $password,
			CURLOPT_RETURNTRANSFER => true,   // Will return the response, if false it print the response
			CURLOPT_FOLLOWLOCATION => true,   // follow redirects
			CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
			CURLOPT_ENCODING       => "",     // handle compressed
			CURLOPT_USERAGENT      => "camae", // name of client
			CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
			CURLOPT_TIMEOUT        => 120,    // time-out on response
			CURLOPT_SSL_VERIFYPEER => false,	  // Disable SSL verification
			CURLOPT_HTTPHEADER	   => $http_header,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CUSTOMREQUEST  => 'DELETE'
		);
		
		$ch = curl_init($url);
		curl_setopt_array($ch, $options);
		$result  = curl_exec($ch);
		print_r($result);
		$result;
		return $result;
		
	}
?>