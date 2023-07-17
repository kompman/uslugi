<?php
class ModelExtensionModuleService extends Model {
    public function getService($service_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "service WHERE service_id = " . (int)$service_id);

        // Получение изображений галереи
        $query_gallery = $this->db->query("SELECT * FROM " . DB_PREFIX . "service_gallery WHERE service_id = " . (int)$service_id);
        $query->row['gallery_images'] = array_column($query_gallery->rows, 'image');

        return $query->row;
    }

    public function getServiceGalleryImages($service_id) {
        $query = $this->db->query("SELECT image FROM " . DB_PREFIX . "service_gallery WHERE service_id = " . (int)$service_id);
        return array_column($query->rows, 'image');
    }

    public function getServices() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "service");

        return $query->rows;
    }

    public function addServiceRequest($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "service_request SET 
            name = '" . $this->db->escape($data['name']) . "', 
            phone = '" . $this->db->escape($data['phone']) . "', 
            service_name = '" . $this->db->escape($data['service_name']) . "', 
            date = NOW()");
    }
}
