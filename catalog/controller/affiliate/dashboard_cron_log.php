<?php 
class ControllerAffiliateDashboardCronLog extends Controller { 
	public function index() {		
		if (!$this->affiliate->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('affiliate/dashboard_cron_log', '', 'SSL');
			$this->redirect($this->url->link('affiliate/login', '', 'SSL'));
		}

		$this->language->load('affiliate/dashboard');
		$this->document->setTitle($this->language->get('heading_title_ebay_cron'));	

		$this->data['template_url'] = 'catalog/view/theme/' . $this->config->get('config_template');
		$affiliate_id = $this->affiliate->getId();
		
		$this->data['heading_title'] = $this->language->get('heading_title_ebay_cron');		
		$this->data['button_clear'] = $this->language->get('button_clear');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL'),       		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_ebay_cron'),
			'href'      => $this->url->link('affiliate/dashboard_cron_log', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		// Buttons
		$this->data['clear'] = $this->url->link('affiliate/dashboard_cron_log/clear', 'token=' . $this->session->data['token'], 'SSL');
		
		$file = DIR_LOGS . 'ebay_cron_log_' . $affiliate_id . '.txt';
		
		if (file_exists($file)) {
			$this->data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
		} else {
			$this->data['log'] = '';
		}

		$this->template = $this->config->get('config_template') . '/template/affiliate/dashboard_cron_log.tpl';
		
		$this->children = array(
			'affiliate/common/header',
			'affiliate/common/footer'
		);
		
		$this->response->setOutput($this->render());
	}
	
	public function clear() {
		$this->language->load('affiliate/dashboard');

		$affiliate_id = $this->affiliate->getId();
		
		$file = DIR_LOGS . 'ebay_cron_log_' . $affiliate_id . '.txt';
		
		$handle = fopen($file, 'w+'); 
				
		fclose($handle); 			
		
		$this->session->data['success'] = $this->language->get('text_success');
		
		$this->redirect($this->url->link('affiliate/dashboard_cron_log', 'token=' . $this->session->data['token'], 'SSL'));		
	}
}
?>