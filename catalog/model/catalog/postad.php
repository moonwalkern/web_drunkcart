<?php
class ModelCatalogPostad extends Model {
	public function addProduct($data, $extraData) {
		//$this->db->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW()");
        
        $this->db->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['title']) . "', quantity = '" . (int)$extraData['quantity'] . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', status = '" . (int)$extraData['status']  . "', stock_status_id = '" . (int)$extraData['stock_status_id'] . "', date_available =  NOW()" . ", price = '" . (float)$data['price'] . "', date_added = NOW()");
        
        
        
		$product_id = $this->db->getLastId();
        echo $extraData['image'];
        if (isset($extraData['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape(html_entity_decode($extraData['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
        
        $language_id = 1;
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($data['title']) . "', description = '" . $this->db->escape($data['description']) . "'");
		

        $store_id = 0;
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
		
        if (isset($extraData['product_category'])) {
			foreach ($extraData['product_category'] as $category_id) {
                
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
        
        if (isset($extraData['product_images'])) {
			foreach ($extraData['product_images'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}
        
        foreach ($extraData['attribute_values'] as $product_attributes) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute_values SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$data['category_id']  .  "', subcategory_id = '" . (int)$data['subcategory_id'] . "', subsubcategory_id = '" . (int)$data['subsubcategory_id']. "', category_attribute_value = '" . $product_attributes['value'] . "', category_attribute_id = '" . $product_attributes['id'] . "', category_attribute_name= '" . $product_attributes['name'] . "'");
        }
        
//        if (isset($extraData['attribute_values'])) 
//				$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute_values SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$data['category_id']  .  "', subcategory_id = '" . (int)$data['subcategory_id'] . "', subsubcategory_id = '" . (int)$data['subsubcategory_id'] . "', category_attribute_value = '" . $extraData['attribute_values'] . "'");
//		}

        //insert product address
        //echo "INSERT INTO " . DB_PREFIX . "product_specifics SET product_id = '" . (int)$product_id .  "', type_ad = '" . $extraData['type_ad']  .  "', type_customer = '" . $extraData['whoareyou']  .  "', product_condition = '" . $extraData['condition']  . "', state = '" . $this->db->escape($data['states_id']) .  "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($extraData['postcode']) . "', country_id = '" . (int)$extraData['country_id'] . "', zone_id = '" . (int)$extraData['zone_id'] . "'";
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_specifics SET product_id = '" . (int)$product_id .  "', type_ad = '" . $extraData['type_ad']  .  "', type_customer = '" . $extraData['whoareyou']  .  "', product_condition = '" . $extraData['condition']  . "', state = '" . $this->db->escape($extraData['zone_id']) .  "', city = '" . $this->db->escape($extraData['city']) . "', postcode = '" . $this->db->escape($extraData['postcode']) . "', country_id = '" . (int)$extraData['country_id'] . "', zone_id = '" . (int)$extraData['zone_id'] . "', locality_id = '" . (int)$extraData['locality_id'] . "', locality_name = '" .$extraData['locality_name'] . "'");
                
        
        
        return $product_id;
	}

    public function addProductToCustomer($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_customer SET product_id = '" . (int)$data['product_id'] . "', customer_id = '" . (int)$data['customer_id'] . "'");        
    }
    
    public function addMessageToSent($product_id, $data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_message SET product_id = '" . (int)$product_id . "', message = '" . $data['text'] . "', mobile_from = '" . $data['mobile'] .  "', mobile_to = '" . $data['mobile_to'] . "', email_from = '" . $data['email'] . "', email_to = '" . $data['email_to'] . "', type_message = '" . $data['type_message'] . "', date_updated = NOW()");
        
    }
    
	public function addCustomerNotification($data) {

        $this->db->query("INSERT INTO " . DB_PREFIX . "customer_notification SET customer_id = '" . (int)$data['customer_id'] . "', email_content = '" . $data['mail_template'] . "', sms_content = '" . $data['sms_content'] .  "', email = '" . $data['email'] . "', mobile = '" . $data['telephone'] . "', otp = '" . $data['otp']  . "', date_updated = NOW()");
    }
    
    
    public function fetchCustomerNotification($data) {

        $this->db->query("INSERT INTO " . DB_PREFIX . "customer_notification SET customer_id = '" . (int)$data['customer_id'] . "', email_content = '" . $data['mail_template'] . "', sms_content = '" . $data['sms_content'] .  "', email = '" . $data['email'] . "', mobile = '" . $data['telephone'] . "', otp = '" . $data['otp']  . "', date_updated = NOW()");
    }
    
    public function updateProduct($data, $extraData) {
        //echo "reached validate</br>";
        echo print_R($data,TRUE);
        echo print_R($extraData,TRUE);
        $product_id = $extraData['product_id'];
		//$this->db->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW()");
        
        $this->db->query("UPDATE " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['title']) . "', quantity = '" . (int)$extraData['quantity'] . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', status = '" . (int)$extraData['status']  . "', stock_status_id = '" . (int)$extraData['stock_status_id'] . "', date_available =  NOW()" . ", price = '" . (float)$data['price'] . "', date_modified = NOW()  WHERE product_id = '" . (int)$product_id . "'"); 
        
        
        
		
        if (isset($extraData['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape(html_entity_decode($extraData['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
        
        $language_id = 1;
		$this->db->query("UPDATE  " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($data['title']) . "', description = '" . $this->db->escape($data['description']) . "' WHERE product_id = '" . (int)$product_id . "'"); 
		

        //$store_id = 0;
		//$this->db->query("UPDATE " . DB_PREFIX . "product_to_store SET store_id = '" . (int)$store_id  . "' WHERE product_id = '" . (int)$product_id . "'");
		
        if (isset($extraData['product_category'])) {
			foreach ($extraData['product_category'] as $category_id) {
                echo "UPDATE " . DB_PREFIX . "product_to_category SET category_id = '" . (int)$category_id . "' WHERE product_id = '" . (int)$product_id . "'";               
				//$this->db->query("UPDATE " . DB_PREFIX . "product_to_category SET category_id = '" . (int)$category_id . "' WHERE product_id = '" . (int)$product_id . "'");
			}
		}
        //Delete the existing images.
        $this->db->query("DELETE FROM " .DB_PREFIX .  "product_image WHERE product_id=" .(int)$product_id );
        if (isset($extraData['product_images'])) {
			foreach ($extraData['product_images'] as $product_image) {
                echo "INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_image['sort_order'] . "'";
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}
        //. "', category_attribute_id = '" . $product_attributes['id'] 
        foreach ($extraData['attribute_values'] as $product_attributes) {
            $this->db->query("UPDATE " . DB_PREFIX . "product_attribute_values SET category_id = '" . (int)$data['category_id']  .  "', subcategory_id = '" . (int)$data['subcategory_id'] . "', subsubcategory_id = '" . (int)$data['subsubcategory_id']. "', category_attribute_value = '" . $product_attributes['value'] . "', category_attribute_name= '" . $product_attributes['name'] . "' WHERE product_id = '" . (int)$product_id . "'" . " AND category_attribute_id = '" . (int)$product_attributes['id'] . "'");
        }

        //insert product address
        //echo "INSERT INTO " . DB_PREFIX . "product_specifics SET product_id = '" . (int)$product_id .  "', type_ad = '" . $extraData['type_ad']  .  "', type_customer = '" . $extraData['whoareyou']  .  "', product_condition = '" . $extraData['condition']  . "', state = '" . $this->db->escape($data['states_id']) .  "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($extraData['postcode']) . "', country_id = '" . (int)$extraData['country_id'] . "', zone_id = '" . (int)$extraData['zone_id'] . "'";
        //$this->db->query("INSERT INTO " . DB_PREFIX . "product_specifics SET product_id = '" . (int)$product_id .  "', type_ad = '" . $extraData['type_ad']  .  "', type_customer = '" . $extraData['whoareyou']  .  "', product_condition = '" . $extraData['condition']  . "', state = '" . $this->db->escape($extraData['zone_id']) .  "', city = '" . $this->db->escape($extraData['city']) . "', postcode = '" . $this->db->escape($extraData['postcode']) . "', country_id = '" . (int)$extraData['country_id'] . "', zone_id = '" . (int)$extraData['zone_id'] . "', locality_id = '" . (int)$extraData['locality_id'] . "', locality_name = '" .$extraData['locality_name'] . "'");
                
        
        
        return $product_id;
	}
    
    public function removeProduct($product_id) {

        $this->db->query("DELETE FROM " .DB_PREFIX .  "product_image WHERE product_id=" .(int)$product_id );
        $this->db->query("DELETE FROM " .DB_PREFIX .  "product_attribute_values WHERE product_id=" .(int)$product_id );
        $this->db->query("DELETE FROM " .DB_PREFIX .  "product_to_category WHERE product_id=" .(int)$product_id );
        $this->db->query("DELETE FROM " .DB_PREFIX .  "product_to_store WHERE product_id=" .(int)$product_id );
        $this->db->query("DELETE FROM " .DB_PREFIX .  "product_description WHERE product_id=" .(int)$product_id );
        $this->db->query("DELETE FROM " .DB_PREFIX .  "product_to_customer WHERE product_id=" .(int)$product_id );
        $this->db->query("DELETE FROM " .DB_PREFIX .  "product_specifics WHERE product_id=" .(int)$product_id );
        $this->db->query("DELETE FROM " .DB_PREFIX .  "product WHERE product_id=" .(int)$product_id );
        
        return true;
    }
    
    
    
    public function mywordwrap($str, $nochar, $endchar){
        $strlen = strlen($str);
        $returnStr = "";
        for( $i = 0; $i <= $strlen; $i++ ) {
            $char = substr( $str, $i, 1 );
            $j = $i+1;
            // $char contains the current character, so do your processing here
            if($j % $nochar == 0 )
                $returnStr = $returnStr. $char."<br>";
            else
                $returnStr = $returnStr. $char;
        }   
        
        return $returnStr;
        
    }
    
    public function senteMail($data){
        $data = $this->buildemailcontent($data);
        $mail = new PHPMailer();  // create a new object
        $mail->IsSMTP(); // enable SMTP
        $mail->IsHTML(true);
        $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true;  // authentication enabled
        //$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
        //$mail->Host = 'smtp.gmail.com';
        $mail->Host = $this->config->get('config_smtp_host');
        $mail->Port = $this->config->get('config_smtp_port'); 
        //$mail->Username = 'moonwalker.n@gmail.com';  
        $mail->Username = $this->config->get('config_smtp_username');
        $mail->Password = $this->config->get('config_smtp_password');
        $from = $this->config->get('config_email');   
        $from_name =$this->config->get('config_name');
//        $subject = html_entity_decode($subject, ENT_QUOTES, 'UTF-8');

        $mail->SetFrom($from, $from_name);
        $mail->Subject = $data['subject'];
        //$mail->Body = buildSwapBody($insertuser);
        $mail->MsgHTML($data['emailcontent']);
        $mail->AddAddress($data['email']);
        if(!$mail->Send()) {
            $error = 'Mail error: '.$mail->ErrorInfo; 
            return false;
        } else {
            $error = 'Message sent!';
            return true;
        }
        
        
    }
    
    public function senteMail_newuser($data){
        //Enabling logging
        $this->load->library('log');
        $logger = new Log('swapdeal.log');
		$logger->write('postad::senteMail_newuser');
        
        //Logging end
        $data = $this->buildemailcontent_newuser($data);
        $mail = new PHPMailer();  // create a new object
        $mail->IsSMTP(); // enable SMTP
        $mail->IsHTML(true);
        $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true;  // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
        //$mail->Host = 'smtp.gmail.com';
        $mail->Host = $this->config->get('config_smtp_host');
        $mail->Port = $this->config->get('config_smtp_port'); 
        //$mail->Username = 'moonwalker.n@gmail.com';  
        $mail->Username = $this->config->get('config_smtp_username');
        $mail->Password = $this->config->get('config_smtp_password');
        $from = $this->config->get('config_email');   
        $from_name =$this->config->get('config_name');
//        $subject = html_entity_decode($subject, ENT_QUOTES, 'UTF-8');

        $mail->SetFrom($from, $from_name);
        $mail->Subject = $data['subject'];
        //$mail->Body = buildSwapBody($insertuser);
        $logger->write('postad::senteMail_newuser::emailcontent::'.$data['emailcontent']);
        $mail->MsgHTML($data['emailcontent']);
        $mail->AddAddress($data['email']);
		
		//$this->addCustomerNotification($data);
		return true;
        if(!$mail->Send()) {
            $error = 'Mail error: '.$mail->ErrorInfo;
            $logger->write('postad::senteMail_newuser::error sending mail::'.$error);
            //echo $error;
            return false;
        } else {
            $error = 'Message sent!';
            $logger->write('postad::senteMail_newuser::success sending mail::'.$error);
            //echo $error;
            return true;
        }
        
        
    }

	public function senteMail_otp($data){
        //Enabling logging
        $this->load->library('log');
        $logger = new Log('swapdeal.log');
		$logger->write('postad::senteMail_newuser');
        
        //Logging end
        $data = $this->buildemailcontent_otp($data);
        $mail = new PHPMailer();  // create a new object
        $mail->IsSMTP(); // enable SMTP
        $mail->IsHTML(true);
        $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true;  // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
        //$mail->Host = 'smtp.gmail.com';
        $mail->Host = $this->config->get('config_smtp_host');
        $mail->Port = $this->config->get('config_smtp_port'); 
        //$mail->Username = 'moonwalker.n@gmail.com';  
        $mail->Username = $this->config->get('config_smtp_username');
        $mail->Password = $this->config->get('config_smtp_password');
        $from = $this->config->get('config_email');   
        $from_name =$this->config->get('config_name');
//        $subject = html_entity_decode($subject, ENT_QUOTES, 'UTF-8');

        $mail->SetFrom($from, $from_name);
        $mail->Subject = $data['subject'];
        //$mail->Body = buildSwapBody($insertuser);
        $logger->write('postad::senteMail_otp::emailcontent::'.$data['emailcontent']);
        $mail->MsgHTML($data['emailcontent']);
        $mail->AddAddress($data['email']);
		
		$this->addCustomerNotification($data);
		return true;
        if(!$mail->Send()) {
            $error = 'Mail error: '.$mail->ErrorInfo;
            $logger->write('postad::senteMail_otp::error sending mail::'.$error);
            //echo $error;
            return false;
        } else {
            $error = 'Message sent!';
            $logger->write('postad::senteMail_otp::success sending mail::'.$error);
            //echo $error;
            return true;
        }
        
        
    }
    //This method will built the email content using the email template.
    public function buildemailcontent($data){
        
        $this->language->load('mail/postad');
        
        $css = file_get_contents($data['style_sheet']);

        $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

		$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";
        
        
        $cssToInlineStyles = new CssToInlineStyles();
        ob_start();
        
        include ($data['mail_template']);
        $body = ob_get_contents();
        ob_get_clean();
        $cssToInlineStyles->setHTML($body);
        $cssToInlineStyles->setCSS($css);
        $emailcontent = $cssToInlineStyles->convert();
        //echo $emailcontent;
        $data['emailcontent'] = $emailcontent;
        return $data;
        
    }
    
    
    public function buildemailcontent_newuser($data){
        
        $this->language->load('mail/postad');
        
        $css = file_get_contents($data['style_sheet']);
        
        $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

		$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";
        
        $data['subject'] = $subject;
        $cssToInlineStyles = new CssToInlineStyles();
        ob_start();
        
        include ($data['mail_template']);
        $body = ob_get_contents();
        ob_get_clean();
        $cssToInlineStyles->setHTML($body);
        $cssToInlineStyles->setCSS($css);
        $emailcontent = $cssToInlineStyles->convert();
        //echo $emailcontent;
        $data['emailcontent'] = $emailcontent;
        return $data;
        
        
    }
	
	public function buildemailcontent_otp($data){
        
        $this->language->load('mail/postad');
        
        $css = file_get_contents($data['style_sheet']);
        
        $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

		$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";
        
        $data['subject'] = $subject;
        $cssToInlineStyles = new CssToInlineStyles();
        ob_start();
        
        include ($data['mail_template_otp']);
        $body = ob_get_contents();
        ob_get_clean();
        $cssToInlineStyles->setHTML($body);
        $cssToInlineStyles->setCSS($css);
        $emailcontent = $cssToInlineStyles->convert();
        //echo $emailcontent;
        $data['emailcontent'] = $emailcontent;
        return $data;
        
        
    }
    
    public function OTP_Content($otp){
		
		$opt_string  = $otp . ' is your login OTP. Treat this as confidential. Sharing it with anyone gives them full access to your Drunkcart Account.';
		return $opt_string;
	}
	
	public function RAND_OTP($min = 0, $max = 0) {
		$min		= $this->flattenSingleValue($min);
		$max		= $this->flattenSingleValue($max);

		if ($min == 0 && $max == 0) {
			return (rand(0,10000000)) / 10000000;
		} else {
			return rand($min, $max);
		}
	}	
	public function flattenSingleValue($value = '') {
		while (is_array($value)) {
			$value = array_pop($value);
		}

		return $value;
	}
}
?>