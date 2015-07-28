<?php
	
	/*
		File name : authentication.php
		Description :
			This file contains the function(s) for initializing a session or/and global variables with user's credentials.
		Note: Change the session's private key
	*/
	
	
	/* if the "LOGIN" button in the login page (index.php) was clicked */
	if(isset($_POST['login'])){
		
		/*************************** 3DCART  AUTHENTICATION ***************************/
		$_SESSION['host'] = 'https://apirest.3dcart.com';				// 3dCart API host
		$_SESSION['private_key'] = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';	// Private key is obtained when registering an app at http://devportal.3dcart.com
		$_SESSION['secure_url'] = $_POST['secure_url'];					// Secure URL is set in Settings->General->StoreSettings
		$_SESSION['token'] = $_POST['token'];							// The token is generated when a user authorizes an app
		$_SESSION['api_version'] = 1;									// 3dCart REST API version
		
		$_SESSION['http_header'] = array(								// header passed in each request to 3dCart REST API
			'Content-Type: application/xml; charset=UTF-8',
			'Accept: application/xml',
			'SecureUrl: ' . $_SESSION['secure_url'],
			'PrivateKey: ' . $_SESSION['private_key'],
			'Token: ' . $_SESSION['token']
		);
		
		
		/*************************** IMONGGO AUTHENTICATION ***************************/
		$_SESSION['imonggo_account_id'] = $_POST['imonggo_account_id'];	// user's account id used in logging in to his/her Imonggo account
		$_SESSION['imonggo_email'] = $_POST['imonggo_email'];			// user's email address used in logging in to his/her Imonggo account
		$_SESSION['imonggo_password'] = $_POST['imonggo_password'];		// user's password used in logging in to his/her Imonggo account
		
		/* set the URL to be used in getting the user's Imonggo API key */
		$imonggo_url = "https://" . $_SESSION['imonggo_account_id'] . ".c3.imonggo.com/api/tokens.xml?email=" . $_SESSION['imonggo_email'] . "&password=" . $_SESSION['imonggo_password'];
		
		/* get the user's Imonggo API key */
		$imonggo_api_key = (string)get_imonggo_token($imonggo_url);
		
		/*
			if the user's login credentials were valid,
			save his/her Imonggo API key in the session then redirect the user to homepage.php
		*/
		if($imonggo_api_key != null){
			$_SESSION['imonggo_api_key'] = $imonggo_api_key;
			header("location:homepage.php");
		}
		
		/*
			if the user's login credentials were invalid or if something went wrong while fetching the user's Imonggo API key',
			start all over (unset and destroy initialized session and refresh the login page or index.php)
		*/
		else{
			session_unset();
			session_destroy();	
			header("location:index.php");
		}
		
	}
?>