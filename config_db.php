<?php
	
	/*************** CONFIGURATION FOR DATABASE CONNECTION ***************/
	
	/*
		'localhost' : database provider/host/location
		'root' : database username
		'' : database password (there's currently no database password)
	*/
	$conn = mysql_connect('localhost','root','');
	
	/*
		'imonggo_integration_db': database name
		$conn : database connection parameters
	*/
	$db = mysql_select_db('imonggo_integration_db', $conn);
	
?>