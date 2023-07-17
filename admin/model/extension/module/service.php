<?php
class ModelExtensionModuleService extends Model {
    
    public function addService($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "service SET 
            name = '" . $this->db->escape($data['name']) . "', 
            description = '" . $this->db->escape($data['description']) . "', 
            price = '" . $this->db->escape($data['price']) . "',
            image = '" . (isset($data['image']) ? $this->db->escape($data['image']) : "") . "', 
            file = '" . (isset($data['file']) ? $this->db->escape($data['file']) : "") . "',  
            meta_title = '" . $this->db->escape($data['meta_title']) . "', 
            meta_description = '" . $this->db->escape($data['meta_description']) . "'");
    }

    public function editService($service_id, $data) {
        $sql = "UPDATE " . DB_PREFIX . "service SET 
            name = '" . $this->db->escape($data['name']) . "', 
            description = '" . $this->db->escape($data['description']) . "', 
            price = '" . $this->db->escape($data['price']) . "',
            meta_title = '" . $this->db->escape($data['meta_title']) . "', 
            meta_description = '" . $this->db->escape($data['meta_description']) . "'";

        if (isset($data['image'])) {
            $sql .= ", image = '" . $this->db->escape($data['image']) . "'";
        }

        if (isset($data['file'])) {  
            $sql .= ", file = '" . $this->db->escape($data['file']) . "'";
        }

        $sql .= " WHERE service_id = " . (int)$service_id;

        $this->db->query($sql);
    }

    public function deleteService($service_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "service WHERE service_id = '" . (int)$service_id . "'");
    }

    public function getService($service_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "service WHERE service_id = " . (int)$service_id);

        return $query->row;
    }

    public function getServices() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "service");

        return $query->rows;
    }
    public function getRequests() {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "service_request");

    return $query->rows;
}


     public function install() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "service` (
            `service_id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(128) NOT NULL,
            `description` text NOT NULL,
            `price` varchar(128) NOT NULL,
            `image` varchar(255) DEFAULT NULL,
            `file` varchar(255) DEFAULT NULL,  
            `meta_title` varchar(255) DEFAULT NULL,
            `meta_description` varchar(255) DEFAULT NULL,
            PRIMARY KEY (`service_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "service_request` (
            `request_id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(128) NOT NULL,
            `phone` varchar(128) NOT NULL,
            `service_name` varchar(128) NOT NULL,
            `date` datetime NOT NULL,
            PRIMARY KEY (`request_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "service`;");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "service_request`;");
    }
    
}