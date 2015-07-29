<?php

	/*
		File name: products_functions.php
		Description:
			This file contains all functions available for the Products service.
			Product functions use Imonggo as its general source of truth and information.
			Products will be pulled from user's Imonggo store and shall be posted to user's 3dCart store.
	*/
	
	
	/*
		function name: update_products
		description : this function pulls product(s) from Imonggo then posts those pulled products to 3dcart
		parameters :
			$tags : an array of tags that were checked
				  :	If the user wishes to pull all Imonggo products (default), $tags is set to null
			$username : the user's Imonggo API key
			$password : any value since only the username is checked when requesting from Imonggo's API
			$account_id : the user's Imonggo account id
			$host : the user's 3dCart store host
			$version : 3dCart's API version
			$http_header : HTTP header to be included in 3dCart post request
						 : It includes the fields: (1) Content-Type, (2) Accept, (3) SecureUrl, (4) PrivateKey, and (5) Token
	*/
	function update_products($tags, $username, $password, $account_id, $host, $version, $http_header){
		
		/* $service : will be used in supplying 3dCart's URL */
		$service = 'Products';
		
		/* get the date of the last time the user posted his/her products from the database */
		$query = "SELECT * FROM last_product_posting";
		$result = mysql_query($query);
		$last_posting_date = mysql_fetch_array($result);
		
		/* get the current date and time */
		$object_date_time = new DateTime('NOW');
		
		/* convert the the current date time stamp to ISO8601 format */
		$date_time = $object_date_time->format(DateTime::ISO8601);
		
		/*
			if the database query is empty (meaning it's the user's first time to update products),
			insert to last_product_posting the current datetimestamp
		*/
		if(!$last_posting_date){
			echo "Products posted from the beginning until " . $date_time . "<br>";
			$insert_to_last_posting = mysql_query("
				INSERT INTO last_product_posting (id, date) VALUES(DEFAULT, '$date_time') 
			");
			$insert_to_invoices = mysql_query("
				INSERT INTO product_posting (post_id, post_date) VALUES (DEFAULT, '$date_time')
			");
		}
		
		/*
			if the database query is not empty, just update last_product_posting to the current datetimestamp
		*/
		else{
			echo "Products posted from " . $last_posting_date[1] . " to " . $date_time . "<br>";
			$update_last_posting = mysql_query("
				UPDATE last_product_posting SET date = '$date_time' WHERE id='$last_posting_date[0]'
			");
			$insert_to_invoices = mysql_query("
				INSERT INTO product_posting (post_id, post_date) VALUES (DEFAULT, '$date_time')
			");
		}
		
		/* gets the number of Imonggo products which are active (products which are not deleted) */
		$url_imonggo = "https://" . $account_id . ".c3.imonggo.com/api/products.xml?active_only=1&q=count";
		$imonggo_active_products_count = pull_from_imonggo($url_imonggo, $username, $password);
		
		/*
			if the number of active products is 0, prompt that the user's store has no products
			and do not proceed to product updating function
		*/
		if($imonggo_active_products_count->count == 0){
			echo "<p class='empty'>Your Imonggo Store has no products!</p>";
			return;
		}
		
		/* if the number of Imonggo products which are active is greater that 0, continue product updating */
		/* $url_3dCart : 3dCart URL which will be used in pulling or/and posting products */
		$url_3dcart = $host . '/3dCartWebAPI/v' . $version . '/' . $service;
		
		/*
			$xml : will contain the xml for updating 3dCart products
				 : initially set to the starting tag "<ArrayOfProduct>" of the xml
		*/
		$xml = '<ArrayOfProduct xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
		
		/* pull the inventory levels of user's Imonggo store */
		$url_imonggo = "https://" . $account_id . ".c3.imonggo.com/api/inventories.xml";
		$inventory_levels = pull_from_imonggo($url_imonggo, $username, $password);
		
		/*
			variable initialization for pulling Imonggo products of the user
			$page: current page being traversed while pulling Imonggo products of the user
			$products_per_page : number of Imonggo products to be pulled per page
		*/
		$page = 0;
		$products_per_page = 50;
		
		/* fetch 50 products every page until all Imonggo products have been traversed */
		do{
			/* increment page number */
			$page++;
			
			/* pull the products of user's Imonggo store */
			$url_imonggo = "https://" . $account_id . ".c3.imonggo.com/api/products.xml?page=" . $page . "&per_page=" . $products_per_page;
			$products_of_imonggo = pull_from_imonggo($url_imonggo, $username, $password);
		
			/* traverse each of the pulled products from Imonggo */
			foreach ($products_of_imonggo as $product_of_imonggo){
				
				/* parse the tag_list of the product which is currently being traversed into an array of tags */
				$product_tags = explode(',', $product_of_imonggo->tag_list);
				
				/* get the product name of the product which is currently being traversed */
				$name = $product_of_imonggo->name;
			
				/* traverse each of the parsed tag_list of the currently being traversed product */
				foreach ($product_tags as $product_tag){
					
					/* remove unnecessary spaces (spaces before and after the tag name) on the currently being traversed tag */
					$product_tag = trim($product_tag);
					
					/*
						$tags == null : if the user chose to update all products (default)
						$tags != null and in_array($product_tag, $tags) : if the user's chosen filter is one of the tags of the product which is currently being traversed
					*/
					if ($tags == null or ($tags != null and in_array($product_tag, $tags))){
						
						/* save the product's id and status to their corresponding variables */
						$id = $product_of_imonggo->id;
						$status = $product_of_imonggo->status;
						$utc_updated_at = $product_of_imonggo->utc_updated_at;
						
						/* fetch the 3dCart id of the product with Imonggo ID equal to the currently being traversed product's ID from database */
						$query = "SELECT id_3dcart FROM products where id_imonggo='$id'";
						$result = mysql_query($query);
						$row = mysql_fetch_array($result);
						
						/*
							if currently being traversed product's ID doesn't match any Imonggo product ID in the database
							and the currently being traversed product is not deleted in Imonggo,
							ADD the product to the user's 3dCart store
						*/
						if ($status != "D"){
							
							/* save necessary xml-tag values to their corresponding variables */
							$cost = $product_of_imonggo->cost;
							$description = $product_of_imonggo->description;
							$retail_price = $product_of_imonggo->retail_price;
							$tax_exempt = $product_of_imonggo->tax_exempt;
							$utc_created_at = $product_of_imonggo->utc_created_at;
							$stock_no = $product_of_imonggo->stock_no;
							$matched = false;
							
							/* traverse each pulled inventory levels from Imonggo */
							foreach ($inventory_levels as $inventory_level){
								
								/* get stock of each traversed inventory */
								$stock = (float) $inventory_level->quantity;

								/* if the traversed inventory's product id matches the currently being traversed product */
								if ((string)$id == (string)$inventory_level->product_id){
									
									/*
										$matched : flag that denotes whether or not the product has matched an inventory
												   from the pulled inventory level of Imonggo
									*/
									$matched = true;

									/*
										if the inventory that matched the product has stock greater than 0,
										ADD the product to the user's 3dCart store
									*/
									if($stock > 0){
										
										/* get the currently being traversed product's image url */
										$thumbnail_url = $product_of_imonggo->thumbnail_url;
										
										/*
											if the fetched image url of the product is not empty,
											convert the image's source to 3dCart's image url format through base64 encoding
										*/
										if($thumbnail_url == null or ($thumbnail_url != null and $thumbnail_url != "")){
											/* parse Imonggo product URL using '/' as delimiter and save it to $image_id as an array */
											$image_id = explode('/', $thumbnail_url);
											
											/* $image_type : Imonggo product image extension */
											$image_type = pathinfo($thumbnail_url, PATHINFO_EXTENSION);
											
											/*
												$image_id[3] : Imonggo product image name
												$thumbnail_url : Imonggo product image URL
											*/
											$thumbnail_url = 'http://' . $_SESSION['imonggo_api_key'] . ':x@' . $account_id . '.c3.imonggo.com/api/products/' . $image_id[3] . '.' . $image_type;
											
											/* $data : Imonggo product physical image */
											$data = file_get_contents($thumbnail_url);
											
											/* $encoded_thumbnail_url : Imonggo product physical image encoded in base64 */
											$encoded_thumbnail_url = base64_encode($data);
											
											/*
												$thumbnail_url : 3dCart product thumbnail image URL
												format : file_location/<imonggo_product_id>_thumbnail|base64_encoded_image
													* eveything before | is the location and name of the image you want to save
													* everything after | is the string value of the image encoded using base64 encoding
											*/
											$thumbnail_url = 'assets/images/' . $image_id[3] . '_thumbnail.' . $image_type . '|' . $encoded_thumbnail_url;
											
											/*
												$main_image_file : Imonggo product main image URL
																 : same as  its thumbnail image but has a larger resolution
											*/
											$main_image_file = 'http://' . $_SESSION['imonggo_api_key'] . ':x@' . $account_id . '.c3.imonggo.com/api/products/' . $image_id[3] . '.' . $image_type . '?size=large';
											
											/* $data : Imonggo product larger physical image */
											$data = file_get_contents($main_image_file);
											
											/* $encoded_main_image_file : Imonggo product larger physical image encoded in base64 */
											$encoded_main_image_file = base64_encode($data);
											
											/*
												$main_image_file : 3dCart product main image URL
												format : file_location/<imonggo_product_id>_thumbnail|base64_encoded_image
													* eveything before | is the location and name of the image you want to save
													* everything after | is the string value of the image encoded using base64 encoding
											*/
											$main_image_file = 'assets/images/' . $image_id[3] . '.' . $image_type . '|' . $encoded_main_image_file;
										}
										
										/* if the product has no image in Imonggo, use the default image provided by 3dCart */
										else{
											$main_image_file = "";
										}
										
										/* provide the user's 3dCart product API url */
										$url_3dcart = $host . '/3dCartWebAPI/v' . $version . '/' . $service;
										
										/*
											use fetched field values from pulled Imonggo product to
											create an xml for the product to be added or updated to the user's 3dCart store
											$xml : xml used to post or put product to 3dCart
										*/
										
										/* if the product doesn't exist yet, try to ADD the product */
										if ($row[0] == null){ 
											$add_product_xml = 
												'<?xml version="1.0" encoding="UTF-8"?>
												<Product xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
													<SKUInfo>
														<SKU>' . $stock_no . '</SKU>
														<Name>' . $name . '</Name>
														<Cost>' . $cost . '</Cost>
														<Price>' . $retail_price . '</Price>
														<Stock>' . $stock . '</Stock>
													</SKUInfo>
													<ShortDescription>' . $description . '</ShortDescription>
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
											}
										}
										
										/* if the product already exist, try to UPDATE the product */
										else{
											/* $row: product's 3dCard ID */
											$temp_url = $host . '/3dCartWebAPI/v' . $version . '/' . $service . '/' . $row[0];
											
											/* pull the product with the corresponding ID */
											$pulled_product = pull_from_3dcart($temp_url, $http_header);
											
											/*
												if the product currently being traversed is not yet hidden in the user's 3dCart store,
												update the product to be hidden
											*/
											if ($utc_updated_at > $last_posting_date['date']){
												$update_product_xml = 
													'<?xml version="1.0" encoding="UTF-8"?>
													<Product xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
														<SKUInfo>
															<SKU>' . $stock_no . '</SKU>
															<Name>' . $name . '</Name>
															<Cost>' . $cost . '</Cost>
															<Price>' . $retail_price . '</Price>
															<Stock>' . $stock . '</Stock>
														</SKUInfo>
														<ShortDescription>' . $description . '</ShortDescription>
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
													</Product>';
													
												/* use the created xml as the xml body for the post request to be sent to 3dCart */
												$result = put_to_3dcart($temp_url, $http_header, $update_product_xml);
												
												/* decode the json response to xml */
												$result = json_decode($result, true);
												
												/* use x-path to retrieve the response's value (id of the newly created product in 3dCart) and message */
												$id_3dcart = $result[0]['Value'];
												$message = (string)$result[0]['Message'];
												
												/* if the edited product is successfully added, prompt a success message*/
												if($message == 'updated successfully'){
													echo '<p class="success">' . $name . ' with imonggo id ' . $id . ' was successfully updated in your 3dCart store.</p>';
												}
												
												/* if an invalid xml input is encountered, prompt an error */
												else if($message == 'Json/XML object is empty or invalid'){
													echo '<p class="invalid">An error occured while updating ' . $name . ' with imonggo id ' . $id . ' due to an invalid xml syntax. Product was not updated.</p>';
												}
												
												/* if other errors were encountered, prompt the error */
												else{
													echo '<p class="invalid">An error occured while updating ' . $name . ' with imonggo id ' . $id . '. Product was not updated.</p>';
												}
											}
											/*
												if the product currently being traversed is already hidden in the user's 3dCart store,
												skip the product and prompt a duplication message
											*/
											else{
												echo '<p class="duplicate">' . $name . ' with imonggo id ' . $id . '. already exists. Product was neither posted nor updated.</p>';
											}
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
							soft delete (hide) the product in 3dCart as well then prompt a message stating the action that was taken
						*/
						else if ($row[0] != null and $status == "D"){
							$temp_url = $host . '/3dCartWebAPI/v' . $version . '/' . $service . '/' . $row[0];
							$pulled_product = pull_from_3dcart($temp_url, $http_header);
							
							/*
								if the product currently being traversed is not yet hidden in the user's 3dCart store,
								update the product to be hidden; otherwise, do nothing
							*/
							if ($pulled_product->Product[0]->Hide == 'false'){
								$xml = $xml . '
									<Product>
										<SKUInfo>
											<CatalogID>' . $row[0] . '</CatalogID>
										</SKUInfo>
										<Hide>true</Hide>
									</Product>
									';
								echo '<p class="deleted">' . $name . ' with Imonggo ID ' . $id . ' had been hidden in 3dCart for it was deleted in Imonggo.</p>';
							}
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
		
		/* $url_3dCart : 3dCart API URL for products */
		$url_3dcart = $host . '/3dCartWebAPI/v' . $version . '/' . $service;
		
		/* end the $xml by closing its starting tag <ArrayOfProduct> */
		$xml = $xml . '</ArrayOfProduct>';
		
		/* $put_result : Imonggo API response to update multiple 3dCart product state to hidden (if they were deleted in user's Imonggo store) */
		$put_result = put_to_3dcart($url_3dcart, $http_header, $xml);
		
	}
	
?>