<?php

	/*
		File name : get_tags.php
		Description :
			This file contains the function for getting all tags used by the user's Imonggo products.
	*/
	
	/* $url : Imonggo API URL for fetching user's Imonggo products */
	$url = "https://" . $_SESSION['imonggo_account_id'] . ".c3.imonggo.com/api/products.xml";
	
	/* pull all of the user's Imonggo products */
	$imonggo_products = pull_from_imonggo($url, $_SESSION['imonggo_api_key'], $_SESSION['imonggo_password']);
	
	/* $i : checkbox counter and checkbox name index */
	$i = 0;
	
	/* $tag_list : array of unique product tags found in all of the user's Imonggo products */
	$tag_list = array();
	
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
				echo
				'<p>
					<input type="checkbox" class="filled-in tags" name="imonggo_tag_list[]" id="' . $tag_list[$i] . '" value=' . $tag_list[$i] . ' />
					<label for="' . $tag_list[$i] . '">' . $tag_list[$i] . '</label>
				</p>';
				
				/* increment checkbox count */
				$i++;
			}
		}
	}
	
?>