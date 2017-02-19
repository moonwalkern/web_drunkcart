<?php 
class ControllerAccountPostadsuccess extends Controller {  
	public function index() {
        
		$this->language->load('account/postadsuccess');

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

		$this->load->model('account/customer_group');

		$customer_group = $this->model_account_customer_group->getCustomerGroup($this->customer->getCustomerGroupId());
        $this->data['username'] = $this->session->data['email'];
        $this->data['password'] = $this->session->data['password'];
   
		if ($customer_group && !$customer_group['approval']) {
            if($this->session->data['existing_customer'] == 'true'){
                unset($this->session->data['existing_customer']);
                $this->data['text_message'] = sprintf($this->language->get('text_message_account'),$this->data['username'],$this->url->link('account/forgotten', '', 'SSL'), $this->url->link('information/contact'));
            }else{
                $this->data['text_message'] = sprintf($this->language->get('text_message'), $this->url->link('information/contact'));
            }
		} else {
		    if($this->session->data['existing_customer'] == 'true'){
                unset($this->session->data['existing_customer']);
                $this->data['text_message'] = sprintf($this->language->get('text_message_account'),$this->data['username'],$this->url->link('account/forgotten', '', 'SSL'), $this->url->link('information/contact'));
            }else{
                $this->data['text_message'] = sprintf($this->language->get('text_approval'), $this->config->get('config_name'), $this->url->link('information/contact'));
            }  
		  	
		}
        unset($this->session->data['email']);
        unset($this->session->data['password']);
        $this->data['text_message_ad'] =  $this->language->get('text_message_ad');
        
		$this->data['button_continue'] = $this->language->get('button_continue');

		if ($this->cart->hasProducts()) {
			$this->data['continue'] = $this->url->link('checkout/cart', '', 'SSL');
		} else {
			$this->data['continue'] = $this->url->link('account/account', '', 'SSL');
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/postadsuccess.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/postadsuccess.tpl';
		} else {
			$this->template = 'default/template/common/success.tpl';
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