<?php
	
	/********************* PULL PRODUCTS FROM IMONGGO THEN POST IT TO 3DCART *********************/
	function update_products($tag, $username, $password, $domain, $host, $version, $http_header){
		
		$url = "https://" . $domain . ".c3.imonggo.com/api/products.xml";
		$imonggo_products = pull_from_imonggo($url, $username, $password);
	
		$service = 'Products';
		
		foreach ($imonggo_products as $imonggo_product){
			if($tag == null || ($tag != null && $imonggo_product->tag_list == $tag)){
				$url = $host . '/3dCartWebAPI/v' . $version . '/' . $service . '/' . $imonggo_product->id;
				$product_of_3dcart = pull_from_3dcart($url, $http_header);
				
				if($product_of_3dcart == null){
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
					
					$url = $host . '/3dCartWebAPI/v' . $version . '/' . $service;
					$xml = 
						'<?xml version="1.0" encoding="UTF-8"?>
						<Product xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
						  <SKUInfo>
							<CatalogID>' . $id . '</CatalogID>
							<SKU>' . $stock_no . '</SKU>
							<Name>' . $name . '</Name>
							<Cost>' . $cost . '</Cost>
							<Price>' . $retail_price . '</Price>
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
					//post_to_3dcart($url, $http_header, $xml);
					echo $retail_price;
				}
				else{
					echo '<p>' . $name . ' (with id ' . $id . ') has same id with an existing product in 3dcart<p>';
				}
			}
		}
		
	}
	
?>