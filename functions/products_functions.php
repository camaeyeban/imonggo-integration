<?php
	
	/********************* PULL PRODUCTS FROM IMONGGO THEN POST THESE TO 3DCART *********************/
	/*
		name: update_products
		description : this function pulls product(s) from imonggo then posts those pulled products to 3dcart
		parameters :
			$tags : an array of tags that were checked
				  :	If the user wishes to pull all Imonggo products (default), $tags is set to null
			$username : the user's Imonggo API key
			$password : any value since only the username is checked when requesting from Imonggo's API
			$account_id : the user's Imonggo account id
			$host : the user's 3dCart store host
			$version : xml version to be used when a post request is to be sent to 3dCart's API
			$http_header : HTTP header to be included in 3dCart post request
						 : It includes the fields: (1) Content-Type, (2) Accept, (3) SecureUrl, (4) PrivateKey, and (5) Token
	*/
	function update_products($tags, $username, $password, $account_id, $host, $version, $http_header){
		
		/* gets the number of Imonggo products which are active (products which are not deleted) */
		$url_imonggo = "https://" . $account_id . ".c3.imonggo.com/api/products.xml?active_only=1&q=count";
		$imonggo_active_products_count = pull_from_imonggo($url_imonggo, $username, $password);
		
		/* if the number of active products is 0, prompt that the user's store has no products */
		if($imonggo_active_products_count->count == 0){
			echo "<p class='empty'>Your Imonggo Store has no products!</p>";
			return;
		}
		
		$service = 'Products';
		$url_3dcart = $host . '/3dCartWebAPI/v' . $version . '/' . $service;
		$xml = '<ArrayOfProduct xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
		
		/* pull the inventory levels of user's Imonggo store */
		$url_imonggo = "https://" . $account_id . ".c3.imonggo.com/api/inventories.xml";
		$inventory_levels = pull_from_imonggo($url_imonggo, $username, $password);
		
		$page = 0;
		$products_per_page = 50;
		
		do{
			$page++;
			
			/* pull the products of user's Imonggo store */
			$url_imonggo = "https://" . $account_id . ".c3.imonggo.com/api/products.xml?page=" . $page . "&per_page=" . $products_per_page;
			$products_of_imonggo = pull_from_imonggo($url_imonggo, $username, $password);
		
			/* traverse each of the pulled products from Imonggo */
			foreach ($products_of_imonggo as $product_of_imonggo){
				
				/* parse the tag_list of the product which is currently being traversed into an array of tags */
				$product_tags = explode(',', $product_of_imonggo->tag_list);
				
				/* get the product name */
				$name = $product_of_imonggo->name;
			
				/* traverse each of the parsed tag_list of the currently being traversed product */
				foreach ($product_tags as $product_tag){
					
					/* remove unnecessary spaces (spaces before and after the tag name) on the currently being traversed tag */
					$product_tag = trim($product_tag);
					
					/*
						$tags == null : if the user chose to update all products (default)
						$tags != null and in_array($product_tag, $tags) : if the user chose to update only desired tags
					*/
					if ($tags == null or ($tags != null and in_array($product_tag, $tags))){
						
						/* save the product's id and status to their corresponding variables */
						$id = $product_of_imonggo->id;
						$status = $product_of_imonggo->status;
						
						/* fetch product(s) with Imonggo ID equal to the currently being traversed product's ID from database */
						$query = "SELECT id_3dcart FROM products where id_imonggo='$id'";
						$result = mysql_query($query);
						$row = mysql_fetch_array($result);
						
						/*
							if currently being traversed product's ID doesn't match any Imonggo product ID in the database
							and the currently being traversed product is not deleted in Imonggo,
							ADD the product to the user's 3dCart store
						*/
						if ($row[0] == null and $status != "D"){
							
							/* save necessary xml-tag values to their corresponding variables */
							$cost = $product_of_imonggo->cost;
							$description = $product_of_imonggo->description;
							$retail_price = $product_of_imonggo->retail_price;
							$tax_exempt = $product_of_imonggo->tax_exempt;
							$utc_created_at = $product_of_imonggo->utc_created_at;
							$utc_updated_at = $product_of_imonggo->utc_updated_at;
							$stock_no = $product_of_imonggo->stock_no;
							$matched = false;
							
							/* traverse each pulled inventory levels from Imonggo */
							foreach ($inventory_levels as $inventory_level){
								
								/* get stock of each traversed inventory */
								$stock = (float) $inventory_level->quantity;

								/*
									if the traversed inventory's product id matches the currently being traversed product,
									ADD the product to 3dCart
								*/
								if ((string)$id == (string)$inventory_level->product_id){
									
									/*
										$matched : flag that denotes whether or not the product has matched an inventory
												   from the pulled inventory level of Imonggo
									*/
									$matched = true;

									/* if the inventory that matched the product has stock greater than 0, ADD the product to the user's 3dCart store */
									if($stock > 0){
										
										/* get the currently being traversed product's image url */
										$thumbnail_url = $product_of_imonggo->thumbnail_url;
										
										/* if the fetched image url of the product is not empty, convert the image's source to 3dCart's image url format */
										if($thumbnail_url == null or ($thumbnail_url != null and $thumbnail_url != "")){
											/*$image_id = explode('/', $thumbnail_url);
											$thumbnail_url = 'https://' . $account_id . '.c3.imonggo.com' . $thumbnail_url;
											//echo $thumbnail_url;
											
											$path = 'http://images.forbes.com/media/lists/companies/google_416x416.jpg';
											$type = pathinfo($path, PATHINFO_EXTENSION);
											$data = file_get_contents($path);
											$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
											echo "<img src=" . $base64 . "></img>";
											//$main_image_file = "assets/images/default/haha." . $type . "|" .$data;
											$main_image_file = $data;
											*/
											$main_image_file = "";
										}
										
										/* if the product has no image in Imonggo, use the default image provided by 3dCart */
										else{
											$main_image_file = "";
										}
										
										/* provide the user's 3dCart product API url */
										$url_3dcart = $host . '/3dCartWebAPI/v' . $version . '/' . $service;
										
										/*
											use fetched field values from pulled Imonggo product then use it to
											create an xml for the product to be added to 3dCart store
										*/
										$add_product_xml = 
											'<?xml version="1.0" encoding="UTF-8"?>
											<Product xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
												<SKUInfo>
													<CatalogID>' . $id . '</CatalogID>
													<SKU>' . $stock_no . '</SKU>
													<Name>' . $name . '</Name>
													<Cost>' . $cost . '</Cost>
													<Price>' . $retail_price . '</Price>
													<Stock>' . $stock . '</Stock>
												</SKUInfo>
												<ShortDescription>' . $description . '</ShortDescription>
												<LastUpdate>' . $utc_updated_at . '</LastUpdate> 
												<NonTaxable>' . $tax_exempt . '</NonTaxable>
												<NotForSale>false</NotForSale>
												<Hide>false</Hide>
												<CategoryList></CategoryList>
												<GiftCertificate>false</GiftCertificate>
												<HomeSpecial>false</HomeSpecial>
												<CategorySpecial>false</CategorySpecial>
												<NonSearchable>false</NonSearchable>
												<Description>' . $description . '</Description>
												<MainImageFile>' . $main_image_file . '</MainImageFile>
												<ThumbnailFile>' . $thumbnail_url . '</ThumbnailFile>
												<DateCreated>' . $utc_created_at . '</DateCreated>
											</Product>';
											
										/* use the created xml as the xml body for the post request to be sent to 3dCart */
										$result = post_to_3dcart($url_3dcart, $http_header, $add_product_xml);
										
										/* decode the json response to xml */
										$result = json_decode($result, true);
										
										/* use x-path to retrieve the response's value (id of the newly created product in 3dCart) and message */
										$id_3dcart = $result[0]['Value'];
										$message = $result[0]['Message'];

										/*
											if no errors were encountered while executing the post request,
											prompt a confirmation message that the process was successful,
											then push a row to product id mapping in database
										*/
										if($message == 'Created successfully'){
											echo '<p class="success">' . $name . ' with imonggo id ' . $id . ' was successfully added to your 3dCart store as ' . $name . ' with 3dCart catalog id ' . $id_3dcart . '.</p>';
											$add_product = mysql_query("
												INSERT INTO products (id_imonggo, id_3dcart) VALUES('$id', '$id_3dcart') 
											");
										}
										
										/*
											if an error occurred while executing the post request,
											prompt the error message
										*/
										else{
											echo '<p class="out-of-stock">' . $name . ' with imonggo id ' . $id . ' was not added to your 3dCart store.<br/>';
											echo 'Error description: ' . $message . '</p>';
											echo 'Error result: ' . $result . '</p>';
										}
										
									}
									
									/*
										if the inventory that matched the product is out of stock (stock less than or equal to 0),
										do not add the product and prompt an error message
									*/
									else{
										echo '<p class="out-of-stock">' . $name . ' with imonggo id ' . $id . ' was not added to your 3dCart store for it has ' . $stock . ' stock.</p>';
									}
									
									/*
										break : do not continue traversing other inventory level entries once it has matched
										at least one of the pulled Imonggo inventory levels to avoid unnecessary exploration
									*/
									break;
								}
							}
							
							/*
								if the currently being traversed product does not match any inventory level entry,
								the product is out of stock (stock = 0), hence do not add the product to user's 3dCart store
							*/
							if($matched == false){
								echo '<p class="out-of-stock">' . $name . ' with imonggo id ' . $id . ' was not added to your 3dCart store for it is out of stock.</p>';
							}
						}
						
						/*
							if the product is not yet added to user's 3dCart store and it has been deleted in Imonggo,
							do not add the product and prompt a message that the product is currently disabled in Imonggo
						*/
						else if ($row[0] == null and $status == "D"){
							echo '<p class="disabled">' . $name . ' with imonggo id ' . $id . ' was already deleted in Imonggo. Product was not added.</p>';
						}
						
						/*
							if the product was already added to 3dCart but it has been deleted in Imonggo,
							delete the product in 3dCart as well then prompt a message stating the action that was taken
						*/
						else if ($row[0] != null and $status == "D"){
							$temp_url = $host . '/3dCartWebAPI/v' . $version . '/' . $service . '/' . $row[0];
							echo $temp_url;
							$pulled_product = pull_from_3dcart($temp_url, $http_header);
							//print_r($pulled_product);
							echo "Product hide: " . $pulled_product->Hide;
							echo htmlentities($pulled_product);
							//if ($pulled_product->Hide == 'false'){
								$xml = $xml . '
									<Product>
										<SKUInfo>
											<CatalogID>' . $row[0] . '</CatalogID>
										</SKUInfo>
										<Hide>true</Hide>
									</Product>
									';
								echo '<p class="deleted">' . $name . ' with Imonggo ID ' . $id . ' had been hidden in 3dCart for it was deleted in Imonggo.</p>';
							//}
						}
						
						/* if an error occurred while adding the product, prompt a message */
						else {
							echo '<p class="duplicate">' . $name . ' with Imonggo ID ' . $id . ' already exists. Product was not added.</p>';
						}
						
						/*
							break : do not continue traversing other tags of the currently being traversed product
									once it has matched at least one of the selected tags to avoid unnecessary exploration
						*/
						break;
					}
				}
			}
		}while($products_of_imonggo->product[0] != null);
		
		$url_3dcart = $host . '/3dCartWebAPI/v' . $version . '/' . $service;
		$xml = $xml . '</ArrayOfProduct>';
		$put_result = put_to_3dcart($url_3dcart, $http_header, $xml);
	}
	
?>