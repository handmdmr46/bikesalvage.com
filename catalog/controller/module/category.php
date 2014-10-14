<?php  
class ControllerModuleCategory extends Controller {
	protected function index($setting) {
		$this->language->load('module/category');

		$this->data['heading_title'] = $this->language->get('heading_title');

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		if (isset($parts[0])) {
			$this->data['category_id'] = $parts[0];
		} else {
			$this->data['category_id'] = 0;
		}

		if (isset($parts[1])) {
			$this->data['child_id'] = $parts[1];
		} else {
			$this->data['child_id'] = 0;
		}

		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('catalog/manufacturer');

		// updated version
		$this->data['models'] = array();
		$manufacturers = $this->model_catalog_manufacturer->getManufacturers();
		foreach ($manufacturers as $manufacturer) {
			$model_data = array();
			$categories = $this->model_catalog_category->getCategoriesByManufacturerId($manufacturer['manufacturer_id']);
			foreach($categories as $result) {
				$total = $this->model_catalog_product->getTotalProducts(array('filter_category_id' => $result['category_id']));
				// make this changable in admin later
				if($total >= (int)$this->config->get('category_count_minimum_sidebar')) {
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

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/category.tpl';
		} else {
			$this->template = 'default/template/module/category.tpl';
		}

		$this->render();
	}
}
?>