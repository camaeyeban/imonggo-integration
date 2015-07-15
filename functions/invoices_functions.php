<?php

	/******************************* PUSH INVOICES TO IMONGGO *******************************/
	function push_invoices_to_imonggo($username, $password, $domain){
	
		$query = "SELECT * FROM last_invoice_posting";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		
		$date_time = date('Y-m-d\TH:i:s', time());
		
		if(!$row){
			echo "Invoices posted from the beginning until " . $date_time . "<br>";
			$insert_to_last_posting = mysql_query("
				INSERT INTO last_invoice_posting (id, date) VALUES(DEFAULT, '$date_time') 
			");
			$insert_to_invoices = mysql_query("
				INSERT INTO invoices (post_id, post_date) VALUES (DEFAULT, '$date_time')
			");
		}
		
		else{
			echo "Invoices posted from " . $row[1] . " to " . $date_time . "<br>";
			$update_last_posting = mysql_query("
				UPDATE last_invoice_posting SET date = '$date_time' WHERE id='$row[0]'
			");
			$insert_to_invoices = mysql_query("
				INSERT INTO invoices (post_id, post_date) VALUES (DEFAULT, '$date_time')
			");
		}
		
		$url = "https://" . $domain . ".c3.imonggo.com/api/invoices.xml";
		$xml = 
			'<?xml version="1.0" encoding="UTF-8"?>
			<invoice>
				<invoice_date>2011-04-01T00:00:00Z</invoice_date>
				<invoice_lines type="array">
					<invoice_line>
						<product_id>951418</product_id>
						<quantity>1</quantity>
						<retail_price>100</retail_price>
					</invoice_line>
				</invoice_lines>
				<payments type="array">
					<payment>
						<amount>100</amount>
					</payment>
				</payments>
			</invoice>';
		post_to_imonggo($url, $username, $password, $xml);
		
	}
	
	
	/******************************* PULL INVOICES FROM 3DCART ********************************/
	function pull_invoices_from_3dcart($host, $version, $http_header){
		
		$query = "SELECT * FROM last_invoice_posting";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		
		$service = 'Orders';
		$url = $host . '/3dCartWebAPI/v' . $version . '/' . $service;
		$invoices_of_3dcart = pull_from_3dcart($url, $http_header);
		
		foreach ($invoices_of_3dcart as $invoice_of_3dcart){
			if (strtotime($invoice_of_3dcart->OrderDate) > strtotime($row[1])){
				$InvoiceNumberPrefix = $invoice_of_3dcart->InvoiceNumberPrefix;
				$InvoiceNumber = $invoice_of_3dcart->InvoiceNumber;
				$OrderID = $invoice_of_3dcart->OrderID;
				$CustomerID = $invoice_of_3dcart->CustomerID;
				$OrderDate = $invoice_of_3dcart->OrderDate;
				$OrderStatusID = $invoice_of_3dcart->OrderStatusID;
				$LastUpdate = $invoice_of_3dcart->LastUpdate;
				$UserID = $invoice_of_3dcart->UserID;
				$SalesPerson = $invoice_of_3dcart->SalesPerson;
				$ContinueURL = $invoice_of_3dcart->ContinueURL;
				$BillingFirstName = $invoice_of_3dcart->BillingFirstName;
				$BillingLastName = $invoice_of_3dcart->BillingLastName;
				$BillingCompany = $invoice_of_3dcart->BillingCompany;
				$BillingAddress = $invoice_of_3dcart->BillingAddress;
				$BillingAddress2 = $invoice_of_3dcart->BillingAddress2;
				$BillingCity = $invoice_of_3dcart->BillingCity;
				$BillingState = $invoice_of_3dcart->BillingState;
				$BillingZipCode = $invoice_of_3dcart->BillingZipCode;
				$BillingCountry = $invoice_of_3dcart->BillingCountry;
				$BillingPhoneNumber = $invoice_of_3dcart->BillingPhoneNumber;
				$BillingEmail = $invoice_of_3dcart->BillingEmail;
				$BillingPaymentMethod = $invoice_of_3dcart->BillingPaymentMethod;
				$BillingOnLinePayment = $invoice_of_3dcart->BillingOnLinePayment;
				$BillingPaymentMethodID = $invoice_of_3dcart->BillingPaymentMethodID;
				
				echo "InvoiceNumberPrefix: " . $InvoiceNumberPrefix . "<br>";
				echo "InvoiceNumber: " . $InvoiceNumber . "<br>";
				echo "OrderID: " . $InvoiceNumberPrefix . "<br>";
				echo "CustomerID: " . $CustomerID . "<br>";
				echo "OrderDate: " . $OrderDate . "<br>";
				echo "OrderStatusID: " . $OrderStatusID . "<br>";
				echo "LastUpdate: " . $LastUpdate . "<br>";
				echo "UserID: " . $UserID . "<br>";
				echo "SalesPerson: " . $SalesPerson . "<br>";
				echo "ContinueURL: " . $ContinueURL . "<br>";
				echo "BillingFirstName: " . $BillingFirstName . "<br>";
				echo "BillingLastName: " . $BillingLastName . "<br>";
				echo "BillingCompany: " . $BillingCompany . "<br>";
				echo "BillingAddress: " . $BillingAddress . "<br>";
				echo "BillingAddress2: " . $BillingAddress2 . "<br>";
				echo "BillingCity: " . $BillingCity . "<br>";
				echo "BillingState: " . $BillingState . "<br>";
				echo "BillingZipCode: " . $BillingZipCode . "<br>";
				echo "BillingCountry: " . $BillingCountry . "<br>";
				echo "BillingPhoneNumber: " . $BillingPhoneNumber . "<br>";
				echo "BillingEmail: " . $BillingEmail . "<br>";
				echo "BillingPaymentMethod: " . $BillingPaymentMethod . "<br>";
				echo "BillingOnLinePayment: " . $BillingOnLinePayment . "<br>";
				echo "BillingPaymentMethodID: " . $BillingPaymentMethodID . "<br>";
				
				foreach ($invoice_of_3dcart->ShipmentList as $invoice_shipment_of_3dcart){
					$ShipmentID = $invoice_shipment_of_3dcart->ShipmentID;
					$ShipmentLastUpdate = $invoice_shipment_of_3dcart->ShipmentLastUpdate;
					$ShipmentBoxes = $invoice_shipment_of_3dcart->ShipmentBoxes;
					$ShipmentInternalComment = $invoice_shipment_of_3dcart->ShipmentInternalComment;
					$ShipmentOrderStatus = $invoice_shipment_of_3dcart->ShipmentOrderStatus;
					$ShipmentAddress = $invoice_shipment_of_3dcart->ShipmentAddress;
					$ShipmentAddress2 = $invoice_shipment_of_3dcart->ShipmentAddress2;
					$ShipmentAlias = $invoice_shipment_of_3dcart->ShipmentAlias;
					$ShipmentCity = $invoice_shipment_of_3dcart->ShipmentCity;
					$ShipmentCompany = $invoice_shipment_of_3dcart->ShipmentCompany;
					$ShipmentCost = $invoice_shipment_of_3dcart->ShipmentCost;
					$ShipmentCountry = $invoice_shipment_of_3dcart->ShipmentCountry;
					$ShipmentEmail = $invoice_shipment_of_3dcart->ShipmentEmail;
					$ShipmentFirstName = $invoice_shipment_of_3dcart->ShipmentFirstName;
					$ShipmentLastName = $invoice_shipment_of_3dcart->ShipmentLastName;
					$ShipmentMethodID = $invoice_shipment_of_3dcart->ShipmentMethodID;
					$ShipmentMethodName = $invoice_shipment_of_3dcart->ShipmentMethodName;
					$ShipmentShippedDate = $invoice_shipment_of_3dcart->ShipmentShippedDate;
					$ShipmentPhone = $invoice_shipment_of_3dcart->ShipmentPhone;
					$ShipmentState = $invoice_shipment_of_3dcart->ShipmentState;
					$ShipmentZipCode = $invoice_shipment_of_3dcart->ShipmentZipCode;
					$ShipmentTax = $invoice_shipment_of_3dcart->ShipmentTax;
					$ShipmentWeight = $invoice_shipment_of_3dcart->ShipmentWeight;
					$ShipmentTrackingCode = $invoice_shipment_of_3dcart->ShipmentTrackingCode;
					$ShipmentUserID = $invoice_shipment_of_3dcart->ShipmentUserID;
					$ShipmentNumber = $invoice_shipment_of_3dcart->ShipmentNumber;
					$ShipmentAddressTypeID = $invoice_shipment_of_3dcart->ShipmentAddressTypeID;
					
					echo "ShipmentID: " . $ShipmentID . "<br>";
					echo "ShipmentLastUpdate: " . $ShipmentLastUpdate . "<br>";
					echo "ShipmentBoxes: " . $ShipmentBoxes . "<br>";
					echo "ShipmentInternalComment: " . $ShipmentInternalComment . "<br>";
					echo "ShipmentOrderStatus: " . $ShipmentOrderStatus . "<br>";
					echo "ShipmentAddress: " . $ShipmentAddress . "<br>";
					echo "ShipmentAddress2: " . $ShipmentAddress2 . "<br>";
					echo "ShipmentAlias: " . $ShipmentAlias . "<br>";
					echo "ShipmentCity: " . $ShipmentCity . "<br>";
					echo "ShipmentCompany: " . $ShipmentCompany . "<br>";
					echo "ShipmentCost: " . $ShipmentCost . "<br>";
					echo "ShipmentCountry: " . $ShipmentCountry . "<br>";
					echo "ShipmentEmail: " . $ShipmentEmail . "<br>";
					echo "ShipmentFirstName: " . $ShipmentFirstName . "<br>";
					echo "ShipmentLastName: " . $ShipmentLastName . "<br>";
					echo "ShipmentMethodID: " . $ShipmentMethodID . "<br>";
					echo "ShipmentMethodName: " . $ShipmentMethodName . "<br>";
					echo "ShipmentShippedDate: " . $ShipmentShippedDate . "<br>";
					echo "ShipmentPhone: " . $ShipmentPhone . "<br>";
					echo "ShipmentState: " . $ShipmentState . "<br>";
					echo "ShipmentZipCode: " . $ShipmentZipCode . "<br>";
					echo "ShipmentTax: " . $ShipmentTax . "<br>";
					echo "ShipmentWeight: " . $ShipmentWeight . "<br>";
					echo "ShipmentTrackingCode: " . $ShipmentTrackingCode . "<br>";
					echo "ShipmentUserID: " . $ShipmentUserID . "<br>";
					echo "ShipmentNumber: " . $ShipmentNumber . "<br>";
					echo "ShipmentID: " . $ShipmentID . "<br>";
					echo "ShipmentID: " . $ShipmentID . "<br>";
					echo "ShipmentID: " . $ShipmentID . "<br>";
					echo "ShipmentAddressTypeID: " . $ShipmentAddressTypeID . "<br>";
					echo "<br><br><br>";
				}
				
				foreach ($invoice_of_3dcart->OrderItemList as $invoice_order_item_of_3dcart){
					$CatalogID = $invoice_order_item_of_3dcart->CatalogID;
					$ItemIndexID = $invoice_order_item_of_3dcart->ItemIndexID;
					$ItemID = $invoice_order_item_of_3dcart->ItemID;
					$ItemShipmentID = $invoice_order_item_of_3dcart->ItemShipmentID;
					$ItemQuantity = $invoice_order_item_of_3dcart->ItemQuantity;
					$ItemWarehouseID = $invoice_order_item_of_3dcart->ItemWarehouseID;
					$ItemDescription = $invoice_order_item_of_3dcart->ItemDescription;
					$ItemUnitPrice = $invoice_order_item_of_3dcart->ItemUnitPrice;
					$ItemWeight = $invoice_order_item_of_3dcart->ItemWeight;
					$ItemOptionPrice = $invoice_order_item_of_3dcart->ItemOptionPrice;
					$ItemAdditionalField1 = $invoice_order_item_of_3dcart->ItemAdditionalField1;
					$ItemAdditionalField2 = $invoice_order_item_of_3dcart->ItemAdditionalField2;
					$ItemAdditionalField3 = $invoice_order_item_of_3dcart->ItemAdditionalField3;
					$ItemPageAdded = $invoice_order_item_of_3dcart->ItemPageAdded;
					$ItemDateAdded = $invoice_order_item_of_3dcart->ItemDateAdded;
					$ItemUnitCost = $invoice_order_item_of_3dcart->ItemUnitCost;
					$ItemUnitStock = $invoice_order_item_of_3dcart->ItemUnitStock;
					$ItemOptions = $invoice_order_item_of_3dcart->ItemOptions;
					$ItemCatalogIDOptions = $invoice_order_item_of_3dcart->ItemCatalogIDOptions;
				
					echo "CatalogID: " . $CatalogID . "<br>";
					echo "ItemIndexID: " . $ItemIndexID . "<br>";
					echo "ItemID: " . $ItemID . "<br>";
					echo "ItemShipmentID: " . $ItemShipmentID . "<br>";
					echo "ItemQuantity: " . $ItemQuantity . "<br>";
					echo "ItemWarehouseID: " . $ItemWarehouseID . "<br>";
					echo "ItemDescription: " . $ItemDescription . "<br>";
					echo "ItemUnitPrice: " . $ItemUnitPrice . "<br>";
					echo "ItemWeight: " . $ItemWeight . "<br>";
					echo "ItemOptionPrice: " . $ItemOptionPrice . "<br>";
					echo "ItemAdditionalField1: " . $ItemAdditionalField1 . "<br>";
					echo "ItemAdditionalField2: " . $ItemAdditionalField2 . "<br>";
					echo "ItemAdditionalField3: " . $ItemAdditionalField3 . "<br>";
					echo "ItemPageAdded: " . $ItemPageAdded . "<br>";
					echo "ItemDateAdded: " . $ItemDateAdded . "<br>";
					echo "ItemUnitCost: " . $ItemUnitCost . "<br>";
					echo "ItemUnitStock: " . $ItemUnitStock . "<br>";
					echo "ItemOptions: " . $ItemOptions . "<br>";
					echo "ItemCatalogIDOptions: " . $ItemCatalogIDOptions . "<br>";
					echo "<br><br><br>";
				}
				
				$OrderDiscount = $invoice_of_3dcart->OrderDiscount;
				$SalesTax = $invoice_of_3dcart->SalesTax;
				$SalesTax2 = $invoice_of_3dcart->SalesTax2;
				$SalesTax3 = $invoice_of_3dcart->SalesTax3;
				$OrderAmount = $invoice_of_3dcart->OrderAmount;
				$AffiliateCommission = $invoice_of_3dcart->AffiliateCommission;
				$TransactionList = $invoice_of_3dcart->TransactionList;
				$CardType = $invoice_of_3dcart->CardType;
				$CardNumber = $invoice_of_3dcart->CardNumber;
				$CardName = $invoice_of_3dcart->CardName;
				$CardExpirationMonth = $invoice_of_3dcart->CardExpirationMonth;
				$CardExpirationYear = $invoice_of_3dcart->CardExpirationYear;
				$CardIssueNumber = $invoice_of_3dcart->CardIssueNumber;
				$CardStartMonth = $invoice_of_3dcart->CardStartMonth;
				$CardStartYear = $invoice_of_3dcart->CardStartYear;
				$CardAddress = $invoice_of_3dcart->CardAddress;
				$CardVerification = $invoice_of_3dcart->CardVerification;
				$RewardPoints = $invoice_of_3dcart->RewardPoints;
				$QuestionList = $invoice_of_3dcart->QuestionList;
				$Referer = $invoice_of_3dcart->Referer;
				$IP = $invoice_of_3dcart->IP;
				$CustomerComments = $invoice_of_3dcart->CustomerComments;
				$InternalComments = $invoice_of_3dcart->InternalComments;
				$ExternalComments = $invoice_of_3dcart->ExternalComments;
				
				echo "OrderDiscount: " . $OrderDiscount . "<br>";
				echo "SalesTax: " . $SalesTax . "<br>";
				echo "SalesTax2: " . $SalesTax2 . "<br>";
				echo "SalesTax3: " . $SalesTax3 . "<br>";
				echo "OrderAmount: " . $OrderAmount . "<br>";
				echo "AffiliateCommission: " . $AffiliateCommission . "<br>";
				echo "TransactionList: " . $TransactionList . "<br>";
				echo "CardType: " . $CardType . "<br>";
				echo "CardNumber: " . $CardNumber . "<br>";
				echo "CardName: " . $CardName . "<br>";
				echo "CardExpirationMonth: " . $CardExpirationMonth . "<br>";
				echo "CardExpirationYear: " . $CardExpirationYear . "<br>";
				echo "CardIssueNumber: " . $CardIssueNumber . "<br>";
				echo "CardStartMonth: " . $CardStartMonth . "<br>";
				echo "CardStartYear: " . $CardStartYear . "<br>";
				echo "CardAddress: " . $CardAddress . "<br>";
				echo "CardVerification: " . $CardVerification . "<br>";
				echo "RewardPoints: " . $RewardPoints . "<br>";
				echo "QuestionList: " . $QuestionList . "<br>";
				echo "Referer: " . $Referer . "<br>";
				echo "IP: " . $IP . "<br>";
				echo "CustomerComments: " . $CustomerComments . "<br>";
				echo "InternalComments: " . $InternalComments . "<br>";
				echo "ExternalComments: " . $ExternalComments . "<br>";
				echo "<br><br><br>";
			}
		}
		
	}
		/*
		<orderid> </orderid>
		<invoicenumber> </invoicenumber>
		<orderstatus> </orderstatus>
		<datestart> </datestart>
		<dateend> </dateend>
	*/
	
?>