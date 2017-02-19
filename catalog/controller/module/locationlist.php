<?php 
class ControllerModuleLocationlist extends Controller {
	public function index() {
	   
		
        if(isset($this->session->data['city'])){
          $this->data['city'] = $this->session->data['city'];  
        }else{
          $this->data['city'] = 'Select City';
        }
		$this->language->load('module/location');

        $this->load->model('localisation/zone');
        
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['text_search'] = $this->language->get('text_search');
        $localities = $this->model_localisation_zone->getAllLocality();
        
        
        $this->data['localities'] = $localities;
        
        foreach ($localities as $key => $locality) {
            if($locality['popular'] == 1){
                $this->data['popular'][] = $locality;   
            }

        }
        
        $localities = $this->model_localisation_zone->getAllLocalityAlpha();
        foreach ($localities as $key => $locality) {
            $this->data['unpopular'][] = $locality;

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
        
        $this->data['page_link']= $this->url->link('module/locationlist');
        
        $this->session->data['json_popular'] = $this->data['popular'];
        $this->session->data['json_all_localities'] = $localities;
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/locationlist.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/locationlist.tpl';
		} else {
			$this->template = 'default/template/module/locationlist.tpl';
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
    
    public function setlocation() {
        $this->language->load('module/location');
        
        $json['success'] = $this->language->get('text_success_message');
        if (isset($this->request->post['location_id'])) {
            unset($this->session->data['location_name']);
            unset($this->session->data['location_id']);
            unset($this->session->data['city_id']);
            unset($this->session->data['city']);
            $this->session->data['location_name'] = $this->request->post['location_name'];
            $this->session->data['location_id'] = $this->request->post['location_id'];
            $this->session->data['city_id'] = $this->request->post['city_id'];
            $this->session->data['city'] = $this->request->post['city'];
        }
        
        $this->response->setOutput(json_encode($json));
    }
    
    public function searchlocation(){        
        $this->response->setOutput(json_encode( $this->session->data['json_all_localities']));
    }
    
    public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$filter = $this->request->get['filter_name'];
			$results = $this->session->data['json_all_localities'];
            
			foreach ($results as $result) {
                if(stripos($result['locality_name'], $filter) !== false){
    				$json[] = array(
    					'locality_id' => $result['locality_id'], 
    					'locality_name'        => strip_tags(html_entity_decode($result['locality_name'], ENT_QUOTES, 'UTF-8'))
    				);
                }
			}		
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['locality_name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->setOutput(json_encode($json));
	}
    
}
?>