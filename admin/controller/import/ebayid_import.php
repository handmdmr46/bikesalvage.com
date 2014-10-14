<?php
class ControllerImportEbayidImport extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('import/csv_import');
		$this->document->setTitle($this->language->get('heading_title_ebayid_import'));
		$this->load->model('import/csv_import');
		$this->document->addScript('view/javascript/event_scheduler/codebase/dhtmlxscheduler.js');
    	$this->document->addScript('view/javascript/event_scheduler/codebase/ext/dhtmlxscheduler_year_view.js');
    	$this->document->addStyle('view/javascript/event_scheduler/codebase/dhtmlxscheduler.css');

		$this->getForm();
	}

	protected function getForm() {
	    // Breadcrumbs
	    $this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
       		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_ebayid_import'),
       		'href'      => $this->url->link('import/ebayid_import', 'token=' . $this->session->data['token'], 'SSL'),
       		'separator' => ' :: '
		);

	   	// Language
		$this->data['heading_title']                    = $this->language->get('heading_title_ebayid_import');
		$this->data['button_import']                    = $this->language->get('button_import');
		$this->data['button_cancel']                    = $this->language->get('button_cancel');
		$this->data['button_clear_dates']               = $this->language->get('button_clear_dates');
		$this->data['text_ebay_start_from']             = $this->language->get('text_ebay_start_from');
		$this->data['text_ebay_start_from_help']        = $this->language->get('text_ebay_start_from_help');
		$this->data['text_ebay_start_to']               = $this->language->get('text_ebay_start_to');
		$this->data['text_ebay_start_to_help']          = $this->language->get('text_ebay_start_to_help');
		$this->data['text_no_dates']                    = $this->language->get('text_no_dates');
		$this->data['text_none']                        = $this->language->get('text_none');
		$this->data['text_compat_help']                 = $this->language->get('text_compat_help');
		$this->data['text_confirm_clear_dates']         = $this->language->get('text_confirm_clear_dates');
		$this->data['text_site_id']                     = $this->language->get('text_site_id');
		$this->data['text_start_dates']                 = $this->language->get('text_start_dates');
		$this->data['text_start_dates_help']            = $this->language->get('text_start_dates_help');
		$this->data['text_select'] 						= $this->language->get('text_select');
		$this->data['text_please_wait'] 				= $this->language->get('text_please_wait');

	    // Error
 		if (isset($this->error['warning'])) {
			$this->data['error'] = $this->error['warning'];
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

	    $url = '';

	    // Buttons
	    $this->data['import_ids'] = $this->url->link('import/ebayid_import/importIds', 'token=' . $this->session->data['token'] . $url, 'SSL');	    
	    $this->data['clear_dates'] = $this->url->link('import/ebayid_import/clearDates', 'token=' . $this->session->data['token'] . $url, 'SSL');
	    $this->data['cancel'] = $this->url->link('common/home', 'token=' . $this->session->data['token'] . $url, 'SSL');

	    $profiles                        = $this->model_import_csv_import->getEbayProfile();
	    $this->data['ebay_sites']        = $this->model_import_csv_import->getEbaySiteIds();
	    $this->data['compat_levels']     = $this->model_import_csv_import->getEbayCompatibilityLevels();
	    $this->data['dates']             = $this->model_import_csv_import->getEbayImportStartDates();

	    $this->template = 'import/ebayid_import.tpl';

	    $this->children = array(
	      'common/header',
	      'common/footer'
	    );

	    $this->response->setOutput($this->render());
	}

    public  function importIds() {
	    $this->language->load('import/csv_import');	    
		$this->document->setTitle($this->language->get('heading_title_ebayid_import'));
		$this->load->model('import/csv_import');
		$this->load->model('inventory/stock_control');
		

	    if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
	    	$time_from = $this->request->post['start_date'] . 'T01:35:27.000Z';
			$time_to = $this->request->post['end_date'] . 'T01:35:27.000Z';
	        $this->model_import_csv_import->setEbayImportStartDates($this->request->post);

	        $import_data = $this->model_inventory_stock_control->getSellerList($time_from, $time_to);

	        if (is_array($import_data)) {
		        $unlinked   = $this->model_inventory_stock_control->getUnlinkedProducts();
		      
		        if($unlinked) {
		      	    foreach($unlinked as $product){
			            foreach(array_combine($import_data['id'], $import_data['title']) as $item_id => $ebay_title) {
			                if ($product['name'] === $ebay_title) {
			                	$this->model_inventory_stock_control->setProductLink($product['product_id'], $item_id);		                  
			                }
			            }
		      	    }
		        }		        

		        $this->session->data['success'] = $this->language->get('success_import');
		        $this->redirect($this->url->link('import/ebayid_import', 'token=' . $this->session->data['token'], 'SSL'));		       
			} else if (is_string($import_data)) { // failed response
				$this->error['warning'] = $import_data;	
				$this->redirect($this->url->link('import/ebayid_import', 'token=' . $this->session->data['token'], 'SSL'));
			}
	    }

	    $this->getForm();
    }

    protected function validate() {
		if (!$this->user->hasPermission('modify', 'import/ebayid_import')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

    public  function clearDates() {
	    $this->language->load('import/csv_import');
		$this->document->setTitle($this->language->get('heading_title_ebayid_import'));
		$this->load->model('import/csv_import');

	    $this->model_import_csv_import->deleteEbayImportStartDates();

	    $this->session->data['success'] = $this->language->get('success_clear_dates');

	    $this->redirect($this->url->link('import/ebayid_import', 'token=' . $this->session->data['token'], 'SSL'));
    }

}// end class

