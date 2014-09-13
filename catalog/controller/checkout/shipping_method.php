<?php 
class ControllerCheckoutShippingMethod extends Controller {
	public function index() {
		$this->language->load('checkout/checkout');

		$this->load->model('account/address');

		if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {					
			$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);		
		} elseif (isset($this->session->data['guest'])) {
			$shipping_address = $this->session->data['guest']['shipping'];
		}

		if (!empty($shipping_address)) {
			// Admin Products
			$admin_products = array();
			$affiliate_ids = array();
			$this->data['is_admin'] = false;
			foreach($this->cart->getProducts() as $product) {
				if ($product['affiliate_id'] < 1) {
					$admin_products[] = $product['name'];
					$this->data['is_admin'] = true;
				}
				if ($product['affiliate_id'] > 0) {
					$affiliate_ids[] = $product['affiliate_id'];
				}
			}

			// Admin Shipping Methods
			$quote_data = array();

			$this->load->model('setting/extension');

			$results = $this->model_setting_extension->getExtensions('shipping');

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('shipping/' . $result['code']);

					$quote = $this->{'model_shipping_' . $result['code']}->getQuote($shipping_address); 

					if ($quote) {
						$quote_data[$result['code']] = array( 
							'title'      => $quote['title'],
							'quote'      => $quote['quote'], 
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error'],
							'products'   => $admin_products
						);
					}
				}
			}

			$sort_order = array();

			foreach ($quote_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $quote_data);

			$this->session->data['shipping_methods'] = $quote_data;

			$affiliate_ids                       = array_unique($affiliate_ids);
			$this->data['affiliate_ids']         = $affiliate_ids;
			$affiliate_shipping                  = array();
			$this->data['is_affiliate_products'] = false;

			if (!empty($affiliate_ids)) {	

				foreach($affiliate_ids as $affiliate_id) {
					$affiliate_products                  = array();
					foreach($this->cart->getProducts() as $product) {
						if ($product['affiliate_id'] == $affiliate_id) {
							$affiliate_products[] = $product['name'];
						}
					}
					
					foreach ($results as $result) {
						if ($this->config->get($result['code'] . '_status')) {
							$this->load->model('shipping/' . $result['code']);

							$quote = $this->{'model_shipping_' . $result['code']}->getAffiliateQuote($shipping_address, $affiliate_id); 

							if ($quote) {
								$quote_data[$result['code']] = array( 
									'title'        => $quote['title'],
									'quote'        => $quote['quote'], 
									'sort_order'   => $quote['sort_order'],
									'error'        => $quote['error'],
									'affiliate_id' => $affiliate_id,
									'products'	   => $affiliate_products					
								);
							}
						}
			        }

			        $sort_order = array();

					foreach ($quote_data as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}

					array_multisort($sort_order, SORT_ASC, $quote_data);

					$this->session->data['shipping_methods_' . $affiliate_id] = $quote_data;
					$affiliate_shipping[]                                     = $quote_data;
				}

				$this->data['is_affiliate_products'] = true;
				$this->data['affiliate_shipping']    = $affiliate_shipping;				
			} 

		} 

		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$this->data['text_comments']        = $this->language->get('text_comments');
		$this->data['text_other_sellers']   = $this->language->get('text_other_sellers');
		$this->data['button_continue']      = $this->language->get('button_continue');
		$this->data['text_seller']          = $this->language->get('text_seller');
		$this->data['text_shipped_from']    = $this->language->get('text_shipped_from');
		$this->data['text_package_weight']  = $this->language->get('text_package_weight');
		$this->data['text_lbs']             = $this->language->get('text_lbs');

		if (empty($this->session->data['shipping_methods'])) {
			$this->data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
		} else {
			$this->data['error_warning'] = '';
		}	

		if (isset($this->session->data['shipping_methods'])) {
			$this->data['shipping_methods'] = $this->session->data['shipping_methods']; 
		} else {
			$this->data['shipping_methods'] = array();
		}

		if (isset($this->session->data['shipping_method']['code'])) {
			$this->data['code'] = $this->session->data['shipping_method']['code'];
		} else {
			$this->data['code'] = '';
		}

		if (isset($this->session->data['comment'])) {
			$this->data['comment'] = $this->session->data['comment'];
		} else {
			$this->data['comment'] = '';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/shipping_method.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/shipping_method.tpl';
		} else {
			$this->template = 'default/template/checkout/shipping_method.tpl';
		}

		$this->response->setOutput($this->render());
	}

	public function validate() {
		$this->language->load('checkout/checkout');

		$json = array();		

		// Validate if shipping is required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}

		// Validate if shipping address has been set.		
		$this->load->model('account/address');

		if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {					
			$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);		
		} elseif (isset($this->session->data['guest'])) {
			$shipping_address = $this->session->data['guest']['shipping'];
		}

		if (empty($shipping_address)) {								
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}

		// Validate cart has products and has stock.	
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');				
		}	

		// Validate minimum quantity requirments.			
		$products = $this->cart->getProducts();
		$affiliate_ids = array();
		foreach ($products as $product) {
			if ($product['affiliate_id'] > 0) {
				$affiliate_ids[] = $product['affiliate_id'];
			}

			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}		

			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart');

				break;
			}				
		}

		$affiliate_ids = array_unique($affiliate_ids);

		// This works
		if (!$json) {
			if (!isset($this->request->post['shipping_method'])) {
				$is_admin = false;
			}

			if (!$is_admin) {
				if (!$affiliate_ids) {
					$json['error']['warning'] = $this->language->get('error_shipping');					
				} else {
					$this->request->post['shipping_method'] = $this->request->post['shipping_method_' . $affiliate_ids[0]];
				}

			} else {
				$shipping = explode('.', $this->request->post['shipping_method']);

				if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {			
					$json['error']['warning'] = $this->language->get('error_shipping');					
				}
			}

			if (!$json) {
				$shipping = explode('.', $this->request->post['shipping_method']);

				$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

				$this->session->data['comment'] = strip_tags($this->request->post['comment']);
			}							
		}

		// Original code version
		/*if (!$json) {
			if (!isset($this->request->post['shipping_method_9'])) {
				// $json['error']['warning'] = $this->language->get('error_shipping');
				$json['error']['warning'] = 'testing';
			} else {
				$shipping = explode('.', $this->request->post['shipping_method_9']);

				if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {			
					$json['error']['warning'] = $this->language->get('error_shipping');
				}
			}

			if (!$json) {
				$shipping = explode('.', $this->request->post['shipping_method_9']);

				$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

				$this->session->data['comment'] = strip_tags($this->request->post['comment']);
			}							
		}*/

		$this->response->setOutput(json_encode($json));	
	}
}
?>