<?php 
class ControllerAffiliateDashboard extends Controller { 
	public function index() {
		if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
		    $this->session->data['redirect'] = $this->url->link('affiliate/dashboard', '', 'SSL');
	  		$this->redirect($this->url->link('affiliate/login', '', 'SSL'));
		}

		if (!$this->affiliate->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('affiliate/dashboard', '', 'SSL');
	  		$this->redirect($this->url->link('affiliate/login', '', 'SSL'));
    	} 

    	$this->data['template_url'] = 'catalog/view/theme/' . $this->config->get('config_template');
	
		$this->language->load('affiliate/dashboard');
		
		$this->document->setTitle($this->language->get('heading_title_dashboard'));
		
		// Language
    	$this->data['heading_title_dashboard'] = $this->language->get('heading_title_dashboard');
		$this->data['text_overview'] = $this->language->get('text_overview');
		$this->data['text_statistics'] = $this->language->get('text_statistics');
		$this->data['text_latest_10_orders'] = $this->language->get('text_latest_10_orders');
		$this->data['text_total_sale'] = $this->language->get('text_total_sale');
		$this->data['text_total_sale_year'] = $this->language->get('text_total_sale_year');
		$this->data['text_total_order'] = $this->language->get('text_total_order');
		$this->data['text_total_customer'] = $this->language->get('text_total_customer');
		$this->data['text_total_customer_approval'] = $this->language->get('text_total_customer_approval');
		$this->data['text_total_review_approval'] = $this->language->get('text_total_review_approval');
		$this->data['text_total_affiliate'] = $this->language->get('text_total_affiliate');
		$this->data['text_total_affiliate_approval'] = $this->language->get('text_total_affiliate_approval');
		$this->data['text_day'] = $this->language->get('text_day');
		$this->data['text_week'] = $this->language->get('text_week');
		$this->data['text_month'] = $this->language->get('text_month');
		$this->data['text_year'] = $this->language->get('text_year');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['column_order'] = $this->language->get('column_order');
		$this->data['column_customer'] = $this->language->get('column_customer');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_firstname'] = $this->language->get('column_firstname');
		$this->data['column_lastname'] = $this->language->get('column_lastname');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['entry_range'] = $this->language->get('entry_range');
		
		// Breadcrumbs
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);
		
		// Error
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		// Success
		if (isset($this->session->data['success'])) {
    		$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->load->model('affiliate/dashboard_order');

		$affiliate_id = $this->affiliate->getId();
		
		$this->data['total_sale']              = $this->currency->format($this->model_affiliate_dashboard_order->getTotalSalesByAffiliateId($affiliate_id), $this->config->get('config_currency'));
		$this->data['total_sale_year']         = $this->currency->format($this->model_affiliate_dashboard_order->getTotalSalesByYearAndAffiliateId(date('Y'), $affiliate_id), $this->config->get('config_currency'));
		$this->data['total_order']             = $this->model_affiliate_dashboard_order->getTotalOrdersByAffiliateId($affiliate_id);
		// $this->data['total_customer']          = $this->model_affiliate_dashboard_order->getTotalCustomersByAffiliateId($affiliate_id);
		// $this->data['total_customer_approval'] = $this->model_affiliate_dashboard_order->getTotalCustomersAwaitingApproval();
		// $this->data['total_review']            = $this->model_affiliate_dashboard_order->getTotalReviews();
		// $this->data['total_review_approval']   = $this->model_affiliate_dashboard_order->getTotalReviewsAwaitingApproval();
				
		$this->data['orders'] = array(); 
		
		$data = array(
			'sort'  => 'o.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 10
		);
		
		$results = $this->model_affiliate_dashboard_order->getOrdersByAffiliateId($data, $affiliate_id);
    	
    	foreach ($results as $result) {
			$action = array();
			 
			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => $this->url->link('affiliate/dashboard_order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], 'SSL')
			);
					
			$this->data['orders'][] = array(
				'order_id'   => $result['order_id'],
				'customer'   => $result['customer'],
				'status'     => $result['status'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'action'     => $action
			);
		}

		if ($this->config->get('config_currency_auto')) {
			$this->load->model('affiliate/dashboard_localisation_currency');
			$this->model_affiliate_dashboard_localisation_currency->updateCurrencies();
		}
		
		$this->template = $this->config->get('config_template') . '/template/affiliate/dashboard.tpl';
		
		$this->children = array(
			'affiliate/common/header',
			'affiliate/common/footer'
		);
		
		$this->response->setOutput($this->render());		
  	}
	
	public function affiliateChart() {
		$this->language->load('affiliate/dashboard');
		$affiliate_id = $this->affiliate->getId();
		$data = array();
		
		$data['order'] = array();
		$data['customer'] = array();
		$data['xaxis'] = array();
		
		$data['order']['label'] = $this->language->get('text_order');
		$data['customer']['label'] = $this->language->get('text_customer');
		
		if (isset($this->request->get['range'])) {
			$range = $this->request->get['range'];
		} else {
			$range = 'month';
		}
		
		switch ($range) {
			case 'day':
				for ($i = 0; $i < 24; $i++) {
					$query = $this->db->query("SELECT    COUNT(*) AS total 
											   FROM      " . DB_PREFIX . "order o
											   LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id)
											   WHERE     o.order_status_id > '" . (int)$this->config->get('config_complete_status_id') . "' 
											   AND       DATE(o.date_added) = DATE(NOW()) 
											   AND       HOUR(o.date_added) = '" . (int)$i . "'
											   AND       op.affiliate_id = '" . (int)$affiliate_id . "'												 
											   GROUP BY  HOUR(o.date_added) 
											   ORDER BY  o.date_added ASC");
					
					if ($query->num_rows) {
						$data['order']['data'][]  = array($i, (int)$query->row['total']);
					} else {
						$data['order']['data'][]  = array($i, 0);
					}
					
					$query = $this->db->query("SELECT     COUNT(*) AS total 
											   FROM       " . DB_PREFIX . "customer c
											   LEFT JOIN  " . DB_PREFIX . "order o ON (c.customer_id = o.customer_id)
											   LEFT JOIN  " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id)
											   WHERE      DATE(c.date_added) = DATE(NOW()) 
											   AND        HOUR(c.date_added) = '" . (int)$i . "'
											   AND        op.affiliate_id = '" . (int)$affiliate_id . "'											 
											   GROUP BY   HOUR(c.date_added) 
											   ORDER BY   c.date_added ASC");
					
					if ($query->num_rows) {
						$data['customer']['data'][] = array($i, (int)$query->row['total']);
					} else {
						$data['customer']['data'][] = array($i, 0);
					}
			
					$data['xaxis'][] = array($i, date('H', mktime($i, 0, 0, date('n'), date('j'), date('Y'))));
				}					
				break;
			case 'week':
				$date_start = strtotime('-' . date('w') . ' days'); 
				
				for ($i = 0; $i < 7; $i++) {
					$date = date('Y-m-d', $date_start + ($i * 86400));

					$query = $this->db->query("SELECT    COUNT(*) AS total 
											   FROM      " . DB_PREFIX . "order o
											   LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id)
											   WHERE     o.order_status_id > '" . (int)$this->config->get('config_complete_status_id') . "' 
											   AND       DATE(o.date_added) = '" . $this->db->escape($date) . "' 
											   AND       op.affiliate_id = '" . (int)$affiliate_id . "'
											   GROUP BY  DATE(o.date_added)");
			
					if ($query->num_rows) {
						$data['order']['data'][] = array($i, (int)$query->row['total']);
					} else {
						$data['order']['data'][] = array($i, 0);
					}
				
					$query = $this->db->query("SELECT     COUNT(*) AS total 
											   FROM       " . DB_PREFIX . "customer c 
											   LEFT JOIN  " . DB_PREFIX . "order o ON (c.customer_id = o.customer_id)
											   LEFT JOIN  " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id)
										       WHERE      DATE(c.date_added) = '" . $this->db->escape($date) . "' 
											   AND        op.affiliate_id = '" . (int)$affiliate_id . "'
											   GROUP BY   DATE(c.date_added)");
			
					if ($query->num_rows) {
						$data['customer']['data'][] = array($i, (int)$query->row['total']);
					} else {
						$data['customer']['data'][] = array($i, 0);
					}
		
					$data['xaxis'][] = array($i, date('D', strtotime($date)));
				}
				
				break;
			default:
			case 'month':
				for ($i = 1; $i <= date('t'); $i++) {
					$date = date('Y') . '-' . date('m') . '-' . $i;
					
					$query = $this->db->query("SELECT    COUNT(*) AS total 
											   FROM      " . DB_PREFIX . "order o 
											   LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id)
											   WHERE     o.order_status_id > '" . (int)$this->config->get('config_complete_status_id') . "' 
											   AND       DATE(o.date_added) = '" . $this->db->escape($date) . "' 	
											   AND       op.affiliate_id = '" . (int)$affiliate_id . "'										
											   GROUP BY  DAY(o.date_added)");
					
					if ($query->num_rows) {
						$data['order']['data'][] = array($i, (int)$query->row['total']);
					} else {
						$data['order']['data'][] = array($i, 0);
					}	
				
					$query = $this->db->query("SELECT     COUNT(*) AS total 
											   FROM       " . DB_PREFIX . "customer c
											   LEFT JOIN  " . DB_PREFIX . "order o ON (c.customer_id = o.customer_id)
											   LEFT JOIN  " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id)
											   WHERE      DATE(c.date_added) = '" . $this->db->escape($date) . "' 
											   AND        op.affiliate_id = '" . (int)$affiliate_id . "'
											   GROUP BY   DAY(c.date_added)");
			
					if ($query->num_rows) {
						$data['customer']['data'][] = array($i, (int)$query->row['total']);
					} else {
						$data['customer']['data'][] = array($i, 0);
					}	
					
					$data['xaxis'][] = array($i, date('j', strtotime($date)));
				}
				break;
			case 'year':
				for ($i = 1; $i <= 12; $i++) {
					$query = $this->db->query("SELECT    COUNT(*) AS total 
											   FROM      " . DB_PREFIX . "order o
											   LEFT JOIN " . DB_PREFIX . "order_product op 
											   WHERE     o.order_status_id > '" . (int)$this->config->get('config_complete_status_id') . "' 
											   AND       YEAR(o.date_added) = '" . date('Y') . "' 
											   AND       MONTH(o.date_added) = '" . $i . "' 
											   AND       op.affiliate_id = '" . (int)$affiliate_id . "'
											   GROUP BY  MONTH(o.date_added)");
					
					if ($query->num_rows) {
						$data['order']['data'][] = array($i, (int)$query->row['total']);
					} else {
						$data['order']['data'][] = array($i, 0);
					}
					
					$query = $this->db->query("SELECT     COUNT(*) AS total 
											   FROM       " . DB_PREFIX . "customer c
											   LEFT JOIN  " . DB_PREFIX . "order o ON (c.customer_id = o.customer_id)
											   LEFT JOIN  " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id)
											   WHERE      YEAR(c.date_added) = '" . date('Y') . "' 
											   AND        MONTH(c.date_added) = '" . $i . "' 
											   AND        op.affiliate_id = '" . (int)$affiliate_id . "'
											   GROUP BY   MONTH(c.date_added)");
					
					if ($query->num_rows) { 
						$data['customer']['data'][] = array($i, (int)$query->row['total']);
					} else {
						$data['customer']['data'][] = array($i, 0);
					}
					
					$data['xaxis'][] = array($i, date('M', mktime(0, 0, 0, $i, 1, date('Y'))));
				}			
				break;	
		} 
		
		$this->response->setOutput(json_encode($data));
	}
}// end class
?>