<?php 
class ControllerAffiliateCommonFooter extends Controller {
	
	public function index() {
		$this->language->load('affiliate/dashboard');
		$this->template = $this->config->get('config_template') . '/template/affiliate/common/footer.tpl';
    	$this->render();
		
	}
	
}