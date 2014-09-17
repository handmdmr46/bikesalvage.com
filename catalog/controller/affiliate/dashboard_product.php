<?php
class ControllerAffiliateDashboardProduct extends Controller {
	private $error = array(); 
	
	public function index() {
		if (!$this->affiliate->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('affiliate/dashboard_product', '', 'SSL');
			$this->redirect($this->url->link('affiliate/login', '', 'SSL'));
		}

		$this->data['template_url'] = 'catalog/view/theme/' . $this->config->get('config_template');
		
		$this->language->load('affiliate/dashboard');
		
		$this->load->model('affiliate/dashboard');
		$this->document->setTitle($this->language->get('heading_title_product'));
    	$this->data['heading_title_product'] = $this->language->get('heading_title_product');
		
		$this->getProducts();		
	}
	
	protected function getProducts() {
		// filters
       if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}
		
		if (isset($this->request->get['filter_price'])) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = null;
		}

		if (isset($this->request->get['filter_quantity'])) {
			$filter_quantity = $this->request->get['filter_quantity'];
		} else {
			$filter_quantity = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
						
		$url = '';
						
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}
		
		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}		

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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
		
		//breadcrumbs
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_product'),
			'href'      => $this->url->link('affiliate/dashboard_product', 'token=' . $this->session->data['token'] . $url, 'SSL'),       		
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = $this->url->link('affiliate/dashboard_upload', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('affiliate/dashboard_product/deleteProduct', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$this->data['products'] = array();
		
		$data = array(
			  'filter_name'	  => $filter_name, 
			  'filter_model'	  => $filter_model,
			  'filter_price'	  => $filter_price,
			  'filter_quantity' => $filter_quantity,
			  'filter_status'   => $filter_status,
			  'sort'            => $sort,
			  'order'           => $order,
			  'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			  'limit'           => $this->config->get('config_admin_limit')
		);
		
		$this->load->model('affiliate/dashboard');
		
		$this->load->model('tool/image');

		$product_total = $this->model_affiliate_dashboard->getTotalProducts($data);
			
		$results = $this->model_affiliate_dashboard->getProducts($data);
		
		foreach ($results as $result) {
			
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				//'href' => $this->url->link('affiliate/dashboard_upload/addProduct', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
				'href' => $this->url->link('affiliate/dashboard_upload', 'token=' . $this->session->data['token']. '&product_id=' . $result['product_id'] . $url, 'SSL')
			);
			
			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
			}

      		$this->data['products'][] = array(
				'product_id' => $result['product_id'],
				'name'       => $result['name'],
				'model'      => $result['model'],
				'price'      => $result['price'],
				'image'      => $image,
				'quantity'   => $result['quantity'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
				'action'     => $action
			);
		
		}
		//language
		$this->data['text_enabled'] = $this->language->get('text_enabled');		
		$this->data['text_disabled'] = $this->language->get('text_disabled');		
		$this->data['text_no_results'] = $this->language->get('text_no_results');		
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');		
			
		$this->data['column_image'] = $this->language->get('column_image');		
		$this->data['column_name'] = $this->language->get('column_name');		
		$this->data['column_model'] = $this->language->get('column_model');		
		$this->data['column_price'] = $this->language->get('column_price');		
		$this->data['column_quantity'] = $this->language->get('column_quantity');		
		$this->data['column_status'] = $this->language->get('column_status');		
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_copy'] = $this->language->get('button_copy');		
		$this->data['button_insert'] = $this->language->get('button_insert');		
		$this->data['button_delete'] = $this->language->get('button_delete');		
		$this->data['button_filter'] = $this->language->get('button_filter');
		 
 		$this->data['token'] = $this->session->data['token'];
		
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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}
		
		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
								
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
					
		$this->data['sort_name'] = $this->url->link('affiliate/dashboard_product', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$this->data['sort_model'] = $this->url->link('affiliate/dashboard_product', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
		$this->data['sort_price'] = $this->url->link('affiliate/dashboard_product', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
		$this->data['sort_quantity'] = $this->url->link('affiliate/dashboard_product', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('affiliate/dashboard_product', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
		$this->data['sort_order'] = $this->url->link('affiliate/dashboard_product', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}
		
		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('affiliate/dashboard_product', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
	
		$this->data['filter_name'] = $filter_name;
		$this->data['filter_model'] = $filter_model;
		$this->data['filter_price'] = $filter_price;
		$this->data['filter_quantity'] = $filter_quantity;
		$this->data['filter_status'] = $filter_status;
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		
		$this->template = $this->config->get('config_template') . '/template/affiliate/dashboard_product.tpl';
		
		$this->children = array(
			'affiliate/common/header',
			'affiliate/common/footer'
		);

		
		$this->response->setOutput($this->render());
	}
	
	public function addProduct() {
		$this->language->load('affiliate/dashboard');
    	
		$this->document->setTitle($this->language->get('heading_title_product')); 
		
		$this->load->model('affiliate/dashboard');
		
		//$this->data['test'] = $this->model_affiliate_dashboard->getManufacturers($data);
		
		// Language - $this->index() defaults
		$this->data['title'] = $this->language->get('heading_title_product');
    	$this->data['heading_title_product'] = $this->language->get('heading_title_product');
		$this->data['styles'] = $this->document->getStyles();
		$this->data['scripts'] = $this->document->getScripts();
		$this->data['first_name'] = $this->affiliate->getFirstName();
		$this->data['last_name'] = $this->affiliate->getLastName();
		$this->data['text_welcome'] = $this->language->get('text_welcome');
		$this->data['text_logout'] = $this->language->get('text_logout');
		$this->data['text_homepage'] = $this->language->get('text_homepage');
		$this->data['text_products'] = $this->language->get('text_products');
		$this->data['text_orders'] = $this->language->get('text_orders');
		$this->data['text_profile_edit'] = $this->language->get('text_profile_edit');
		$this->data['text_sales'] = $this->language->get('text_sales');
		$this->data['text_product_upload'] = $this->language->get('text_product_upload');
		$this->data['text_product_edit'] = $this->language->get('text_product_edit');
		$this->data['text_product_import'] = $this->language->get('text_product_import');
		$this->data['text_dashboard_header'] = $this->language->get('text_dashboard_header');
		$this->data['text_dashboard'] = $this->language->get('text_dashboard');
		$this->data['text_catalog'] = $this->language->get('text_catalog');
		$this->data['text_view_product'] = $this->language->get('text_view_product');
		$this->data['text_edit_product'] = $this->language->get('text_edit_product');
		$this->data['text_upload_product'] = $this->language->get('text_upload_product');
		
		// HTTP:HTTPS decision maker - $this->index() defaults
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
			$this->data['store'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
			$this->data['store'] = HTTP_SERVER;
		}
		
		// Main Menu Links - $this->index() defaults
		$this->data['logout'] = $this->url->link('affiliate/logout', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['affiliate_dashboard'] = $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['view_product'] = $this->url->link('affiliate/dashboard_product', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['edit_product'] = $this->url->link('affiliate/dashboard_product', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['upload_product'] = $this->url->link('affiliate/dashboard_product', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['dashboard_sale'] = $this->url->link('affiliate/dashboard_sale', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['dashboard_profile'] = $this->url->link('affiliate/dashboard_profile', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['dashboard_import'] = $this->url->link('affiliate/dashboard_import', 'token=' . $this->session->data['token'], 'SSL');
		
		// Language 
    	$this->data['heading_title'] = $this->language->get('heading_title');
 
    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
    	$this->data['text_none'] = $this->language->get('text_none');
    	$this->data['text_yes'] = $this->language->get('text_yes');
    	$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_plus'] = $this->language->get('text_plus');
		$this->data['text_minus'] = $this->language->get('text_minus');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		$this->data['text_option'] = $this->language->get('text_option');
		$this->data['text_option_value'] = $this->language->get('text_option_value');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_percent'] = $this->language->get('text_percent');
		$this->data['text_amount'] = $this->language->get('text_amount');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
    	$this->data['entry_model'] = $this->language->get('entry_model');
		$this->data['entry_sku'] = $this->language->get('entry_sku');
		$this->data['entry_upc'] = $this->language->get('entry_upc');
		$this->data['entry_ean'] = $this->language->get('entry_ean');
		$this->data['entry_jan'] = $this->language->get('entry_jan');
		$this->data['entry_isbn'] = $this->language->get('entry_isbn');
		$this->data['entry_mpn'] = $this->language->get('entry_mpn');
		$this->data['entry_location'] = $this->language->get('entry_location');
		$this->data['entry_minimum'] = $this->language->get('entry_minimum');
		$this->data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
    	$this->data['entry_shipping'] = $this->language->get('entry_shipping');
    	$this->data['entry_date_available'] = $this->language->get('entry_date_available');
    	$this->data['entry_quantity'] = $this->language->get('entry_quantity');
		$this->data['entry_stock_status'] = $this->language->get('entry_stock_status');
    	$this->data['entry_price'] = $this->language->get('entry_price');
		$this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$this->data['entry_points'] = $this->language->get('entry_points');
		$this->data['entry_option_points'] = $this->language->get('entry_option_points');
		$this->data['entry_subtract'] = $this->language->get('entry_subtract');
    	$this->data['entry_weight_class'] = $this->language->get('entry_weight_class');
    	$this->data['entry_weight'] = $this->language->get('entry_weight');
		$this->data['entry_dimension'] = $this->language->get('entry_dimension');
		$this->data['entry_length'] = $this->language->get('entry_length');
    	$this->data['entry_image'] = $this->language->get('entry_image');
    	$this->data['entry_download'] = $this->language->get('entry_download');
    	$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_filter'] = $this->language->get('entry_filter');
		$this->data['entry_related'] = $this->language->get('entry_related');
		$this->data['entry_attribute'] = $this->language->get('entry_attribute');
		$this->data['entry_text'] = $this->language->get('entry_text');
		$this->data['entry_option'] = $this->language->get('entry_option');
		$this->data['entry_option_value'] = $this->language->get('entry_option_value');
		$this->data['entry_required'] = $this->language->get('entry_required');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_priority'] = $this->language->get('entry_priority');
		$this->data['entry_tag'] = $this->language->get('entry_tag');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_reward'] = $this->language->get('entry_reward');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_featured_image'] = $this->language->get('entry_featured_image');
		$this->data['entry_gallery_images'] = $this->language->get('entry_gallery_images');
		$this->data['entry_shipping_methods'] = $this->language->get('entry_shipping_methods');
				
    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_attribute'] = $this->language->get('button_add_attribute');
		$this->data['button_add_option'] = $this->language->get('button_add_option');
		$this->data['button_add_option_value'] = $this->language->get('button_add_option_value');
		$this->data['button_add_discount'] = $this->language->get('button_add_discount');
		$this->data['button_add_special'] = $this->language->get('button_add_special');
		$this->data['button_add_image'] = $this->language->get('button_add_image');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
		$this->data['button_add_image_link'] = $this->language->get('button_add_image_link');
		$this->data['entry_gallery_image_link'] = $this->language->get('entry_gallery_image_link');
		
    	$this->data['tab_general'] = $this->language->get('tab_general');
    	$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_attribute'] = $this->language->get('tab_attribute');
		$this->data['tab_option'] = $this->language->get('tab_option');		
		$this->data['tab_discount'] = $this->language->get('tab_discount');
		$this->data['tab_special'] = $this->language->get('tab_special');
    	$this->data['tab_image'] = $this->language->get('tab_image');		
		$this->data['tab_links'] = $this->language->get('tab_links');
		$this->data['tab_reward'] = $this->language->get('tab_reward');
		$this->data['tab_design'] = $this->language->get('tab_design');
		
		// Error Warning
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = array();
		}

 		if (isset($this->error['meta_description'])) {
			$this->data['error_meta_description'] = $this->error['meta_description'];
		} else {
			$this->data['error_meta_description'] = array();
		}		
   
   		if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = array();
		}	
		
   		if (isset($this->error['model'])) {
			$this->data['error_model'] = $this->error['model'];
		} else {
			$this->data['error_model'] = '';
		}		
     	
		if (isset($this->error['date_available'])) {
			$this->data['error_date_available'] = $this->error['date_available'];
		} else {
			$this->data['error_date_available'] = '';
		}
			
		// Sucess
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		// URL variable for breadcrumbs
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
		
		// Breadcrumbs
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_product'),
			'href'      => $this->url->link('affiliate/dashboard_product/addProduct', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		// Insert || Update					
		if (!isset($this->request->get['product_id'])) {
			$this->data['action'] = $this->url->link('affiliate/dashboard_product/insertProduct', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('affiliate/dashboard_product/updateProduct', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');
		}
		// Cancel
		$this->data['cancel'] = $this->url->link('affiliate/dashboard_product', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		// If edit product - get product info
		if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$product_info = $this->model_affiliate_dashboard->getProduct($this->request->get['product_id']);
    	}

		$this->data['token'] = $this->session->data['token'];
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		// Description
		if (isset($this->request->post['product_description'])) {
			$this->data['product_description'] = $this->request->post['product_description'];
		} elseif (isset($this->request->get['product_id'])) {
			$this->data['product_description'] = $this->model_affiliate_dashboard->getProductDescriptions($this->request->get['product_id']);
		} else {
			$this->data['product_description'] = array();
		}		
		
		// Model
		if (isset($this->request->post['model'])) {
      		$this->data['model'] = $this->request->post['model'];
    	} elseif (!empty($product_info)) {
			$this->data['model'] = $product_info['model'];
		} else {
      		$this->data['model'] = '';
    	}
				
		// Price
    	if (isset($this->request->post['price'])) {
      		$this->data['price'] = $this->request->post['price'];
    	} elseif (!empty($product_info)) {
			$this->data['price'] = $product_info['price'];
		} else {
      		$this->data['price'] = '';
    	}
		
		// Quantity									
    	if (isset($this->request->post['quantity'])) {
      		$this->data['quantity'] = $this->request->post['quantity'];
    	} elseif (!empty($product_info)) {
      		$this->data['quantity'] = $product_info['quantity'];
    	} else {
			$this->data['quantity'] = 1;
		}
		
		// Weight
    	if (isset($this->request->post['weight'])) {
      		$this->data['weight'] = $this->request->post['weight'];
		} elseif (!empty($product_info)) {
			$this->data['weight'] = $product_info['weight'];
    	} else {
      		$this->data['weight'] = '';
    	} 
		
		// Length
		if (isset($this->request->post['length'])) {
      		$this->data['length'] = $this->request->post['length'];
    	} elseif (!empty($product_info)) {
			$this->data['length'] = $product_info['length'];
		} else {
      		$this->data['length'] = '';
    	}
		
		// Width
		if (isset($this->request->post['width'])) {
      		$this->data['width'] = $this->request->post['width'];
		} elseif (!empty($product_info)) {	
			$this->data['width'] = $product_info['width'];
    	} else {
      		$this->data['width'] = '';
    	}
		
		// Height
		if (isset($this->request->post['height'])) {
      		$this->data['height'] = $this->request->post['height'];
		} elseif (!empty($product_info)) {
			$this->data['height'] = $product_info['height'];
    	} else {
      		$this->data['height'] = '';
    	}
		
		// Manufacturer		
    	if (isset($this->request->post['manufacturer_id'])) {
      		$this->data['manufacturer_id'] = $this->request->post['manufacturer_id'];
		} elseif (!empty($product_info)) {
			$this->data['manufacturer_id'] = $product_info['manufacturer_id'];
		} else {
      		$this->data['manufacturer_id'] = 0;
    	} 		
		
    	if (isset($this->request->post['manufacturer'])) {
      		$this->data['manufacturer'] = $this->request->post['manufacturer'];
		} elseif (!empty($product_info)) {
			$manufacturer_info = $this->model_affiliate_dashboard->getManufacturer($product_info['manufacturer_id']);
			
			if ($manufacturer_info) {		
				$this->data['manufacturer'] = $manufacturer_info['name'];
			} else {
				$this->data['manufacturer'] = '';
			}	
		} else {
      		$this->data['manufacturer'] = '';
    	} 
		
		// Categories
		if (isset($this->request->post['product_category'])) {
			$categories = $this->request->post['product_category'];
		} elseif (isset($this->request->get['product_id'])) {		
			$categories = $this->model_affiliate_dashboard->getProductCategories($this->request->get['product_id']);
		} else {
			$categories = array();
		}
	
		$this->data['product_categories'] = array();
		
		foreach ($categories as $category_id) {
			$category_info = $this->model_affiliate_dashboard->getCategory($category_id);
			
			if ($category_info) {
				$this->data['product_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path'] ? $category_info['path'] . ' &gt; ' : '') . $category_info['name']
				);
			}
		}
				
		// Shipping methods
		$this->load->model('tool/image');
		
		$this->data['shipping_method'] = $this->model_affiliate_dashboard->getShippingMethods();
		
		if (isset($this->request->post['shipping_type'])) {
			$this->data['shipping_type'] = $this->request->post['shipping_type'];
		} elseif (isset($this->request->get['product_id'])) {
			$this->data['shipping_type'] = $this->model_affiliate_dashboard->getProductShippingMethods($this->request->get['product_id']);
		} else {
			$this->data['shipping_type'] = array(); 
		}
		
		// Images
		
		//featured image
		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (!empty($product_info)) {
			$this->data['image'] = $product_info['image'];
		} else {
			$this->data['image'] = '';
		}
		
		$this->load->model('tool/image');
		
		if (isset($this->request->post['image']) && file_exists(DIR_IMAGE . $this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($product_info) && $product_info['image'] && file_exists(DIR_IMAGE . $product_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		//gallery images 
		if (isset($this->request->post['product_image'])) {
			$product_images = $this->request->post['product_image'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_images = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
		} else {
			$product_images = array();
		}
		
		$this->data['product_images'] = array();
		
		foreach ($product_images as $product_image) {
			if ($product_image['image'] && file_exists(DIR_IMAGE . $product_image['image'])) {
				$image = $product_image['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
			$this->data['product_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize($image, 100, 100),
				'sort_order' => $product_image['sort_order']
			);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		$this->template = $this->config->get('config_template') . '/template/affiliate/dashboard_upload.tpl';
		
		$this->children = array(
			'affiliate/common/header',
			'affiliate/common/footer'
		);
				
		$this->response->setOutput($this->render());
	}	
	
	public function updateProduct() {
		$this->language->load('affiliate/dashboard');

    	$this->document->setTitle($this->language->get('heading_title_product'));
		
		$this->load->model('affiliate/dashboard');
	
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			$this->model_affiliate_dashboard->editProduct($this->request->get['product_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
		
			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}
			
			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}	
		
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
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
			
			$this->redirect($this->url->link('affiliate/dashboard_product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->addProduct();		
	}
	
	public function deleteProduct() {
    	$this->language->load('affiliate/dashboard');

    	$this->document->setTitle($this->language->get('heading_title_product'));
		
		$this->load->model('affiliate/dashboard');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_id) {
				
			  $this->model_affiliate_dashboard->deleteProduct($product_id);
			  
			}
  
			  $this->session->data['success'] = $this->language->get('text_success');
			  
			  $url = '';
			  
			  if (isset($this->request->get['filter_name'])) {
				  $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			  }
		  
			  if (isset($this->request->get['filter_model'])) {
				  $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			  }
			  
			  if (isset($this->request->get['filter_price'])) {
				  $url .= '&filter_price=' . $this->request->get['filter_price'];
			  }
			  
			  if (isset($this->request->get['filter_quantity'])) {
				  $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			  }	
		  
			  if (isset($this->request->get['filter_status'])) {
				  $url .= '&filter_status=' . $this->request->get['filter_status'];
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
			  
			  $this->redirect($this->url->link('affiliate/dashboard_product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			  
		} else {
			
			  $this->error['warning'] = $this->language->get('error_selected');	
		}

    	$this->index();
	}
	
  	public function insertProduct() {
		$this->language->load('affiliate/dashboard');

    	$this->document->setTitle($this->language->get('heading_title_product')); 
		
		$this->load->model('affiliate/dashboard');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			$this->model_affiliate_dashboard->addProduct($this->request->post);
			
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
			
			$this->redirect($this->url->link('affiliate/dashboard_product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}
	
    	$this->index();
  	}
	
	// Validaton
  	protected function validateForm() { 
    	if (!$this->affiliate->isLogged()) {
      		$this->error['warning'] = $this->language->get('error_token');
    	}

    	foreach ($this->request->post['product_description'] as $language_id => $value) {
      		if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 255)) {
        		$this->error['name'][$language_id] = $this->language->get('error_name');
      		}
    	}
		
    	if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
      		$this->error['model'] = $this->language->get('error_model');
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
	
  	protected function validateDelete() {
    	if (!$this->affiliate->isLogged()) {
      		$this->error['warning'] = $this->language->get('error_token');  
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
  	
  	protected function validateCopy() {
    	if (!$this->affiliate->isLogged()) {
      		$this->error['warning'] = $this->language->get('error_token');  
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
	
	// Autocomplete
	public function autocomplete() {
		$json = array();
		
		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model']) || isset($this->request->get['filter_category_id'])) {
			//$this->load->model('catalog/product');
			//$this->load->model('catalog/option');
			$this->load->model('affiliate/dashboard');
			
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
			
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];	
			} else {
				$limit = 20;	
			}			
						
			$data = array(
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
			);
			
			$results = $this->model_affiliate_dashboard->getProducts($data);
			
			foreach ($results as $result) {
				$option_data = array();
				
				$product_options = $this->model_affiliate_dashboard->getProductOptions($result['product_id']);	
				
				foreach ($product_options as $product_option) {
					$option_info = $this->model_affiliate_dashboard->getOption($product_option['option_id']);
					
					if ($option_info) {				
						if ($option_info['type'] == 'select' || $option_info['type'] == 'radio' || $option_info['type'] == 'checkbox' || $option_info['type'] == 'image') {
							$option_value_data = array();
							
							foreach ($product_option['product_option_value'] as $product_option_value) {
								$option_value_info = $this->model_affiliate_dashboard->getOptionValue($product_option_value['option_value_id']);
						
								if ($option_value_info) {
									$option_value_data[] = array(
										'product_option_value_id' => $product_option_value['product_option_value_id'],
										'option_value_id'         => $product_option_value['option_value_id'],
										'name'                    => $option_value_info['name'],
										'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
										'price_prefix'            => $product_option_value['price_prefix']
									);
								}
							}
						
							$option_data[] = array(
								'product_option_id' => $product_option['product_option_id'],
								'option_id'         => $product_option['option_id'],
								'name'              => $option_info['name'],
								'type'              => $option_info['type'],
								'option_value'      => $option_value_data,
								'required'          => $product_option['required']
							);	
						} else {
							$option_data[] = array(
								'product_option_id' => $product_option['product_option_id'],
								'option_id'         => $product_option['option_id'],
								'name'              => $option_info['name'],
								'type'              => $option_info['type'],
								'option_value'      => $product_option['option_value'],
								'required'          => $product_option['required']
							);				
						}
					}
				}
					
				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),	
					'model'      => $result['model'],
					'option'     => $option_data,
					'price'      => $result['price']
				);	
			}
		}

		$this->response->setOutput(json_encode($json));
	}
	
			
	
}// end class