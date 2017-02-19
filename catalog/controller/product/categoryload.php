<?php 
class ControllerProductCategoryload extends Controller {
	public function index() {
		error_reporting(E_ALL ^ E_WARNING); 
	
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
	
	    
		// $categoryData = array(
			// 'category_description' => $category_description,
			// 'path' => 'Beer',
			// 'filter' =>'',
			// 'parent_id' => 1, 
			// 'category_store' => $category_store,
			// 'keyword' =>'', 
		    // 'image' => 'data/Beer/asahi-Japan-sml.png',
		    // 'image_small' => '',
		    // 'column' => '1',
		    // 'sort_order' => '0',
		    // 'status' => '1',
		    // 'category_layout' => $category_layout	
		// );
		//echo print_R($categoryData,TRUE);
		
		$this->load->model('account/category');
		//$this->model_account_category->addCategory($categoryData);
		$objPHPExcel = PHPExcel_IOFactory::load(DIR_SYSTEM . 'example1.xlsx');
		
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
			$baseCategoryIdCell = $worksheet->getCellByColumnAndRow(2, $row);
			echo "Category id --->".$baseCategoryIdCell->getValue().'</br>';
			//echo $baseCategoryCell->getValue() . '---'. $baseCategoryVal;
			if(($baseCategoryVal != $baseCategoryCell->getValue()) && ($baseCategoryCell->getValue() != "") ){
				$baseCategoryVal = $baseCategoryCell->getValue();
				$baseCategoryIdVal = $baseCategoryIdCell->getValue();
				echo '<td>---'. $baseCategoryIdVal.'---</td></tr><tr>';
				if($baseCategoryIdVal == ""){
					$baseCategoryIdVal = $this->model_account_category->getCategoryIdFromName(trim($baseCategoryVal));
				}
			}
			
			for ($col = 0; $col < $highestColumnIndex; ++ $col) {
				
				
				$cell = $worksheet->getCellByColumnAndRow($col, $row);
	           	$val = $cell->getValue();
				if(($val != "") && ($val != $baseCategoryVal)){
					// echo "<td>";
					// echo $val;
					// echo "</td>";
					$category_description[1] = array(
						'name' => trim($val),
						'meta_description' => '',
						'meta_keyword' => '',
						'description' => ''
					);
					$categoryData = array(
						'category_description' => $category_description,
						'path' => $baseCategoryVal,
						'filter' =>'',
						'parent_id' => $baseCategoryIdVal, 
						'category_store' => $category_store,
						'keyword' =>'', 
					    'image' => 'data/' .$baseCategoryVal. '/'.trim($val).'.png',
					    'image_small' => '',
					    'column' => '1',
					    'sort_order' => '0',
					    'status' => '1',
					    'category_layout' => $category_layout	
					);
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
	