<?php
	
	/*************************** IMONGGO AUTHENTICATION ***************************/
	
	/*
		Replace the text insert_api_token_here below with your API token.
		Your API token can be found by logging into your Imonggo account,
		clicking on the "My Info" link in the upper-right,
		and then clicking the "show" under "API token". 
	*/
	$imonggo_username = 'insert_api_token_here';
	$imonggo_password = 'X';
	/*
		Replace the text insert_account_id_here below with your account ID.
		Your account id is the one you use in logging in your imonggo account.
	*/
	$imonggo_account_id = 'insert_account_id_here';
	
	
	/*************************** 3DCART  AUTHENTICATION ***************************/
	$host = 'https://apirest.3dcart.com';
	$version = 1;
	
	/*
		Replace the text insert_secure_url_here below with your Secure URL.
		Secure URL is set in Settings->General->StoreSettings
	*/
	$secure_url = 'insert_secure_url_here';
	
	/*
		Replace the text insert_private_key_here below with your Secure URL.
		Private key is obtained when registering your app at http://devportal.3dcart.com
	*/
	$private_key = 'insert_private_key_here';
	
	/*
		Replace the text insert_token_here below with your Secure URL.
		The token is generated when a customer authorizes your app
	*/
	$token = 'insert_token_here';
	$http_header = array(
		'Content-Type: application/xml; charset=UTF-8',
		'Accept: application/xml',
		'SecureUrl: ' . $secure_url,
		'PrivateKey: ' . $private_key,
		'Token: ' . $token
	);
	
?>