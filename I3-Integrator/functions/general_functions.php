<?php

	/*
		File name: general_functions.php
		Description:
			This file contains all general functions used in pulling and posting to both Imonggo and 3dCart APIs.
	*/
	
	
	
	/* set time zone to UTC since it is the standard time zone used by Imonggo */
	date_default_timezone_set('UTC');

	
	
	/*
		function name : get_imonggo_token
		description : this function returns the user's Imonggo API key
		parameter :
			$url : user's Imonggo token API URL
	*/
	function get_imonggo_token($url){
		
		/* $http_header : an array containing the headers that will be passed along with the request */
		$http_header = array(
			'Content-Type: application/xml; charset=UTF-8',
			'Accept: application/xml'
		);
		
		/* initialize a cURL session options */
		$options = array(
			CURLOPT_RETURNTRANSFER => true,		// Will return the response, if false it print the response
			CURLOPT_HEADER         => false,	// don't return headers
			CURLOPT_FOLLOWLOCATION => true,		// follow redirects
			CURLOPT_FAILONERROR	   => 1,
			CURLOPT_MAXREDIRS      => 10,		// stop after 10 redirects
			CURLOPT_ENCODING       => "",		// handle compressed
			CURLOPT_USERAGENT      => "camae",	// name of client
			CURLOPT_AUTOREFERER    => true,		// set referrer on redirect
			CURLOPT_CONNECTTIMEOUT => 520,		// time-out on connect
			CURLOPT_TIMEOUT        => 520,		// time-out on response
			CURLOPT_SSL_VERIFYPEER => false		// Disable SSL verification
		);
		
		$ch = curl_init($url);								/* cURL session initialization; takes a string URL as its parameter */
		curl_setopt_array($ch, $options);					/* Set multiple options for a cURL transfer */
		$result = simplexml_load_string(curl_exec($ch));	/* convert the string result of performing a cURL session to a SimpleXMLElement object */
		curl_close($ch);									/* close a cURL session */
		
		/* return the user's Imonggo api key if the given credentials are valid */
		if($result != null)
			return $result->api_token;
		
		/* return null if the given credentials are invalid */
		else
			return null;
		
	}
	
	
	
	/*
		function name : post_to_imonggo
		description : this function posts the service (product, customer, invoice) to the user's Imonggo store
		parameters :
			$url : user's Imonggo service API URL
			$username : user's Imonggo API key
			$password : user's Imonggo password
			$xml : the XML to be posted to the user's service API
	*/
	function post_to_imonggo($url, $username, $password, $xml){
		
		/* $http_header : an array containing the headers that will be passed along with the request */
		$http_header = array(
			'Content-Type: application/xml; charset=UTF-8',
			'Accept: application/xml'
		);
	
		/* initialize a cURL session options */
		$options = array(
			CURLOPT_HTTPAUTH  	   => CURLAUTH_BASIC,
			CURLOPT_USERPWD		   => $username . ":" . $password,
			CURLOPT_RETURNTRANSFER => true,		// Will return the response, if false it print the response
			CURLOPT_FOLLOWLOCATION => true,		// follow redirects
			CURLOPT_MAXREDIRS      => 10,		// stop after 10 redirects
			CURLOPT_ENCODING       => "",		// handle compressed
			CURLOPT_USERAGENT      => "camae",	// name of client
			CURLOPT_AUTOREFERER    => true,		// set referrer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,		// time-out on connect
			CURLOPT_TIMEOUT        => 120,		// time-out on response
			CURLOPT_SSL_VERIFYPEER => false,	// Disable SSL verification
			CURLOPT_POST		   => true,
			CURLOPT_HTTPHEADER	   => $http_header,
			CURLOPT_POSTFIELDS	   => $xml
		);
		
		$ch = curl_init($url);								/* cURL session initialization; takes a string URL as its parameter */
		curl_setopt_array($ch, $options);					/* Set multiple options for a cURL transfer */
		$result  = simplexml_load_string(curl_exec($ch));	/* convert the string result of performing a cURL session to a SimpleXMLElement object */
		curl_close($ch);									/* close a cURL session */
		
		return $result;
		
	}
	
	
	/*
		function name : pull_from_imonggo
		description : this function pulls the service (product, customer, invoice, inventory levels) from the user's Imonggo store
		parameters :
			$url : user's Imonggo service API URL
			$username : user's Imonggo API key
			$password : user's Imonggo password
	*/
	function pull_from_imonggo($url, $username, $password){
		
		/* initialize a cURL session options */
		$options = array(
			CURLOPT_HTTPAUTH  	   => CURLAUTH_BASIC,
			CURLOPT_USERPWD		   => $username . ":" . $password,
			CURLOPT_RETURNTRANSFER => true,		// Will return the response, if false it print the response
			CURLOPT_HEADER         => false,	// don't return headers
			CURLOPT_FOLLOWLOCATION => true,		// follow redirects
			CURLOPT_FAILONERROR	   => 1,
			CURLOPT_MAXREDIRS      => 10,		// stop after 10 redirects
			CURLOPT_ENCODING       => "",		// handle compressed
			CURLOPT_USERAGENT      => "camae",	// name of client
			CURLOPT_AUTOREFERER    => true,		// set referrer on redirect
			CURLOPT_CONNECTTIMEOUT => 520,		// time-out on connect
			CURLOPT_TIMEOUT        => 520,		// time-out on response
			CURLOPT_SSL_VERIFYPEER => false		// Disable SSL verification
		);
		
		$ch = curl_init($url);								/* cURL session initialization; takes a string URL as its parameter */
		curl_setopt_array($ch, $options);					/* Set multiple options for a cURL transfer */
		$result = simplexml_load_string(curl_exec($ch));	/* convert the string result of performing a cURL session to a SimpleXMLElement object */
		curl_close($ch);									/* close a cURL session */
		
		return $result;
	
	}
	
	
	/*
		function name : post_to_3dcart
		description : this function posts the service (product, customer, invoice) from the user's Imonggo store
		parameters :
			$url : user's 3dCart service API URL
			$http_header : contains the user's 3dCart credentials supplied in authentication.php
			$xml : SimpleXMLElement object to be posted to the user's 3dCart store
	*/
	function post_to_3dcart($url, $http_header, $xml){
		
		/* initialize a cURL session options */
		$options = array(
			CURLOPT_RETURNTRANSFER => true,			// Will return the response, if false it print the response
			CURLOPT_FOLLOWLOCATION => true,			// follow redirects
			CURLOPT_MAXREDIRS      => 10,			// stop after 10 redirects
			CURLOPT_ENCODING       => "",			// handle compressed
			CURLOPT_USERAGENT      => "camae",		// name of client
			CURLOPT_AUTOREFERER    => true,			// set referrer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,			// time-out on connect
			CURLOPT_TIMEOUT        => 120,			// time-out on response
			CURLOPT_SSL_VERIFYPEER => false,		// Disable SSL verification
			CURLOPT_POST		   => true,
			CURLOPT_HTTPHEADER	   => $http_header,
			CURLOPT_CUSTOMREQUEST  => 'POST',
			CURLOPT_POSTFIELDS	   => $xml
		);
		
		$ch = curl_init($url);				/* cURL session initialization; takes a string URL as its parameter */
		curl_setopt_array($ch, $options);	/* Set multiple options for a cURL transfer */
		$result  = curl_exec($ch);			/* perform a cURL session */
		curl_close($ch);					/* close a cURL session */
		
		return $result;
		
	}
	
	
	/*
		function name : pull_from_3dcart
		description : this function pulls the service (product, customer, invoice) from the user's 3dCart store
		parameters :
			$url : user's 3dCart service API URL
			$http_header : contains the user's 3dCart credentials supplied in authentication.php
	*/
	function pull_from_3dcart($url, $http_header){
	
		/* initialize a cURL session options */
		$options = array(
			CURLOPT_RETURNTRANSFER => true,			// Will return the response, if false it print the response
			CURLOPT_HTTPHEADER	   => $http_header,
			CURLOPT_FOLLOWLOCATION => true,			// follow redirects
			CURLOPT_MAXREDIRS      => 10,			// stop after 10 redirects
			CURLOPT_ENCODING       => "",			// handle compressed
			CURLOPT_USERAGENT      => "camae",		// name of client
			CURLOPT_AUTOREFERER    => true,			// set referrer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,			// time-out on connect
			CURLOPT_TIMEOUT        => 120,			// time-out on response
			CURLOPT_SSL_VERIFYPEER => false			// Disable SSL verification
		);
		
		$ch = curl_init($url);								/* cURL session initialization; takes a string URL as its parameter */
		curl_setopt_array($ch, $options);					/* Set multiple options for a cURL transfer */
		$result = simplexml_load_string(curl_exec($ch));	/* convert the string result of performing a cURL session to a SimpleXMLElement object */
		curl_close($ch);									/* close a cURL session */
		
		return $result;
		
	}
	
	
	
	/*
		function name : put_to_3dcart
		description : this function updates the service (product, customer, invoice) in the user's 3dCart store
		parameters :
			$url : user's 3dCart service API URL
			$http_header : contains the user's 3dCart credentials supplied in authentication.php
	*/
	function put_to_3dcart($url, $http_header, $xml){
	
		/* initialize a cURL session options */
		$options = array(
			CURLOPT_RETURNTRANSFER => true,			// Will return the response, if false it print the response
			CURLOPT_FOLLOWLOCATION => true,			// follow redirects
			CURLOPT_MAXREDIRS      => 10,			// stop after 10 redirects
			CURLOPT_ENCODING       => "",			// handle compressed
			CURLOPT_USERAGENT      => "camae",		// name of client
			CURLOPT_AUTOREFERER    => true,			// set referrer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,			// time-out on connect
			CURLOPT_TIMEOUT        => 120,			// time-out on response
			CURLOPT_SSL_VERIFYPEER => false,		// Disable SSL verification
			CURLOPT_CUSTOMREQUEST  => "PUT",
			CURLOPT_HTTPHEADER	   => $http_header,
			CURLOPT_POSTFIELDS	   => $xml
		);
		
		$ch = curl_init($url);				/* cURL session initialization; takes a string URL as its parameter */
		curl_setopt_array($ch, $options);	/* Set multiple options for a cURL transfer */
		$result  = curl_exec($ch);			/* perform a cURL session */
		curl_close($ch);					/* close a cURL session */
		
		return $result;
		
	}
	
?>