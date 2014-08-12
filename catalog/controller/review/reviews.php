<?php 
class ControllerReviewReviews extends Controller {
	private $error = array();
	  
  	public function index() {
    	$this->language->load('review/reviews');

		$this->load->model('review/reviews');
		
		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/jquery-1.7.1.min.js');
		
    	// Language
    	$this->data['heading_title'] = $this->language->get('heading_title');
	
		$this->data['text_write'] = $this->language->get('text_write');
		$this->data['text_note'] = $this->language->get('text_note');
		$this->data['text_videos'] = $this->language->get('text_videos');
		$this->data['text_images'] = $this->language->get('text_images');
		$this->data['text_products'] = $this->language->get('text_products');
		$this->data['text_review'] = $this->language->get('text_review');
		$this->data['text_tags'] = $this->language->get('text_tags');
		$this->data['text_wait'] = $this->language->get('text_wait');
		$this->data['text_review_intro'] = $this->language->get('text_review_intro');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_website'] = $this->language->get('entry_website');
		$this->data['entry_comment'] = $this->language->get('entry_comment');
		$this->data['entry_rating'] = $this->language->get('entry_rating');
		$this->data['entry_good'] = $this->language->get('entry_good');
		$this->data['entry_bad'] = $this->language->get('entry_bad');
		$this->data['entry_captcha'] = $this->language->get('entry_captcha');

		$this->data['button_review'] = $this->language->get('button_review');
		$this->data['button_cart'] = $this->language->get('button_cart');
  		$this->data['button_continue'] = $this->language->get('button_continue');
		
		// Breadcrumbs
		$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),       	
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_review'),
			'href'      => $this->url->link('review/reviews', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/review/reviews.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/review/reviews.tpl';
		} else {
			$this->template = 'default/template/review/reviews.tpl';
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


	public function getReviews() {
		$this->language->load('review/reviews');
		$this->load->model('review/reviews');

		// Language
		$this->data['text_on'] = $this->language->get('text_on');
      	$this->data['text_no_reviews'] = $this->language->get('text_no_reviews');	

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}  
		$limit = 20;
		
		$this->data['reviews'] = array();
				
		$comment_total = $this->model_review_reviews->getTotalReviews();
					
		$results = $this->model_review_reviews->getReviews(($page - 1) * 5, $limit);
      	
		foreach ($results as $result) {
        	$this->data['reviews'][] = array(
        		'author'     => $result['author'],
				'text'       => $result['text'],
				'website'       => $result['website'],
				'rating'     => (int)$result['rating'],
        		'comments'    => sprintf($this->language->get('text_comments'), (int)$comment_total),
        		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
			
		$pagination = new Pagination();
		$pagination->total = $comment_total;
		$pagination->page = $page;
		$pagination->limit = $limit; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('review/reviews', '&page={page}');
			
		$this->data['pagination'] = $pagination->render();

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/review/review_post.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/review/review_post.tpl';
		} else {
			$this->template = 'default/template/review/review_post.tpl';
		}

		$this->response->setOutput($this->render());
	}



	public function review() {
		$this->language->load('product/product');

		$this->load->model('catalog/review');

		$this->data['text_on'] = $this->language->get('text_on');
		$this->data['text_no_reviews'] = $this->language->get('text_no_reviews');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$this->data['reviews'] = array();

		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);

		$results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);

		foreach ($results as $result) {
			$this->data['reviews'][] = array(
				'author'     => $result['author'],
				'text'       => $result['text'],
				'rating'     => (int)$result['rating'],
				'reviews'    => sprintf($this->language->get('text_reviews'), (int)$review_total),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/review.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/review.tpl';
		} else {
			$this->template = 'default/template/product/review.tpl';
		}

		$this->response->setOutput($this->render());
	}

	public function write() {
		$this->language->load('review/reviews');
		
		$this->load->model('review/reviews');
		
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
				$this->model_review_reviews->addReview($this->request->post);
				
				$json['success'] = $this->language->get('text_success');
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}
}// end class