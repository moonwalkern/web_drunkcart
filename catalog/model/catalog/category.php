<?php
class ModelCatalogCategory extends Model {
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");
		
		return $query->row;
	}
	
	public function getCategories($parent_id = 0) {
		//echo "SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)" . '</br>';
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");
  
		return $query->rows;
	}
    //This will be used for category filter feature in Post Ad view, also will be used for autocomplete feature.
        
    public function getCategoriesFilter($data) {
		$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' &gt; ') AS name, c.parent_id, c.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c ON (cp.path_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (c.category_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " GROUP BY cp.category_id ORDER BY name";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}        
	//This method fetches the attributes avaliable for a given category.
    public function getCategoryAttributes($category_id) {
        
        //echo "SELECT ca.attribute_id, cad.language_id, cag.attribute_group_id, cag.name,cagd.attribute_group_type FROM " . DB_PREFIX . "category_attribute ca LEFT JOIN " . DB_PREFIX . "category_attribute_description cad ON ca.attribute_id = cad.attribute_id LEFT JOIN " . DB_PREFIX . "category_attribute_group cag ON cad.attribute_id = cag.attribute_id LEFT JOIN " . DB_PREFIX . "category_attribute_group_description cagd ON cag.attribute_group_id = cagd.attribute_group_id WHERE ca.category_id = '" . (int)$category_id . "' ORDER BY cagd.sort_order";
        $query = $this->db->query("SELECT ca.attribute_id, cad.language_id, cag.attribute_group_id, cag.name as attribute_name,cagd.attribute_group_type FROM " . DB_PREFIX . "category_attribute ca LEFT JOIN " . DB_PREFIX . "category_attribute_description cad ON ca.attribute_id = cad.attribute_id LEFT JOIN " . DB_PREFIX . "category_attribute_group cag ON cad.attribute_id = cag.attribute_id LEFT JOIN " . DB_PREFIX . "category_attribute_group_description cagd ON cag.attribute_group_id = cagd.attribute_group_id WHERE ca.category_id = '" . (int)$category_id . "' ORDER BY cagd.sort_order");
        
        //echo print_R($query,TRUE);
        $category_attribute_data = array();
        foreach ($query->rows as $category_group) {
            $category_attribute_data[$category_group['attribute_name']] = $category_group;
            //echo print_R($category_group,TRUE);
            
            //echo "SELECT " . DB_PREFIX . "category_attribute_group_value.values FROM " . DB_PREFIX . "category_attribute_group_value WHERE attribute_group_id = '" . (int)$category_group['attribute_group_id'] . "'" . '</br>';
            $attribute_query = $this->db->query("SELECT " . DB_PREFIX . "category_attribute_group_value.value FROM " . DB_PREFIX . "category_attribute_group_value WHERE attribute_group_id = '" . (int)$category_group['attribute_group_id'] . "'");
            //echo print_R($attribute_query,TRUE);
            $attr_values = "";
             foreach ($attribute_query->rows as $category_group_value) {
                //echo $category_group_value['values'] . "\n";
                $attr_values = $category_group_value['value'] . "," . $attr_values;
             }
              $attr_values = rtrim($attr_values, ',');
             $category_attribute_data[$category_group['attribute_name']]['values'] = $attr_values;
        }
        //echo print_R($category_attribute_data, TRUE);
        
        return $category_attribute_data;
    }
    
    public function getCategoryAttributeValues($product_id, $category_id) {
        
        echo "SELECT ca.attribute_id, cad.language_id, cag.attribute_group_id, cag.name,cagd.attribute_group_type , pav.category_attribute_value FROM " . DB_PREFIX . "category_attribute ca LEFT JOIN " . DB_PREFIX . "category_attribute_description cad ON ca.attribute_id = cad.attribute_id LEFT JOIN " . DB_PREFIX . "category_attribute_group cag ON cad.attribute_id = cag.attribute_id LEFT JOIN " . DB_PREFIX . "category_attribute_group_description cagd ON cag.attribute_group_id = cagd.attribute_group_id LEFT JOIN " . DB_PREFIX . "product_attribute_values pav ON pav.category_attribute_id = cag.attribute_id WHERE ca.category_id = '" . (int)$category_id . "' AND pav.product_id = '" . (int)$product_id  . "' ORDER BY cagd.sort_order";
        $query = $this->db->query("SELECT ca.attribute_id, cad.language_id, cag.attribute_group_id, cag.name,cagd.attribute_group_type, pav.category_attribute_value FROM " . DB_PREFIX . "category_attribute ca LEFT JOIN " . DB_PREFIX . "category_attribute_description cad ON ca.attribute_id = cad.attribute_id LEFT JOIN " . DB_PREFIX . "category_attribute_group cag ON cad.attribute_id = cag.attribute_id LEFT JOIN " . DB_PREFIX . "category_attribute_group_description cagd ON cag.attribute_group_id = cagd.attribute_group_id LEFT JOIN " . DB_PREFIX . "product_attribute_values pav ON pav.category_attribute_id = cag.attribute_id WHERE ca.category_id = '" . (int)$category_id . "' AND pav.product_id = '" . (int)$product_id  . "' ORDER BY cagd.sort_order");
        
        //echo print_R($query,TRUE);
        $category_attribute_data = array();
        foreach ($query->rows as $category_group) {
            $category_attribute_data[$category_group['name']] = $category_group;
            //echo print_R($category_group,TRUE);
            $attribute_query = $this->db->query("SELECT " . DB_PREFIX . "category_attribute_group_value.values FROM " . DB_PREFIX . "category_attribute_group_value WHERE attribute_group_id = '" . (int)$category_group['attribute_group_id'] . "'");
            //echo print_R($attribute_query,TRUE);
            $attr_values = "";
             foreach ($attribute_query->rows as $category_group_value) {
                //echo $category_group_value['values'] . "\n";
                $attr_values = $category_group_value['values'] . "," . $attr_values;
             }
             
             $category_attribute_data[$category_group['name']]['values'] = $attr_values;
        }
        //echo print_R($category_attribute_data, TRUE);
        
        return $category_attribute_data;
    }
    
	public function getCategoryFilters($category_id) {
		$implode = array();
		
		$query = $this->db->query("SELECT filter_id FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");
		
		foreach ($query->rows as $result) {
			$implode[] = (int)$result['filter_id'];
		}
		
		
		$filter_group_data = array();
		
		if ($implode) {
			$filter_group_query = $this->db->query("SELECT DISTINCT f.filter_group_id, fgd.name, fg.sort_order FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_group fg ON (f.filter_group_id = fg.filter_group_id) LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id) WHERE f.filter_id IN (" . implode(',', $implode) . ") AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY f.filter_group_id ORDER BY fg.sort_order, LCASE(fgd.name)");
			
			foreach ($filter_group_query->rows as $filter_group) {
				$filter_data = array();
				
				$filter_query = $this->db->query("SELECT DISTINCT f.filter_id, fd.name FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.filter_id IN (" . implode(',', $implode) . ") AND f.filter_group_id = '" . (int)$filter_group['filter_group_id'] . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY f.sort_order, LCASE(fd.name)");
				
				foreach ($filter_query->rows as $filter) {
					$filter_data[] = array(
						'filter_id' => $filter['filter_id'],
						'name'      => $filter['name']			
					);
				}
				
				if ($filter_data) {
					$filter_group_data[] = array(
						'filter_group_id' => $filter_group['filter_group_id'],
						'name'            => $filter_group['name'],
						'filter'          => $filter_data
					);	
				}
			}
		}
		
		return $filter_group_data;
	}
				
	public function getCategoryLayoutId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return false;
		}
	}
					
	public function getTotalCategoriesByCategoryId($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");
		
		return $query->row['total'];
	}
}
?>