<?php
class ModelInventoryEbayCron extends Model {
	public function getOrdersRequest($time_from, $time_to) {
		$call_name = 'GetOrders';
		$profile = $this->getEbayProfile();
		$ebay_call = new Ebaycall($profile['developer_id'], $profile['application_id'], $profile['certification_id'], $profile['compat'], $profile['site_id'], $call_name);
		
		$xml = '<?xml version="1.0" encoding="utf-8"?>';
		$xml .= '<GetOrdersRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$xml .= '<RequesterCredentials>';
		$xml .= '<eBayAuthToken>' . $profile['user_token'] . '</eBayAuthToken>';
		$xml .= '</RequesterCredentials>';
		$xml .= '<Pagination ComplexType="PaginationType">';
	    $xml .= '<EntriesPerPage>50</EntriesPerPage>';
		$xml .= '<PageNumber>1</PageNumber>';
		$xml .= '</Pagination>';			
		$xml .= '<WarningLevel>Low</WarningLevel>';
		$xml .= '<OutputSelector>PaginationResult</OutputSelector>';
		$xml .= '<OutputSelector>OrderArray.Order.OrderID</OutputSelector>';
		$xml .= '<OutputSelector>OrderArray.Order.TransactionArray.Transaction.Item.ItemID</OutputSelector>';
		$xml .= '<OutputSelector>OrderArray.Order.TransactionArray.Transaction.Item.Title</OutputSelector>';
		$xml .= '<OutputSelector>OrderArray.Order.TransactionArray.Transaction.QuantityPurchased</OutputSelector>';
		$xml .= '<CreateTimeFrom>' . $time_from . '</CreateTimeFrom>';
		$xml .= '<CreateTimeTo>' . $time_to . '</CreateTimeTo>';
		$xml .= '<OrderRole>Seller</OrderRole>';
		$xml .= '<OrderStatus>Completed</OrderStatus>';
		$xml .= '</GetOrdersRequest>';

		$xml_response = $ebay_call->sendHttpRequest($xml);

		if(stristr($xml_response, 'HTTP 404') || $xml_response == '') {
			$this->language->load('affiliate/stock_control');
	        $response = $this->language->get('error_ebay_api_call');
	        return $response;
        }

        $doc_response = new DomDocument();
        $doc_response->loadXML($xml_response);
        $message = $doc_response->getElementsByTagName('Ack')->item(0)->nodeValue;
        

        if($message == 'Failure') {
        	$severity_code = $doc_response->getElementsByTagName('SeverityCode')->item(0)->nodeValue;
        	$error_code = $doc_response->getElementsByTagName('ErrorCode')->item(0)->nodeValue;
        	$short_message = $doc_response->getElementsByTagName('ShortMessage')->item(0)->nodeValue;
        	$long_message = $doc_response->getElementsByTagName('LongMessage')->item(0)->nodeValue;
	        $response = strtoupper($severity_code) . ': ' . $long_message . ' Error Code: ' . $error_code;
			return $response;
        }

        $titles = $doc_response->getElementsByTagName('Title');
        $item_ids = $doc_response->getElementsByTagName('ItemID');
        $qty_purchased = $doc_response->getElementsByTagName('QuantityPurchased');
        $total_pages = $doc_response->getElementsByTagName('TotalNumberOfPages');
        $page_count = intval($total_pages->item(0)->nodeValue);	        
        $import_data = array();

        foreach ($titles as $title) {
          $import_data['title'][] = $title->nodeValue;
        }

        foreach ($item_ids as $item_id) {
          $import_data['id'][] = $item_id->nodeValue;
        }

        foreach ($qty_purchased as $qty) {
        	$import_data['qty_purchased'][] = $qty->nodeValue;
        }

        if($page_count > 1) {
        	for($i = 2; $i <= $page_count; $i++) {
        		$ebay_call = new Ebaycall($profile['developer_id'], $profile['application_id'], $profile['certification_id'], $profile['compatability_level'], $profile['site_id'], $call_name);
        		
        		$xml = '<?xml version="1.0" encoding="utf-8"?>';
				$xml .= '<GetOrdersRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
				$xml .= '<RequesterCredentials>';
				$xml .= '<eBayAuthToken>' . $profile['user_token'] . '</eBayAuthToken>';
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
				$xml .= '<OutputSelector>OrderArray.Order.TransactionArray.Transaction.Item.Title</OutputSelector>';
				$xml .= '<OutputSelector>OrderArray.Order.TransactionArray.Transaction.QuantityPurchased</OutputSelector>';
				$xml .= '<CreateTimeFrom>' . $time_from .'</CreateTimeFrom>';
				$xml .= '<CreateTimeTo>' . $time_to . '</CreateTimeTo>';
				$xml .= '<OrderRole>Seller</OrderRole>';
				$xml .= '<OrderStatus>Completed</OrderStatus>';
				$xml .= '</GetOrdersRequest>';

				$xml_response = $ebay_call->sendHttpRequest($xml);

				if(stristr($xml_response, 'HTTP 404') || $xml_response == '') {
				   $this->language->load('affiliate/stock_control');
	     		   $response = $this->language->get('error_ebay_api_call');
	        	   return $response;
        		}	

		        $doc_response = new DomDocument();
		        $doc_response->loadXML($xml_response);
		        $message = $doc_response->getElementsByTagName('Ack')->item(0)->nodeValue;
        		
        		if($message == 'Failure') {
        			$severity_code = $doc_response->getElementsByTagName('SeverityCode')->item(0)->nodeValue;
        			$error_code = $doc_response->getElementsByTagName('ErrorCode')->item(0)->nodeValue;
        			$short_message = $doc_response->getElementsByTagName('ShortMessage')->item(0)->nodeValue;
        			$long_message = $doc_response->getElementsByTagName('LongMessage')->item(0)->nodeValue;
	        		$response = strtoupper($severity_code) . ': ' . $long_message . ' Error Code: ' . $error_code;
					return $response;
        		}

		        foreach ($titles as $title) {
             		$import_data['title'][] = $title->nodeValue;
        		}

		        foreach ($item_ids as $item_id) {
		          	$import_data['id'][] = $item_id->nodeValue;
		        }

		        foreach ($quantity_purchased as $quantity) {
		        	$import_data['quantity_purchased'][] = $quantity->nodeValue;
		        }				        		        
		    }
	    }

	    return $import_data;
	}

	public function getEbayProfile($affiliate_id = 0) {
		$sql = "SELECT * FROM " . DB_PREFIX . "ebay_settings WHERE `affiliate_id` = '" . (int)$affiliate_id . "'";
		$query = $this->db->query($sql);
		return $query->row;
	}

	public function setEbayLogMessage($message) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "ebay_log SET message = '" . $this->db->escape($message) . "', log_date = NOW()");
	}

	public function getProductQuantity($product_id) {
		$query = $this->db->query("SELECT quantity FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		return $query->row['quantity'];
	}

	public function setProductQuantity($new_quantity, $product_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '" . (int)$new_quantity . "' WHERE product_id = '" . (int)$product_id . "'");
	}

	public function setProductStatus($new_status, $product_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET status = '" . (int)$new_status . "' WHERE product_id = '" . (int)$product_id . "'");
	}

	public function getProductIdFromEbayListing($ebay_item_id) {
		// NOTE: ebay_item_id is not an (int)
		$query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "ebay_listing WHERE ebay_item_id = '" . $this->db->escape($ebay_item_id) . "'");
		
		if ($query->num_rows > 0) {
			return $query->row['product_id'];
		} else {
			return 0;
		}
	}
} // end class
?>