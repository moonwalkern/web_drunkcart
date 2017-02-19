<?php  
class ControllerModulevendorbox extends Controller {
	protected function index() {
		$this->language->load('module/vendorbox');
        // echo print_R($this->session->data['product_vendor'],TRUE);           
        $this->data['product_vendors'] = $this->session->data['product_vendor'];
		        
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['code'] = str_replace('http', 'https', html_entity_decode($this->config->get('vendorbox_code')));
		} else {
			$this->data['code'] = html_entity_decode($this->config->get('vendorbox_code'));
		}
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/vendorbox.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/vendorbox.tpl';
		} else {
			$this->template = 'default/template/module/vendorbox.tpl';
		}
		
		$this->render();
	}
}
?>