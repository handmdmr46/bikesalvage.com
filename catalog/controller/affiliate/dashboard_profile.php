<?php
class ControllerAffiliateDashboardProfile extends Controller {
	private $error = array();

	public function index() {
		if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
		    $this->session->data['redirect'] = $this->url->link('affiliate/dashboard', '', 'SSL');
	  		$this->redirect($this->url->link('affiliate/login', '', 'SSL'));
		}
		
		if (!$this->affiliate->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('affiliate/dashboard_profile', '', 'SSL');
			$this->redirect($this->url->link('affiliate/login', '', 'SSL'));
		}

		$this->data['template_url'] = 'catalog/view/theme/' . $this->config->get('config_template');

		$this->language->load('affiliate/dashboard');
		$this->load->model('affiliate/dashboard_profile');
		$this->document->setTitle($this->language->get('heading_title_profile'));
		 
    	$this->getForm();
    }

    protected function getForm() {
		// Breadcrumbs
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_profile'),
			'href'      => $this->url->link('affiliate/dashboard_profile', 'token=' . $this->session->data['token'], 'SSL'),       		
      		'separator' => ' :: '
   		);

		// Language		
    	$this->data['heading_title_profile'] = $this->language->get('heading_title_profile');

		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('affiliate/login', '', 'SSL'));
    	$this->data['text_signup'] = $this->language->get('text_signup');
		$this->data['text_your_details'] = $this->language->get('text_your_details');
    	$this->data['text_your_address'] = $this->language->get('text_your_address');
		$this->data['text_payment'] = $this->language->get('text_payment');
    	$this->data['text_your_password'] = $this->language->get('text_your_password');
		$this->data['text_cheque'] = $this->language->get('text_cheque');
		$this->data['text_paypal'] = $this->language->get('text_paypal');
		$this->data['text_bank'] = $this->language->get('text_bank');
				
    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_other_email'] = $this->language->get('entry_other_email');
    	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
    	$this->data['entry_fax'] = $this->language->get('entry_fax');
    	$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_website'] = $this->language->get('entry_website');
    	$this->data['entry_address_1'] = $this->language->get('entry_address_1');
    	$this->data['entry_address_2'] = $this->language->get('entry_address_2');
    	$this->data['entry_postcode'] = $this->language->get('entry_postcode');
    	$this->data['entry_city'] = $this->language->get('entry_city');
    	$this->data['entry_country'] = $this->language->get('entry_country');
    	$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_tax'] = $this->language->get('entry_tax');
		$this->data['entry_payment'] = $this->language->get('entry_payment');
		$this->data['entry_cheque'] = $this->language->get('entry_cheque');
		$this->data['entry_paypal'] = $this->language->get('entry_paypal');
		$this->data['entry_bank_name'] = $this->language->get('entry_bank_name');
		$this->data['entry_bank_branch_number'] = $this->language->get('entry_bank_branch_number');
		$this->data['entry_bank_swift_code'] = $this->language->get('entry_bank_swift_code');
		$this->data['entry_bank_account_name'] = $this->language->get('entry_bank_account_name');
		$this->data['entry_bank_account_number'] = $this->language->get('entry_bank_account_number');
    	$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_confirm'] = $this->language->get('entry_confirm');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		// Error
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['firstname'])) {
			$this->data['error_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_firstname'] = '';
		}	
		
		if (isset($this->error['lastname'])) {
			$this->data['error_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_lastname'] = '';
		}		
	
		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
		
		if (isset($this->error['telephone'])) {
			$this->data['error_telephone'] = $this->error['telephone'];
		} else {
			$this->data['error_telephone'] = '';
		}
		
		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}
		
 		if (isset($this->error['confirm'])) {
			$this->data['error_confirm'] = $this->error['confirm'];
		} else {
			$this->data['error_confirm'] = '';
		}
		
  		if (isset($this->error['address_1'])) {
			$this->data['error_address_1'] = $this->error['address_1'];
		} else {
			$this->data['error_address_1'] = '';
		}
   		
		if (isset($this->error['city'])) {
			$this->data['error_city'] = $this->error['city'];
		} else {
			$this->data['error_city'] = '';
		}
		
		if (isset($this->error['postcode'])) {
			$this->data['error_postcode'] = $this->error['postcode'];
		} else {
			$this->data['error_postcode'] = '';
		}

		if (isset($this->error['country'])) {
			$this->data['error_country'] = $this->error['country'];
		} else {
			$this->data['error_country'] = '';
		}

		if (isset($this->error['zone'])) {
			$this->data['error_zone'] = $this->error['zone'];
		} else {
			$this->data['error_zone'] = '';
		}

		// Success	
		if (isset($this->session->data['success'])) {
    		$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
        $this->data['action'] = $this->url->link('affiliate/dashboard_profile/edit', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['token'] = $this->session->data['token'];	
		
		$this->data['result'] = $this->model_affiliate_dashboard_profile->getAffiliate($this->affiliate->getId());
		
		// Fields
		if (isset($this->request->post['firstname'])) {
    		$this->data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($this->data['result']['firstname'])) {
			$this->data['firstname'] = $this->data['result']['firstname'];
		} else {
			$this->data['firstname'] = '';
		}

		if (isset($this->request->post['lastname'])) {
    		$this->data['lastname'] = $this->request->post['lastname'];
		} elseif (!empty($this->data['result']['lastname'])) {
			$this->data['lastname'] = $this->data['result']['lastname'];
		} else {
			$this->data['lastname'] = '';
		}
		
		if (isset($this->request->post['email'])) {
    		$this->data['email'] = $this->request->post['email'];
		} elseif (!empty($this->data['result']['email'])) {
			$this->data['email'] = $this->data['result']['email'];
		} else {
			$this->data['email'] = '';
		}

		if (isset($this->request->post['other_email'])) {
    		$this->data['other_email'] = $this->request->post['other_email'];
		} elseif (!empty($this->data['result']['other_email'])) {
			$this->data['other_email'] = $this->data['result']['other_email'];
		} else {
			$this->data['other_email'] = '';
		}
		
		if (isset($this->request->post['telephone'])) {
    		$this->data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($this->data['result']['telephone'])) {
			$this->data['telephone'] = $this->data['result']['telephone'];
		} else {
			$this->data['telephone'] = '';
		}
		
		if (isset($this->request->post['fax'])) {
    		$this->data['fax'] = $this->request->post['fax'];
		} elseif (!empty($this->data['result']['fax'])) {
			$this->data['fax'] = $this->data['result']['fax'];
		} else {
			$this->data['fax'] = '';
		}
		
		if (isset($this->request->post['company'])) {
    		$this->data['company'] = $this->request->post['company'];
		} elseif (!empty($this->data['result']['company'])) {
			$this->data['company'] = $this->data['result']['company'];
		} else {
			$this->data['company'] = '';
		}

		if (isset($this->request->post['website'])) {
    		$this->data['website'] = $this->request->post['website'];
		} elseif (!empty($this->data['result']['website'])) {
			$this->data['website'] = $this->data['result']['website'];
		} else {
			$this->data['website'] = '';
		}
				
		if (isset($this->request->post['address_1'])) {
    		$this->data['address_1'] = $this->request->post['address_1'];
		} elseif (!empty($this->data['result']['address_1'])) {
			$this->data['address_1'] = $this->data['result']['address_1'];
		} else {
			$this->data['address_1'] = '';
		}

		if (isset($this->request->post['address_2'])) {
    		$this->data['address_2'] = $this->request->post['address_2'];
		} elseif (!empty($this->data['result']['address_2'])) {
			$this->data['address_2'] = $this->data['result']['address_2'];
		} else {
			$this->data['address_2'] = '';
		}

		if (isset($this->request->post['postcode'])) {
    		$this->data['postcode'] = $this->request->post['postcode'];
		} elseif (!empty($this->data['result']['postcode'])) {
			$this->data['postcode'] = $this->data['result']['postcode'];
		} else {
			$this->data['postcode'] = '';
		}
		
		if (isset($this->request->post['city'])) {
    		$this->data['city'] = $this->request->post['city'];
		} elseif (!empty($this->data['result']['city'])) {
			$this->data['city'] = $this->data['result']['city'];
		} else {
			$this->data['city'] = '';
		}

    	if (isset($this->request->post['country_id'])) {
      		$this->data['country_id'] = $this->request->post['country_id'];
		} elseif (!empty($this->data['result']['country_id'])) {
			$this->data['country_id'] = $this->data['result']['country_id'];
		} else {	
      		$this->data['country_id'] = $this->config->get('config_country_id');
    	}

    	if (isset($this->request->post['zone_id'])) {
      		$this->data['zone_id'] = $this->request->post['zone_id']; 	
		} elseif (!empty($this->data['result']['zone_id'])) {
			$this->data['zone_id'] = $this->data['result']['zone_id'];
		} else {
      		$this->data['zone_id'] = '';
    	}
		
		$this->load->model('affiliate/dashboard_country');
		
    	$this->data['countries'] = $this->model_affiliate_dashboard_country->getcountries();

		if (isset($this->request->post['tax'])) {
    		$this->data['tax'] = $this->request->post['tax'];
		} elseif (!empty($this->data['result']['tax'])) {
			$this->data['tax'] = $this->data['result']['tax'];
		} else {
			$this->data['tax'] = '';
		}
		
		if (isset($this->request->post['payment'])) {
    		$this->data['payment'] = $this->request->post['payment'];
		} elseif (!empty($this->data['result']['payment'])) {
			$this->data['payment'] = $this->data['result']['payment'];
		} else {
			$this->data['payment'] = 'cheque';
		}

		if (isset($this->request->post['cheque'])) {
    		$this->data['cheque'] = $this->request->post['cheque'];
		} elseif (!empty($this->data['result']['cheque'])) {
			$this->data['cheque'] = $this->data['result']['cheque'];
		} else {
			$this->data['cheque'] = '';
		}

		if (isset($this->request->post['paypal'])) {
    		$this->data['paypal'] = $this->request->post['paypal'];
		} elseif (!empty($this->data['result']['paypal'])) {
			$this->data['paypal'] = $this->data['result']['paypal'];
		} else {
			$this->data['paypal'] = '';
		}

		if (isset($this->request->post['bank_name'])) {
    		$this->data['bank_name'] = $this->request->post['bank_name'];
		} elseif (!empty($this->data['result']['bank_name'])) {
			$this->data['bank_name'] = $this->data['result']['bank_name'];
		} else {
			$this->data['bank_name'] = '';
		}

		if (isset($this->request->post['bank_branch_number'])) {
    		$this->data['bank_branch_number'] = $this->request->post['bank_branch_number'];
		} elseif (!empty($this->data['result']['bank_branch_number'])) {
			$this->data['bank_branch_number'] = $this->data['result']['bank_branch_number'];
		} else {
			$this->data['bank_branch_number'] = '';
		}

		if (isset($this->request->post['bank_swift_code'])) {
    		$this->data['bank_swift_code'] = $this->request->post['bank_swift_code'];
		} elseif (!empty($this->data['result']['bank_swift_code'])) {
			$this->data['bank_swift_code'] = $this->data['result']['bank_swift_code'];
		} else {
			$this->data['bank_swift_code'] = '';
		}

		if (isset($this->request->post['bank_account_name'])) {
    		$this->data['bank_account_name'] = $this->request->post['bank_account_name'];
		} elseif (!empty($this->data['result']['bank_account_name'])) {
			$this->data['bank_account_name'] = $this->data['result']['bank_account_name'];
		} else {
			$this->data['bank_account_name'] = '';
		}
		
		if (isset($this->request->post['bank_account_number'])) {
    		$this->data['bank_account_number'] = $this->request->post['bank_account_number'];
		} elseif (!empty($this->data['result']['bank_account_number'])) {
			$this->data['bank_account_number'] = $this->data['result']['bank_account_number'];
		} else {
			$this->data['bank_account_number'] = '';
		}
		
		$this->template = $this->config->get('config_template') . '/template/affiliate/dashboard_profile.tpl';
		
		$this->children = array(
			'affiliate/common/header',
			'affiliate/common/footer'
		);
		
		$this->response->setOutput($this->render());	
	}

	public function edit() {
		$this->data['template_url'] = 'catalog/view/theme/' . $this->config->get('config_template');

		$this->language->load('affiliate/dashboard');

		$this->load->model('affiliate/dashboard_profile');

		$this->document->setTitle($this->language->get('heading_title_profile'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_affiliate_dashboard_profile->editAffiliate($this->request->post, $this->affiliate->getId());

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('affiliate/dashboard_profile', 'token=' . $this->session->data['token'], 'SSL'));
	    }

	    $this->getForm();
    }
	
  	protected function validateForm() {
    	if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

    	if ((utf8_strlen($this->request->post['email']) > 96) || (utf8_strlen($this->request->post['email']) < 5)) {
      		$this->error['email'] = $this->language->get('error_email');
    	}
		
    	if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
    	}

    	if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
      		$this->error['address_1'] = $this->language->get('error_address_1');
    	}

    	if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128)) {
      		$this->error['city'] = $this->language->get('error_city');
    	}
		
		$this->load->model('affiliate/dashboard_country');
		
		$country_info = $this->model_affiliate_dashboard_country->getcountry($this->request->post['country_id']);
		
		if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
			$this->error['postcode'] = $this->language->get('error_postcode');
		}

    	if ($this->request->post['country_id'] == '') {
      		$this->error['country'] = $this->language->get('error_country');
    	}
		
    	if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
      		$this->error['zone'] = $this->language->get('error_zone');
    	}
				
    	if (!$this->error) {
      		return true;
    	} else {
    		$this->error['warning'] = $this->language->get('error_warning');
      		return false;
    	}
  	}
  
	public function country() {
		$json = array();
		
		$this->load->model('affiliate/dashboard_country');

    	$country_info = $this->model_affiliate_dashboard_country->getcountry($this->request->get['country_id']);
		
		if ($country_info) {
			$this->load->model('affiliate/dashboard_localisation_zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_affiliate_dashboard_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']		
			);
		}
		
		$this->response->setOutput(json_encode($json));
	}	
	
	
	
	
}// end class