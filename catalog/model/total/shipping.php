<?php
class ModelTotalShipping extends Model {

	public function getTotal(&$total_data, &$total, $affiliate_id) {
		if ($this->cart->hasShipping() && isset($this->session->data['shipping_methods_' . $affiliate_id])) {
			$total_data[] = array(
				'code'         => 'shipping',
				'title'        => $this->session->data['shipping_methods_' . $affiliate_id]['quote'][0]['title'],
				'text'         => $this->currency->format($this->session->data['shipping_methods_' . $affiliate_id]['quote'][0]['cost']),
				'value'        => $this->session->data['shipping_methods_' . $affiliate_id]['quote'][0]['cost'],
				'affiliate_id' => $affiliate_id,
				'sort_order'   => $this->config->get('shipping_sort_order')
			);

			$total += $this->session->data['shipping_methods_' . $affiliate_id]['quote'][0]['cost'];
		}
	}

	public function getTotalOriginal(&$total_data, &$total, &$taxes) {
		if ($this->cart->hasShipping() && isset($this->session->data['shipping_method'])) {
			$total_data[] = array(
				'code'       => 'shipping',
				'title'      => $this->session->data['shipping_method']['title'],
				'text'       => $this->currency->format($this->session->data['shipping_method']['cost']),
				'value'      => $this->session->data['shipping_method']['cost'],
				'sort_order' => $this->config->get('shipping_sort_order')
			);

			if ($this->session->data['shipping_method']['tax_class_id']) {
				$tax_rates = $this->tax->getRates($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($taxes[$tax_rate['tax_rate_id']])) {
						$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}

			$total += $this->session->data['shipping_method']['cost'];
		}
	}
}
?>