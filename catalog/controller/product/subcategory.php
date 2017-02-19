<?php 
class ControllerProductSubcategory extends Controller {
    private $error = array();
    
    public function index() {
        
        $this->language->load('product/subcategory');
        $this->load->model('tool/image');
        $this->load->model('catalog/category');
        
        if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/vlist.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/vlist.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/vlist.css');
		}
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        
        $this->data['heading_title'] = $this->language->get('heading_title');
        
		$this->data['styles'] = $this->document->getStyles();
		$this->data['scripts'] = $this->document->getScripts();
        
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
       
        $this->loadcategory();
        if (($this->request->server['REQUEST_METHOD'] == 'GET') && isset($this->request->get['product_id'])) {
            $this->data['product_id'] =  $this->request->get['product_id'];
        }  
        $incCategory = 0; 
        foreach ($this->data['categories_all'] as $category){
        	if($category['category_id'] == $this->data['product_id']){
	            foreach($category['subcategories'] as $subcategory){
	                $subsubcategory = $this->model_catalog_category->getCategories($subcategory['category_id']);
	                $incCategory = $incCategory+1;
	                if(empty($subsubcategory)){
	                    $subsubcategory_all = 0;                    
	                }else{
	                    $subsubcategory_all = array();
	                    foreach($subsubcategory as $subcategory_array){
	                        //array_push($subcategory_array, 'href' = 'ssss');
	                        $subcategory_array['href'] = $this->url->link('product/category', 'path=' . $subcategory['category_id'] . '_' . $subcategory_array['category_id']);
	                        $subsubcategory_all[] = $subcategory_array;
							$incCategory = $incCategory+1;
	                    }
	                   
	                    //$subsubcategory[] = $this->url->link('product/category', 'path=' . $subcategory['category_id'] . '_' . $subcategory['category_id']);
	                }
	                $this->data['sub_category'][] = array(
	                    'subcategory' =>  $subsubcategory_all,
	                    'subcategory_name' => $subcategory['name'],
	                    'subcategory_id' => $subcategory['category_id'],
	                    'category_name' => $category['name'],
	                    'category_id' => $category['category_id'],
	                    'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $subcategory['category_id'])
	                );
	            }
				
            }
        }  
		$this->data['incCategory'] = $incCategory;
		if(isset($this->data['sub_category'])){
			array_multisort($this->data['sub_category'], SORT_REGULAR);
		}	
		        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/subcategory.tpl')) {
        	$this->template = $this->config->get('config_template') . '/template/product/subcategory.tpl';
        } else {
        	$this->template = 'default/template/product/subcategory.tpl';
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
  
    
    public function loadcategory(){
        $this->load->model('catalog/category');
        $this->load->model('tool/image');
        $this->load->library('log');
        
        
        $logger = new Log('swapdeal.log');
		$logger->write('subcategory/cache - started');
        
        $category_data = $this->cache->get('category_data');
        if(!$category_data){
            $logger->write('subcategory/cache - No Cache data for category_data, so cache it back');
            $category_data = $this->model_catalog_category->getCategories();
			$this->cache->set('category_data', $category_data);
            
        }
        
        
        
        $this->data['categories_all'] = $this->cache->get('category_all_data');//This has all the categories and subcategories.
        
        if(!$this->data['categories_all']){
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
                'imagesmall' => $this->model_tool_image->resize($category['image'], 7, 7),
                'subcategories' => $this->model_catalog_category->getCategories($category['category_id']),
                'href'      => $this->url->link('product/category'),
                );
            }
        	$this->cache->set('category_all_data', $this->data['categories_all']);
            $logger->write('subcategory/cache - No Cache data for category_all_data, so cache it back');
        }
        //echo print_R($this->data['categories_all'],TRUE);
        $logger->write('subcategory/cache - end');
    }
    
    public function getsubcategory() {
        
        if(isset($this->request->get['category_id'])){
            $category_id = $this->request->get['category_id'];
        }else{
            return;
        }
        
        $json = array();
        
        $this->loadcategory();
        $image = "";
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
					
                    $json[][] = array(
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
                }
            }
            
            
        } 
       
        
        
        //foreach($results as $result) {
//            $localities = $this->model_localisation_zone->getLocality($result['zone_id']);
//            
//            foreach($localities as $locality)
//            $json[] = array(
//					'state_id' => $result['zone_id'],
//                    'locality_id' => $locality['locality_id'],
//                    'locality_name' => $locality['locality_name']
//				);
//        }
//        
        
                    
		array_multisort($json, SORT_REGULAR);
        $this->response->setOutput(json_encode($json));
	}   


}
?>