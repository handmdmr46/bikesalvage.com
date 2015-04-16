<?php
class ModelTotalSubTotal extends Model {

	public function getTotal(&$total_data, &$total, $affiliate_id) {
		$this->language->load('total/sub_total');

		$sub_total = $this->cart->getSubTotalByAffiliateId($affiliate_id);

		if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
			foreach ($this->session->data['vouchers'] as $voucher) {
				$sub_total += $voucher['amount'];
			}
		}

		$total_data[] = array(
			'code'         => 'sub_total',
			'title'        => $this->language->get('text_sub_total'),
			'text'         => $this->currency->format($sub_total),
			'value'        => $sub_total,
			'affiliate_id' => $affiliate_id,
			'sort_order'   => $this->config->get('sub_total_sort_order')
		);

		$total += $sub_total;
	}

	public function getTotalOriginal(&$total_data, &$total, &$taxes) {
		$this->language->load('total/sub_total');

		$sub_total = $this->cart->getSubTotal();

		if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
			foreach ($this->session->data['vouchers'] as $voucher) {
				$sub_total += $voucher['amount'];
			}
		}

		$total_data[] = array(
			'code'       => 'sub_total',
			'title'      => $this->language->get('text_sub_total'),
			'text'       => $this->currency->format($sub_total),
			'value'      => $sub_total,
			'sort_order' => $this->config->get('sub_total_sort_order')
		);

		$total += $sub_total;
	}
}
?>