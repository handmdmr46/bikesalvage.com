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
      	$xml .= '<OutputSelector>ItemArray.Item.SellingStatus.ListingStatus</OutputSelector>';
      	$xml .= '<OutputSelector>ItemArray.Item.ListingDetails.EndTime</OutputSelector>';
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
	    $listing_status = $doc_response->getElementsByTagName('ListingStatus');
	    $end_time = $doc_response->getElementsByTagName('EndTime');
	    $number_pages = $doc_response->getElementsByTagName('TotalNumberOfPages');
	    $number_entries = $doc_response->getElementsByTagName('TotalNumberOfEntries');
	    $import_data = array();

	    foreach ($titles as $title) {
	      $import_data['title'][] = $title->nodeValue;
	    }

	    foreach ($item_ids as $item_id) {
	      $import_data['id'][] = $item_id->nodeValue;
	    }

	    foreach ($listing_status as $status) {
	    	$import_data['listing_status'][] = $status->nodeValue;
	    }

	    foreach ($end_time as $time) {
	    	$import_data['end_time'][] = $time->nodeValue;
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
				$xml .= '<OutputSelector>ItemArray.Item.SellingStatus.ListingStatus</OutputSelector>';
				$xml .= '<OutputSelector>ItemArray.Item.ListingDetails.EndTime</OutputSelector>';
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

			    foreach ($listing_status as $status) {
			    	$import_data['listing_status'][] = $status->nodeValue;
			    }

			    foreach ($end_time as $time) {
			    	$import_data['end_time'][] = $time->nodeValue;
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

	public function buildSellerList($import_data) {

		for($i = 0, $l = count($import_data['id']); $i < $l; ++$i) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "ebay_seller_list
				              SET ebay_item_id = '" . $this->db->escape($import_data['id'][$i]) . "',
				                  ebay_title = '" . $this->db->escape($import_data['title'][$i]) . "',
				                  listing_status = '" . $this->db->escape($import_data['listing_status'][$i]) . "',
				                  end_time = '" . $this->db->escape($import_data['end_time'][$i]) . "'");
		}
	}

	public function truncateSellerList() {
		$this->db->query("TRUNCATE TABLE " . DB_PREFIX . "ebay_seller_list");
		// tab
	}

	public function getSellerListDatabase($data = array()) {
		// $sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "ebay_seller_list";
		$sql = "SELECT * FROM " . DB_PREFIX . "ebay_seller_list";

		$sql .= " WHERE id > '0'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND ebay_title LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_status'])) {
			$sql .= " AND listing_status = '" . $this->db->escape($data['filter_status']) . "'";
		}

		if (!empty($data['filter_date'])) {
			$sql .= " AND end_time = '" . $this->db->escape($data['filter_date']) . "'";
		}

		if (!empty($data['filter_id'])) {
			$sql .= " AND ebay_item_id = '" . $this->db->escape($data['filter_id']) . "'";
		}

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

	public function getTotalSellerListDatabase($data = array()) {
		// $sql = "SELECT COUNT(DISTINCT ebay_item_id) AS total FROM " . DB_PREFIX . "ebay_seller_list";
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "ebay_seller_list";

		$sql .= " WHERE id > '0'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND ebay_title LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_status'])) {
			$sql .= " AND listing_status = '" . $this->db->escape($data['filter_status']) . "'";
		}

		if (!empty($data['filter_date'])) {
			$sql .= " AND end_time = '" . $this->db->escape($data['filter_date']) . "'";
		}

		if (!empty($data['filter_id'])) {
			$sql .= " AND ebay_item_id = '" . $this->db->escape($data['filter_id']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getSyncProducts($data = array(), &$total) {
		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

		$product_ids = array();
		$pids = $this->getProductIdCompletedEbayList();
		foreach ($pids as $pid) {
			foreach ($pid as $id) {
				$product_ids[] = $id;
				$total++;
			}
		}

		$sql .= " WHERE p.product_id IN (" . implode(', ', $product_ids) . ")";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (!empty($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
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


	public function getProductIdCompletedEbayList() {

		$sql = "SELECT DISTINCT el.product_id FROM " . DB_PREFIX . "ebay_listing el LEFT JOIN " . DB_PREFIX . "ebay_seller_list esl ON ( el.ebay_item_id = esl.ebay_item_id ) WHERE esl.listing_status =  'Completed'";

		$query = $this->db->query($sql);

		return $query->rows;
	}
}
?>