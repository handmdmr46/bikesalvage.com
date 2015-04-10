<?php
class ControllerAffiliateCommonHeader extends Controller {

	protected function index() {
		if (!isset($this->session->data['token'])) {
			$this->session->data['token'] = md5(mt_rand());
		}

		$this->data['title'] = $this->document->getTitle();

		$this->language->load('affiliate/dashboard');

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['styles']                    = $this->document->getStyles();
		$this->data['scripts']                   = $this->document->getScripts();
		$this->data['first_name']                = $this->affiliate->getFirstName();
		$this->data['last_name']                 = $this->affiliate->getLastName();
		$this->data['text_welcome']              = $this->language->get('text_welcome');
		$this->data['text_logout']               = $this->language->get('text_logout');
		$this->data['text_homepage']             = $this->language->get('text_homepage');
		$this->data['text_products']             = $this->language->get('text_products');
		$this->data['text_orders']               = $this->language->get('text_orders');
		$this->data['text_profile_edit']         = $this->language->get('text_profile_edit');
		$this->data['text_password_edit']        = $this->language->get('text_password_edit');
		$this->data['text_sales']                = $this->language->get('text_sales');
		$this->data['text_product_upload']       = $this->language->get('text_product_upload');
		$this->data['text_product_edit']         = $this->language->get('text_product_edit');
		$this->data['text_product_import']       = $this->language->get('text_product_import');
		$this->data['text_dashboard_header']     = $this->language->get('text_dashboard_header');
		$this->data['text_dashboard']            = $this->language->get('text_dashboard');
		$this->data['text_catalog']              = $this->language->get('text_catalog');
		$this->data['text_view_product']         = $this->language->get('text_view_product');
		$this->data['text_edit_product']         = $this->language->get('text_edit_product');
		$this->data['text_upload_product']       = $this->language->get('text_upload_product');
		$this->data['text_order']                = $this->language->get('text_order');
		$this->data['text_return']               = $this->language->get('text_return');
		$this->data['text_setting']              = $this->language->get('text_setting');
		$this->data['text_config']               = $this->language->get('text_config');
		$this->data['text_shipping_config']      = $this->language->get('text_shipping_config');
		$this->data['text_csv_import']           = $this->language->get('text_csv_import');
		$this->data['text_ebay_import']          = $this->language->get('text_ebay_import');
		$this->data['text_ebayid_import']        = $this->language->get('text_ebayid_import');
		$this->data['text_transaction']          = $this->language->get('text_transaction');
		$this->data['text_shipping_config']      = $this->language->get('text_shipping_config');
		$this->data['text_linked_products']      = $this->language->get('text_linked_products');
		$this->data['text_unlinked_products']    = $this->language->get('text_unlinked_products');
		$this->data['text_stock_control']        = $this->language->get('text_stock_control');
		$this->data['text_config_stock_control'] = $this->language->get('text_config_stock_control');
		$this->data['text_ebay_cron_log']        = $this->language->get('text_ebay_cron_log');

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
			$this->data['store'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
			$this->data['store'] = HTTP_SERVER;
		}

		$this->data['logout']                  = $this->url->link('affiliate/logout', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['affiliate_dashboard']     = $this->url->link('affiliate/dashboard', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['view_product']            = $this->url->link('affiliate/dashboard_product', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['upload_product']          = $this->url->link('affiliate/dashboard_product/upload', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['profile']                 = $this->url->link('affiliate/dashboard_setting_profile', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['edit_password']           = $this->url->link('affiliate/dashboard_edit_password', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['order']                   = $this->url->link('affiliate/dashboard_order', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['return']                  = $this->url->link('affiliate/dashboard_return', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['dashboard_import_csv']    = $this->url->link('affiliate/dashboard_import_csv', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['dashboard_import_ebay']   = $this->url->link('affiliate/dashboard_import_ebay', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['dashboard_import_ebayid'] = $this->url->link('affiliate/dashboard_import_ebayid', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['transaction']             = $this->url->link('affiliate/dashboard_transaction', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['shipping_config']         = $this->url->link('affiliate/dashboard_setting_shipping', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['linked_products']         = $this->url->link('affiliate/dashboard_linked_products', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['unlinked_products']       = $this->url->link('affiliate/dashboard_unlinked_products', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['config_stock_control']    = $this->url->link('affiliate/dashboard_setting_stock_control', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['ebay_cron_log']           = $this->url->link('affiliate/dashboard_cron_log', 'token=' . $this->session->data['token'], 'SSL');

		$affiliate_id = $this->affiliate->getId();
		$this->data['stock_control_id']     = $this->config->get($affiliate_id . '_stock_control_id');
		$this->data['usps_shipping_status'] = $this->config->get($affiliate_id . '_usps_shipping_status');

		$this->data['template_url'] = 'catalog/view/theme/' . $this->config->get('config_template');
		$this->template = $this->config->get('config_template') . '/template/affiliate/common/header.tpl';
    	$this->render();
	}

}