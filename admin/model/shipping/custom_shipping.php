<?php 
class ModelShippingCustomShipping extends Model {
	
	public function getShippingMethods() {
		
		$shipping_methods = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "shipping_method ORDER BY shipping_id ASC");
		
		foreach ($query->rows as $result) {
			
			$shipping_methods[] = array(
				'shipping_id'	=> $result['shipping_id'],
				'method_name'	=> $result['method_name'],
				'zone'			=> $result['zone']
			);
			
		}
		
		return $shipping_methods;
	}
	
	public function getProductShippingMethods($product_id) {
		
		$shipping_method_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_shipping WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$shipping_method_data[] = $result['shipping_id'];
		}
		
		return $shipping_method_data;
	}
	
}// end class