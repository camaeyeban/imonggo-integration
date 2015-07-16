<?php

	/******************* PULL CUSTOMERS FROM 3DCART THEN POST THESE TO IMONGGO *******************/
	function update_customers($choice, $username, $password, $domain, $host, $version, $http_header){
		$query = "SELECT * FROM adding_customers_option";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		
		if(!$row){
			$save_choice = mysql_query("
				INSERT INTO adding_customers_option (choice_id, choice) VALUES(DEFAULT, '$choice') 
			");
		}
		else{
			$update_choice = mysql_query("
				UPDATE adding_customers_option SET choice = '$choice' WHERE choice_id='$row[0]'
			");
		}
		
		
		$service = 'Customers';
		$url = $host . '/3dCartWebAPI/v' . $version . '/' . $service;
		$customers_of_3dcart = pull_from_3dcart($url, $http_header);
		
		
		foreach ($customers_of_3dcart as $customer_of_3dcart){
			$customer_id = $customer_of_3dcart->CustomerID;
			$enabled = $customer_of_3dcart->Enabled;
			
			if($enabled == "true"){
				$email = $customer_of_3dcart->Email;
				$password = $customer_of_3dcart->password;
				
				if($choice == "Billing"){
					$company = $customer_of_3dcart->BillingCompany;
					$first_name = $customer_of_3dcart->BillingFirstName;
					$last_name = $customer_of_3dcart->BillingLastName;
					$address1 = $customer_of_3dcart->BillingAddress1;
					$address2 = $customer_of_3dcart->BillingAddress2;
					$city = $customer_of_3dcart->BillingCity;
					$state = $customer_of_3dcart->BillingState;
					$zip_code = $customer_of_3dcart->BillingZipCode;
					$country = $customer_of_3dcart->BillingCountry;
				}
				else if($choice == "Shipping"){
					$company = $customer_of_3dcart->ShippingCompany;
					$first_name = $customer_of_3dcart->ShippingFirstName;
					$last_name = $customer_of_3dcart->ShippingLastName;
					$address1 = $customer_of_3dcart->ShippingAddress1;
					$address2 = $customer_of_3dcart->ShippingAddress2;
					$city = $customer_of_3dcart->ShippingCity;
					$state = $customer_of_3dcart->ShippingState;
					$zip_code = $customer_of_3dcart->ShippingZipCode;
					$country = $customer_of_3dcart->ShippingCountry;
				}
				$mobile = $customer_of_3dcart->BillingPhoneNumber;
				$telephone = $customer_of_3dcart->ShippingPhoneNumber;
				$customer_group_id = $customer_of_3dcart->CustomerGroupID;
				$non_taxable = $customer_of_3dcart->NonTaxable;
				$comments = $customer_of_3dcart->Comments;
				$name = $first_name . ' ' . $last_name;
				
				
				$xml = 
					'<?xml version="1.0" encoding="UTF-8"?>
					<customer>
						<alternate_code>' . $customer_id . '</alternate_code>
						<city>' . $city . '</city>
						<company_name>' . $company . '</company_name>
						<country>' . $country . '</country>
						<email>' . $email . '</email>
						<first_name>' . $first_name . '</first_name>
						<last_name>' . $last_name . '</last_name>
						<mobile>' . $mobile . '</mobile>
						<name>' . $name . '</name>
						<remark>' . $comments . '</remark>
						<state>' . $state . '</state>
						<street>' . $address1 . '</street>
						<telephone>' . $telephone . '</telephone>
						<zipcode>' . $zip_code . '</zipcode>
					</customer>';
					
				$url = "https://" . $domain . ".c3.imonggo.com/api/customers.xml";
				$http_code = post_to_imonggo($url, $username, $password, $xml);
				
				if($http_code == 422){
					echo "<br>Customer Name: " . $name . "<br>";
					echo "3dCart Customer ID:" . $customer_id . "<br><br>";
				}
				else if($http_code == 200){
					echo $name . " with 3dcart customer id " . $customer_id . " was successfully added to Imonggo.<br>";
				}
			}
			else{
				echo "Customer with 3dCart ID " . $customer_id . " is disabled. It wasn't posted to Imonggo.<br><br>";
			}
		}
		
	}
	
?>