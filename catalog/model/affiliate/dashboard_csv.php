<?php
class ModelAffiliateDashboardCsv extends Model {

	public function getManufacturerName($manufacturer_id) {
		$query = $this->db->query("SELECT `name` FROM " . DB_PREFIX . "manufacturer WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");

		return $query->row['name'];
	}

	public function getCategoryName($category_id) {
		$query = $this->db->query("SELECT `name` FROM " . DB_PREFIX . "category_description WHERE `category_id` = '" . (int)$category_id . "'");

		return $query->row['name'];
	}

	public function getProductCategories($manufacturer_id) {
		$sql = "SELECT     c.category_id,
						   cd.name AS 'category_name'
				FROM       " . DB_PREFIX . "category c
				LEFT JOIN  " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id)
				WHERE      c.manufacturer_id = '" . (int)$manufacturer_id . "'
				ORDER BY   name ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getInternationalShippingMethods() {
		$sql = "SELECT  `shipping_id`,
						 `method_name`
				FROM    " . DB_PREFIX . "shipping_method
				WHERE   `zone` = 'international'";

		$query = $this->db->query($sql);


		foreach($query->rows as $result) {
			$methods[] = array(
			  'shipping_id'	=> $result['shipping_id'],
			  'shipping_name'	=> $result['method_name']
			);
		}
		return $methods;
	}

	public function getDomesticShippingMethods() {
		$sql = "SELECT  `shipping_id`,
						 `method_name`
				FROM    " . DB_PREFIX . "shipping_method
				WHERE   `zone` = 'domestic'";

		$query = $this->db->query($sql);

		foreach($query->rows as $result) {
			$methods[] = array(
			  'shipping_id'	=> $result['shipping_id'],
			  'shipping_name'	=> $result['method_name']
			);
		}

		return $methods;
	}

	public function getShippingMethodName($shipping_id) {
		$query = $this->db->query("SELECT `method_name` FROM " . DB_PREFIX . "shipping_method WHERE `shipping_id` = '" . (int)$shipping_id . "'");

		return $query->row['method_name'];
	}

	/**
	* NOTE: $methods[0] = domestic shipping
	*       $methods[1] = international shipping
	*/
	public function getProductShippingMethods($product_id) {
		$query = $this->db->query("SELECT     sm.method_name,
												 sm.shipping_id
									 FROM       " . DB_PREFIX . "product_to_shipping pts
									 LEFT JOIN  " . DB_PREFIX . "shipping_method sm ON pts.shipping_id = sm.shipping_id
									 WHERE		 `product_id` = '" . (int)$product_id . "'");

		return $query->rows;
	}

	public function getProductShippingIds($product_id) {
		$query = $this->db->query("SELECT     sm.shipping_id
									 FROM       " . DB_PREFIX . "product_to_shipping pts
									 LEFT JOIN  " . DB_PREFIX . "shipping_method sm ON pts.shipping_id = sm.shipping_id
									 WHERE		 `product_id` = '" . (int)$product_id . "'");

		$method_id = array();
		foreach($query->rows as $result) {
			$method_id[] =  $result['shipping_id'];
		}

		return $method_id;
	}

	public function getProductGalleryImages($product_id) {
		$query = $this->db->query("SELECT `image` FROM " . DB_PREFIX . "product_image WHERE `product_id` = '" . (int)$product_id . "'");

		$images = array();
		foreach($query->rows as $result) {
			$images[] = $result['image'];
		}

		return $images;
	}

	public function getTotalGalleryImages($product_id) {
		$count = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_image WHERE `product_id` = '" . (int)$product_id . "'");
		return $count->row['total'];
	}

	public function addAffiliateCsvImportProduct($affiliate_id, $data) {

		$image_array = explode('|', $data['image']);
		$featured_image = $image_array[0];

		$this->db->query("INSERT INTO " . DB_PREFIX . "product
							SET `model` = '" . $this->db->escape($data['model']) . "',
								`quantity` = '" . (int)$data['quantity'] . "',
								`image` = '" . $this->db->escape(substr($featured_image, 1)) . "',
								`manufacturer_id` = '" . (int)$data['manufacturer_id'] . "',
								`price` = '" . (int)$data['price'] . "',
								`weight` =  '" . (int)$data['weight'] . "',
								`length` = '" . (int)$data['length'] . "',
								`width` = '" . (int)$data['width'] . "',
								`height` = '" . (int)$data['height'] . "',
								`length_class_id` = '3',
								`weight_class_id` = '5',
								`status` = '0',
								`stock_status_id` = '5',
								`tax_class_id` = '9',
								`date_added` = NOW(),
								`affiliate_id` = '" . (int)$affiliate_id . "'");

		$product_id = $this->db->getLastId();

		$manufacturer_name = $this->getManufacturerName((int)$data['manufacturer_id']);

		$this->db->query("INSERT INTO " . DB_PREFIX . "product_description
							SET	 `product_id` = '" . (int)$product_id . "',
								  `language_id` = '1',
								  `name` = '" . $this->db->escape($data['title']) . "',
								  `description` = '" . $this->db->escape(TRIM($data['description'])) . "',
								  `meta_description` = '" . $this->db->escape($data['title']) . "',
								  `meta_keyword` = '" . $manufacturer_name . "',
								  `tag` = '" . $manufacturer_name . "'");

		$isFirst = true;
		foreach($image_array as $image) {
			if($isFirst) {
				$isFirst = false;
				continue;
			}
			$image = substr(parse_url($image, PHP_URL_PATH), 1);
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_image  SET `product_id` = '" . (int)$product_id . "', `image` = '" . $this->db->escape($image) . "'");
		}

		$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET `product_id` = '" . (int)$product_id . "', `category_id` = '" . (int)$data['category_id'] . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_shipping SET `product_id` = '" . (int)$product_id . "', `shipping_id` = '" . (int)$data['shipping_dom'] . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_shipping SET `product_id` = '" . (int)$product_id . "', `shipping_id` = '" . (int)$data['shipping_intl'] . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET `product_id` = '" . (int)$product_id . "', `store_id` = '0'");
	}

	public function getTotalCsvImportProducts($affiliate_id) {
	    $query = $this->db->query("SELECT `product_id` FROM " . DB_PREFIX . "product WHERE `status` = '0' AND `affiliate_id` = '" . (int)$affiliate_id . "'");

		$pids = array();
		foreach($query->rows as $result) {
			$pids[] = $result['product_id'];
		}
		if(count($pids) >= 1) {
			$count = $this->db->query("SELECT COUNT(DISTINCT product_id) AS total FROM " . DB_PREFIX . "product WHERE product_id IN (" . $this->db->escape(implode(',',$pids)) . ")");
			return $count->row['total'];
		}
		return null;
	}

	public  function getTotalUnlinkedProducts() {
		$query = $this->db->query("SELECT `product_id` FROM " . DB_PREFIX . "product WHERE `status` = '0' AND `affiliate_id` = '" . $this->affiliate->getId() . "'");

		$pids = array();

		foreach($query->rows as $result) {
			$pids[] = $result['product_id'];
		}
		if(count($pids) >= 1) {
			$count = $this->db->query("SELECT COUNT(*) AS `total` FROM " . DB_PREFIX . "product_description WHERE `product_id` IN (" . $this->db->escape(implode(',',$pids)) . ")");
			return $count->row['total'];
		}
		return null;
	}

	public  function getTotalLinkedProducts() {
		$count = $this->db->query("SELECT COUNT(*) AS `total` FROM " . DB_PREFIX . "affiliate_product_link WHERE `affiliate_id` = '" . (int)$this->affiliate->getId() . "'");

		return $count->row['total'];
	}

	public function getProductLinkEbayId($product_id) {
		$query = $this->db->query("SELECT ebay_item_id FROM " . DB_PREFIX . "ebay_listing WHERE product_id = '" . (int)$product_id . "'");

		if ($query->num_rows > 0) {
			return $query->row['ebay_item_id'];
		} else {
			return '';
		}
	}

	public function getCsvImportProductInfo($start, $limit, $affiliate_id) {

		$sql = "SELECT     p.model,
						   p.product_id,
						   p.quantity,
						   p.image,
						   p.price,
						   p.weight,
						   p.length,
						   p.width,
						   p.height,
						   pd.name AS 'product_name',
						   pd.description AS 'product_description',
						   cd.name AS 'category_name',
						   cd.category_id,
						   m.name AS 'manufacturer_name',
						   m.manufacturer_id
			    FROM       " . DB_PREFIX . "product p
			    LEFT JOIN  " . DB_PREFIX . "product_description pd ON p.product_id = pd.product_id
			    LEFT JOIN  " . DB_PREFIX . "product_to_category ptc ON p.product_id = ptc.product_id
			    LEFT JOIN  " . DB_PREFIX . "category_description cd ON ptc.category_id = cd.category_id
			    LEFT JOIN  " . DB_PREFIX . "manufacturer m ON p.manufacturer_id = m.manufacturer_id
			    WHERE  p.status = '0'
			    AND    p.affiliate_id = '" . (int)$affiliate_id . "'
			    AND    p.csv_import = '1'";

	    if(isset($start) || isset($limit)) {
			if($start < 0) {
				$start = 0;
			}
			if($limit < 1) {
				$limit = 20;
			}

		    $sql .= " LIMIT " . (int)$start . "," . (int)$limit;
		}

		$query_product = $this->db->query($sql);

		$product_data = array();

		foreach($query_product->rows as $data) {

				$product_data[] = array(
					'product_id'        => $data['product_id'],
					'model'             => $data['model'],
					'quantity'          => $data['quantity'],
					'featured_image'    => $data['image'],
					'price'             => $data['price'],
					'weight'            => $data['weight'],
					'length'            => $data['length'],
					'width'             => $data['width'],
					'height'            => $data['height'],
					'product_name'      => $data['product_name'],
					'description'       => $data['product_description'],
					'category_name'     => $data['category_name'],
					'category_id'       => $data['category_id'],
					'manufacturer_name' => $data['manufacturer_name'],
					'manufacturer_id'   => $data['manufacturer_id'],
					'gallery_images'    => $this->getProductGalleryImages($data['product_id']),
					'shipping_methods'  => $this->getProductShippingMethods($data['product_id']),
					'categories'        => $this->getProductCategories($data['manufacturer_id']),
					'selected'          => isset($this->request->post['selected']) && in_array($data['product_id'], $this->request->post['selected'])
				);
		}

		return $product_data;
	}

    public function editList($product_id, $edit_data) {
		if (isset($edit_data['price'])  || isset($edit_data['quantity'])     || isset($edit_data['model'])  ||
			isset($edit_data['weight']) || isset($edit_data['lenght'])       || isset($edit_data['width'])  ||
			isset($edit_data['height']) || isset($edit_data['manufacturer']) || isset($edit_data['featured_image'])) {


			$this->db->query("UPDATE " . DB_PREFIX . "product
								SET    `price` = '" . (float)$edit_data['price'] . "',
										`quantity` = '" . (int)$edit_data['quantity'] . "',
										`model` = '" . $this->db->escape($edit_data['model']) . "',
										`weight` = '" . (float)$edit_data['weight'] . "',
										`length` = '" . (float)$edit_data['length'] . "',
										`width` = '" . (float)$edit_data['width'] . "',
										`height` = '" . (float)$edit_data['height'] . "',
										`manufacturer_id` = '" . (int)$edit_data['manufacturer'] . "',
										`image` = '" . $this->db->escape($edit_data['featured_image']) . "'
								WHERE   `product_id` = '" . (int)$product_id . "'");
		}

		if (isset($edit_data['name']) || isset($edit_data['description'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product_description
								SET   `name` = '" . $this->db->escape($edit_data['name']) . "',
									  `description` = '" . $this->db->escape(TRIM($edit_data['description'])) . "'
								WHERE `product_id` = '" . (int)$product_id . "'");
		}

		if (isset($edit_data['category'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE `product_id` = '" . (int)$product_id . "'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET `category_id` = '" . (int)$edit_data['category'] . "', `product_id` = '" . (int)$product_id . "'");
		}

		if (isset($edit_data['shipping_dom']) || isset($edit_data['shipping_intl']) ) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_shipping WHERE `product_id` = '" . (int)$product_id . "'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_shipping SET `shipping_id` = '" . (int)$edit_data['shipping_dom'] . "',  `product_id` = '" . (int)$product_id . "'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_shipping SET `shipping_id` = '" . (int)$edit_data['shipping_intl'] . "', `product_id` = '" . (int)$product_id . "'");
		}

		if (isset($edit_data['gallery_images']) ) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE `product_id` = '" . (int)$product_id . "'");
			foreach($edit_data['gallery_images'] as $gallery_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET `image` = '" . $this->db->escape($gallery_image) . "' , `product_id` = '" . (int)$product_id . "'");
			}
		}
    }

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
	}

	public function getManufacturers() {
		$sql = "SELECT * FROM " . DB_PREFIX . "manufacturer";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getEbaySiteIds() {
		$sql = "SELECT * FROM " . DB_PREFIX . "ebay_site_ids";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getEbayProfile($affiliate_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "ebay_settings WHERE `affiliate_id` = '" . (int)$affiliate_id . "'";
		$query = $this->db->query($sql);
		return $query->row;
	}

	public function setEbayProfile($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "ebay_settings
						  SET			`compat` = '" . (int)$data['compatability_level'] . "',
										`user_token` = '" . $this->db->escape($data['user_token']) . "',
										`application_id` = '" . $this->db->escape($data['application_id']) . "',
										`developer_id` = '" . $this->db->escape($data['developer_id']) . "',
										`certification_id` = '" . $this->db->escape($data['certification_id']) . "',
										`site_id` = '" . $this->db->escape($data['site_id']) . "',
										`affiliate_id` = '" . (int)$this->affiliate->getId() . "'");
	}

	public function updateEbayProfile($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "ebay_settings WHERE affiliate_id = '" . (int)$this->affiliate->getId() . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "ebay_settings
						  SET			`compat` = '" . (int)$data['compatability_level'] . "',
										`user_token` = '" . $this->db->escape($data['user_token']) . "',
										`application_id` = '" . $this->db->escape($data['application_id']) . "',
										`developer_id` = '" . $this->db->escape($data['developer_id']) . "',
										`certification_id` = '" . $this->db->escape($data['certification_id']) . "',
										`site_id` = '" . $this->db->escape($data['site_id']) . "',
										`affiliate_id` = '" . (int)$this->affiliate->getId() . "'");
	}

	public function addEbayImportStartDates($data) {
		$today = date("F j, Y, g:i a");
		$this->db->query("INSERT INTO " . DB_PREFIX . "ebay_import_startdates
						  SET		  `start_date` = '" . $this->db->escape($data['start_date']) . "',
									  `end_date` = '" . $this->db->escape($data['end_date']) . "',
									  `affiliate_id` = '" . (int)$this->affiliate->getId() . "',
									  `text` = '" . $this->db->escape($today) . "'");
	}

	public function clearDates() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "ebay_import_startdates WHERE `affiliate_id` = '" . (int)$this->affiliate->getId() . "'");
	}

	public function getEbayCompatibilityLevels() {
		$sql = "SELECT * FROM " . DB_PREFIX . "ebay_compatibility";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public  function getCsvImportProducts() {
		$sql = "SELECT    p.product_id,
						  pd.name
		 		FROM      " . DB_PREFIX . "product p
		 		LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
		 		WHERE     p.status = '0'
		 		AND       p.affiliate_id = '" . (int)$this->affiliate->getId() . "'";

		$query = $this->db->query($sql);

		foreach($query->rows as $data) {
			$product_data[] = array (
				'title'      => $data['name'],
				'product_id' => $data['product_id']
			);
		}

		return $product_data;
	}

	public  function addProductLink($data) {

		foreach ($data as $product_id => $ebay_id) {
			$this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "affiliate_product_link
						  SET         `product_id` = '" . $this->db->escape($product_id). "',
						  			  `ebay_item_id` = '" . $this->db->escape($ebay_id) . "',
						  			  `affiliate_id` = '" . (int)$this->affiliate->getId() . "'");
		}
	}

	public  function getLinkedProducts($start, $limit) {
		$sql = "SELECT    apl.product_id,
	  			  		  apl.ebay_item_id,
          		  		  pd.name
				FROM      " . DB_PREFIX . "affiliate_product_link apl
				LEFT JOIN " . DB_PREFIX . "product_description pd ON (apl.product_id = pd.product_id)
				WHERE     apl.affiliate_id = '" . (int)$this->affiliate->getId() . "'";

		if(isset($start) || isset($limit)) {
				if($start < 0) {
					$start = 0;
				}
				if($limit < 1) {
					$limit = 20;
				}

			    $sql .= " LIMIT " . (int)$start . "," . (int)$limit;
		}

		$query = $this->db->query($sql);
		$product_data = array();

		foreach($query->rows as $data) {
				$product_data[] = array (
				'product_id'   => $data['product_id'],
				'ebay_item_id' => $data['ebay_item_id'],
				'title'        => $data['name'],
				'selected'     => isset($this->request->post['selected']) && in_array($data['product_id'], $this->request->post['selected'])
				);
		}

		return $product_data;
	}

	public function getUnlinkedProducts($affiliate_id, $data = array()) {
		$sql = "SELECT    *
		        FROM      " . DB_PREFIX . "product p
		        LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
		        WHERE     p.affiliate_id = '" . (int)$affiliate_id . "'
		        AND       p.linked = '0'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .=	" ORDER BY pd.name DESC";

		if(isset($data['start']) || isset($data['limit'])) {
			if($data['start'] < 0) {
				$data['start'] = 0;
			}
			if($data['limit'] < 1) {
				$data['limit'] = 20;
			}

		    $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public  function editLinkedProducts($product_id, $ebay_id) {
		if (isset($ebay_id) ) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "affiliate_product_link WHERE `product_id` = '" . $this->db->escape($product_id) . "' AND `affiliate_id` = '" . (int)$this->affiliate->getId() . "'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "affiliate_product_link
							  SET `product_id` = '" . $this->db->escape($product_id) . "',
							  	  `ebay_item_id` = '" . $this->db->escape($ebay_id) . "',
							  	  `affiliate_id` = '" . (int)$this->affiliate->getId() . "'");
		}
	}

	public  function editUnlinkedProducts($product_id, $ebay_id) {
		if (isset($ebay_id) ) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "affiliate_product_link
							  SET `product_id` = '" . $this->db->escape($product_id) . "',
							  	  `ebay_item_id` = '" . $this->db->escape($ebay_id) . "',
							  	  `affiliate_id` = '" . (int)$this->affiliate->getId() . "'");

			$this->db->query("UPDATE " . DB_PREFIX . "product SET `status` = '1'");
		}
	}

	public function getCategoryInfoByManufacturerId($manufacturer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description cd
								   LEFT JOIN " . DB_PREFIX . "category c ON cd.category_id = c.category_id
								   WHERE c.manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->rows;
	}

	public function getManufacturerInfo() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer");

		return $query->rows;
	}

	public function clearCsvImportTable($affiliate_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product p SET p.csv_import = '0' WHERE p.csv_import = '1' AND p.affiliate_id = '" . (int)$affiliate_id . "'");
	}

	public function getSellerList($affiliate_id, $time_from, $time_to) {
		$call_name = 'GetSellerList';
		$profile = $this->getEbayProfile($affiliate_id);
		$ebay_call = new Ebaycall($profile['developer_id'], $profile['application_id'], $profile['certification_id'], $profile['compat'], $profile['site_id'], $call_name);

		$xml  = '<?xml version="1.0" encoding="utf-8"?>';
        $xml .= '<GetSellerListRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
      	$xml .= '<RequesterCredentials>';
      	$xml .= '<eBayAuthToken>' . $profile['user_token'] . '</eBayAuthToken>';
      	$xml .= '</RequesterCredentials>';
      	$xml .= '<Pagination ComplexType="PaginationType">';
      	$xml .= '<EntriesPerPage>200</EntriesPerPage>';
      	$xml .= '<PageNumber>1</PageNumber>';
      	$xml .= '</Pagination>';
      	$xml .= '<GranularityLevel>Coarse</GranularityLevel>';
      	$xml .= '<StartTimeFrom>' . $time_from . '</StartTimeFrom>';
      	$xml .= '<StartTimeTo>' . $time_to . '</StartTimeTo>';
      	$xml .= '<WarningLevel>Low</WarningLevel>';
      	$xml .= '<OutputSelector>PaginationResult</OutputSelector>';
      	$xml .= '<OutputSelector>ItemArray.Item.Title</OutputSelector>';
      	$xml .= '<OutputSelector>ItemArray.Item.ItemID</OutputSelector>';
      	$xml .= '</GetSellerListRequest>';

      	$xml_response = $ebay_call->sendHttpRequest($xml);

		if(stristr($xml_response, 'HTTP 404') || $xml_response == '') {
			$this->language->load('affiliate/stock_control');
	        $response = $this->language->get('error_ebay_api_call');
	        return $response;
        }

        $doc_response = new DomDocument();
        $doc_response->loadXML($xml_response);
        $message = $doc_response->getElementsByTagName('Ack')->item(0)->nodeValue;

        if($message == 'Failure') {
        	$severity_code = $doc_response->getElementsByTagName('SeverityCode')->item(0)->nodeValue;
        	$error_code = $doc_response->getElementsByTagName('ErrorCode')->item(0)->nodeValue;
        	$short_message = $doc_response->getElementsByTagName('ShortMessage')->item(0)->nodeValue;
        	$long_message = $doc_response->getElementsByTagName('LongMessage')->item(0)->nodeValue;
	        $response = strtoupper($severity_code) . ': ' . $long_message . ' Error Code: ' . $error_code;
			return $response;
        }

        $titles = $doc_response->getElementsByTagName('Title');
	    $item_ids = $doc_response->getElementsByTagName('ItemID');
	    $number_pages = $doc_response->getElementsByTagName('TotalNumberOfPages');
	    $number_entries = $doc_response->getElementsByTagName('TotalNumberOfEntries');
	    $import_data = array();

	    foreach ($titles as $title) {
	      $import_data['title'][] = $title->nodeValue;
	    }

	    foreach ($item_ids as $item_id) {
	      $import_data['id'][] = $item_id->nodeValue;
	    }

	    $page_count = intval($number_pages->item(0)->nodeValue);
	    $total_entries = intval($number_entries->item(0)->nodeValue);

	    if($page_count > 1) {
	        for($i = 2; $i <= $page_count; $i++) {

	          $xml  = '<?xml version="1.0" encoding="utf-8"?>';
	          $xml .= '<GetSellerListRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
	          $xml .= '<RequesterCredentials>';
	          $xml .= '<eBayAuthToken>' . $profile['user_token'] . '</eBayAuthToken>';
	          $xml .= '</RequesterCredentials>';
	          $xml .= '<Pagination ComplexType="PaginationType">';
	          $xml .= '<EntriesPerPage>200</EntriesPerPage>';
	          $xml .= '<PageNumber>' . $i . '</PageNumber>';
	          $xml .= '</Pagination>';
	          $xml .= '<GranularityLevel>Coarse</GranularityLevel>';
	          $xml .= '<StartTimeFrom>' . $time_from . '</StartTimeFrom>';
      	   	  $xml .= '<StartTimeTo>' . $time_to . '</StartTimeTo>';
	          $xml .= '<WarningLevel>Low</WarningLevel>';
	          $xml .= '<OutputSelector>PaginationResult</OutputSelector>';
	          $xml .= '<OutputSelector>ItemArray.Item.Title</OutputSelector>';
	          $xml .= '<OutputSelector>ItemArray.Item.ItemID</OutputSelector>';
	          $xml .= '</GetSellerListRequest>';

	          $xml_response = $ebay_call->sendHttpRequest($xml);

			  if(stristr($xml_response, 'HTTP 404') || $xml_response == '') {
				$this->language->load('affiliate/stock_control');
		        $response = $this->language->get('error_ebay_api_call');
		        return $response;
	          }

	          foreach ($titles as $title) {
	            $import_data['title'][] = $title->nodeValue;
	          }

	          foreach ($item_ids as $item_id) {
	            $import_data['id'][] = $item_id->nodeValue;
	          }
	        }
	    }

	    return $import_data;
	}

	public function getEbayImportStartDates($affiliate_id) {
		$sql = "SELECT `start_date`, `end_date`, `text` FROM " . DB_PREFIX . "ebay_import_startdates WHERE `affiliate_id` = '" . (int)$affiliate_id . "'";
		$query = $this->db->query($sql);
		return json_encode($query->rows);
	}

	public function setEbayImportStartDates($data) {
		$today = date("F j, Y, g:i a");
		$this->db->query("INSERT INTO " . DB_PREFIX . "ebay_import_startdates
						  SET		  `start_date` = '" . $this->db->escape($data['start_date']) . "',
									  `end_date` = '" . $this->db->escape($data['end_date']) . "',
									  `affiliate_id` = '0',
									  `text` = '" . $this->db->escape($today) . "'");
	}

	public function setProductLink($affiliate_id, $product_id, $ebay_id) {
		if (isset($ebay_id) ) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET linked = 1, status = 1 WHERE product_id = '" . $this->db->escape($product_id) . "'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "ebay_listing SET ebay_item_id = '" . $this->db->escape($ebay_id) . "', product_id = '" . $this->db->escape($product_id) . "', affiliate_id = '" . (int)$affiliate_id . "'");
		}
	}
}