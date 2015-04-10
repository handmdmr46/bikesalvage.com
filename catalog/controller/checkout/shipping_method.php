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
			$affiliate_ids = array();
			$product_names = array();
			$quote_data    = array();

			$this->load->model('setting/extension');

			foreach($this->cart->getProducts() as $product) {
				$affiliate_ids[] = $product['affiliate_id'];
			}

			$affiliate_ids = array_unique($affiliate_ids);
			$this->data['affiliate_ids'] = $affiliate_ids;

			foreach($affiliate_ids as $affiliate_id) {
				foreach($this->cart->getProducts() as $product) {
					if ($product['affiliate_id'] == $affiliate_id) {
						$product_names[] = $product['name'];
					}
				}

				$results = $this->model_setting_extension->getExtensions('shipping'); // maybe move this out of the affiliate_ids loop? Maybe have different shipping option for affilites?

				foreach ($results as $key => $result) {
					if ($this->config->get($result['code'] . '_status')) { // usps_status
						$this->load->model('shipping/' . $result['code']); // shipping/usps

						$quote = $this->{'model_shipping_' . $result['code']}->getAffiliateQuote($shipping_address, $affiliate_id); // model_shipping_usps

						if ($quote) {
							$quote_data[$result['code']] = array(
								'info'       => $quote['info'],
								'quote'      => $quote['quote'],
								'sort_order' => $quote['sort_order'],
								'error'      => $quote['error'],
								'products'   => $product_names
							);
						}
					}
				}

				$sort_order = array();

				foreach ($quote_data as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $quote_data);

				$this->session->data['shipping_methods_' . $affiliate_id] = $quote_data[$result['code']];

				if (empty($this->session->data['shipping_methods_' . $affiliate_id])) {
					$this->data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
				} else {
					$this->data['error_warning'] = '';
				}

				if (isset($this->session->data['shipping_methods_' . $affiliate_id])) {
					$this->data['shipping_methods'][$affiliate_id] = $this->session->data['shipping_methods_' . $affiliate_id];
				} else {
					$this->data['shipping_methods'][$affiliate_id] = array();
				}
			}

			if (isset($this->session->data['comment'])) {
				$this->data['comment'] = $this->session->data['comment'];
			} else {
				$this->data['comment'] = '';
			}

			/*if (isset($this->session->data['shipping_method']['code'])) {
				$this->data['code'] = $this->session->data['shipping_method']['code'];
			} else {
				$this->data['code'] = '';
			}*/

			if (isset($this->session->data['shipping_methods_' . $affiliate_ids[0]]['quote']['code'])) {
				$this->data['code'] = $this->session->data['shipping_methods_' . $affiliate_ids[0]]['quote']['code'];
			} else {
				$this->data['code'] = '';
			}
		}

		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$this->data['text_comments']        = $this->language->get('text_comments');
		$this->data['text_other_sellers']   = $this->language->get('text_other_sellers');
		$this->data['button_continue']      = $this->language->get('button_continue');
		$this->data['text_seller']          = $this->language->get('text_seller');
		$this->data['text_shipped_from']    = $this->language->get('text_shipped_from');
		$this->data['text_package_weight']  = $this->language->get('text_package_weight');

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

		$affiliate_ids = array();

		// Validate minimum quantity requirments.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
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
			$affiliate_ids[] = $product['affiliate_id'];
		}

		$affiliate_ids = array_unique($affiliate_ids);

		if (!$json) {
			foreach ($affiliate_ids as $affiliate_id) {
				if (!isset($this->request->post['shipping_method'][$affiliate_id])) {
					// $json['error']['warning'] = $this->language->get('error_shipping');
					$json['error']['warning'] = 'testingERROR_2';
				} else {
					$shipping = explode('.', $this->request->post['shipping_method'][$affiliate_id]);

					if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods_' . $affiliate_id]['quote'])) {
						// $json['error']['warning'] = $this->language->get('error_shipping');
						$json['error']['warning'] = 'testingERROR_1';
					}
				}
			}

			if (!$json) {
				// keep this incase shipping comments, one shipping comment for all affiliates, may update in the future
				$this->session->data['comment'] = strip_tags($this->request->post['comment']);
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>