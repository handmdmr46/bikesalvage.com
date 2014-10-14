<?php
class ModelInventoryStockControl extends Model {
	/**
	* Controls inventory for products hosted on both OpenCart and eBay
	*
	*/	
	public function getEbayProfile($affiliate_id = 0) {
		$sql = "SELECT * FROM " . DB_PREFIX . "ebay_settings WHERE `affiliate_id` = '" . (int)$affiliate_id . "'";
		$query = $this->db->query($sql);
		return $query->row;
	}

	public function setEbayProfile($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "ebay_settings WHERE affiliate_id = '0'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "ebay_settings
						  SET		  `compat` = '" . (int)$data['compatability_level'] . "',
									  `user_token` = '" . $this->db->escape($data['user_token']) . "',
									  `application_id` = '" . $this->db->escape($data['application_id']) . "',
									  `developer_id` = '" . $this->db->escape($data['developer_id']) . "',
									  `certification_id` = '" . $this->db->escape($data['certification_id']) . "',
									  `site_id` = '" . $this->db->escape($data['site_id']) . "',
									  `affiliate_id` = '0'");
	}
	
	public function getEbayCompatibilityLevels() {
		$sql = "SELECT * FROM " . DB_PREFIX . "ebay_compatibility ORDER BY id DESC";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getEbaySiteIds() {
		$sql = "SELECT * FROM " . DB_PREFIX . "ebay_site_ids";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getEbayCallNames() {
		$call_names = array('getOrders', 'getItemQuantity', 'endFixedPriceItem', 'reviseInventoryStatus');
		return $call_names;
	}

	public function getTotalLinkedProducts($data= array()) {
		$sql = "SELECT COUNT(DISTINCT el.product_id) AS total FROM " . DB_PREFIX . "ebay_listing el LEFT JOIN " . DB_PREFIX . "product_description pd ON (el.product_id = pd.product_id)";
		
		$sql .= " WHERE affiliate_id = '0'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalUnlinkedProducts($data= array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
		
		$sql .= " WHERE `linked` = '0' AND `affiliate_id` = '0' AND `status` = '0'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getLinkedProducts($data= array()) {
		$sql = "SELECT    el.product_id,
	  			  		  el.ebay_item_id,
          		  		  pd.name
				FROM      " . DB_PREFIX . "ebay_listing el
				LEFT JOIN " . DB_PREFIX . "product_description pd ON (el.product_id = pd.product_id)
				WHERE     el.affiliate_id = '0'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .=	" ORDER BY pd.name DESC";

		if(isset($data['start']) || isset($data['limit'])) {
			if($data['start'] < 0) {
				$data['start'] = 0;
			}
			if($data['limit'] < 1) {
				$data['limit'] = 20;
			}

		    $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getUnlinkedProducts($data = array()) {
		$sql = "SELECT    * 
		        FROM      " . DB_PREFIX . "product p 
		        LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
		        WHERE     p.affiliate_id = '0'
		        AND       p.linked = '0'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .=	" ORDER BY pd.name DESC";

		if(isset($data['start']) || isset($data['limit'])) {
			if($data['start'] < 0) {
				$data['start'] = 0;
			}
			if($data['limit'] < 1) {
				$data['limit'] = 20;
			}

		    $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function setLinkedProductEbayItemId($product_id, $ebay_id) {
		if (isset($ebay_id) ) {			
			$this->db->query("UPDATE " . DB_PREFIX . "ebay_listing SET ebay_item_id = '" . $this->db->escape($ebay_id) . "' WHERE product_id = '" . $this->db->escape($product_id) . "'");
		}
	}

	public function setProductLink($product_id, $ebay_id) {
		if (isset($ebay_id) ) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET linked = 1, status = 1 WHERE product_id = '" . $this->db->escape($product_id) . "'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "ebay_listing SET ebay_item_id = '" . $this->db->escape($ebay_id) . "', product_id = '" . $this->db->escape($product_id) . "', affiliate_id = '0'");
		}
	}

	public function removeProductLink($product_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET linked = 0, status = 0 WHERE product_id = '" . $this->db->escape($product_id) . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ebay_listing WHERE product_id = '" . $this->db->escape($product_id) . "'");
	}

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

	public function getSellerList($time_from, $time_to) {
		$call_name = 'GetSellerList';
		$profile = $this->getEbayProfile();
		$ebay_call = new Ebaycall($profile['developer_id'], $profile['application_id'], $profile['certification_id'], $profile['compat'], $profile['site_id'], $call_name);

		$xml  = '<?xml version="1.0" encoding="utf-8"?>';
        $xml .= '<GetSellerListRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
      	$xml .= '<RequesterCredentials>';
      	$xml .= '<eBayAuthToken>' . $profile['user_token'] . '</eBayAuthToken>';
      	$xml .= '</RequesterCredentials>';
      	$xml .= '<Pagination ComplexType="PaginationType">';
      	$xml .= '<EntriesPerPage>200</EntriesPerPage>';
      	$xml .= '<PageNumber>1</PageNumber>';
      	$xml .= '</Pagination>';
      	$xml .= '<GranularityLevel>Coarse</GranularityLevel>';
      	$xml .= '<StartTimeFrom>' . $time_from . '</StartTimeFrom>';
      	$xml .= '<StartTimeTo>' . $time_to . '</StartTimeTo>';
      	$xml .= '<WarningLevel>Low</WarningLevel>';
      	$xml .= '<OutputSelector>PaginationResult</OutputSelector>';
      	$xml .= '<OutputSelector>ItemArray.Item.Title</OutputSelector>';
      	$xml .= '<OutputSelector>ItemArray.Item.ItemID</OutputSelector>';
      	$xml .= '</GetSellerListRequest>';

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
	    $number_pages = $doc_response->getElementsByTagName('TotalNumberOfPages');
	    $number_entries = $doc_response->getElementsByTagName('TotalNumberOfEntries');
	    $import_data = array();

	    foreach ($titles as $title) {
	      $import_data['title'][] = $title->nodeValue;
	    }

	    foreach ($item_ids as $item_id) {
	      $import_data['id'][] = $item_id->nodeValue;
	    }

	    $page_count = intval($number_pages->item(0)->nodeValue);
	    $total_entries = intval($number_entries->item(0)->nodeValue);

	    if($page_count > 1) {
	        for($i = 2; $i <= $page_count; $i++) {

	          $xml  = '<?xml version="1.0" encoding="utf-8"?>';
	          $xml .= '<GetSellerListRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
	          $xml .= '<RequesterCredentials>';
	          $xml .= '<eBayAuthToken>' . $profile['user_token'] . '</eBayAuthToken>';
	          $xml .= '</RequesterCredentials>';
	          $xml .= '<Pagination ComplexType="PaginationType">';
	          $xml .= '<EntriesPerPage>200</EntriesPerPage>';
	          $xml .= '<PageNumber>' . $i . '</PageNumber>';
	          $xml .= '</Pagination>';
	          $xml .= '<GranularityLevel>Coarse</GranularityLevel>';
	          $xml .= '<StartTimeFrom>' . $time_from . '</StartTimeFrom>';
      	   	  $xml .= '<StartTimeTo>' . $time_to . '</StartTimeTo>';
	          $xml .= '<WarningLevel>Low</WarningLevel>';
	          $xml .= '<OutputSelector>PaginationResult</OutputSelector>';
	          $xml .= '<OutputSelector>ItemArray.Item.Title</OutputSelector>';
	          $xml .= '<OutputSelector>ItemArray.Item.ItemID</OutputSelector>';
	          $xml .= '</GetSellerListRequest>';

	          $xml_response = $ebay_call->sendHttpRequest($xml);

			  if(stristr($xml_response, 'HTTP 404') || $xml_response == '') {
				$this->language->load('affiliate/stock_control');
		        $response = $this->language->get('error_ebay_api_call');
		        return $response;
	          }

	          foreach ($titles as $title) {
	            $import_data['title'][] = $title->nodeValue;
	          }

	          foreach ($item_ids as $item_id) {
	            $import_data['id'][] = $item_id->nodeValue;
	          }
	        }
	    }

	    return $import_data;
	}

	public function getEbayItemQuantity($ebay_item_id) {
		$call_name = 'GetItem';
		$profile = $this->getEbayProfile();
		$ebay_call = new Ebaycall($profile['developer_id'], $profile['application_id'], $profile['certification_id'], $profile['compat'], $profile['site_id'], $call_name);
		
		$xml = '<?xml version="1.0" encoding="utf-8"?>';
		$xml .= '<GetItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$xml .= '<RequesterCredentials>';
		$xml .= '<eBayAuthToken>' . $profile['user_token'] . '</eBayAuthToken>';
		$xml .= '</RequesterCredentials>';
		$xml .= '<ItemID>' . $ebay_item_id . '</ItemID>';
		$xml .= '<WarningLevel>Low</WarningLevel>';
        $xml .= '<OutputSelector>Title</OutputSelector>';
        $xml .= '<OutputSelector>ItemID</OutputSelector>';
        $xml .= '<OutputSelector>Quantity</OutputSelector>';
        $xml .= '</GetItemRequest>';

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
        
        $quantity = $doc_response->getElementsByTagName('Quantity')->item(0)->nodeValue;	
        return $quantity;
	}

	public function getEbayItemId($product_id) {
		$ebay_item_id = $this->db->query("SELECT ebay_item_id FROM " . DB_PREFIX . "ebay_listing WHERE product_id = '" . (int)$product_id . "'");
		return $ebay_item_id->row['ebay_item_id'];
	}

	public function reviseEbayItemQuantity($ebay_item_id, $new_quantity) {
		$call_name = 'ReviseInventoryStatus';
		$profile = $this->getEbayProfile();
		$ebay_call = new Ebaycall($profile['developer_id'], $profile['application_id'], $profile['certification_id'], $profile['compat'], $profile['site_id'], $call_name);

		$xml = '<?xml version="1.0" encoding="utf-8"?>';
		$xml .= '<ReviseInventoryStatusRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$xml .= '<RequesterCredentials>';
		$xml .= '<eBayAuthToken>' . $profile['user_token'] . '</eBayAuthToken>';
		$xml .= '</RequesterCredentials>';
		$xml .= '<WarningLevel>Low</WarningLevel>';
		$xml .= '<InventoryStatus>';
		$xml .= '<ItemID>' . $ebay_item_id . '</ItemID>';
		$xml .= '<Quantity>' . $new_quantity . '</Quantity>';
		$xml .= '</InventoryStatus>';
		$xml .= '</ReviseInventoryStatusRequest>';

		$xml_response = $ebay_call->sendHttpRequest($xml);

		$ebay_call_response = '';

        if(stristr($xml_response, 'HTTP 404') || $xml_response == '') {
	        $this->language->load('affiliate/stock_control');
	        $ebay_call_response = $this->language->get('error_ebay_api_call');	        
        }

        $doc_response = new DomDocument();
        $doc_response->loadXML($xml_response);
        $message = $doc_response->getElementsByTagName('Ack')->item(0)->nodeValue;                

        if($message == 'Failure') {
        	$severity_code = $doc_response->getElementsByTagName('SeverityCode')->item(0)->nodeValue;
        	$error_code = $doc_response->getElementsByTagName('ErrorCode')->item(0)->nodeValue;
        	$short_message = $doc_response->getElementsByTagName('ShortMessage')->item(0)->nodeValue;
        	$long_message = $doc_response->getElementsByTagName('LongMessage')->item(0)->nodeValue;
	        $ebay_call_response = strtoupper($severity_code) . ': ' . $long_message . ' Error Code: ' . $error_code;	        
        }

        if($message == 'Success') {
        	$ebay_call_response = $doc_response->getElementsByTagName('Timestamp')->item(0)->nodeValue;
        	$ebay_call_response .= '<br>' . $doc_response->getElementsByTagName('ItemID')->item(0)->nodeValue;
        	$ebay_call_response .= '<br>' .$doc_response->getElementsByTagName('StartPrice')->item(0)->nodeValue;
        	$ebay_call_response .= '<br>' .$doc_response->getElementsByTagName('Quantity')->item(0)->nodeValue;
        }

        return $ebay_call_response;
	}

	public function endEbayItem($ebay_item_id) {
		$call_name = 'EndFixedPriceItem';
		$profile = $this->getEbayProfile();
		$ebay_call = new Ebaycall($profile['developer_id'], $profile['application_id'], $profile['certification_id'], $profile['compatability_level'], $profile['site_id'], $call_name);

		$xml = '<?xml version="1.0" encoding="utf-8"?>';
		$xml .= '<EndFixedPriceItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$xml .= '<ItemID>' . $ebay_item_id . '</ItemID>';
		$xml .= '<EndingReason EnumType="EndReasonCodeType">NotAvailable</EndingReason>';
		$xml .= '<RequesterCredentials>';
		$xml .= '<eBayAuthToken>' . $profile['user_token'] . '</eBayAuthToken>';
		$xml .= '</RequesterCredentials>';
		$xml .= '</EndFixedPriceItemRequest>';

		$xml_response = $ebay_call->sendHttpRequest($xml);

		$ebay_call_response = '';

        if(stristr($xml_response, 'HTTP 404') || $xml_response == '') {
	        $this->language->load('affiliate/stock_control');
	        $ebay_call_response = $this->language->get('error_ebay_api_call');	        
        }

        $doc_response = new DomDocument();
        $doc_response->loadXML($xml_response);
        $message = $doc_response->getElementsByTagName('Ack')->item(0)->nodeValue;                

        if($message == 'Failure') {
        	$severity_code = $doc_response->getElementsByTagName('SeverityCode')->item(0)->nodeValue;
        	$error_code = $doc_response->getElementsByTagName('ErrorCode')->item(0)->nodeValue;
        	$short_message = $doc_response->getElementsByTagName('ShortMessage')->item(0)->nodeValue;
        	$long_message = $doc_response->getElementsByTagName('LongMessage')->item(0)->nodeValue;
	        $ebay_call_response = strtoupper($severity_code) . ': ' . $long_message . ' Error Code: ' . $error_code;	        
        }

        if($message == 'Success') {
        	$ebay_call_response = $doc_response->getElementsByTagName('Timestamp')->item(0)->nodeValue;
        }

        return $ebay_call_response;
	}

	public function getTotalLogs($data = array()) {
		$sql = "SELECT COUNT(el.log_id) AS total FROM " . DB_PREFIX . "ebay_log el";
		
		if (!empty($data['filter_date_start']) && !empty($data['filter_date_end'])) {
			$sql .= " WHERE DATE(el.log_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
			$sql .= " AND DATE(el.log_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		} else {
			$sql .= " WHERE el.log_date >= CURDATE()";
		}
			
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getLogs($data = array()) {		
		$sql = "SELECT * FROM " . DB_PREFIX . "ebay_log el";		
		
		if (!empty($data['filter_date_start']) && !empty($data['filter_date_end'])) {
			$sql .= " WHERE DATE(el.log_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
			$sql .= " AND DATE(el.log_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		} else {
			$sql .= " WHERE el.log_date >= CURDATE()";
		}
				
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
			
		$query = $this->db->query($sql);
	
		return $query->rows;
	}

	public function setEbayLogMessage($message) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "ebay_log SET message = '" . $this->db->escape($message) . "', log_date = NOW()");
		// tab
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

	public function getProductQuantity($product_id) {
		$query = $this->db->query("SELECT quantity FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		return $query->row['quantity'];
	}

	public function setProductQuantity($new_quantity, $product_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '" . (int)$new_quantity . "' WHERE product_id = '" . (int)$product_id . "'");
		//tab
	}

	public function setProductStatus($new_status, $product_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET status = '" . (int)$new_status . "' WHERE product_id = '" . (int)$product_id . "'");
		//tab
	}

} // end class
?>