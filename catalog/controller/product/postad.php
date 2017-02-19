<?php 
class ControllerProductPostad extends Controller {
    private $error = array();
    
    public function index() {
        
        $this->language->load('product/postad');
        $this->document->setTitle($this->language->get('heading_title'));
       	$this->load->model('tool/image');
        $this->load->model('catalog/product');
        $this->load->model('catalog/postad');
        $this->load->model('catalog/manufacturer');
        
		$this->document->addScript('catalog/view/javascript/filereader.js');
        $this->document->addScript('catalog/view/javascript/script.js');
        
		$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
        
		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/style.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/style.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/style.css');
		}
        
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            //echo "add";
            $this->insert($this->request->post);
            //echo print_R($this->request->post,TRUE);
            //echo print_R($this->data, TRUE);
               
            $product_id = $this->model_catalog_postad->addProduct($this->request->post,$this->data);
            
            //once the product is added, we need to create a new user of the user is not existing.
            
            $this->load->model('account/customer');
            
            
            
            $customer = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
            
            
            
            if(empty($customer)){
                
                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/expresstemplatenewuser.tpl')) {
        			$this->template = DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/expresstemplatenewuser.tpl';
        		} else {
        			$this->template = 'default/template/mail/expresstemplatenewuser.tpl';
        		}
                
                $this->data['mail_template'] = $this->template;
                
                if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/style.css')) {
        			$this->style = 'catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/email.css';
        		} else {
                    $this->style = 'catalog/view/theme/default/stylesheet/email.css';
        		}
                
                $this->data['style_sheet'] = $this->style;
                
                //image/data/samsung_logo.jpg
                if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
        			$this->data['logo'] =  'image/' . $this->config->get('config_logo');
        		} else {
        			$this->data['logo'] = '';
                }
         		$this->data['username'] = $this->request->post['email'];
                $this->data['email'] = $this->request->post['email']; //email id of the interested buyer/seller
                $this->data['mobile'] = $this->request->post['mobile']; //mobile of the interested buyer/seller
                
                
                
                if($this->data['firstname'])
                
                $customer_id = $this->model_account_customer->addCustomer($this->data);
                echo 'The customer id is ->'.$customer_id;
                
                $this->session->data['existing_customer'] = 'false';
            }
            else{
                $customer_id = $customer['customer_id'];
                echo 'Customer Found, customer_id->' . $customer_id;
                $this->session->data['existing_customer'] = 'true';
            }
            $this->data['product_id'] = $product_id;
            $this->data['customer_id'] = $customer_id;
            $this->model_catalog_postad->addProductToCustomer($this->data);
            $this->session->data['email'] = $this->data['email'];
            $this->session->data['password'] = $this->data['password'];
            $this->customer->login($this->request->post['email'], $this->data['password']);

			unset($this->session->data['guest']);
            $this->redirect($this->url->link('account/postadsuccess'));
            //die;
            
        }
        
        $this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
			'separator' => false
		);
        
        $this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_yourad'),
			'href'      => $this->url->link('product/postad', '', 'SSL'),      	
			'separator' => $this->language->get('text_separator')
		);
        
        
        
        //Here we are setting a random token for sesssion validation
        $this->session->data['token'] = md5(mt_rand());
        $this->data['action'] = $this->url->link('product/postad', '', 'SSL');
    
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['text_your_ad_pic'] = $this->language->get('text_your_ad_pic');
        $this->data['text_your_ad_details'] = $this->language->get('text_your_ad_details');
        $this->data['text_describe_your_ad'] = $this->language->get('text_describe_your_ad');
        $this->data['text_classify_your_ad'] = $this->language->get('text_classify_your_ad');
       	$this->data['text_browse'] = $this->language->get('text_browse');
       	$this->data['text_clear'] = $this->language->get('text_clear');
        $this->data['text_image_manager'] = $this->language->get('text_image_manager');
        $this->data['text_select'] = $this->language->get('text_select');	
        $this->data['text_none'] = $this->language->get('text_none');
       	$this->data['text_sell'] = $this->language->get('text_sell');
		$this->data['text_buy'] = $this->language->get('text_buy');
		$this->data['text_used'] = $this->language->get('text_used');
        $this->data['text_new'] = $this->language->get('text_new');
        $this->data['text_individual'] = $this->language->get('text_individual');
        $this->data['text_agent'] = $this->language->get('text_agent');
        
        $this->data['entry_category'] = $this->language->get('entry_category');
        $this->data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
        $this->data['entry_title'] = $this->language->get('entry_title');
        $this->data['entry_price'] = $this->language->get('entry_price');
        $this->data['entry_subcategory'] = $this->language->get('entry_subcategory');
        $this->data['entry_type_of_ad'] = $this->language->get('entry_type_of_ad');
        $this->data['entry_condition'] = $this->language->get('entry_condition');
        $this->data['entry_image'] = $this->language->get('entry_image');
        $this->data['entry_state'] = $this->language->get('entry_state');
        $this->data['entry_locality'] = $this->language->get('entry_locality');
        $this->data['entry_desc'] = $this->language->get('entry_desc');
        $this->data['entry_type_of_seller'] = $this->language->get('entry_type_of_seller');
        $this->data['entry_email'] = $this->language->get('entry_email');
        $this->data['entry_name'] = $this->language->get('entry_name');
        $this->data['entry_mobile'] = $this->language->get('entry_mobile');
        
        $this->data['button_postfree'] = $this->language->get('button_postfree');
        
        
        if (isset($this->error['manufacturer'])) {
			$this->data['error_manufacturer'] = $this->error['manufacturer'];
		} else {
			$this->data['error_manufacturer'] = '';
		}
        
        if (isset($this->error['category'])) {
			$this->data['error_category'] = $this->error['category'];
		} else {
			$this->data['error_category'] = '';
		}
        
        if (isset($this->error['subcategory'])) {
			$this->data['error_subcategory'] = $this->error['subcategory'];
		} else {
			$this->data['error_subcategory'] = '';
		}
        
        if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}
        
        if (isset($this->error['price'])) {
			$this->data['error_price'] = $this->error['price'];
		} else {
			$this->data['error_price'] = '';
		}
        
        if (isset($this->error['state'])) {
			$this->data['error_state'] = $this->error['state'];
		} else {
			$this->data['error_state'] = '';
		}
        
        if (isset($this->error['locality'])) {
			$this->data['error_locality'] = $this->error['locality'];
		} else {
			$this->data['error_locality'] = '';
		}
        
        if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
        
        if (isset($this->error['mobile'])) {
			$this->data['error_mobile'] = $this->error['mobile'];
		} else {
			$this->data['error_mobile'] = '';
		}
        
        $this->data['others'] = 'Others';// Hardcoding for default selection
        
        
        
        $this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
        
        $this->data['attribute_groups'] = $this->model_catalog_product->getProductAttributes(43);
        
        $this->load->model('localisation/zone');

		$this->data['states'] = $this->model_localisation_zone->getStates(99);
        
        //$this->data['locality'] = $this->model_localisation_zone->getAllLocality();
        
        
        
        $this->data['action'] = $this->url->link('product/postad', '', 'SSL');  
        
        
        if (isset($this->request->post['type_ad'])) {
			$this->data['type_ad'] = $this->request->post['type_ad'];
		} else {
			$this->data['type_ad'] = 0;
		}
        
        if (isset($this->request->post['category_id'])) {
			$this->data['category_id'] = $this->request->post['category_id'];
		} else {
			$this->data['category_id'] = '';
		}
        
        if (isset($this->request->post['subcategory_id'])) {
			$this->data['subcategory_id'] = $this->request->post['subcategory_id'];
		} else {
			$this->data['subcategory_id'] = '';
		}
        
        if (isset($this->request->post['subsubcategory_id'])) {
			$this->data['subsubcategory_id'] = $this->request->post['subsubcategory_id'];
		} else {
			$this->data['subsubcategory_id'] = '';
		}
        
        if (isset($this->request->post['title'])) {
			$this->data['title'] = $this->request->post['title'];
		} else {
			$this->data['title'] = '';
		}
        
        if (isset($this->request->post['condition'])) {
			$this->data['condition'] = $this->request->post['condition'];
		} else {
			$this->data['condition'] = '';
		}
        
        if (isset($this->request->post['price'])) {
			$this->data['price'] = $this->request->post['price'];
		} else {
			$this->data['price'] = '';
		}
        
        if (isset($this->request->post['states_id'])) {
			$this->data['states_id'] = $this->request->post['states_id'];
		} elseif(isset($this->session->data['city_id'])){
            $this->data['states_id'] = $this->session->data['city_id'];
        }else{
			$this->data['states_id'] = '';
		}
        
        if (isset($this->request->post['locality_id'])) {
            $localityArray = explode(",",$this->request->post['locality_id'] );
			$this->data['locality_id'] = $localityArray[0];
		} elseif(isset($this->session->data['location_id'])){
            $this->data['locality_id'] = $this->session->data['location_id'];
        } else {
			$this->data['locality_id'] = '';
		}
        
        if (isset($this->request->post['description'])) {
			$this->data['description'] = $this->request->post['description'];
		} else {
			$this->data['description'] = '';
		}
        
        if (isset($this->request->post['whoareyou'])) {
			$this->data['type_customer'] = $this->request->post['whoareyou'];
		} else {
			$this->data['type_customer'] = 0;
		}
        
        if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		}else if ($this->customer->getEmail() != ''){
            $this->data['email'] = $this->customer->getEmail();
        }else{
			$this->data['email'] = '';
		}
        
        if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} else {
			$this->data['name'] = '';
		}
        
        if (isset($this->request->post['mobile'])) {
			$this->data['mobile'] = $this->request->post['mobile'];
		}else if ($this->customer->getTelephone() != ''){
            $this->data['mobile'] = $this->customer->getTelephone();
        } else {
			$this->data['mobile'] = '';
		}
        
        if (isset($this->request->post['agree'])) {
			$this->data['agree'] = $this->request->post['agree'];
		} else {
			$this->data['agree'] = '';
		}
        
        $manufacturers = $this->model_catalog_manufacturer->getManufacturers();
        $this->data['manufacturers'] = $manufacturers;
        //echo print_R($this->data['manufacturers'],TRUE);
        
        $this->load->model('catalog/category');

		if (isset($this->request->post['product_category'])) {
			$categories = $this->request->post['product_category'];
		} elseif (isset($this->request->get['product_id'])) {		
			$categories = $this->model_catalog_product->getProductCategories($this->request->get['product_id']);
		} else {
			$categories = array();
		}
        
        if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

			if ($information_info) {
				$this->data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/info', 'information_id=' . $this->config->get('config_account_id'), 'SSL'), $information_info['title'], $information_info['title']);
			} else {
				$this->data['text_agree'] = '';
			}
		} else {
			$this->data['text_agree'] = '';
		}
        
        if (isset($this->request->post['agree'])) {
			$this->data['agree'] = $this->request->post['agree'];
		} else {
			$this->data['agree'] = false;
		}
        
		$this->data['product_categories'] = array();

        //$this->model_catalog_category->getCategoryAttributes(61);   

		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$this->data['product_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path'] ? $category_info['path'] . ' &gt; ' : '') . $category_info['name']
				);
			}
		}
        
        $this->data['product_categories_list'] = $this->model_catalog_category->getCategories(0);
        
        $categories_1 = $this->model_catalog_category->getCategories(0);
        //echo print_R($categories_1);
       
        
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/postad.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/postad.tpl';
		} else {
			$this->template = 'default/template/product/postad.tpl';
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
    protected function find_str($needle, $haystack){
        return strpos($haystack, $needle) !== false;
    }
    protected function insert($data){
        
        //echo print_R($data['product_category'],TRUE);
        $attribute_values = "";
        foreach($_POST as $key => $value){
            
            echo $key. "==" .$value . "<br>"  ;
            
            if($this->find_str(":attribute", $key)){
                if($attribute_values == ""){
                    $attribute_values = str_replace(":attribute","",$key) .":" . $value;    
                }else{
                    $attribute_values = str_replace(":attribute","",$key) .":" . $value;
                }
                    
                //15|Type:Ready to move
                
                //echo $attribute_values . "<br>"  ; 
                
                $aSplit = explode(":",$attribute_values );
                $aSplitAll = explode("|",$aSplit[0]);
                //aSplit[0] - attribute id | name
                //aSplit[1] - value
                if($aSplit[1] != ''){
                    $attribute_details[] = array(
                            'id' =>  $aSplitAll[0],
                            'name' => $aSplitAll[1],
                            'value' => $aSplit[1]
                    );
                }
            }
            
        }
        //echo $attribute_values;
    
        
       
        
        $this->data['attribute_values'] = $attribute_details;
        
        $this->data['stock_status_id'] = 6;
        $this->data['status'] = 1;
        $this->data['quantity'] = 1;
        
        $images = array();
        if(isset($data['images'])){
            $imageSplit = explode(",",$data['images']);
            foreach ($imageSplit as $key => $value) {
                    $images[] = array(
                        'image' =>  $value,
                        'sort_order' => $key
                );
            }
        }
        if(isset($images)){
            if(isset($images[0])){
                $this->data['image'] = $images[0]['image'];        
            }
        }
        //echo print_R($images,TRUE);
        
        
        $this->data['product_images'] = array();

        //foreach ($images as $product_image) {
//            echo print_R($product_image, TRUE);
//            }
//        echo "</br>";
 
		foreach ($images as $product_image) {
            if($product_image['sort_order'] > '0'){
                echo DIR_IMAGE . $product_image['image'] . '</br>';
    			if ($product_image['image'] && file_exists(DIR_IMAGE . $product_image['image'])) {
    				$image = $product_image['image'];
    			} else {
    				$image = 'no_image.jpg';
    			}
    
    			$this->data['product_images'][] = array(
    				'image'      => $image,
    				'thumb'      => $this->model_tool_image->resize($image, 100, 100),
    				'sort_order' => $product_image['sort_order']
    			);
            }
		}
        
        //echo "</br>";
//        echo print_R($this->data['product_images'],TRUE);
        
        if (isset($data['category_id'])) {
            $this->data['product_category'][0] = $data['category_id'];
        }
        if (isset($data['subcategory_id'])&& $data['subcategory_id'] >0) {
            $this->data['product_category'][1] = $data['subcategory_id'];
        }
        if (isset($data['subsubcategory_id']) && $data['subsubcategory_id'] >0) {
            $this->data['product_category'][2] = $data['subsubcategory_id'];
        }
        
        if($data['condition']){
            $this->data['condition'] = 'Used';
        }else{
            $this->data['condition'] = 'New';
        }
        
        if($data['whoareyou']){
            $this->data['whoareyou'] = 'Individual';
        }else{
            $this->data['whoareyou'] = 'Dealar';
        }
        
        if($data['type_ad']){
            $this->data['type_ad'] = 'Sell';
        }else{
            $this->data['type_ad'] = 'Buy';
        }     
        
        //creating random passwor for new user
        $password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);
        
        
        $localitySplit = explode(",",$data['locality_id']);
        
        $this->data['email'] = $data['email']; 
        $this->data['firstname'] = $data['name'];
        $this->data['lastname'] = $data['name'];
        $this->data['telephone'] = $data['mobile'];
        $this->data['fax'] = '';
        $this->data['newsletter'] = 0;
        $this->data['company'] = '';
        $this->data['company_id'] = '';
        $this->data['tax_id'] = '';
        $this->data['address_1'] = '';
        $this->data['address_2'] = '';
        $this->data['country_id'] = 99;
        $this->data['city'] = $localitySplit[1];
        $this->data['postcode'] = 0;
        $this->data['zone_id'] =  $data['states_id'];
        $this->data['password'] =  $password;
        $this->data['locality_id'] =  $localitySplit[0];
        $this->data['locality_name'] =  $localitySplit[1];
        
        
    }
    
    
    protected function update($data){
        
        //echo print_R($data['product_category'],TRUE);
        $attribute_values = "";
        foreach($_POST as $key => $value){
            
            echo $key. "==" .$value . "<br>"  ;
            
            if($this->find_str(":attribute", $key)){
                if($attribute_values == ""){
                    $attribute_values = str_replace(":attribute","",$key) .":" . $value;    
                }else{
                    $attribute_values = str_replace(":attribute","",$key) .":" . $value;
                }
                    
                //15|Type:Ready to move
                
                //echo $attribute_values . "<br>"  ; 
                
                $aSplit = explode(":",$attribute_values );
                $aSplitAll = explode("|",$aSplit[0]);
                //aSplit[0] - attribute id | name
                //aSplit[1] - value
                
                $attribute_details[] = array(
                        'id' =>  $aSplitAll[0],
                        'name' => $aSplitAll[1],
                        'value' => $aSplit[1]
                );
            }
            
        }
        //echo $attribute_values;
    
        
       
        
        $this->data['attribute_values'] = $attribute_details;
        
        $this->data['stock_status_id'] = 6;
        $this->data['status'] = 1;
        $this->data['quantity'] = 1;
        
        $images = array();
        if(isset($data['images'])){
            $imageSplit = explode(",",$data['images']);
            foreach ($imageSplit as $key => $value) {
                    $images[] = array(
                        'image' =>  $value,
                        'sort_order' => $key
                );
            }
        }
        if(isset($images)){
            if(isset($images[0])){
                $this->data['image'] = $images[0]['image'];        
            }
        }
        //echo print_R($images,TRUE);
        
        
        $this->data['product_images'] = array();

        //foreach ($images as $product_image) {
//            echo print_R($product_image, TRUE);
//            }
//        echo "</br>";
 
		foreach ($images as $product_image) {
            if($product_image['sort_order'] > '0'){
                
    			if ($product_image['image'] && file_exists(DIR_IMAGE . $product_image['image'])) {
    				$image = $product_image['image'];
    			} else {
    				$image = 'no_image.jpg';
    			}
    
    			$this->data['product_images'][] = array(
    				'image'      => $image,
    				'thumb'      => $this->model_tool_image->resize($image, 100, 100),
    				'sort_order' => $product_image['sort_order']
    			);
            }
		}
        
        
        if (isset($data['category_id'])) {
            $this->data['product_category'][0] = $data['category_id'];
        }
        if (isset($data['subcategory_id']) && $data['subcategory_id'] >0) {
            $this->data['product_category'][1] = $data['subcategory_id'];
        }
        if (isset($data['subsubcategory_id']) && $data['subsubcategory_id'] >0) {
            $this->data['product_category'][2] = $data['subsubcategory_id'];
        }
        
        if($data['condition']){
            $this->data['condition'] = 'Used';
        }else{
            $this->data['condition'] = 'New';
        }
        
        if($data['whoareyou']){
            $this->data['whoareyou'] = 'Individual';
        }else{
            $this->data['whoareyou'] = 'Dealar';
        }
        
        if($data['type_ad']){
            $this->data['type_ad'] = 'Sell';
        }else{
            $this->data['type_ad'] = 'Buy';
        }     
        
        //creating random passwor for new user
        $password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);
        
        
        $localitySplit = explode(",",$data['locality_id']);
        $this->data['product_id'] = $data['product_id'];
        $this->data['email'] = $data['email']; 
        $this->data['firstname'] = $data['name'];
        $this->data['lastname'] = $data['name'];
        $this->data['telephone'] = $data['mobile'];
        $this->data['fax'] = '';
        $this->data['newsletter'] = 0;
        $this->data['company'] = '';
        $this->data['company_id'] = '';
        $this->data['tax_id'] = '';
        $this->data['address_1'] = '';
        $this->data['address_2'] = '';
        $this->data['country_id'] = 99;
        $this->data['city'] = $localitySplit[1];
        $this->data['postcode'] = 0;
        $this->data['zone_id'] =  $data['states_id'];
        $this->data['password'] =  $password;
        $this->data['locality_id'] =  $localitySplit[0];
        $this->data['locality_name'] =  $localitySplit[1];

    }
    
    public function remove(){
        $this->load->model('catalog/postad');
        //echo print_R($this->request->get);
        $product_id = $this->request->get['id'];
        $this->model_catalog_postad->removeProduct($product_id);
        
        $this->response->setOutput('success');
                
    }
    
    public function edit(){
        
        $this->language->load('product/postad');
        $this->document->setTitle($this->language->get('heading_title'));
       	$this->load->model('tool/image');
        $this->load->model('catalog/product');
        $this->load->model('catalog/postad');
        $this->load->model('catalog/manufacturer');
        $this->load->model('localisation/zone');
        $this->load->model('catalog/category');
                
		$this->document->addScript('catalog/view/javascript/filereader.js');
        $this->document->addScript('catalog/view/javascript/script.js');
        
		$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
        
		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/style.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/style.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/style.css');
		}
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            echo 'update';
            $this->update($this->request->post);
            
            //echo print_R($this->data,TRUE);
            $product_id = $this->model_catalog_postad->updateProduct($this->request->post,$this->data);
            echo $product_id;
            $this->redirect($this->url->link('account/updateadsuccess'));
        }
        
        $this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
			'separator' => false
		);
        
        $this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_yourad'),
			'href'      => $this->url->link('product/postad', '', 'SSL'),      	
			'separator' => $this->language->get('text_separator')
		);
        $this->session->data['token'] = md5(mt_rand());
        $this->data['action'] = $this->url->link('product/postad/edit', '&id='.$this->request->get['id'], 'SSL');
    
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['text_your_ad_pic'] = $this->language->get('text_your_ad_pic');
        $this->data['text_your_ad_details'] = $this->language->get('text_your_ad_details');
        $this->data['text_describe_your_ad'] = $this->language->get('text_describe_your_ad');
        $this->data['text_classify_your_ad'] = $this->language->get('text_classify_your_ad');
       	$this->data['text_browse'] = $this->language->get('text_browse');
       	$this->data['text_clear'] = $this->language->get('text_clear');
        $this->data['text_image_manager'] = $this->language->get('text_image_manager');
        $this->data['text_select'] = $this->language->get('text_select');	
        $this->data['text_none'] = $this->language->get('text_none');
       	$this->data['text_sell'] = $this->language->get('text_sell');
		$this->data['text_buy'] = $this->language->get('text_buy');
		$this->data['text_used'] = $this->language->get('text_used');
        $this->data['text_new'] = $this->language->get('text_new');
        $this->data['text_individual'] = $this->language->get('text_individual');
        $this->data['text_agent'] = $this->language->get('text_agent');
        
        $this->data['entry_category'] = $this->language->get('entry_category');
        $this->data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
        $this->data['entry_title'] = $this->language->get('entry_title');
        $this->data['entry_price'] = $this->language->get('entry_price');
        $this->data['entry_subcategory'] = $this->language->get('entry_subcategory');
        $this->data['entry_type_of_ad'] = $this->language->get('entry_type_of_ad');
        $this->data['entry_condition'] = $this->language->get('entry_condition');
        $this->data['entry_image'] = $this->language->get('entry_image');
        $this->data['entry_state'] = $this->language->get('entry_state');
        $this->data['entry_locality'] = $this->language->get('entry_locality');
        $this->data['entry_desc'] = $this->language->get('entry_desc');
        $this->data['entry_type_of_seller'] = $this->language->get('entry_type_of_seller');
        $this->data['entry_email'] = $this->language->get('entry_email');
        $this->data['entry_name'] = $this->language->get('entry_name');
        $this->data['entry_mobile'] = $this->language->get('entry_mobile');
        
        $this->data['button_updatead'] = $this->language->get('button_updatead');
        $this->data['button_continue'] = $this->language->get('button_continue');
        
        $this->data['continue'] = $this->url->link('account/adlist', '', 'SSL');
        
        if (isset($this->error['manufacturer'])) {
			$this->data['error_manufacturer'] = $this->error['manufacturer'];
		} else {
			$this->data['error_manufacturer'] = '';
		}
        
        if (isset($this->error['category'])) {
			$this->data['error_category'] = $this->error['category'];
		} else {
			$this->data['error_category'] = '';
		}
        
        if (isset($this->error['subcategory'])) {
			$this->data['error_subcategory'] = $this->error['subcategory'];
		} else {
			$this->data['error_subcategory'] = '';
		}
        
        if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}
        
        if (isset($this->error['price'])) {
			$this->data['error_price'] = $this->error['price'];
		} else {
			$this->data['error_price'] = '';
		}
        
        if (isset($this->error['state'])) {
			$this->data['error_state'] = $this->error['state'];
		} else {
			$this->data['error_state'] = '';
		}
        
        if (isset($this->error['locality'])) {
			$this->data['error_locality'] = $this->error['locality'];
		} else {
			$this->data['error_locality'] = '';
		}
        
        if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
        
        if (isset($this->error['mobile'])) {
			$this->data['error_mobile'] = $this->error['mobile'];
		} else {
			$this->data['error_mobile'] = '';
		}
        
        $this->data['others'] = 'Others';// Hardcoding for default selection
        
        $product_id = $this->request->get['id'];
        
        $this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
        
        
        //Loading default data from configuration
		$this->data['states'] = $this->model_localisation_zone->getStates(99);
        
        $manufacturers = $this->model_catalog_manufacturer->getManufacturers();
        $this->data['manufacturers'] = $manufacturers;
        
        $this->data['product_categories_list'] = $this->model_catalog_category->getCategories(0);
        
        //Loading data from saved db
		$product_info = $this->model_catalog_product->getProduct($product_id);
        $product_categories = $this->model_catalog_product->getProductCategories($product_id);
        $product_attribute_values = $this->model_catalog_product->getProductCategoryAttributes($product_id);
        $product_customer = $this->model_catalog_product->getProductCustomer($product_id);
        $product_images = $this->model_catalog_product->getProductImages($product_id);
        //echo print_R($product_info,TRUE);
        //echo "</br>";
        //echo print_R($product_attribute_values, TRUE);
        //echo "</br>";
        //echo print_R($product_customer, TRUE);
        //echo "</br>";
        //echo print_R($product_images, TRUE);
        
        //echo $product_attribute_values['category_attribute_value'];
        //echo print_R($product_categories,TRUE);
        
        if(empty($product_attribute_values)){
            foreach ($product_categories as $key => $value){
                //echo $key . '<===>' . $value['category_id'] .'</br>';
                if((int)$key == 0){
                    $this->data['category_id'] = $value['category_id'];
                }
                elseif((int)$key == 1){
                    $this->data['subcategory_id'] = $value['category_id'];
                }
                elseif((int)$key == 2){
                    $this->data['subsubcategory_id'] = $value['category_id'];
                }
            }
        }
        if(!empty($product_attribute_values)){
            $product_attribute_value = $product_attribute_values[0];
            $this->data['product_subcategories_list'] = $this->model_catalog_category->getCategories($product_attribute_value['category_id']);
            $this->data['product_subsubcategories_list'] = $this->model_catalog_category->getCategories($product_attribute_value['subcategory_id']);    
        }
        
        //foreach ($product_attribute_values as $product_attribute_value) {
//            echo print_R($product_attribute_value,TRUE);            
//            
//        }
        //echo print_R($product_attribute_value,TRUE);
        
        //$this->data['product_attribute_values'] = $product_attribute_values['category_attribute_value'];    
        
        
        //echo print_R($this->data['product_subcategories_list'], TRUE);
        //echo print_R($this->data['product_subsubcategories_list'], TRUE);
        $image = '';
        $imageLoad = '';
        foreach ($product_images as $product_image) {
            if($product_image['sort_order'] > '0'){
                //echo DIR_IMAGE . $product_image['image'] . '</br>';
    			if ($product_image['image'] && file_exists(DIR_IMAGE . $product_image['image'])) {
    				$image = 'image/'.$product_image['image'] .','.$image;
                    $imageLoad = $product_image['image'] .','.$imageLoad;
    			} 
                
    		
            }
		}
        //check if image data is avaliable.
        if($product_info['image'] != ''){
            $image = 'image/'.$product_info['image'].','.$image ;
            $imageLoad = $product_info['image'].','.$imageLoad ;    //This data loads up the text box for image updation.
            $imageLoad = rtrim($imageLoad, ',');
        }
        if (isset($product_customer['type_ad'])) {
            if($product_customer['type_ad'] = 'Sell'){
                $this->data['type_ad'] = 0;
            }else{
                $this->data['type_ad'] = 1;
            }  
		} else {
			$this->data['type_ad'] = '';
		}
        
        if (isset($product_id)) {
			$this->data['product_id'] = $product_id;
		} else {
			$this->data['product_id'] = '';
		}
        
        if(!isset($this->data['category_id'])){
            if (isset($product_attribute_value['category_id'])) {
                $this->data['category_id'] = $product_attribute_value['category_id'];
    		} else {
                $this->data['category_id'] = '';
    		}    
        }
        
        if(!isset($this->data['subcategory_id'])){
            if (isset($product_attribute_value['subcategory_id'])) {
    			$this->data['subcategory_id'] = $product_attribute_value['subcategory_id'];
    		} else {
    			$this->data['subcategory_id'] = '';
    		}    
        }
        
        if(!isset($this->data['subsubcategory_id'])){
            if (isset($product_attribute_value['subsubcategory_id'])) {
    			$this->data['subsubcategory_id'] = $product_attribute_value['subsubcategory_id'];
    		} else {
    			$this->data['subsubcategory_id'] = '';
    		}    
        }
        
        
        
        if (isset($product_info['manufacturer_id'])) {
			$this->data['manufacturer_id'] = $product_info['manufacturer_id'];
		} else {
			$this->data['manufacturer_id'] = '';
		}
        
        
        if (isset($product_info['name'])) {
			$this->data['title'] = $product_info['name'];
		} else {
			$this->data['title'] = '';
		}
        
        if (isset($product_customer['product_condition'])) {
			$this->data['condition'] = $product_customer['product_condition'];
		} else {
			$this->data['condition'] = '';
		}
        
        if (isset($product_info['price'])) {
			$this->data['price'] = $product_info['price'];
		} else {
			$this->data['price'] = '';
		}
        
        if (isset($product_customer['state'])) {
			$this->data['states_id'] = $product_customer['state'];
		} else {
			$this->data['states_id'] = '';
		}
        
        if (isset($product_customer['locality_id'])) {
			$this->data['locality_id'] = $product_customer['locality_id'];
		} else {
			$this->data['locality_id'] = '';
		}
        
        if (isset($product_info['description'])) {
			$this->data['description'] = $product_info['description'];
		} else {
			$this->data['description'] = '';
		}
        
        if (isset($product_customer['type_customer'])) {
            if($product_customer['type_customer'] = 'Dealer'){
                $this->data['type_customer'] = 1;
            }else{
                $this->data['type_customer'] = 0;
            } 
        
		} else {
			$this->data['type_customer'] = '';
		}
        
        if (isset($image)) {
			$this->data['image'] = $image;
		} else {
			$this->data['image'] = '';
		}
        
        if (isset($image)) {
			$this->data['imageTemp'] = $imageLoad;
		} else {
			$this->data['imageTemp'] = '';
		}
        
        if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		}else if ($this->customer->getEmail() != ''){
            $this->data['email'] = $this->customer->getEmail();
        }else{
			$this->data['email'] = '';
		}
        
        if (isset($product_customer['name'])) {
			$this->data['name'] = $product_customer['name'];
		} else {
			$this->data['name'] = '';
		}
        
        if (isset($product_customer['telephone'])) {
			$this->data['mobile'] = $product_customer['telephone'];
		} else {
			$this->data['mobile'] = '';
		}
        
        if (isset($this->request->post['agree'])) {
			$this->data['agree'] = $this->request->post['agree'];
		} else {
			$this->data['agree'] = '';
		}
        //echo print_R($this->data,TRUE);
        if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

			if ($information_info) {
				$this->data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/info', 'information_id=' . $this->config->get('config_account_id'), 'SSL'), $information_info['title'], $information_info['title']);
			} else {
				$this->data['text_agree'] = '';
			}
		} else {
			$this->data['text_agree'] = '';
		}
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/postadedit.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/postadedit.tpl';
		} else {
			$this->template = 'default/template/product/postadedit.tpl';
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
    
    protected function validate() {
        $attribute_values = "";
        
        if ((utf8_strlen($this->request->post['title']) < 1)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}
        
        if ($this->request->post['manufacturer_id'] == '') {
			$this->error['manufacturer'] = $this->language->get('error_manufacturer');
		}
        
        if ($this->request->post['category_id'] == 0) {
			$this->error['category'] = $this->language->get('error_category');
		}
        
        if(isset($this->request->post['subcategory_id'])){
            if ($this->request->post['subcategory_id'] == 0) {
    			$this->error['subcategory'] = $this->language->get('error_subcategory');
    		}
        }
        
        if ((utf8_strlen($this->request->post['title']) < 1)) {
			$this->error['title'] = $this->language->get('error_title');
		}
        
        if ((utf8_strlen($this->request->post['price']) < 1)) {
			$this->error['price'] = $this->language->get('error_price');
		}
        
        if ($this->request->post['states_id'] == '') {
			$this->error['state'] = $this->language->get('error_state');
		}
        
        if ($this->request->post['locality_id'] == '') {
			$this->error['locality'] = $this->language->get('error_locality');
		}
        
        if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}
  
        if ((utf8_strlen($this->request->post['mobile']) < 3) || (utf8_strlen($this->request->post['mobile']) > 32)) {
			$this->error['mobile'] = $this->language->get('error_mobile');
		}
        
        if (!$this->error) {
			return true;
		} else {
			return false;
		}
        
    }
    
    public function locality() {
        $json = array();
        
		if (isset($this->request->get['zone_id'])) {
		  
            $this->load->model('localisation/zone');
                        
    		$json = array(
    			'zone_id'        => $this->model_localisation_zone->getLocality($this->request->get['zone_id'])
    		);
    	
            
    		
        }
        $this->response->setOutput(json_encode($json));
	}
    
    public function localityall() {
        $json = array();
        

        $this->load->model('localisation/zone');
        
        $results = $this->model_localisation_zone->getStates(99);
        
        
        
        foreach($results as $result) {
            $localities = $this->model_localisation_zone->getLocality($result['zone_id']);
            
            foreach($localities as $locality)
            $json[] = array(
					'state_id' => $result['zone_id'],
                    'locality_id' => $locality['locality_id'],
                    'locality_name' => $locality['locality_name']
				);
        }
        
        
                    
		
        $this->response->setOutput(json_encode($json));
	}
    
    public function emailtemplate($data){
        $this->load->model('catalog/postad');
        
        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/expresstemplate.tpl')) {
			$this->template = DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/expresstemplate.tpl';
		} else {
			$this->template = 'default/template//mail/swapexpresstemplate.tpl';
		}
        $this->data['mail_template'] = $this->template;
        
        if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/style.css')) {
			$this->style = 'catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/email.css';
		} else {
            $this->style = 'catalog/view/theme/default/stylesheet/email.css';
		}
        $this->data['style_sheet'] = $this->style;
        
        //image/data/samsung_logo.jpg
        if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] =  'image/' . $this->config->get('config_logo');
		} else {
			$this->data['logo'] = '';
		}
        //echo print_R($this->data,TRUE);
        
        $this->model_catalog_postad->senteMail($this->data);
        
        
    }
    
}
?>