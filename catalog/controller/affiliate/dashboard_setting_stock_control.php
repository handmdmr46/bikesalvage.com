<?php
class ControllerAffiliateDashboardSettingStockControl extends Controller {
	private $error = array();

	public function index() {
		if (!$this->affiliate->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('affiliate/dashboard_setting_stock_control', '', 'SSL');
			$this->redirect($this->url->link('affiliate/login', '', 'SSL'));
		}

		$this->language->load('affiliate/dashboard');
		$this->load->model('affiliate/dashboard_stock_control');

		$this->document->setTitle($this->language->get('heading_title_stock_control_config'));

		$this->data['template_url'] = 'catalog/view/theme/' . $this->config->get('config_template');

		$affiliate_id = $this->affiliate->getId();

		$this->data['affiliate_id'] = $affiliate_id;
		$group = $affiliate_id . '_stock_control';

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting($group, $this->request->post);
			$this->model_affiliate_dashboard_stock_control->setEbayProfile($this->request->post, $affiliate_id);

			$this->session->data['success'] = $this->language->get('success_stock_control_config');

			$this->redirect($this->url->link('affiliate/dashboard_setting_stock_control', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title_stock_control_config'] = $this->language->get('heading_title_stock_control_config');
		$this->data['entry_stock_control_config']         = $this->language->get('entry_stock_control_config');
		$this->data['entry_build_log']                    = $this->language->get('entry_build_log');
		$this->data['button_save']                        = $this->language->get('button_save');
		$this->data['button_cancel']                      = $this->language->get('button_cancel');
		$this->data['button_build_log']                   = $this->language->get('button_build_log');
		$this->data['text_yes']                           = $this->language->get('text_yes');
		$this->data['text_no']                            = $this->language->get('text_no');
		$this->data['text_none']                          = $this->language->get('text_none');
		$this->data['text_no_dates']                      = $this->language->get('text_no_dates');
		$this->data['text_user_token']                    = $this->language->get('text_user_token');
		$this->data['text_developer_id']                  = $this->language->get('text_developer_id');
		$this->data['text_application_id']                = $this->language->get('text_application_id');
		$this->data['text_certification_id']              = $this->language->get('text_certification_id');
		$this->data['text_compat_level']                  = $this->language->get('text_compat_level');
		$this->data['text_compat_help']                   = $this->language->get('text_compat_help');
		$this->data['text_site_id']                       = $this->language->get('text_site_id');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('affiliate/dashboard_setting_stock_control', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		if (isset($this->session->data['error'])) {
        	$this->data['error'] = $this->session->data['error'];
      		unset($this->session->data['error']);
    	} else {
      		$this->data['error'] = '';
    	}

    	if (isset($this->session->data['error_user_token'])) {
	      $this->data['error_user_token'] = $this->session->data['error_user_token'];
	      unset($this->session->data['error_user_token']);
	    } else {
	      $this->data['error_user_token'] = '';
	    }

	    if (isset($this->session->data['error_developer_id'])) {
	      $this->data['error_developer_id'] = $this->session->data['error_developer_id'];
	      unset($this->session->data['error_developer_id']);
	    } else {
	      $this->data['error_developer_id'] = '';
	    }

	    if (isset($this->session->data['error_application_id'])) {
	      $this->data['error_application_id'] = $this->session->data['error_application_id'];
	      unset($this->session->data['error_application_id']);
	    } else {
	      $this->data['error_application_id'] = '';
	    }

	    if (isset($this->session->data['error_certification_id'])) {
	      $this->data['error_certification_id'] = $this->session->data['error_certification_id'];
	      unset($this->session->data['error_certification_id']);
	    } else {
	      $this->data['error_certification_id'] = '';
	    }

	    if (isset($this->session->data['error_site_id'])) {
	      $this->data['error_site_id'] = $this->session->data['error_site_id'];
	      unset($this->session->data['error_site_id']);
	    } else {
	      $this->data['error_site_id'] = '';
	    }

		if (isset($this->session->data['success'])) {
    		$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['action']    = $this->url->link('affiliate/dashboard_setting_stock_control', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel']    = $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post[$affiliate_id . '_stock_control_id'])) {
			$this->data['stock_control_id'] = $this->request->post[$affiliate_id . '_stock_control_id'];
		} else {
			$this->data['stock_control_id'] = $this->config->get($affiliate_id . '_stock_control_id');
		}

		$affiliate_id                    = $this->affiliate->getId();
	    $profiles                        = $this->model_affiliate_dashboard_stock_control->getEbayProfile($affiliate_id);
	    $this->data['ebay_sites']        = $this->model_affiliate_dashboard_stock_control->getEbaySiteIds();
	    $this->data['compat_levels']     = $this->model_affiliate_dashboard_stock_control->getEbayCompatibilityLevels();

	    if (!empty($profiles)) {
	      $this->data['developer_id'] = $profiles['developer_id'];
	    } else {
	      $this->data['developer_id'] = '';
	    }

	    if (!empty($profiles)) {
	      $this->data['application_id'] = $profiles['application_id'];
	    } else {
	      $this->data['application_id'] = '';
	    }

	    if (!empty($profiles)) {
	      $this->data['certification_id'] = $profiles['certification_id'];
	    } else {
	      $this->data['certification_id'] = '';
	    }

	    if (!empty($profiles)) {
	      $this->data['user_token'] = $profiles['user_token'];
	    } else {
	      $this->data['user_token'] = '';
	    }

	    if (!empty($profiles)) {
	      $this->data['site_id'] = $profiles['site_id'];
	    } else {
	      $this->data['site_id'] = '';
	    }

		$this->template = $this->config->get('config_template') . '/template/affiliate/dashboard_setting_stock_control.tpl';

		$this->children = array(
			'affiliate/common/header',
			'affiliate/common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validate() {
		/*if (!$this->user->hasPermission('modify', 'extras/custom_setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}*/

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateSaveEbayProfile() {
	    $boolean = 1;

	    if ((utf8_strlen($this->request->post['user_token']) < 1) || (utf8_strlen($this->request->post['user_token']) > 872)) {
	        $this->session->data['error_user_token'] = $this->language->get('error_user_token');
	        $this->session->data['error'] = $this->language->get('error');
	        $boolean = 0;
	    }

	    if ((utf8_strlen($this->request->post['developer_id']) < 1) || (utf8_strlen($this->request->post['developer_id']) > 36)) {
	        $this->session->data['error_developer_id'] = $this->language->get('error_developer_id');
	        $this->session->data['error'] = $this->language->get('error');
	        $boolean = 0;
	    }

	    if ((utf8_strlen($this->request->post['certification_id']) < 1) || (utf8_strlen($this->request->post['certification_id']) > 36)) {
	        $this->session->data['error_certification_id'] = $this->language->get('error_certification_id');
	        $this->session->data['error'] = $this->language->get('error');
	        $boolean = 0;
	    }

	    if ((utf8_strlen($this->request->post['application_id']) < 1) || (utf8_strlen($this->request->post['application_id']) > 36)) {
	        $this->session->data['error_application_id'] = $this->language->get('error_application_id');
	        $this->session->data['error'] = $this->language->get('error');
	        $boolean = 0;
	    }

	    if($this->request->post['site_id'] == 999) {
	      $this->session->data['error_site_id'] = $this->language->get('error_site_id');
	        $this->session->data['error'] = $this->language->get('error');
	        $boolean = 0;
	    }

	    if($this->request->post['compatability_level'] == 999) {
	      $this->session->data['error_compatability_level'] = $this->language->get('error_compatability_level');
	        $this->session->data['error'] = $this->language->get('error');
	        $boolean = 0;
	    }

	    return $boolean;
    }
}
?>