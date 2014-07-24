<?php
class ControllerExtrasBlogComment extends Controller {
	
	private $error = array();
 
	public function index() {
		$this->load->language('extras/blog_comment');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extras/blog_comment');
		
		$this->getList();
	} 

	public function insert() {
		$this->load->language('extras/blog_comment');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extras/blog_comment');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extras_blog_comment->addBlogComment($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
			$this->redirect($this->url->link('extras/blog_comment', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('extras/blog_comment');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extras/blog_comment');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extras_blog_comment->editBlogComment($this->request->get['blog_comment_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
			$this->redirect($this->url->link('extras/blog_comment', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() { 
		$this->load->language('extras/blog_comment');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extras/blog_comment');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $blog_comment_id) {
				$this->model_extras_blog_comment->deleteBlogComment($blog_comment_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
			$this->redirect($this->url->link('extras/blog_comment', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'bc.date_added';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
				
		$url = '';
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extras/blog_comment', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('extras/blog_comment/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('extras/blog_comment/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$this->data['blog_comments'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$blog_comment_total = $this->model_extras_blog_comment->getTotalBlogComments();
	
		$results = $this->model_extras_blog_comment->getBlogComments($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('extras/blog_comment/update', 'token=' . $this->session->data['token'] . '&blog_comment_id=' . $result['blog_comment_id'] . $url, 'SSL')
			);
						
			$this->data['blog_comments'][] = array(
				'blog_comment_id'  => $result['blog_comment_id'],
				'title'       => $result['title'],
				'author'     => $result['author'],
				'rating'     => $result['rating'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added' => date('Y/m/d \a\t h:i a', strtotime($result['date_added'])),
				'selected'   => isset($this->request->post['selected']) && in_array($result['blog_comment_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_author'] = $this->language->get('column_author');
		$this->data['column_rating'] = $this->language->get('column_rating');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
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
		
		$this->data['sort_title'] = $this->url->link('extras/blog_comment', 'token=' . $this->session->data['token'] . '&sort=bd.title' . $url, 'SSL');
		$this->data['sort_author'] = $this->url->link('extras/blog_comment', 'token=' . $this->session->data['token'] . '&sort=bc.author' . $url, 'SSL');
		$this->data['sort_rating'] = $this->url->link('extras/blog_comment', 'token=' . $this->session->data['token'] . '&sort=bc.rating' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('extras/blog_comment', 'token=' . $this->session->data['token'] . '&sort=bc.status' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('extras/blog_comment', 'token=' . $this->session->data['token'] . '&sort=bc.date_added' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $blog_comment_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('extras/blog_comment', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'extras/blog_comment_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_select'] = $this->language->get('text_select');

		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_author'] = $this->language->get('entry_author');
		$this->data['entry_rating'] = $this->language->get('entry_rating');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_text'] = $this->language->get('entry_text');
		$this->data['entry_good'] = $this->language->get('entry_good');
		$this->data['entry_bad'] = $this->language->get('entry_bad');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
 		
		if (isset($this->error['blog'])) {
			$this->data['error_blog'] = $this->error['blog'];
		} else {
			$this->data['error_blog'] = '';
		}
		
 		if (isset($this->error['author'])) {
			$this->data['error_author'] = $this->error['author'];
		} else {
			$this->data['error_author'] = '';
		}
		
 		if (isset($this->error['text'])) {
			$this->data['error_text'] = $this->error['text'];
		} else {
			$this->data['error_text'] = '';
		}
		
 		if (isset($this->error['rating'])) {
			$this->data['error_rating'] = $this->error['rating'];
		} else {
			$this->data['error_rating'] = '';
		}

		$url = '';
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
				
   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extras/blog_comment', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
										
		if (!isset($this->request->get['blog_comment_id'])) { 
			$this->data['action'] = $this->url->link('extras/blog_comment/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('extras/blog_comment/update', 'token=' . $this->session->data['token'] . '&blog_comment_id=' . $this->request->get['blog_comment_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('extras/blog_comment', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['blog_comment_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$blog_comment_info = $this->model_extras_blog_comment->getBlogComment($this->request->get['blog_comment_id']);
		}
		
		$this->data['token'] = $this->session->data['token'];
			
		$this->load->model('extras/blog');
		
		if (isset($this->request->post['blog_id'])) {
			$this->data['blog_id'] = $this->request->post['blog_id'];
		} elseif (!empty($blog_comment_info)) {
			$this->data['blog_id'] = $blog_comment_info['blog_id'];
		} else {
			$this->data['blog_id'] = '';
		}

		if (isset($this->request->post['blog'])) {
			$this->data['blog'] = $this->request->post['blog'];
		} elseif (!empty($blog_comment_info)) {
			$this->data['blog'] = $blog_comment_info['blog'];
		} else {
			$this->data['blog'] = '';
		}
				
		if (isset($this->request->post['author'])) {
			$this->data['author'] = $this->request->post['author'];
		} elseif (!empty($blog_comment_info)) {
			$this->data['author'] = $blog_comment_info['author'];
		} else {
			$this->data['author'] = '';
		}

		if (isset($this->request->post['text'])) {
			$this->data['text'] = $this->request->post['text'];
		} elseif (!empty($blog_comment_info)) {
			$this->data['text'] = $blog_comment_info['text'];
		} else {
			$this->data['text'] = '';
		}

		if (isset($this->request->post['rating'])) {
			$this->data['rating'] = $this->request->post['rating'];
		} elseif (!empty($blog_comment_info)) {
			$this->data['rating'] = $blog_comment_info['rating'];
		} else {
			$this->data['rating'] = '';
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($blog_comment_info)) {
			$this->data['status'] = $blog_comment_info['status'];
		} else {
			$this->data['status'] = '';
		}

		$this->template = 'extras/blog_comment_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'extras/blog_comment')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['blog_id']) {
			$this->error['blog'] = $this->language->get('error_blog');
		}
		
		if ((utf8_strlen($this->request->post['author']) < 3) || (utf8_strlen($this->request->post['author']) > 64)) {
			$this->error['author'] = $this->language->get('error_author');
		}

		if (utf8_strlen($this->request->post['text']) < 1) {
			$this->error['text'] = $this->language->get('error_text');
		}
				
		if (!isset($this->request->post['rating'])) {
			$this->error['rating'] = $this->language->get('error_rating');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extras/blog_comment')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}	
		
	public function autocomplete() {
		$json = array();
		
		if (isset($this->request->get['filter_title']) || isset($this->request->get['filter_category_id'])) {
			$this->load->model('extras/blog');
			
			if (isset($this->request->get['filter_title'])) {
				$filter_title = $this->request->get['filter_title'];
			} else {
				$filter_title = '';
			}
						
			if (isset($this->request->get['filter_category_id'])) {
				$filter_category_id = $this->request->get['filter_category_id'];
			} else {
				$filter_category_id = '';
			}
			
			if (isset($this->request->get['filter_sub_category'])) {
				$filter_sub_category = $this->request->get['filter_sub_category'];
			} else {
				$filter_sub_category = '';
			}
			
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];	
			} else {
				$limit = 20;	
			}			
						
			$data = array(
				'filter_title'        => $filter_title,
				'filter_category_id'  => $filter_category_id,
				'filter_sub_category' => $filter_sub_category,
				'start'               => 0,
				'limit'               => $limit
			);
			
			$results = $this->model_extras_blog->getBlogs($data);
			
			foreach ($results as $result) {
					
				$json[] = array(
					'blog_id' => $result['blog_id'],
					'title'       => html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8')
				);	
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>