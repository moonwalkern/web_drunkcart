<?php
class ModelLocalisationZone extends Model {
	public function getZone($zone_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "' AND status = '1'");
		
		return $query->row;
	}		
	
	public function getZonesByCountryId($country_id) {
		$zone_data = $this->cache->get('zone.' . (int)$country_id);
	
		if (!$zone_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "' AND status = '1' ORDER BY name");
	
			$zone_data = $query->rows;
		
			$this->cache->set('zone.' . (int)$country_id, $zone_data);
		}
	
		return $zone_data;
	}
    
    public function getStates($country_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "' AND status = '1'");
		
		return $query->rows;
	}
    
    public function getLocality($state_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "locality WHERE state_id = '" . (int)$state_id . "' AND status = '1' ORDER BY state_id");
		return $query->rows;
	}
    
    public function getAllLocality() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "locality WHERE status = '1' ORDER BY popular desc LIMIT 0,100");
		return $query->rows;
	}
    
    public function getAllLocalityAlpha() {
		$query = $this->db->query("SELECT LEFT(locality_name, 1) AS Alpha,  locality_name, locality_id,state_id,state FROM " . DB_PREFIX . "locality GROUP BY locality_name ORDER BY locality_name");
		return $query->rows;
	}
}
?>