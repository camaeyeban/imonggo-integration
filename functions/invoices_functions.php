<?php

	/******************************* PULL INVOICES FROM 3DCART ********************************/
	function update_invoices($choice, $username, $password, $domain, $host, $version, $http_header){
		
		$query = "SELECT * FROM last_invoice_posting";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		
		$service = 'Orders';
		$url = $host . '/3dCartWebAPI/v' . $version . '/' . $service;
		$invoices_of_3dcart = pull_from_3dcart($url, $http_header);
		
		foreach ($invoices_of_3dcart as $invoice_of_3dcart){
			if (strtotime($invoice_of_3dcart->OrderDate) > strtotime($row[1])){
				$order_id = $invoice_of_3dcart->OrderID;
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
				
				$xml = 
					'<?xml version="1.0" encoding="UTF-8"?>
					<invoice>
						<reference>' . $order_id . '</reference>
						<customer_name nil="true">' . $name . '</customer_name>
						<customer_id nil="true">' . $customer_id . '</customer_id>
						<payments type="array">
							<payment>
								<amount>' . $order_amount . '</amount>
								<card_last_4_digits nil="true">' . $card_number . '</card_last_4_digits>
								<card_last_name nil="true">' . $card_name . '</card_last_name>
								<card_type nil="true">' . $card_type . '</card_type>
								<city nil="true">' . $city . '</city>
								<country nil="true">' . $country . '</country>
								<payment_type_id>' . $payment_method_id . '</payment_type_id>
								<state nil="true">' . $state . '</state>
								<street nil="true">' . $address . '</street>
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
						$update_choice = mysql_query("
							SELECT id_imonggo FROM products WHERE id_3dcart='$catalog_id'
						");
						$catalog_id = $row[0];
					}
					
					$xml = $xml . 
							'<invoice_line>
								<product_id>' . $catalog_id . '</product_id>
								<quantity>' . $item_quantity . '</quantity>
								<retail_price>' . $item_unit_price . '</retail_price>
								<product_name>' . $item_description . '</product_name>
								<product_stock_no>' . $item_id . '</product_stock_no>
							</invoice_line>';
				}
				
				$xml = $xml . 
						'</invoice_lines>
					</invoice>';
				
				echo htmlentities($xml);
				echo '<br/><br/><br/>';
					/*
				$url = "https://" . $domain . ".c3.imonggo.com/api/invoices.xml";
				$http_code = post_to_imonggo($url, $username, $password, $xml);
				
				if($http_code == 422){
					echo "<br/>3dCart Order ID: " . $order_id . "<br>";
					echo "Customer Name:" . $name . "<br><br>";
				}
				else if($http_code == 200){
					echo "Order with 3dcart order id " . $order_id . " was successfully posted to Imonggo.<br>";
				}*/
			}
		}
		
	}
	
?>