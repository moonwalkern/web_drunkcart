<?php  
class ControllerModuleAlphaBox extends Controller {
	protected function index() {
		$this->language->load('module/alphabox');
        $this->load->model('catalog/category');
        $this->load->model('tool/image');
        
        $categories = $this->model_catalog_category->getCategories();
        $this->data['categories'] = $categories;
        
        $this->data['categories_all'] = array();
        foreach ($categories as $category){
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
            'thumb' => $this->model_tool_image->resize($category['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
            );
        }
        //echo print_R($this->data['categories_all'],TRUE);
        
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['code'] = str_replace('http', 'https', html_entity_decode($this->config->get('alphabox_code')));
		} else {
			$this->data['code'] = html_entity_decode($this->config->get('alphabox_code'));
		}
		
        
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/alphabox.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/alphabox.tpl';
		} else {
			$this->template = 'default/template/module/alphabox.tpl';
		}
		
		$this->render();
	}
}
?>