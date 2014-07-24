<?php 
class ControllerExtrasBlogCategory extends Controller {  
	public function index() { 
		$this->language->load('extras/blog_category');
		
		$this->load->model('extras/blog_category');
		
		$this->load->model('extras/blog');
		
		$this->load->model('tool/image'); 
		
		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/magic.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/magic.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/magic.css');
		}
		
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
							
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_catalog_limit');
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
		
		$blog_category_info = $this->model_extras_blog_category->getBlogCategory($blog_category_id);
	
		if ($blog_category_info) {
	  		$this->document->setTitle($blog_category_info['name']);
			$this->document->setDescription($blog_category_info['meta_description']);
			$this->document->setKeywords($blog_category_info['meta_keyword']);
				
			$this->data['heading_title'] = $blog_category_info['name'];
	
			$this->data['text_posted_on'] = $this->language->get('text_posted_on');
			$this->data['text_tags'] = $this->language->get('text_tags');
			$this->data['text_read_more'] = $this->language->get('text_read_more');
			$this->data['text_not_found'] = $this->language->get('text_not_found');
		
			$this->data['min_height'] = $this->config->get('blog_list_image_height');
					
			if ($blog_category_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($blog_category_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
			} else {
				$this->data['thumb'] = '';
			}
									
			$this->data['description'] = html_entity_decode($blog_category_info['description'], ENT_QUOTES, 'UTF-8');
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}	
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
								
			$this->data['blog_categories'] = array();
			
			$results = $this->model_extras_blog_category->getBlogCategories($blog_category_id);
			
			foreach ($results as $result) {
				$data = array(
					'filter_blog_category_id'  => $result['blog_category_id'],
					'filter_sub_blog_category' => true	
				);
							
				$blog_total = $this->model_extras_blog->getTotalBlogs($data);
				
				$this->data['blog_categories'][] = array(
					'name'  => $result['name'] . ' (' . $blog_total . ')',
					'href'  => $this->url->link('extras/blog_category', 'blogpath=' . $this->request->get['blogpath'] . '_' . $result['blog_category_id'] . $url)
				);
			}
			
			$this->data['blogs'] = array();
			
			$data = array(
				'filter_blog_category_id' => $blog_category_id, 
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);
					
			$blog_total = $this->model_extras_blog->getTotalBlogs($data); 
			
			$results = $this->model_extras_blog->getBlogs($data);
			
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('blog_list_image_width'), $this->config->get('blog_list_image_height'));
				} else {
					$image = $this->model_tool_image->resize('no_image.jpg', $this->config->get('blog_list_image_width'), $this->config->get('blog_list_image_height'));
				}
				
				// Blog Post Tags [START]
				$tags = array();
				$tags = $this->model_extras_blog->getBlogTags($result['blog_id']);
				// Blog Post Tags [END]
				
				//if ($this->config->get('config_review_status')) {
				//	$rating = (int)$result['rating'];
				//} else {
				//	$rating = false;
				//}
								
				$this->data['blogs'][] = array(
					'blog_id'  => $result['blog_id'],
					'thumb'       => $image,
					'title'        => $result['title'],
					'description' => mb_substr(str_replace('&nbsp;',' ',strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('blog_list_max_chars')) . '..',
					'date_added' => date("M. d, Y", strtotime($result['date_added'])),
					'image'   		=> $image,
					'tags' => $tags,
					'tag_href' => $this->url->link('extras/blog_search'),
				//	'rating'      => $result['rating'],
				//	'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'        => $this->url->link('extras/blog/getblog', 'blogpath=' . $this->request->get['blogpath'] . '&blog_id=' . $result['blog_id'])
				);
			}
			
			$url = '';
	
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
							
			$this->data['sorts'] = array();
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'b.sort_order-ASC',
				'href'  => $this->url->link('extras/blog_category', 'blogpath=' . $this->request->get['blogpath'] . '&sort=b.sort_order&order=ASC' . $url)
			);
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_title_asc'),
				'value' => 'bd.title-ASC',
				'href'  => $this->url->link('extras/blog_category', 'blogpath=' . $this->request->get['blogpath'] . '&sort=bd.title&order=ASC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_title_desc'),
				'value' => 'bd.title-DESC',
				'href'  => $this->url->link('extras/blog_category', 'blogpath=' . $this->request->get['blogpath'] . '&sort=bd.title&order=DESC' . $url)
			);
			
			if ($this->config->get('config_review_status')) {
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('extras/blog_category', 'blogpath=' . $this->request->get['blogpath'] . '&sort=rating&order=DESC' . $url)
				); 
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('extras/blog_category', 'blogpath=' . $this->request->get['blogpath'] . '&sort=rating&order=ASC' . $url)
				);
			}
			
			$url = '';
	
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->data['limits'] = array();
			
			$this->data['limits'][] = array(
				'text'  => $this->config->get('config_catalog_limit'),
				'value' => $this->config->get('config_catalog_limit'),
				'href'  => $this->url->link('extras/blog_category', 'blogpath=' . $this->request->get['blogpath'] . $url . '&limit=' . $this->config->get('config_catalog_limit'))
			);
						
			$this->data['limits'][] = array(
				'text'  => 25,
				'value' => 25,
				'href'  => $this->url->link('extras/blog_category', 'blogpath=' . $this->request->get['blogpath'] . $url . '&limit=25')
			);
			
			$this->data['limits'][] = array(
				'text'  => 50,
				'value' => 50,
				'href'  => $this->url->link('extras/blog_category', 'blogpath=' . $this->request->get['blogpath'] . $url . '&limit=50')
			);

			$this->data['limits'][] = array(
				'text'  => 75,
				'value' => 75,
				'href'  => $this->url->link('extras/blog_category', 'blogpath=' . $this->request->get['blogpath'] . $url . '&limit=75')
			);
			
			$this->data['limits'][] = array(
				'text'  => 100,
				'value' => 100,
				'href'  => $this->url->link('extras/blog_category', 'blogpath=' . $this->request->get['blogpath'] . $url . '&limit=100')
			);
						
			$url = '';
	
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
	
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
					
			$pagination = new Pagination();
			$pagination->total = $blog_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('extras/blog_category', 'blogpath=' . $this->request->get['blogpath'] . $url . '&page={page}');
		
			$this->data['pagination'] = $pagination->render();
		
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			$this->data['limit'] = $limit;
		
			$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extras/blog_category.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/extras/blog_category.tpl';
			} else {
				$this->template = 'default/template/extras/blog_category.tpl';
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
			$url = '';
			
			if (isset($this->request->get['blogpath'])) {
				$url .= '&blogpath=' . $this->request->get['blogpath'];
			}
									
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
				
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
						
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('extras/blog_category', $url),
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
}
?>