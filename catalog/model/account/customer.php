<?php
class ModelAccountCustomer extends Model {
	public function addCustomer($data) {
		if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $data['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET store_id = '" . (int)$this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', customer_group_id = '" . (int)$customer_group_id . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW()");

		$customer_id = $this->db->getLastId();

		//$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', company_id = '" . $this->db->escape($data['company_id']) . "', tax_id = '" . $this->db->escape($data['tax_id']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "'");
        //Adding locality id and state id details
        $this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', company_id = '" . $this->db->escape($data['company_id']) . "', tax_id = '" . $this->db->escape($data['tax_id']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', states_id = '" . (int)$data['states_id'] . "', locality_id = '" . (int)$data['locality_id'] . "'");

		$address_id = $this->db->getLastId();

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");

		$this->language->load('mail/customer');

		$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

		$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";

		if (!$customer_group_info['approval']) {
			$message .= $this->language->get('text_login') . "\n";
		} else {
			$message .= $this->language->get('text_approval') . "\n";
		}
        $UUID = $this->db->escape(sha1($salt . sha1($salt . sha1($data['password']))));
        //Caching cart data once the customer is registered and activated, this will fix the issue if the activate url is opened in a different browser
			
        if(isset($this->session->data['cart'])){
        	
			$this->load->model('tool/image');
    		$this->load->library('log');
    		$logger = new Log('swapdeal.log');
			
            $logger->write('customer::addcustomer adding cart data to cache for this $UUID '.$UUID);
			
            $cart_data = $this->session->data['cart'];
			$this->cache->set($UUID.'-cart', $cart_data);
            $logger->write('customer::addcustomer caching completed '.print_R($cart_data,TRUE));
        }
    
        $this->load->model('catalog/postad');
        if($data['checkout'] == '1'){//This means new user is created from cart check out
        	$data['activatelink'] = $this->url->link('account/register/activateuser', 'u=' .$UUID.'&checkout=1');
        }else{
        	$data['activatelink'] = $this->url->link('account/register/activateuser', 'u=' .$UUID);	
        }
        
        
        $data['subject'] = $subject;
        $otp =  $this->RAND_OTP(100000,999999);
		$data['sms_content'] = $this->OTP_Content($otp);
		$data['otp'] = $otp;
		$data['customer_id'] = $customer_id;
		
		$logger->write('customer::addcustomer data '.print_R($data,TRUE));
		
		$this->model_catalog_postad->senteMail_otp($data);
		
        $this->model_catalog_postad->senteMail_newuser($data);
		
		$this->activateCustomerUsingOTP($data);
		
		
        
		$message .= $this->url->link('account/login', '', 'SSL') . "\n\n";
		$message .= $this->language->get('text_services') . "\n\n";
		$message .= $this->language->get('text_thanks') . "\n";
		$message .= $this->config->get('config_name');

		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');				
		$mail->setTo($data['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		//$mail->send();

		// Send to main admin email if new account email is enabled
		if ($this->config->get('config_account_mail')) {
			$message  = $this->language->get('text_signup') . "\n\n";
			$message .= $this->language->get('text_website') . ' ' . $this->config->get('config_name') . "\n";
			$message .= $this->language->get('text_firstname') . ' ' . $data['firstname'] . "\n";
			$message .= $this->language->get('text_lastname') . ' ' . $data['lastname'] . "\n";
			$message .= $this->language->get('text_customer_group') . ' ' . $customer_group_info['name'] . "\n";

			if ($data['company']) {
				$message .= $this->language->get('text_company') . ' '  . $data['company'] . "\n";
			}

			$message .= $this->language->get('text_email') . ' '  .  $data['email'] . "\n";
			$message .= $this->language->get('text_telephone') . ' ' . $data['telephone'] . "\n";

			$mail->setTo($this->config->get('config_email'));
			$mail->setSubject(html_entity_decode($this->language->get('text_new_customer'), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();

			// Send to additional alert emails if new account email is enabled
			$emails = explode(',', $this->config->get('config_alert_emails'));

			foreach ($emails as $email) {
				if (strlen($email) > 0 && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}
        return $customer_id;
	}

	public function resendOtp($data){
		
	}

	public function editCustomer($data) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
	}

	public function editPassword($email, $password) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

	public function editNewsletter($newsletter) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
	}

	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getCustomerByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getCustomerByToken($token) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = '" . $this->db->escape($token) . "' AND token != ''");

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = ''");

		return $query->row;
	}

	public function getCustomers($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cg.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group cg ON (c.customer_group_id = cg.customer_group_id) ";

		$implode = array();

		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}

		if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
			$implode[] = "LCASE(c.email) = '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "'";
		}

		if (isset($data['filter_customer_group_id']) && !is_null($data['filter_customer_group_id'])) {
			$implode[] = "cg.customer_group_id = '" . $this->db->escape($data['filter_customer_group_id']) . "'";
		}	

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}	

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
		}	

		if (isset($data['filter_ip']) && !is_null($data['filter_ip'])) {
			$implode[] = "c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}	

		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'c.email',
			'customer_group',
			'c.status',
			'c.ip',
			'c.date_added'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}		

		$query = $this->db->query($sql);

		return $query->rows;	
	}

	public function getTotalCustomersByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}

	public function getIps($customer_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->rows;
	}	

	public function isBanIp($ip) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ban_ip` WHERE ip = '" . $this->db->escape($ip) . "'");

		return $query->num_rows;
	}
    
    public function activateCustomerFromEmail($data){
        echo print_R($data,TRUE) .'--';
        echo "SELECT * FROM `" . DB_PREFIX . "customer` WHERE password = '" . $data['u'] . "'";
        
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE password = '" . $data['u'] . "'");
        if($query->row){
            $status = '1';
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET approved = '" . (int)$status . "' WHERE customer_id = '" . (int)$query->row['customer_id'] . "'");
        }
        return $query->row;
    }	


	public function activateCustomerUsingOTP($data){
        //echo print_R($data,TRUE) .'--';
        
         $this->load->model('tool/image');
		 $this->load->library('log');
		 $logger = new Log('swapdeal.log');
		
        $logger->write('activate otp query '."SELECT max(date_updated)  FROM `" . DB_PREFIX . "customer_notification` WHERE otp = '" . $data['otp'] . "' and mobile = '" . $data['mobile'] . "'");
        
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_notification` WHERE otp = '" . $data['otp'] . "' and mobile = '" . $data['telephone'] . "'");
		//$logger->write('Row -' .print_R($query->num_rows,TRUE));
		// return 0;
		
        if($query->row){
            $status = '1';
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET approved = '" . (int)$status . "' WHERE status_otp = 0 AND  customer_id = '" . (int)$query->row['customer_id'] . "'");
        }
        return $query->row;
    }
	
	
	public function validateCustomerUsingOTP($data){
        //echo print_R($data,TRUE) .'--';
        
        $this->load->model('tool/image');
		$this->load->library('log');
		$logger = new Log('swapdeal.log');
		
        $logger->write('activate otp query '."SELECT * FROM `" . DB_PREFIX . "customer_notification` WHERE status_otp = 1 AND otp = '" . $data['otp'] . "' and mobile = '" . $data['mobile'] . "'" . ' ORDER By date_updated DESC LIMIT 1');
        
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_notification` WHERE status_otp = 1 AND otp = '" . $data['otp'] . "' and mobile = '" . $data['mobile'] . "'" . ' ORDER By date_updated DESC LIMIT 1');
		$logger->write('Row -' .print_R($query->num_rows,TRUE));
		// return 0;
		
		if($query->num_rows == 0)
			return 0;
		else
        	return $query->row;
    }
	
	public function fetchCustomerOTP($data){
        //echo print_R($data,TRUE) .'--';
        
        $this->load->model('tool/image');
		$this->load->library('log');
		$logger = new Log('swapdeal.log');
		
        $logger->write('fetch otp query '. "SELECT * FROM " . DB_PREFIX . "customer_notification WHERE status_otp = 0 ");
        
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_notification WHERE status_otp = 0 AND mobile != '' ");
		$logger->write('Row -' .print_R($query->num_rows,TRUE));
		// return 0;
		
		
        return $query->rows;
    }
	
	public function removeCustomerOTP($data){
        //echo print_R($data,TRUE) .'--';
        
        $this->load->model('tool/image');
		$this->load->library('log');
		$logger = new Log('swapdeal.log');
		
         $logger->write('remove otp query '. "UPDATE " . DB_PREFIX . "customer_notification set status_otp = 1 WHERE otp = '" . $data['otp'] . "' and mobile = '" . $data['mobile'] . "'");
        
        $query = $this->db->query("UPDATE " . DB_PREFIX . "customer_notification set status_otp = 1 WHERE otp = '" . $data['otp'] . "' and mobile = '" . $data['mobile'] . "'");
		$logger->write('Row -' .print_R($query->num_rows,TRUE));
		// return 0;
		
		
        return $query->rows;
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
	
	public function OTP_Content($otp){
		
		$opt_string  = $otp . ' is your login OTP. Treat this as confidential. Sharing it with anyone gives them full access to your Drunkcart Account. Drunkcart never calls to verify your OTP.';
		return $opt_string;
	}
	
}
?>
