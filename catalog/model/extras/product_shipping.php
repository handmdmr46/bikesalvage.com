<?php
class ModelExtrasProductShipping extends Model {
	
	public function getProductShippingMethods($product_id) {
		
		$product_shipping_method = array();
		
		if ( count($product_id) > 1 ) {
			
		  $query = $this->db->query("SELECT    sm.key_id, 
		  	                                   p2s.product_id
								     FROM      " . DB_PREFIX . "shipping_method sm
								     LEFT JOIN " . DB_PREFIX . "product_to_shipping p2s ON  (sm.shipping_id = p2s.shipping_id)
								     WHERE     product_id IN (" . $this->db->escape( implode(', ', $product_id) ) . ") ");
		  
		} else {
		
		  $query = $this->db->query("SELECT    sm.key_id, 
		  	                                   p2s.product_id
								     FROM      " . DB_PREFIX . "shipping_method sm
								     LEFT JOIN " . DB_PREFIX . "product_to_shipping p2s ON (sm.shipping_id = p2s.shipping_id)
								     WHERE     product_id = '" . $this->db->escape( implode('', $product_id) ) . "'");
		  
		}
		
		if ($query->num_rows > 0) {
			foreach ($query->rows as $result) {
				$product_shipping_method[] = array(
					'key_id'			=>	$result['key_id'],
					'product_id'		=>	$result['product_id']
				);
			}
		}
		return $product_shipping_method;
		
	}

	public function getProductShippingMethod($product_id) {
		$query = $this->db->query("SELECT    sm.key_id 
								   FROM      " . DB_PREFIX . "shipping_method sm
								   LEFT JOIN " . DB_PREFIX . "product_to_shipping p2s ON (sm.shipping_id = p2s.shipping_id)
								   WHERE     p2s.product_id = '" . (int)$product_id . "'");

		
		/*if ($query->num_rows > 0) {
			return $query->rows;
		} else {
			return array($this->config->get('domestic_shipping_default_id'), $this->config->get('domestic_shipping_default_id')); 
		}*/
		return $query->rows;
	}
	
	
	
}// end class