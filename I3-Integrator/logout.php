<?php

	/*
		File name : logout.php
		Description :
			This file contains the code for logging out.
	*/
	
	/* Unset and destroy the initialized session then redirect the user to the login page (index.php) */
	session_start();
	session_unset();
	session_destroy();
	header("location: index.php");
	
?>