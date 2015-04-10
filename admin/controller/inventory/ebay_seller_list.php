<?php
class ControllerInventoryEbaySellerList extends Controller {
	/**
	* View all linked products
	*
	*/
	public function index() {
		$this->load->language('inventory/stock_control');
		$this->load->model('inventory/stock_control');
		$this->document->setTitle($this->language->get('heading_title_ebay_seller_list'));

		$this->getList();
	}

	protected function getList() {
		// Filter
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = null;
		}

		if (isset($this->request->get['filter_id'])) {
			$filter_id = $this->request->get['filter_id'];
		} else {
			$filter_id = null;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . urlencode(html_entity_decode($this->request->get['filter_date'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_id'])) {
			$url .= '&filter_id=' . urlencode(html_entity_decode($this->request->get['filter_id'], ENT_QUOTES, 'UTF-8'));
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
       		'text'      => $this->language->get('heading_title_ebay_seller_list'),
       		'href'      => $this->url->link('inventory/ebay_seller_list', 'token=' . $this->session->data['token'] . $url, 'SSL'),
       		'separator' => ' :: '
		);

		// Language
		$this->data['heading_title']      = $this->language->get('heading_title_ebay_seller_list');
		$this->data['button_edit']        = $this->language->get('button_edit');
		$this->data['button_cancel']      = $this->language->get('button_cancel');
		$this->data['button_delete']      = $this->language->get('button_delete');
		$this->data['text_ebay_item_id']  = $this->language->get('text_ebay_item_id');
		$this->data['text_product_id']    = $this->language->get('text_product_id');
		$this->data['text_product_title'] = $this->language->get('text_product_title');
		$this->data['button_filter']      = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];

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
			'filter_name'   => $filter_name,
			'filter_status' => $filter_status,
			'filter_date'   => $filter_date,
			'filter_id'     => $filter_id,
			'start'         => ($page - 1) * $limit,
			'limit'         => $limit
		);

		// Variables
	    $total   = $this->model_inventory_stock_control->getTotalSellerListDatabase($data);
	    $results = $this->model_inventory_stock_control->getSellerListDatabase($data);
	    $this->data['seller_list'] = array();

	    foreach ($results as $result) {
	    	$this->data['seller_list'][] = array(
	    		'ebay_title'     => $result['ebay_title'],
				'ebay_item_id'   => $result['ebay_item_id'],
				'listing_status' => $result['listing_status'],
				'end_time'       => $result['end_time']
	    	);
	    }

	    // Buttons
	    $this->data['build_list'] = $this->url->link('inventory/ebay_seller_list/getSellerList', 'token=' . $this->session->data['token'] . $url, 'SSL');
	    $this->data['truncate_list'] = $this->url->link('inventory/ebay_seller_list/truncateSellerList', 'token=' . $this->session->data['token'] . $url, 'SSL');
	    $this->data['synchronize'] = $this->url->link('inventory/ebay_seller_list/syncProducts', 'token=' . $this->session->data['token'], 'SSL');

	    // Pagination
	    $pagination        = new Pagination();
	    $pagination->total = $total;
	    $pagination->page  = $page;
	    $pagination->limit = $limit;
	    $pagination->text  = $this->language->get('text_pagination');
	    $pagination->url   = $this->url->link('inventory/ebay_seller_list', 'token=' . $this->session->data['token'] . $url . '&page={page}' , 'SSL');

	    $this->data['pagination'] = $pagination->render();

		$this->data['filter_name']   = $filter_name;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_date']   = $filter_date;
		$this->data['filter_id']     = $filter_id;
		$this->data['item_count']    = $this->model_inventory_stock_control->getTotalSellerListDatabase();

	    $this->template = 'inventory/ebay_seller_list.tpl';

	    $this->children = array(
	      'common/header',
	      'common/footer'
	    );

	    $this->response->setOutput($this->render());
	}

	public function getSellerList() {
		$this->load->language('inventory/stock_control');
		$this->load->model('inventory/stock_control');
		$this->document->setTitle($this->language->get('heading_title_ebay_seller_list'));

	    if ($this->request->server['REQUEST_METHOD'] == 'POST') {

			date_default_timezone_set('UTC');
			set_time_limit(0);

			$time_from = $this->request->post['start_date'] . 'T01:35:27.000Z';
			$date_to = $this->request->post['end_date'] . 'T01:35:27.000Z';

			while (strtotime($time_from) <= strtotime($date_to)) {
				$time_to = date ("Y-m-d", strtotime("+30 day", strtotime($time_from)));
				$import_data = $this->model_inventory_stock_control->getSellerList($time_from, $time_to);
				$time_from = date ("Y-m-d", strtotime("+30 day", strtotime($time_from)));

	        	if (count($import_data) > 0) {
					$this->model_inventory_stock_control->buildSellerList($import_data);
	        	}
			}

			$this->session->data['success'] = sprintf('SUCCESS: GetSellerList Import Complete - %d Items Returned', $this->model_inventory_stock_control->getTotalSellerListDatabase());
		    $this->redirect($this->url->link('inventory/ebay_seller_list', 'token=' . $this->session->data['token'], 'SSL'));
	    }

	    $this->getList();
	}

	public function truncateSellerList() {
		$this->load->language('inventory/stock_control');
		$this->load->model('inventory/stock_control');
		$this->document->setTitle($this->language->get('heading_title_ebay_seller_list'));
		$this->model_inventory_stock_control->truncateSellerList();
		$this->redirect($this->url->link('inventory/ebay_seller_list', 'token=' . $this->session->data['token'], 'SSL'));
	}

	public function syncProducts() {
		$this->load->language('inventory/stock_control');
		$this->load->language('catalog/product');
		$this->load->model('inventory/stock_control');
		$this->load->model('catalog/product');
		$this->document->setTitle('Product Synchronize List');
		$this->getSyncList();
	}

	protected function getSyncList() {
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
			'text'      => 'Product Synchronize List',
			'href'      => $this->url->link('inventory/ebay_seller_list/syncProducts', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->data['delete'] = $this->url->link('inventory/ebay_seller_list/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

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


		$total = 0; // count total in function below
		$results = $this->model_inventory_stock_control->getSyncProducts($data, $total);

		foreach ($results as $result) {

			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
			}

			$this->data['products'][] = array(
				'product_id' => $result['product_id'],
				'ebay_id'    => $this->model_inventory_stock_control->getEbayItemId($result['product_id']),
				'name'       => $result['name'],
				'model'      => $result['model'],
				'price'      => $result['price'],
				'image'      => $image,
				'quantity'   => $result['quantity'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected'])
			);
		}

		$this->data['heading_title'] = 'Product Synchronize List';

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

		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];

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

		$this->data['sort_name'] = $this->url->link('inventory/ebay_seller_list/syncProducts', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$this->data['sort_model'] = $this->url->link('inventory/ebay_seller_list/syncProducts', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
		$this->data['sort_price'] = $this->url->link('inventory/ebay_seller_list/syncProducts', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
		$this->data['sort_quantity'] = $this->url->link('inventory/ebay_seller_list/syncProducts', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('inventory/ebay_seller_list/syncProducts', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
		$this->data['sort_order'] = $this->url->link('inventory/ebay_seller_list/syncProducts', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');

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
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('inventory/ebay_seller_list/syncProducts', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_model'] = $filter_model;
		$this->data['filter_price'] = $filter_price;
		$this->data['filter_quantity'] = $filter_quantity;
		$this->data['filter_status'] = $filter_status;

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->data['item_count'] = $total;

		$this->template = 'inventory/ebay_sync_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function delete() {
		$this->load->language('inventory/stock_control');
		$this->load->language('catalog/product');
		$this->load->model('inventory/stock_control');
		$this->load->model('catalog/product');
		$this->document->setTitle('Product Synchronize List');

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

			$this->redirect($this->url->link('inventory/ebay_seller_list/syncProducts', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getSyncList();
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'inventory/ebay_seller_list')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}


}// end class