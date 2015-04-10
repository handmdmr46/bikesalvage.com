<?php
class ControllerAffiliateDashboardImportCsv extends Controller {
	public function index() {
		if (!$this->affiliate->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('affiliate/dashboard_import_csv', '', 'SSL');
			$this->redirect($this->url->link('affiliate/login', '', 'SSL'));
		}

		$this->data['template_url'] = 'catalog/view/theme/' . $this->config->get('config_template');

		$this->load->language('affiliate/dashboard_csv');
		$this->load->model('affiliate/dashboard_csv');
		$this->document->setTitle($this->language->get('heading_title_csv_import'));

		$this->import();
	}


	public function import() {
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_csv_import'),
			'href'      => $this->url->link('affiliate/dashboard_import_csv', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['heading_title_csv_import'] = $this->language->get('heading_title_csv_import');
		$this->data['text_choose_file']         = $this->language->get('text_choose_file');
		$this->data['text_skip_image']          = $this->language->get('text_skip_image');
		$this->data['text_skip_image_help']     = $this->language->get('text_skip_image_help');
		$this->data['button_import']            = $this->language->get('button_import');
		$this->data['button_cancel']            = $this->language->get('button_cancel');
		$this->data['text_file_type']           = $this->language->get('text_file_type');
		$this->data['text_file_type_help']      = $this->language->get('text_file_type_help');
		$this->data['text_file_exchange']       = $this->language->get('text_file_exchange');
		$this->data['text_turbo_lister']        = $this->language->get('text_turbo_lister');
		$this->data['text_title']               = $this->language->get('text_title');
		$this->data['text_description']         = $this->language->get('text_description');
		$this->data['text_quantity']            = $this->language->get('text_quantity');
		$this->data['text_price']               = $this->language->get('text_price');
		$this->data['text_length']              = $this->language->get('text_length');
		$this->data['text_width']               = $this->language->get('text_width');
		$this->data['text_height']              = $this->language->get('text_height');
		$this->data['text_manufacturer']        = $this->language->get('text_manufacturer');
		$this->data['text_category']            = $this->language->get('text_category');
		$this->data['text_shipping_dom']        = $this->language->get('text_shipping_dom');
		$this->data['text_shipping_intl']       = $this->language->get('text_shipping_intl');
		$this->data['text_model']               = $this->language->get('text_model');
		$this->data['text_gallery_image']       = $this->language->get('text_gallery_image');
		$this->data['text_image']               = $this->language->get('text_image');
		$this->data['text_count']               = $this->language->get('text_count');
		$this->data['text_model']               = $this->language->get('text_model');
		$this->data['text_weight']              = $this->language->get('text_weight');
		$this->data['button_delete']            = $this->language->get('button_delete');
		$this->data['button_edit_list']         = $this->language->get('button_edit_list');
		$this->data['button_clear']             = $this->language->get('button_clear');
		$this->data['text_confirm']             = $this->language->get('text_confirm');
		$this->data['text_confirm_edit']        = $this->language->get('text_confirm_edit');
		$this->data['entry_select']             = $this->language->get('entry_select');
		$this->data['text_search_image']        = $this->language->get('text_search_image');
		$this->data['text_search_image_help']   = $this->language->get('text_search_image_help');
		$this->data['text_ebay_id']             = $this->language->get('text_ebay_id');
		$this->data['text_please_wait']         = $this->language->get('text_please_wait');
		$this->data['text_export_date']         = $this->language->get('text_export_date');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->session->data['error_file'])) {
			$this->data['error_warning'] = $this->session->data['error_file'];
			unset($this->session->data['error_file']);
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['error_validation'])) {
			$this->data['error_warning'] = $this->session->data['error_validation'];
			unset($this->session->data['error_validation']);
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['error_delete'])) {
			$this->data['error_warning'] = $this->session->data['error_delete'];
			unset($this->session->data['error_edit']);
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['error_edit'])) {
			$this->data['error_warning'] = $this->session->data['error_edit'];
			unset($this->session->data['error_edit']);
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['error_duplicate_title'])) {
			$this->data['error_warning'] = $this->session->data['error_duplicate_title'];
			unset($this->session->data['error_duplicate_title']);
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
    		$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->session->data['success_delete'])) {
    		$this->data['success'] = $this->session->data['success_delete'];
			unset($this->session->data['success_delete']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->session->data['success_edit'])) {
    		$this->data['success'] = $this->session->data['success_edit'];
			unset($this->session->data['success_edit']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
			$this->data['page'] = $this->request->get['page'];
		} else {
			$page = 1;
			$this->data['page'] = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$limit = $this->config->get('config_admin_limit');
		$start = ($page - 1) * $limit;

		$this->data['import']   = $this->url->link('affiliate/dashboard_import_csv', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel']   = $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete']   = $this->url->link('affiliate/dashboard_import_csv/delete', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['edit']     = $this->url->link('affiliate/dashboard_import_csv/edit', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['loading']  = '';
		$this->data['csv']      = array();
		$this->data['csv_view'] = array();

		$csv_mimetypes = array(
			'text/csv',
			'text/plain',
			'application/csv',
			'text/comma-separated-values',
			'application/excel',
			'application/vnd.ms-excel',
			'application/vnd.msexcel',
			'text/anytext',
			'application/octet-stream',
			'application/txt',
        );

		$affiliate_id = $this->affiliate->getId();
	    $this->data['manufacturers'] = $this->model_affiliate_dashboard_csv->getManufacturers();
	    $this->data['domestic_shipping'] = $this->model_affiliate_dashboard_csv->getDomesticShippingMethods();
	    $this->data['international_shipping'] = $this->model_affiliate_dashboard_csv->getInternationalShippingMethods();

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

		    if (is_uploaded_file($this->request->files['csv']['tmp_name']) && in_array($this->request->files['csv']['type'], $csv_mimetypes)) {
			    $content = $this->request->files['csv']['tmp_name'];
		    } else {
			    $content = false;
			    $this->session->data['error_file'] = $this->language->get('error_file_type');
		        $this->redirect($this->url->link('import/csv_import', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		    }

		    if ($this->validImport($content)) {

			    $csv = new Parsecsv();

			    $csv->auto($content);

			    $import_data = array();

			    foreach($csv->csvdata as $key => $row) {
					$import_data[] = array(
						'title'              => $csv->csvdata[$key]['Title'],
						'description'        => $csv->csvdata[$key]['Description'],
						'quantity'           => $csv->csvdata[$key]['Quantity'],
						'price'              => $csv->csvdata[$key]['StartPrice'],
						'item_id'            => $csv->csvdata[$key]['ItemID'],
						'image'              => $csv->csvdata[$key]['PicURL'],
						'weight'             => $csv->csvdata[$key]['WeightMinor'] / 16 + $csv->csvdata[$key]['WeightMajor'],
						'unit'               => $csv->csvdata[$key]['MeasurementUnit'],
						'length'             => $csv->csvdata[$key]['PackageLength'],
						'width'              => $csv->csvdata[$key]['PackageWidth'],
						'height'             => $csv->csvdata[$key]['PackageDepth'],
						'shipping_dom'       => $csv->csvdata[$key]['ShippingService-1:Option'],
						'shipping_intl'      => $csv->csvdata[$key]['IntlShippingService-1:Option'],
						'is_paypal'          => $csv->csvdata[$key]['PayPalAccepted'],
						'paypal_email'       => $csv->csvdata[$key]['PayPalEmailAddress'],
						'shipping_name_dom'  => '',
						'shipping_name_intl' => '',
						'manufacturer_id'    => '',
						'manufacturer_name'  => '',
						'category_id'        => '',
						'category_name'      => '',
						'gallery_images'     => '',
						'model'              => $csv->csvdata[$key]['CustomLabel']
					);
			    }

			    foreach($import_data as $key => $row) {

			    	foreach($import_data as $key2 => $row2) {
			    		if ($import_data[$key]['title'] === $import_data[$key2]['title']) {
			    			$this->session->data['error_duplicate_title'] = $this->language->get('error_duplicate_title');
							$this->redirect($this->url->link('import/csv_import', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			    		}
			    	}

			  	    $import_data[$key]['image'] = parse_url($import_data[$key]['image'], PHP_URL_PATH);
			  	    $import_data[$key]['image'] = str_replace('image/', '', $import_data[$key]['image']);

				    $manufacturers = $this->model_affiliate_dashboard_csv->getManufacturerInfo();
				    foreach ($manufacturers as $manufacturer) {
				  	    if (stripos($import_data[$key]['title'],$manufacturer['name']) !== false) {
					    	$import_data[$key]['manufacturer_id'] = (int)$manufacturer['manufacturer_id'];
				    	}
					}

			    	$categories = $this->model_affiliate_dashboard_csv->getCategoryInfoByManufacturerId((int)$import_data[$key]['manufacturer_id']);
				    if ($categories) {
				  		foreach ($categories as $category) {
					  		if (strpos($import_data[$key]['title'], $category['name']) !== false) {
					  			$import_data[$key]['category_id'] = (int)$category['category_id'];
					  		}
				    	}
			  		}

				  	$import_data[$key]['description'] = str_ireplace ('%0d', '', $import_data[$key]['description']);
				  	$import_data[$key]['description'] = str_ireplace ('%0a', '', $import_data[$key]['description']);
				  	$import_data[$key]['description'] = str_ireplace('@@@@%','', $import_data[$key]['description']);
				  	$import_data[$key]['description'] = preg_replace('(\<(/?[^\>]+)\>)', '', $import_data[$key]['description']);
				  	$import_data[$key]['description'] = str_replace('Browse our many parts at:','',$import_data[$key]['description']);
				  	$import_data[$key]['description'] = str_replace('GSMESS Bits!','',$import_data[$key]['description']);
				  	$import_data[$key]['description'] = str_replace('We\'ve got great feedback from thousands of honest trades, so purchase with confidence!','',$import_data[$key]['description']);
				  	$import_data[$key]['description'] = str_replace('Please understand that we do not know if this will fit your custom application, or other years/models other than those explicitly listed in the title and description, thank you.','',$import_data[$key]['description']);
				  	$import_data[$key]['description'] = str_replace('International customers, sometimes you get hit with a ridiculous customs tax before taking delivery of your items; at that, I sympathize, though it\'s out of my control.','',$import_data[$key]['description']);
				  	$import_data[$key]['description'] = str_replace('Yes, you have to pay import taxes.','',$import_data[$key]['description']);
				  	$import_data[$key]['description'] = str_replace('Domestic customers, as always: No hidden charges. No small print. No hassle returns.','',$import_data[$key]['description']);
				  	$import_data[$key]['description'] = str_replace('Other payment forms are still gladly accepted!','',$import_data[$key]['description']);
				  	$import_data[$key]['description'] = str_replace('Please contact us after buying for payment details.','',$import_data[$key]['description']);
				  	$import_data[$key]['description'] = str_replace('Customs fees for international buyers are the responsibility of the purchaser, please do not ask me to edit declaration values.','',$import_data[$key]['description']);
				  	$import_data[$key]['description'] = str_replace('No hidden charges.','',$import_data[$key]['description']);
				  	$import_data[$key]['description'] = str_replace('No small print.','',$import_data[$key]['description']);
				  	$import_data[$key]['description'] = str_replace('No hassle returns.','',$import_data[$key]['description']);

				  	if($import_data[$key]['shipping_dom'] == 'USPSPriorityFlatRateEnvelope'){
					  	$import_data[$key]['shipping_dom'] = 4;
				  	} elseif ($import_data[$key]['shipping_dom'] == 'USPSPriorityFlatRateBox'){
					  	$import_data[$key]['shipping_dom'] = 5;
				  	} elseif ($import_data[$key]['shipping_dom'] == 'USPSPriorityLargeFlatRateBox'){
					  	$import_data[$key]['shipping_dom'] = 6;
				  	} elseif ($import_data[$key]['shipping_dom'] == 'USPSFirstClass'){
					  	$import_data[$key]['shipping_dom'] = 1;
				  	} else {
						$import_data[$key]['shipping_dom'] = 7;//Parcel Select
				  	}

				  	if($import_data[$key]['shipping_intl'] == 'USPSPriorityMailInternationalFlatRateEnvelope'){
					  	$import_data[$key]['shipping_intl'] = 11;
				  	} elseif($import_data[$key]['shipping_intl'] == 'USPSPriorityMailInternationalFlatRateBox'){
					  	$import_data[$key]['shipping_intl'] = 12;
				  	} elseif ($import_data[$key]['shipping_intl'] == 'USPSPriorityMailInternationalLargeFlatRateBox'){
					  	$import_data[$key]['shipping_intl'] = 13;
				  	} elseif ($import_data[$key]['shipping_intl'] == 'USPSFirstClassMailInternational'){
					  	$import_data[$key]['shipping_intl'] = 9;
				  	} else {
					  	$import_data[$key]['shipping_intl'] = 10;//Priority International
				  	}

					$this->model_affiliate_dashboard_csv->addAffiliateCsvImportProduct($affiliate_id, $import_data[$key]);
			    }

				date_default_timezone_set('America/Los_Angeles');

				$import_date = $this->request->post['import_date'];

			    $date = new DateTime($import_date);
				$date->sub(new DateInterval('P3D'));
				$time_from = $date->format('Y-m-d');

				$date = new DateTime($import_date);
				$date->add(new DateInterval('P3D'));
				$time_to = $date->format('Y-m-d');

				$this->load->model('inventory/stock_control');
				$import_data = $this->model_affiliate_dashboard_csv->getSellerList($affiliate_id, $time_from . 'T01:00:00.000Z', $time_to . 'T01:00:00.000Z');

				if (is_array($import_data)) {
		        	$unlinked   = $this->model_affiliate_dashboard_csv->getUnlinkedProducts($affiliate_id);

			        if($unlinked) {
			      	    foreach($unlinked as $product){
				            foreach(array_combine($import_data['id'], $import_data['title']) as $item_id => $ebay_title) {
				                if ($product['name'] === $ebay_title) {
				                	$this->model_affiliate_dashboard_csv->setProductLink($affiliate_id, $product['product_id'], $item_id);
				                }
				            }
			      	    }
			        }
				} else if (is_string($import_data)) { // failed response
					$this->session->data['error_validation'] = $import_data;
					$this->redirect($this->url->link('import/csv_import', 'token=' . $this->session->data['token'], 'SSL'));
				}

			    $this->session->data['success'] = $this->language->get('text_csv_success');
			    $this->redirect($this->url->link('import/csv_import', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		    } else {
			    $this->session->data['error_validation'] = $this->language->get('error_validation');
			    $this->redirect($this->url->link('import/csv_import', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		    }
		}

		$this->data['csv_view'] = $this->model_affiliate_dashboard_csv->getCsvImportProductInfo($start, $limit, $affiliate_id);

		if($this->data['csv_view']) {
		  $import_count = $this->model_affiliate_dashboard_csv->getTotalCsvImportProducts($affiliate_id);
		} else {
			$import_count = '';
		}

		$pagination = new Pagination();
		$pagination->total = $import_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('affiliate/dashboard_import_csv', 'token=' . $this->session->data['token']  . '&page={page}' , 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = $this->config->get('config_template') . '/template/affiliate/dashboard_import_csv.tpl';

		$this->children = array(
			'affiliate/common/header',
			'affiliate/common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function clear() {
		$this->language->load('affiliate/dashboard');
		$this->load->model('affiliate/dashboard_csv');
		$this->document->setTitle($this->language->get('heading_title_import_csv'));

		$url = '';

		if (isset($this->request->get['page'])) {
			  $url .= '&page=' . $this->request->get['page'];
		}

		$this->model_import_csv_import->clearCsvImportTable($this->affiliate->getId());
		$this->session->data['success_clear'] = $this->language->get('success_clear');
    	$this->redirect($this->url->link('affiliate/dashboard_import_csv', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function edit() {
		$this->language->load('affiliate/dashboard');
		$this->load->model('affiliate/dashboard_csv');
		$this->document->setTitle($this->language->get('heading_title_import_csv'));
		$url = '';

		if (isset($this->request->get['page'])) {
			  $url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $product_id) {
					$name_str           = $product_id . '_name';
					$description_str    = $product_id . '_description';
					$quantity_str       = $product_id . '_quantity';
					$price_str          = $product_id . '_price';
					$length_str         = $product_id . '_length';
					$width_str          = $product_id . '_width';
					$height_str         = $product_id . '_height';
					$category_str       = $product_id . '_category';
					$manufacturer_str   = $product_id . '_manufacturer';
					$weight_str         = $product_id . '_weight';
					$shipping_dom_str   = $product_id . '_shipping_dom';
					$shipping_intl_str  = $product_id . '_shipping_intl';
					$model_str          = $product_id . '_model';
					$featured_image_str = $product_id . '_featured_image';

				  $image_count = $this->model_affiliate_dashboard_csv->getTotalGalleryImages($product_id);

				  $gallery_array = array();

				  for($i = 0; $i < $image_count; $i++) {
					  $gallery_array[] = ($product_id . '_gallery_image_' . $i);
				  }

					$name           = $this->request->post[$name_str];
					$description    = $this->request->post[$description_str];
					$quantity       = $this->request->post[$quantity_str];
					$price          = $this->request->post[$price_str];
					$lenght         = $this->request->post[$length_str];
					$width          = $this->request->post[$width_str];
					$height         = $this->request->post[$height_str];
					$category       = $this->request->post[$category_str];
					$manufacturer   = $this->request->post[$manufacturer_str];
					$weight         = $this->request->post[$weight_str];
					$shipping_dom   = $this->request->post[$shipping_dom_str];
					$shipping_intl  = $this->request->post[$shipping_intl_str];
					$model          = $this->request->post[$model_str];
					$featured_image = $this->request->post[$featured_image_str];

				  $gallery_images = array();

				  foreach($gallery_array as $value) {
				    $gallery_images[] = $this->request->post[$value];
				  }

				  $edit_data = array(
				  	'name'           => $name,
					'description'    => $description,
					'quantity'       => $quantity,
					'price'          => $price,
					'length'         => $lenght,
					'width'          => $width,
					'height'         => $height,
					'category'       => $category,
					'manufacturer'   => $manufacturer,
					'weight'         => $weight,
					'shipping_dom'   => $shipping_dom,
					'shipping_intl'  => $shipping_intl,
					'model'          => $model,
					'featured_image' => $featured_image,
					'gallery_images' => $gallery_images
				  );

				  $this->model_affiliate_dashboard_csv->editList($product_id, $edit_data);
			}

			$this->session->data['success_edit'] = $this->language->get('success_edit');
			$this->redirect($this->url->link('affiliate/dashboard_import_csv', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->session->data['error_edit'] = $this->language->get('error_edit');
    	$this->redirect($this->url->link('affiliate/dashboard_import_csv', 'token=' . $this->session->data['token'] . $url, 'SSL'));
  	}

  	public function delete() {
		$this->language->load('affiliate/dashboard');
		$this->load->model('affiliate/dashboard_csv');
		$this->document->setTitle($this->language->get('heading_title_import_csv'));

		if (isset($this->request->post['selected'])) {
			$url = '';

			if (isset($this->request->get['page'])) {
				  $url .= '&page=' . $this->request->get['page'];
			}

			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_affiliate_dashboard_csv->deleteProduct($product_id);
	  		}

			$this->session->data['success_delete'] = $this->language->get('success_delete');
			$this->redirect($this->url->link('affiliate/dashboard_import_csv', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->session->data['error_delete'] = $this->language->get('error_delete');
    	$this->redirect($this->url->link('affiliate/dashboard_import_csv', 'token=' . $this->session->data['token'] . $url, 'SSL'));
  	}

	protected function validImport($content) {
		$csv = new Parsecsv();
		$csv->auto($content);
		$match = 0;
		foreach ($csv->titles as $value) {
			if($value == 'Title') {$match++;}
			if($value == 'Description') {$match++;}
			if($value == 'Quantity') {$match++;}
			if($value == 'ItemID') {$match++;}
			if($value == 'PicURL') {$match++;}
			if($value == 'WeightMinor') {$match++;}
			if($value == 'WeightMajor') {$match++;}
			if($value == 'MeasurementUnit') {$match++;}
			if($value == 'PackageLength') {$match++;}
			if($value == 'PackageWidth') {$match++;}
			if($value == 'PackageDepth') {$match++;}
			if($value == 'ShippingService-1:Option') {$match++;}
			if($value == 'IntlShippingService-1:Option') {$match++;}
			if($value == 'PayPalAccepted') {$match++;}
			if($value == 'PayPalEmailAddress') {$match++;}
		}

		if ($match == 15) {
			return true;
		} else {
			return false;
		}
	}
}
?>