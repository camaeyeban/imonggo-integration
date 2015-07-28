<?php
	
	/*
		File name: config_db.php
		Description:
			This file contains the configuration for database connection
	*/
	
	
	/*
		'localhost' : database server
		'root' : database username
		'' : database password (there's currently no database password)
		$link_identifier : contains a MySQL link identifier on success or FALSE on failure
	*/
	$link_identifier = mysql_connect('localhost','root','');
	
	
	/*
		'imonggo_integration_db': database name
		$link_identifier : MySQL link identifier
		$database_connection_response : contains TRUE on success or FALSE on failure
	*/
	$database_connection_response = mysql_select_db('imonggo_integration_db', $link_identifier);
	
?>