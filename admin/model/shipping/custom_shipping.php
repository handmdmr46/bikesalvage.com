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
	
	/*public function getProductShippingMethodTest($product_id) {
		
		$product_shipping_method = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "shipping_method 
		LEFT JOIN " . DB_PREFIX . "product_to_shipping 
		ON " . DB_PREFIX . "shipping_method.shipping_id = " . DB_PREFIX . "product_to_shipping.shipping_id 
		WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			
			$product_shipping_method[] = array(
				'shipping_id'		=> 	$result['shipping_id'],
				'method_name'		=>	$result['method_name'],
				'zone'				=>	$result['zone'],
				'group'				=>	$result['group'],
				'key'				=>	$result['key'],
				'value'				=> 	$result['value'],
				'product_id'		=>	$result['product_id']
			);
		}
		
		return $product_shipping_method;
		
	}*/
	
	//$product_id = $this->db->getLastId();
	
	
}// end class