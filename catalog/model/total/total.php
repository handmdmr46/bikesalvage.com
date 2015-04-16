<?php
class ModelTotalTotal extends Model {
	public function getTotal(&$total_data, &$total, $affiliate_id) {
		$this->language->load('total/total');

		$sub_total = $this->cart->getSubTotalByAffiliateId($affiliate_id);

		if (isset($this->session->data['shipping_methods_' . $affiliate_id]['quote'][0]['cost'])) {
			$shipping_total = $this->session->data['shipping_methods_' . $affiliate_id]['quote'][0]['cost'];
		} else {
			$shipping_total = 0;
		}

		$total = $sub_total + $shipping_total;

		$total_data[] = array(
			'code'         => 'total',
			'title'        => $this->language->get('text_total'),
			'text'         => $this->currency->format(max(0, $total)),
			'value'        => max(0, $total),
			'affiliate_id' => $affiliate_id,
			'sort_order'   => $this->config->get('total_sort_order')
		);
	}

	public function getTotalOriginal(&$total_data, &$total, &$taxes) {
		$this->language->load('total/total');

		$total_data[] = array(
			'code'       => 'total',
			'title'      => $this->language->get('text_total'),
			'text'       => $this->currency->format(max(0, $total)),
			'value'      => max(0, $total),
			'sort_order' => $this->config->get('total_sort_order')
		);
	}
}
?>