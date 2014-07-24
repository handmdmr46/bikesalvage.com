<?php 
class ControllerExtrasBlog extends Controller {
	private $error = array();
	  
  	public function index() {
    	$this->language->load('extras/blog');

		$this->load->model('extras/blog');
		
		$this->load->model('tool/image');
		
		$this->getBlogs();
  	}

  	private function getBlogs() {

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_blog'),
			'href'      => $this->url->link('extras/blog', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
		$this->document->setTitle($this->language->get('heading_title'));
			
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_posted_on'] = $this->language->get('text_posted_on');
    	$this->data['text_tags'] = $this->language->get('text_tags');
    	$this->data['text_read_more'] = $this->language->get('text_read_more');
    	$this->data['text_not_found'] = $this->language->get('text_not_found');
    	$this->data['button_comment'] = $this->language->get('button_comment');
		
		$this->data['min_height'] = $this->config->get('blog_list_image_height');

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
		
    	$this->data['blogs'] = array();
		
		$data = array(
			'sort'                => 'b.date_added',
			'order'               => 'DESC',
			'start'               => 0,
			'limit'               => 20 // $this->config->get('config_catalog_limit')
		);
		
		$this->data['tags'] = array();
		
		$results = $this->model_extras_blog->getBlogs($data);

    	foreach ($results as $result) {
      		
			// Blog Post Tags [START]
			$tags = array();
			$tags = $this->model_extras_blog->getBlogTags($result['blog_id']);
			// Blog Post Tags [END]
			
			// Blog Image [START]
			if (!empty($result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], $this->config->get('blog_list_image_width'), $this->config->get('blog_list_image_height'));
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', $this->config->get('blog_list_image_width'), $this->config->get('blog_list_image_height'));
			}
			// Blog Image [END]
			
			$this->data['blogs'][] = array(
        		'blog_id' => $result['blog_id'],
        		'title' => $result['title'],
				'tags' => $tags,
				'tag_href' => $this->url->link('extras/blog_search'),
				'date_added' => date("M. d, Y", strtotime($result['date_added'])),
				'image'   		=> $image,
				//'description' => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
				'description' => mb_substr(str_replace('&nbsp;',' ',strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('blog_list_max_chars')) . '..',
				'href' => $this->url->link('extras/blog/getblog', 'blog_id=' . $result['blog_id'])
      		);
    	}

    	//$this->data['insert'] = $this->url->link('account/address/insert', '', 'SSL');
		//$this->data['back'] = $this->url->link('account/account', '', 'SSL');
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extras/blog_list.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/extras/blog_list.tpl';
		} else {
			$this->template = 'default/template/extras/blog_list.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'		
		);
						
		$this->response->setOutput($this->render());		
  	}

  	public function getBlog() {
    	$this->language->load('extras/blog');
		$this->load->model('extras/blog');
		
		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/magic.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/magic.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/magic.css');
		}

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),       	
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_blog'),
			'href'      => $this->url->link('extras/blog', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
		if (isset($this->request->get['blog_id'])) {
			$blog_id = $this->request->get['blog_id'];
		} else {
			$blog_id = 0;
		}
		
		$blog_info = $this->model_extras_blog->getBlog($blog_id);
   		
		if ($blog_info) {
			$this->load->model('extras/blog_category');
			
			if (isset($this->request->get['blogpath'])) {
				$path = '';
			
				$parts = explode('_', (string)$this->request->get['blogpath']);
			
				foreach ($parts as $path_id) {
					if (!$path) {
						$path = $path_id;
					} else {
						$path .= '_' . $path_id;
					}
										
					$blog_category_info = $this->model_extras_blog_category->getBlogCategory($path_id);
					
					if ($blog_category_info) {
						$this->data['breadcrumbs'][] = array(
							'text'      => $blog_category_info['name'],
							'href'      => $this->url->link('extras/blog_category', 'blogpath=' . $path),
							'separator' => $this->language->get('text_separator')
						);
					}
				}		
			
				$blog_category_id = array_pop($parts);
			} else {
				$blog_category_id = 0;
			}

	  		$this->document->setTitle($blog_info['title']); 

      		$this->data['breadcrumbs'][] = array(
        		'text'      => $blog_info['title'],
				'href'      => $this->url->link('extras/blog/getblog', 'blog_id=' .  $blog_id),      		
        		'separator' => $this->language->get('text_separator')
      		);		
						
      		$this->data['heading_title'] = $blog_info['title'];

			$this->data['text_write'] = $this->language->get('text_write');
			$this->data['text_note'] = $this->language->get('text_note');
			$this->data['text_videos'] = $this->language->get('text_videos');
			$this->data['text_images'] = $this->language->get('text_images');
			$this->data['text_products'] = $this->language->get('text_products');
			$this->data['text_related_blogs'] = $this->language->get('text_related_blogs');
			$this->data['text_tags'] = $this->language->get('text_tags');
			$this->data['text_wait'] = $this->language->get('text_wait');

			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_email'] = $this->language->get('entry_email');
			$this->data['entry_website'] = $this->language->get('entry_website');
			$this->data['entry_comment'] = $this->language->get('entry_comment');
			$this->data['entry_rating'] = $this->language->get('entry_rating');
			$this->data['entry_good'] = $this->language->get('entry_good');
			$this->data['entry_bad'] = $this->language->get('entry_bad');
			$this->data['entry_captcha'] = $this->language->get('entry_captcha');
			$this->data['button_comment'] = $this->language->get('button_comment');

			$this->data['button_cart'] = $this->language->get('button_cart');
      		
      		$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->data['description'] = html_entity_decode($blog_info['description'], ENT_QUOTES, 'UTF-8');
      		
			$this->data['continue'] = $this->url->link('common/home');

			$this->data['blog_id'] = $this->request->get['blog_id'];
			
			$this->data['videos'] = array();
			
			$results = $this->model_extras_blog->getBlogVideos($this->request->get['blog_id']);
			
			foreach ($results as $result) {
				$this->data['videos'][] = array(
					'link' => $result['video']
				);
			}	

			$this->load->model('tool/image');
			
			$this->data['images'] = array();
			
			$results = $this->model_extras_blog->getBlogImages($this->request->get['blog_id']);
			
			foreach ($results as $result) {
				$this->data['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
					'thumb' => $this->model_tool_image->resize($result['image'], 100, 100)
				);
			}
			
			// Product Related START]
			
			$this->data['products'] = array();
			
			$results = $this->model_extras_blog->getBlogProductRelated($this->request->get['blog_id']);
			
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				} else {
					$image = false;
				}
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
						
				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}
				
				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
							
				$this->data['products'][] = array(
					'product_id' => $result['product_id'],
					'thumb'   	 => $image,
					'name'    	 => $result['name'],
					'price'   	 => $price,
					'special' 	 => $special,
					'rating'     => $rating,
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
				);
			}	
			// Product Related END]	
			
			// Blog Related [START]
			
			$this->data['blogs'] = array();
			
			$results = $this->model_extras_blog->getBlogRelated($this->request->get['blog_id']);
			
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				} else {
					$image = false;
				}
				
				//if ($this->config->get('config_review_status')) {
				//	$rating = (int)$result['rating'];
				//} else {
				//	$rating = false;
				//}
							
				$this->data['blogs'][] = array(
					'blog_id' => $result['blog_id'],
					'thumb'   	 => $image,
					'title'    	 => $result['title'],
					//'rating'     => $rating,
					//'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'    	 => $this->url->link('extras/blog/getblog', 'blog_id=' . $result['blog_id']),
				);
			}	
			// Blog related [END]
			
			$this->data['tags'] = array();
					
			$results = $this->model_extras_blog->getBlogTags($this->request->get['blog_id']);
			
			foreach ($results as $result) {
				$this->data['tags'][] = array(
					'tag'  => $result['tag'],
					'href' => $this->url->link('extras/blog_search', 'filter_tag=' . $result['tag'])
				);
			}
			
			$this->model_extras_blog->updateViewed($this->request->get['blog_id']);

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extras/blog.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/extras/blog.tpl';
			} else {
				$this->template = 'default/template/extras/blog.tpl';
			}
			
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
						
	  		$this->response->setOutput($this->render());
    	} else {
      		$this->data['breadcrumbs'][] = array(
        		'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('extras/blog/getblog', 'blog_id=' . $blog_id),
        		'separator' => $this->language->get('text_separator')
      		);
				
	  		$this->document->setTitle($this->language->get('text_error'));
			
      		$this->data['heading_title'] = $this->language->get('text_error');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}
			
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
					
	  		$this->response->setOutput($this->render());
    	}			

  	}
	
	public function comment() {
    	$this->language->load('extras/blog');
		
		$this->load->model('extras/blog');

		$this->data['text_on'] = $this->language->get('text_on');
		$this->data['text_no_comments'] = $this->language->get('text_no_comments');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}  
		
		$this->data['comments'] = array();
		
		$comment_total = $this->model_extras_blog->getTotalCommentsByBlogId($this->request->get['blog_id']);
			
		$results = $this->model_extras_blog->getCommentsByBlogId($this->request->get['blog_id'], ($page - 1) * 5, 5);
      		
		foreach ($results as $result) {
        	$this->data['comments'][] = array(
        		'author'     => $result['author'],
				'text'       => $result['text'],
				'website'       => $result['website'],
				'rating'     => (int)$result['rating'],
        		'comments'    => sprintf($this->language->get('text_comments'), (int)$comment_total),
        		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
		
		if (isset($this->request->get['blog_id'])) {
			$blog_id = $this->request->get['blog_id'];
		} else {
			$blog_id = 0;
		}

		$blog_info = $this->model_extras_blog->getBlog($blog_id);

		$this->data['responses'] = sprintf($this->language->get('text_responses'), $comment_total, $blog_info['title']);
			
		$pagination = new Pagination();
		$pagination->total = $comment_total;
		$pagination->page = $page;
		$pagination->limit = 5; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('extras/blog/comment', 'blog_id=' . $this->request->get['blog_id'] . '&page={page}');
			
		$this->data['pagination'] = $pagination->render();
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extras/blog_comment.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/extras/blog_comment.tpl';
		} else {
			$this->template = 'default/template/extras/blog_comment.tpl';
		}
		
		$this->response->setOutput($this->render());
	}

	public function write() {
		$this->language->load('extras/blog');
		
		$this->load->model('extras/blog');
		
		if (isset($this->request->get['blog_id'])) {
			$blog_id = $this->request->get['blog_id'];
		} else {
			$blog_id = 0;
		}
		
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}
	
	    	if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
				$json['error'] = $this->language->get('error_email');
			}
			
			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}
	
			if (empty($this->request->post['rating'])) {
				$json['error'] = $this->language->get('error_rating');
			}
	
			if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
				$json['error'] = $this->language->get('error_captcha');
			}
				
			if (!isset($json['error'])) {
				$this->model_extras_blog->addComment($blog_id, $this->request->post);
				
				$json['success'] = $this->language->get('text_success');
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function captcha() {
		$this->load->library('captcha');
		
		$captcha = new Captcha();
		
		$this->session->data['captcha'] = $captcha->getCode();
		
		$captcha->showImage();
	}
}
?>