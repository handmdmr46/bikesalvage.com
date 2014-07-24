<?php
class ControllerExtrasBlogConfiguration extends Controller {
	private $error = array();
 
	public function index() {
		$this->load->language('extras/blog_configuration'); 

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$this->model_setting_setting->editSetting('blog', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extras/blog_configuration', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
 		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');	
		
		$this->data['entry_blog_list_image'] = $this->language->get('entry_blog_list_image');
		$this->data['entry_blog_list_max_chars'] = $this->language->get('entry_blog_list_max_chars');
		$this->data['entry_twitter_id'] = $this->language->get('entry_twitter_id');
		$this->data['entry_facebook_page_id'] = $this->language->get('entry_facebook_page_id');

		$this->data['tab_social'] = $this->language->get('tab_social');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
 		if (isset($this->error['blog_list_image'])) {
			$this->data['error_blog_list_image'] = $this->error['blog_list_image'];
		} else {
			$this->data['error_blog_list_image'] = '';
		}
		
 		if (isset($this->error['blog_list_max_chars'])) {
			$this->data['error_blog_list_max_chars'] = $this->error['blog_list_max_chars'];
		} else {
			$this->data['error_blog_list_max_chars'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extras/blog_configuration', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['action'] = $this->url->link('extras/blog_configuration', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->post['blog_list_image_width'])) {
			$this->data['blog_list_image_width'] = $this->request->post['blog_list_image_width'];
		} else {
			$this->data['blog_list_image_width'] = $this->config->get('blog_list_image_width');
		}

		if (isset($this->request->post['blog_list_image_height'])) {
			$this->data['blog_list_image_height'] = $this->request->post['blog_list_image_height'];
		} else {
			$this->data['blog_list_image_height'] = $this->config->get('blog_list_image_height');
		}
		
		if (isset($this->request->post['blog_list_max_chars'])) {
			$this->data['blog_list_max_chars'] = $this->request->post['blog_list_max_chars'];
		} else {
			$this->data['blog_list_max_chars'] = $this->config->get('blog_list_max_chars');
		}
		
		if (isset($this->request->post['blog_twitter_id'])) {
			$this->data['blog_twitter_id'] = $this->request->post['blog_twitter_id'];
		} else {
			$this->data['blog_twitter_id'] = $this->config->get('blog_twitter_id');
		}
		
		if (isset($this->request->post['blog_facebook_page_id'])) {
			$this->data['blog_facebook_page_id'] = $this->request->post['blog_facebook_page_id'];
		} else {
			$this->data['blog_facebook_page_id'] = $this->config->get('blog_facebook_page_id');
		}
						
		$this->template = 'extras/blog_configuration.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extras/blog_configuration')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
								
		if (!$this->request->post['blog_list_image_width'] || !$this->request->post['blog_list_image_width']) {
			$this->error['blog_list_image'] = $this->language->get('error_blog_list_image');
		} 
		
		if (!$this->request->post['blog_list_max_chars']) {
			$this->error['blog_list_max_chars'] = $this->language->get('error_blog_list_max_chars');
		}
		
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