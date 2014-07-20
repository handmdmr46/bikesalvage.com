<?php
class ModelExtrasBlogCategory extends Model {
	public function getBlogCategory($blog_category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "blog_category bc LEFT JOIN " . DB_PREFIX . "blog_category_description bcd ON (bc.blog_category_id = bcd.blog_category_id) LEFT JOIN " . DB_PREFIX . "blog_category_to_store bc2s ON (bc.blog_category_id = bc2s.blog_category_id) WHERE bc.blog_category_id = '" . (int)$blog_category_id . "' AND bcd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND bc2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND bc.status = '1'");
		
		return $query->row;
	}
	
	public function getBlogCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category bc LEFT JOIN " . DB_PREFIX . "blog_category_description bcd ON (bc.blog_category_id = bcd.blog_category_id) LEFT JOIN " . DB_PREFIX . "blog_category_to_store bc2s ON (bc.blog_category_id = bc2s.blog_category_id) WHERE bc.parent_id = '" . (int)$parent_id . "' AND bcd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND bc2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND bc.status = '1' ORDER BY bc.sort_order, LCASE(bcd.name)");
		
		return $query->rows;
	}

	public function getBlogCategoriesByParentId($blog_category_id) {
		$blog_category_data = array();
		
		$blog_category_query = $this->db->query("SELECT blog_category_id FROM " . DB_PREFIX . "blog_category WHERE parent_id = '" . (int)$blog_category_id . "'");
		
		foreach ($blog_category_query->rows as $blog_category) {
			$blog_category_data[] = $blog_category['blog_category_id'];
			
			$children = $this->getBlogCategoriesByParentId($blog_category['blog_category_id']);
			
			if ($children) {
				$blog_category_data = array_merge($children, $blog_category_data);
			}			
		}
		
		return $blog_category_data;
	}
		
	public function getBlogCategoryLayoutId($blog_category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category_to_layout WHERE blog_category_id = '" . (int)$blog_category_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return $this->config->get('config_layout_blog_category');
		}
	}
					
	public function getTotalBlogCategoriesByBlogCategoryId($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_category bc LEFT JOIN " . DB_PREFIX . "blog_category_to_store bc2s ON (bc.blog_category_id = bc2s.blog_category_id) WHERE bc.parent_id = '" . (int)$parent_id . "' AND bc2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND bc.status = '1'");
		
		return $query->row['total'];
	}
}
?>