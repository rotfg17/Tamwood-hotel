<?php
class Service {
    private $service_id;
    private $service_name;
    private $service_description;
    private $price;

    // Constructor
    public function __construct($service_id, $service_name, $service_description, $price) {
        $this->service_id = $service_id;
        $this->service_name = $service_name;
        $this->service_description = $service_description;
        $this->price = $price;
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