<?php    
class ControllerAffiliateDashboardErrorNotFound extends Controller {    
	public function index() { 
		$this->language->load('affiliate/dashboard_error_not_found');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_not_found'] = $this->language->get('text_not_found');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('affiliate/dashboard_error_not_found', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->template = $this->config->get('config_template') . '/template/affiliate/dashboard_error_not_found.tpl';
		
		$this->children = array(
			'affiliate/common/header',
			'affiliate/common/footer'
		);

		$this->response->setOutput($this->render());	
	}
}
?>