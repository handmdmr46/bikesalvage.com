<?php
class ModelExtrasBlogLink extends Model {	
	public function getBlogLink($blog_link_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "blog_link  WHERE blog_link_id = '" . (int)$blog_link_id . "'");
		
		return $query->row;
	}

	public function getBlogLinks($blog_link_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_link_image bli LEFT JOIN " . DB_PREFIX . "blog_link_image_description blid ON (bli.blog_link_image_id  = blid.blog_link_image_id) WHERE bli.blog_link_id = '" . (int)$blog_link_id . "' AND blid.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->rows;
	}
}
?>