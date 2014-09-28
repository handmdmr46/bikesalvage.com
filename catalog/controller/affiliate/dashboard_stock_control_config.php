<?php
class ControllerAffiliateDashboardStockControlConfig extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('affiliate/dashboard');

		$this->document->setTitle($this->language->get('heading_title_stock_control_config'));

		$this->data['template_url'] = 'catalog/view/theme/' . $this->config->get('config_template');

		$this->load->model('setting/setting');

		$affiliate_id = $this->affiliate->getId();
		
		$this->data['affiliate_id'] = $affiliate_id;
		$group = $affiliate_id . '_stock_control';

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_setting_setting->editSetting($group, $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success_stock_control_config');

			$this->redirect($this->url->link('affiliate/dashboard_stock_control_config', 'token=' . $this->session->data['token'], 'SSL'));
		}

		// Language
		$this->data['heading_title_stock_control_config'] = $this->language->get('heading_title_stock_control_config');
		$this->data['entry_stock_control_config']         = $this->language->get('entry_stock_control_config');
		$this->data['entry_build_log']                    = $this->language->get('entry_build_log');
		$this->data['button_save']                        = $this->language->get('button_save');
		$this->data['button_cancel']                      = $this->language->get('button_cancel');
		$this->data['button_build_log']                   = $this->language->get('button_build_log');
		$this->data['text_yes']                           = $this->language->get('text_yes');
		$this->data['text_no']                            = $this->language->get('text_no');

		// Breadcrumbs
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('affiliate/dashboard_stock_control_config', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		if (isset($this->session->data['success'])) {
    		$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['action']    = $this->url->link('affiliate/dashboard_stock_control_config', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['build_log'] = $this->url->link('affiliate/dashboard_stock_control_config/buildLogFile', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel']    = $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL');		

		if (isset($this->request->post[$affiliate_id . '_stock_control_id'])) {
			$this->data['stock_control_id'] = $this->request->post[$affiliate_id . '_stock_control_id'];
		} else {
			$this->data['stock_control_id'] = $this->config->get($affiliate_id . '_stock_control_id');
		}

		$this->template = $this->config->get('config_template') . '/template/affiliate/dashboard_stock_control_config.tpl';
		
		$this->children = array(
			'affiliate/common/header',
			'affiliate/common/footer'
		);
		
		$this->response->setOutput($this->render());
	}

}// end class
?>