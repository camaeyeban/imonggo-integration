<?php

	/******************************* VARIABLE INITIALIZATION *******************************/
	$url = "https://" . $_SESSION['imonggo_account_id'] . ".c3.imonggo.com/api/products.xml";
	$imonggo_products = pull_from_imonggo($url, $_SESSION['imonggo_api_key'], $_SESSION['imonggo_password']);
	$i = 0;
	$tag_list = array();
	
	foreach ($imonggo_products as $imonggo_product){
		$product_tags = explode(',', $imonggo_product->tag_list);
		
		foreach($product_tags as $product_tag){
			if($product_tag != null and $product_tag != "" and !in_array($product_tag, $tag_list)){
				array_push($tag_list, trim($product_tag));
				echo
				'<p>
					<input type="checkbox" class="filled-in tags" name="imonggo_tag_list[]" id="' . $tag_list[$i] . '" value=' . $tag_list[$i] . ' />
					<label for="' . $tag_list[$i] . '">' . $tag_list[$i] . '</label>
				</p>';
				$i++;
			}
		}
	}
	
?>