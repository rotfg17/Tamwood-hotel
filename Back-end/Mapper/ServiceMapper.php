<?php
require_once __DIR__ . '/../model/Service.php';

class ServiceMapper{
    private $conn;
    private $table_name = 'services';

    public function __construct($conn=null) {
        $this->conn = $conn->getConnection();
    }
    
    public function getServices() {
        try {
            $services = [];
            
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY service_id DESC";

            $stmt = $this->conn->prepare($query);

            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $services[] = $row;
            }

            return $services;
        } catch (PDOException $e) {
            error_log("Error in getServices: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function createService(Service $service) {
        try {            
            $query = "INSERT INTO " . $this->table_name . " 
                (service_name, service_description, price) 
                value (:service_name, :service_description, :price)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':service_name', $service->getServiceName());
            $stmt->bindParam(':service_description', $service->getServiceDescription());
            $stmt->bindParam(':price', $service->getPrice());
            
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error in createService: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function updateService(Service $service) {
        try {            
            $fieldsToUpdate = [];
            $params = [':service_id' => $service->getServiceId()];            

            if ($service->getServiceName() !== null) {
                $fieldsToUpdate[] = "service_name = :service_name";
                $params[':service_name'] = $service->getServiceName();
            }

            if ($service->getServiceDescription() !== null) {
                $fieldsToUpdate[] = "service_description = :service_description";
                $params[':service_description'] = $service->getServiceDescription();
            }

            if ($service->getPrice() !== null) {
                $fieldsToUpdate[] = "price = :price";
                $params[':price'] = $service->getPrice();
            }

            $fieldsToUpdate[] = "updated_at = CURRENT_TIMESTAMP";

            $query = "UPDATE " . $this->table_name . " SET " . implode(", ", $fieldsToUpdate) . " WHERE service_id = :service_id";

            $stmt = $this->conn->prepare($query);

            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }
            
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error in updateService: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function deleteService(Service $service) {
        try {            
            $query = "DELETE FROM " . $this->table_name . " WHERE service_id = :service_id";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':service_id', $service->getServiceId());
            
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error in deleteService: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function getServicePrice(array $serviceArr):array{
        try {
            $query = "SELECT price * :quantity as totalPrice FROM " . $this->table_name . " WHERE service_id = :service_id";

            $totalPrice =[];
    
            for($idx=0; $idx < count($serviceArr["service_id"]); $idx++) {
                $stmt = $this->conn->prepare($query);
    
                $stmt->bindParam(':quantity', $serviceArr["quantity"][$idx]);
                $stmt->bindParam(':service_id', $serviceArr["service_id"][$idx]);
                $stmt->execute();

                array_push($totalPrice, $stmt->fetchColumn());
            }
            return $totalPrice;
        } catch (PDOException $e) {
            error_log("Error in deleteService: " . $e->getMessage());
            return $e->getMessage();
        }
    }

}

?>