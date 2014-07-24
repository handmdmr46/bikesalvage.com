<?php
class ControllerExtrasSeoURL extends Controller { 
	private $error = array();

	public function index() {
		$this->load->language('extras/seo_url');

		$this->document->setTitle($this->language->get('heading_title'));
		 
		$this->load->model('extras/seo_url');

		$this->getList();
	}

	public function insert() {
		$this->load->language('extras/seo_url');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extras/seo_url');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extras_seo_url->addAliasURL($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->link('extras/seo_url', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('extras/seo_url');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extras/seo_url');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extras_seo_url->editAliasURL($this->request->get['url_alias_id'], $this->request->post);
						
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->link('extras/seo_url', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}
 
	public function delete() {
		$this->load->language('extras/seo_url');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extras/seo_url');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $url_alias_id) {
				$this->model_extras_seo_url->deleteAliasURL($url_alias_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->link('extras/seo_url', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'url_alias_id';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		$url = '';
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extras/seo_url', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('extras/seo_url/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('extras/seo_url/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');		

		$this->data['seo_urls'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$url_alias_total = $this->model_extras_seo_url->getTotalAliasURLs();
	
		$results = $this->model_extras_seo_url->getAliasURLs($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('extras/seo_url/update', 'token=' . $this->session->data['token'] . '&url_alias_id=' . $result['url_alias_id'] . $url, 'SSL')
			);
						
			$this->data['seo_urls'][] = array(
				'url_alias_id' => $result['url_alias_id'],
				'query'      => $result['query'],
				'keyword'      => $result['keyword'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['url_alias_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_url_alias_id'] = $this->language->get('column_url_alias_id');
		$this->data['column_query'] = $this->language->get('column_query');
		$this->data['column_keyword'] = $this->language->get('column_keyword');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
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

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_query'] = $this->url->link('extras/seo_url', 'token=' . $this->session->data['token'] . '&sort=query' . $url, 'SSL');
		$this->data['sort_keyword'] = $this->url->link('extras/seo_url', 'token=' . $this->session->data['token'] . '&sort=keyword' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $url_alias_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('extras/seo_url', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
					
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'extras/seo_url_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render()); 
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['tab_data'] = $this->language->get('tab_data');
		
		$this->data['text_default'] = $this->language->get('text_default');
		
		$this->data['entry_query'] = $this->language->get('entry_query');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['query'])) {
			$this->data['error_query'] = $this->error['query'];
		} else {
			$this->data['error_query'] = '';
		}
		
	 	if (isset($this->error['keyword'])) {
			$this->data['error_keyword'] = $this->error['keyword'];
		} else {
			$this->data['error_keyword'] = '';
		}
				
		$url = '';
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),     		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extras/seo_url', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		if (!isset($this->request->get['url_alias_id'])) {
			$this->data['action'] = $this->url->link('extras/seo_url/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('extras/seo_url/update', 'token=' . $this->session->data['token'] . '&url_alias_id=' . $this->request->get['url_alias_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('extras/seo_url', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['url_alias_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$seo_url_info = $this->model_extras_seo_url->getAliasURL($this->request->get['url_alias_id']);
		}
				
		if (isset($this->request->post['query'])) {
			$this->data['query'] = $this->request->post['query'];
		} elseif (isset($seo_url_info)) {
			$this->data['query'] = $seo_url_info['query'];
		} else {
			$this->data['query'] = '';
		}
				
		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (isset($seo_url_info)) {
			$this->data['keyword'] = $seo_url_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		
		$this->template = 'extras/seo_url_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render()); 
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'extras/seo_url')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((strlen($this->request->post['query']) < 3)) {
			$this->error['query'] = $this->language->get('error_query');
		}

		if ((strlen($this->request->post['keyword']) < 3)) {
			$this->error['keyword'] = $this->language->get('error_keyword');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			if (!isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_required_data');
			}
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extras/seo_url')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>