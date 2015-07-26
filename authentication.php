<?php
	
	/*************************** IMONGGO AUTHENTICATION ***************************/
	$imonggo_username = $_SESSION['token'];
	$imonggo_password = $_SESSION['password'];
	$imonggo_account_id = $_SESSION['account_id'];
	
	
	/*************************** 3DCART  AUTHENTICATION ***************************/
	$host = $_SESSION['host'];
	$version = 1;
	$secure_url = $_SESSION['secure_url'];
	$private_key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; // Private key is obtained when registering your app at http://devportal.3dcart.com
	$token = $_SESSION['token'];
	$http_header = array(
		'Content-Type: application/xml; charset=UTF-8',
		'Accept: application/xml',
		'SecureUrl: ' . $secure_url,
		'PrivateKey: ' . $private_key,
		'Token: ' . $token
	);
	
?>