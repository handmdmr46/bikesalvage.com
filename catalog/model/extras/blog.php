<?php
class ModelExtrasBlog extends Model {
	public function updateViewed($blog_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "blog SET viewed = (viewed + 1) WHERE blog_id = '" . (int)$blog_id . "'");
	}

	public function getBlog($blog_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "blog b LEFT JOIN " . DB_PREFIX . "blog_description bd ON (b.blog_id = bd.blog_id) LEFT JOIN " . DB_PREFIX . "blog_to_store b2s ON (b.blog_id = b2s.blog_id) WHERE b.blog_id = '" . (int)$blog_id . "' AND bd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND b2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND b.status = '1'");
	
		return $query->row;
	}
	
	//public function getBlogs() {
	//	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog b LEFT JOIN " . DB_PREFIX . "blog_description bd ON (b.blog_id = bd.blog_id) LEFT JOIN " . DB_PREFIX . "blog_to_store b2s ON (b.blog_id = b2s.blog_id) WHERE bd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND b2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND b.status = '1' ORDER BY b.date_added DESC");
		
	//	return $query->rows;
	//}

	public function getBlogs($data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
		
		$cache = md5(http_build_query($data));
		
		$blog_data = $this->cache->get('blog.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache);
		
		if (!$blog_data) {
			$sql = "SELECT b.blog_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "blog_comment bc1 WHERE bc1.blog_id = b.blog_id AND bc1.status = '1' GROUP BY bc1.blog_id) AS rating FROM " . DB_PREFIX . "blog b LEFT JOIN " . DB_PREFIX . "blog_description bd ON (b.blog_id = bd.blog_id) LEFT JOIN " . DB_PREFIX . "blog_to_store b2s ON (b.blog_id = b2s.blog_id)"; 
			
			if (!empty($data['filter_tag'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "blog_tag bt ON (b.blog_id = bt.blog_id)";			
			}
						
			if (!empty($data['filter_blog_category_id'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "blog_to_category b2c ON (b.blog_id = b2c.blog_id)";			
			}
			
			$sql .= " WHERE bd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND b.status = '1' AND b2s.store_id = '" . (int)$this->config->get('config_store_id') . "'"; 
			
			if (!empty($data['filter_title']) || !empty($data['filter_tag'])) {
				$sql .= " AND (";
											
				if (!empty($data['filter_title'])) {
					$implode = array();
					
					$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_title'])));
					
					foreach ($words as $word) {
						if (!empty($data['filter_description'])) {
							$implode[] = "LCASE(bd.title) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%' OR LCASE(bd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
						} else {
							$implode[] = "LCASE(bd.title) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
						}				
					}
					
					if ($implode) {
						$sql .= " " . implode(" OR ", $implode) . "";
					}
				}
				
				if (!empty($data['filter_title']) && !empty($data['filter_tag'])) {
					$sql .= " OR ";
				}
				
				if (!empty($data['filter_tag'])) {
					$implode = array();
					
					$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_tag'])));
					
					foreach ($words as $word) {
						$implode[] = "LCASE(bt.tag) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
					}
					
					if ($implode) {
						$sql .= " " . implode(" OR ", $implode) . " AND bt.language_id = '" . (int)$this->config->get('config_language_id') . "'";
					}
				}
			
				$sql .= ")";
			}
			
			if (!empty($data['filter_blog_category_id'])) {
				if (!empty($data['filter_sub_blog_category'])) {
					$implode_data = array();
					
					$implode_data[] = "b2c.blog_category_id = '" . (int)$data['filter_blog_category_id'] . "'";
					
					$this->load->model('extras/blog_category');
					
					$categories = $this->model_extras_blog_category->getBlogCategoriesByParentId($data['filter_blog_category_id']);
										
					foreach ($categories as $category_id) {
						$implode_data[] = "b2c.blog_category_id = '" . (int)$category_id . "'";
					}
								
					$sql .= " AND (" . implode(' OR ', $implode_data) . ")";			
				} else {
					$sql .= " AND b2c.blog_category_id = '" . (int)$data['filter_blog_category_id'] . "'";
				}
			}		
			
			$sql .= " GROUP BY b.blog_id";
			
			$sort_data = array(
				'bd.title',
				'b.sort_order',
				'b.date_added'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				if ($data['sort'] == 'bd.title') {
					$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
				} else {
					$sql .= " ORDER BY " . $data['sort'];
				}
			} else {
				$sql .= " ORDER BY b.sort_order";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC, LCASE(bd.title) DESC";
			} else {
				$sql .= " ASC, LCASE(bd.title) ASC";
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
			
			$blog_data = array();
					
			$query = $this->db->query($sql);
		
			foreach ($query->rows as $result) {
				$blog_data[$result['blog_id']] = $this->getBlog($result['blog_id']);
			}
			
			$this->cache->set('blog.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache, $blog_data);
		}
		
		return $blog_data;
	}
	
	public function getBlogLayoutId($blog_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_to_layout WHERE blog_id = '" . (int)$blog_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		 
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return $this->config->get('config_layout_blog');
		}
	}	
		
	public function getBlogVideos($blog_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_video WHERE blog_id = '" . (int)$blog_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}
		
	public function getBlogImages($blog_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_image WHERE blog_id = '" . (int)$blog_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}
	
	public function getBlogRelated($blog_id) {
		$blog_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_related br LEFT JOIN " . DB_PREFIX . "blog b ON (br.related_id = b.blog_id) LEFT JOIN " . DB_PREFIX . "blog_to_store b2s ON (b.blog_id = b2s.blog_id) WHERE br.blog_id = '" . (int)$blog_id . "' AND b.status = '1' AND b2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		foreach ($query->rows as $result) { 
			$blog_data[$result['related_id']] = $this->getBlog($result['related_id']);
		}
		
		return $blog_data;
	}
	
	public function getBlogProductRelated($blog_id) {
		$this->load->model('catalog/product');
		
		$blog_product_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_product_related bpr LEFT JOIN " . DB_PREFIX . "product p ON (bpr.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE bpr.blog_id = '" . (int)$blog_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		foreach ($query->rows as $result) { 
			$blog_product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
		}
		
		return $blog_product_data;
	}
		
	public function getBlogTags($blog_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_tag WHERE blog_id = '" . (int)$blog_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY tag ASC");

		return $query->rows;
	}

	public function addComment($blog_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "blog_comment SET author = '" . $this->db->escape($data['name']) . "', email = '" . $this->db->escape($data['email']) . "', website = '" . $this->db->escape($data['website']) . "', customer_id = '" . (int)$this->customer->getId() . "', blog_id = '" . (int)$blog_id . "', text = '" . $this->db->escape($data['text']) . "', rating = '" . (int)$data['rating'] . "', date_added = NOW()");
	}
		
	public function getCommentsByBlogId($blog_id, $start = 0, $limit = 20) {
		$query = $this->db->query("SELECT bc.blog_comment_id, bc.author, bc.email, bc.website, bc.rating, bc.text, b.blog_id, bd.title, b.image, bc.date_added FROM " . DB_PREFIX . "blog_comment bc LEFT JOIN " . DB_PREFIX . "blog b ON (bc.blog_id = b.blog_id) LEFT JOIN " . DB_PREFIX . "blog_description bd ON (b.blog_id = bd.blog_id) WHERE b.blog_id = '" . (int)$blog_id . "' AND b.status = '1' AND bc.status = '1' AND bd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY bc.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
		
		return $query->rows;
	}
	
	public function getAverageRating($blog_id) {
		$query = $this->db->query("SELECT AVG(rating) AS total FROM " . DB_PREFIX . "blog_comment WHERE status = '1' AND blog_id = '" . (int)$blog_id . "' GROUP BY blog_id");
		
		if (isset($query->row['total'])) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}	
		
	public function getTotalBlogs($data = array()) {
		$sql = "SELECT COUNT(DISTINCT b.blog_id) AS total FROM " . DB_PREFIX . "blog b LEFT JOIN " . DB_PREFIX . "blog_description bd ON (b.blog_id = bd.blog_id) LEFT JOIN " . DB_PREFIX . "blog_to_store b2s ON (b.blog_id = b2s.blog_id)";

		if (!empty($data['filter_blog_category_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "blog_to_category b2c ON (b.blog_id = b2c.blog_id)";			
		}
		
		if (!empty($data['filter_tag'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "blog_tag bt ON (b.blog_id = bt.blog_id)";			
		}
					
		$sql .= " WHERE bd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND b.status = '1' AND b2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		if (!empty($data['filter_title']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";
								
			if (!empty($data['filter_title'])) {
				$implode = array();
				
				$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_title'])));
				
				foreach ($words as $word) {
					if (!empty($data['filter_description'])) {
						$implode[] = "LCASE(bd.title) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%' OR LCASE(bd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
					} else {
						$implode[] = "LCASE(bd.title) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
					}				
				}
				
				if ($implode) {
					$sql .= " " . implode(" OR ", $implode) . "";
				}
			}
			
			if (!empty($data['filter_title']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}
			
			if (!empty($data['filter_tag'])) {
				$implode = array();
				
				$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_tag'])));
				
				foreach ($words as $word) {
					$implode[] = "LCASE(bt.tag) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
				}
				
				if ($implode) {
					$sql .= " " . implode(" OR ", $implode) . " AND bt.language_id = '" . (int)$this->config->get('config_language_id') . "'";
				}
			}
		
			$sql .= ")";
		}
		
		if (!empty($data['filter_blog_category_id'])) {
			if (!empty($data['filter_sub_blog_category'])) {
				$implode_data = array();
				
				$implode_data[] = "b2c.blog_category_id = '" . (int)$data['filter_blog_category_id'] . "'";
				
				$this->load->model('extras/blog_category');
				
				$categories = $this->model_extras_blog_category->getBlogCategoriesByParentId($data['filter_blog_category_id']);
					
				foreach ($categories as $category_id) {
					$implode_data[] = "b2c.blog_category_id = '" . (int)$category_id . "'";
				}
							
				$sql .= " AND (" . implode(' OR ', $implode_data) . ")";			
			} else {
				$sql .= " AND b2c.blog_category_id = '" . (int)$data['filter_blog_category_id'] . "'";
			}
		}		
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function getTotalComments() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_comment bc LEFT JOIN " . DB_PREFIX . "blog b ON (bc.blog_id = b.blog_id) WHERE b.status = '1' AND bc.status = '1'");
		
		return $query->row['total'];
	}

	public function getTotalCommentsByBlogId($blog_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_comment bc LEFT JOIN " . DB_PREFIX . "blog b ON (bc.blog_id = b.blog_id) LEFT JOIN " . DB_PREFIX . "blog_description bd ON (b.blog_id = bd.blog_id) WHERE b.blog_id = '" . (int)$blog_id . "' AND b.status = '1' AND bc.status = '1' AND bd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row['total'];
	}
}
?>