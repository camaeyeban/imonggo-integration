<?php
	
	include("config_db.php");
	include("functions/general_functions.php");
	include("authentication.php");
	
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
	
	/**************************** IMONGGO INTEGRATION BUTTON FUNCTIONS *****************************/
	if (isset($_POST['update_products'])){
		include("functions/products_functions.php");
		$tags = $_POST['tag0'];
		update_products($tags, $imonggo_username, $imonggo_password, $imonggo_domain, $host, $version, $http_header);
		echo "<script>$('#output_modal').openModal();</script>";
	}
	
	else if (isset($_POST['update_customers'])){
		include("functions/customers_functions.php");
		if(empty($_POST['billing_shipping_choice'])){
			echo "<script type='text/javascript'>alert('Please choose either shipping or billing.')</script>";
		}
		else{
			$choice = $_POST['billing_shipping_choice'];
			update_customers($choice, $imonggo_username, $imonggo_password, $imonggo_domain, $host, $version, $http_header);
		}
		echo "<script>$('#output_modal').openModal();</script>";
	}
	
	else if (isset($_POST['update_invoices'])){
		include("functions/invoices_functions.php");
		update_invoices($choice, $imonggo_username, $imonggo_password, $imonggo_domain, $host, $version, $http_header);
		echo "<script>$('#output_modal').openModal();</script>";
	}
	
?>