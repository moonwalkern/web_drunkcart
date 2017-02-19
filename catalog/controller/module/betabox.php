<?php  
class ControllerModulebetabox extends Controller {
	protected function index() {
		$this->language->load('module/betabox');
        $this->load->model('catalog/category');
        $this->load->model('tool/image');

        $this->data['categories_all'] = array();
        $this->data['categories_all'] = $this->cache->get('category_all_data');
        if(!$this->data['categories_all']){
            $this->load->library('log');
            $logger = new Log('swapdeal.log');
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
                'subcategories' => $this->model_catalog_category->getCategories($category['category_id'])
                );
            }
        	$this->cache->set('category_all_data', $this->data['categories_all']);
            $logger->write('categorysearch/cache - No Cache data for category_all_data, so cache it back');
        }
        
        $this->data['subcategories_popular'] = array();
        $key = 0;
        foreach ($this->data['categories_all'] as $category){
            
            foreach($category['subcategories'] as $subcategory){
                
                $this->data['subcategories_popular'][] = array(
                    'subcategory' =>  $subcategory, 
                    'category_name' => $category['name']
                );
                if($key == 8){
                    break 2;
                }     
                $key = $key +1;
            }
            
        }                
        
       // echo print_R($this->data['subcategories_popular'],TRUE);
        
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['code'] = str_replace('http', 'https', html_entity_decode($this->config->get('betabox_code')));
		} else {
			$this->data['code'] = html_entity_decode($this->config->get('betabox_code'));
		}
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/betabox.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/betabox.tpl';
		} else {
			$this->template = 'default/template/module/betabox.tpl';
		}
		
		$this->render();
	}
}
?>