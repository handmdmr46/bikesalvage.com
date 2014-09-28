<?php
class ControllerAffiliateDashboardShipping extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('affiliate/dashboard_shipping');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['template_url'] = 'catalog/view/theme/' . $this->config->get('config_template');

		$this->load->model('setting/setting');

		$affiliate_id = $this->affiliate->getId();
		
		$this->data['affiliate_id'] = $affiliate_id;
		$group = $affiliate_id . '_usps';

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting($group, $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('affiliate/dashboard_shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title']        = $this->language->get('heading_title');
		$this->data['entry_user_id']        = $this->language->get('entry_user_id');
		$this->data['entry_postcode']       = $this->language->get('entry_postcode');
		$this->data['entry_dimension']      = $this->language->get('entry_dimension');
		$this->data['button_save']          = $this->language->get('button_save');
		$this->data['button_cancel']        = $this->language->get('button_cancel');

		// Error
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['user_id'])) {
			$this->data['error_user_id'] = $this->error['user_id'];
		} else {
			$this->data['error_user_id'] = '';
		}

		if (isset($this->error['postcode'])) {
			$this->data['error_postcode'] = $this->error['postcode'];
		} else {
			$this->data['error_postcode'] = '';
		}

		if (isset($this->error['width'])) {
			$this->data['error_width'] = $this->error['width'];
		} else {
			$this->data['error_width'] = '';
		}

		if (isset($this->error['length'])) {
			$this->data['error_length'] = $this->error['length'];
		} else {
			$this->data['error_length'] = '';
		}

		if (isset($this->error['height'])) {
			$this->data['error_height'] = $this->error['height'];
		} else {
			$this->data['error_height'] = '';
		}

		// Success
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
			'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('affiliate/dashboard_shipping', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('affiliate/dashboard_shipping', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL');		

		if (isset($this->request->post[$affiliate_id . '_usps_user_id'])) {
			$this->data['usps_user_id'] = $this->request->post[$affiliate_id . '_usps_user_id'];
		} else {
			$this->data['usps_user_id'] = $this->config->get($affiliate_id . '_usps_user_id');
		}


		if (isset($this->request->post[$affiliate_id . '_usps_postcode'])) {
			$this->data['usps_postcode'] = $this->request->post[$affiliate_id . '_usps_postcode'];
		} else {
			$this->data['usps_postcode'] = $this->config->get($affiliate_id . '_usps_postcode');
		}


		if (isset($this->request->post[$affiliate_id . '_usps_length'])) {
			$this->data['usps_length'] = $this->request->post[$affiliate_id . '_usps_length'];
		} else {
			$this->data['usps_length'] = $this->config->get($affiliate_id . '_usps_length');
		}

		if (isset($this->request->post[$affiliate_id . '_usps_width'])) {
			$this->data['usps_width'] = $this->request->post[$affiliate_id . '_usps_width'];
		} else {
			$this->data['usps_width'] = $this->config->get($affiliate_id . '_usps_width');
		}

		if (isset($this->request->post[$affiliate_id . '_usps_height'])) {
			$this->data['usps_height'] = $this->request->post[$affiliate_id . '_usps_height'];
		} else {
			$this->data['usps_height'] = $this->config->get($affiliate_id . '_usps_height');
		}		

		$this->template = $this->config->get('config_template') . '/template/affiliate/dashboard_shipping.tpl';
		
		$this->children = array(
			'affiliate/common/header',
			'affiliate/common/footer'
		);
		
		$this->response->setOutput($this->render());
	}

	protected function validate() {
		$affiliate_id = $this->affiliate->getId();
		if (!$this->request->post[$affiliate_id . '_usps_user_id']) {
			$this->error['user_id'] = $this->language->get('error_user_id');
		}

		if (!$this->request->post[$affiliate_id . '_usps_postcode']) {
			$this->error['postcode'] = $this->language->get('error_postcode');
		}

		if (!$this->request->post[$affiliate_id . '_usps_width']) {
			$this->error['width'] = $this->language->get('error_width');
		}

		if (!$this->request->post[$affiliate_id . '_usps_height']) {
			$this->error['height'] = $this->language->get('error_height');
		}

		if (!$this->request->post[$affiliate_id . '_usps_length']) {
			$this->error['length'] = $this->language->get('error_length');
		}

		if (!$this->error) {
			return true;
		} else {
			$this->error['warning'] = $this->language->get('error_warning');
			return false;
		}
	}
}
?>