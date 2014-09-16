<?php
class ControllerAffiliateDashboardImportEbay extends Controller {
	private $error = array();

	public function index() {
		if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
		    $this->session->data['redirect'] = $this->url->link('affiliate/dashboard', '', 'SSL');
	  		$this->redirect($this->url->link('affiliate/login', '', 'SSL'));
		}

		if (!$this->affiliate->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('affiliate/dashboard_import', '', 'SSL');
			$this->redirect($this->url->link('affiliate/login', '', 'SSL'));
		}

		$this->data['template_url'] = 'catalog/view/theme/' . $this->config->get('config_template');

		$this->language->load('affiliate/dashboard');

		//breadcrumbs
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_import_ebay'),
			'href'      => $this->url->link('affiliate/dashboard_import', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		// language
		$this->document->setTitle($this->language->get('heading_title_import_ebay'));
    	$this->data['heading_title_import_ebay'] = $this->language->get('heading_title_import_ebay');

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

		$this->load->model('affiliate/dashboard');

		$this->template = $this->config->get('config_template') . '/template/affiliate/dashboard_import_ebay.tpl';

		$this->children = array(
			'affiliate/common/header',
			'affiliate/common/footer'
		);

		$this->response->setOutput($this->render());

	}

}// end class