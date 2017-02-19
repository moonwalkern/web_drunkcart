<?php
class ControllerProductProduct extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('product/product');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);

		$this->load->model('catalog/category');

		if (isset($this->request->get['path'])) {
			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$this->data['breadcrumbs'][] = array(
						'text'      => $category_info['name'],
						'href'      => $this->url->link('product/category', 'path=' . $path),
						'separator' => $this->language->get('text_separator')
					);
				}
			}
            
			// Set the last category breadcrumb
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$url = '';

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				$this->data['breadcrumbs'][] = array(
					'text'      => $category_info['name'],
					'href'      => $this->url->link('product/category', 'path=' . $this->request->get['path'].$url),
					'separator' => $this->language->get('text_separator')
				);
			}
		}

		$this->load->model('catalog/manufacturer');

		if (isset($this->request->get['manufacturer_id'])) {
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_brand'),
				'href'      => $this->url->link('product/manufacturer'),
				'separator' => $this->language->get('text_separator')
			);

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

			if ($manufacturer_info) {
				$this->data['breadcrumbs'][] = array(
					'text'	    => $manufacturer_info['name'],
					'href'	    => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url),
					'separator' => $this->language->get('text_separator')
				);
			}
		}

		if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_search'),
				'href'      => $this->url->link('product/search', $url),
				'separator' => $this->language->get('text_separator')
			);
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);
        

		if ($product_info) {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$this->data['breadcrumbs'][] = array(
				'text'      => $product_info['name'],
				'href'      => $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id']),
				'separator' => $this->language->get('text_separator')
			);

			$this->document->setTitle($product_info['name']);
			$this->document->setDescription($product_info['meta_description']);
			$this->document->setKeywords($product_info['meta_keyword']);
			$this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');
			$this->document->addScript('catalog/view/javascript/jquery/tabs.js');
			$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');

			$this->data['heading_title'] = $product_info['name'];

			$this->data['text_select'] = $this->language->get('text_select');
			$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$this->data['text_model'] = $this->language->get('text_model');			$this->data['text_reward'] = $this->language->get('text_reward');
			$this->data['text_points'] = $this->language->get('text_points');
			$this->data['text_discount'] = $this->language->get('text_discount');
			$this->data['text_stock'] = $this->language->get('text_stock');
			$this->data['text_posted_by'] = $this->language->get('text_posted_by');
			$this->data['text_condition'] = $this->language->get('text_condition');
			$this->data['text_price'] = $this->language->get('text_price');
			$this->data['text_tax'] = $this->language->get('text_tax');
			$this->data['text_discount'] = $this->language->get('text_discount');
			$this->data['text_option'] = $this->language->get('text_option');
			$this->data['text_qty'] = $this->language->get('text_qty');
			$this->data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
			$this->data['text_or'] = $this->language->get('text_or');
			$this->data['text_write'] = $this->language->get('text_write');
			$this->data['text_sms'] = $this->language->get('text_sms');
            $this->data['text_email'] = $this->language->get('text_email');
			$this->data['text_note'] = $this->language->get('text_note');
			$this->data['text_share'] = $this->language->get('text_share');
			$this->data['text_wait'] = $this->language->get('text_wait');
			$this->data['text_tags'] = $this->language->get('text_tags');
            $this->data['text_attributes'] = $this->language->get('text_attributes');

			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_review'] = $this->language->get('entry_review');
			$this->data['entry_rating'] = $this->language->get('entry_rating');
			$this->data['entry_good'] = $this->language->get('entry_good');
			$this->data['entry_bad'] = $this->language->get('entry_bad');
			$this->data['entry_captcha'] = $this->language->get('entry_captcha');
            $this->data['entry_message'] = $this->language->get('entry_message');
            $this->data['entry_mobile'] = $this->language->get('entry_mobile');
            $this->data['entry_email'] = $this->language->get('entry_email');

			$this->data['button_cart'] = $this->language->get('button_cart');
			$this->data['button_wishlist'] = $this->language->get('button_wishlist');
			$this->data['button_compare'] = $this->language->get('button_compare');
			$this->data['button_upload'] = $this->language->get('button_upload');
			$this->data['button_continue'] = $this->language->get('button_continue');
			$this->data['button_sent'] = $this->language->get('button_sent');

			$this->load->model('catalog/review');

			$this->data['tab_description'] = $this->language->get('tab_description');
			$this->data['tab_attribute'] = $this->language->get('tab_attribute');
			$this->data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);
			$this->data['tab_related'] = $this->language->get('tab_related');
			$this->data['tab_sendsms'] = $this->language->get('tab_sendsms');
			$this->data['tab_email'] = $this->language->get('tab_email');

			$this->data['product_id'] = $this->request->get['product_id'];
			$this->data['manufacturer'] = $product_info['manufacturer'];
			$this->data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
			$this->data['model'] = $product_info['model'];
			$this->data['reward'] = $product_info['reward'];
			$this->data['points'] = $product_info['points'];

			if ($product_info['quantity'] <= 0) {
				$this->data['stock'] = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$this->data['stock'] = $product_info['quantity'];
			} else {
				$this->data['stock'] = $this->language->get('text_instock');
			}
            
            $product_attribute_values = $this->model_catalog_product->getProductCategoryAttributes($product_id);
            
            //echo print_R($product_attribute_values, TRUE);
           
          //echo $product_attribute_values['category_attribute_value'];
            $this->data['attributes_values'] = array();
            if(!isset($product_attribute_values['category_attribute_value'])){
                foreach ($product_attribute_values as $product_attributes) {
                    $this->data['attributes_values'][] = array(
                        'attribute' =>  $product_attributes['category_attribute_name'],
                        'values' =>  $product_attributes['category_attribute_value']
                    );
                }
            }
			 // echo $this->session->data['location_name'];
             // echo $this->session->data['location_id'];
             // echo $this->session->data['city_id'];
             // echo $this->session->data['city'];
            $states_id = '';
			$locality_id = $this->session->data['location_id'];
            $product_customer = $this->model_catalog_product->getProductVendor($product_id,$states_id,$locality_id);
			$this->load->model('tool/image');

            $this->data['mobile'] = $product_customer['mobile'];
            $this->data['email'] = $product_customer['email'];
            $this->data['name'] =  $product_customer['firstname'];
            //$this->data['type_customer'] = $product_customer['type_customer'];
            $this->data['type_customer'] = 'vendor';
            $this->data['product_condition'] = 'new';
            // $this->data['product_condition'] = $product_customer['product_condition'] ; 
            $this->data['date_added'] = $product_info['date_added'] ;
            $this->data['image_local'] = $this->model_tool_image->image_real($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
            $this->data['product_location'] =  $product_customer['state'] . ',' .  $product_customer['city'] . ',' .$product_customer['locality_name'];
            
            // if($product_customer['type_ad'] == 'Buy'){
                // $this->data['tab_details'] = $this->language->get('tab_buyer');
            // }else{
                // $this->data['tab_details'] = $this->language->get('tab_seller');
            // }
            
            if (isset($this->request->get['path'])) {
				$path = '';
	
				$parts = explode('_', (string)$this->request->get['path']);
				// echo print_R($parts,TRUE);
				$product_info['category_id'] = $parts[0];
			}
            $this->data['product_vendor'] = array();
			// echo print_R($product_info,TRUE);
			$product_info['location_id'] = $locality_id;
            $product_vendors = $this->model_catalog_product->getVendorProduct($product_info);
			foreach ($product_vendors as $product_vendor) {
				$this->data['product_vendor'][] = array(
				        'product_id' =>  $product_vendor['product_id'],
				        'product_name' =>  $product_vendor['model'],
				        'category_id' =>  $product_vendor['category_id'],
				        'vendor_id' =>  $product_vendor['user_id'],
				        'vendor_name' =>  $product_vendor['firstname'] . ' ' . $product_vendor['lastname'],
				        'vendor_email' =>  $product_vendor['email'],
				        'mobile' =>  $product_vendor['mobile'],
				        'phone' =>  $product_vendor['phone'],
				        'states_id' =>  $product_vendor['states_id'],
				        'locality_id' =>  $product_vendor['locality_id'],
				        'city' =>  $product_vendor['city'],
				        'address_1' =>  $product_vendor['address_1'],
				        'address_2' =>  $product_vendor['address_2'],
				    );
			}
			// echo print_R($this->data['product_vendor'], TRUE);
			$this->session->data['product_vendor'] = $this->data['product_vendor'];
            
            $this->load->model('localisation/zone');
            $zone_data = $this->model_localisation_zone->getZone($product_customer['zone_id']);
           
            $url = '';
            
            if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

            if(!empty($zone_data['name'])){ 
                $this->data['locationcrumbs'][] = array(
    				'text'      => $zone_data['name'],
    				'href'      => $this->url->link('product/search', $url . '&state=' . $zone_data['name']),
    				'separator' => false
    			);
            
            }
            if(!empty($product_customer['city'])){
                $this->data['locationcrumbs'][] = array(
    				'text'      => $product_customer['city'],
    				'href'      => $this->url->link('product/search', $url . '&city=' . $product_customer['city']),
    				'separator' => $this->language->get('text_separator_location')
    			);
            }
            if(!empty($product_customer['locality_name'])){
                $this->data['locationcrumbs'][] = array(
    				'text'      => $product_customer['locality_name'],
    				'href'      => $this->url->link('product/search', $url . '&locality=' . $product_customer['locality_name']),
    				'separator' => $this->language->get('text_separator_location')
    			);
            }
            
			if ($product_info['image']) {
				$this->data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			} else {
				$this->data['popup'] = '';
			}

			if ($product_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
			} else {
				$this->data['thumb'] = '';
			}

			$this->data['images'] = array();

			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

			foreach ($results as $result) {
				$this->data['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
				);
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$this->data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$this->data['price'] = false;
			}

			if ((float)$product_info['special']) {
				$this->data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$this->data['special'] = false;
			}

			if ($this->config->get('config_tax')) {
				$this->data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
			} else {
				$this->data['tax'] = false;
			}

			$discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

			$this->data['discounts'] = array();

			foreach ($discounts as $discount) {
				$this->data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')))
				);
			}

			$this->data['options'] = array();

			foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) {
				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
					$option_value_data = array();

					foreach ($option['option_value'] as $option_value) {
						if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
							if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
								$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
							} else {
								$price = false;
							}

							$option_value_data[] = array(
								'product_option_value_id' => $option_value['product_option_value_id'],
								'option_value_id'         => $option_value['option_value_id'],
								'name'                    => $option_value['name'],
								'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
								'price'                   => $price,
								'price_prefix'            => $option_value['price_prefix']
							);
						}
					}

					$this->data['options'][] = array(
						'product_option_id' => $option['product_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option_value_data,
						'required'          => $option['required']
					);
				} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
					$this->data['options'][] = array(
						'product_option_id' => $option['product_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option['option_value'],
						'required'          => $option['required']
					);
				}
			}

			if ($product_info['minimum']) {
				$this->data['minimum'] = $product_info['minimum'];
			} else {
				$this->data['minimum'] = 1;
			}

			$this->data['review_status'] = $this->config->get('config_review_status');
			$this->data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
			$this->data['rating'] = (int)$product_info['rating'];
			$this->data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
			$this->data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);

			$this->data['products'] = array();

			$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				} else {
					$image = false;
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

				$this->data['products'][] = array(
					'product_id' => $result['product_id'],
					'thumb'   	 => $image,
					'name'    	 => $result['name'],
					'price'   	 => $price,
					'special' 	 => $special,
					'rating'     => $rating,
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}

			$this->data['tags'] = array();

			if ($product_info['tag']) {
				$tags = explode(',', $product_info['tag']);

				foreach ($tags as $tag) {
					$this->data['tags'][] = array(
						'tag'  => trim($tag),
						'href' => $this->url->link('product/search', 'tag=' . trim($tag))
					);
				}
			}

			$this->data['text_payment_profile'] = $this->language->get('text_payment_profile');
			$this->data['profiles'] = $this->model_catalog_product->getProfiles($product_info['product_id']);

			$this->model_catalog_product->updateViewed($this->request->get['product_id']);

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/product.tpl';
			} else {
				$this->template = 'default/template/product/product.tpl';
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
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('product/product', $url . '&product_id=' . $product_id),
				'separator' => $this->language->get('text_separator')
			);

			$this->document->setTitle($this->language->get('text_error'));

			$this->data['heading_title'] = $this->language->get('text_error');

			$this->data['text_error'] = $this->language->get('text_error');

			$this->data['button_continue'] = $this->language->get('button_continue');

			$this->data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
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

	public function review() {
		$this->language->load('product/product');

		$this->load->model('catalog/review');

		$this->data['text_on'] = $this->language->get('text_on');
		$this->data['text_no_reviews'] = $this->language->get('text_no_reviews');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$this->data['reviews'] = array();

		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);

		$results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);

		foreach ($results as $result) {
			$this->data['reviews'][] = array(
				'author'     => $result['author'],
				'text'       => $result['text'],
				'rating'     => (int)$result['rating'],
				'reviews'    => sprintf($this->language->get('text_reviews'), (int)$review_total),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/review.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/review.tpl';
		} else {
			$this->template = 'default/template/product/review.tpl';
		}

		$this->response->setOutput($this->render());
	}

	public function getRecurringDescription() {
		$this->language->load('product/product');
		$this->load->model('catalog/product');

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->post['profile_id'])) {
			$profile_id = $this->request->post['profile_id'];
		} else {
			$profile_id = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = $this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		$product_info = $this->model_catalog_product->getProduct($product_id);
		$profile_info = $this->model_catalog_product->getProfile($product_id, $profile_id);

		$json = array();

		if ($product_info && $profile_info) {

			if (!$json) {
				$frequencies = array(
					'day' => $this->language->get('text_day'),
					'week' => $this->language->get('text_week'),
					'semi_month' => $this->language->get('text_semi_month'),
					'month' => $this->language->get('text_month'),
					'year' => $this->language->get('text_year'),
				);

				if ($profile_info['trial_status'] == 1) {
					$price = $this->currency->format($this->tax->calculate($profile_info['trial_price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')));
					$trial_text = sprintf($this->language->get('text_trial_description'), $price, $profile_info['trial_cycle'], $frequencies[$profile_info['trial_frequency']], $profile_info['trial_duration']) . ' ';
				} else {
					$trial_text = '';
				}

				$price = $this->currency->format($this->tax->calculate($profile_info['price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')));

				if ($profile_info['duration']) {
					$text = $trial_text . sprintf($this->language->get('text_payment_description'), $price, $profile_info['cycle'], $frequencies[$profile_info['frequency']], $profile_info['duration']);
				} else {
					$text = $trial_text . sprintf($this->language->get('text_payment_until_canceled_description'), $price, $profile_info['cycle'], $frequencies[$profile_info['frequency']], $profile_info['duration']);
				}

				$json['success'] = $text;
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function write() {
		$this->language->load('product/product');

		$this->load->model('catalog/review');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			if (empty($this->request->post['rating'])) {
				$json['error'] = $this->language->get('error_rating');
			}

			if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
				$json['error'] = $this->language->get('error_captcha');
			}

			if (!isset($json['error'])) {
				$this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function captcha() {
		$this->load->library('captcha');

		$captcha = new Captcha();

		$this->session->data['captcha'] = $captcha->getCode();

		$captcha->showImage();
	}

	public function upload() {
		$this->language->load('product/product');

		$json = array();

		if (!empty($this->request->files['file']['name'])) {
			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));

			if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
				$json['error'] = $this->language->get('error_filename');
			}

			// Allowed file extension types
			$allowed = array();

			$filetypes = explode("\n", $this->config->get('config_file_extension_allowed'));

			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}

			if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Allowed file mime types
			$allowed = array();

			$filetypes = explode("\n", $this->config->get('config_file_mime_allowed'));

			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}

			if (!in_array($this->request->files['file']['type'], $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Check to see if any PHP files are trying to be uploaded
			$content = file_get_contents($this->request->files['file']['tmp_name']);

			if (preg_match('/\<\?php/i', $content)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}

		if (!$json && is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
			$file = basename($filename) . '.' . md5(mt_rand());

			// Hide the uploaded file name so people can not link to it directly.
			$json['file'] = $this->encryption->encrypt($file);

			move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);

			$json['success'] = $this->language->get('text_upload');
		}

		$this->response->setOutput(json_encode($json));
	}
    
    public function sentmessage() {
		$this->language->load('product/product');

		$this->load->model('catalog/postad');

		$json = array();
        
        //echo print_R($this->request->post,TRUE);
        
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['mobile']) < 3) || (utf8_strlen($this->request->post['mobile']) > 25)) {
				$json['error'] = $this->language->get('error_mobile');
			}

			if ((utf8_strlen($this->request->post['text']) < 5) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
				$json['error'] = $this->language->get('error_email');
			}

			if (!isset($json['error'])) {
				$this->model_catalog_postad->addMessageToSent($this->request->get['product_id'], $this->request->post);
				
                if($this->request->post['type_message'] == 'email'){ //Do this only for email 
                    //Building email data array
                    $this->load->model('catalog/postad');
        
                    
                    
                    if($this->request->post['by'] == 'Buyer'){
                        //Seller email template.
                        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/expresstemplatesellerforbuy.tpl')) {
                			$this->template = DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/expresstemplatesellerforbuy.tpl';
                		} else {
                			$this->template = 'default/template//mail/expresstemplatesellerforbuy.tpl';
                		}
                    }else{
                        //Seller email template.
                        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/expresstemplateseller.tpl')) {
                			$this->template = DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/expresstemplateseller.tpl';
                		} else {
                			$this->template = 'default/template//mail/expresstemplateseller.tpl';
                		}
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
                    
                    $this->data['type'] = $this->request->post['by'];
                    
                    $this->language->load('mail/postad');
                    $this->data['text_title'] = $this->language->get('text_title');
                    
                    
                    $this->data['text_welcome'] = $this->language->get('text_welcome');
                    $this->data['text_greeting_buyer'] = $this->language->get('text_greeting_buyer');
                    $this->data['text_greeting_seller'] = $this->language->get('text_greeting_seller');
                    $this->data['text_info_seller'] = $this->language->get('text_info_seller');
                    $this->data['text_info_buyer'] = $this->language->get('text_info_buyer');
                    $this->data['text_reply_buyer'] = $this->language->get('text_reply_buyer');
                    $this->data['text_reply_seller'] = $this->language->get('text_reply_seller');
                    $this->data['text_reply'] = $this->language->get('text_reply');
                    $this->data['text_details_buyer'] = $this->language->get('text_details_buyer');
                    $this->data['text_details_seller'] = $this->language->get('text_details_seller');
                    $this->data['text_location'] = $this->language->get('text_location');
                    $this->data['text_email'] = $this->language->get('text_email');
                    $this->data['text_mobile'] = $this->language->get('text_mobile');
                    $this->data['text_date'] = $this->language->get('text_date');
                    $this->data['text_price'] = $this->language->get('text_price');
                    $this->data['text_subject_seller'] = $this->language->get('text_subject_seller');
                    $this->data['text_subject_buyer'] = $this->language->get('text_subject_buyer');
                    $this->data['text_subject_seller_buyer'] = $this->language->get('text_subject_seller_buyer');
                    $this->data['text_subject_buyer_seller'] = $this->language->get('text_subject_buyer_seller');

                    
                    
                    //Data for email template.
                    $this->data['product_id']= $this->request->get['product_id'];
                    $this->data['username'] = 'your@gmail.com';
                    $this->data['password'] = $this->language->get('text_message_account');
                    $this->data['forgotpassword'] = $this->url->link('account/forgotten', '', 'SSL');
                       
                    $this->data['message']  = $this->mywordwrap($this->request->post['text'], 50, "<br>"); //Message from customer.
                    
                    $this->data['link']  = $this->url->link('product/product', 'product_id=' .$this->data['product_id']);
                    $this->data['image'] = $this->request->post['image'];
                    $this->data['price'] = $this->request->post['price'];
                    $this->data['date'] = date( 'd/M/Y', strtotime($this->request->post['date']));
                    $this->data['email'] = $this->request->post['email']; //email id of the interested buyer/seller
                    $this->data['mobile'] = $this->request->post['mobile']; //mobile of the interested buyer/seller
                    $this->data['mobile_to'] = $this->request->post['mobile_to']; //mobile of the seller/buyer
                    $this->data['email_to'] = $this->request->post['email_to']; //email id of the seller/buyer
                    $this->data['postedby'] = $this->data['type']; //type of customer buyer/seller
                    $this->data['condition'] = $this->request->post['condition'];
                    $this->data['type_customer'] = $this->request->post['type_customer']; //individual
                    $this->data['replylink'] = "mailto:". $this->data['email_to']. "?subject=Reply for the item ID-: ". $this->data['product_id'];
                    $this->data['location'] =  $this->request->post['location'];
                    //echo print_R($this->data, TRUE);
                
                    if($this->request->post['by'] == 'Buyer'){
                        $this->data['subject'] = $this->data['text_subject_seller_buyer'];
                    }
                    else{
                        $this->data['subject'] = $this->data['text_subject_seller'];    
                    }
                    
                    
                    $this->model_catalog_postad->senteMail($this->data);
                    $this->data = $this->data_switch($this->data);
                    $this->model_catalog_postad->senteMail($this->data);
                    $json['success'] = $this->language->get('text_success_message');
                    $this->response->setOutput(json_encode($json));
                    
                }
			}
		}

		$this->response->setOutput(json_encode($json));
	}
    
    function mywordwrap($str, $nochar, $endchar){
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
    /*
    /This menthod will switch the buyer and seller email.
    */
    public function data_switch($data){
        
        
        $this->language->load('mail/postad');
        
        if($data['type'] == 'Buyer'){
            //Buyer email template.
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/expresstemplatebuyerforsell.tpl')) {
    			$this->template = DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/expresstemplatebuyerforsell.tpl';
    		} else {
    			$this->template = 'default/template/mail/expresstemplatebuyerforsell.tpl';
    		} 
        }
        else{
           //Buyer email template.
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/expresstemplatebuyer.tpl')) {
    			$this->template = DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/expresstemplatebuyer.tpl';
    		} else {
    			$this->template = 'default/template/mail/expresstemplatebuyer.tpl';
    		} 
        }
        
        $data['mail_template'] = $this->template;
        $email = $data['email_to'];
        $mobile = $data['mobile_to'];
        $data['email_to'] = $data['email'];
        $data['mobile_to'] = $data['mobile'];
        
        $data['email'] = $email;
        $data['mobile'] = $mobile;
        
        $data['replylink'] = "mailto:". $this->data['email']. "?subject=Reply for the item ";
        if($data['type'] == 'Buyer'){
            $this->data['subject'] = $this->data['text_subject_buyer_seller'];
        }
        else{
            $this->data['subject'] = $this->data['text_subject_buyer'];
        }
        $data['subject'] = $this->data['text_subject_buyer'];
        return $data;
    }
}
?>