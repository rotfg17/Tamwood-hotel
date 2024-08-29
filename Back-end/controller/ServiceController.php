<?php
require_once __DIR__ . '/../model/Service.php';
require_once __DIR__ . '/../mapper/ServiceMapper.php';

class ServiceController {
    private $db;
    private $requestMethod;

    public function __construct($db, $requestMethod) {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
    }

    public function processRequest($param) {
        switch ($param) {
            case 'services':
                $response = $this->getServices();
                break;
            case 'create-service':
                $response = $this->createService();
                break;
            case 'update-service':
                $response = $this->updateService();
                break;
            case 'delete-service':
                $response = $this->deleteService();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        
        return $response;
        // header($response['status_code_header']);
        // if ($response['body']) {
        //     echo $response['body'];
        // }
    }

    public function getServices() {
        try {
            $serviceMapper = new ServiceMapper($this->db);

            $result = $serviceMapper->getServices();

            return $this->jsonResponse(200, $result);
        } catch (PDOException $e) {
            error_log("Error getting Services: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error getting Services: " . $e->getMessage()]);
        }
    }

    public function createService() {
        try {
            // $role = unserialize($_SESSION['userClass']) -> getRole();
            // if($role!='admin' && $role != 'staff') throw new Exception("Failed to create Service.");

            $serviceMapper = new ServiceMapper($this->db);
            $input = $_POST;
            
            $service = new Service();
            $service->setServiceName($input['service_name']);
            $service->setServiceDescription($input['service_description']);
            $service->setPrice($input['price']);

            $result = $serviceMapper->createService($service);

            return $this->jsonResponse(200, $result);
        } catch (PDOException $e) {
            error_log("Error getting Services: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error getting Services: " . $e->getMessage()]);
        }
    }

    public function updateService() {
        try {
            // $role = unserialize($_SESSION['userClass']) -> getRole();
            // if($role!='admin' && $role != 'staff') throw new Exception("Failed to update Service.");

            $serviceMapper = new ServiceMapper($this->db);
            $input = $_POST;
            
            $service = new Service();
            $service->setServiceId($input['service_id']);
            $service->setServiceName($input['service_name']);
            $service->setServiceDescription($input['service_description']);
            $service->setPrice($input['price']);

            $result = $serviceMapper->updateService($service);

            return $this->jsonResponse(200, $result);
        } catch (PDOException $e) {
            error_log("Error getting Services: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error getting Services: " . $e->getMessage()]);
        }
    }

    public function deleteService() {
        try {
            // $role = unserialize($_SESSION['userClass']) -> getRole();
            // if($role!='admin' && $role != 'staff') throw new Exception("Failed to delete booking.");

            $serviceMapper = new ServiceMapper($this->db);
            $input = $_POST;
            
            $service = new Service();
            $service->setServiceId($input['service_id']);

            $result = $serviceMapper->deleteService($service);

            return $this->jsonResponse(200, $result);
        } catch (PDOException $e) {
            error_log("Error getting Services: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error getting Services: " . $e->getMessage()]);
        }
    }
    
    private function jsonResponse($statusCode, $data) {
        header("Content-Type: application/json");
        http_response_code($statusCode);
        return json_encode($data);
    }
    
    private function notFoundResponse() {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode(['message' => 'Not Found']);
        return $response;
    }
}

?>