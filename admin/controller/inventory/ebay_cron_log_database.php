<?php
class ControllerInventoryEbayCronLogDatabase extends Controller {
	public function index() {     
		$this->language->load('inventory/ebay_cron_log_database');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
		}	

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
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
			'href'      => $this->url->link('inventory/ebay_cron_log_database', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->load->model('inventory/stock_control');

		$this->data['logs'] = array();

		$data = array(
			'filter_date_start'	     => $filter_date_start, 
			'filter_date_end'	     => $filter_date_end, 
			'start'                  => ($page - 1) * 24,
			'limit'                  => 24
		);

		$log_total = $this->model_inventory_stock_control->getTotalLogs($data); 
		$results = $this->model_inventory_stock_control->getLogs($data);

		foreach ($results as $result) {

			$this->data['logs'][] = array (
				'message'	=> $result['message'],
				'log_date'  => $result['log_date']
			);
		}

		$this->data['heading_title']    = $this->language->get('heading_title');
		$this->data['text_no_results']  = $this->language->get('text_no_results');
		$this->data['text_all_status']  = $this->language->get('text_all_status');
		$this->data['column_message']   = $this->language->get('column_message');
		$this->data['column_date']      = $this->language->get('column_date');
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end']   = $this->language->get('entry_date_end');
		$this->data['button_filter']    = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $log_total;
		$pagination->page = $page;
		$pagination->limit = 24;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('inventory/ebay_cron_log_database', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;		

		$this->template = 'inventory/ebay_cron_log_database.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
}
?>