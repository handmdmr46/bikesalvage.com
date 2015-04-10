<?php
class ModelAffiliateDashboardSettingProfile extends Model {
	public function editAffiliate($data, $affiliate_id) {

		$this->db->query("UPDATE " . DB_PREFIX . "affiliate 
							SET  firstname = '" . $this->db->escape($data['firstname']) . "',
							     lastname = '" . $this->db->escape($data['lastname']) . "',
							     email = '" . $this->db->escape($data['email']) . "',
							     other_email = '" . $this->db->escape($data['other_email']) . "',
							     telephone = '" . $this->db->escape($data['telephone']) . "',
							     fax = '" . $this->db->escape($data['fax']) . "', 
								 company = '" . $this->db->escape($data['company']) . "', 
								 address_1 = '" . $this->db->escape($data['address_1']) . "', 
								 address_2 = '" . $this->db->escape($data['address_2']) . "', 
								 city = '" . $this->db->escape($data['city']) . "', 
								 postcode = '" . $this->db->escape($data['postcode']) . "',
								 country_id = '" . (int)$data['country_id'] . "',
								 zone_id = '" . (int)$data['zone_id'] . "',
								 website = '" . $this->db->escape($data['website']) . "',
								 cheque = '" . $this->db->escape($data['cheque']) . "',
								 tax = '" . $this->db->escape($data['tax']) . "'
						 WHERE   affiliate_id = '" . (int)$affiliate_id . "'");		
	}
	
	public function editPassword($data, $affiliate_id) {
      	$this->db->query("UPDATE  " . DB_PREFIX . "affiliate 
							SET   salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', 
							      password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' 
							WHERE affiliate_id = '" . (int)$affiliate_id . "'");
	}
				
	public function getAffiliate($affiliate_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "affiliate WHERE affiliate_id = '" . (int)$affiliate_id . "'");
		
		return $query->row;
	}
	
	public function getAffiliateByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "affiliate WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		
		return $query->row;
	}
		
	public function getAffiliateByCode($code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "affiliate WHERE code = '" . $this->db->escape($code) . "'");
		
		return $query->row;
	}
			
	public function getTotalAffiliatesByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "affiliate WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		
		return $query->row['total'];
	}
	
	
	
}//end class