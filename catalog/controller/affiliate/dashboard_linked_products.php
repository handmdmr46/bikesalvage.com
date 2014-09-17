<?php
class ControllerAffiliateDashboardLinkedProducts extends Controller {
	/**
	* View all linked products
	*
	*/
	public function index() {
		$this->language->load('affiliate/dashboard_stock_control');
		$this->document->setTitle($this->language->get('heading_title_linked_products'));
		$this->load->model('affiliate/dashboard_stock_control');
		$this->init();
	}

	protected function init() {
		$affiliate_id = $this->affiliate->getId();
		$this->data['template_url'] = 'catalog/view/theme/' . $this->config->get('config_template');
		// Filter
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
	      $url .= '&page=' . $this->request->get['page'];
	    }

		// Breadcrumbs
	    $this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
       		'href'      => $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
       		'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_linked_products'),
       		'href'      => $this->url->link('affiliate/dashboard_linked_products', 'token=' . $this->session->data['token'] . $url, 'SSL'),
       		'separator' => ' :: '
		);

		//Language
		$this->data['heading_title']      = $this->language->get('heading_title_linked_products');
		$this->data['button_edit']        = $this->language->get('button_edit');
		$this->data['button_cancel']      = $this->language->get('button_cancel');
		$this->data['button_delete']      = $this->language->get('button_delete');
		$this->data['text_ebay_item_id']  = $this->language->get('text_ebay_item_id');
		$this->data['text_product_id']    = $this->language->get('text_product_id');
		$this->data['text_product_title'] = $this->language->get('text_product_title');
		$this->data['button_filter']      = $this->language->get('button_filter');
		$this->data['loading']            = $this->language->get('loading');

		// Error
	    if (isset($this->session->data['error'])) {
	      $this->data['error'] = $this->session->data['error'];
	      unset($this->session->data['error']);
	    } else {
	      $this->data['error'] = '';
	    }

	    // Success
	    if (isset($this->session->data['success'])) {
	      $this->data['success'] = $this->session->data['success'];
	      unset($this->session->data['success']);
	    } else {
	      $this->data['success'] = '';
	    }

	    // Page, Start & Limit -- pagination --
	    if (isset($this->request->get['page'])) {
	      $page = $this->request->get['page'];
	      $this->data['page'] = $this->request->get['page'];
	    } else {
	      $page = 1;
	      $this->data['page'] = 1;
	    }

	    $limit = 100;

	    $data = array(
			'filter_name'	  => $filter_name, 
			'start'           => ($page - 1) * $limit,
			'limit'           => $limit
		);

	    // Variables	    
	    $total                    		 = $this->model_affiliate_dashboard_stock_control->getTotalLinkedProducts($data, $affiliate_id);
	    $this->data['linked_products']   = $this->model_affiliate_dashboard_stock_control->getLinkedProducts($data, $affiliate_id);

	    // Buttons
	    $this->data['edit'] = $this->url->link('affiliate/dashboard_linked_products/edit', 'token=' . $this->session->data['token'] . $url, 'SSL');
	    $this->data['remove'] = $this->url->link('affiliate/dashboard_linked_products/remove', 'token=' . $this->session->data['token'] . $url, 'SSL');
	    $this->data['cancel'] = $this->url->link('common/home', 'token=' . $this->session->data['token'] . $url, 'SSL');

	    // Pagination
	    $pagination        = new Pagination();
	    $pagination->total = $total;
	    $pagination->page  = $page;
	    $pagination->limit = $limit;
	    $pagination->text  = $this->language->get('text_pagination');
	    $pagination->url   = $this->url->link('affiliate/dashboard_linked_products', 'token=' . $this->session->data['token'] . $url . '&page={page}' , 'SSL');

	    $this->data['pagination'] = $pagination->render();

	    $this->data['filter_name'] = $filter_name;

	    $this->template = $this->config->get('config_template') . '/template/affiliate/dashboard_linked_products.tpl';
		
		$this->children = array(
			'affiliate/common/header',
			'affiliate/common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function edit() {
		$this->language->load('affiliate/dashboard_stock_control');
		$this->document->setTitle($this->language->get('heading_title_linked_products'));
		$this->load->model('affiliate/dashboard_stock_control');
		$this->data['template_url'] = 'catalog/view/theme/' . $this->config->get('config_template');

	    $url = '';

	    if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

	    if (isset($this->request->get['page'])) {
	        $url .= '&page=' . $this->request->get['page'];
	    }

	    if (isset($this->request->post['selected'])) {
	      foreach ($this->request->post['selected'] as $product_id) {
	          
	          $ebay_item_id_str = $product_id . '_ebay_item_id';
	          $ebay_item_id     = $this->request->post[$ebay_item_id_str];
	          $this->model_affiliate_dashboard_stock_control->setLinkedProductEbayItemId($product_id, $ebay_item_id);
	      }

	      $this->session->data['success'] = $this->language->get('success_edit');
	      $this->redirect($this->url->link('affiliate/dashboard_linked_products', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	    }

	    $this->session->data['error'] = $this->language->get('error_edit');
	    $this->redirect($this->url->link('affiliate/dashboard_linked_products', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function remove() {
		$this->language->load('affiliate/dashboard_stock_control');
		$this->document->setTitle($this->language->get('heading_title_linked_products'));
		$this->load->model('affiliate/dashboard_stock_control');
		$this->data['template_url'] = 'catalog/view/theme/' . $this->config->get('config_template');

	    $url = '';

	    if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

	    if (isset($this->request->get['page'])) {
	        $url .= '&page=' . $this->request->get['page'];
	    }

	    if (isset($this->request->post['selected'])) {
	      foreach ($this->request->post['selected'] as $product_id) {	          
	      	$this->model_affiliate_dashboard_stock_control->removeProductLink($product_id);
	      }

	      $this->session->data['success'] = $this->language->get('success_remove');
	      $this->redirect($this->url->link('affiliate/dashboard_linked_products', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	    }

	    $this->session->data['error'] = $this->language->get('error_edit');
	    $this->redirect($this->url->link('affiliate/dashboard_linked_products', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}


}// end class