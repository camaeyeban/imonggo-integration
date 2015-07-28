<?php

	/*
		File name: invoices_functions.php
		Description:
			This file contains all functions available for the Invoices service.
			Invoice functions use Imonggo as its general source of truth but use 3dCart as its source of information.
			Invoices will be pulled from user's 3dCart store and shall be posted to user's Imonggo store.
	*/
	
	
	/*
		function name: update_invoices
		description : this function pulls all user's 3dCart invoices
					  then pushes the pulled invoices to the user's Imonggo store
		parameters :
			$username : the user's Imonggo API key
			$password : any value since only the username is checked when requesting from Imonggo's API
			$account_id : the user's Imonggo account id
			$host : the user's 3dCart store host
			$version : 3dCart's API version
			$http_header : HTTP header to be included in 3dCart post request
						 : It includes the fields: (1) Content-Type, (2) Accept, (3) SecureUrl, (4) PrivateKey, and (5) Token
	*/
	function update_invoices($username, $password, $account_id, $host, $version, $http_header){
		
		/* $service : will be used in supplying 3dCart's URL */
		$service = 'Orders';
		
		/* get the id and date of the last time the user posted his/her invoices from the database */
		$query = "SELECT * FROM last_invoice_posting";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		
		/* get the current date and time */
		$object_date_time = new DateTime('NOW');
		
		/* convert the the current date time stamp to ISO8601 format */
		$date_time = $object_date_time->format(DateTime::ISO8601);
		
		/*
			if the database query is empty (meaning it's the user's first time to update invoices),
			insert to last_invoice_posting the current datetimestamp
		*/
		if(!$row){
			echo "Invoices posted from the beginning until " . $date_time . "<br>";
			$insert_to_last_posting = mysql_query("
				INSERT INTO last_invoice_posting (id, date) VALUES(DEFAULT, '$date_time') 
			");
			$insert_to_invoices = mysql_query("
				INSERT INTO invoice_posting (post_id, post_date) VALUES (DEFAULT, '$date_time')
			");
		}
		
		/*
			if the database query is not empty, just update last_product_posting to the current datetimestamp
		*/
		else{
			echo "Invoices posted from " . $row['date'] . " to " . $date_time . "<br>";
			$update_last_posting = mysql_query("
				UPDATE last_invoice_posting SET date = '$date_time' WHERE id='$row[0]'
			");
			$insert_to_invoices = mysql_query("
				INSERT INTO invoice_posting (post_id, post_date) VALUES (DEFAULT, '$date_time')
			");
		}
		
		/*
			get the total number of user's 3dCart invoices
			$invoice_count : total number of user's 3dCart invoices
		*/
		$url = $host . '/3dCartWebAPI/v' . $version . '/' . $service . '?countonly=1';
		$invoices_of_3dcart_count = pull_from_3dcart($url, $http_header);
		$invoices_count = $invoices_of_3dcart_count->TotalCount;
		
		/*
			if the user has no invoice in his/her 3dCart store, prompt a message
			and do not proceed to product updating function
		*/
		if($invoices_count == 0){
			echo "<p class='empty'>Your 3dCart Store has no invoice!</p>";
			return;
		}
		
		/* if the number of Imonggo invoices is greater that 0, continue invoice updating */
		/*
			Get all user's 3dCart invoices.
			Use the acquired total number of 3dCart invoices ($invoice_count)
				as parameter in getting all of the user's 3dCart invoices.
		*/
		$url = $host . '/3dCartWebAPI/v' . $version . '/' . $service . '?limit=' . $invoices_count;
		$invoices_of_3dcart = pull_from_3dcart($url, $http_header);
		
		/* Traverse all invoices in 3dCart */
		foreach ($invoices_of_3dcart as $invoice_of_3dcart){
			/* save invoice xml fields to their corresponding variables */
			$order_id = $invoice_of_3dcart->OrderID;
			$invoice_number = $invoice_of_3dcart->InvoiceNumberPrefix . $invoice_of_3dcart->InvoiceNumber;
			$status_id = $invoice_of_3dcart->OrderStatusID;
			
			/* if the invoice was ordered after the last time the user updated invoices */
			if ($invoice_of_3dcart->OrderDate > $row['date']){
				
				/* by default, an invoice has been shipped if its status id is equal to 4 */
				if($status_id == 4){
					/* save invoice details to their corresponding variables */
					$customer_id = $invoice_of_3dcart->CustomerID;
					$first_name = $invoice_of_3dcart->BillingFirstName;
					$last_name = $invoice_of_3dcart->BillingLastName;
					$payment_method = $invoice_of_3dcart->BillingPaymentMethod;
					$payment_method_id = $invoice_of_3dcart->BillingPaymentMethodID;
					$order_amount = $invoice_of_3dcart->OrderAmount;
					
					/* $name : concatenation of the first name and last name of the customer who ordered */
					$name = $first_name . ' ' . $last_name;
					
					/* $shipment_cost : total shipment cost of the invoice */
					$shipment_cost = 0.0;
					foreach ($invoice_of_3dcart->ShipmentList->Shipment as $invoice_shipment_of_3dcart){
						$shipment_cost = (double)$shipment_cost + (double)$invoice_shipment_of_3dcart->ShipmentCost;
					}
					
					/* $order_amount : the cost of all bought products (exclusive of their shipment cost) */
					$order_amount = (double)$order_amount - (double)$shipment_cost;
					
					/*
						Create the xml to be used in posting an invoice in Imonggo.
						Use the variables which were previously initialized using 3dCart invoice information.
						$xml : xml used to post the invoice to Imonggo
					*/
					$xml = 
						'<?xml version="1.0" encoding="UTF-8"?>
						<invoice>
							<reference>' . $order_id . '</reference>
							<customer_name nil="true">' . $name . '</customer_name>
							<customer_id nil="true">' . $customer_id . '</customer_id>
							<payments type="array">
								<payment>
									<amount>' . $order_amount . '</amount>
									<payment_type_id>' . $payment_method_id . '</payment_type_id>
								</payment>
							</payments>
							<invoice_tax_rates type="array"/>
							<invoice_lines type="array">';
							
					/* traverse each product which were ordered in the invoice currently being traversed */
					foreach ($invoice_of_3dcart->OrderItemList->OrderItem as $invoice_order_item_of_3dcart){
						/* save details of ordered product to their corresponding variables */
						$catalog_id = $invoice_order_item_of_3dcart->CatalogID;
						$item_quantity = $invoice_order_item_of_3dcart->ItemQuantity;
						$item_unit_price = $invoice_order_item_of_3dcart->ItemUnitPrice;
						
						/* check if there are no mapped product in the database */
						$query = "SELECT * FROM products";
						$result = mysql_query($query);
						$row = mysql_fetch_array($result);
						
						/*
							if the database has no product, alert the user to update products before updating invoices,
							then do not continue invoice updating
						*/
						if(!$row){
							echo "<script> alert('Please update Products before updating Invoices.'); </script>";
							return;
						}
						
						/* if the database has products */
						else{
							/* get the product's Imonggo ID using database's product ID mapping */
							$query = "SELECT id_imonggo FROM products where id_3dcart='$catalog_id'";
							$result = mysql_query($query);
							$row = mysql_fetch_array($result);
							
							/* if the 3dCart product found its Imonggo match, get the product's Imonggo ID */
							if($row[0] != null){
								$catalog_id = $row[0];
							}
						}
						
						/* supply the product's Imonggo ID to the Invoice xml */
						$xml = $xml . 
								'<invoice_line>
									<product_id>' . $catalog_id . '</product_id>
									<quantity>' . $item_quantity . '</quantity>
									<retail_price>' . $item_unit_price . '</retail_price>
								</invoice_line>';
					}
					
					/* concatenate closing xml tags to $xml */
					$xml = $xml . 
							'</invoice_lines>
						</invoice>';
					
					/* post the created $xml to Imonggo */
					$url = "https://" . $account_id . ".c3.imonggo.com/api/invoices.xml";
					
					/* $result : Imonggo API POST response*/
					$result = post_to_imonggo($url, $username, $password, $xml);
					
					/* $output : error contained in Imonggo's response */
					$output = (string)$result->error;
					
					/* If the reference supplied already exist, prompt a duplication message */
					if ($output != null and $output == "Reference has already been taken"){
						echo "<p class='duplicate'>Invoice " . $invoice_number . " with 3dCart Order ID: " . $order_id . " already exists. Invoice was not added.</p>";
					}
					
					/*
						If the ordered product doesn't exist in Imonggo, do not post the invoice to Imonggo,
						and prompt an error message and the error's possible reason
					*/
					else if ($output != null and $output != "Product can't be blank"){
						echo "<p class='invalid'>An error occurred while posting Invoice " . $invoice_number . " with 3dcart Order ID " . $order_id . ". Invoice was not posted to Imonggo.</br>";
						echo "Note: Please update products before updating invoices and delete 3dCart products which are not in your Imonggo store.</p>";
					}
					
					/* If invoice was successfully added, prompt a success message */
					else if ($output == null and (string)$result->id != null and (string)$result->id != ""){
						echo "<p class='success'>Invoice " . $invoice_number . " with 3dcart Order ID " . $order_id . " was successfully posted to Imonggo.</p>";
					}
					
					/* if other error(s) occurred while posting the invoice, do not post invoice to Imonggo; prompt the error encountered */
					else if ($output != null and $output != ""){
						echo "<p class='invalid'>An error occurred while posting Invoice " . $invoice_number . " with 3dcart Order ID " . $order_id . ". Invoice was not posted to Imonggo.</br>";
						echo "Error Description: " . $output . ".</p>";
					}
					
					/* If an internal server error occurred, prompt an error */
					else{
						echo "<p class='internal-server-error'>An internal server error occurred while posting " . $name . " with 3dcart id " . $customer_id . ". Customer was not added to Imonggo.</p>";
					}
				}
				
				/* If the invoice status is not "shipped", do not post the invoice; prompt an output message. */
				else{
					echo "<p class='not_yet_shipped'>Invoice " . $invoice_number . " with 3dcart Order ID " . $order_id . " was not posted because the order wasn't shipped yet.</p>";
				}
			}
			
			/*
				If the invoice order date is prior to the last time the user posted invoices, it should have already been posted to Imonggo.
				Hence, do not post the invoice to avoid duplication.
				Then, prompt a duplication message.
			*/
			else{
				echo "<p class='duplicate'>Invoice " . $invoice_number . " with 3dcart Order ID " . $order_id . " was not posted because it had been posted to imonggo before.</p>";
			}
		}
		
	}
	
?>