<?php
class ModelCheckoutOrder extends Model {	
	/**
	* Modified for checkout procedure - stock_control
	*
	*/
	public function addMasterOrder($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` 
							SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', 
							    store_id = '" . (int)$data['store_id'] . "', 
								store_name = '" . $this->db->escape($data['store_name']) . "', 
								store_url = '" . $this->db->escape($data['store_url']) . "', 
								customer_id = '" . (int)$data['customer_id'] . "', 
								customer_group_id = '" . (int)$data['customer_group_id'] . "', 
								firstname = '" . $this->db->escape($data['firstname']) . "', 
								lastname = '" . $this->db->escape($data['lastname']) . "', 
								email = '" . $this->db->escape($data['email']) . "', 
								telephone = '" . $this->db->escape($data['telephone']) . "', 
								fax = '" . $this->db->escape($data['fax']) . "', 
								payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', 
								payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', 
								payment_company = '" . $this->db->escape($data['payment_company']) . "', 
								payment_company_id = '" . $this->db->escape($data['payment_company_id']) . "', 
								payment_tax_id = '" . $this->db->escape($data['payment_tax_id']) . "', 
								payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', 
								payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', 
								payment_city = '" . $this->db->escape($data['payment_city']) . "', 
								payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', 
								payment_country = '" . $this->db->escape($data['payment_country']) . "', 
								payment_country_id = '" . (int)$data['payment_country_id'] . "', 
								payment_zone = '" . $this->db->escape($data['payment_zone']) . "', 
								payment_zone_id = '" . (int)$data['payment_zone_id'] . "', 
								payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', 
								payment_method = '" . $this->db->escape($data['payment_method']) . "', 
								payment_code = '" . $this->db->escape($data['payment_code']) . "', 
								shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', 
								shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', 
								shipping_company = '" . $this->db->escape($data['shipping_company']) . "', 
								shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', 
								shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', 
								shipping_city = '" . $this->db->escape($data['shipping_city']) . "', 
								shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', 
								shipping_country = '" . $this->db->escape($data['shipping_country']) . "', 
								shipping_country_id = '" . (int)$data['shipping_country_id'] . "', 
								shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', 
								shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', 
								shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', 
								shipping_method = '" . $this->db->escape($data['shipping_method']) . "', 
								shipping_code = '" . $this->db->escape($data['shipping_code']) . "', 
								comment = '" . $this->db->escape($data['comment']) . "', 
								total = '" . (float)$data['total'] . "', 
								commission = '" . (float)$data['commission'] . "', 
								language_id = '" . (int)$data['language_id'] . "', 
								currency_id = '" . (int)$data['currency_id'] . "', 
								currency_code = '" . $this->db->escape($data['currency_code']) . "', 
								currency_value = '" . (float)$data['currency_value'] . "', 
								ip = '" . $this->db->escape($data['ip']) . "', 
								forwarded_ip = '" .  $this->db->escape($data['forwarded_ip']) . "', 
								user_agent = '" . $this->db->escape($data['user_agent']) . "', 
								accept_language = '" . $this->db->escape($data['accept_language']) . "', 
								date_added = NOW(), 
								date_modified = NOW()");
								
		$order_id = $this->db->getLastId();

		foreach ($data['products'] as $product) { 
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_product 
								SET order_id = '" . (int)$order_id . "', 
								    product_id = '" . (int)$product['product_id'] . "', 
									name = '" . $this->db->escape($product['name']) . "', 
									model = '" . $this->db->escape($product['model']) . "', 
									quantity = '" . (int)$product['quantity'] . "', 
									price = '" . (float)$product['price'] . "', 
									total = '" . (float)$product['total'] . "', 
									tax = '" . (float)$product['tax'] . "', 
									reward = '" . (int)$product['reward'] . "',
									affiliate_id = '" . (int)$product['affiliate_id'] . "'");
												
 
			$order_product_id = $this->db->getLastId();

			// Product option
			foreach ($product['option'] as $option) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_option 
									SET order_id = '" . (int)$order_id . "', 
									    order_product_id = '" . (int)$order_product_id . "', 
										product_option_id = '" . (int)$option['product_option_id'] . "', 
										product_option_value_id = '" . (int)$option['product_option_value_id'] . "', 
										name = '" . $this->db->escape($option['name']) . "', 
										`value` = '" . $this->db->escape($option['value']) . "', 
										`type` = '" . $this->db->escape($option['type']) . "'");
			}
			
			// Product download	
			foreach ($product['download'] as $download) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_download 
									SET order_id = '" . (int)$order_id . "', 
									    order_product_id = '" . (int)$order_product_id . "', 
										name = '" . $this->db->escape($download['name']) . "', 
										filename = '" . $this->db->escape($download['filename']) . "', 
										mask = '" . $this->db->escape($download['mask']) . "', 
										remaining = '" . (int)($download['remaining'] * $product['quantity']) . "'");
			}	
		}
		
		// Vouchers
		foreach ($data['vouchers'] as $voucher) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher 
								SET order_id = '" . (int)$order_id . "', 
								    description = '" . $this->db->escape($voucher['description']) . "', 
									code = '" . $this->db->escape($voucher['code']) . "', 
									from_name = '" . $this->db->escape($voucher['from_name']) . "', 
									from_email = '" . $this->db->escape($voucher['from_email']) . "', 
									to_name = '" . $this->db->escape($voucher['to_name']) . "', 
									to_email = '" . $this->db->escape($voucher['to_email']) . "', 
									voucher_theme_id = '" . (int)$voucher['voucher_theme_id'] . "', 
									message = '" . $this->db->escape($voucher['message']) . "', 
									amount = '" . (float)$voucher['amount'] . "'");
		}
		
		// Totals	
		foreach ($data['totals'] as $total) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total 
								SET order_id = '" . (int)$order_id . "', 
								    code = '" . $this->db->escape($total['code']) . "', 
									title = '" . $this->db->escape($total['title']) . "', 
									text = '" . $this->db->escape($total['text']) . "', 
									`value` = '" . (float)$total['value'] . "', 
									sort_order = '" . (int)$total['sort_order'] . "'");
		}	

		return $order_id;
	}

	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT *, 
										(SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status
										FROM  " . DB_PREFIX . "order o 
										WHERE o.order_id = '" . (int)$order_id . "'");
			
		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");
			
			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';				
			}
			
			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");
			
			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}			
			
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");
			
			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';				
			}
			
			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");
			
			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}
			
			$this->load->model('localisation/language');
			
			$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);
			
			if ($language_info) {
				$language_code = $language_info['code'];
				$language_filename = $language_info['filename'];
				$language_directory = $language_info['directory'];
			} else {
				$language_code = '';
				$language_filename = '';
				$language_directory = '';
			}
		 			
			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],				
				'customer_id'             => $order_query->row['customer_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'email'                   => $order_query->row['email'],
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],				
				'payment_company'         => $order_query->row['payment_company'],
				'payment_company_id'      => $order_query->row['payment_company_id'],
				'payment_tax_id'          => $order_query->row['payment_tax_id'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],	
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_method'          => $order_query->row['payment_method'],
				'payment_code'            => $order_query->row['payment_code'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],				
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],	
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_method'         => $order_query->row['shipping_method'],
				'shipping_code'           => $order_query->row['shipping_code'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'order_status_id'         => $order_query->row['order_status_id'],
				'order_status'            => $order_query->row['order_status'],
				'language_id'             => $order_query->row['language_id'],
				'language_code'           => $language_code,
				'language_filename'       => $language_filename,
				'language_directory'      => $language_directory,
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'ip'                      => $order_query->row['ip'],
				'forwarded_ip'            => $order_query->row['forwarded_ip'], 
				'user_agent'              => $order_query->row['user_agent'],	
				'accept_language'         => $order_query->row['accept_language'],				
				'date_modified'           => $order_query->row['date_modified'],
				'date_added'              => $order_query->row['date_added']
			);
		} else {
			return false;	
		}
	}	

	/**
	*
	* Modified: eBay call for stock control here
	*/
	public function confirm($order_id, $order_status_id, $comment = '', $notify = false) {
		$order_info = $this->getOrder($order_id);
		 
		if ($order_info && !$order_info['order_status_id']) {
			// Fraud Detection
			if ($this->config->get('config_fraud_detection')) {
				$this->load->model('checkout/fraud');
				
				$risk_score = $this->model_checkout_fraud->getFraudScore($order_info);
				
				if ($risk_score > $this->config->get('config_fraud_score')) {
					$order_status_id = $this->config->get('config_fraud_status_id');
				}
			}

			// Ban IP
			$status = false;
			$this->load->model('account/customer');
			if ($order_info['customer_id']) {
				$results = $this->model_account_customer->getIps($order_info['customer_id']);
				foreach ($results as $result) {
					if ($this->model_account_customer->isBanIp($result['ip'])) {
						$status = true;
						break;
					}
				}
			} else {
				$status = $this->model_account_customer->isBanIp($order_info['ip']);
			}
			
			if ($status) {
				$order_status_id = $this->config->get('config_order_status_id');
			}		
			
			// Update order_status from 0, makes visible in admin Sales/Orders	
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

			// Insert order_history
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '1', comment = '" . $this->db->escape(($comment && $notify) ? $comment : '') . "', date_added = NOW()");

			// Order Products
			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");			
			foreach ($order_product_query->rows as $order_product) {
				############################################
				############# STOCK CONTROL ################
				############################################			
				
				if($order_product['affiliate_id'] < 1) { // only make ebay call to admin products here
					$ebay_item_id = $this->getEbayItemId($order_product['product_id']);
					$ebay_item_quantity = $this->getEbayItemQuantity($ebay_item_id);
					$new_ebay_item_quantity = $ebay_item_quantity - $order_product['quantity'];

					$ebay_response = 'FAILED REQUEST - Please adjust your stock manually for this item';

					// ebay item stock control
					if(is_numeric($ebay_item_quantity) && $new_ebay_item_quantity < 1) {
						$ebay_response = 'EBAY ITEM ENDED - ItemID: ' . $ebay_item_id . ' - Response:';
						$ebay_response .= $this->endEbayItem($ebay_item_id);
					}

					if(is_numeric($ebay_item_quantity) && $new_ebay_item_quantity >= 1) {
						$ebay_response = 'REVISED EBAY ITEM QUANTITY - ItemID: ' . $ebay_item_id . ' - Response: ';
						$ebay_response .= $this->reviseEbayItemQuantity($ebay_item_id, $new_ebay_item_quantity);
					}
				}
				// all other affiliates
				if($order_product['affiliate_id'] > 0) {
					// eBay Stock Control
					//if(config('affiliate_stock_control_status') > 0 ) { //make ebayCall }

					//Commission Control
					if ($this->config->has('config_commission')) {
						$commission_rate = $this->config->get('config_commission');
					} else {
						$commission_rate = 4.00;
					}
					$commission = ($commission_rate / 100) * $order_product['price'];
					$commission = $commission * $order_product['quantity'];
                    $this->db->query("UPDATE " . DB_PREFIX . "order_product SET commission = '" . (int)$commission . "' WHERE product_id = '" . (int)$order_product['product_id'] . "'");
				}

				// adjust product quantity
				$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");				
				
				// set product status
				if($this->getProductQuantity($order_product['product_id']) < 1) {
					$this->db->query("UPDATE " . DB_PREFIX . "product SET status = '0' WHERE product_id = '" . (int)$order_product['product_id'] . "'");
					$ebay_response .= ' - Opencart Product Status: Not Active (0) ';
				}

				// add eBay response to db
				$this->db->query("UPDATE " . DB_PREFIX . "order_product SET ebay_response = '" . $this->db->escape($ebay_response) . "' WHERE order_id = '" . (int)$order_id . "' AND product_id = '" . (int)$order_product['product_id'] . "'");

				// order options
				$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product['order_product_id'] . "'");				
				foreach ($order_option_query->rows as $option) {
					$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
				}
			}
			
			$this->cache->delete('product');
			
			// Downloads
			$order_download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "'");
			
			// Gift Voucher
			$this->load->model('checkout/voucher');
			
			// Order Voucher
			$order_voucher_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");
			foreach ($order_voucher_query->rows as $order_voucher) {
				$voucher_id = $this->model_checkout_voucher->addVoucher($order_id, $order_voucher);				
				$this->db->query("UPDATE " . DB_PREFIX . "order_voucher SET voucher_id = '" . (int)$voucher_id . "' WHERE order_voucher_id = '" . (int)$order_voucher['order_voucher_id'] . "'");
			}			
			
			// Send out any gift voucher mails
			if ($this->config->get('config_complete_status_id') == $order_status_id) {
				$this->model_checkout_voucher->confirm($order_id);
			}
					
			// Order Totals			
			$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");			
			foreach ($order_total_query->rows as $order_total) {
				$this->load->model('total/' . $order_total['code']);				
				if (method_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
					$this->{'model_total_' . $order_total['code']}->confirm($order_info, $order_total);
				}
			}
			
			// Send out order confirmation mail - to customer??
			$language = new Language($order_info['language_directory']);
			$language->load($order_info['language_filename']);
			$language->load('mail/order');
		 
			$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");
			
			if ($order_status_query->num_rows) {
				$order_status = $order_status_query->row['name'];	
			} else {
				$order_status = '';
			}
			
			$subject = sprintf($language->get('text_new_subject'), $order_info['store_name'], $order_id);
		
			// HTML Mail
			$template = new Template();
			
			// Language
			$template->data['title']                 = sprintf($language->get('text_new_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);
			$template->data['text_greeting']         = sprintf($language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
			$template->data['text_link']             = $language->get('text_new_link');
			$template->data['text_download']         = $language->get('text_new_download');
			$template->data['text_order_detail']     = $language->get('text_new_order_detail');
			$template->data['text_instruction']      = $language->get('text_new_instruction');
			$template->data['text_order_id']         = $language->get('text_new_order_id');
			$template->data['text_date_added']       = $language->get('text_new_date_added');
			$template->data['text_payment_method']   = $language->get('text_new_payment_method');	
			$template->data['text_shipping_method']  = $language->get('text_new_shipping_method');
			$template->data['text_email']            = $language->get('text_new_email');
			$template->data['text_telephone']        = $language->get('text_new_telephone');
			$template->data['text_ip']               = $language->get('text_new_ip');
			$template->data['text_payment_address']  = $language->get('text_new_payment_address');
			$template->data['text_shipping_address'] = $language->get('text_new_shipping_address');
			$template->data['text_product']          = $language->get('text_new_product');
			$template->data['text_model']            = $language->get('text_new_model');
			$template->data['text_quantity']         = $language->get('text_new_quantity');
			$template->data['text_price']            = $language->get('text_new_price');
			$template->data['text_total']            = $language->get('text_new_total');
			$template->data['text_footer']           = $language->get('text_new_footer');
			$template->data['text_powered']          = $language->get('text_new_powered');
			
			$template->data['logo']        = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');		
			$template->data['store_name']  = $order_info['store_name'];
			$template->data['store_url']   = $order_info['store_url'];
			$template->data['customer_id'] = $order_info['customer_id'];
			$template->data['link']        = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id;
			
			if ($order_download_query->num_rows) {
				$template->data['download'] = $order_info['store_url'] . 'index.php?route=account/download';
			} else {
				$template->data['download'] = '';
			}
			
			$template->data['order_id']        = $order_id;
			$template->data['date_added']      = date($language->get('date_format_short'), strtotime($order_info['date_added']));    	
			$template->data['payment_method']  = $order_info['payment_method'];
			$template->data['shipping_method'] = $order_info['shipping_method'];
			$template->data['email']           = $order_info['email'];
			$template->data['telephone']       = $order_info['telephone'];
			$template->data['ip']              = $order_info['ip'];
			
			if ($comment && $notify) {
				$template->data['comment'] = nl2br($comment);
			} else {
				$template->data['comment'] = '';
			}
						
			if ($order_info['payment_address_format']) {
				$format = $order_info['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}
			
			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);
		
			$replace = array(
				'firstname' => $order_info['payment_firstname'],
				'lastname'  => $order_info['payment_lastname'],
				'company'   => $order_info['payment_company'],
				'address_1' => $order_info['payment_address_1'],
				'address_2' => $order_info['payment_address_2'],
				'city'      => $order_info['payment_city'],
				'postcode'  => $order_info['payment_postcode'],
				'zone'      => $order_info['payment_zone'],
				'zone_code' => $order_info['payment_zone_code'],
				'country'   => $order_info['payment_country']  
			);
		
			$template->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));						
									
			if ($order_info['shipping_address_format']) {
				$format = $order_info['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}
			
			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);
		
			$replace = array(
				'firstname' => $order_info['shipping_firstname'],
				'lastname'  => $order_info['shipping_lastname'],
				'company'   => $order_info['shipping_company'],
				'address_1' => $order_info['shipping_address_1'],
				'address_2' => $order_info['shipping_address_2'],
				'city'      => $order_info['shipping_city'],
				'postcode'  => $order_info['shipping_postcode'],
				'zone'      => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
				'country'   => $order_info['shipping_country']  
			);
		
			$template->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
			
			// Products
			$template->data['products'] = array();
				
			foreach ($order_product_query->rows as $product) {
				$option_data = array();
				
				$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");
				
				foreach ($order_option_query->rows as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
					}
					
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);					
				}
			  
				$template->data['products'][] = array(
					'name'     => $product['name'],
					'model'    => $product['model'],
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
				);
			}
	
			// Vouchers
			$template->data['vouchers'] = array();
			
			foreach ($order_voucher_query->rows as $voucher) {
				$template->data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
				);
			}
	
			$template->data['totals'] = $order_total_query->rows;
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/order.tpl')) {
				$html = $template->fetch($this->config->get('config_template') . '/template/mail/order.tpl');
			} else {
				$html = $template->fetch('default/template/mail/order.tpl');
			}
			
			// Text Email Header
			$text  = sprintf($language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8')) . "\n\n";
			$text .= $language->get('text_new_order_id') . ' ' . $order_id . "\n";
			$text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
			$text .= $language->get('text_new_order_status') . ' ' . $order_status . "\n\n";
			
			if ($comment && $notify) {
				$text .= $language->get('text_new_instruction') . "\n\n";
				$text .= $comment . "\n\n";
			}
			
			//Text Email Products
			$text .= $language->get('text_new_products') . "\n";
			foreach ($order_product_query->rows as $product) {
				$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
				
				$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . $product['order_product_id'] . "'");
				foreach ($order_option_query->rows as $option) {
					$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($option['value']) > 20 ? utf8_substr($option['value'], 0, 20) . '..' : $option['value']) . "\n";
				}
			}
			
			// Text Email Vouchers
			foreach ($order_voucher_query->rows as $voucher) {
				$text .= '1x ' . $voucher['description'] . ' ' . $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']);
			}
			
			// Text Email Order Total			
			$text .= "\n";
			$text .= $language->get('text_new_order_total') . "\n";
			foreach ($order_total_query->rows as $total) {
				$text .= $total['title'] . ': ' . html_entity_decode($total['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
			}			
			
			// Text Email Link
			$text .= "\n";
			if ($order_info['customer_id']) {
				$text .= $language->get('text_new_link') . "\n";
				$text .= $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "\n\n";
			}
			
			// Text Email Download Link
			if ($order_download_query->num_rows) {
				$text .= $language->get('text_new_download') . "\n";
				$text .= $order_info['store_url'] . 'index.php?route=account/download' . "\n\n";
			}
			
			// Text Email Comment
			if ($order_info['comment']) {
				$text .= $language->get('text_new_comment') . "\n\n";
				$text .= $order_info['comment'] . "\n\n";
			}

			// Text Email Footer
			$text .= $language->get('text_new_footer') . "\n\n";
		
			$mail = new Mail(); 
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');			
			$mail->setTo($order_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($order_info['store_name']);
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setHtml($html);
			$mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
			$mail->send();

			// Send Order Alert Email
			if ($this->config->get('config_alert_mail')) {
				$subject = sprintf($language->get('text_new_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $order_id);
				
				// Text 
				$text  = $language->get('text_new_received') . "\n\n";
				$text .= $language->get('text_new_order_id') . ' ' . $order_id . "\n";
				$text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
				$text .= $language->get('text_new_order_status') . ' ' . $order_status . "\n\n";
				$text .= $language->get('text_new_products') . "\n";
				
				foreach ($order_product_query->rows as $product) {
					$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
					
					$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . $product['order_product_id'] . "'");
					
					foreach ($order_option_query->rows as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
						}
											
						$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value) . "\n";
					}
				}
				
				foreach ($order_voucher_query->rows as $voucher) {
					$text .= '1x ' . $voucher['description'] . ' ' . $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']);
				}
							
				$text .= "\n";

				$text .= $language->get('text_new_order_total') . "\n";
				
				foreach ($order_total_query->rows as $total) {
					$text .= $total['title'] . ': ' . html_entity_decode($total['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
				}			
				
				$text .= "\n";
				
				if ($order_info['comment']) {
					$text .= $language->get('text_new_comment') . "\n\n";
					$text .= $order_info['comment'] . "\n\n";
				}
			
				$mail = new Mail(); 
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->hostname = $this->config->get('config_smtp_host');
				$mail->username = $this->config->get('config_smtp_username');
				$mail->password = $this->config->get('config_smtp_password');
				$mail->port = $this->config->get('config_smtp_port');
				$mail->timeout = $this->config->get('config_smtp_timeout');
				$mail->setTo($this->config->get('config_email'));
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($order_info['store_name']);
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
				$mail->send();
				
				// Send to additional alert emails
				$emails = explode(',', $this->config->get('config_alert_emails'));
				
				foreach ($emails as $email) {
					if ($email && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
						$mail->setTo($email);
						$mail->send();
					}
				}				
			}	
			
			// Send Affiliates Order Alert Email
			/*foreach ($order_product_query->rows as $product) {
				if ($product['affiliate_id'] != 0) {
					// $affiliate_email = $this->getAffiliateEmail($product['affiliate_id']);

					// start build affiliate order emails here....
				}

			}*/
		}
	
	}
	
	public function update($order_id, $order_status_id, $comment = '', $notify = false) {
		$order_info = $this->getOrder($order_id);

		if ($order_info && $order_info['order_status_id']) {
			// Fraud Detection
			if ($this->config->get('config_fraud_detection')) {
				$this->load->model('checkout/fraud');
				
				$risk_score = $this->model_checkout_fraud->getFraudScore($order_info);
				
				if ($risk_score > $this->config->get('config_fraud_score')) {
					$order_status_id = $this->config->get('config_fraud_status_id');
				}
			}			

			// Ban IP
			$status = false;
			
			$this->load->model('account/customer');
			
			if ($order_info['customer_id']) {
								
				$results = $this->model_account_customer->getIps($order_info['customer_id']);
				
				foreach ($results as $result) {
					if ($this->model_account_customer->isBanIp($result['ip'])) {
						$status = true;
						
						break;
					}
				}
			} else {
				$status = $this->model_account_customer->isBanIp($order_info['ip']);
			}
			
			if ($status) {
				$order_status_id = $this->config->get('config_order_status_id');
			}		
						
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
		
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
	
			// Send out any gift voucher mails
			if ($this->config->get('config_complete_status_id') == $order_status_id) {
				$this->load->model('checkout/voucher');
	
				$this->model_checkout_voucher->confirm($order_id);
			}	
	
			if ($notify) {
				$language = new Language($order_info['language_directory']);
				$language->load($order_info['language_filename']);
				$language->load('mail/order');
			
				$subject = sprintf($language->get('text_update_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);
	
				$message  = $language->get('text_update_order') . ' ' . $order_id . "\n";
				$message .= $language->get('text_update_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n\n";
				
				$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");
				
				if ($order_status_query->num_rows) {
					$message .= $language->get('text_update_order_status') . "\n\n";
					$message .= $order_status_query->row['name'] . "\n\n";					
				}
				
				if ($order_info['customer_id']) {
					$message .= $language->get('text_update_link') . "\n";
					$message .= $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "\n\n";
				}
				
				if ($comment) { 
					$message .= $language->get('text_update_comment') . "\n\n";
					$message .= $comment . "\n\n";
				}
					
				$message .= $language->get('text_update_footer');

				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->hostname = $this->config->get('config_smtp_host');
				$mail->username = $this->config->get('config_smtp_username');
				$mail->password = $this->config->get('config_smtp_password');
				$mail->port = $this->config->get('config_smtp_port');
				$mail->timeout = $this->config->get('config_smtp_timeout');				
				$mail->setTo($order_info['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($order_info['store_name']);
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
				$mail->send();
			}
		}
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
        	$ebay_call_response = 'FAILURE: ';
	        $ebay_call_response .= strtoupper($severity_code) . ': ' . $long_message . ' Error Code: ' . $error_code;	        
        }

        if($message == 'Success') {
        	$ebay_call_response = 'SUCCESS: ItemID=';
        	$ebay_call_response .= $doc_response->getElementsByTagName('ItemID')->item(0)->nodeValue;
        	$ebay_call_response .= ' NewQuantity=';
        	$ebay_call_response .= $doc_response->getElementsByTagName('Quantity')->item(0)->nodeValue;
        	$ebay_call_response .= ' Timestamp=';
        	$ebay_call_response .= $doc_response->getElementsByTagName('Timestamp')->item(0)->nodeValue;
        }

        return $ebay_call_response;
	}

	public function endEbayItem($ebay_item_id) {
		$call_name = 'endFixedPriceItem';		
		$profile = $this->getEbayProfile();
		$ebay_call = new Ebaycall($profile['developer_id'], $profile['application_id'], $profile['certification_id'], $profile['compat'], $profile['site_id'], $call_name);

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
        	$ebay_call_response = 'FAILURE: ';
	        $ebay_call_response .= strtoupper($severity_code) . ': ' . $long_message . ' Error Code: ' . $error_code;	        
        }

        if($message == 'Success') {
        	$ebay_call_response = 'SUCCESS: Timestamp=';
        	$ebay_call_response .= $doc_response->getElementsByTagName('Timestamp')->item(0)->nodeValue;
        }

        return $ebay_call_response;
	}

	public function getEbayItemQuantity($ebay_item_id) {
		$call_name = 'getItem';		
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

	public function getProductQuantity($product_id) {
		$product_quantity = $this->db->query("SELECT quantity FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		return $product_quantity->row['quantity'];
	}

	public function getEbayItemId($product_id) {
		$ebay_item_id = $this->db->query("SELECT ebay_item_id FROM " . DB_PREFIX . "ebay_listing WHERE product_id = '" . (int)$product_id . "'");
		return $ebay_item_id->row['ebay_item_id'];
	}

	public function getEbayProfile($affiliate_id = 0) {
		$sql = "SELECT * FROM " . DB_PREFIX . "ebay_settings WHERE `affiliate_id` = '" . (int)$affiliate_id . "'";
		$query = $this->db->query($sql);
		return $query->row;
	}

}// end class
?>