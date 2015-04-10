<?php
class ControllerAffiliateDashboardImportEbayid extends Controller {
	private $error = array();

  public function index() {
    if (!$this->affiliate->isLogged()) {
      $this->session->data['redirect'] = $this->url->link('affiliate/dashboard_import_ebayid', '', 'SSL');
      $this->redirect($this->url->link('affiliate/login', '', 'SSL'));
    }

    $this->data['template_url'] = 'catalog/view/theme/' . $this->config->get('config_template');

    $this->data['token'] = $this->session->data['token'];

		$this->load->language('affiliate/dashboard_csv');
		$this->load->model('affiliate/dashboard_csv');
		$this->document->setTitle($this->language->get('heading_title_ebayid_import'));
    $this->document->addScript('catalog/view/javascript/event_scheduler/codebase/dhtmlxscheduler.js');
    $this->document->addScript('catalog/view/javascript/event_scheduler/codebase/ext/dhtmlxscheduler_year_view.js');
    $this->document->addStyle('catalog/view/javascript/event_scheduler/codebase/dhtmlxscheduler.css');

		$this->getForm();
	}

	protected function getForm() {
      $affiliate_id = $this->affiliate->getId();

  	  $this->data['breadcrumbs'] = array();

  		$this->data['breadcrumbs'][] = array(
         'text'      => $this->language->get('text_home'),
         'href'      => $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
         'separator' => false
  		);

  		$this->data['breadcrumbs'][] = array(
         'text'      => $this->language->get('heading_title_ebayid_import'),
         'href'      => $this->url->link('affiliate/dashboard_import_ebay_id', 'token=' . $this->session->data['token'], 'SSL'),
         'separator' => ' :: '
  		);

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
      $this->data['text_select']                      = $this->language->get('text_select');
      $this->data['text_please_wait']                 = $this->language->get('text_please_wait');

      if (isset($this->error['warning'])) {
          $this->data['error'] = $this->error['warning'];
      } else {
          $this->data['error'] = '';
      }

      if (isset($this->session->data['success'])) {
          $this->data['success'] = $this->session->data['success'];
          unset($this->session->data['success']);
      } else {
          $this->data['success'] = '';
      }

      $url = '';

      $this->data['import_ids'] = $this->url->link('affiliate/dashboard_import_ebayid/importIds', 'token=' . $this->session->data['token'] . $url, 'SSL');
      $this->data['cancel'] = $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'] . $url, 'SSL');
      $this->data['clear_dates'] = $this->url->link('affiliate/dashboard_import_ebayid/clearDates', 'token=' . $this->session->data['token'] . $url, 'SSL');

      $this->data['dates'] = $this->model_affiliate_dashboard_csv->getEbayImportStartDates($affiliate_id);

      $this->template = $this->config->get('config_template') . '/template/affiliate/dashboard_import_ebayid.tpl';

      $this->children = array(
        'affiliate/common/header',
        'affiliate/common/footer'
      );

      $this->response->setOutput($this->render());
	}

  public  function importIds() {
      $this->load->language('affiliate/dashboard_csv');
      $this->load->model('affiliate/dashboard_csv');
      $this->document->setTitle($this->language->get('heading_title_ebayid_import'));

      $affiliate_id = $this->affiliate->getId();


      if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
          $time_from = $this->request->post['start_date'] . 'T01:35:27.000Z';
          $time_to = $this->request->post['end_date'] . 'T01:35:27.000Z';
          $this->model_affiliate_dashboard_csv->setEbayImportStartDates($this->request->post);

          $import_data = $this->model_affiliate_dashboard_csv->getSellerList($affiliate_id, $time_from, $time_to);
          $match = 0;
          if (is_array($import_data)) {
            $unlinked   = $this->model_affiliate_dashboard_csv->getUnlinkedProducts($affiliate_id);

            if($unlinked) {
                foreach($unlinked as $product){
                  foreach(array_combine($import_data['id'], $import_data['title']) as $item_id => $ebay_title) {
                      if ($product['name'] === $ebay_title) {
                        $this->model_affiliate_dashboard_csv->setProductLink($affiliate_id, $product['product_id'], $item_id);
                        $match++;
                      }
                  }
                }
            }

            $this->session->data['success'] = sprintf($this->language->get('success_import'), $match);
            $this->redirect($this->url->link('import/ebayid_import', 'token=' . $this->session->data['token'], 'SSL'));
          } else if (is_string($import_data)) { // failed response
            $this->error['warning'] = $import_data;
            $this->redirect($this->url->link('import/ebayid_import', 'token=' . $this->session->data['token'], 'SSL'));
          }
      }

      $this->getForm();
  }

  public  function clearDates() {
      $this->load->language('affiliate/dashboard_csv');
      $this->load->model('affiliate/dashboard_csv');
      $this->document->setTitle($this->language->get('heading_title_ebayid_import'));

      $this->model_affiliate_dashboard_csv->clearDates();

      $this->session->data['success'] = $this->language->get('success_clear_dates');

      $this->redirect($this->url->link('affiliate/dashboard_import_ebayid', 'token=' . $this->session->data['token'], 'SSL'));
  }

  protected function validate() {
      $true = 1;

      if ((utf8_strlen($this->request->post['start_date']) < 1) || (utf8_strlen($this->request->post['start_date']) > 10)) {
          $this->session->data['error_start_date'] = $this->language->get('error_start_date');
          $true = 0;
      }

      if ((utf8_strlen($this->request->post['end_date']) < 1) || (utf8_strlen($this->request->post['end_date']) > 10)) {
          $this->session->data['error_end_date'] = $this->language->get('error_end_date');
          $true = 0;
      }

      if ($true == 0) {
        return false;
      }
      return true;
  }
}
?>