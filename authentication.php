<?php
	    
	$version = 1;
	
	if(isset($_POST['login'])){
		/*************************** 3DCART  AUTHENTICATION ***************************/
		$_SESSION['host'] = 'https://apirest.3dcart.com';
		/* Change the private key below */
		$_SESSION['private_key'] = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';	// Private key is obtained when registering your app at http://devportal.3dcart.com
		$_SESSION['secure_url'] = $_POST['secure_url'];					// Secure URL is set in Settings->General->StoreSettings
		$_SESSION['token'] = $_POST['token'];							// The token is generated when a customer authorizes your app
		$_SESSION['api_version'] = 1;
		
		$_SESSION['http_header'] = array(
			'Content-Type: application/xml; charset=UTF-8',
			'Accept: application/xml',
			'SecureUrl: ' . $_SESSION['secure_url'],
			'PrivateKey: ' . $_SESSION['private_key'],
			'Token: ' . $_SESSION['token']
		);
		
		/*************************** IMONGGO AUTHENTICATION ***************************/
		$_SESSION['imonggo_account_id'] = $_POST['imonggo_account_id'];
		$_SESSION['imonggo_email'] = $_POST['imonggo_email'];
		$_SESSION['imonggo_password'] = $_POST['imonggo_password'];
		
		$imonggo_url = "https://" . $_SESSION['imonggo_account_id'] . ".c3.imonggo.com/api/tokens.xml?email=" . $_SESSION['imonggo_email'] . "&password=" . $_SESSION['imonggo_password'];
		$imonggo_api_key = (string)get_imonggo_token($imonggo_url);
		
		if($imonggo_api_key != null){
			$_SESSION['imonggo_api_key'] = $imonggo_api_key;
			header("location:homepage.php");
		}
		else{
			session_unset();
			session_destroy();	
			header("location:index.php");
		}
		
	}
?>