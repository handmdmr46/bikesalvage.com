<?php
/** Modifications: cron job, designed to be on one hour intervals...
*
*				 Ebay Call:    getOrders()
*                Parameters:   timeFrom: now()-1hour  |  timeTo: now()
*
*
*/
$path = dirname( __FILE__ );
require_once($path . '/ebay_cron_config.php');
require_once(DIR_LIBRARY . '/ebaycall.php');

$log_message = '';

// mysql connect
$connection = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
if (!$connection) {
    $log_message .= 'Connection failed: ' . mysql_error();
}
// mysql db select
$db_selected = mysql_select_db(DB_DATABASE, $connection);
if (!$db_selected) {
    $log_message .= 'Can\'t select database: ' . mysql_error();
}

// get ebay settings
$sql    = "SELECT * FROM oc_ebay_settings WHERE affiliate_id = '0'";
$result = mysql_query($sql, $connection);
$row = mysql_fetch_assoc($result);

$compatability = $row['compat'];
$user_token    = $row['user_token'];
$app_id        = $row['application_id'];
$dev_id        = $row['developer_id'];
$cert_id       = $row['certification_id'];
$site_id       = $row['site_id'];
$affiliate_id  = $row['affiliate_id'];

if (!$result) {
    $log_message .= "DB Error, could not query the database\n";
    $log_message .= 'MySQL Error: ' . mysql_error();
    exit;
}
mysql_free_result($result);

// eBay Call GetOrders
$call_name = 'GetOrders';
$ebay_call = new EbayCall($dev_id, $app_id, $cert_id, $compatability, $site_id, $call_name);

// format output example: 2014-07-29T01:20:07.000Z
date_default_timezone_set('America/Los_Angeles');

$timeFrom = date('Y-m-d\TH:i:s\.', time() - 3800). '000Z'; // 3600 = 1 hour (added 200ms to prevent any chance of overlap times)
$timeTo   = date('Y-m-d\TH:i:s\.') . '000Z';

$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<GetOrdersRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
$xml .= '<RequesterCredentials>';
$xml .= '<eBayAuthToken>' . $user_token . '</eBayAuthToken>';
$xml .= '</RequesterCredentials>';
$xml .= '<Pagination ComplexType="PaginationType">';
$xml .= '<EntriesPerPage>50</EntriesPerPage>';
$xml .= '<PageNumber>1</PageNumber>';
$xml .= '</Pagination>';
$xml .= '<WarningLevel>Low</WarningLevel>';
$xml .= '<OutputSelector>PaginationResult</OutputSelector>';
$xml .= '<OutputSelector>OrderArray.Order.OrderID</OutputSelector>';
$xml .= '<OutputSelector>OrderArray.Order.TransactionArray.Transaction.Item.ItemID</OutputSelector>';
$xml .= '<OutputSelector>OrderArray.Order.TransactionArray.Transaction.QuantityPurchased</OutputSelector>';
$xml .= '<CreateTimeFrom>'. $timeFrom .'</CreateTimeFrom>';
$xml .= '<CreateTimeTo>'. $timeTo .'</CreateTimeTo>';
$xml .= '<OrderRole>Seller</OrderRole>';
$xml .= '<OrderStatus>Completed</OrderStatus>';
$xml .= '</GetOrdersRequest>';

$xml_response = $ebay_call->sendHttpRequest($xml);

if(stristr($xml_response, 'HTTP 404') || $xml_response == '') {
	$log_message .= "ERROR: Request Failed, HTTP 404 or Empty Response";
}

$doc_response = new DomDocument();
$doc_response->loadXML($xml_response);
$message = $doc_response->getElementsByTagName('Ack')->item(0)->nodeValue;

if($message == 'Failure') {
	$severity_code = $doc_response->getElementsByTagName('SeverityCode')->item(0)->nodeValue;
	$error_code = $doc_response->getElementsByTagName('ErrorCode')->item(0)->nodeValue;
	$short_message = $doc_response->getElementsByTagName('ShortMessage')->item(0)->nodeValue;
	$long_message = $doc_response->getElementsByTagName('LongMessage')->item(0)->nodeValue;
    $log_message .= strtoupper($severity_code) . ': ' . $long_message . ' Error Code:' . $error_code . "\n";
}

$ebay_item_ids = $doc_response->getElementsByTagName('ItemID');
$qty_purchased = $doc_response->getElementsByTagName('QuantityPurchased');
$total_pages = $doc_response->getElementsByTagName('TotalNumberOfPages');
$page_count = intval($total_pages->item(0)->nodeValue);

// Check if any orders were returned
if ($ebay_item_ids->length > 0) {
	$log_message .= 'GetOrders TimeStamp: ' . $timeTo . "\n";
	foreach (array_combine($ebay_item_ids, $qty_purchased) as $ebay_item_id => $quantity_purchased) {

	   		$log_message .= 'EBayItemID:' . $ebay_item_id . ' - ';
	   		$log_message .= 'QuantityPurchased:' . $quantity_purchased . ' - ';
	   		// Get Opencart ProductID
	   		$sql = "SELECT product_id FROM " . DB_PREFIX . "ebay_listing WHERE ebay_item_id = '" . (int)$ebay_item_id . "'";
	   		$result = mysql_query($sql, $connection);

	   		if (mysql_num_rows($result) > 0) {

		   		$row = mysql_fetch_assoc($result);
				$oc_product_id = $row['product_id'];
		   		if (!$result) {
		    	  $log_message .= "DB Error, could not query the database\n";
		    	  $log_message .= 'MySQL Error: ' . mysql_error();
		    	  exit;
				}
				mysql_free_result($result);

				// Get Opencart Product Quantity
		   		$sql = "SELECT quantity FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$oc_product_id . "'";
		   		$result = mysql_query($sql, $connection);
		   		$row = mysql_fetch_assoc($result);
		   		$opencart_product_quantity = $row['quantity'];
		   		if (!$result) {
		    	  $log_message .= "DB Error, could not query the database\n";
		    	  $log_message .= 'MySQL Error: ' . mysql_error();
		    	  exit;
				}
				mysql_free_result($result);

				// Stock Control - adjust inventory
				$log_message .= 'ActionTaken:';
		   		$new_opencart_product_quantity = $opencart_product_quantity - $quantity_purchased;
		   		if($new_opencart_product_quantity >= 1) {
		   			$sql = "UPDATE " . DB_PREFIX . "product SET quantity = '" . (int)$new_opencart_product_quantity . "' WHERE product_id = '" . (int)$oc_product_id . "'";
		   			mysql_query($sql, $connection);
		   			$log_message .= 'ProductID = ' . $oc_product_id . ' SET NewQuantity = ' . $new_opencart_product_quantity . "\n";
		   		}
		   		if($new_opencart_product_quantity < 1) {
		   			$sql = "UPDATE " . DB_PREFIX . "product SET status = '0' WHERE product_id = '" . (int)$oc_product_id . "'";
		   			mysql_query($sql, $connection);
		   			$log_message .= 'ProductID = ' . $oc_product_id . ' NewQuantity = 0 SET Status = Not Avtive(0)' .  "\n";
		   		}

		   	} else {
	   			$log_message .= 'NO MATCH FOUND: EBayItemID = ' . $ebay_item_id . "\n";
	   		}
	}

	// eBay Call GetOrders - High Volume = over 50 per page
	if($page_count > 1) {
		for($i = 2; $i <= $page_count; $i++) {
			$ebay_call = new Ebaycall($profile['developer_id'], $profile['application_id'], $profile['certification_id'], $profile['compatability_level'], $profile['site_id'], $call_name);

			$xml = '<?xml version="1.0" encoding="utf-8"?>';
			$xml .= '<GetOrdersRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
			$xml .= '<RequesterCredentials>';
			$xml .= '<eBayAuthToken>' . $user_token . '</eBayAuthToken>';
			$xml .= '</RequesterCredentials>';
			$xml .= '<Pagination ComplexType="PaginationType">';
		    $xml .= '<EntriesPerPage>50</EntriesPerPage>';
			$xml .= '<PageNumber>' . $i . '</PageNumber>';
			$xml .= '</Pagination>';
			$xml .= '<DetailLevel>ReturnAll</DetailLevel>';
			$xml .= '<WarningLevel>Low</WarningLevel>';
			$xml .= '<OutputSelector>PaginationResult</OutputSelector>';
			$xml .= '<OutputSelector>OrderArray.Order.OrderID</OutputSelector>';
			$xml .= '<OutputSelector>OrderArray.Order.TransactionArray.Transaction.Item.ItemID</OutputSelector>';
			$xml .= '<OutputSelector>OrderArray.Order.TransactionArray.Transaction.QuantityPurchased</OutputSelector>';
			$xml .= '<CreateTimeFrom>'. $timeFrom .'</CreateTimeFrom>';
			$xml .= '<CreateTimeTo>'. $timeTo .'</CreateTimeTo>';
			$xml .= '<OrderRole>Seller</OrderRole>';
			$xml .= '<OrderStatus>Completed</OrderStatus>';
			$xml .= '</GetOrdersRequest>';

			$xml_response = $ebay_call->sendHttpRequest($xml);

			if(stristr($xml_response, 'HTTP 404') || $xml_response == '') {
				$log_message .= "ERROR: Request Failed, HTTP 404 or Empty Response";
			}

	        $doc_response = new DomDocument();
	        $doc_response->loadXML($xml_response);
	        $message = $doc_response->getElementsByTagName('Ack')->item(0)->nodeValue;

			if($message == 'Failure') {
				$severity_code = $doc_response->getElementsByTagName('SeverityCode')->item(0)->nodeValue;
				$error_code = $doc_response->getElementsByTagName('ErrorCode')->item(0)->nodeValue;
				$short_message = $doc_response->getElementsByTagName('ShortMessage')->item(0)->nodeValue;
				$long_message = $doc_response->getElementsByTagName('LongMessage')->item(0)->nodeValue;
	    		$log_message .= strtoupper($severity_code) . ': ' . $long_message . ' Error Code: ' . $error_code;
			}

			foreach (array_combine($ebay_item_ids, $qty_purchased) as $ebay_item_id => $quantity_purchased) {

		   		$log_message .= 'EBayItemID:' . $ebay_item_id . ' - ';
	   		    $log_message .= 'QuantityPurchased:' . $quantity_purchased . ' - ';
		   		// Get Opencart ProductID
		   		$sql = "SELECT product_id FROM " . DB_PREFIX . "ebay_listing WHERE ebay_item_id = '" . (int)$ebay_item_id . "'";
		   		$result = mysql_query($sql, $connection);
		   		if (!$result) {
			    	  $log_message .= "DB Error, could not query the database\n";
			    	  $log_message .= 'MySQL Error: ' . mysql_error();
			    	  exit;
				}
		   		if (mysql_num_rows($result) > 0) {

			   		$row = mysql_fetch_assoc($result);
					$oc_product_id = $row['product_id'];			   		
					mysql_free_result($result);

					// Get Opencart Product Quantity
			   		$sql = "SELECT quantity FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$oc_product_id . "'";
			   		$result = mysql_query($sql, $connection);
			   		$row = mysql_fetch_assoc($result);
			   		$opencart_product_quantity = $row['quantity'];
			   		if (!$result) {
			    	  $log_message .= "DB Error, could not query the database\n";
			    	  $log_message .= 'MySQL Error: ' . mysql_error();
			    	  exit;
					}
					mysql_free_result($result);

					// Stock Control - adjust inventory
					$log_message .= 'ActionTaken:';
		   		$new_opencart_product_quantity = $opencart_product_quantity - $quantity_purchased;
		   		if($new_opencart_product_quantity >= 1) {
		   			$sql = "UPDATE " . DB_PREFIX . "product SET quantity = '" . (int)$new_opencart_product_quantity . "' WHERE product_id = '" . (int)$oc_product_id . "'";
		   			mysql_query($sql, $connection);
		   			$log_message .= 'ProductID = ' . $oc_product_id . ' SET NewQuantity = ' . $new_opencart_product_quantity . "\n";
		   		}
		   		if($new_opencart_product_quantity < 1) {
		   			$sql = "UPDATE " . DB_PREFIX . "product SET status = '0' WHERE product_id = '" . (int)$oc_product_id . "'";
		   			mysql_query($sql, $connection);
		   			$log_message .= 'ProductID = ' . $oc_product_id . ' NewQuantity = 0 SET Status = Not Avtive(0)' .  "\n";
		   		}

			   	} else {
		   			$log_message .= 'NO MATCH FOUND: EBayItemID = ' . $ebay_item_id . "\n";
		   		}
			}


	    }
	}

} else {
	$log_message .= 'NO ORDERS PRESENT AT THIS TIME: TimeStamp - ' . $timeTo . "\n";
}

// Write To Log File
$file = DIR_LOGS . 'ebay_cron_log.txt';
file_put_contents($file, $log_message, FILE_APPEND | LOCK_EX);
mysql_close($connection);


?>