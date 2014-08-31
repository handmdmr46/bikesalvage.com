<?php
class ModelExtrasProductShipping extends Model {
	
	public function getProductShippingInfo($product_id) {
		
		$product_shipping_method = array();
		
		/*ints:	$query = "SELECT * FROM `$table` WHERE `$column` IN(".implode(',',$array).')';

		strings:	$query = "SELECT * FROM `$table` WHERE `$column` IN('".implode("','",$array).'\')';*/
		
		/*
		if ( count($product_id) > 1 ) {
			
		}else{
			
		}
		*/
		/*
	
		SELECT db_shipping_method.key_id, product_to_shipping.product_id
		FROM db_shipping_method
		LEFT JOIN db_product_to_shipping
		ON db_shipping_method.shipping_id = db_product_to_shipping.shipping_id 
		WHERE product_id IN ( 10388, 10391, 10392, 10393, 10394, 10395 )
	
		*/
		
		if ( count($product_id) > 1 ) {
			
		  $query = $this->db->query("SELECT " . DB_PREFIX . "shipping_method.key_id, " . DB_PREFIX . "product_to_shipping.product_id
		  FROM " . DB_PREFIX . "shipping_method
		  LEFT JOIN " . DB_PREFIX . "product_to_shipping
		  ON " . DB_PREFIX . "shipping_method.shipping_id = " . DB_PREFIX . "product_to_shipping.shipping_id
		  WHERE product_id IN (" . $this->db->escape( implode(', ', $product_id) ) . ") ");
		  
		} else {
		
		  $query = $this->db->query("SELECT " . DB_PREFIX . "shipping_method.key_id, " . DB_PREFIX . "product_to_shipping.product_id
		  FROM " . DB_PREFIX . "shipping_method 
		  LEFT JOIN " . DB_PREFIX . "product_to_shipping 
		  ON " . DB_PREFIX . "shipping_method.shipping_id = " . DB_PREFIX . "product_to_shipping.shipping_id 
		  WHERE product_id = '" . $this->db->escape( implode('', $product_id) ) . "'");
		  
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
	
	
	
}// end class