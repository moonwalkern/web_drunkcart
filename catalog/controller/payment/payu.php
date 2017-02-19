<?php
class ControllerPaymentPayu extends Controller {

	public function index() {	
    	$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->load->model('checkout/order');
		$this->language->load('payment/payu');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$this->data['merchant'] = $this->config->get('payu_merchant');
		
		 /////////////////////////////////////Start Payu Vital  Information /////////////////////////////////
		
		if($this->config->get('payu_test')=='demo')
			$this->data['action'] = 'https://test.payu.in/_payment.php';
		else
		    $this->data['action'] = 'https://secure.payu.in/_payment.php';
			
		$txnid        = 	$this->session->data['order_id'];

		             
		$this->data['key'] = $this->config->get('payu_merchant');
		$this->data['salt'] = $this->config->get('payu_salt');
		$this->data['txnid'] = $txnid;
		$this->data['amount'] = (int)$order_info['total'];
		$this->data['productinfo'] = 'opencart products information';
		$this->data['firstname'] = $order_info['payment_firstname'];
		$this->data['Lastname'] = $order_info['payment_lastname'];
		$this->data['Zipcode'] = $order_info['payment_postcode'];
		$this->data['email'] = $order_info['email'];
		$this->data['phone'] = $order_info['telephone'];
		$this->data['address1'] = $order_info['payment_address_1'];
        $this->data['address2'] = $order_info['payment_address_2'];
        $this->data['state'] = $order_info['payment_zone'];
        $this->data['city']=$order_info['payment_city'];
        $this->data['country']=$order_info['payment_country'];
		$this->data['Pg'] = 'CC';
		$this->data['surl'] = $this->url->link('payment/payu/callback');//HTTP_SERVER.'/index.php?route=payment/payu/callback';
		$this->data['Furl'] = $this->url->link('payment/payu/callback');//HTTP_SERVER.'/index.php?route=payment/payu/callback';
	  //$this->data['surl'] = $this->url->link('checkout/success');//HTTP_SERVER.'/index.php?route=payment/payu/callback';
      //$this->data['furl'] = $this->url->link('checkout/cart');//HTTP_SERVER.'/index.php?route=payment/payu/callback';
		$this->data['curl'] = $this->url->link('payment/payu/callback');
		$key          =  $this->config->get('payu_merchant');
		$amount       = (int)$order_info['total'];
		$productInfo  = $this->data['productinfo'];
	    $firstname    = $order_info['payment_firstname'];
		$email        = $order_info['email'];
		$salt         = $this->config->get('payu_salt');
		$Hash=hash('sha512', $key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'|||||||||||'.$salt); 
		$this->data['user_credentials'] = $this->data['key'].':'.$this->data['email'];
		$this->data['Hash'] = $Hash;
		$service_provider = 'payu_paisa';
		$this->data['service_provider'] = $service_provider;
					/////////////////////////////////////End Payu Vital  Information /////////////////////////////////
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/payu.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/payu.tpl';
		} else {
			$this->template = 'default/template/payment/payu.tpl';
		}	
		
		$this->render();	
		
		
		
	}
	
	public function callback() {
	    $this->load->library('log');
        $logger = new Log('swapdeal.log');
		$logger->write(print_R($this->request->post['key'],TRUE));
        $logger->write(print_R($this->config->get('payu_merchant'),TRUE));
		if (isset($this->request->post['key']) && ($this->request->post['key'] == $this->config->get('payu_merchant'))) {
			$this->language->load('payment/payu');
			$logger->write('Inside If');
			$this->data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

			if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
				$this->data['base'] = HTTP_SERVER;
			} else {
				$this->data['base'] = HTTPS_SERVER;
			}
		
			$this->data['charset'] = $this->language->get('charset');
			$this->data['language'] = $this->language->get('code');
			$this->data['direction'] = $this->language->get('direction');
			$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
			$this->data['text_response'] = $this->language->get('text_response');
			$this->data['text_success'] = $this->language->get('text_success');
			$this->data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), $this->url->link('checkout/success'));
			$this->data['text_failure'] = $this->language->get('text_failure');
			$this->data['text_cancelled'] = $this->language->get('text_cancelled');
			$this->data['text_cancelled_wait'] = sprintf($this->language->get('text_cancelled_wait'), $this->url->link('checkout/cart'));
			$this->data['text_pending'] = $this->language->get('text_pending');
			$this->data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/cart'));
			
			 $this->load->model('checkout/order');
			 $orderid = $this->request->post['txnid'];
			 $order_info = $this->model_checkout_order->getOrder($orderid);
			 
			 
				$key          		=  	$this->request->post['key'];
				$amount      		= 	$this->request->post['amount'];
				$productInfo  		= 	$this->request->post['productinfo'];
				$firstname    		= 	$this->request->post['firstname'];
				$email        		=	$this->request->post['email'];
				$salt        		= 	$this->config->get('payu_salt');
				$txnid		 		=   $this->request->post['txnid'];
				$keyString 	  		=  	$key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'||||||||||';
				$keyArray 	  		= 	explode("|",$keyString);
				$reverseKeyArray 	= 	array_reverse($keyArray);
				$reverseKeyString	=	implode("|",$reverseKeyArray);
			 
			 
			 if (isset($this->request->post['status']) && $this->request->post['status'] == 'success') {
                $logger->write('Status-:'.$this->request->post['status']);
			 	$saltString     = $salt.'|'.$this->request->post['status'].'|'.$reverseKeyString;
				$sentHashString = strtolower(hash('sha512', $saltString));
			 	$responseHashString=$this->request->post['hash'];
				
				$order_id = $this->request->post['txnid'];
				$message = '';
				$message .= 'orderId: ' . $this->request->post['txnid'] . "\n";
				$message .= 'Transaction Id: ' . $this->request->post['mihpayid'] . "\n";
				foreach($this->request->post as $k => $val){
					$message .= $k.': ' . $val . "\n";
				}
                $logger->write('Status-:'.print_R($message,TRUE));
                $logger->write('sentHashString-:'.$sentHashString);
                $logger->write('post-:'.$this->request->post['hash']);
					if($sentHashString==$this->request->post['hash']){
					   $logger->write('Inside If');
					        $this->model_checkout_order->confirm($this->request->post['txnid'], $this->config->get('payu_order_status_id'));
							$this->model_checkout_order->update($this->request->post['txnid'], $this->config->get('payu_order_status_id'), $message, false);
                            
							$this->data['continue'] = $this->url->link('checkout/success');
							
							if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/payu_success.tpl')) {
								$this->template = $this->config->get('config_template') . '/template/payment/payu_success.tpl';
							} else {
								$this->template = 'default/template/payment/payu_success.tpl';
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
			 
			 
			 }else {
			     $logger->write('else');
    			$this->data['continue'] = $this->url->link('checkout/cart');
				

		        if(isset($this->request->post['status']) && $this->request->post['unmappedstatus'] == 'userCancelled')
				{
			
				 if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/payu_cancelled.tpl')) {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/payu_cancelled.tpl', $this->data));
				} else {
				    $this->response->setOutput($this->load->view('default/template/payment/payu_cancelled.tpl', $this->data));
				}
				}
				else {
                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/payu_pending.tpl')) {
								$this->template = $this->config->get('config_template') . '/template/payment/payu_pending.tpl';
							} else {
								$this->template = 'default/template/payment/payu_pending.tpl';
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
		}
	}
}
?>