<?php
class ControllerExtrasBlog extends Controller { 
	private $error = array();

	public function index() {
		$this->load->language('extras/blog');

		$this->document->setTitle($this->language->get('heading_title'));
		 
		$this->load->model('extras/blog');
		$this->load->model('extras/blog_comment');

		$this->getList();
	}

	public function insert() {
		$this->load->language('extras/blog');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extras/blog');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extras_blog->addBlog($this->request->post);
			
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
			
			$this->redirect($this->url->link('extras/blog', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('extras/blog');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extras/blog');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extras_blog->editBlog($this->request->get['blog_id'], $this->request->post);
			
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
			
			$this->redirect($this->url->link('extras/blog', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}
 
	public function delete() {
		$this->load->language('extras/blog');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extras/blog');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $blog_id) {
				$this->model_extras_blog->deleteBlog($blog_id);
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
			
			$this->redirect($this->url->link('extras/blog', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'b.date_added';
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
			'href'      => $this->url->link('extras/blog', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('extras/blog/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('extras/blog/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$this->data['blogs'] = array();
		
		$this->data['tags'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		// $blog_total = 0;
		$blog_total = $this->model_extras_blog->getTotalBlogs();
		
		$results = array();
		$results = $this->model_extras_blog->getBlogs($data);

 
    	foreach ($results as $result) {
      		
			// Blog Post Tags [START]
			$tags = array();
			$tags = $this->model_extras_blog->getBlogListTags($result['blog_id']);
			// Blog Post Tags [END]

			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('extras/blog/update', 'token=' . $this->session->data['token'] . '&blog_id=' . $result['blog_id'] . $url, 'SSL')
			);
						
			$this->data['blogs'][] = array(
				'blog_id'                          => $result['blog_id'],
				'title'                            => $result['title'],
				'tags'                             => $tags,
				'total_comments'                   => $this->model_extras_blog_comment->getTotalBlogCommentsByBlogId($result['blog_id']),
				'total_comments_awaiting_approval' => $this->model_extras_blog_comment->getTotalBlogCommentsAwaitingApprovalByBlogId($result['blog_id']),
				'total_comments_approved'          => $this->model_extras_blog_comment->getTotalBlogCommentsApprovedByBlogId($result['blog_id']),
				'date_added'                       => date('Y/m/d \a\t h:i a', strtotime($result['date_added'])),
				'sort_order'                       => $result['sort_order'],
				'selected'                         => isset($this->request->post['selected']) && in_array($result['blog_id'], $this->request->post['selected']),
				'action'                           => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_tags'] = $this->language->get('column_tags');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_comment'] = $this->language->get('column_comment');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
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
		
		$this->data['sort_title'] = $this->url->link('extras/blog', 'token=' . $this->session->data['token'] . '&sort=bd.title' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('extras/blog', 'token=' . $this->session->data['token'] . '&sort=b.date_added' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('extras/blog', 'token=' . $this->session->data['token'] . '&sort=b.sort_order' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $blog_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('extras/blog', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'extras/blog_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
		
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$this->data['entry_tag'] = $this->language->get('entry_tag');
		$this->data['entry_store'] = $this->language->get('entry_store');
    	$this->data['entry_image'] = $this->language->get('entry_image');
    	$this->data['entry_video_link'] = $this->language->get('entry_video_link');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_bottom'] = $this->language->get('entry_bottom');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
    	$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_related'] = $this->language->get('entry_related');
		$this->data['entry_product_related'] = $this->language->get('entry_product_related');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_image'] = $this->language->get('button_add_image');
		$this->data['button_add_video_link'] = $this->language->get('button_add_video_link');
		$this->data['button_remove'] = $this->language->get('button_remove');
    	
		$this->data['tab_general'] = $this->language->get('tab_general');
    	$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_links'] = $this->language->get('tab_links');
    	$this->data['tab_image'] = $this->language->get('tab_image');		
    	$this->data['tab_video'] = $this->language->get('tab_video');		
		$this->data['tab_design'] = $this->language->get('tab_design');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = array();
		}
		
	 	if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = array();
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
			'href'      => $this->url->link('extras/blog', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		if (!isset($this->request->get['blog_id'])) {
			$this->data['action'] = $this->url->link('extras/blog/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('extras/blog/update', 'token=' . $this->session->data['token'] . '&blog_id=' . $this->request->get['blog_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('extras/blog', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['blog_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$blog_info = $this->model_extras_blog->getBlog($this->request->get['blog_id']);
		}
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['blog_description'])) {
			$this->data['blog_description'] = $this->request->post['blog_description'];
		} elseif (isset($this->request->get['blog_id'])) {
			$this->data['blog_description'] = $this->model_extras_blog->getBlogDescriptions($this->request->get['blog_id']);
		} else {
			$this->data['blog_description'] = array();
		}
		
		if (isset($this->request->post['blog_tag'])) {
			$this->data['blog_tag'] = $this->request->post['blog_tag'];
		} elseif (isset($this->request->get['blog_id'])) {
			$this->data['blog_tag'] = $this->model_extras_blog->getBlogTags($this->request->get['blog_id']);
		} else {
			$this->data['blog_tag'] = array();
		}

		$this->load->model('setting/store');
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['blog_store'])) {
			$this->data['blog_store'] = $this->request->post['blog_store'];
		} elseif (isset($this->request->get['blog_id'])) {
			$this->data['blog_store'] = $this->model_extras_blog->getBlogStores($this->request->get['blog_id']);
		} else {
			$this->data['blog_store'] = array(0);
		}		
		
		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (!empty($blog_info)) {
			$this->data['image'] = $blog_info['image'];
		} else {
			$this->data['image'] = '';
		}
		
		$this->load->model('tool/image');
		
		if (isset($this->request->post['image']) && file_exists(DIR_IMAGE . $this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($blog_info) && $blog_info['image'] && file_exists(DIR_IMAGE . $blog_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($blog_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($blog_info)) {
			$this->data['keyword'] = $blog_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		
		if (isset($this->request->post['bottom'])) {
			$this->data['bottom'] = $this->request->post['bottoms'];
		} elseif (!empty($blog_info)) {
			$this->data['bottom'] = $blog_info['bottom'];
		} else {
			$this->data['bottom'] = 0;
		}
		
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($blog_info)) {
			$this->data['status'] = $blog_info['status'];
		} else {
			$this->data['status'] = 1;
		}
				
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($blog_info)) {
			$this->data['sort_order'] = $blog_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}
		
		// Blog Category [START]
		
		$this->load->model('extras/blog_category');
				
		$this->data['categories'] = $this->model_extras_blog_category->getBlogCategories(0);
		
		if (isset($this->request->post['blog_category'])) {
			$this->data['blog_category'] = $this->request->post['blog_category'];
		} elseif (isset($this->request->get['blog_id'])) {
			$this->data['blog_category'] = $this->model_extras_blog->getBlogCategories($this->request->get['blog_id']);
		} else {
			$this->data['blog_category'] = array();
		}		
		
		// Blog Category [END]
		
		// Related Blog Post [START]
		$this->load->model('catalog/product');
		
		if (isset($this->request->post['blog_related'])) {
			$blogs = $this->request->post['blog_related'];
		} elseif (isset($this->request->get['blog_id'])) {		
			$blogs = $this->model_extras_blog->getBlogRelated($this->request->get['blog_id']);
		} else {
			$blogs = array();
		}
	
		$this->data['blog_related'] = array();
		
		foreach ($blogs as $blog_id) {
			$related_info = $this->model_extras_blog->getBlog($blog_id);
			
			if ($related_info) {
				$this->data['blog_related'][] = array(
					'blog_id' => $related_info['blog_id'],
					'title'       => $related_info['title']
				);
			}
		}
		
		// Related Blog Post [END]
		
		// Related Product [START]
		
		if (isset($this->request->post['blog_product_related'])) {
			$blog_products = $this->request->post['blog_product_related'];
		} elseif (isset($this->request->get['blog_id'])) {		
			$blog_products = $this->model_extras_blog->getBlogProductRelated($this->request->get['blog_id']);
		} else {
			$blog_products = array();
		}
	
		$this->data['blog_product_related'] = array();
		
		foreach ($blog_products as $product_id) {
			$related_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($related_info) {
				$this->data['blog_product_related'][] = array(
					'product_id' => $related_info['product_id'],
					'name'       => $related_info['name']
				);
			}
		}
		// Related Product [END]
		
		if (isset($this->request->post['blog_layout'])) {
			$this->data['blog_layout'] = $this->request->post['blog_layout'];
		} elseif (isset($this->request->get['blog_id'])) {
			$this->data['blog_layout'] = $this->model_extras_blog->getBlogLayouts($this->request->get['blog_id']);
		} else {
			$this->data['blog_layout'] = array();
		}
		
		if (isset($this->request->post['blog_image'])) {
			$blog_images = $this->request->post['blog_image'];
		} elseif (isset($this->request->get['blog_id'])) {
			$blog_images = $this->model_extras_blog->getBlogImages($this->request->get['blog_id']);
		} else {
			$blog_images = array();
		}
		
		$this->data['blog_images'] = array();
		
		foreach ($blog_images as $blog_image) {
			if ($blog_image['image'] && file_exists(DIR_IMAGE . $blog_image['image'])) {
				$image = $blog_image['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
			$this->data['blog_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize($image, 100, 100),
				'sort_order' => $blog_image['sort_order']
			);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		if (isset($this->request->post['blog_video'])) {
			$blog_videos = $this->request->post['blog_video'];
		} elseif (isset($this->request->get['blog_id'])) {
			$blog_videos = $this->model_extras_blog->getBlogVideos($this->request->get['blog_id']);
		} else {
			$blog_videos = array();
		}
		
		$this->data['blog_videos'] = array();
		
		foreach ($blog_videos as $blog_video) {
			if ($blog_video['video']) {
				$video = $blog_video['video'];
			} else {
				$video = '';
			}
			
			$this->data['blog_videos'][] = array(
				'video'      => $video,
				'sort_order' => $blog_video['sort_order']
			);
		}

		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
				
		$this->template = 'extras/blog_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'extras/blog')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['blog_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 64)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		
			if (utf8_strlen($value['description']) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}
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

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extras/blog')) {
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
		
	public function productautocomplete() {
		$json = array();
		
		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model']) || isset($this->request->get['filter_category_id'])) {
			$this->load->model('catalog/product');
			
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}
			
			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
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
				'filter_name'         => $filter_name,
				'filter_model'        => $filter_model,
				'filter_category_id'  => $filter_category_id,
				'filter_sub_category' => $filter_sub_category,
				'start'               => 0,
				'limit'               => $limit
			);
			
			$results = $this->model_catalog_product->getProducts($data);
			
			foreach ($results as $result) {
				$option_data = array();
				
				$product_options = $this->model_catalog_product->getProductOptions($result['product_id']);	
				
				foreach ($product_options as $product_option) {
					if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
						$option_value_data = array();
					
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$option_value_data[] = array(
								'product_option_value_id' => $product_option_value['product_option_value_id'],
								'option_value_id'         => $product_option_value['option_value_id'],
								'name'                    => $product_option_value['name'],
								'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
								'price_prefix'            => $product_option_value['price_prefix']
							);	
						}
					
						$option_data[] = array(
							'product_option_id' => $product_option['product_option_id'],
							'option_id'         => $product_option['option_id'],
							'name'              => $product_option['name'],
							'type'              => $product_option['type'],
							'option_value'      => $option_value_data,
							'required'          => $product_option['required']
						);	
					} else {
						$option_data[] = array(
							'product_option_id' => $product_option['product_option_id'],
							'option_id'         => $product_option['option_id'],
							'name'              => $product_option['name'],
							'type'              => $product_option['type'],
							'option_value'      => $product_option['option_value'],
							'required'          => $product_option['required']
						);				
					}
				}
					
				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'),	
					'model'      => $result['model'],
					'option'     => $option_data,
					'price'      => $result['price']
				);	
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>