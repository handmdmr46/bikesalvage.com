<?php    
class ControllerAffiliateDashboardEditPassword extends Controller {    
	private $error = array();

	public function index() {
		if (!$this->affiliate->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('affiliate/dashboard_edit_password', '', 'SSL');
			$this->redirect($this->url->link('affiliate/login', '', 'SSL'));
		}

		$this->data['template_url'] = 'catalog/view/theme/' . $this->config->get('config_template');

		$this->language->load('affiliate/dashboard');
		$this->load->model('affiliate/dashboard_profile');
		$this->document->setTitle($this->language->get('heading_title_edit_password'));
		 
    	$this->getForm();
    }


	protected function getForm() { 
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title_edit_password'),
			'href'      => $this->url->link('affiliate/dashboard_edit_password', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['heading_title_edit_password']  = $this->language->get('heading_title_edit_password');

		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_confirm']  = $this->language->get('entry_confirm');
		$this->data['button_save']    = $this->language->get('button_save');
		$this->data['button_cancel']  = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
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

		if (isset($this->session->data['success'])) {
    		$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['action'] = $this->url->link('affiliate/dashboard_edit_password/edit', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['token'] = $this->session->data['token'];	

		if (isset($this->request->post['password'])) {
    		$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}
		
		if (isset($this->request->post['confirm'])) {
    		$this->data['confirm'] = $this->request->post['confirm'];
		} else {
			$this->data['confirm'] = '';
		}

		$this->template = $this->config->get('config_template') . '/template/affiliate/dashboard_edit_password.tpl';
		
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

		$this->document->setTitle($this->language->get('heading_title_edit_password'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_affiliate_dashboard_profile->editPassword($this->request->post, $this->affiliate->getId());

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('affiliate/dashboard_edit_password', 'token=' . $this->session->data['token'], 'SSL'));
	    }

	    $this->getForm();
    }
	
  	protected function validateForm() {
    	if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
      		$this->error['password'] = $this->language->get('error_password');
    	}

    	if ($this->request->post['confirm'] != $this->request->post['password']) {
      		$this->error['confirm'] = $this->language->get('error_confirm');
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