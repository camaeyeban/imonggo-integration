<?php

	/*
		File name: customers_functions.php
		Description:
			This file contains all functions available for the Customers service.
			Customer functions use Imonggo as its general source of truth but use 3dCart as its source of information.
			Customers will be pulled from user's 3dCart store and shall be posted to user's Imonggo store.
	*/
	
	
	/*
		function name : update_customer
		description : this function pulls all user's 3dCart customers
					  then pushes the pulled customers to the user's Imonggo store
		parameters :
			$choice : source of information (value is either 'Billing' or 'Shipping')
					: whether the information to be posted to Imonggo is from 3dCart
						customers' billing or shipping information
			$username : the user's Imonggo API key
			$password : any value since only the username is checked when requesting from Imonggo's API
			$account_id : the user's Imonggo account id
			$host : the user's 3dCart store host
			$version : 3dCart's API version
			$http_header : HTTP header to be included in 3dCart post request
						 : It includes the fields: (1) Content-Type, (2) Accept, (3) SecureUrl, (4) PrivateKey, and (5) Token
	*/
	function update_customers($choice, $username, $password, $account_id, $host, $version, $http_header){
		
		/* $service : will be used in supplying 3dCart's URL */
		$service = 'Customers';
		
		/* Get total customer count in 3dCart */
		$url_3dcart = $host . '/3dCartWebAPI/v' . $version . '/' . $service . '?countonly=1';
		$customers_of_3dcart_count = pull_from_3dcart($url_3dcart, $http_header);
		$customer_count = $customers_of_3dcart_count->TotalCount;
		
		/*
			Retrieve all of the user's 3dCart customers.
			Use the total customer count as limit parameter in retrieving the list of customers from 3dCart.
		*/
		$url_3dcart = $host . '/3dCartWebAPI/v' . $version . '/' . $service . '?limit=' . $customer_count;
		$customers_of_3dcart = pull_from_3dcart($url_3dcart, $http_header);
		
		/* Traverse all customers in 3dCart */
		foreach ($customers_of_3dcart as $customer_of_3dcart){
			/* save customer xml fields to their corresponding variables */
			$customer_id = $customer_of_3dcart->CustomerID;
			$enabled = $customer_of_3dcart->Enabled;
			
			/*
				if the user chooses "Billing" as the source of customer information,
				fetch 3dCart customer name using his/her billing information
			*/
			if($choice == "Billing"){
				$first_name = $customer_of_3dcart->BillingFirstName;
				$last_name = $customer_of_3dcart->BillingLastName;
			}
			
			/* otherwise, fetch 3dCart customer name using his/her shipping information */
			else if($choice == "Shipping"){
				$first_name = $customer_of_3dcart->ShippingFirstName;
				$last_name = $customer_of_3dcart->ShippingLastName;
			}
			
			/* $name : string containing the concatenated first name and last name of the customers */
			$name = $first_name . ' ' . $last_name;
			
			/* get the customer's imonggo id through the customer id mapping in the database */
			$query = "SELECT id_imonggo FROM customers where id_3dcart='$customer_id'";
			$result = mysql_query($query);
			$row = mysql_fetch_array($result);
			
			/* If customer is not yet added to Imonggo and is enabled in 3dCart, try to add customer to Imonggo */
			if($enabled == "true" and $row[0] == null){
				
				/* save needed fields to their corresponding variables */
				$email = $customer_of_3dcart->Email;
				$password = $customer_of_3dcart->password;
				
				/*
					if the user chooses to get customer information from 3dCart customer billing information,
					fetch customer information from his/her Billing details
				*/
				if($choice == "Billing"){
					$company = $customer_of_3dcart->BillingCompany;
					$address1 = $customer_of_3dcart->BillingAddress1;
					$address2 = $customer_of_3dcart->BillingAddress2;
					$city = $customer_of_3dcart->BillingCity;
					$state = $customer_of_3dcart->BillingState;
					$zip_code = $customer_of_3dcart->BillingZipCode;
					$country = $customer_of_3dcart->BillingCountry;
				}
				
				/*
					if the user chooses to get customer information from 3dCart customer shipping information,
					fetch customer information from his/her Shipping details
				*/
				else if($choice == "Shipping"){
					$company = $customer_of_3dcart->ShippingCompany;
					$address1 = $customer_of_3dcart->ShippingAddress1;
					$address2 = $customer_of_3dcart->ShippingAddress2;
					$city = $customer_of_3dcart->ShippingCity;
					$state = $customer_of_3dcart->ShippingState;
					$zip_code = $customer_of_3dcart->ShippingZipCode;
					$country = $customer_of_3dcart->ShippingCountry;
				}
				
				/* save other necessary information to their corresponding variables */
				$mobile = $customer_of_3dcart->BillingPhoneNumber;
				$telephone = $customer_of_3dcart->ShippingPhoneNumber;
				$customer_group_id = $customer_of_3dcart->CustomerGroupID;
				$non_taxable = $customer_of_3dcart->NonTaxable;
				$comments = $customer_of_3dcart->Comments;
				
				/*
					Create the xml to be used in posting a customer in Imonggo.
					Use the variables which were previously initialized using 3dCart customer information.
					$xml : xml used to post the customer to Imonggo
				*/
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
					
				$url_imonggo = "https://" . $account_id . ".c3.imonggo.com/api/customers.xml";
				
				/* use the created xml as xml body in posting a customer to Imonggo */
				$result = post_to_imonggo($url_imonggo, $username, $password, $xml);
				
				/* get the error using the response of Imonggo after posting a customer */
				$output = (string)$result->error;
				
				/* If alternate code has already been taken, do not add customer, because it is possibly a duplicate */
				if ($output != null and $output == "Alternate code has already been taken"){
					echo "<p class='duplicate'>" . $name . " with 3dCart ID " . $customer_id . " was not added because alternate code has already been taken.</p>";
				}
				
				/* If name is blank, do not add customer */
				else if($output != null and $output == "Name can't be blank"){
					echo "<p class='invalid'>Customer with 3dcart id " . $customer_id . " has no name. Customer was not added.</p>";
				}
				
				/* If other error(s) occurred, do not add customer and prompt the error(s) */
				else if($output != null and $output != ""){
					echo "<p class='invalid'>An error occured while posting " . $name . " with 3dcart ID " . $customer_id . ". Customer was not added to Imonggo.</br>";
					echo "Error Description: " . $output . "</p>";
				}
				
				/* If no error occurred, add customer ID mapping to database and prompt a success message */
				else if($output == null){
					$add_customer = mysql_query("
						INSERT INTO customers (id_3dcart, id_imonggo) VALUES('$customer_id', '$result->id')
					");
					echo "<p class='success'>" . $name . " with 3dcart customer ID " . $customer_id . " was successfully added to Imonggo.</p>";
				}
				
			}
			
			/* If the customer is not yet added to Imonggo but is disabled in 3dCart, do not add customer to Imonggo */
			else if($enabled == "false" and $row[0] == null){
				echo "<p class='disabled'>Customer with 3dCart ID " . $customer_id . " is disabled. It wasn't posted to Imonggo.</p>";
			}
			
			/* If the customer is already added to Imonggo and is enabled in 3dCart, do not add customer to Imonggo to avoid duplication */
			else if($enabled == "true" and $row[0] != null){
				echo "<p class='duplicate'>Customer with 3dCart ID " . $customer_id . " already exists in 3dCart. Customer was not added.</p>";
			}
			
			/* If the customer is already added to Imonggo and is disabled in 3dCart, prompt a message but do nothing */
			else if ($enabled == "false" and $row[0] != null){
				echo "<p class='no-action'>Customer with 3dCart ID " . $customer_id . " and Imonggo ID " . $row[0] . " was disabled in 3dCart. No action was taken.</p>";
			}
			
		}
		
		/*
			$xml : will contain the xml for updating 3dCart customers
				 : initially set to the starting tag "<ArrayOfCustomers>" of the xml
		*/
		$xml = '<ArrayOfCustomer xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
		
		/*
			variable initialization for pulling Imonggo customers of the user
			$page: current page being traversed while pulling Imonggo customers of the user
			$products_per_page : number of Imonggo customers to be pulled per page
		*/
		$page = 0;
		$customers_per_page = 50;
		
		/* fetch 50 customers every page until all Imonggo customers have been traversed */
		do{
			/* increment page number */
			$page++;
			
			/* pull the customers of user's Imonggo store */
			$url_imonggo = "https://" . $account_id . ".c3.imonggo.com/api/customers.xml?page=" . $page . "&per_page=" . $customers_per_page;
			$customers_of_imonggo = pull_from_imonggo($url_imonggo, $username, $password);
			
			/* Traverse all customers of Imonggo */
			foreach ($customers_of_imonggo as $customer_of_imonggo){
				
				/* if Imonggo customer is deleted, disable him/her in 3dCart*/
				if($customer_of_imonggo->status == "D"){
					/* get the customer's 3dCart id using customer id mapping in database */
					$query = "SELECT id_3dCart FROM customers where id_imonggo='$customer_of_imonggo->id'";
					$result = mysql_query($query);
					$row = mysql_fetch_array($result);
				
					/* if the Imonggo customer has corresponding 3dCart id, proceed to disabling the customer then prompt a soft delete message */
					if($row[0] != null){
						$xml = $xml . '
							<Customer>
								<CustomerID>' . $row[0] . '</CustomerID>
								<Enabled>false</Enabled>
							</Customer>
							';
						echo '<p class="deleted">' . $customer_of_imonggo->first_name . ' ' . $customer_of_imonggo->last_name . ' had been disabled in 3dCart for it was deleted in Imonggo.</p>';
					}
				}
			}
		}while($customers_of_imonggo->customer[0] != null);
		
		/* $url_3dCart : 3dCart API URL for customers */
		$url_3dcart = $host . '/3dCartWebAPI/v' . $version . '/' . $service;
		
		/* end the $xml by closing its starting tag <ArrayOfCustomer>*/
		$xml = $xml . '</ArrayOfCustomer>';
		
		/* $put_result : Imonggo API response to update multiple 3dCart customers state to disabled (if they were deleted in user's Imonggo store) */
		$put_result = put_to_3dcart($url_3dcart, $http_header, $xml);
		
	}
	
?>