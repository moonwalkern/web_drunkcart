<?php
class ModelAccountCategory extends Model {
	public function addCategory($data) {
	  	//echo print_R($data,TRUE);die;
		$this->db->query("INSERT INTO " . DB_PREFIX . "category SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");

		$category_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "', image_small = '" . $this->db->escape(html_entity_decode($data['image_small'], ENT_QUOTES, 'UTF-8'))  . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$level = 0;

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");

		foreach ($query->rows as $result) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

			$level++;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");

		if (isset($data['category_filter'])) {
			foreach ($data['category_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		// Set which layout to use with this category
		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('category');
	}

	public function editCategory($category_id, $data) {
	    
		$this->db->query("UPDATE " . DB_PREFIX . "category SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "', image_small = '" . $this->db->escape(html_entity_decode($data['image_small'], ENT_QUOTES, 'UTF-8')) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");

		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE path_id = '" . (int)$category_id . "' ORDER BY level ASC");

		if ($query->rows) {
			foreach ($query->rows as $category_path) {
				// Delete the path below the current one
				$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' AND level < '" . (int)$category_path['level'] . "'");

				$path = array();

				// Get the nodes new parents
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Get whats left of the nodes current path
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Combine the paths with a new level
				$level = 0;

				foreach ($path as $path_id) {
					$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_path['category_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");

					$level++;
				}
			}
		} else {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_id . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', level = '" . (int)$level . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['category_filter'])) {
			foreach ($data['category_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
			}		
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['category_store'])) {		
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id. "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
        
        $this->deleteCategoryAttribute($category_id);
        
        $data['attribute_values_save'] = array();
        foreach($_POST['attribute_name'] as $key =>$value ){
                $data['attribute_values_save'][] = array(
                    'id'        => $key,
                    'name'      => $_POST['attribute_name'][$key]['position'],
                    'type'      => $_POST['attribute_type'][$key]['position'],
                    'value'     => $_POST['attribute_value'][$key]['position']
                );
                $sort_order = 1;
                $language_id = 1;
                echo "Inserting new attributes";
                $this->db->query("INSERT INTO " . DB_PREFIX . "category_attribute SET sort_order=" . (int)$sort_order . ", category_id = " . (int)$category_id . "");
                $attribute_id = $this->db->getLastId();
                $this->db->query("INSERT INTO " . DB_PREFIX . "category_attribute_group SET sort_order=" . (int)$sort_order . ", attribute_id = " . (int)$attribute_id . ", name = '" . $_POST['attribute_name'][$key]['position'] . "'");
                $attribute_group_id = $this->db->getLastId();
                $this->db->query("UPDATE " . DB_PREFIX . "category_attribute SET attribute_group_id = " . (int)$attribute_group_id . " WHERE attribute_id = " . (int)$attribute_id . "");
                               
                $this->db->query("INSERT INTO " . DB_PREFIX . "category_attribute_description SET attribute_id=" . (int)$attribute_id . ", language_id = " . (int)$language_id . ", name = '" . $_POST['attribute_name'][$key]['position'] . "'");                
                echo "INSERT INTO " . DB_PREFIX . "category_attribute_group_description SET attribute_group_id=" . (int)$attribute_group_id . ", sort_order = " . (int)$sort_order . ", language_id = " . (int)$language_id . ", attribute_group_type = '" . $_POST['attribute_type'][$key]['position'] . "'";
                $this->db->query("INSERT INTO " . DB_PREFIX . "category_attribute_group_description SET attribute_group_id=" . (int)$attribute_group_id . ", sort_order = " . (int)$sort_order . ", language_id = " . (int)$language_id . ", attribute_group_type = '" . $_POST['attribute_type'][$key]['position'] . "'");
                echo 'Value=' . $_POST['attribute_value'][$key]['position'].'</br>';
                $a_values = explode(",",$_POST['attribute_value'][$key]['position']);
                
                if( $_POST['attribute_type'][$key]['position'] == 'input'){
                    echo "INSERT INTO " . DB_PREFIX . "category_attribute_group_value SET attribute_group_id=" . (int)$attribute_group_id .  ", value = '" . $_POST['attribute_value'][$key]['position'] . "'" .'</br>'; 
                    $this->db->query("INSERT INTO " . DB_PREFIX . "category_attribute_group_value SET attribute_group_id=" . (int)$attribute_group_id .  ", value = '" . $_POST['attribute_value'][$key]['position'] . "'");
                }else{
                    foreach ($a_values as $a_value) {
                        echo $a_value. '</br>';
                        echo "INSERT INTO " . DB_PREFIX . "category_attribute_group_value SET attribute_group_id=" . (int)$attribute_group_id . ", value = '" . $a_value .  "'" .'</br>';
                        $this->db->query("INSERT INTO " . DB_PREFIX . "category_attribute_group_value SET attribute_group_id=" . (int)$attribute_group_id . ", value = '" . $a_value .  "'");                    
                    }    
                }
        }
        
		$this->cache->delete('category');
	}
    
    public function deleteCategoryAttribute($category_id){//This function will delete all the category attributes
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_attribute` WHERE category_id = '" . (int)$category_id . "' ORDER BY category_id ASC");
        if ($query->rows) {
			foreach ($query->rows as $category_attibutes) {
			     
                 
                 $this->db->query("DELETE FROM " . DB_PREFIX . "category_attribute_group_value WHERE attribute_group_id = '" . (int)$category_attibutes['attribute_group_id'] . "'");
                 $this->db->query("DELETE FROM " . DB_PREFIX . "category_attribute_group_description WHERE attribute_group_id = '" . (int)$category_attibutes['attribute_group_id'] . "'");
                 $this->db->query("DELETE FROM " . DB_PREFIX . "category_attribute_description WHERE attribute_id = '" . (int)$category_attibutes['attribute_id'] . "'");
                 $this->db->query("DELETE FROM " . DB_PREFIX . "category_attribute_group WHERE attribute_id = '" . (int)$category_attibutes['attribute_id'] . "'");
                 $this->db->query("DELETE FROM " . DB_PREFIX . "category_attribute WHERE category_id = '" . (int)$category_id . "'");
                 
		    }
        }
    }

	public function deleteCategory($category_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_path WHERE category_id = '" . (int)$category_id . "'");

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_path WHERE path_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {	
			$this->deleteCategory($result['category_id']);
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "'");

		$this->cache->delete('category');
	} 

	// Function to repair any erroneous categories that are not in the category path table.
	public function repairCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int)$parent_id . "'");

		foreach ($query->rows as $category) {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category['category_id'] . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$parent_id . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$category['category_id'] . "', level = '" . (int)$level . "'");

			$this->repairCategories($category['category_id']);
		}
	}

	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR ' &gt; ') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "') AS keyword FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	} 

	public function getCategories($data) {
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

	public function getCategoryDescriptions($category_id) {
		$category_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_description' => $result['meta_description'],
				'description'      => $result['description']
			);
		}

		return $category_description_data;
	}	

	public function getCategoryFilters($category_id) {
		$category_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_filter_data[] = $result['filter_id'];
		}

		return $category_filter_data;
	}

	public function getCategoryStores($category_id) {
		$category_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_store_data[] = $result['store_id'];
		}

		return $category_store_data;
	}

	public function getCategoryLayouts($category_id) {
		$category_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $category_layout_data;
	}

	public function getTotalCategories() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category");

		return $query->row['total'];
	}	

	public function getTotalCategoriesByImageId($image_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category WHERE image_id = '" . (int)$image_id . "'");

		return $query->row['total'];
	}

	public function getTotalCategoriesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
    //This method fetches the attributes avaliable for a given category.
    public function getCategoryAttributes($category_id) {
        
        //echo "SELECT ca.attribute_id, cad.language_id, cag.attribute_group_id, cag.name,cagd.attribute_group_type FROM " . DB_PREFIX . "category_attribute ca LEFT JOIN " . DB_PREFIX . "category_attribute_description cad ON ca.attribute_id = cad.attribute_id LEFT JOIN " . DB_PREFIX . "category_attribute_group cag ON cad.attribute_id = cag.attribute_id LEFT JOIN " . DB_PREFIX . "category_attribute_group_description cagd ON cag.attribute_group_id = cagd.attribute_group_id WHERE ca.category_id = '" . (int)$category_id . "' ORDER BY cagd.sort_order";
        $query = $this->db->query("SELECT ca.attribute_id, cad.language_id, cag.attribute_group_id, cag.name,cagd.attribute_group_type FROM " . DB_PREFIX . "category_attribute ca LEFT JOIN " . DB_PREFIX . "category_attribute_description cad ON ca.attribute_id = cad.attribute_id LEFT JOIN " . DB_PREFIX . "category_attribute_group cag ON cad.attribute_id = cag.attribute_id LEFT JOIN " . DB_PREFIX . "category_attribute_group_description cagd ON cag.attribute_group_id = cagd.attribute_group_id WHERE ca.category_id = '" . (int)$category_id . "' ORDER BY cagd.sort_order");
        
        //echo print_R($query,TRUE);
        $category_attribute_data = array();
        foreach ($query->rows as $category_group) {
            $category_attribute_data[$category_group['name']] = $category_group;
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
             $category_attribute_data[$category_group['name']]['values'] = $attr_values;
        }
        //echo print_R($category_attribute_data, TRUE);
        
        return $category_attribute_data;
    }	
    
    public function getCategoryIdFromName($name){
    	$query = $this->db->query("select oct.category_id from " . DB_PREFIX . "category oct, " . DB_PREFIX . "category_description dc where oct.category_id = dc.category_id and dc.name = '". $name ."'");
    	return $query->row['category_id'];
    }	
}
?>