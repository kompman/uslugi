<?php
class ModelExtensionModuleService extends Model {
    public function getService($service_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "service WHERE service_id = " . (int)$service_id);

        return $query->row;
    }

    public function getServices() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "service");

        return $query->rows;
    }
}
