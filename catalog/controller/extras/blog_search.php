<?php 
class ControllerExtrasBlogSearch extends Controller { 	
	public function index() { 
    	$this->language->load('extras/blog_search');
		
		$this->load->model('extras/blog_category');
		
		$this->load->model('extras/blog');
		
		$this->load->model('tool/image'); 
		
		if (isset($this->request->get['filter_title'])) {
			$filter_title = $this->request->get['filter_title'];
		} else {
			$filter_title = '';
		} 
		
		if (isset($this->request->get['filter_tag'])) {
			$filter_tag = $this->request->get['filter_tag'];
		} elseif (isset($this->request->get['filter_title'])) {
			$filter_tag = $this->request->get['filter_title'];
		} else {
			$filter_tag = '';
		} 
				
		if (isset($this->request->get['filter_description'])) {
			$filter_description = $this->request->get['filter_description'];
		} else {
			$filter_description = '';
		} 
				
		if (isset($this->request->get['filter_blog_category_id'])) {
			$filter_blog_category_id = $this->request->get['filter_blog_category_id'];
		} else {
			$filter_blog_category_id = 0;
		} 
		
		if (isset($this->request->get['filter_sub_blog_category'])) {
			$filter_sub_blog_category = $this->request->get['filter_sub_blog_category'];
		} else {
			$filter_sub_blog_category = '';
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
		
		if (isset($this->request->get['filter_title'])) {
			$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->request->get['filter_title']);
		} else {
			$this->document->setTitle($this->language->get('heading_title'));
		}

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array( 
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
      		'separator' => false
   		);
		
		$url = '';
		
		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_tag'])) {
			$url .= '&filter_tag=' . urlencode(html_entity_decode($this->request->get['filter_tag'], ENT_QUOTES, 'UTF-8'));
		}
				
		if (isset($this->request->get['filter_description'])) {
			$url .= '&filter_description=' . $this->request->get['filter_description'];
		}
				
		if (isset($this->request->get['filter_blog_category_id'])) {
			$url .= '&filter_blog_category_id=' . $this->request->get['filter_blog_category_id'];
		}
		
		if (isset($this->request->get['filter_sub_blog_category'])) {
			$url .= '&filter_sub_blog_category=' . $this->request->get['filter_sub_blog_category'];
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
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extras/blog_search', $url),
      		'separator' => $this->language->get('text_separator')
   		);
		
		if (isset($this->request->get['filter_title'])) {
    		$this->data['heading_title'] = $this->language->get('heading_title') .  ' - ' . $this->request->get['filter_title'];
		} else {
			$this->data['heading_title'] = $this->language->get('heading_title');
		}

		$this->data['text_posted_on'] = $this->language->get('text_posted_on');
		$this->data['text_tags'] = $this->language->get('text_tags');
		$this->data['text_read_more'] = $this->language->get('text_read_more');
		$this->data['text_not_found'] = $this->language->get('text_not_found');
		
		$this->data['min_height'] = $this->config->get('blog_list_image_height');
		
		$this->load->model('extras/blog_category');
		
		// 3 Level Category Search
		$this->data['categories'] = array();
					
		$categories_1 = $this->model_extras_blog_category->getBlogCategories(0);
		
		foreach ($categories_1 as $category_1) {
			$level_2_data = array();
			
			$categories_2 = $this->model_extras_blog_category->getBlogCategories($category_1['blog_category_id']);
			
			foreach ($categories_2 as $category_2) {
				$level_3_data = array();
				
				$categories_3 = $this->model_extras_blog_category->getBlogCategories($category_2['blog_category_id']);
				
				foreach ($categories_3 as $category_3) {
					$level_3_data[] = array(
						'blog_category_id' => $category_3['blog_category_id'],
						'name'        => $category_3['name'],
					);
				}
				
				$level_2_data[] = array(
					'blog_category_id' => $category_2['blog_category_id'],	
					'name'        => $category_2['name'],
					'children'    => $level_3_data
				);					
			}
			
			$this->data['categories'][] = array(
				'blog_category_id' => $category_1['blog_category_id'],
				'name'        => $category_1['name'],
				'children'    => $level_2_data
			);
		}
		
		$this->data['blogs'] = array();
		
		if (isset($this->request->get['filter_title']) || isset($this->request->get['filter_tag'])) {
			$data = array(
				'filter_title'         => $filter_title, 
				'filter_tag'          => $filter_tag, 
				'filter_description'  => $filter_description,
				'filter_blog_category_id'  => $filter_blog_category_id, 
				'filter_sub_blog_category' => $filter_sub_blog_category, 
				'sort'                => $sort,
				'order'               => $order,
				'start'               => ($page - 1) * $limit,
				'limit'               => $limit
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
					//'rating'      => $result['rating'],
					//'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'        => $this->url->link('extras/blog/getblog', $url . '&blog_id=' . $result['blog_id'])
				);
			}
					
			$url = '';
			
			if (isset($this->request->get['filter_title'])) {
				$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . urlencode(html_entity_decode($this->request->get['filter_tag'], ENT_QUOTES, 'UTF-8'));
			}
					
			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			}
			
			if (isset($this->request->get['filter_blog_category_id'])) {
				$url .= '&filter_blog_category_id=' . $this->request->get['filter_blog_category_id'];
			}
			
			if (isset($this->request->get['filter_sub_blog_category'])) {
				$url .= '&filter_sub_blog_category=' . $this->request->get['filter_sub_blog_category'];
			}
					
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
						
			$this->data['sorts'] = array();
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'b.sort_order-ASC',
				'href'  => $this->url->link('extras/blog_search', 'sort=b.sort_order&order=ASC' . $url)
			);
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_title_asc'),
				'value' => 'bd.title-ASC',
				'href'  => $this->url->link('extras/blog_search', 'sort=bd.title&order=ASC' . $url)
			); 
	
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_title_desc'),
				'value' => 'bd.title-DESC',
				'href'  => $this->url->link('extras/blog_search', 'sort=bd.title&order=DESC' . $url)
			);
			
			if ($this->config->get('config_review_status')) {
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('extras/blog_search', 'sort=rating&order=DESC' . $url)
				); 
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('extras/blog_search', 'sort=rating&order=ASC' . $url)
				);
			}
	
			$url = '';
			
			if (isset($this->request->get['filter_title'])) {
				$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . urlencode(html_entity_decode($this->request->get['filter_tag'], ENT_QUOTES, 'UTF-8'));
			}
					
			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			}
			
			if (isset($this->request->get['filter_blog_category_id'])) {
				$url .= '&filter_blog_category_id=' . $this->request->get['filter_blog_category_id'];
			}
			
			if (isset($this->request->get['filter_sub_blog_category'])) {
				$url .= '&filter_sub_blog_category=' . $this->request->get['filter_sub_blog_category'];
			}
						
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
				'href'  => $this->url->link('extras/blog_search', $url . '&limit=' . $this->config->get('config_catalog_limit'))
			);
						
			$this->data['limits'][] = array(
				'text'  => 25,
				'value' => 25,
				'href'  => $this->url->link('extras/blog_search', $url . '&limit=25')
			);
			
			$this->data['limits'][] = array(
				'text'  => 50,
				'value' => 50,
				'href'  => $this->url->link('extras/blog_search', $url . '&limit=50')
			);
	
			$this->data['limits'][] = array(
				'text'  => 75,
				'value' => 75,
				'href'  => $this->url->link('extras/blog_search', $url . '&limit=75')
			);
			
			$this->data['limits'][] = array(
				'text'  => 100,
				'value' => 100,
				'href'  => $this->url->link('extras/blog_search', $url . '&limit=100')
			);
					
			$url = '';
	
			if (isset($this->request->get['filter_title'])) {
				$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . urlencode(html_entity_decode($this->request->get['filter_tag'], ENT_QUOTES, 'UTF-8'));
			}
					
			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			}
			
			if (isset($this->request->get['filter_blog_category_id'])) {
				$url .= '&filter_blog_category_id=' . $this->request->get['filter_blog_category_id'];
			}
			
			if (isset($this->request->get['filter_sub_blog_category'])) {
				$url .= '&filter_sub_blog_category=' . $this->request->get['filter_sub_blog_category'];
			}
										
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
			$pagination->url = $this->url->link('extras/blog_search', $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
		}	
		
		$this->data['filter_title'] = $filter_title;
		$this->data['filter_description'] = $filter_description;
		$this->data['filter_blog_category_id'] = $filter_blog_category_id;
		$this->data['filter_sub_blog_category'] = $filter_sub_blog_category;
				
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->data['limit'] = $limit;
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extras/blog_search.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/extras/blog_search.tpl';
		} else {
			$this->template = 'default/template/extras/blog_search.tpl';
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
?>