<?php
class ModelExtensionModuleService extends Model {
    public function addService($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "service SET 
            name = '" . $this->db->escape($data['name']) . "', 
            description = '" . $this->db->escape($data['description']) . "', 
            price = '" . (float)$data['price'] . "', 
            image = '" . $this->db->escape($data['image']) . "', 
            meta_title = '" . $this->db->escape($data['meta_title']) . "', 
            meta_description = '" . $this->db->escape($data['meta_description']) . "'");
    }

    public function editService($service_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "service SET 
            name = '" . $this->db->escape($data['name']) . "', 
            description = '" . $this->db->escape($data['description']) . "', 
            price = '" . (float)$data['price'] . "', 
            image = '" . $this->db->escape($data['image']) . "', 
            meta_title = '" . $this->db->escape($data['meta_title']) . "', 
            meta_description = '" . $this->db->escape($data['meta_description']) . "' 
            WHERE service_id = " . (int)$service_id);
    }

    public function deleteService($service_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "service WHERE service_id = " . (int)$service_id);
    }

    public function getService($service_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "service WHERE service_id = " . (int)$service_id);

        return $query->row;
    }

    public function getServices() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "service");

        return $query->rows;
    }

    public function install() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "service` (
            `service_id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(128) NOT NULL,
            `description` text NOT NULL,
            `price` decimal(15,4) NOT NULL DEFAULT '0.0000',
            `image` varchar(255) DEFAULT NULL,
            `meta_title` varchar(255) DEFAULT NULL,
            `meta_description` varchar(255) DEFAULT NULL,
            PRIMARY KEY (`service_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "service`;");
    }
}
