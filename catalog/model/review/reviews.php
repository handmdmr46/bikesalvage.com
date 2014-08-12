<?php
class ModelReviewReviews extends Model {
	public function addReview($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "store_review 
			              SET author = '" . $this->db->escape($data['name']) . "', 
			                  email = '" . $this->db->escape($data['email']) . "', 
			                  website = '" . $this->db->escape($data['website']) . "', 			                  			                  
			                  text = '" . $this->db->escape($data['text']) . "', 
			                  rating = '" . (int)$data['rating'] . "', 
			                  date_added = NOW()");
	}
		
	public function getReviews($start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 20;
		}	
		$query = $this->db->query("SELECT r.review_id, 
			                              r.author, 
			                              r.email, 
			                              r.website, 
			                              r.rating, 
			                              r.text, 			                               
			                              r.date_added 
			                       FROM " . DB_PREFIX . "store_review r 			                       
			                       WHERE r.status = '1' 			               
			                       ORDER BY r.date_added DESC 
			                       LIMIT " . (int)$start . "," . (int)$limit);
		
		return $query->rows;
	}

	public function getTotalReviews() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store_review WHERE status = '1'");
		
		return $query->row['total'];
	}
}// end class
/*
sql
CREATE TABLE `oc_store_review` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `email` varchar(96) COLLATE utf8_bin NOT NULL DEFAULT '',
  `website` varchar(128) COLLATE utf8_bin NOT NULL,
  `text` text COLLATE utf8_bin NOT NULL,
  `rating` int(1) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`review_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;


*/