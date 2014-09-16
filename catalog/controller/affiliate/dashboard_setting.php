<?php
class ControllerAffiliateDashboardSetting extends Controller {
	private $error = array();

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

		$this->document->setTitle($this->language->get('heading_title_setting'));
		
		$this->load->model('affiliate/dashboard_setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_affiliate_dashboard_setting->editSetting('config_affiliate', $this->request->post);

			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('affiliate/dashboard_setting', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		// Language
		$this->data['heading_title_setting'] = $this->language->get('heading_title_setting');
		
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_items'] = $this->language->get('text_items');
		$this->data['text_product'] = $this->language->get('text_product');
		$this->data['text_voucher'] = $this->language->get('text_voucher');
		$this->data['text_tax'] = $this->language->get('text_tax');
		$this->data['text_account'] = $this->language->get('text_account');
		$this->data['text_checkout'] = $this->language->get('text_checkout');
		$this->data['text_stock'] = $this->language->get('text_stock');
		$this->data['text_affiliate'] = $this->language->get('text_affiliate');
		$this->data['text_return'] = $this->language->get('text_return');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
 		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');	
		$this->data['text_shipping'] = $this->language->get('text_shipping');	
		$this->data['text_payment'] = $this->language->get('text_payment');					
		$this->data['text_mail'] = $this->language->get('text_mail');
		$this->data['text_smtp'] = $this->language->get('text_smtp');
		
		$this->data['entry_mail_protocol'] = $this->language->get('entry_mail_protocol');
		$this->data['entry_mail_parameter'] = $this->language->get('entry_mail_parameter');
		$this->data['entry_smtp_host'] = $this->language->get('entry_smtp_host');
		$this->data['entry_smtp_username'] = $this->language->get('entry_smtp_username');
		$this->data['entry_smtp_password'] = $this->language->get('entry_smtp_password');
		$this->data['entry_smtp_port'] = $this->language->get('entry_smtp_port');
		$this->data['entry_smtp_timeout'] = $this->language->get('entry_smtp_timeout');
		$this->data['entry_alert_mail'] = $this->language->get('entry_alert_mail');
		$this->data['entry_account_mail'] = $this->language->get('entry_account_mail');
		$this->data['entry_alert_emails'] = $this->language->get('entry_alert_emails');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		// Errors
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}		
		
 		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}		
		// Bredcrumbs
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('affiliate/dashboard_setting', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['action'] = $this->url->link('affiliate/dashboard_setting', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['token'] = $this->session->data['token'];
		

												
		if (isset($this->request->post['config_affiliate_affiliate_mail_protocol'])) {
			$this->data['config_affiliate_affiliate_mail_protocol'] = $this->request->post['config_affiliate_mail_protocol'];
		} else {
			$this->data['config_affiliate_affiliate_mail_protocol'] = $this->model_affiliate_dashboard_setting->getSettingValues('config_affiliate_mail_protocol');
		}
		
       if (isset($this->request->post['config_affiliate_mail_parameter'])) {
			$this->data['config_affiliate_mail_parameter'] = $this->request->post['config_affiliate_mail_parameter'];
			$this->data['config_affiliate_email'] = $this->request->post['config_affiliate_mail_parameter'];
		} elseif (count($this->model_affiliate_dashboard_setting->getSettingValues('config_affiliate_mail_parameter')) > 0) {
			$this->data['config_affiliate_mail_parameter'] = $this->model_affiliate_dashboard_setting->getSettingValues('config_affiliate_mail_parameter');
		} else {
			$this->data['config_affiliate_mail_parameter'] = '';
		}
		
		if (isset($this->request->post['config_affiliate_smtp_host'])) {
			$this->data['config_affiliate_smtp_host'] = $this->request->post['config_affiliate_smtp_host'];
		} elseif (count($this->model_affiliate_dashboard_setting->getSettingValues('config_affiliate_smtp_host')) > 0) {
			$this->data['config_affiliate_smtp_host'] = $this->model_affiliate_dashboard_setting->getSettingValues('config_affiliate_smtp_host');
		} else {
			$this->data['config_affiliate_smtp_host'] = '';
		}

		if (isset($this->request->post['config_affiliate_smtp_username'])) {
			$this->data['config_affiliate_smtp_username'] = $this->request->post['config_affiliate_smtp_username'];
		} elseif (count($this->model_affiliate_dashboard_setting->getSettingValues('config_affiliate_smtp_username')) > 0) {
			$this->data['config_affiliate_smtp_username'] = $this->model_affiliate_dashboard_setting->getSettingValues('config_affiliate_smtp_username');
		} else {
			$this->data['config_affiliate_smtp_username'] = '';
		}
		
		if (isset($this->request->post['config_affiliate_smtp_password'])) {
			$this->data['config_affiliate_smtp_password'] = $this->request->post['config_affiliate_smtp_password'];
		} elseif (count($this->model_affiliate_dashboard_setting->getSettingValues('config_affiliate_smtp_password')) > 0) {
			$this->data['config_affiliate_smtp_password'] = $this->model_affiliate_dashboard_setting->getSettingValues('config_affiliate_smtp_password');
		} else {
			$this->data['config_affiliate_smtp_password'] = '';
		}
		
		if (isset($this->request->post['config_affiliate_smtp_port'])) {
			$this->data['config_affiliate_smtp_port'] = $this->request->post['config_affiliate_smtp_port'];
		} elseif (count($this->model_affiliate_dashboard_setting->getSettingValues('config_affiliate_smtp_port')) > 0) {
			$this->data['config_affiliate_smtp_port'] = $this->model_affiliate_dashboard_setting->getSettingValues('config_affiliate_smtp_port');
		} else {
			$this->data['config_affiliate_smtp_port'] = 25;
		}	
		
		if (isset($this->request->post['config_affiliate_smtp_timeout'])) {
			$this->data['config_affiliate_smtp_timeout'] = $this->request->post['config_affiliate_smtp_timeout'];
		} elseif (count($this->model_affiliate_dashboard_setting->getSettingValues('config_affiliate_smtp_timeout')) > 0) {
			$this->data['config_affiliate_smtp_timeout'] = $this->model_affiliate_dashboard_setting->getSettingValues('config_affiliate_smtp_timeout');
		} else {
			$this->data['config_affiliate_smtp_timeout'] = 5;	
		}	
		
		if (isset($this->request->post['config_affiliate_alert_mail'])) {
			$this->data['config_affiliate_alert_mail'] = $this->request->post['config_affiliate_alert_mail'];
		} elseif (count($this->model_affiliate_dashboard_setting->getSettingValues('config_affiliate_alert_mail')) > 0) {
			$this->data['config_affiliate_alert_mail'] = $this->model_affiliate_dashboard_setting->getSettingValues('config_affiliate_alert_mail');
		} else {
			$this->data['config_affiliate_alert_mail'] = '';
		}
		
		if (isset($this->request->post['config_affiliate_alert_emails'])) {
			$this->data['config_affiliate_alert_emails'] = $this->request->post['config_affiliate_alert_emails'];
		} elseif (count($this->model_affiliate_dashboard_setting->getSettingValues('config_affiliate_alert_emails')) > 0) {
			$this->data['config_affiliate_alert_emails'] = $this->model_affiliate_dashboard_setting->getSettingValues('config_affiliate_alert_emails');
		} else {
			$this->data['config_affiliate_alert_emails'];
		}
		
						
		$this->template = $this->config->get('config_template') . '/template/affiliate/dashboard_setting.tpl';
		
		$this->children = array(
			'affiliate/common/header',
			'affiliate/common/footer'
		);
		
		$this->response->setOutput($this->render());		
	}

	protected function validate() {
    	/*if ((utf8_strlen($this->request->post['config_affiliate_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['config_affiliate_email'])) {
      		$this->error['email'] = $this->language->get('error_email');
    	}*/
				
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
			
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
		
	
	
}//end class