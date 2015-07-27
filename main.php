<?php
	
	/* include necessary files */
	include("config_db.php");
	include("functions/general_functions.php");
	
	/**************** FETCH PREVIOUS CHOICE (BILLING / SHIPPING) FROM THE DATABASE ****************/
	$query = "SELECT * FROM adding_customers_option";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);

	$choice = null;
	if($row != null){
		if($row[1] == "Shipping"){
			$choice = "Shipping";
		}
		else{
			$choice = "Billing";
		}
	}
	
	
	/******************************** ADD PRODUCTS BUTTON FUNCTION *********************************/
	if (isset($_POST['update_products'])){
		include("functions/products_functions.php");
		$tags = array();
		
		/* If the user chooses to add tags, update only products that matches at least one of the chosen tags */
		if (isset($_POST['imonggo_tag_list'])){
			$tags = $_POST['imonggo_tag_list'];
		}
		
		/* If "No Tags" checkbox is checked */
		if(isset($_POST['no_tags'])){
			array_push($tags, "");
		}
		
		/* If the user did not choose any tag, update all products */
		if (!isset($_POST['imonggo_tag_list']) and !isset($_POST['no_tags'])){
			$tags = null;
		}
		
		update_products($tags, $_SESSION['imonggo_api_key'], $_SESSION['imonggo_password'], $_SESSION['imonggo_account_id'], $_SESSION['host'], $_SESSION['api_version'], $_SESSION['http_header']);
		echo "<script>$('#output_modal').openModal();</script>";
	}
	
	
	/******************************** POST INVOICES BUTTON FUNCTION *********************************/
	else if (isset($_POST['update_invoices'])){
		include("functions/invoices_functions.php");
		update_invoices($choice, $_SESSION['imonggo_api_key'], $_SESSION['imonggo_password'], $_SESSION['imonggo_account_id'], $_SESSION['host'], $_SESSION['api_version'], $_SESSION['http_header']);
		echo "<script>$('#output_modal').openModal();</script>";
	}
	
	
	/******************************** ADD CUSTOMERS BUTTON FUNCTION *********************************/
	else if (isset($_POST['update_customers'])){
		include("functions/customers_functions.php");
		
		/* If it is the user's first time to use this app, and he/she have chosen neither Billing nor Shipping, prompt her to choose one */
		if(empty($_POST['billing_shipping_choice'])){
			echo "<script type='text/javascript'>alert('Please choose either shipping or billing.')</script>";
			return;
		}
		
		/* If the user have chosen either Billing or Shipping, get customers' information based on his/her choice */
		else{
			$choice = $_POST['billing_shipping_choice'];
			$save_choice = mysql_query("
				INSERT INTO adding_customers_option (choice_id, choice) VALUES(DEFAULT, '$choice') 
			");
			update_customers($choice, $_SESSION['imonggo_api_key'], $_SESSION['imonggo_password'], $_SESSION['imonggo_account_id'], $_SESSION['host'], $_SESSION['api_version'], $_SESSION['http_header']);
		}
		
		echo "<script>$('#output_modal').openModal();</script>";
	}
	
	
?>