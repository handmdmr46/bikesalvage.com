<?php 
class ControllerExtrasCustomSetting extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('extras/custom_setting');
    	
		$this->document->setTitle($this->language->get('heading_title')); 		
		
		$this->getList();
  	}

	protected function getList() {
		$this->data['heading_title']                         = $this->language->get('heading_title');
		$this->data['entry_international_shipping_methods']  = $this->language->get('entry_international_shipping_methods');
		$this->data['entry_domestic_shipping_methods']       = $this->language->get('entry_domestic_shipping_methods');
		$this->data['entry_affiliate_order_complete_status'] = $this->language->get('entry_affiliate_order_complete_status');
		$this->data['entry_category_count_minimum_sidebar']  = $this->language->get('entry_category_count_minimum_sidebar');
		$this->data['entry_category_count_minimum_menu']     = $this->language->get('entry_category_count_minimum_menu');
		$this->data['button_cancel']                         = $this->language->get('button_cancel');
		$this->data['button_save']                           = $this->language->get('button_save');
		$this->data['text_select']                           = $this->language->get('text_select');
		$this->data['text_user_token']                       = $this->language->get('text_user_token');
		$this->data['text_developer_id']                     = $this->language->get('text_developer_id');
		$this->data['text_application_id']                   = $this->language->get('text_application_id');
		$this->data['text_certification_id']                 = $this->language->get('text_certification_id');
		$this->data['text_compat_level']                     = $this->language->get('text_compat_level');
		$this->data['text_none']                             = $this->language->get('text_none');
		$this->data['text_compat_help']                      = $this->language->get('text_compat_help');
		$this->data['text_confirm_clear_dates']              = $this->language->get('text_confirm_clear_dates');
		$this->data['text_site_id']                          = $this->language->get('text_site_id');

		$this->data['token'] = $this->session->data['token']; 
		$url = '';

   		$this->data['cancel'] = $this->url->link('common/home', 'token=' . $this->session->data['token'] . $url, 'SSL');
   		$this->data['action'] = $this->url->link('extras/custom_setting', 'token=' . $this->session->data['token'], 'SSL');

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

 		if (isset($this->error['warning'])) {
			$this->data['error'] = $this->error['warning'];
		} else {
			$this->data['error'] = '';
		}		

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

   		$this->load->model('shipping/custom_shipping');
   		$this->data['shipping_methods'] = $this->model_shipping_custom_shipping->getShippingMethods();

   		$this->load->model('localisation/transaction_status');
		$this->data['transaction_statuses'] = $this->model_localisation_transaction_status->getTransactionStatuses();	
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('setting/setting');
			$this->load->model('inventory/stock_control');
			$this->model_setting_setting->editSetting('custom_setting', $this->request->post);
			$this->model_inventory_stock_control->setEbayProfile($this->request->post);
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

		if (isset($this->request->post['category_count_minimum_sidebar'])) {
			$this->data['category_count_minimum_sidebar'] = $this->request->post['category_count_minimum_sidebar'];
		} else {
			$this->data['category_count_minimum_sidebar'] = $this->config->get('category_count_minimum_sidebar');
		}

		if (isset($this->request->post['category_count_minimum_menu'])) {
			$this->data['category_count_minimum_menu'] = $this->request->post['category_count_minimum_menu'];
		} else {
			$this->data['category_count_minimum_menu'] = $this->config->get('category_count_minimum_menu');
		}

		$this->load->model('import/csv_import');
		$profiles                        = $this->model_import_csv_import->getEbayProfile();
	    $this->data['ebay_sites']        = $this->model_import_csv_import->getEbaySiteIds();
	    $this->data['compat_levels']     = $this->model_import_csv_import->getEbayCompatibilityLevels();
	    $this->data['dates']             = $this->model_import_csv_import->getEbayImportStartDates();
	   
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

	    if(!empty($profiles)) {
	      $this->data['compat'] = $profiles['compat'];
	    } else {
	      $this->data['compat'] = '';
	    }

   		$this->template = 'extras/custom_setting.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	protected function validate() {
		/*if (!$this->user->hasPermission('modify', 'extras/custom_setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
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
}
?>