<?php  
class ControllerModuleApps extends Controller {
	protected function index() {
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->load->library('log');
	        $logger = new Log('swapdeal.log');
			//Here we get the post data//
			$logger->write('Apps Service Start');
			$logger->write('Post Data' . print_R($this->request->post),TRUE);
			if($this->request->post['device'] == 'iOS' && $this->request->post['service'] == 'category'){
					
					$logger->write('Device is iOS');
					if ($address_info) {
						$json['data'] = $address_info;
					}else{
						$json['data'] = 'No Data';
					}	
					
					$this->response->setOutput(json_encode($json));
					return;
			}
        	$logger->write('Apps Service End');
		}
		
	}
    
	public function firstaccess() {
		$this->load->model('tool/image');
        $this->load->library('log');
		$logger = new Log('swapdeal.log');
		
		$logger->write('Apps Service Start:firstaccess');
		$logger->write('Get Data ' . print_R($this->request->post,TRUE));
		if ($this->request->server['REQUEST_METHOD'] == 'GET' || $this->request->server['REQUEST_METHOD'] == 'POST') {
			$json['data'] = 'ec2-54-201-107-80.us-west-2.compute.amazonaws.com';
			$logger->write('Apps Service End:firstaccess, '.print_r($json['data'],TRUE));
			$this->response->setOutput(json_encode($json));
		}
		
		$logger->write('Apps Service End:firstaccess');
	}
	
	public function validate() {
		
		$this->load->model('account/customer');
        $this->load->model('tool/image');
        $this->load->library('log');
		$logger = new Log('swapdeal.log');
		
		$logger->write('Apps Service Start');
		//$logger->write('Get Data ' . print_R($this->request->get));
		$customer_info = $this->model_account_customer->activateCustomerUsingOTP($this->request->post);
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
		
	        if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
				$logger->write('User validation failed for the user '. $this->request->post['email'] . ' with the password ' . $this->request->post['password']);
				//$json['data'] = 'User validation failed for the user '. $this->request->post['email'] . ' with the password ' . $this->request->post['password'];
				//$this->response->setOutput(json_encode($json));
				return false;
			}
			$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
			
			if(isset($customer_info)){
				$logger->write('customer data ' . print_R($customer_info,TRUE));
				$json['data'] = $customer_info;
				$this->response->setOutput(json_encode($json));
			}
        
		}else{
			if (!$this->customer->login($this->request->get['email'], $this->request->get['password'])) {
				$logger->write('User validation failed for the user '. $this->request->get['email'] . ' with the password ' . $this->request->get['password']);
				$customer_info = array();
				$customer_info['status'] = '1'; //Customer not found
				$customer_info['message'] = 'User validation failed for the user '. $this->request->get['email'] . ' with the password ' . $this->request->get['password'];
				$json['data'] = $customer_info;
				$this->response->setOutput(json_encode($json));
				return false;
			}
			$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->get['email']);
			
			if(isset($customer_info)){
				$logger->write('customer data ' . print_R($customer_info,TRUE));
				$customer_info['status'] = '0'; //Customer found
				$json['data'] = $customer_info;
				$this->response->setOutput(json_encode($json));
			}
		}
				
		$logger->write('Apps Service End');
	}

	public function authenticateotp() {
		$this->load->model('account/customer');
        $this->load->model('tool/image');
        $this->load->library('log');
		$logger = new Log('swapdeal.log');
		
		$logger->write('Apps Service Start');
		//$authOtpResponse = array();
		$data['transaction'] = "FAILURE";
		$json['data'] = $data;
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if($this->request->post['auth'] == 'FALSE'){
				$logger->write('Post Data Authenticate OTP -:' .print_R($this->request->post,TRUE));
				$authOtpResponse = $this->addcustomer();
				
				$logger->write('Authenticate OTP -:' .print_R($authOtpResponse,TRUE));
							
					if($authOtpResponse){
						$data['transaction'] = "SUCCESS";
						$data['customer'] = $authOtpResponse;
						$json['data'] = $authOtpResponse;		
					}else{
						$data['transaction'] = "FAILURE";
						$json['data'] = $data;
					
						$logger->write('Json Out '. print_r($json,TRUE));
					}
				}else{
					//Validating OTP from post screen
					
					$authOtpResponse = $this->model_account_customer->validateCustomerUsingOTP($this->request->post);
					$logger->write('OTP record found '.print_r($authOtpResponse,TRUE) . 'xxxx');
					$logger->write('OTP isset ' . $authOtpResponse);
					
					if($authOtpResponse != 0){
							$logger->write('OTP record found');
							$data['transaction'] = "SUCCESS";
							$data['customer'] = $authOtpResponse;
							$json['data'] = $data;	
					}else{
						$logger->write('OTP record not found');
						$data['transaction'] = "FAILURE";
						$json['data'] = $data;
					}
					$this->response->setOutput(json_encode($json));	
					return;
				}
		}else{
			$data['transaction'] = "FAILURE, Not a POST";
			$json['data'] = $data;
		}
		$this->response->setOutput(json_encode($json));	
		$logger->write('Apps Service End');
		
	}
	
	
	
	public function addcustomer() {
		
		
		$this->load->model('account/customer');
        $this->load->model('tool/image');
		$this->load->model('catalog/postad');
        $this->load->library('log');
		$logger = new Log('swapdeal.log');
		
		$logger->write('Apps Service Start');
		
		$logger->write('Post Data New User -:' .print_R($this->request->post,TRUE));
		
		//Validating if user exist.
		$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
		$logger->write('Customer Validate Data  -:' .print_R($customer_info, TRUE));
		
		//
		$json = array();
		
		$data['email'] = $this->request->post['email'];
		$data['password'] = $this->request->post['password'];
		$data['firstname'] = $this->request->post['firstname'];
		$data['lastname'] = $this->request->post['lastname'];
		$data['telephone'] = $this->request->post['mobile'];
		$data['govtid'] = $this->request->post['govtid'];
		$data['fax'] = ''; 
	    $data['company'] = ''; 
	    $data['customer_group_id'] = 1;
		$data['checkout'] = 0;
	    $data['company_id'] = '';
	    $data['tax_id'] = '';
	    $data['address_1'] = 'flat';
	    $data['address_2'] = '';
	    $data['city'] = 'pune';
	    $data['postcode'] = '411014';
	    $data['country_id'] = 99;
	    $data['zone_id'] = ''; 
	    $data['states_id'] = 1493;
	    $data['locality_id'] = 846;
	    $data['newsletter'] = '0';
	    $data['agree'] = 1;
	    $data['mail_template'] = '/Users/sreejigopal/Documents/DrunkCart.com/MyDrunkCart/catalog/view/theme/default/template/mail/expresstemplatenewuser.tpl';
		$data['mail_template_otp'] = '/Users/sreejigopal/Documents/DrunkCart.com/MyDrunkCart/catalog/view/theme/default/template/mail/expresstemplateotp.tpl';
	    $data['style_sheet'] = 'catalog/view/theme/default/stylesheet/email.css';
	    $data['logo'] = 'image/data/Drunkcart-Logo-small.jpg';
		
		$logger->write('Customer Data  -:' .print_R($data, TRUE));
		if($customer_info){
			$customerId = $customer_info['customer_id'];
			$logger->write('Customer Data from table -:' .print_R($customer_info, TRUE));
			$logger->write('Customer ID -:' .$customerId);
			$logger->write('Updating password');
			$data['password'] = "password";
			$this->model_account_customer->editPassword($customer_info['email'], "password");
			$logger->write('Updating password done');
			
			
			$this->language->load('mail/customer');
			$logger->write('mail/customer done');
			$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
			
			$data['subject'] = $subject;
	        $otp =  $this->model_catalog_postad->RAND_OTP(100000,999999);
			$data['sms_content'] = $this->model_catalog_postad->OTP_Content($otp);
			$data['otp'] = $otp;
			$data['customer_id'] = $customerId;
			
			$logger->write('customer::insert new otp then sent mail'.print_R($data,TRUE));
			
			$this->model_catalog_postad->senteMail_otp($data);
			
	        //$this->model_catalog_postad->senteMail_newuser($data);
			
			//$this->activateCustomerUsingOTP($data);
		}else{
			$customerId = $this->model_account_customer->addCustomer($data);
		}
		$tempdata['customer'] = $data; 
		$data = $this->buildemaildata($this->request->post); 
		$data['customer_id'] = $customerId;
		$data['customer'] = $tempdata;
		$json['data'] = $data;
		//$this->response->setOutput(json_encode($json));
		$logger->write('customer::data '.print_R($json,TRUE));
		$logger->write('Returning back');
		return $json;		
	}
	
	public function buildemaildata($data){
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/expresstemplatenewuser.tpl')) {
			$this->template = DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/expresstemplatenewuser.tpl';
		} else {
			$this->template = 'default/template//mail/expresstemplatenewuser.tpl';
		}
        
        $data['mail_template'] = $this->template;
        
        if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/style.css')) {
			$this->style = 'catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/email.css';
		} else {
            $this->style = 'catalog/view/theme/default/stylesheet/email.css';
		}
        
        $data['style_sheet'] = $this->style;
        
        //image/data/samsung_logo.jpg
        if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] =  'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
        }
 		$data['username'] = $this->request->post['firstname'];
        $data['email'] = $this->request->post['email']; //email id of the interested buyer/seller
        $data['mobile'] = $this->request->post['mobile']; //mobile of the interested buyer/seller
        
        return $data;
    }
	
	public function fetchotpmessage(){
		$this->load->model('account/customer');
        $this->load->model('tool/image');
		$this->load->model('catalog/postad');
        $this->load->library('log');
		$logger = new Log('swapdeal.log');
		
		$logger->write('Apps Service Start');
		
		$logger->write('Post Data New User -:' .print_R($this->request->post,TRUE));
		
		//Validating if user exist.
		$customer_notification_infos = $this->model_account_customer->fetchCustomerOTP($this->request->post);
		$logger->write('Notificaitons ' . print_R($customer_notification_infos,TRUE));
		
		
		// foreach ($customer_notification_infos as $customer_notification_info){
// 			
// 			
// 			
		// }
		if($customer_notification_infos == null)
			return null;
		else
			$this->response->setOutput(json_encode($customer_notification_infos));
		//return ;
	}
	
	
	public function removeotpmessage(){
		$this->load->model('account/customer');
        $this->load->model('tool/image');
		$this->load->model('catalog/postad');
        $this->load->library('log');
		$logger = new Log('swapdeal.log');
		
		$logger->write('Apps Service Start');
		
		$logger->write('Post Data New User -:' .print_R($this->request->post,TRUE));
		
		$remove_lists = $this->request->post;
		
		foreach ($remove_lists as $remove_list){
			$logger->write('Post Data New User -:' .print_R($remove_list,TRUE));
			$customer_notification_remove = $this->model_account_customer->removeCustomerOTP($remove_list);
		}	
		
		//Validating if user exist.
		//$remove_customer_notification_list = $this->model_account_customer->fetchCustomerOTP($this->request->post);
		//$logger->write('Notificaitons ' . print_R($customer_notification_infos,TRUE));
		
		
		// foreach ($customer_notification_infos as $customer_notification_info){
// 			
// 			
// 			
		// }
		
		$this->response->setOutput(json_encode($customer_notification_infos));
		//return ;
	}
	
	
	public function customeradd(){
		$this->load->model('tool/image');
        $this->load->library('log');
		$logger = new Log('swapdeal.log');
		$json['data'] = 'Yes';
		$this->response->setOutput(json_encode($json));
        $logger->write('categorysearch/cache - end');
	}
	
    public function loadcategory(){
        $this->load->model('catalog/category');
		$this->load->model('account/customer');
        $this->load->model('tool/image');
        $this->load->library('log');
		$logger = new Log('swapdeal.log');
       
		$logger->write('categorysearch/cache - started');
        $logger->write('Apps Service Start');
        $category_data = $this->cache->get('category_data');
		$logger->write('Post Data' . print_R($this->request->post,TRUE));
		$logger->write('category_data ' . print_R($category_data,TRUE));
        if(!$category_data){
            $logger->write('categorysearch/cache - No Cache data for category_data, so cache it back');
            $category_data = $this->model_catalog_category->getCategories();
			$this->cache->set('category_data', $category_data);
            //$logger->write('category_data ' . $category_data);
        }
       
        
    	$this->data['categories_all'] = $this->cache->get('category_all_data');//This has all the categories and subcategories.
        
        //if(!$this->data['categories_all']){
            $this->data['categories_all'] = array();
            foreach ($category_data as $category){
                
                $this->data['categories_all'][] = array(
                'category_id' =>  $category['category_id'],
                'image' => $category['image'],
                'parent_id' => $category['parent_id'],
                'top' => $category['top'],
                'column' => $category['column'],
                'status' => $category['sort_order'],
                'date_added' => $category['date_added'],
                'date_modified' => $category['date_modified'],
                'language_id' => $category['language_id'],
                'name' => $category['name'],
                'description' => $category['description'],
                'meta_description' => $category['meta_description'],
                'meta_keyword' => $category['meta_keyword'], 
                'store_id' => $category['store_id'],
                'thumb' => $this->model_tool_image->resize($category['image'], $this->config->get('config_image_category_search_width'), $this->config->get('config_image_category_search_height')),
                'imagesmall' => $this->model_tool_image->image_url_real($category['image_small']),
                'subcategories' => $this->model_catalog_category->getCategories($category['category_id']),
                'href'      => $this->url->link('product/subcategory')
                );
            }
        	$this->cache->set('category_all_data', $this->data['categories_all']);
            $logger->write('categorysearch/cache - No Cache data for category_all_data, so cache it back');
			
			if($this->request->post['device'] == 'iOS'){
				$this->load->library('log');
        		$logger = new Log('swapdeal.log');
				$logger->write('Post Data ' . $this->request->post['device']);
				$logger->write('Device is iOS****');
				if ($category_data) {
					$json['data'] = $category_data;
					$logger->write('Data sent, from apps');
				}else{
					$json['data'] = 'No Data';
					$logger->write('Data Not Sent');
				}	
				
				$this->response->setOutput(json_encode($json));
				
			}
        //}
        $logger->write('categorysearch/cache - end');
		
	}
	
	public function getsubcategory() {
		$this->load->library('log');
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$logger = new Log('swapdeal.log');
        $logger->write('subcategory/cache - started');
        if(isset($this->request->get['category_id'])){
            $category_id = $this->request->get['category_id'];
        }else{
            return;
        }
        $category_data = $this->cache->get('category_data');
		$logger->write('Post Data' . print_R($this->request->post,TRUE));
		//$logger->write('category_data ' . print_R($category_data,TRUE));
        if(!$category_data){
            $logger->write('categorysearch/cache - No Cache data for category_data, so cache it back');
            $category_data = $this->model_catalog_category->getCategories();
			$this->cache->set('category_data', $category_data);
            //$logger->write('category_data ' . $category_data);
        }
        $json = array();
        $this->data['categories_all'] = $this->cache->get('category_all_data');//This has all the categories and subcategories.
        if(!$this->data['categories_all'])
        	$this->loadcategory();
		else
			 $logger->write('cache avaliable');
        $image = "";
		
		$this->data['products'] = array();
		$subcategory_id = 17;
		$filter = "";
		$sort = "p.sort_order";
		$order = "ASC";
		$start = 0;
		$limit = 15;
		
		$data = array(
			'filter_category_id' => $subcategory_id,
			'filter_filter'      => $filter, 
			'sort'               => $sort,
			'order'              => $order,
			'start'              => $start,
			'limit'              => $limit
		);
		$subcategory_data = array();
		$logger->write('Data ' . print_R($data,TRUE));
        foreach ($this->data['categories_all'] as $category){
        	
            if($category_id == $category['category_id']){
            	
                foreach($category['subcategories'] as $subcategory){
                    $subsubcategory = $this->model_catalog_category->getCategories($subcategory['category_id']);
                    
                    if(empty($subsubcategory)){
                        $subsubcategory_all = 0;
						$subcount = 0;                    
                    }else{
                        $subsubcategory_all = array();
						$subcount = 1;
                        foreach($subsubcategory as $subcategory_array){
                            //array_push($subcategory_array, 'href' = 'ssss');
                            $subcategory_array['href'] = $this->url->link('product/category', 'path=' . $subcategory['category_id'] . '_' . $subcategory_array['category_id']);
                            $subsubcategory_all[] = $subcategory_array;
                        }
                       
                        //$subsubcategory[] = $this->url->link('product/category', 'path=' . $subcategory['category_id'] . '_' . $subcategory['category_id']);
                    }
                    if($image == ""){
                        
                        $image = $category['imagesmall'];
                        
                    }
					
					$data = array(
						'filter_category_id' => $subcategory['category_id'],
						'filter_filter'      => $filter, 
						'sort'               => $sort,
						'order'              => $order,
						'start'              => $start,
						'limit'              => $limit,
						'filter_vendor_id'   => 1
					);
					
					$location_id = 0;
					$results = $this->model_catalog_product->getProductsArray($data,$location_id);
					//$logger->write('product ' .$subcategory['category_id'] . '---'. $subcategory['name'] . '-------' . print_R($results,TRUE));
					if($results){
						$subcategory_data[] = array(
						'subcategory_id' => $subcategory['category_id'],
						'subcategory_name' => $subcategory['name'],
						'subcategories' => $results
					);	
					}
					
					
                    
                }
					// $subcategory_data[][] = array(
                        // 'subcategory' =>  $subsubcategory_all,
                        // 'subcategory_name' => $subcategory['name'],
                        // 'subcategory_id' => $subcategory['category_id'],
                        // 'image' =>$subcategory['image'],
                        // 'category_name' => $category['name'],
                        // 'category_id' => $category['category_id'],
                        // 'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $subcategory['category_id']),
                        // 'imagesmall' => $image,
                        // 'subcount' => $subcount
                    // );
					 $logger->write('$subcategory_data ' . print_R($subcategory_data,TRUE));
            }
            
            
        } 
       array_multisort($json, SORT_REGULAR);
	   
	   if($this->request->post['device'] == 'iOS'){
				$this->load->library('log');
        		$logger = new Log('swapdeal.log');
				$logger->write('Post Data ' . $this->request->post['device']);
				$logger->write('Device is iOS****');
				if ($subcategory_data) {
					$json['data'] = $subcategory_data;
					$logger->write('Data sent, from apps');
				}else{
					$json['data'] = 'No Data';
					$logger->write('Data Not Sent');
				}	
				
				$this->response->setOutput(json_encode($json));
				
			}
    }
	
	public function getsubcategoryall() {
		$this->load->library('log');
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$logger = new Log('swapdeal.log');
        $logger->write('subcategory/cache - started');
        if(isset($this->request->get['category_id'])){
            $category_id = $this->request->get['category_id'];
        }else{
            return;
        }
        $category_data = $this->cache->get('category_data');
		$logger->write('Post Data' . print_R($this->request->post,TRUE));
		//$logger->write('category_data ' . print_R($category_data,TRUE));
        if(!$category_data){
            $logger->write('categorysearch/cache - No Cache data for category_data, so cache it back');
            $category_data = $this->model_catalog_category->getCategories();
			$this->cache->set('category_data', $category_data);
            //$logger->write('category_data ' . $category_data);
        }
        $json = array();
        $this->data['categories_all'] = $this->cache->get('category_all_data');//This has all the categories and subcategories.
        if(!$this->data['categories_all'])
        	$this->loadcategory();
		else
			 $logger->write('cache avaliable');
        $image = "";
		
		$this->data['products'] = array();
		$subcategory_id = 17;
		$filter = "";
		$sort = "p.sort_order";
		$order = "ASC";
		$start = 0;
		$limit = 15;
		
		$data = array(
			'filter_category_id' => $subcategory_id,
			'filter_filter'      => $filter, 
			'sort'               => $sort,
			'order'              => $order,
			'start'              => $start,
			'limit'              => $limit
		);
		$subcategory_data = array();
		$logger->write('Data ' . print_R($data,TRUE));
        foreach ($this->data['categories_all'] as $category){
        	
            if($category_id == $category['category_id']){
            	
                foreach($category['subcategories'] as $subcategory){
                    $subsubcategory = $this->model_catalog_category->getCategories($subcategory['category_id']);
                    
                    if(empty($subsubcategory)){
                        $subsubcategory_all = 0;
						$subcount = 0;                    
                    }else{
                        $subsubcategory_all = array();
						$subcount = 1;
                        foreach($subsubcategory as $subcategory_array){
                            //array_push($subcategory_array, 'href' = 'ssss');
                            $subcategory_array['href'] = $this->url->link('product/category', 'path=' . $subcategory['category_id'] . '_' . $subcategory_array['category_id']);
                            $subsubcategory_all[] = $subcategory_array;
                        }
                       
                        //$subsubcategory[] = $this->url->link('product/category', 'path=' . $subcategory['category_id'] . '_' . $subcategory['category_id']);
                    }
                    if($image == ""){
                        
                        $image = $category['imagesmall'];
                        
                    }
					
					$data = array(
						'filter_category_id' => $subcategory['category_id'],
						'filter_filter'      => $filter, 
						'sort'               => $sort,
						'order'              => $order,
						'start'              => $start,
						'limit'              => $limit,
						'filter_vendor_id'   => 1
					);
					
					$location_id = 0;
					$results = $this->model_catalog_product->getProducts($data,$location_id);
					$logger->write('product ' .$subcategory['category_id'] . '---'. $subcategory['name'] . '-------' . print_R($results,TRUE));
                    $subcategory_data[][] = array(
                        'subcategory' =>  $subsubcategory_all,
                        'subcategory_name' => $subcategory['name'],
                        'subcategory_id' => $subcategory['category_id'],
                        'image' =>$subcategory['image'],
                        'category_name' => $category['name'],
                        'category_id' => $category['category_id'],
                        'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $subcategory['category_id']),
                        'imagesmall' => $image,
                        'subcount' => $subcount
                    );
					$logger->write('$subcategory_data ' . print_R($subcategory_data,TRUE));
                }
            }
            
            
        } 
       array_multisort($json, SORT_REGULAR);
	   
	   if($this->request->post['device'] == 'iOS'){
				$this->load->library('log');
        		$logger = new Log('swapdeal.log');
				$logger->write('Post Data ' . $this->request->post['device']);
				$logger->write('Device is iOS****');
				if ($subcategory_data) {
					$json['data'] = $subcategory_data;
					$logger->write('Data sent, from apps');
				}else{
					$json['data'] = 'No Data';
					$logger->write('Data Not Sent');
				}	
				
				$this->response->setOutput(json_encode($json));
				
			}
    }
}
?>