<?php   
class ControllerCommonHeader extends Controller {
	protected function index() {
		$this->data['title'] = $this->document->getTitle();

		// blog
		$this->document->addScript('catalog/view/javascript/jquery/extras/blog.js');
			
		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/magic.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/magic.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/magic.css');
		}

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (isset($this->session->data['error']) && !empty($this->session->data['error'])) {
			$this->data['error'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$this->data['error'] = '';
		}

		$this->data['base'] = $server;
		$this->data['description'] = $this->document->getDescription();
		$this->data['keywords'] = $this->document->getKeywords();
		$this->data['links'] = $this->document->getLinks();	 
		$this->data['styles'] = $this->document->getStyles();
		$this->data['scripts'] = $this->document->getScripts();
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		$this->data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
		$this->data['name'] = $this->config->get('config_name');

		// blog
		$this->data['store']              = $this->config->get('config_name');
		$this->data['address']            = nl2br($this->config->get('config_address'));
		$this->data['telephone']          = $this->config->get('config_telephone');
		$this->data['fax']                = $this->config->get('config_fax');
		$this->data['google_base_status'] = $this->config->get('google_base_status');
		$this->data['blog_feed_status']   = $this->config->get('blog_feed_status');
		$this->data['product_feed']       = $this->url->link('feed/google_base');
		$this->data['blog_feed']          = $this->url->link('extras/blog_feed');

		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = $server . 'image/' . $this->config->get('config_icon');
		} else {
			$this->data['icon'] = '';
		}

		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$this->data['logo'] = '';
		}		

		$this->language->load('common/header');
		$this->language->load('common/footer');
		// Language
		$this->data['text_home'] = $this->language->get('text_home');
		$this->data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		$this->data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
		$this->data['text_search'] = $this->language->get('text_search');
		$this->data['text_welcome'] = sprintf($this->language->get('text_welcome'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
		$this->data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));
		$this->data['text_account'] = $this->language->get('text_account');
		$this->data['text_checkout'] = $this->language->get('text_checkout');
		$this->data['text_blog'] = $this->language->get('text_blog');
		$this->data['text_parts_search'] = $this->language->get('text_parts_search');
		$this->data['text_affiliates'] = $this->language->get('text_affiliates');

		$this->data['home'] = $this->url->link('common/home');
		$this->data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['account'] = $this->url->link('account/account', '', 'SSL');
		$this->data['shopping_cart'] = $this->url->link('checkout/cart');
		$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
		$this->data['text_information'] = $this->language->get('text_information');
		$this->data['text_service'] = $this->language->get('text_service');
		$this->data['text_extra'] = $this->language->get('text_extra');
		$this->data['text_contact'] = $this->language->get('text_contact');
		$this->data['text_return'] = $this->language->get('text_return');
		$this->data['text_sitemap'] = $this->language->get('text_sitemap');
		$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$this->data['text_voucher'] = $this->language->get('text_voucher');
		$this->data['text_affiliate'] = $this->language->get('text_affiliate');
		$this->data['text_special'] = $this->language->get('text_special');
		$this->data['text_account'] = $this->language->get('text_account');
		$this->data['text_order'] = $this->language->get('text_order');
		$this->data['text_wishlist'] = $this->language->get('text_wishlist');
		$this->data['text_newsletter'] = $this->language->get('text_newsletter');
		$this->data['text_manufacturers'] = $this->language->get('text_manufacturers');
		$this->data['text_models'] = $this->language->get('text_models');
		$this->data['text_see_all_models'] = $this->language->get('text_see_all_models');
		$this->data['text_see_all_manufacturers'] = $this->language->get('text_see_all_manufacturers');
		//blog
		$this->data['text_blog'] = $this->language->get('text_blog');
        $this->data['text_product_rss'] = $this->language->get('text_product_rss');
        $this->data['text_blog_rss'] = $this->language->get('text_blog_rss');
        $this->data['text_contact'] = $this->language->get('text_contact');
        $this->data['text_telephone'] = $this->language->get('text_telephone');
        $this->data['text_fax'] = $this->language->get('text_fax');
        // responsive header
        $this->data['text_menu'] = $this->language->get('text_menu');

        if (isset($this->request->get['blogpath'])) {
		  $parts = explode('_', (string)$this->request->get['blogpath']);
		  $this->data['blogpath'] = $parts[0];
		} else {
		  $this->data['blogpath'] = 'home';
		}

		// Daniel's robot detector
		$status = true;

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$robots = explode("\n", trim($this->config->get('config_robots')));

			foreach ($robots as $robot) {
				if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
					$status = false;

					break;
				}
			}
		}

		// A dirty hack to try to set a cookie for the multi-store feature
		$this->load->model('setting/store');

		$this->data['stores'] = array();
		if ($this->config->get('config_shared') && $status) {
			$this->data['stores'][] = $server . 'catalog/view/javascript/crossdomain.php?session_id=' . $this->session->getId();

			$stores = $this->model_setting_store->getStores();

			foreach ($stores as $store) {
				$this->data['stores'][] = $store['url'] . 'catalog/view/javascript/crossdomain.php?session_id=' . $this->session->getId();
			}
		}

		// Search		
		if (isset($this->request->get['search'])) {
			$this->data['search'] = $this->request->get['search'];
		} else {
			$this->data['search'] = '';
		}

		// Menu
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('catalog/information');
		$this->load->model('catalog/manufacturer');

		// models
		$this->data['models'] = array();
		$manufacturers = $this->model_catalog_manufacturer->getManufacturers();
		foreach ($manufacturers as $manufacturer) {
			$model_data = array();
			$categories = $this->model_catalog_category->getCategoriesByManufacturerId($manufacturer['manufacturer_id']);
			foreach($categories as $result) {				
				$total = $this->model_catalog_product->getTotalProducts(array('filter_category_id' => $result['category_id']));				
				if($total > 0) {
					$model_data[] = array(
						'name' => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $total . ')' : ''),
						'href' => $this->url->link('product/category', 'path=' . $result['category_id'])
					);
				}
			}
			$this->data['models'][] = array(
				'manufacturer' => $manufacturer['name'],
				'model_data'   => $model_data
			);
		}

		// information
		$this->data['informations'] = array();
		$information = $this->model_catalog_information->getInformations();
		foreach ($information as $result) {
			if ($result) {
				$this->data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}

		// manufacturers
		$manufacturers = $this->model_catalog_manufacturer->getManufacturers();
		foreach($manufacturers as $result) {
			if ($result) {
				$this->data['manufacturers'][] = array(
					'title' => $result['name'],
					'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id'])
				);
			}
		}
		
		
		// others
		$this->data['home'] = $this->url->link('common/home');
		$this->data['all_manufacturers'] = $this->url->link('product/manufacturer');
		$this->data['parts_search'] = $this->url->link('product/search');
		$this->data['affiliates'] = $this->url->link('affiliate/dashboard');
		$this->data['blog'] = $this->url->link('extras/blog');
		$this->data['contact'] = $this->url->link('information/contact');
		$this->data['return'] = $this->url->link('account/return/insert', '', 'SSL');
		$this->data['sitemap'] = $this->url->link('information/sitemap');

		// extras
		$this->data['manufacturer'] = $this->url->link('product/manufacturer');
		$this->data['voucher'] = $this->url->link('account/voucher', '', 'SSL');
		$this->data['affiliate'] = $this->url->link('affiliate/account', '', 'SSL');
		$this->data['special'] = $this->url->link('product/special');

		$this->children = array(
			'module/language',
			'module/currency',
			'module/cart'
		);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/header.tpl';
		} else {
			$this->template = 'default/template/common/header.tpl';
		}

		$this->render();
	} 	

}// end class
?>
