<?php
class ControllerCatalogProductUpload extends Controller {
	private $error = array(); 

	public function index() {

		$this->language->load('catalog/product');

		$this->document->setTitle($this->language->get('heading_title')); 

		$this->load->model('catalog/product');

		$this->getForm();
	}

  	protected function getForm() {
		// Language
		$this->data['heading_title']            = $this->language->get('heading_title');
		
		$this->data['text_enabled']             = $this->language->get('text_enabled');
		$this->data['text_disabled']            = $this->language->get('text_disabled');
		$this->data['text_none']                = $this->language->get('text_none');
		$this->data['text_yes']                 = $this->language->get('text_yes');
		$this->data['text_no']                  = $this->language->get('text_no');
		$this->data['text_plus']                = $this->language->get('text_plus');
		$this->data['text_minus']               = $this->language->get('text_minus');
		$this->data['text_default']             = $this->language->get('text_default');
		$this->data['text_image_manager']       = $this->language->get('text_image_manager');
		$this->data['text_browse']              = $this->language->get('text_browse');
		$this->data['text_clear']               = $this->language->get('text_clear');
		$this->data['text_option']              = $this->language->get('text_option');
		$this->data['text_option_value']        = $this->language->get('text_option_value');
		$this->data['text_select']              = $this->language->get('text_select');
		$this->data['text_none']                = $this->language->get('text_none');
		$this->data['text_percent']             = $this->language->get('text_percent');
		$this->data['text_amount']              = $this->language->get('text_amount');
		
		$this->data['entry_name']               = $this->language->get('entry_name');
		$this->data['entry_meta_description']   = $this->language->get('entry_meta_description');
		$this->data['entry_meta_keyword']       = $this->language->get('entry_meta_keyword');
		$this->data['entry_description']        = $this->language->get('entry_description');
		$this->data['entry_store']              = $this->language->get('entry_store');
		$this->data['entry_keyword']            = $this->language->get('entry_keyword');
		$this->data['entry_model']              = $this->language->get('entry_model');
		$this->data['entry_sku']                = $this->language->get('entry_sku');
		$this->data['entry_upc']                = $this->language->get('entry_upc');
		$this->data['entry_ean']                = $this->language->get('entry_ean');
		$this->data['entry_jan']                = $this->language->get('entry_jan');
		$this->data['entry_isbn']               = $this->language->get('entry_isbn');
		$this->data['entry_mpn']                = $this->language->get('entry_mpn');
		$this->data['entry_location']           = $this->language->get('entry_location');
		$this->data['entry_minimum']            = $this->language->get('entry_minimum');
		$this->data['entry_manufacturer']       = $this->language->get('entry_manufacturer');
		$this->data['entry_shipping']           = $this->language->get('entry_shipping');
		$this->data['entry_date_available']     = $this->language->get('entry_date_available');
		$this->data['entry_quantity']           = $this->language->get('entry_quantity');
		$this->data['entry_stock_status']       = $this->language->get('entry_stock_status');
		$this->data['entry_price']              = $this->language->get('entry_price');
		$this->data['entry_tax_class']          = $this->language->get('entry_tax_class');
		$this->data['entry_points']             = $this->language->get('entry_points');
		$this->data['entry_option_points']      = $this->language->get('entry_option_points');
		$this->data['entry_subtract']           = $this->language->get('entry_subtract');
		$this->data['entry_weight_class']       = $this->language->get('entry_weight_class');
		$this->data['entry_weight']             = $this->language->get('entry_weight');
		$this->data['entry_dimension']          = $this->language->get('entry_dimension');
		$this->data['entry_length']             = $this->language->get('entry_length');
		$this->data['entry_image']              = $this->language->get('entry_image');
		$this->data['entry_download']           = $this->language->get('entry_download');
		$this->data['entry_category']           = $this->language->get('entry_category');
		$this->data['entry_filter']             = $this->language->get('entry_filter');
		$this->data['entry_related']            = $this->language->get('entry_related');
		$this->data['entry_attribute']          = $this->language->get('entry_attribute');
		$this->data['entry_text']               = $this->language->get('entry_text');
		$this->data['entry_option']             = $this->language->get('entry_option');
		$this->data['entry_option_value']       = $this->language->get('entry_option_value');
		$this->data['entry_required']           = $this->language->get('entry_required');
		$this->data['entry_sort_order']         = $this->language->get('entry_sort_order');
		$this->data['entry_status']             = $this->language->get('entry_status');
		$this->data['entry_customer_group']     = $this->language->get('entry_customer_group');
		$this->data['entry_date_start']         = $this->language->get('entry_date_start');
		$this->data['entry_date_end']           = $this->language->get('entry_date_end');
		$this->data['entry_priority']           = $this->language->get('entry_priority');
		$this->data['entry_tag']                = $this->language->get('entry_tag');
		$this->data['entry_customer_group']     = $this->language->get('entry_customer_group');
		$this->data['entry_reward']             = $this->language->get('entry_reward');
		$this->data['entry_layout']             = $this->language->get('entry_layout');
		$this->data['entry_featured_image']     = $this->language->get('entry_featured_image');
		$this->data['entry_gallery_images']     = $this->language->get('entry_gallery_images');
		$this->data['entry_shipping_methods']   = $this->language->get('entry_shipping_methods');
		
		$this->data['button_save']              = $this->language->get('button_save');
		$this->data['button_cancel']            = $this->language->get('button_cancel');
		$this->data['button_add_attribute']     = $this->language->get('button_add_attribute');
		$this->data['button_add_option']        = $this->language->get('button_add_option');
		$this->data['button_add_option_value']  = $this->language->get('button_add_option_value');
		$this->data['button_add_discount']      = $this->language->get('button_add_discount');
		$this->data['button_add_special']       = $this->language->get('button_add_special');
		$this->data['button_add_image']         = $this->language->get('button_add_image');
		$this->data['button_remove']            = $this->language->get('button_remove');
		
		$this->data['button_add_image_link']    = $this->language->get('button_add_image_link');
		$this->data['entry_gallery_image_link'] = $this->language->get('entry_gallery_image_link');
		
		$this->data['tab_general']              = $this->language->get('tab_general');
		$this->data['tab_data']                 = $this->language->get('tab_data');
		$this->data['tab_attribute']            = $this->language->get('tab_attribute');
		$this->data['tab_option']               = $this->language->get('tab_option');		
		$this->data['tab_discount']             = $this->language->get('tab_discount');
		$this->data['tab_special']              = $this->language->get('tab_special');
		$this->data['tab_image']                = $this->language->get('tab_image');		
		$this->data['tab_links']                = $this->language->get('tab_links');
		$this->data['tab_reward']               = $this->language->get('tab_reward');
		$this->data['tab_design']               = $this->language->get('tab_design');
		
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
		
		// URL Request & Breadcrumbs
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
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/product_upload', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		// Form Save					
		if (!isset($this->request->get['product_id'])) {
			$this->data['action'] = $this->url->link('catalog/product_upload/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/product_upload/update', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');
		}
		// Form Cancel
		$this->data['cancel'] = $this->url->link('catalog/product_upload', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$product_info = $this->model_catalog_product_upload->getProduct($this->request->get['product_id']);
    	}

		$this->data['token'] = $this->session->data['token'];
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		// Description
		if (isset($this->request->post['product_description'])) {
			$this->data['product_description'] = $this->request->post['product_description'];
		} elseif (isset($this->request->get['product_id'])) {
			$this->data['product_description'] = $this->model_catalog_product_upload->getProductDescriptions($this->request->get['product_id']);
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
		$this->load->model('catalog/manufacturer');
		
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
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);
			
			if ($manufacturer_info) {		
				$this->data['manufacturer'] = $manufacturer_info['name'];
			} else {
				$this->data['manufacturer'] = '';
			}	
		} else {
      		$this->data['manufacturer'] = '';
    	} 
		
		// Categories
		$this->load->model('catalog/category');
		
		if (isset($this->request->post['product_category'])) {
			$categories = $this->request->post['product_category'];
		} elseif (isset($this->request->get['product_id'])) {		
			$categories = $this->model_catalog_product_upload->getProductCategories($this->request->get['product_id']);
		} else {
			$categories = array();
		}
	
		$this->data['product_categories'] = array();
		
		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);
			
			if ($category_info) {
				$this->data['product_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path'] ? $category_info['path'] . ' &gt; ' : '') . $category_info['name']
				);
			}
		}
				
		// Shipping methods
		$this->load->model('extras/shipping_method');
		
		// this is for the view
		$this->data['shipping_method'] = $this->model_extras_shipping_method->getShippingMethods();
		
		//example <input type="checkbox" value="1" name="shipping_type[]">
		//value="shipping_id" returned from view and stored in shipping_type. This is from <input name="shipping_type[]"> in the view
		if (isset($this->request->post['shipping_type'])) {
			$this->data['shipping_type'] = $this->request->post['shipping_type'];
		} elseif (isset($this->request->get['product_id'])) {
			$this->data['shipping_type'] = $this->model_catalog_product_upload->getProductShippingMethods($this->request->get['product_id']);
		} else {
			$this->data['shipping_type'] = array(); 
		}
				
		// Images
		$this->load->model('tool/image');
		
		// Featured Image -- data['image'] = db_product
		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (!empty($product_info)) {
			$this->data['image'] = $product_info['image'];
		} else {
			$this->data['image'] = '';
		}
		
		if (isset($this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($product_info) && $product_info['image']) {
			$this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		// Gallery Images -- data['product_image'] = db_product_image
		if (isset($this->request->post['product_image'])) {
			$product_images = $this->request->post['product_image'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_images = $this->model_catalog_product_upload->getProductImages($this->request->get['product_id']);
		} else {
			$product_images = array();
		}
		
		$this->data['product_images'] = array();
		
		foreach ($product_images as $product_image) {
			if ($product_image['image'] /*&& file_exists(DIR_IMAGE . $product_image['image'])*/) { // re-add this once real images are being used
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
										
		$this->template = 'catalog/product_upload_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
  	}

  	protected function getList() {
		
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

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/product_upload', 'token=' . $this->session->data['token'] . $url, 'SSL'),       		
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = $this->url->link('catalog/product_upload/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/product_upload/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
    	
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
		
		$this->load->model('tool/image');
		
		$product_total = $this->model_catalog_product->getTotalProducts($data);
			
		$results = $this->model_catalog_product->getProducts($data);
				    	
		if ($result) {
			foreach ($results as $result) {
				$action = array();
				
				$action[] = array(
					'text' => $this->language->get('text_edit'),
					'href' => $this->url->link('catalog/product_upload', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
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
		}
		
		
		// Language
		$this->data['heading_title']        = $this->language->get('heading_title');		
		$this->data['heading_product_edit'] = $this->language->get('heading_product_edit');
		
		$this->data['text_enabled']         = $this->language->get('text_enabled');		
		$this->data['text_disabled']        = $this->language->get('text_disabled');		
		$this->data['text_no_results']      = $this->language->get('text_no_results');		
		$this->data['text_image_manager']   = $this->language->get('text_image_manager');		
		
		$this->data['column_image']         = $this->language->get('column_image');		
		$this->data['column_name']          = $this->language->get('column_name');		
		$this->data['column_model']         = $this->language->get('column_model');		
		$this->data['column_price']         = $this->language->get('column_price');		
		$this->data['column_quantity']      = $this->language->get('column_quantity');		
		$this->data['column_status']        = $this->language->get('column_status');		
		$this->data['column_action']        = $this->language->get('column_action');		
		
		$this->data['button_copy']          = $this->language->get('button_copy');		
		$this->data['button_insert']        = $this->language->get('button_insert');		
		$this->data['button_delete']        = $this->language->get('button_delete');		
		$this->data['button_filter']        = $this->language->get('button_filter');
		$this->data['button_search']        = $this->language->get('button_search');
	 
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
					
		$this->data['sort_name']     = $this->url->link('catalog/product_upload', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$this->data['sort_model']    = $this->url->link('catalog/product_upload', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
		$this->data['sort_price']    = $this->url->link('catalog/product_upload', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
		$this->data['sort_quantity'] = $this->url->link('catalog/product_upload', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
		$this->data['sort_status']   = $this->url->link('catalog/product_upload', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
		$this->data['sort_order']    = $this->url->link('catalog/product_upload', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');
		
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
		
		$pagination        = new Pagination();
		$pagination->total = $product_total;
		$pagination->page  = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text  = $this->language->get('text_pagination');
		$pagination->url   = $this->url->link('catalog/product_upload', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

				
	
		$this->data['filter_name']     = $filter_name;
		$this->data['filter_model']    = $filter_model;
		$this->data['filter_price']    = $filter_price;
		$this->data['filter_quantity'] = $filter_quantity;
		$this->data['filter_status']   = $filter_status;
		$this->data['sort']            = $sort;
		$this->data['order']           = $order;

		$this->template = 'catalog/product_upload_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
  	}

  	public function insert() {
		$this->language->load('catalog/product_upload');

    	$this->document->setTitle($this->language->get('heading_title')); 
		
		$this->load->model('catalog/product_upload');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_product_upload->addProduct($this->request->post);
			
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
			
			$this->redirect($this->url->link('catalog/product_upload', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}
	
    	$this->getList();	
  	}
	
  	public function edit() {
    	$this->language->load('catalog/product_upload');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/product_upload');
	
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_product_upload->editProduct($this->request->get['product_id'], $this->request->post);
			
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
		}

    	$this->getList();
  	} 

  	public function delete() {
    	$this->language->load('catalog/product_upload');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/product');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_product->deleteProduct($product_id);
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
			
			$this->redirect($this->url->link('catalog/product_upload', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getList();
  	}

  	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/product')) {
      		$this->error['warning'] = $this->language->get('error_permission');  
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

  	protected function validateInsert() {
	    if ( !$this->user->hasPermission('modify', 'catalog/product_upload') ) {
      		$this->error['warning'] = $this->language->get('error_permission');
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

  	protected function validateEdit() {

  	}

}// end class