<?php
	
	/**************************** PULL INVENTORY LEVELS FROM IMONGGO ****************************/
	function pull_imonggo_inventories($username, $password, $domain){
		
		$url = "https://" . $domain . ".c3.imonggo.com/api/inventories.xml";
		$imonggo_inventories = pull_from_imonggo($url, $username, $password);
		$imonggo_inventories_count = pull_from_imonggo($url . "?q=count", $username, $password);
	
		for($i=0; $i<$imonggo_inventories_count->count; $i++){
			$branch_id = $imonggo_inventories->inventory[$i]->branch_id;
			$inventory_id = $imonggo_inventories->inventory[$i]->inventory_id;
			$quantity = $imonggo_inventories->inventory[$i]->quantity;
			$stock_no = $imonggo_inventories->inventory[$i]->stock_no;
			$utc_created_at = $imonggo_inventories->inventory[$i]->utc_created_at;
			$utc_updated_at = $imonggo_inventories->inventory[$i]->utc_updated_at;
			
			echo "branch_id: " . $branch_id . "<br>";
			echo "inventory_id: " . $inventory_id . "<br>";
			echo "quantity: " . $quantity . "<br>";
			echo "stock_no: " . $stock_no . "<br>";
			echo "utc_created_at: " . $utc_created_at . "<br>";
			echo "utc_updated_at: " . $utc_updated_at . "<br>";
			echo "<br><br><br>";
		}

	}

?>