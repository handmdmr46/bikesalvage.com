<?php
class ModelShippingFlat extends Model {
	function getQuote($address) {
		$this->language->load('shipping/flat');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('flat_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('flat_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$quote_data = array();

			$quote_data['flat'] = array(
				'code'         => 'flat.flat',
				'title'        => $this->language->get('text_description'),
				'cost'         => $this->config->get('flat_cost'),
				'tax_class_id' => $this->config->get('flat_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate($this->config->get('flat_cost'), $this->config->get('flat_tax_class_id'), $this->config->get('config_tax')))
			);
			
			$weight = $this->weight->convert($this->cart->getWeightByAffiliateId(0), $this->config->get('config_weight_class_id'), $this->config->get('usps_weight_class_id'));
			$title  = '<b>Seller:</b> ' . $this->config->get('config_owner');
			$title  .= ' <b>Shipped From:</b> ' . $this->config->get('config_address');
			$title  .= ' <b>Package Weight:</b> ' . $this->weight->format($weight, $this->config->get('usps_weight_class_id')) . 'lbs';

			$method_data = array(
				'code'       => 'flat',
				'title'      => $title,
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('flat_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}

	function getAffiliateQuote($address, $affiliate_id) {
		$this->language->load('shipping/flat');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone 
			                       WHERE geo_zone_id = '" . (int)$this->config->get('flat_geo_zone_id') . "' 
			                       AND country_id = '" . (int)$address['country_id'] . "' 
			                       AND (zone_id = '" . (int)$address['zone_id'] . "' 
			                       	OR zone_id = '0')");

		if (!$this->config->get('flat_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$quote_data = array();

			$quote_data['flat'] = array(
				'code'         => 'flat.flat',
				'title'        => $this->language->get('text_description'),
				'cost'         => $this->config->get('flat_cost'),
				'tax_class_id' => $this->config->get('flat_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate($this->config->get('flat_cost'), $this->config->get('flat_tax_class_id'), $this->config->get('config_tax')))
			);

			$title_data = array();

			$title_data[] = $this->getAffiliateShippingInfo($affiliate_id);

			$method_data = array(
				'code'       => 'flat',
				'title'      => $title_data,
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('flat_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}

	function getAffiliateShippingInfo($affiliate_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "affiliate a WHERE a.affiliate_id = '" . (int)$affiliate_id . "'");
			                                                  
		$zone = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone z WHERE z.zone_id = '" . (int)$query->row['zone_id'] . "'");

		$weight = $this->weight->convert($this->cart->getWeightByAffiliateId($affiliate_id), $this->config->get('config_weight_class_id'), $this->config->get('usps_weight_class_id'));

		$address = array(
			'firstname'  => $query->row['firstname'],
			'lastname'   => $query->row['lastname'],
			'state_name' => $zone->row['name'],
			'state_code' => $zone->row['code'],
			'city'       => $query->row['city'],
			'postcode'   => $query->row['postcode'],
			'address_1'  => $query->row['address_1'],
			'address_2'  => $query->row['address_2'],
			'weight'     => $this->weight->format($weight, $this->config->get('usps_weight_class_id'))
		);

		return $address;
	}


}// end class
?>