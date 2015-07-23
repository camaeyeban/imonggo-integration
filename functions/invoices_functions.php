<?php

	/************************* PULL INVOICES FROM 3DCART THEN POST TO IMONGGO **************************/
	function update_invoices($choice, $username, $password, $domain, $host, $version, $http_header){
		
		$query = "SELECT * FROM last_invoice_posting";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		
		$date_time = date('Y-m-d H:i:s', time());
		
		if(!$row){
			echo "Invoices posted from the beginning until " . $date_time . "<br>";
			$insert_to_last_posting = mysql_query("
				INSERT INTO last_invoice_posting (id, date) VALUES(DEFAULT, '$date_time') 
			");
			$insert_to_invoices = mysql_query("
				INSERT INTO invoices (post_id, post_date) VALUES (DEFAULT, '$date_time')
			");
		}
		
		else{
			echo "Invoices posted from " . $row[1] . " to " . $date_time . "<br>";
			$update_last_posting = mysql_query("
				UPDATE last_invoice_posting SET date = '$date_time' WHERE id='$row[0]'
			");
			$insert_to_invoices = mysql_query("
				INSERT INTO invoices (post_id, post_date) VALUES (DEFAULT, '$date_time')
			");
		}
		
		$service = 'Orders';
		
		$url = $host . '/3dCartWebAPI/v' . $version . '/' . $service . '?countonly=1';
		$invoices_of_3dcart_count = pull_from_3dcart($url, $http_header);
		$invoices_count = $invoices_of_3dcart_count->TotalCount;
		
		$url = $host . '/3dCartWebAPI/v' . $version . '/' . $service . '?limit=' . $invoices_count;
		$invoices_of_3dcart = pull_from_3dcart($url, $http_header);
		
		foreach ($invoices_of_3dcart as $invoice_of_3dcart){
			$order_id = $invoice_of_3dcart->OrderID;
			$invoice_number = $invoice_of_3dcart->InvoiceNumberPrefix . $invoice_of_3dcart->InvoiceNumber;
				
			$status_id = $invoice_of_3dcart->OrderStatusID;
			
			if (strtotime($invoice_of_3dcart->OrderDate) > strtotime($row[1])){
				if($status_id == 4){
					$customer_id = $invoice_of_3dcart->CustomerID;
					$first_name = $invoice_of_3dcart->BillingFirstName;
					$last_name = $invoice_of_3dcart->BillingLastName;
					$address = $invoice_of_3dcart->BillingAddress;
					$city = $invoice_of_3dcart->BillingCity;
					$state = $invoice_of_3dcart->BillingState;
					$country = $invoice_of_3dcart->BillingCountry;
					$payment_method = $invoice_of_3dcart->BillingPaymentMethod;
					$payment_method_id = $invoice_of_3dcart->BillingPaymentMethodID;
					
					$order_amount = $invoice_of_3dcart->OrderAmount;
					$AffiliateCommission = $invoice_of_3dcart->AffiliateCommission;
					$card_type = $invoice_of_3dcart->CardType;
					$full_card_number = $invoice_of_3dcart->CardNumber;
					$card_name = $invoice_of_3dcart->CardName;
					
					$card_number = substr($full_card_number, -4);
					$name = $first_name . ' ' . $last_name;
							
					$shipment_cost = 0.0;
					foreach ($invoice_of_3dcart->ShipmentList->Shipment as $invoice_shipment_of_3dcart){
						$shipment_cost = (double)$shipment_cost + (double)$invoice_shipment_of_3dcart->ShipmentCost;
					}
					
					$order_amount = (double)$order_amount - (double)$shipment_cost;
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
							
					foreach ($invoice_of_3dcart->OrderItemList->OrderItem as $invoice_order_item_of_3dcart){
						$catalog_id = $invoice_order_item_of_3dcart->CatalogID;
						$item_id = $invoice_order_item_of_3dcart->ItemID;
						$item_quantity = $invoice_order_item_of_3dcart->ItemQuantity;
						$item_description = $invoice_order_item_of_3dcart->ItemDescription;
						$item_unit_price = $invoice_order_item_of_3dcart->ItemUnitPrice;
						
						$query = "SELECT * FROM products";
						$result = mysql_query($query);
						$row = mysql_fetch_array($result);
						
						if(!$row){
							echo "<script> alert('Please update Products before updating Invoices.'); </script>";
							return;
						}
						else{
							$query = "SELECT id_imonggo FROM products where id_3dcart='$catalog_id'";
							$result = mysql_query($query);
							$row = mysql_fetch_array($result);
							
							if($row[0] != null){
								echo $catalog_id;
								$catalog_id = $row[0];
								echo " == " . $catalog_id;
							}
						}
						
						$xml = $xml . 
								'<invoice_line>
									<product_id>' . $catalog_id . '</product_id>
									<quantity>' . $item_quantity . '</quantity>
									<retail_price>' . $item_unit_price . '</retail_price>
								</invoice_line>';
					}
					
					$xml = $xml . 
							'</invoice_lines>
						</invoice>';
					
					$url = "https://" . $domain . ".c3.imonggo.com/api/invoices.xml";
					$result = post_to_imonggo($url, $username, $password, $xml);
					$output = (string)$result->error;
					
					
					if($output != null and $output == "Reference has already been taken"){
						echo "<p class='duplicate'>Invoice " . $invoice_number . " with 3dCart Order ID: " . $order_id . " already exists. Invoice was not added.</p>";
					}
					
					/* If other error(s) occurred, do not add customer and prompt the error(s) */
					else if($output != null and $output != ""){
						echo "<p class='invalid'>An error occurred while posting Invoice " . $invoice_number . " with 3dcart Order ID " . $order_id . ". Invoice was not posted to Imonggo.</br>";
						echo "Error Description: " . $output . "</p>";
					}
					
					/* If invoice was successfully added */
					else if($output == null and (string)$result->id != null and (string)$result->id != ""){
						echo "<p class='success'>Invoice " . $invoice_number . " with 3dcart Order ID " . $order_id . " was successfully posted to Imonggo.</p>";
					}
					
					/* If an internal server error occurred */
					else{
						echo "<p class='internal-server-error'>An internal server error occurred while posting " . $name . " with 3dcart id " . $customer_id . ". Customer was not added to Imonggo.</p>";
					}
				}
				else{
					echo "<p class='not_yet_shipped'>Invoice " . $invoice_number . " with 3dcart Order ID " . $order_id . " was not posted because the order wasn't shipped yet.</p>";
				}
			}
			else{
				echo "<p class='duplicate'>Invoice " . $invoice_number . " with 3dcart Order ID " . $order_id . " was not posted because it had been posted to imonggo before.</p>";
			}
		}
		
	}
	
?>