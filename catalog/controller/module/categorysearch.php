<?php  
class ControllerModuleCategorySearch extends Controller {
	protected function index() {
		$this->language->load('module/categorysearch');
        $this->loadcategory();
        
        
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['code'] = str_replace('http', 'https', html_entity_decode($this->config->get('categorysearch_code')));
		} else {
			$this->data['code'] = html_entity_decode($this->config->get('categorysearch_code'));
		}
		
        
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/categorysearch.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/categorysearch.tpl';
		} else {
			$this->template = 'default/template/module/categorysearch.tpl';
		}
		
		$this->render();
	}
    
    public function loadcategory(){
        $this->load->model('catalog/category');
        $this->load->model('tool/image');
        $this->load->library('log');
        
        
        $logger = new Log('swapdeal.log');
		$logger->write('categorysearch/cache - started');
        
        $category_data = $this->cache->get('category_data');
		$logger->write('category_data ' . print_R($category_data,TRUE));
        if(!$category_data){
            $logger->write('categorysearch/cache - No Cache data for category_data, so cache it back');
            $category_data = $this->model_catalog_category->getCategories();
			$this->cache->set('category_data', $category_data);
            $logger->write('category_data ' . $category_data);
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
			if(isset($this->request->post['device'])){
				if($this->request->post['device'] == 'iOS'){
					$this->load->library('log');
	        		$logger = new Log('swapdeal.log');
					$logger->write('Post Data ' . $this->request->post['device']);
					$logger->write('Device is iOS****');
					if ($category_data) {
						$json['data'] = $category_data;
						$logger->write('Data sent');
					}else{
						$json['data'] = 'No Data';
						$logger->write('Data Not Sent');
					}	
					
					$this->response->setOutput(json_encode($json));
					
				}
			}
        //}
        $logger->write('categorysearch/cache - end');
    }
}
?>