<?php 
class ControllerProductProductload extends Controller {
	public function index() {
		error_reporting(E_ALL ^ E_WARNING); 
	
		$categoryData = array();
	
		$product_description = array();
	
		$product_store = array(
			'0' => 0
		);
		
		$product_category = array(
			'0' => 19
		);
	    
	
	    $product_layout[] = array(
	    	'layout_id' => ''
	    );
		
	    $product_reward[1] = array(
	    	'points' => ''
	    );
		
		$product_description[1] = array(
			'name' => 'Budweiser 650ml',
			'meta_description' => '',
			'meta_keyword' => '',
			'description' => '',
			'tag' => ''
		);
		
		$product = array(
			'product_description' => $product_description,
			'model' => 'Budweiser 650ml',
		    'sku' => '',
		    'upc' => '',
		    'ean' => '',
		    'jan' => '',
		    'isbn' => '',
		    'mpn' => '',
		    'location' => '', 
		    'price' => '',
		    'tax_class_id' => 0,
		    'quantity' => 1,
		    'minimum' => 1,
		    'subtract' => 1,
		    'stock_status_id' => 5,
		    'shipping' => 1,
		    'keyword' => '',
		    'image' => 'data/Beer/Budweiser.jpeg',
		    'date_available' => '2015-05-05',
		    'length' => '',
		    'width' => '',
		    'height' => '',
		    'length_class_id' => 1,
		    'weight' => '',
		    'weight_class_id' => 1,
		    'status' => 1,
		    'sort_order' => 1,
		    'manufacturer' => '', 
		    'manufacturer_id' => 0,
		    'category' => 'bud',
		    'filter' => '',
		    'download' => '', 
    		'related' => '',
		    'option' => '',
		    'points' =>  '',
		    'vendor' =>  2,
		    'product_category' => $product_category,
		    'product_store' => $product_store,
		    'product_reward' => $product_reward,
		    'product_layout' => $product_layout
			
		);
	    
		
		$this->load->model('catalog/product');
		$this->load->model('account/category');
		//$this->model_catalog_product->addProduct($product);
		
		$objPHPExcel = PHPExcel_IOFactory::load(DIR_SYSTEM . 'Prodcuts.xlsx');
		
		$baseCategoryVal = "";
	foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
		$worksheetTitle     = $worksheet->getTitle();
		// echo "Title:". $worksheetTitle;
		$highestRow         = $worksheet->getHighestRow();
		$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		// echo '<br>Data: <table border="1"><tr>';
		for ($row = 2; $row <= $highestRow; ++ $row) {
			// echo '<tr>';
			$baseCategoryCell = $worksheet->getCellByColumnAndRow(0, $row);
			$baseCategoryIdCell = $worksheet->getCellByColumnAndRow(0, $row);
			$baseSubCategoryIdCell = $worksheet->getCellByColumnAndRow(1, $row);
			$productLCell = $worksheet->getCellByColumnAndRow(2, $row);
			$productPCell = $worksheet->getCellByColumnAndRow(3, $row);
			
			echo $baseCategoryCell->getValue() . '---'. $baseCategoryVal;
			if($baseCategoryIdCell->getValue() != ""){
				$baseCategoryVal = $baseCategoryIdCell->getValue();
				$baseCategoryIdVal = $this->model_account_category->getCategoryIdFromName(trim($baseCategoryVal));
				
				
			}
			$baseSubCategoryIdVal = $this->model_account_category->getCategoryIdFromName(trim($baseSubCategoryIdCell));
			echo "Category id --->".$baseCategoryIdVal.'</br>';
			echo "SubCategory  --->".$baseSubCategoryIdCell->getValue().'</br>';
			echo "SubCategoryId  --->".$baseSubCategoryIdVal.'</br>';
			echo "productL  --->".$productLCell->getValue().'</br>';
			echo "productP  --->".$productPCell->getValue().'</br>';
			// if(($baseCategoryVal != $baseCategoryCell->getValue()) && ($baseCategoryCell->getValue() != "") ){
				// $baseCategoryVal = $baseCategoryCell->getValue();
				// $baseCategoryIdVal = $baseCategoryIdCell->getValue();
				// echo '<td>---'. $baseCategoryIdVal.'---</td></tr><tr>';
				// if($baseCategoryIdVal == ""){
					// $baseCategoryIdVal = $this->model_account_category->getCategoryIdFromName(trim($baseCategoryVal));
				// }
			// }
			
			$product_description[1] = array(
				'name' => $baseSubCategoryIdCell->getValue() . ' '.trim($productLCell->getValue()),
				'meta_description' => '',
				'meta_keyword' => '',
				'description' => '',
				'tag' => ''
			);
			$product_category = array(
				'0' => $baseCategoryIdVal,
				'1' => $baseSubCategoryIdVal
			);
			$product = array(
				'product_description' => $product_description,
				'model' => $baseSubCategoryIdCell->getValue() . ' '.trim($productLCell->getValue()),
			    'sku' => '',
			    'upc' => '',
			    'ean' => '',
			    'jan' => '',
			    'isbn' => '',
			    'mpn' => '',
			    'location' => '', 
			    'price' => $productPCell->getValue(),
			    'tax_class_id' => 0,
			    'quantity' => 10,
			    'minimum' => 1,
			    'subtract' => 1,
			    'stock_status_id' => 5,
			    'shipping' => 1,
			    'keyword' => '',
			    'image' => 'data/Beer/'.$baseSubCategoryIdCell->getValue() . ' '.trim($productLCell->getValue()).'.jpeg',
			    'date_available' => '2015-05-05',
			    'length' => '',
			    'width' => '',
			    'height' => '',
			    'length_class_id' => 1,
			    'weight' => '',
			    'weight_class_id' => 1,
			    'status' => 1,
			    'sort_order' => 1,
			    'manufacturer' => '', 
			    'manufacturer_id' => 0,
			    'category' => 'bud',
			    'filter' => '',
			    'download' => '', 
	    		'related' => '',
			    'option' => '',
			    'points' =>  '',
			    'vendor' =>  2,
			    'product_category' => $product_category,
			    'product_store' => $product_store,
			    'product_reward' => $product_reward,
			    'product_layout' => $product_layout
			
			);
			$this->model_catalog_product->addProduct($product);
			for ($col = 0; $col < $highestColumnIndex; ++ $col) {
				
				
				$cell = $worksheet->getCellByColumnAndRow($col, $row);
	           	$val = $cell->getValue();
				if(($val != "") && ($val != $baseCategoryVal)){
					// echo "<td>";
					// echo $val;
					// echo "</td>";
					
					//echo print_R($categoryData,TRUE);
					echo "<p>";
					//$this->model_account_category->addCategory($categoryData);
					//echo $this->model_account_category->getCategoryIdFromName(trim($baseCategoryVal));
				}
				
			}
			// echo '</tr>';
		}
		// echo '</table>';
	}
		
	}
}
?>		
	