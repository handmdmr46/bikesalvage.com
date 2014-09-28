<?php
class ModelAffiliateAffiliate extends Model {
	public function addAffiliate($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "affiliate 
							SET firstname = '" . $this->db->escape($data['firstname']) . "', 
								lastname = '" . $this->db->escape($data['lastname']) . "', 
								email = '" . $this->db->escape($data['email']) . "', 
								telephone = '" . $this->db->escape($data['telephone']) . "', 
								fax = '" . $this->db->escape($data['fax']) . "', 
								salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', 
								password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', 
								company = '" . $this->db->escape($data['company']) . "', 
								address_1 = '" . $this->db->escape($data['address_1']) . "', 
								address_2 = '" . $this->db->escape($data['address_2']) . "', 
								city = '" . $this->db->escape($data['city']) . "', 
								postcode = '" . $this->db->escape($data['postcode']) . "', 
								country_id = '" . (int)$data['country_id'] . "', 
								zone_id = '" . (int)$data['zone_id'] . "', 
								code = '" . $this->db->escape($data['code']) . "', 
								commission = '" . (float)$data['commission'] . "', 
								tax = '" . $this->db->escape($data['tax']) . "', 
								payment = '" . $this->db->escape($data['payment']) . "', 
								cheque = '" . $this->db->escape($data['cheque']) . "', 
								paypal = '" . $this->db->escape($data['paypal']) . "', 
								bank_name = '" . $this->db->escape($data['bank_name']) . "', 
								bank_branch_number = '" . $this->db->escape($data['bank_branch_number']) . "', 
								bank_swift_code = '" . $this->db->escape($data['bank_swift_code']) . "', 
								bank_account_name = '" . $this->db->escape($data['bank_account_name']) . "', 
								bank_account_number = '" . $this->db->escape($data['bank_account_number']) . "', 
								status = '" . (int)$data['status'] . "', 
								date_added = NOW()");       	
	}
	
	public function editAffiliate($affiliate_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "affiliate 
							SET firstname = '" . $this->db->escape($data['firstname']) . "', 
								lastname = '" . $this->db->escape($data['lastname']) . "', 
								email = '" . $this->db->escape($data['email']) . "', 
								telephone = '" . $this->db->escape($data['telephone']) . "', 
								fax = '" . $this->db->escape($data['fax']) . "', 
								company = '" . $this->db->escape($data['company']) . "', 
								address_1 = '" . $this->db->escape($data['address_1']) . "', 
								address_2 = '" . $this->db->escape($data['address_2']) . "', 
								city = '" . $this->db->escape($data['city']) . "', 
								postcode = '" . $this->db->escape($data['postcode']) . "', 
								country_id = '" . (int)$data['country_id'] . "', 
								zone_id = '" . (int)$data['zone_id'] . "', 
								code = '" . $this->db->escape($data['code']) . "', 
								commission = '" . (float)$data['commission'] . "', 
								tax = '" . $this->db->escape($data['tax']) . "', 
								payment = '" . $this->db->escape($data['payment']) . "', 
								cheque = '" . $this->db->escape($data['cheque']) . "', 
								paypal = '" . $this->db->escape($data['paypal']) . "', 
								bank_name = '" . $this->db->escape($data['bank_name']) . "', 
								bank_branch_number = '" . $this->db->escape($data['bank_branch_number']) . "', 
								bank_swift_code = '" . $this->db->escape($data['bank_swift_code']) . "', 
								bank_account_name = '" . $this->db->escape($data['bank_account_name']) . "', 
								bank_account_number = '" . $this->db->escape($data['bank_account_number']) . "', 
								status = '" . (int)$data['status'] . "' 
						 WHERE  affiliate_id = '" . (int)$affiliate_id . "'");
	
      	if ($data['password']) {
        	$this->db->query("UPDATE " . DB_PREFIX . "affiliate 
								SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', 
									password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' 
							 WHERE affiliate_id = '" . (int)$affiliate_id . "'");
      	}
	}
	
	public function deleteAffiliate($affiliate_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "affiliate WHERE affiliate_id = '" . (int)$affiliate_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "affiliate_transaction WHERE affiliate_id = '" . (int)$affiliate_id . "'");
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE affiliate_id = '" . (int)$affiliate_id . "'");
		foreach($query->rows as $row) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$row['product_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$row['product_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$row['product_id'] . "'");			
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$row['product_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$row['product_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$row['product_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_recurring WHERE product_id = '" . (int)$row['product_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_profile WHERE product_id = '" . (int)$row['product_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$row['product_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$row['product_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$row['product_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$row['product_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$row['product_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_shipping WHERE product_id = '" . (int)$row['product_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$row['product_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$row['product_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$row['product_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$row['product_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_shipping WHERE product_id = '" . (int)$row['product_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_affiliate WHERE product_id = '" . (int)$row['product_id'] . "'");
		}
	}
	
	public function getAffiliate($affiliate_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "affiliate WHERE affiliate_id = '" . (int)$affiliate_id . "'");
	
		return $query->row;
	}
	
	public function getAffiliateByEmail($email) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "affiliate WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	
		return $query->row;
	}
			
	public function getAffiliates($data = array()) {
		$sql = "SELECT *, 
				CONCAT(a.firstname, ' ', a.lastname) AS name, 
				(SELECT SUM(at.amount) FROM " . DB_PREFIX . "affiliate_transaction at WHERE at.affiliate_id = a.affiliate_id GROUP BY at.affiliate_id) AS balance 
				FROM   " . DB_PREFIX . "affiliate a";

		$implode = array();
		
		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(a.firstname, ' ', a.lastname) LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "LCASE(a.email) = '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "'";
		}
		
		if (!empty($data['filter_code'])) {
			$implode[] = "a.code = '" . $this->db->escape($data['filter_code']) . "'";
		}
					
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "a.status = '" . (int)$data['filter_status'] . "'";
		}	
		
		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "a.approved = '" . (int)$data['filter_approved'] . "'";
		}		
		
		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(a.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'name',
			'a.email',
			'a.code',
			'a.status',
			'a.approved',
			'a.date_added'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
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
	
	public function approve($affiliate_id) {
		$affiliate_info = $this->getAffiliate($affiliate_id);
			
		if ($affiliate_info) {
			$this->db->query("UPDATE " . DB_PREFIX . "affiliate SET approved = '1' WHERE affiliate_id = '" . (int)$affiliate_id . "'");
			
			$this->language->load('mail/affiliate');
	
			$message  = sprintf($this->language->get('text_approve_welcome'), $this->config->get('config_name')) . "\n\n";
			$message .= $this->language->get('text_approve_login') . "\n";
			$message .= HTTP_CATALOG . 'index.php?route=affiliate/login' . "\n\n";
			$message .= $this->language->get('text_approve_services') . "\n\n";
			$message .= $this->language->get('text_approve_thanks') . "\n";
			$message .= $this->config->get('config_name');
	
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');							
			$mail->setTo($affiliate_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_approve_subject'), $this->config->get('config_name')), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
	}
	
	public function getAffiliatesByNewsletter() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "affiliate WHERE newsletter = '1' ORDER BY firstname, lastname, email");
	
		return $query->rows;
	}
		
	public function getTotalAffiliates($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "affiliate";
		
		$implode = array();
		
		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if (!empty($data['filter_email'])) {
			$implode[] = "LCASE(email) = '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "'";
		}	
				
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "status = '" . (int)$data['filter_status'] . "'";
		}			
		
		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "approved = '" . (int)$data['filter_approved'] . "'";
		}		
				
		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
				
		$query = $this->db->query($sql);
				
		return $query->row['total'];
	}
		
	public function getTotalAffiliatesAwaitingApproval() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "affiliate WHERE status = '0' OR approved = '0'");

		return $query->row['total'];
	}
	
	public function getTotalAffiliatesByCountryId($country_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "affiliate WHERE country_id = '" . (int)$country_id . "'");
		
		return $query->row['total'];
	}	
	
	public function getTotalAffiliatesByZoneId($zone_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "affiliate WHERE zone_id = '" . (int)$zone_id . "'");
		
		return $query->row['total'];
	}
		
	public function addTransaction($affiliate_id = 0, $description = '', $amount = '', $status_id = 0, $order_id = 0) {
		$affiliate_info = $this->getAffiliate($affiliate_id);
		
		if ($affiliate_info) { 
			$this->db->query("INSERT INTO " . DB_PREFIX . "affiliate_transaction 
				               SET affiliate_id = '" . (int)$affiliate_id . "', 
				                   order_id = '" . (float)$order_id . "', 
				                   description = '" . $this->db->escape($description) . "', 
				                   amount = '" . (float)$amount . "', 
				                   status_id = '" . (int)$status_id . "',
				                   date_added = NOW()");
		
			$this->language->load('mail/affiliate');
							
			$message  = sprintf($this->language->get('text_transaction_received'), $this->currency->format($amount, $this->config->get('config_currency'))) . "\n\n";
			$message .= sprintf($this->language->get('text_transaction_total'), $this->currency->format($this->getTransactionTotal($affiliate_id), $this->config->get('config_currency')));
								
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');
			$mail->setTo($affiliate_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_transaction_subject'), $this->config->get('config_name')), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
	}

	public function editTransaction($affiliate_id, $transaction_id, $data) {
			if (isset($data['status_id']) && isset($data['description'])) {
				$this->db->query("UPDATE " . DB_PREFIX . "affiliate_transaction 
					              SET    description = '" . $this->db->escape($data['description']) . "', 				                 
					                     status_id = '" . (int)$data['status_id'] . "',
					                     last_modified = NOW()
					              WHERE  affiliate_transaction_id = '" . (int)$transaction_id . "'");
			}
	}
	
	public function deleteTransaction($order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "affiliate_transaction WHERE order_id = '" . (int)$order_id . "'");
		// for tab
	}
	
	public function getTransactions($affiliate_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 10;
		}	
				
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "affiliate_transaction WHERE affiliate_id = '" . (int)$affiliate_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
	
		return $query->rows;
	}

	/**
	* -- $this->config->get('config_affiliate_order_complete_status_id');
	*/
	public function getCommissionBalanceByAffiliateId($affiliate_id) {
		$query = $this->db->query("SELECT    SUM(commission) AS balance 
			                       FROM      " . DB_PREFIX . "order_product op			                       
			                       WHERE     op.order_id IN (SELECT o.order_id FROM " . DB_PREFIX . "order o WHERE o.order_status_id = '" . (int)$this->config->get('config_affiliate_order_complete_status_id') . "')
			                       AND       op.affiliate_id = '" . (int)$affiliate_id . "'");			                       

		return $query->row['balance'];
	}

	public function getOrderProductBalanceByAffiliateId($affiliate_id) {
		$query = $this->db->query("SELECT    SUM(total) AS balance 
			                       FROM      " . DB_PREFIX . "order_product op			                       
			                       WHERE     op.order_id IN (SELECT o.order_id FROM " . DB_PREFIX . "order o WHERE o.order_status_id = '" . (int)$this->config->get('config_affiliate_order_complete_status_id') . "')
			                       AND       op.affiliate_id = '" . (int)$affiliate_id . "'");			                       

		return $query->row['balance'];
	}

	public function getTotalTransactions($affiliate_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total  FROM " . DB_PREFIX . "affiliate_transaction WHERE affiliate_id = '" . (int)$affiliate_id . "'");
	
		return $query->row['total'];
	}
			
	public function getTransactionTotal($affiliate_id) {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "affiliate_transaction WHERE affiliate_id = '" . (int)$affiliate_id . "'");
	
		return $query->row['total'];
	}	
	
	public function getTotalTransactionsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "affiliate_transaction WHERE order_id = '" . (int)$order_id . "'");
	
		return $query->row['total'];
	}	

	public function getTotalProductsByAffiliateId($data = array(), $affiliate_id) {
		$sql = "SELECT    COUNT(DISTINCT p.product_id) AS total 
		        FROM      " . DB_PREFIX . "product p 
		        LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

		if (!empty($data['filter_category_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";			
		}

		$sql .= " WHERE p.affiliate_id = '" . (int)$affiliate_id . "'";

		$sql .= " AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

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

		$query = $this->db->query($sql);

		return $query->row['total'];
	}	

	public function getProductsByAffiliateId($data = array(), $affiliate_id) {

		$sql = "SELECT * FROM " . DB_PREFIX . "product p 
		        LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

		if (!empty($data['filter_category_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";			
		}

		$sql .= " WHERE p.affiliate_id = '" . (int)$affiliate_id . "'";

		$sql .= " AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'"; 

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

	public function addAffiliateProduct($data, $affiliate_id) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "product 
			              SET model = '" . $this->db->escape($data['model']) . "', 
			                  sku = '" . $this->db->escape($data['sku']) . "', 
			                  upc = '" . $this->db->escape($data['upc']) . "', 
			                  ean = '" . $this->db->escape($data['ean']) . "', 
			                  jan = '" . $this->db->escape($data['jan']) . "', 
			                  isbn = '" . $this->db->escape($data['isbn']) . "', 
			                  mpn = '" . $this->db->escape($data['mpn']) . "', 
			                  location = '" . $this->db->escape($data['location']) . "', 
			                  quantity = '" . (int)$data['quantity'] . "', 
			                  minimum = '" . (int)$data['minimum'] . "', 
			                  subtract = '" . (int)$data['subtract'] . "', 
			                  stock_status_id = '" . (int)$data['stock_status_id'] . "', 
			                  date_available = '" . $this->db->escape($data['date_available']) . "', 
			                  manufacturer_id = '" . (int)$data['manufacturer_id'] . "', 
			                  shipping = '" . (int)$data['shipping'] . "', 
			                  price = '" . (float)$data['price'] . "', 
			                  points = '" . (int)$data['points'] . "', 
			                  weight = '" . (float)$data['weight'] . "', 
			                  weight_class_id = '" . (int)$data['weight_class_id'] . "', 
			                  length = '" . (float)$data['length'] . "', 
			                  width = '" . (float)$data['width'] . "', 
			                  height = '" . (float)$data['height'] . "', 
			                  length_class_id = '" . (int)$data['length_class_id'] . "', 
			                  status = '" . (int)$data['status'] . "', 
			                  tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "', 
			                  sort_order = '" . (int)$data['sort_order'] . "', 
			                  date_added = NOW(),
			                  affiliate_id = '" . (int)$affiliate_id . "'");

		$product_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE product_id = '" . (int)$product_id . "'");
		}

		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "'");
		}

		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {				
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}

		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

					$product_option_id = $this->db->getLastId();

					if (isset($product_option['product_option_value']) && count($product_option['product_option_value']) > 0 ) {
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						} 
					}else{
						$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_option_id = '".$product_option_id."'");
					}
				} else { 
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value = '" . $this->db->escape($product_option['option_value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}

		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}

		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}

		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $product_reward) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$product_reward['points'] . "'");
			}
		}

		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		if (isset($data['product_profiles'])) {
			foreach ($data['product_profiles'] as $profile) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_profile` SET `product_id` = " . (int)$product_id . ", customer_group_id = " . (int)$profile['customer_group_id'] . ", `profile_id` = " . (int)$profile['profile_id']);
			}
		} 

		$this->cache->delete('product');
	}

}// end class
?>