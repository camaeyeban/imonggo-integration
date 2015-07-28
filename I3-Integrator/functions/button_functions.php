<?php
	
	/*
		File name: button_functions.php
		Description:
			this file contains the actions done by each user interface buttons of the homepage
	*/
	
	
	/* include necessary files */
	include("config_db.php");
	include("functions/general_functions.php");
	
	/**************** FETCH PREVIOUS CHOICE (BILLING / SHIPPING) FROM THE DATABASE ****************/
	
	/* query from the database to get the user's previous choice */
	$query = "SELECT * FROM adding_customers_option";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);

	$choice = null;
	
	/* if the user have previously chosen from the options */
	if($row != null){
		/* if the user's previous choice is "Shipping" */
		if($row[1] == "Shipping"){
			$choice = "Shipping";
		}
		/* if the user's previous choice is "Billing" */
		else{
			$choice = "Billing";
		}
	}
	
	
	/******************************** BUTTON FUNCTION FOR UPDATE PRODUCTS *********************************/
	if (isset($_POST['update_products'])){
		include("functions/products_functions.php");
		
		/*
			$tags : an array of strings that will contain all the tags checked by the user
				  : will contain null if no filter was added (default: all products will be updated)
		*/
		$tags = array();
		
		/* If the user checked at least one tag, add it to $tags array */
		if (isset($_POST['imonggo_tag_list'])){
			$tags = $_POST['imonggo_tag_list'];
		}
		
		/* If "No Tags" checkbox is checked, add it to the $tags array */
		if(isset($_POST['no_tags'])){
			array_push($tags, "");
		}
		
		/* If the user did not choose any tag, update all products */
		if (!isset($_POST['imonggo_tag_list']) and !isset($_POST['no_tags'])){
			$tags = null;
		}
		
		/*
			update_products : execute product updating
			Note: function and parameter descriptions are in "functions/products_functions.php"
		*/
		update_products($tags, $_SESSION['imonggo_api_key'], $_SESSION['imonggo_password'], $_SESSION['imonggo_account_id'], $_SESSION['host'], $_SESSION['api_version'], $_SESSION['http_header']);
		
		/* after updating the product, open a modal to show the function output */
		echo "<script>$('#output_modal').openModal();</script>";
	}
	
	
	/****************************** BUTTON FUNCTION FOR POST INVOICES *******************************/
	else if (isset($_POST['update_invoices'])){
		include("functions/invoices_functions.php");
		
		/*
			update_invoices : execute invoice updating
			Note: function and parameter descriptions are in "functions/invoices_functions.php"
		*/
		update_invoices($_SESSION['imonggo_api_key'], $_SESSION['imonggo_password'], $_SESSION['imonggo_account_id'], $_SESSION['host'], $_SESSION['api_version'], $_SESSION['http_header']);
		
		/* after updating the invoices, open a modal to show the function output */
		echo "<script>$('#output_modal').openModal();</script>";
	}
	
	
	/****************************** BUTTON FUNCTION FOR ADD CUSTOMERS ********************************/
	else if (isset($_POST['update_customers'])){
		include("functions/customers_functions.php");
		
		/*
			If it is the user's first time to use this app, and he/she have chosen neither Billing nor Shipping,
			prompt her to choose one and do not continue customer updating
		*/
		if(empty($_POST['billing_shipping_choice'])){
			echo "<script type='text/javascript'>alert('Please choose either shipping or billing.')</script>";
			return;
		}
		
		/*
			If the user have chosen either Billing or Shipping,
			get customers' information based on his/her choice
		*/
		else{
			$choice = $_POST['billing_shipping_choice'];
			
			/* if no choice had been saved to database yet, insert the user's choice to the database */
			if(!$row){
				$save_choice = mysql_query("
					INSERT INTO adding_customers_option (choice_id, choice) VALUES(DEFAULT, '$choice') 
				");
			}
			
			/* if choice had been previously saved to database, just update the user's previous choice in the database */
			else{
				$update_choice = mysql_query("
					UPDATE adding_customers_option SET choice = '$choice' WHERE choice_id = '$row[0]' 
				");
			}
			update_customers($choice, $_SESSION['imonggo_api_key'], $_SESSION['imonggo_password'], $_SESSION['imonggo_account_id'], $_SESSION['host'], $_SESSION['api_version'], $_SESSION['http_header']);
		}
		
		/* after updating the customers, open a modal to show the function output */
		echo "<script>$('#output_modal').openModal();</script>";
	}
	
	
?>