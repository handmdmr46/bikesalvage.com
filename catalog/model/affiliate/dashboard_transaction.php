<?php
class ModelAffiliateDashboardTransaction extends Model {
	public function getTransactions($affiliate_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 10;
		}	
				
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "affiliate_transaction WHERE affiliate_id = '" . (int)$affiliate_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
	
		return $query->rows;
	}

	public function getTotalTransactions($affiliate_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total  FROM " . DB_PREFIX . "affiliate_transaction WHERE affiliate_id = '" . (int)$affiliate_id . "'");
	
		return $query->row['total'];
	}
			
	public function getTransactionTotal($affiliate_id) {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "affiliate_transaction WHERE affiliate_id = '" . (int)$affiliate_id . "'");
	
		return $query->row['total'];
	}	
	
	public function getTotalTransactionsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "affiliate_transaction WHERE order_id = '" . (int)$order_id . "'");
	
		return $query->row['total'];
	}

	public function getTransactionStatuses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "transaction_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sql .= " ORDER BY name";	

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
		} else {
			$transaction_status_data = $this->cache->get('transaction_status.' . (int)$this->config->get('config_language_id'));

			if (!$transaction_status_data) {
				$query = $this->db->query("SELECT transaction_status_id, name FROM " . DB_PREFIX . "transaction_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

				$transaction_status_data = $query->rows;

				$this->cache->set('order_status.' . (int)$this->config->get('config_language_id'), $transaction_status_data);
			}	

			return $transaction_status_data;				
		}
	}

	public function getOrderProductTotalByAffiliateId($affiliate_id) {
		$query = $this->db->query("SELECT    SUM(op.total) as total
			                       FROM      " . DB_PREFIX . "order_product op
			                       LEFT JOIN " . DB_PREFIX . "order o ON (op.order_id = o.order_id)
			                       WHERE     op.affiliate_id = '" . (int)$affiliate_id . "'
			                       AND       o.order_status_id = '" . (int)$this->config->get('config_affiliate_order_complete_status_id') . "'");

		return $query->row['total'];
	}

	public function getOrderProductCommissionTotalByAffiliateId($affiliate_id) {
		$query = $this->db->query("SELECT    SUM(op.commission) as commission
			                       FROM      " . DB_PREFIX . "order_product op
			                       LEFT JOIN " . DB_PREFIX . "order o ON (op.order_id = o.order_id)
			                       WHERE     op.affiliate_id = '" . (int)$affiliate_id . "'
			                       AND       o.order_status_id = '" . (int)$this->config->get('config_affiliate_order_complete_status_id') . "'");

		return $query->row['commission'];
	}

}// end class
?>