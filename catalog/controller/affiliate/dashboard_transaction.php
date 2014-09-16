<?php 
class ControllerAffiliateDashboardTransaction extends Controller {

	public function index() {
		if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
		    $this->session->data['redirect'] = $this->url->link('affiliate/dashboard', '', 'SSL');
	  		$this->redirect($this->url->link('affiliate/login', '', 'SSL'));
		}

		if (!$this->affiliate->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('affiliate/dashboard', '', 'SSL');
	  		$this->redirect($this->url->link('affiliate/login', '', 'SSL'));
    	} 

    	$this->data['template_url'] = 'catalog/view/theme/' . $this->config->get('config_template');

		$this->language->load('affiliate/dashboard');

		$this->load->model('affiliate/dashboard_transaction');

		$this->data['heading_title_transaction'] = $this->language->get('heading_title_transaction');

		$this->document->setTitle($this->language->get('heading_title_transaction'));

		$this->transaction();
	}

	public function transaction() {
		$url = '';

		// Breadcrumbs
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title_transaction'),
			'href'      => $this->url->link('affiliate/dashboard_transaction', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$affiliate_id = $this->affiliate->getId();

		// Langaugae
		$this->data['text_no_results']    = $this->language->get('text_no_results');
		$this->data['text_balance']       = $this->language->get('text_balance');
		$this->data['column_date_added']  = $this->language->get('column_date_added');
		$this->data['column_description'] = $this->language->get('column_description');
		$this->data['column_amount']      = $this->language->get('column_amount');
		$this->data['column_status']      = $this->language->get('column_status');
		$this->data['button_save']        = $this->language->get('button_save');
		$this->data['button_cancel']      = $this->language->get('button_cancel');
		$this->data['text_paid_total'] = $this->language->get('text_paid_total');
		$this->data['text_balance_due'] = $this->language->get('text_balance_due');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}  

		$this->data['transaction_statuses'] = $this->model_affiliate_dashboard_transaction->getTransactionStatuses();
		$total_commission = $this->model_affiliate_dashboard_transaction->getOrderProductCommissionTotalByAffiliateId($affiliate_id);
		$order_product_total = $this->model_affiliate_dashboard_transaction->getOrderProductTotalByAffiliateId($affiliate_id);
		$this->data['balance_due'] = $this->currency->format(($order_product_total - $total_commission) - $order_product_total, $this->config->get('config_currency'));
		$this->data['total_paid'] = $this->currency->format($this->model_affiliate_dashboard_transaction->getTransactionTotal($affiliate_id), $this->config->get('config_currency'));

		$this->data['transactions'] = array();

		$results = $this->model_affiliate_dashboard_transaction->getTransactions($affiliate_id, ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$this->data['transactions'][] = array(
				'amount'      => $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'description' => $result['description'],
				'status_id'   => $result['status_id'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		

		$transaction_total = $this->model_affiliate_dashboard_transaction->getTotalTransactions($affiliate_id);

		$pagination        = new Pagination();
		$pagination->total = $transaction_total;
		$pagination->page  = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text  = $this->language->get('text_pagination');
		$pagination->url   = $this->url->link('affiliate/dashboard/transaction', 'token=' . $this->session->data['token'] . '&affiliate_id=' . $affiliate_id . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = $this->config->get('config_template') . '/template/affiliate/dashboard_transaction.tpl';
		
		$this->children = array(
			'affiliate/common/header',
			'affiliate/common/footer'
		);
		
		$this->response->setOutput($this->render());
	}

	public function edit() {
		$this->language->load('affiliate/dashboard');

		$this->data['heading_title_transaction'] = $this->language->get('heading_title_transaction');

		$this->document->setTitle($this->language->get('heading_title_transaction'));

		$this->data['template_url'] = 'catalog/view/theme/' . $this->config->get('config_template');

		$this->load->model('affiliate/dashboard_transaction');

		if ($this->request->server['REQUEST_METHOD'] == 'POST')  {
			$this->model_affiliate_dashboard_order->editTransaction();

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			$this->redirect($this->url->link('affiliate/dashboard_transaction', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->transaction();
  	}

}// end class
?>