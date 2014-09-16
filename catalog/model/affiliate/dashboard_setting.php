<?php 
class ModelAffiliateDashboardSetting extends Model {
	public function getSetting($group, $store_id = 0) {
		$data = array(); 
		
		$affiliate_id = $this->affiliate->getId();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting 
									 WHERE `store_id` = '" . (int)$store_id . "' 
									 AND   `group` = '" . $this->db->escape($group) . "'
									 AND   `affiliate_id` = '" . (int)$affiliate_id . "'");
		
		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$data[$result['key']] = $result['value'];
			} else {
				$data[$result['key']] = unserialize($result['value']);
			}
		}

		return $data;
	}
	
	public function getSettingValues($key, $store_id = 0) {		
		$affiliate_id = $this->affiliate->getId();
		
		$group = 'config_affiliate';
		
		$query = $this->db->query("SELECT `value` FROM " . DB_PREFIX . "setting 
									 WHERE  `store_id` = '" . (int)$store_id . "' 
									 AND    `key` = '" . $this->db->escape($key) . "'
									 AND    `group` = '" . $this->db->escape($group) . "'
									 AND    `affiliate_id` = '" . (int)$affiliate_id . "'");
		
		return $query->row;
	}
	
	public function getSettingValuesById($key, $affiliate_id, $store_id = 0) {		
		$group = 'config_affiliate';
		
		$query = $this->db->query("SELECT `value` FROM " . DB_PREFIX . "setting 
									 WHERE  `store_id` = '" . (int)$store_id . "' 
									 AND    `key` = '" . $this->db->escape($key) . "'
									 AND    `group` = '" . $this->db->escape($group) . "'
									 AND    `affiliate_id` = '" . (int)$affiliate_id . "'");
		
		return $query->row;
	}
	
	public function editSetting($group, $data, $store_id = 0) {
		$affiliate_id = $this->affiliate->getId();
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting 
							WHERE `store_id` = '" . (int)$store_id . "' 
							AND   `group` = '" . $this->db->escape($group) . "'
							AND   `affiliate_id` = '" . (int)$affiliate_id . "'");

		foreach ($data as $key => $value) {
			if (!is_array($value)) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "setting 
									SET  `store_id` = '" . (int)$store_id . "', 
										  `group` = '" . $this->db->escape($group) . "', 
										  `key` = '" . $this->db->escape($key) . "', 
										  `value` = '" . $this->db->escape($value) . "',
									     `affiliate_id` = '" . (int)$affiliate_id . "'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "setting 
									SET  `store_id` = '" . (int)$store_id . "', 
										  `group` = '" . $this->db->escape($group) . "', 
										  `key` = '" . $this->db->escape($key) . "', 
										  `value` = '" . $this->db->escape(serialize($value)) . "', 
										  `serialized` = '1',
									     `affiliate_id` = '" . (int)$affiliate_id . "'");
			}
		}
	}
	
	public function deleteSetting($group, $store_id = 0) {
		$affiliate_id = $this->affiliate->getId();
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting 
							WHERE `store_id` = '" . (int)$store_id . "' 
							AND   `group` = '" . $this->db->escape($group) . "'
							AND   `affiliate_id` = '" . (int)$affiliate_id . "'");
	}
	
	public function editSettingValue($group = '', $key = '', $value = '', $store_id = 0) {
		$affiliate_id = $this->affiliate->getId();
		
		if (!is_array($value)) {
			$this->db->query("UDPATE " . DB_PREFIX . "setting 
								SET   `value` = '" . $this->db->escape($value) . " 
								WHERE `group` = '" . $this->db->escape($group) . "' 
								AND   `key` = '" . $this->db->escape($key) . "' 
								AND   `store_id` = '" . (int)$store_id . "'
								AND   `affiliate_id` = '" . (int)$affiliate_id . "'");
		} else {
			$this->db->query("UDPATE " . DB_PREFIX . "setting 
								SET   `value` = '" . $this->db->escape(serialize($value)) . "' 
								WHERE `group` = '" . $this->db->escape($group) . "' 
								AND   `key` = '" . $this->db->escape($key) . "'
								AND   `affiliate_id` = '" . (int)$affiliate_id . "' 
								AND   `store_id` = '" . (int)$store_id . "', 
								      `serialized` = '1'");
		}
	}	
}
?>