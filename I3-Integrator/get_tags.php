<?php

	/*
		File name : get_tags.php
		Description :
			This file contains the function for getting all tags used by the user's Imonggo products.
	*/
	
	
	/* get the date of the last time the user posted his/her products from the database */
	$query = "SELECT * FROM last_product_posting";
	$result = mysql_query($query);
	$last_posting_date = mysql_fetch_array($result);
	
	/* $i : checkbox counter and checkbox name index */
	$i = 0;
	
	/* $tag_list : array of unique product tags found in all of the user's Imonggo products */
	$tag_list = array();
	
	/*
		variable initialization for pulling Imonggo products of the user
		$page: current page being traversed while pulling Imonggo products of the user
		$products_per_page : number of Imonggo products to be pulled per page
	*/
	$page = 0;
	$products_per_page = 50;
	$checkboxes = "";
	
	/* fetch 50 products every page until all Imonggo products have been traversed */
	do{
		/* increment page number */
		$page++;
		
		/* $url : Imonggo API URL for fetching user's Imonggo products */
		$url = "https://" . $_SESSION['imonggo_account_id'] . ".c3.imonggo.com/api/products.xml?page=" . $page . "&per_page=" . $products_per_page;
		
		/* pull all of the user's Imonggo products */
		$imonggo_products = pull_from_imonggo($url, $_SESSION['imonggo_api_key'], $_SESSION['imonggo_password']);
	
		/* traverse each Imonggo products */
		foreach ($imonggo_products as $imonggo_product){
			/* $product_tags : array of all the tags of the currently being traversed product */
			$product_tags = explode(',', $imonggo_product->tag_list);
			
			/* traverse each of the tags in $product_tags */
			foreach($product_tags as $product_tag){
				/*
					$product_tag != null				: the product has a tag
					$product_tag != ""					: the tag of the product is not an empty string
					!in_array($product_tag, $tag_list)	: the tag is not yet pushed to $tag_list
				*/
				if($product_tag != null and $product_tag != "" and !in_array($product_tag, $tag_list)){
					/* add the tag to $tag_list */
					array_push($tag_list, trim($product_tag));
					
					/* show the tag as a checkbox in the homepage */
					$checkboxes .= 
					'<p>
						<input type="checkbox" class="filled-in tags" name="imonggo_tag_list[]" id="' . $tag_list[$i] . '" value=' . $tag_list[$i] . ' />
						<label for="' . $tag_list[$i] . '">' . $tag_list[$i] . '</label>
					</p>';
					
					/* increment checkbox count */
					$i++;
				}
			}
		}
	}while($imonggo_products->product[0] != null);
	
	if($tag_list != null){
		echo '
			<hr/>
			<p>
				<input type="checkbox" name="no_tags" class="filled-in" id="no-tags" />
				<label for="no-tags">No Tags</label>
			</p>
			<p>
				<input type="checkbox" class="filled-in" id="check_all_tags" />
				<label for="check_all_tags">Select/Deselect All Tags</label>
			</p>
			<hr/>
		';
		echo $checkboxes;
	}
	
	else{
		echo "<hr><p class='red-text'>All your Imonggo products has no filter.</p>";
	}
?>