<?php 
class ControllerExtrasBlogFeed extends Controller {
	
	private $error = array(); 
	
	public function index() {
		$this->load->language('extras/blog_feed');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('blog_feed', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extras/blog_feed', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_data_feed'] = $this->language->get('entry_data_feed');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
				
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_blog_feed'),
			'href'      => $this->url->link('extras/blog_feed', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		//$this->data['breadcrumbs'][] = array(
       	//	'text'      => $this->language->get('heading_title'),
		//	'href'      => $this->url->link('extras/blog_feed', 'token=' . $this->session->data['token'], 'SSL'),
      	//	'separator' => ' :: '
   		//);
				
		$this->data['action'] = $this->url->link('extras/blog_feed', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extras/blog_feed', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['blog_feed_status'])) {
			$this->data['blog_feed_status'] = $this->request->post['blog_feed_status'];
		} else {
			$this->data['blog_feed_status'] = $this->config->get('blog_feed_status');
		}
		
		$this->data['data_feed'] = HTTP_CATALOG . 'index.php?route=extras/blog_feed';

		$this->template = 'extras/blog_feed.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	} 
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'extras/blog_feed')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}	
}
?>