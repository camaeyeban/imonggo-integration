<?php
	
	include("config_db.php");
	include("functions/general_functions.php");
	include("authentication.php");
	
	
	/************************************* PULL FROM IMONGGO *************************************/
	if (isset($_GET['pull_products'])){
		include("functions/products_functions.php");
		$tag = null;
		pull_imonggo_products($tag, $imonggo_username, $imonggo_password, $imonggo_domain);
	}
	
	else if (isset($_GET['pull_products_available_online'])){
		include("functions/products_functions.php");
		$tag = "available_online";
		pull_imonggo_products($tag, $imonggo_username, $imonggo_password, $imonggo_domain);
	}
	
	else if (isset($_GET['pull_customers'])){
		include("functions/customers_functions.php");
		pull_imonggo_customers($imonggo_username, $imonggo_password, $imonggo_domain);
	}
	
	else if (isset($_GET['update_inventory'])){
		include("functions/inventories_functions.php");
		pull_imonggo_inventories($imonggo_username, $imonggo_password, $imonggo_domain);
	}
	
	
	/************************************** POST TO IMONGGO **************************************/
	if (isset($_POST['post_products'])){
		include("functions/products_functions.php");
		push_products_to_imonggo($imonggo_username, $imonggo_password, $imonggo_domain);
	}
	
	else if (isset($_POST['post_customers'])){
		include("functions/customers_functions.php");
		push_customers_to_imonggo($imonggo_username, $imonggo_password, $imonggo_domain);
	}
	
	else if (isset($_POST['post_invoices'])){
		include("functions/invoices_functions.php");
		push_invoices_to_imonggo($imonggo_username, $imonggo_password, $imonggo_domain);
	}
	
	
	/************************************** POST TO 3DCART ***************************************/
	if (isset($_POST['post_products_to_3dcart'])){
		include("functions/products_functions.php");
		push_products_to_3dcart($host, $version, $http_header);
	}
	
	else if (isset($_POST['post_customers_to_3dcart'])){
		include("functions/customers_functions.php");
		push_customers_to_3dcart($host, $version, $http_header);
	}
	
	
	/************************************* PULL FROM 3DCART **************************************/
	if (isset($_GET['pull_product_from_3dcart'])){
		include("functions/products_functions.php");
		pull_product_from_3dcart($host, $version, $http_header);
	}
	else if (isset($_GET['pull_products_from_3dcart'])){
		include("functions/products_functions.php");
		pull_products_from_3dcart($host, $version, $http_header);
	}
	
	else if (isset($_GET['pull_invoices_from_3dcart'])){
		include("functions/invoices_functions.php");
		pull_invoices_from_3dcart($host, $version, $http_header);
	}
	
	/******************************** IMONGGO-3DCART INTEGRATION *********************************/
	if (isset($_GET['update_products'])){
		include("functions/products_functions.php");
		$tag = "available_online";
		update_products($tag, $imonggo_username, $imonggo_password, $imonggo_domain, $host, $version, $http_header);
	}
	
	else if (isset($_GET['update_customers'])){
		include("functions/customers_functions.php");
		update_customers($imonggo_username, $imonggo_password, $imonggo_domain, $host, $version, $http_header);
	}
	
?>


<html lang="en">

	<head>
		<title>3DCART-IMONGGO</title>
	</head>
	
	<body>
		<form method="GET">
			<h2>Pull from Imonggo</h2>
			
			<button type="submit" name="pull_products">Pull Products from Imonggo</button>
			<button type="submit" name="pull_products_available_online">Pull Tagged (available_online) Products from Imonggo</button>
			<button type="submit" name="pull_customers">Pull Customer from Imonggo</button>
			<button type="submit" name="update_inventory">Pull Inventory Levels of Imonggo</button>

		</form>
		<form method="POST">
			<h2>Post to Imonggo</h2>
			
			<button type="submit" name="post_products">Post Products to Imonggo</button>
			<button type="submit" name="post_customers">Post Customers to Imonggo</button>
			<button type="submit" name="post_invoices">Post Invoices to Imonggo</button>
			
		</form>
		<form method="POST">
			<h2>Post to 3dCart</h2>
			
			<button type="submit" name="post_products_to_3dcart">Post Products to 3dCart</button>
			<button type="submit" name="post_customers_to_3dcart">Post Customers to 3dCart</button>
			
		</form>
		<form method="GET">
			<h2>Pull from 3dCart</h2>
			
			<button type="submit" name="pull_product_from_3dcart">Pull A Product from 3dCart</button>
			
			<button type="submit" name="pull_products_from_3dcart">Pull Products from 3dCart</button>
			<button type="submit" name="pull_invoices_from_3dcart">Pull Invoices from 3dCart</button>
			
		</form>
		
		<form method="GET">
			<h2>Imonggo-3dcart Integration</h2>
			
			<button type="submit" name="update_products">Pull Products from Imonggo then push these to 3dcart</button>
			<button type="submit" name="update_customers">Pull Customers from Imonggo then push these to 3dcart</button>
		</form>
	</body>
	
</html>