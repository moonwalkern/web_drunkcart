<?php  
class ControllerModuleSearchFilter extends Controller {
	protected function index($setting) {
		$this->language->load('module/searchfilter');
        $this->load->model('setting/extension');
		$this->data['heading_title_brands'] = $this->language->get('heading_title_brands');
        
        $this->document->addScript('catalog/view/javascript/jquery/jscrollpane/jquery.jscrollpane.min.js');
        
        if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/buttons.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/buttons.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/buttons.css');
		}

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		if (isset($parts[0])) {
			$this->data['category_id'] = $parts[0];
		} else {
			$this->data['category_id'] = 0;
		}

		if (isset($parts[1])) {
			$this->data['child_id'] = $parts[1];
		} else {
			$this->data['child_id'] = 0;
		}

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');
        
		$this->data['categories'] = array();
        $this->data['currency'] = $this->currency->getSymbolLeft();
        
        
        
       	$categories_attributes = $this->model_catalog_category->getCategoryAttributes($this->data['child_id']);
        
        $this->data['categories_attributes'] = $categories_attributes;
		$categories = $this->model_catalog_category->getCategories($this->data['child_id']);
        $this->data['brands'] = "";
		foreach ($categories as $category) {
			//$total = $this->model_catalog_product->getTotalProducts(array('filter_category_id' => $category['category_id']));

			$children_data = array();

			$children = $this->model_catalog_category->getCategories($category['category_id']);

			foreach ($children as $child) {
				$data = array(
					'filter_category_id'  => $child['category_id'],
					'filter_sub_category' => true
				);

				//$product_total = $this->model_catalog_product->getTotalProducts($data);

				//$total += $product_total;

				$children_data[] = array(
					'category_id' => $child['category_id'],
					'name'        => $child['name'] ,
					'href'        => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])	
				);		
			}

			$this->data['categories'][] = array(
				'category_id' => $category['category_id'],
				'name'        => $category['name'] ,
				'children'    => $children_data,
				'href'        => $this->url->link('product/category', 'path=' . $category['category_id'])
			);
            $this->data['brands'] = $this->data['brands'] . $category['name'] . ',' ;
            
            
		}
        $this->data['brands'] = rtrim($this->data['brands'], ',');
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/searchfilter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/searchfilter.tpl';
		} else {
			$this->template = 'default/template/module/searchfilter.tpl';
		}

		$this->render();
	}
}
?>