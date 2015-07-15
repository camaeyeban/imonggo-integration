<?php

	/***************************** PULL CUSTOMERS FROM IMONGGO *****************************/
	function pull_imonggo_customers($username, $password, $domain){
	
		$url = "https://" . $domain . ".c3.imonggo.com/api/customers.xml";
		$imonggo_customers = pull_from_imonggo($url, $username, $password);
		$imonggo_customer_count = pull_from_imonggo($url . "?q=count", $username, $password);
		
		for($i=0; $i<$imonggo_customer_count->count; $i++){
			$alternate_code = $imonggo_customers->customer[$i]->alternate_code;
			$birthday = $imonggo_customers->customer[$i]->birthday;
			$city = $imonggo_customers->customer[$i]->city;
			$code = $imonggo_customers->customer[$i]->code;
			$company_name = $imonggo_customers->customer[$i]->company_name;
			$country = $imonggo_customers->customer[$i]->country;
			$customer_type_id = $imonggo_customers->customer[$i]->customer_type_id;
			$email = $imonggo_customers->customer[$i]->email;
			$fax = $imonggo_customers->customer[$i]->fax;
			$first_name = $imonggo_customers->customer[$i]->first_name;
			$id = $imonggo_customers->customer[$i]->id;
			$last_name = $imonggo_customers->customer[$i]->last_name;
			$mobile = $imonggo_customers->customer[$i]->mobile;
			$name = $imonggo_customers->customer[$i]->name;
			$remark = $imonggo_customers->customer[$i]->remark;
			$status = $imonggo_customers->customer[$i]->status;
			$street = $imonggo_customers->customer[$i]->street;
			$tax_exempt = $imonggo_customers->customer[$i]->tax_exempt;
			$telephone = $imonggo_customers->customer[$i]->telephone;
			$tin = $imonggo_customers->customer[$i]->tin;
			$zipcode = $imonggo_customers->customer[$i]->zipcode;
			$customer_type_name = $imonggo_customers->customer[$i]->customer_type_name;
			$birthdate = $imonggo_customers->customer[$i]->birthdate;
			$utc_created_at = $imonggo_customers->customer[$i]->utc_created_at;
			$utc_updated_at = $imonggo_customers->customer[$i]->utc_updated_at;
			
			echo "alternate_code: " . $alternate_code . "<br>";
			echo "birthday: " . $birthday . "<br>";
			echo "city: " . $city . "<br>";
			echo "code: " . $code . "<br>";
			echo "company_name: " . $company_name . "<br>";
			echo "country: " . $country . "<br>";
			echo "customer_type_id: " . $customer_type_id . "<br>";
			echo "email: " . $email . "<br>";
			echo "fax: " . $fax . "<br>";
			echo "first_name: " . $first_name . "<br>";
			echo "id: " . $id . "<br>";
			echo "last_name: " . $last_name . "<br>";
			echo "mobile: " . $mobile . "<br>";
			echo "name: " . $name . "<br>";
			echo "remark: " . $remark . "<br>";
			echo "status: " . $status . "<br>";
			echo "street: " . $street . "<br>";
			echo "tax_exempt: " . $tax_exempt . "<br>";
			echo "telephone: " . $telephone . "<br>";
			echo "tin: " . $tin . "<br>";
			echo "zipcode: " . $zipcode . "<br>";
			echo "customer_type_name: " . $customer_type_name . "<br>";
			echo "birthdate: " . $birthdate . "<br>";
			echo "utc_created_at: " . $utc_created_at . "<br>";
			echo "utc_updated_at: " . $utc_updated_at . "<br>";
			echo "<br><br><br>";
		}
		
	}
	
	
	/***************************** POST CUSTOMERS TO IMONGGO *****************************/
	function push_customers_to_imonggo($username, $password, $domain){
	
		$url = "https://" . $domain . ".c3.imonggo.com/api/customers.xml";
		$xml = 
			'<?xml version="1.0" encoding="UTF-8"?>
			<customer>
				<first_name>Dash</first_name>
				<last_name>Dalupan</last_name>
			</customer>';
		post_to_imonggo($url, $username, $password, $xml);
		
	}
	
	
	/***************************** POST CUSTOMERS TO 3DCART ******************************/
	function push_customers_to_3dcart($host, $version, $http_header){
	
		$service = 'Customers';
		$url = $host . '/3dCartWebAPI/v' . $version . '/' . $service;
		$xml = 
			'<?xml version="1.0" encoding="UTF-8"?>
			<Customer xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
			  <CustomerID>3</CustomerID>
			  <Email>carmelapatrice@gmail.com</Email>
			  <Password>oyeah</Password>
			  <BillingCompany>UPLB</BillingCompany>
			  <BillingFirstName>Patrice</BillingFirstName>
			  <BillingLastName>Pare</BillingLastName>
			  <BillingAddress1>San Pedro</BillingAddress1>
			  <BillingCity>Laguna</BillingCity>
			  <BillingZipCode>1234</BillingZipCode>
			  <BillingCountry>Philippines</BillingCountry>
			  <BillingPhoneNumber>09121212121</BillingPhoneNumber>
			  <BillingTaxID>1</BillingTaxID>
			  <ShippingCompany>sample string 13</ShippingCompany>
			  <ShippingFirstName>Patrice</ShippingFirstName>
			  <ShippingLastName>Pare</ShippingLastName>
			  <ShippingAddress1>San Pedro</ShippingAddress1>
			  <ShippingCity>Laguna</ShippingCity>
			  <ShippingZipCode>1234</ShippingZipCode>
			  <ShippingCountry>Philippines</ShippingCountry>
			  <ShippingPhoneNumber>09121212121</ShippingPhoneNumber>
			  <ShippingAddressType>1</ShippingAddressType>
			  <CustomerGroupID>2</CustomerGroupID>
			  <Enabled>true</Enabled>
			  <Comments>officemate ko to! xD</Comments>
			</Customer>';
		post_to_3dcart($url, $http_header, $xml);
		
	}
	
	
	/******************* PULL CUSTOMERS FROM IMONGGO THEN POST IT TO 3DCARD *******************/
	function update_customers($username, $password, $domain, $host, $version, $http_header){
	
		$url = "https://" . $domain . ".c3.imonggo.com/api/customers.xml";
		$imonggo_customers = pull_from_imonggo($url, $username, $password);
		
		$service = 'Customers';
		$url = $host . '/3dCartWebAPI/v' . $version . '/' . $service;
		
		foreach ($imonggo_customers as $imonggo_customer){
			$city = $imonggo_customer->city;
			$company_name = $imonggo_customer->company_name;
			$country = $imonggo_customer->country;
			if ($country == ""){
				$country = "United States";
			}
			$email = $imonggo_customer->email;
			if ($email == null or $email == ""){
				$i = rand();
				$email = 'test' . $i . '@3dcart.com';
			}
			$password = $imonggo_customer->password;
			if ($password == null or $password == ""){
				$password = 'oyeah';
			}
			$status = $imonggo_customer->status;
			if ($status == "D"){
				$status = false;
			}
			else{
				$status = true;
			}
			$first_name = $imonggo_customer->first_name;
			$id = $imonggo_customer->id;
			$last_name = $imonggo_customer->last_name;
			$mobile = $imonggo_customer->mobile;
			$name = $imonggo_customer->name;
			$remark = $imonggo_customer->remark;
			$street = $imonggo_customer->street;
			$state = $imonggo_customer->state;
			if ($state == ""){
				$state = "Alabama";
			}
			$tax_exempt = $imonggo_customer->tax_exempt;
			$telephone = $imonggo_customer->telephone;
			$zipcode = $imonggo_customer->zipcode;
			$remark = $imonggo_customer->remark;
			
			$xml = 
				'<?xml version="1.0" encoding="UTF-8"?>
				<Customer xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
				  <CustomerID>' . $id . '</CustomerID>
				  <Email>' . $email . '</Email>
				  <Password>oyeah</Password>
				  <BillingCompany>' . $company_name . '</BillingCompany>
				  <BillingFirstName>' . $first_name . '</BillingFirstName>
				  <BillingLastName>' . $last_name . '</BillingLastName>
				  <BillingAddress1>' . $street . '</BillingAddress1>
				  <BillingCity>' . $city . '</BillingCity>
				  <BillingState>' . $state . '</BillingState>
				  <BillingZipCode>' . $zipcode . '</BillingZipCode>
				  <BillingCountry>' . $country . '</BillingCountry>
				  <BillingPhoneNumber>' . $mobile . '</BillingPhoneNumber>
				  <ShippingCompany>' . $company_name . '</ShippingCompany>
				  <ShippingFirstName>' . $first_name . '</ShippingFirstName>
				  <ShippingLastName>' . $last_name . '</ShippingLastName>
				  <ShippingAddress1>' . $street . '</ShippingAddress1>
				  <ShippingCity>' . $city . '</ShippingCity>
				  <ShippingZipCode>' . $zipcode . '</ShippingZipCode>
				  <ShippingCountry>' . $country . '</ShippingCountry>
				  <ShippingPhoneNumber>' . $telephone . '</ShippingPhoneNumber>
				  <ShippingAddressType>1</ShippingAddressType>
				  <Enabled>' . $status . '</Enabled>
				  <Comments>' . $remark . '</Comments>
				</Customer>';
			post_to_3dcart($url, $http_header, $xml);
		}
		
	}
	
?>