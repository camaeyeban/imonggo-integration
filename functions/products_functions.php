<?php
	
	/************************************ PULL PRODUCTS FROM IMONGGO ************************************/
	function pull_imonggo_products($tag, $username, $password, $domain){
		
		$url = "https://" . $domain . ".c3.imonggo.com/api/products.xml";
		$imonggo_products = pull_from_imonggo($url, $username, $password);
	
		foreach ($imonggo_products as $imonggo_product){
			if($tag == null || ($tag != null && $imonggo_product->tag_list == $tag)){
				$allow_decimal_quantities = $imonggo_product->allow_decimal_quantities;
				$cost = $imonggo_product->cost;
				$description = $imonggo_product->description;
				$disable_discount = $imonggo_product->disable_discount;
				$disable_inventory = $imonggo_product->disable_inventory;
				$enable_open_price = $imonggo_product->enable_open_price;
				$id = $imonggo_product->id;
				$name = $imonggo_product->name;
				$retail_price = $imonggo_product->retail_price;
				$status = $imonggo_product->status;
				$stock_no = $imonggo_product->stock_no;
				$tax_exempt = $imonggo_product->tax_exempt;
				$tag_list = $imonggo_product->tag_list;
				$barcode_list = $imonggo_product->barcode_list;
				$utc_created_at = $imonggo_product->utc_created_at;
				$utc_updated_at = $imonggo_product->utc_updated_at;
				$thumbnail_url = $imonggo_product->thumbnail_url;
				$tax_rates = $imonggo_product->tax_rates;
				$branch_prices = $imonggo_product->branch_prices;
				
				echo "allow_decimal_quantities: " . $allow_decimal_quantities . "<br>";
				echo "cost: " . $cost . "<br>";
				echo "description: " . $description . "<br>";
				echo "disable_discount: " . $disable_discount . "<br>";
				echo "disable_inventory: " . $disable_inventory . "<br>";
				echo "enable_open_price: " . $enable_open_price . "<br>";
				echo "id: " . $id . "<br>";
				echo "name: " . $name . "<br>";
				echo "retail_price: " . $retail_price . "<br>";
				echo "status: " . $status . "<br>";
				echo "stock_no: " . $stock_no . "<br>";
				echo "tax_exempt: " . $tax_exempt . "<br>";
				echo "tag_list: " . $tag_list . "<br>";
				echo "barcode_list: " . $barcode_list . "<br>";
				echo "utc_created_at: " . $utc_created_at . "<br>";
				echo "utc_updated_at: " . $utc_updated_at . "<br>";
				echo "thumbnail_url: " . $thumbnail_url . "<br>";
				echo "tax_rates: " . $tax_rates . "<br>";
				echo "branch_prices: " . $branch_prices . "<br>";
				echo "<br><br><br>";
			}
		}

	}
	
	
	/************************************ POST PRODUCTS TO IMONGGO ************************************/
	function push_products_to_imonggo($username, $password, $domain){
		
		$url = "https://" . $domain . ".c3.imonggo.com/api/products.xml";
		$xml = 
			'<?xml version="1.0" encoding="UTF-8"?>
			<product>
				<name>dash</name>
				<stock_no>00011</stock_no>
				<retail_price>1</retail_price>
			</product>';
		post_to_imonggo($url, $username, $password, $xml);
		
	}
	
	
	/************************************ PULL PRODUCTS FROM 3DCART ************************************/
	function pull_products_from_3dcart($host, $version, $http_header){
		
		$service = 'Products';
		$url = $host . '/3dCartWebAPI/v' . $version . '/' . $service;
		pull_from_3dcart($url, $http_header);
	}
	
	/************************************ PULL A PRODUCT FROM 3DCART ************************************/
	function pull_product_from_3dcart($host, $version, $http_header){
		
		$service = 'Products';
		$url = $host . '/3dCartWebAPI/v' . $version . '/' . $service . '/1';
		$result = pull_from_3dcart($url, $http_header);
	}
	
	
	/************************************ POST PRODUCTS TO 3DCART *************************************/
	function push_products_to_3dcart($host, $version, $http_header){
	
		$service = 'Products';
		$url = $host . '/3dCartWebAPI/v' . $version . '/' . $service;
		$xml = 
			'<?xml version="1.0" encoding="UTF-8"?>
			<Product xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
			  <SKUInfo>
			    <CatalogID>1</CatalogID>
				<SKU>00011</SKU>
				<Name>Brocolli</Name>
				<Cost>0</Cost>
				<Price>6.0</Price>
				<RetailPrice>8.0</RetailPrice>
				<SalePrice>5.0</SalePrice>
				<OnSale>true</OnSale>
				<Stock>20</Stock>
			  </SKUInfo>
			  <ShortDescription>Parang maliit na puno</ShortDescription>
			  <NonTaxable>true</NonTaxable>
			  <NotForSale>false</NotForSale>
			  <Hide>false</Hide>
			  <CategoryList>Vagetables</CategoryList>
			  <GiftCertificate>false</GiftCertificate>
			  <HomeSpecial>false</HomeSpecial>
			  <CategorySpecial>false</CategorySpecial>
			  <NonSearchable>false</NonSearchable>
			  <ShipCost>0</ShipCost>
			  <SelfShip>false</SelfShip>
			  <FreeShipping>true</FreeShipping>
			  <Description>Parang maliit na puno (description)</Description>
			  <MainImageFile>assets/images/brocolli.jpg</MainImageFile>
			  <ThumbnailFile>assets/images/brocolli_thumbnail.jpg</ThumbnailFile> 
			</Product>';
		post_to_3dcart($url, $http_header, $xml);
		
	}
	
	
	/********************* PULL PRODUCTS FROM IMONGGO THEN POST IT TO 3DCART *********************/
	function update_products($tag, $username, $password, $domain, $host, $version, $http_header){
		
		$url = "https://" . $domain . ".c3.imonggo.com/api/products.xml";
		$imonggo_products = pull_from_imonggo($url, $username, $password);
	
		$service = 'Products';
		$url = $host . '/3dCartWebAPI/v' . $version . '/' . $service;
		
		foreach ($imonggo_products as $imonggo_product){
			if($tag == null || ($tag != null && $imonggo_product->tag_list == $tag)){
				$cost = $imonggo_product->cost;
				$description = $imonggo_product->description;
				$id = $imonggo_product->id;
				$name = $imonggo_product->name;
				$retail_price = $imonggo_product->retail_price;
				$tax_exempt = $imonggo_product->tax_exempt;
				$utc_created_at = $imonggo_product->utc_created_at;
				$utc_updated_at = $imonggo_product->utc_updated_at;
				$thumbnail_url = $imonggo_product->thumbnail_url;
				$stock_no = $imonggo_product->stock_no;
				
				$xml = 
					'<?xml version="1.0" encoding="UTF-8"?>
					<Product xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
					  <SKUInfo>
					    <CatalogID>' . $stock_no . '</CatalogID>
						<SKU>' . $id . '</SKU>
						<Name>' . $name . '</Name>
						<Cost>' . $cost . '</Cost>
						<RetailPrice>' . $retail_price . '</RetailPrice>
						<SalePrice>5.0</SalePrice>
						<OnSale>false</OnSale>
						<Stock>100</Stock>
					  </SKUInfo>
					  <ShortDescription>' . $description . '</ShortDescription>
					  <LastUpdate>' . $utc_updated_at . '</LastUpdate> 
					  <NonTaxable>' . $tax_exempt . '</NonTaxable>
					  <NotForSale>true</NotForSale>
					  <Hide>false</Hide>
					  <CategoryList></CategoryList>
					  <GiftCertificate>false</GiftCertificate>
					  <HomeSpecial>false</HomeSpecial>
					  <CategorySpecial>false</CategorySpecial>
					  <NonSearchable>false</NonSearchable>
					  <Description>' . $description . '</Description>
					  <MainImageFile>' . $thumbnail_url . '</MainImageFile>
					  <ThumbnailFile>' . $thumbnail_url . '</ThumbnailFile>
					  <DateCreated>' . $utc_created_at . '</DateCreated>
					</Product>';
				post_to_3dcart($url, $http_header, $xml);
			}
		}
		
	}
?>