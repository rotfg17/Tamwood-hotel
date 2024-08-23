<?php
class Service {
    private $service_id;
    private $service_name;
    private $service_description;
    private $price;
    private $created_at;
    private $updated_at;

    // Constructor
    public function __construct($service_id = null, $service_name = null, $service_description = null, $price = 0.0, $created_at = null, $updated_at = null) {
        $this->service_id = $service_id;
        $this->service_name = $service_name;
        $this->service_description = $service_description;
        $this->price = $price;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Getters
    public function getServiceId() {
        return $this->service_id;
    }

    public function getServiceName() {
        return $this->service_name;
    }

    public function getServiceDescription() {
        return $this->service_description;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function getUpdatedAt() {
        return $this->updated_at;
    }

    // Setters
    public function setServiceId($service_id) {
        $this->service_id = $service_id;
    }

    public function setServiceName($service_name) {
        $this->service_name = $service_name;
    }

    public function setServiceDescription($service_description) {
        $this->service_description = $service_description;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

}

?>