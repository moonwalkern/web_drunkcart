<?php 
class ControllerAccountUpdateadsuccess extends Controller {  
	public function index() {
        
		$this->language->load('account/updateadsuccess');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),       	
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_success'),
			'href'      => $this->url->link('account/success'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['heading_title'] = $this->language->get('heading_title');

		
        $this->data['text_message_ad'] =  $this->language->get('text_message_ad');
        
		$this->data['button_continue'] = $this->language->get('button_continue');

		if ($this->cart->hasProducts()) {
			$this->data['continue'] = $this->url->link('checkout/cart', '', 'SSL');
		} else {
			$this->data['continue'] = $this->url->link('account/account', '', 'SSL');
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/updateadsuccess.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/updateadsuccess.tpl';
		} else {
			$this->template = 'default/template/common/updateadsuccess.tpl';
		}

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);

		$this->response->setOutput($this->render());				
	}
}
?>