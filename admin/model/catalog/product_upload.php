<?php
class ModelExtrasBikesalvageEmployee extends Model {
	
	public function addProduct($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "product 						   
						  SET	model = '" . $this->db->escape($data['model']) . "', 
								quantity = '" . (int)$data['quantity'] . "', 
								manufacturer_id = '" . (int)$data['manufacturer_id'] . "', 
								price = '" . (float)$data['price'] . "', 
								weight = '" . (float)$data['weight'] . "', 
								length = '" . (float)$data['length'] . "', 
								width = '" . (float)$data['width'] . "', 
								height = '" . (float)$data['height'] . "', 
								subtract = 1,
								minimum = 1,
								stock_status_id = 5,
								length_class_id = 3,
								status = 1,
								weight_class_id = 5,
								tax_class_id = 9,
								shipping = 1,
								date_added = NOW()");
		
		$product_id = $this->db->getLastId();
		
		// product description & meta description
		foreach ($data['product_description'] as $language_id => $value) {
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET 
			product_id = '" . (int)$product_id . "', 
			language_id = '" . (int)$language_id . "', 
			name = '" . $this->db->escape($value['name']) . "',  
			description = '" . $this->db->escape($value['description']) . "',
			meta_description = '" .$this->db->escape($value['description']). "'
			");
			
		}
		
		// Store, if not assigned product will not show in the front end
		$store_id = 0;
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
		
		// Product name to url alias
		foreach ($data['product_description'] as $language => $value) {
			
			$value['name'] = strtolower($value['name']);
			$value['name'] = explode(" ", $value['name']);
			$value['name'] = implode("-", $value['name']);
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET
			query = 'product_id=" . (int)$product_id . "',
			keyword = '" . $this->db->escape($value['name']) . "'");
		}
		
		// Featured Image
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
		
		// Gallery Images
		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}
		
		// Category
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
		
		
		// Manufacturer to tag 
		if (isset($data['manufacturer'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product_description
			SET tag = '" . $this->db->escape($data['manufacturer']) . "' 
			WHERE product_id = '" . (int)$product_id . "'");
		}
		// Manufacturer to keyword
		if (isset($data['manufacturer'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product_description
			SET meta_keyword = '" . $this->db->escape($data['manufacturer']) . "'
			WHERE product_id = '" . (int)$product_id . "'");
		}
		
		// Shipping methods
		if (isset($data['shipping_type'])) {
			foreach ($data['shipping_type'] as $shipping_id) {
				$this->db->query("INSERT INTO ". DB_PREFIX ."product_to_shipping SET shipping_id = '" . (int)$shipping_id . "' , product_id = '" . (int)$product_id . "'");
			}
		}
		
		// Category to tag and meta_keyword -- had to comment out -- need function to add comma and join all manufacturer and category in one array --
		// for now we are just sending manufacturer to tag and meta_keyword -- since it makes better sense --
		// Category to tag
		/*if (isset($data['product_category'])) {
			
			$query = $this->db->query("SELECT " . DB_PREFIX . "category_description.name, " . DB_PREFIX . "product_to_category.product_id
			  FROM " . DB_PREFIX . "category_description
			  LEFT JOIN " . DB_PREFIX . "product_to_category 
			  ON " . DB_PREFIX . "category_description.category_id = " . DB_PREFIX . "product_to_category.category_id
			  WHERE product_id = '" . (int)$product_id . "'
			  ORDER BY " . DB_PREFIX . "category_description.name");
			
			// try to get multiple categories to tags
			if ( count($query->rows,1) > 1 ) {
				
				foreach ($query->rows as $result) {
					
					$result['name'] = str_replace($result['name'], "{$result['name']},", $result['name']);
					
					$this->db->query("UPDATE " . DB_PREFIX . "product_description
					SET tag = '" . $result['name'] . "'
					WHERE product_id = '" . (int)$product_id . "'");
					
				}
				
			} else {
				
				  foreach ($query->rows as $result) {
					  
					  $this->db->query("UPDATE " . DB_PREFIX . "product_description
					  SET tag = '" . $result['name'] . "'
					  WHERE product_id = '" . (int)$product_id . "'");
					  
				  }
				  
			}
			
			foreach ($query->rows as $result) {
				$this->db->query("UPDATE " . DB_PREFIX . "product_description
				SET tag = '" . $result['name'] . "'
				WHERE product_id = '" . (int)$product_id . "'");
			}	
			
		}*/
		
		// Category to meta_keyword
		/*if (isset($data['product_category'])) {
			
			$query = $this->db->query("SELECT " . DB_PREFIX . "category_description.name, " . DB_PREFIX . "product_to_category.product_id
			  FROM " . DB_PREFIX . "category_description
			  LEFT JOIN " . DB_PREFIX . "product_to_category 
			  ON " . DB_PREFIX . "category_description.category_id = " . DB_PREFIX . "product_to_category.category_id
			  WHERE product_id = '" . (int)$product_id . "'
			  ORDER BY " . DB_PREFIX . "category_description.name");
			
			foreach ($query->rows as $result) {
				$this->db->query("UPDATE " . DB_PREFIX . "product_description
				SET meta_keyword = '" . $result['name'] . "'
				WHERE product_id = '" . (int)$product_id . "'");
			}	
			
		}*/
		
		
		$this->cache->delete('product');
	}
	
	
	public function editProduct($product_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET 
		model = '" . $this->db->escape($data['model']) . "', 
		quantity = '" . (int)$data['quantity'] . "', 
		manufacturer_id = '" . (int)$data['manufacturer_id'] . "', 
		price = '" . (float)$data['price'] . "', 
		weight = '" . (float)$data['weight'] . "', 
		length = '" . (float)$data['length'] . "', 
		width = '" . (float)$data['width'] . "', 
		height = '" . (float)$data['height'] . "', 
		subtract = 1,
		minimum = 1,
		stock_status_id = 5,
		length_class_id = 3,
		status = 1,
		weight_class_id = 5,
		tax_class_id = 9,
		shipping = 1,
		date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");
		
		// product image
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
		
		// product description & meta description
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($data['product_description'] as $language_id => $value) {
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET 
			product_id = '" . (int)$product_id . "', 
			language_id = '" . (int)$language_id . "', 
			name = '" . $this->db->escape($value['name']) . "',  
			description = '" . $this->db->escape($value['description']) . "',
			meta_description = '" .$this->db->escape($value['description']). "'
			");
			
		}
		
		// Product name to url alias
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE  query = 'product_id=" . (int)$product_id . "'");
		
		foreach ($data['product_description'] as $language => $value) {
			
			$value['name'] = strtolower($value['name']);
			$value['name'] = explode(" ", $value['name']);
			$value['name'] = implode("-", $value['name']);
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET
			query = 'product_id=" . (int)$product_id . "',
			keyword = '" . $this->db->escape($value['name']) . "'");
		}
		
		// product image
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}
		
		// category
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}		
		}
		
		// shipping method
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_shipping WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['shipping_type'])) {
			foreach ($data['shipping_type'] as $shipping_id) {
				$this->db->query("INSERT INTO ". DB_PREFIX ."product_to_shipping SET shipping_id = '" . (int)$shipping_id . "' , product_id = '" . (int)$product_id . "'");
			}
		}
		
		// Manufacturer to tag 
		if (isset($data['manufacturer'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product_description
			SET tag = '" . $this->db->escape($data['manufacturer']) . "' 
			WHERE product_id = '" . (int)$product_id . "'");
		}
		// Manufacturer to keyword
		if (isset($data['manufacturer'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product_description
			SET meta_keyword = '" . $this->db->escape($data['manufacturer']) . "'
			WHERE product_id = '" . (int)$product_id . "'");
		}
		
		// Category to tag
		/*if (isset($data['category_url'])) {
			foreach ($data['category_url'] as $category_url) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_description
				SET tag = '" . $this->db->escape($category_url) . "'
				WHERE product_id = '" . (int)$product_id . "'"); 
			}
		}*/
		
		$this->cache->delete('product');
	}
	
	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "') AS keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
				
		return $query->row;
	}
	
	public function getProductDescriptions($product_id) {
		$product_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_description' => $result['meta_description'],
				'tag'              => $result['tag']
			);
		}
		
		return $product_description_data;
	}
	
	public function getProductCategories($product_id) {
		
		$product_category_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}
	
	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		
		return $query->rows;
	}
	
	public function getProductShippingMethods($product_id) {
		
		$shipping_method_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_shipping WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$shipping_method_data[] = $result['shipping_id'];
		}
		
		return $shipping_method_data;
	}
	
	/*public function getCategoryNames($product_id) {
		
		$product_category_info = array();
		
		$query = $this->db->query("SELECT " . DB_PREFIX . "category_description.name, " . DB_PREFIX . "product_to_category.product_id
		FROM " . DB_PREFIX . "category_description
		LEFT JOIN " . DB_PREFIX . "product_to_category 
		ON " . DB_PREFIX . "category_description.category_id = " . DB_PREFIX . "product_to_category.category_id
		WHERE product_id = '" . (int)$product_id . "'
		ORDER BY " . DB_PREFIX . "category_description.name");
		
		foreach ($query->rows as $result) {
			$product_category_info[] = $result['name'];
		}
		
		return $product_category_info;
			
	}*/
	
	
	
}// end class