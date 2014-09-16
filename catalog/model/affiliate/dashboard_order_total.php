<?php
class ModelAffiliateDashboardOrderTotal extends Model {	
	public function setAffiliateOrderTotals($data, $affiliate_id, $order_id) {
		foreach ($data as $total) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_total 
								  SET         order_id = '" . (int)$order_id . "', 
								              code = '" . $this->db->escape($total['code']) . "', 
									          title = '" . $this->db->escape($total['title']) . "', 
									          text = '" . $this->db->escape($total['text']) . "', 
									          `value` = '" . (float)$total['value'] . "', 
									          sort_order = '" . (int)$total['sort_order'] . "',
									          affiliate_id = '" . (int)$affiliate_id . "'");			
		}
	}

	public function updateOrderTotals($total_data, $order_id) {
		/*foreach ($total_data as $total) {
				$this->db->query("UPDATE " . DB_PREFIX . "order_total ot
								  SET    ot.code = '" . $this->db->escape($total['code']) . "', 
									     ot.title = '" . $this->db->escape($total['title']) . "', 
									     ot.text = '" . $this->db->escape($total['text']) . "', 
									     ot.value = '" . (float)$total['value'] . "', 
									     ot.sort_order = '" . (int)$total['sort_order'] . "'
								  WHERE  ot.order_id = '" . (int)$order_id . "'
								  AND    ot.affiliate_id = '0'
								  AND ot.master_total = '1'");
		}*/

		$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND affiliate_id = '0' AND master_total = '1'");

		foreach ($total_data as $total) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total 
								SET order_id = '" . (int)$order_id . "', 
								    code = '" . $this->db->escape($total['code']) . "', 
									title = '" . $this->db->escape($total['title']) . "', 
									text = '" . $this->db->escape($total['text']) . "', 
									`value` = '" . (float)$total['value'] . "', 
									sort_order = '" . (int)$total['sort_order'] . "',
									master_total = '1',
									affiliate_id = '0'");
		}
	}

}// end class