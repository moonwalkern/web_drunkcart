<?php 
class ControllerProductCategoryload extends Controller {
	public function index() {

	
		$categoryData = array();
	
		$category_description = array();
	
		$category_description[1] = array(
			'name' => 'Asahi',
			'meta_description' => '',
			'meta_keyword' => '',
			'description' => ''
		);
		
		$category_store = array(
			'0' => 0
		);
	    
	
	    $category_layout[] = array(
	    	'layout_id' => ''
	    );
	
	    
		$categoryData = array(
			'category_description' => $category_description,
			'path' => 'Beer',
			'filter' =>'',
			'parent_id' => 1, 
			'category_store' => $category_store,
			'keyword' =>'', 
		    'image' => 'data/Beer/asahi-Japan-sml.png',
		    'image_small' => '',
		    'column' => '1',
		    'sort_order' => '0',
		    'status' => '1',
		    'category_layout' => $category_layout	
		);
		echo print_R($categoryData,TRUE);
		
		$this->load->model('catalog/category');
		
		$this->model_catalog_category->addCategory($categoryData);
		
	}
}
?>		
	