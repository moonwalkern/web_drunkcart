<?php 
class ControllerModulePostlink extends Controller {
	public function index() {
		
		
        $this->data['text_postad'] = $this->language->get('text_postad');
        $this->data['postad'] = $this->url->link('product/postad', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/postlink.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/postlink.tpl';
		} else {
			$this->template = 'default/template/module/postlink.tpl';
		}

		$this->response->setOutput($this->render());		
	}
}
?>