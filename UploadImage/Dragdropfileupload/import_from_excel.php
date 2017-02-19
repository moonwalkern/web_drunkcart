<?php

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
	// echo print_R($categoryData,TRUE);
			
	error_reporting(E_ALL ^ E_WARNING); 
	require_once 'Classes/PHPExcel/IOFactory.php';
	$objPHPExcel = PHPExcel_IOFactory::load("Tool.xls");
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
			//echo $baseCategoryCell->getValue() . '---'. $baseCategoryVal;
			if(($baseCategoryVal != $baseCategoryCell->getValue()) && ($baseCategoryCell->getValue() != "") ){
				$baseCategoryVal = $baseCategoryCell->getValue();
				//echo '<td>---'. $baseCategoryVal.'---</td></tr><tr>';
			}
			
			for ($col = 0; $col < $highestColumnIndex; ++ $col) {
				
				
				$cell = $worksheet->getCellByColumnAndRow($col, $row);
	           	$val = $cell->getValue();
				if(($val != "") && ($val != $baseCategoryVal) && ($val !='ï¿¼')){
					// echo "<td>";
					// echo $val;
					// echo "</td>";
					$category_description[1] = array(
						'name' => $val,
						'meta_description' => '',
						'meta_keyword' => '',
						'description' => ''
					);
					$categoryData = array(
						'category_description' => $category_description,
						'path' => $baseCategoryVal,
						'filter' =>'',
						'parent_id' => 1, 
						'category_store' => $category_store,
						'keyword' =>'', 
					    'image' => 'data/' .$baseCategoryVal. '/'.trim($val).'.png',
					    'image_small' => '',
					    'column' => '1',
					    'sort_order' => '0',
					    'status' => '1',
					    'category_layout' => $category_layout	
					);
					echo print_R($categoryData,TRUE);
				}
				
			}
			// echo '</tr>';
		}
		// echo '</table>';
	}
	
?>	