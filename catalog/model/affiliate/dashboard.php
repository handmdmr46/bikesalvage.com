<?php
class ModelAffiliateDashboard extends Model {
	/*
	*   --  MODELS --
	* catalog/product
	* catalog/option
	*
	*/

	/**
	* Gets affiliate products only for current logged in affiliate
	*/
	public function getProduct($product_id) {
		$affiliate_id = $this->affiliate->getId();

		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "')
									AS keyword FROM " . DB_PREFIX . "product p
									LEFT JOIN " . DB_PREFIX . "product_description pd
									ON (p.product_id = pd.product_id)
									WHERE p.product_id = '" . (int)$product_id . "'
									AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
									AND p.affiliate_id = '" . (int)$affiliate_id . "'");

		return $query->row;
	}

	/**
	* Adds product, includes affiliate_id of current logged in affiliate
	*/
	public function addProduct($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "product SET
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
		date_added = NOW(),
		affiliate_id = '" . $this->affiliate->getId() . "'");

		$product_id = $this->db->getLastId();
		$affiliate_id = $this->affiliate->getId();

		/*if ($this->affiliate->isLogged()) {
			$this->db->query("	INSERT INTO " . DB_PREFIX . "product_to_affiliate
								SET product_id = '" . (int)$product_id . "',
								affiliate_id = '" . (int)$affiliate_id . "'");
		}*/

		// product description & meta description
		foreach ($data['product_description'] as $language_id => $value) {

			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET
			product_id = '" . (int)$product_id . "',
			language_id = '" . (int)$language_id . "',
			name = '" . $this->db->escape($value['name']) . "',
			description = '" . $this->db->escape(TRIM($value['description'])) . "',
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

		// featured image
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product
								SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "'
								WHERE product_id = '" . (int)$product_id . "'");
		}

		// gallery image
		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image
									SET product_id = '" . (int)$product_id . "',
									image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "',
									sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}

		// category
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

		$this->cache->delete('product');
		$this->cache->delete('dashboard');
	}

	/**
	* Gets products for current logged in affiliate
	*/
	public function getProducts($data = array()) {
		$affiliate_id = $this->affiliate->getId();

		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

		$sql .= " WHERE p.affiliate_id = '" . (int)$affiliate_id . "'";

		if (!empty($data['filter_category_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
		}

		$sql .= " AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (!empty($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	* Gets products total for current logged in affiliate
	*/
	public function getTotalProducts($data = array()) {
		$affiliate_id = $this->affiliate->getId();

		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

		if (!empty($data['filter_category_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
		}

		$sql .= " WHERE p.affiliate_id = '" . (int)$affiliate_id . "'";

		$sql .= " AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (!empty($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	/**
	* Edits product for current logged in affiliate
	*/
	public function editProduct($product_id, $data) {
		$affiliate_id = $this->affiliate->getId();

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
		date_modified = NOW()
		WHERE product_id = '" . (int)$product_id . "'
		AND affiliate_id = '" . (int)$affiliate_id . "'");

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
		$this->cache->delete('dashboard');
	}

	/**
	* Deletes products by product_id
	*/
	public function deleteProduct($product_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id. "'");

		$this->cache->delete('product');
		$this->cache->delete('dashboard');
	}

	public function resizeImage($filename, $width, $height) {
		if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
			return;
		}

		$info = pathinfo($filename);

		$extension = $info['extension'];

		$old_image = $filename;
		$new_image = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

		if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
			$path = '';

			$directories = explode('/', dirname(str_replace('../', '', $new_image)));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!file_exists(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}

			$image = new Image(DIR_IMAGE . $old_image);
			$image->resize($width, $height);
			$image->save(DIR_IMAGE . $new_image);
		}

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			return HTTPS_CATALOG . 'image/' . $new_image;
		} else {
			return HTTP_CATALOG . 'image/' . $new_image;
		}
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
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

	public function getManufacturer($manufacturer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer m
									LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s
									ON (m.manufacturer_id = m2s.manufacturer_id)
									WHERE m.manufacturer_id = '" . (int)$manufacturer_id . "'
									AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		return $query->row;
	}

	public function getManufacturers($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "manufacturer";

		if (!empty($data['filter_name'])) {
			$sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT *,
									(SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR ' &gt; ')
									  FROM " . DB_PREFIX . "category_path cp
									  LEFT JOIN " . DB_PREFIX . "category_description cd1
									  ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id)
									  WHERE cp.category_id = c.category_id
									  AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "'
									  GROUP BY cp.category_id) AS path,
									  (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "')
										AS keyword FROM " . DB_PREFIX . "category c
										LEFT JOIN " . DB_PREFIX . "category_description cd2
										ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "'
										AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getCategories($data) {
		$sql = "SELECT cp.category_id AS category_id,
				GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' &gt; ')
				AS name, c.parent_id, c.sort_order
				FROM " . DB_PREFIX . "category_path cp
				LEFT JOIN " . DB_PREFIX . "category c
				ON (cp.path_id = c.category_id)
				LEFT JOIN " . DB_PREFIX . "category_description cd1
				ON (c.category_id = cd1.category_id)
				LEFT JOIN " . DB_PREFIX . "category_description cd2
				ON (cp.category_id = cd2.category_id)
				WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "'
				AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " GROUP BY cp.category_id ORDER BY name";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getProductCategories($product_id) {
		$product_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}

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

	public function getProductShippingInfo($product_id) {
		$product_shipping_method = array();

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

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "'");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'points'                  => $product_option_value['points'],
					'points_prefix'           => $product_option_value['points_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'product_option_value' => $product_option_value_data,
				'option_value'         => $product_option['option_value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}

	public function getOption($option_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option` o
									LEFT JOIN " . DB_PREFIX . "option_description od
									ON (o.option_id = od.option_id)
									WHERE o.option_id = '" . (int)$option_id . "'
									AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getOptions($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "option` o
				LEFT JOIN " . DB_PREFIX . "option_description od
				ON (o.option_id = od.option_id)
				WHERE od.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$sql .= " AND od.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'od.name',
			'o.type',
			'o.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY od.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getOptionValue($option_value_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value ov
									LEFT JOIN " . DB_PREFIX . "option_value_description ovd
									ON (ov.option_value_id = ovd.option_value_id)
									WHERE ov.option_value_id = '" . (int)$option_value_id . "'
									AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getOptionValues($option_id) {
		$option_value_data = array();

		$option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value ov
													LEFT JOIN " . DB_PREFIX . "option_value_description ovd
													ON (ov.option_value_id = ovd.option_value_id)
													WHERE ov.option_id = '" . (int)$option_id . "'
													AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'
													ORDER BY ov.sort_order ASC");

		foreach ($option_value_query->rows as $option_value) {
			$option_value_data[] = array(
				'option_value_id' => $option_value['option_value_id'],
				'name'            => $option_value['name'],
				'image'           => $option_value['image'],
				'sort_order'      => $option_value['sort_order']
			);
		}

		return $option_value_data;
	}

	public function getOptionValueDescriptions($option_id) {
		$option_value_data = array();

		$option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value WHERE option_id = '" . (int)$option_id . "'");

		foreach ($option_value_query->rows as $option_value) {
			$option_value_description_data = array();

			$option_value_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value_description WHERE option_value_id = '" . (int)$option_value['option_value_id'] . "'");

			foreach ($option_value_description_query->rows as $option_value_description) {
				$option_value_description_data[$option_value_description['language_id']] = array('name' => $option_value_description['name']);
			}

			$option_value_data[] = array(
				'option_value_id'          => $option_value['option_value_id'],
				'option_value_description' => $option_value_description_data,
				'image'                    => $option_value['image'],
				'sort_order'               => $option_value['sort_order']
			);
		}

		return $option_value_data;
	}

	public function getTotalOptions() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "option`");

		return $query->row['total'];
	}

	public function getFilter($filter_id) {
		$query = $this->db->query("SELECT *, (SELECT name FROM " . DB_PREFIX . "filter_group_description fgd
									WHERE f.filter_group_id = fgd.filter_group_id
									AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "')
									AS `group` FROM " . DB_PREFIX . "filter f
									LEFT JOIN " . DB_PREFIX . "filter_description fd
									ON (f.filter_id = fd.filter_id)
									WHERE f.filter_id = '" . (int)$filter_id . "'
									AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getFilters($data) {
		$sql = "SELECT *, (SELECT name FROM " . DB_PREFIX . "filter_group_description fgd WHERE f.filter_group_id = fgd.filter_group_id AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "')
				AS `group`
				FROM " . DB_PREFIX . "filter f
				LEFT JOIN " . DB_PREFIX . "filter_description fd
				ON (f.filter_id = fd.filter_id)
				WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND fd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " ORDER BY f.sort_order ASC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
}//end class