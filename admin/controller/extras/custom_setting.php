<?php 
class ControllerExtrasCustomSetting extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('extras/custom_setting');
    	
		$this->document->setTitle($this->language->get('heading_title')); 		
		
		$this->getList();
  	}

	protected function getList() {
		// Language
		$this->data['heading_title']                         = $this->language->get('heading_title');
		$this->data['entry_international_shipping_methods']  = $this->language->get('entry_international_shipping_methods');
		$this->data['entry_domestic_shipping_methods']       = $this->language->get('entry_domestic_shipping_methods');
		$this->data['entry_affiliate_order_complete_status'] = $this->language->get('entry_affiliate_order_complete_status');
		$this->data['button_cancel']                         = $this->language->get('button_cancel');
		$this->data['button_save']                           = $this->language->get('button_save');
		$this->data['text_select']                           = $this->language->get('text_select');

		$this->data['token'] = $this->session->data['token']; 
		$url = '';

		// Buttons   		
   		$this->data['cancel'] = $this->url->link('common/home', 'token=' . $this->session->data['token'] . $url, 'SSL');
   		$this->data['action'] = $this->url->link('extras/custom_setting', 'token=' . $this->session->data['token'], 'SSL');

		// Breadcrumbs
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extras/custom_setting', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

   		// Error
 		if (isset($this->error['warning'])) {
			$this->data['error'] = $this->error['warning'];
		} else {
			$this->data['error'] = '';
		}

   		// Sucess
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

   		$this->load->model('shipping/custom_shipping');
   		$this->data['shipping_methods'] = $this->model_shipping_custom_shipping->getShippingMethods();

   		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();	

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('custom_setting', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('extras/custom_setting', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if (isset($this->request->post['domestic_shipping_default_id'])) {
			$this->data['domestic_shipping_default_id'] = $this->request->post['domestic_shipping_default_id'];
		} else {
			$this->data['domestic_shipping_default_id'] = $this->config->get('domestic_shipping_default_id');
		}

		if (isset($this->request->post['international_shipping_default_id'])) {
			$this->data['international_shipping_default_id'] = $this->request->post['international_shipping_default_id'];
		} else {
			$this->data['international_shipping_default_id'] = $this->config->get('international_shipping_default_id');
		}

		if (isset($this->request->post['config_affiliate_order_complete_status_id'])) {
			$this->data['config_affiliate_order_complete_status_id'] = $this->request->post['config_affiliate_order_complete_status_id'];
		} else {
			$this->data['config_affiliate_order_complete_status_id'] = $this->config->get('config_affiliate_order_complete_status_id');
		}

   		$this->template = 'extras/custom_setting.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extras/custom_setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

  }// and class
  ?>