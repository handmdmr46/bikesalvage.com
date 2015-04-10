<?php 
class ModelShippingCustomShipping extends Model {
	
	public function getShippingMethods() {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "shipping_method ORDER BY shipping_id ASC");
				
		return $query->rows;
	}
	
	public function getProductShippingMethods($product_id) {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_shipping WHERE product_id = '" . (int)$product_id . "'");
		
		return $query->rows;
	}
}