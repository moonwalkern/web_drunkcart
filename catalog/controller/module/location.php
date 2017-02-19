<?php 
class ControllerModuleLocation extends Controller {
	public function index() {
	       
		
        if(isset($this->session->data['city'])){
          $this->data['city'] = $this->session->data['city'];  
        }else{
          $this->data['city'] = 'Select City';  
        }
		
        if(isset($this->session->data['location_name'])){
          $this->data['location_name'] = $this->session->data['location_name'];  
        }else{
          $this->data['location_name'] = '';  
        }
        
        $this->load->model('localisation/zone');
        
        $localities = $this->model_localisation_zone->getAllLocality();
        //echo print_R($localities,TRUE);
        $this->data['localities'] = $localities;
        
        foreach ($localities as $key => $locality) {
            if($locality['popular'] == 1){
                $this->data['popular'][] = $locality;   
            }else{
                $this->data['unpopular'][] = $locality;  
            }
            if($key == 60){
                break;
            }
        }
        $this->data['locationlist_url'] = $this->url->link('module/locationlist');
        $this->session->data['json_popular'] = $this->data['popular'];
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/location.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/location.tpl';
		} else {
			$this->template = 'default/template/module/location.tpl';
		}

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
        
        $json['home_url'] = $this->url->link('common/home');
        $this->response->setOutput(json_encode($json));
    }
    
    public function searchlocation(){
        
        $this->response->setOutput(json_encode( $this->session->data['json_popular']));
    }
}
?>