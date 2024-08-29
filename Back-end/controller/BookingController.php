<?php
require_once __DIR__ . '/../model/Bookings.php';
require_once __DIR__ . '/../model/BookingService.php';
require_once __DIR__ . '/../mapper/BookingMapper.php';
require_once __DIR__ . '/../mapper/RoomMapper.php';
require_once __DIR__ . '/../Utils/Paging.php';
require_once __DIR__ . '/../Utils/Util.php';

class BookingController{
    private $db;
    private $requestMethod;

    public function __construct($db, $requestMethod) {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
    }

    public function processRequest($param) {
        switch ($param) {
            case 'booking-list':
                $response = $this->getBookingList();
                break;
            case 'bookings':
                $response = $this->getBookings();
                break;
            case 'booking-info':
                $response = $this->getBookingInfo();
                break;
            case 'create-booking':
                $response = $this->createBooking();
                break;
            case 'update-booking-status':
                $response = $this->updateBookingStatus();
                break;
            case 'delete-booking':
                $response = $this->deleteBooking();
                break;
            case 'available-rooms':
                $response = $this->getAvailableRooms();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        
        return $response;
    }

    public function getBookingList() {
        try {
            $currPage = isset($_GET['currentPage']) ? $_GET['currentPage'] : 1;
            $searchString = isset($_GET['searchString']) ? $_GET['searchString'] : ""; 
            $searchType = isset($_GET['searchType']) ? $_GET['searchType'] : "";

            $bookingMapper = new BookingMapper($this->db);

            $booking_count = $bookingMapper->getBookingTotalCount();
            $pageObject = new Paging($currPage, $booking_count, 20);
            
            $data = [
                "result" => $bookingMapper->getBookingList($pageObject, $searchString, $searchType),
                "pagination" => $pageObject->getPaginationLinks($_SERVER['REDIRECT_URL'])
            ];

            return $this->jsonResponse(200, $data);
        } catch (PDOException $e) {
            error_log("Error getting bookings: " . $e->getMessage()); // error log
            return $this->jsonResponse(500, ["error" => "Error getting bookings: " . $e->getMessage()]);
        }
    }

    public function getBookings() {
        try {
            $bookingMapper = new BookingMapper($this->db);
            $result = $bookingMapper->getBookings();
            return $this->jsonResponse(200, $result);
        } catch (PDOException $e) {
            error_log("Error getting bookings: " . $e->getMessage()); // error log
            return $this->jsonResponse(500, ["error" => "Error getting bookings: " . $e->getMessage()]);
        }
    }

    public function getBookingInfo() {
        try {
            $bookingId = isset($_GET['bookingId']) ? $_GET['bookingId'] : "";
            $userId = isset($_GET['userId']) ? $_GET['userId'] : "";
            $bookingMapper = new BookingMapper($this->db);
            $result = $bookingMapper->getBookingInfo($bookingId, $userId);
            return $this->jsonResponse(200, $result);
        } catch (PDOException $e) {
            error_log("Error getting bookings: " . $e->getMessage()); // error log
            return $this->jsonResponse(500, ["error" => "Error getting bookings: " . $e->getMessage()]);
        }
    }

    public function createBooking() {
        try {
            $booking = new Booking();
            $bookingMapper = new BookingMapper($this->db);
            $roomMapper = new RoomMapper($this->db);
            $serviceMapper = new ServiceMapper($this->db);
            $util = new Util();

            $input = json_decode(file_get_contents('php://input'), true);
            
            // Asignar valores al objeto booking
            $booking->setUserId($input['user_id']);
            $booking->setRoomId($input['room_id']);
            $booking->setCheckInDate($input['check_in_date']);
            $booking->setCheckOutDate($input['check_out_date']);
            $serviceArr = $input['service'] ?? [];

            // Calcular los días entre la fecha de entrada y salida
            $interval = $util->getDays($booking->getCheckInDate(), $booking->getCheckOutDate());

            // Obtener el precio de la habitación
            $roomPrice = $roomMapper->getRoomPrice($booking->getRoomId(), $interval);
            // Obtener el precio total de los servicios
            $serviceTotalArray = $serviceMapper->getServicePrice($serviceArr);
            $serviceTotalPrice = array_sum($serviceTotalArray);

            // Calcular el precio total
            $totalPrice = $roomPrice + $serviceTotalPrice;
            $booking->setTotalPrice($totalPrice);

            // Insertar en la tabla de bookings
            $lastBookingIdx = $bookingMapper->createBooking($booking);

            // Insertar en la tabla de booking_service
            $bsArray = [];
            foreach ($serviceArr as $service) {
                $bookingService = new BookingService($lastBookingIdx, $service['service_id'], $service['quantity'], $serviceTotalArray[$service['service_id']]);
                $bsArray[] = $bookingService;
            }

            $result = $bookingMapper->createBookingService($bsArray);

            // Generar auditoría
            $util->Audit_Gen($_SERVER, true, unserialize($_SESSION['userClass'])->getEmail()." successfully booking");
            return $this->jsonResponse(200, $result);
        } catch (Exception $e) {
            error_log("Error creating booking: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error creating booking: " . $e->getMessage()]);
        }
    }

    public function updateBookingStatus() {
        try {
            $role = unserialize($_SESSION['userClass'])->getRole();
            if ($role != 'admin' && $role != 'staff') {
                throw new Exception("Failed to update booking.");
            }

            $bookingMapper = new BookingMapper($this->db);
            $input = $_POST;

            $booking = new Booking();
            $booking->setBookingId($input['bid']);
            $booking->setStatus($input['status']);

            if ($bookingMapper->updateBookingStatus($booking)) {
                return $this->jsonResponse(201, ['message' => 'Booking Updated']);
            } else {
                throw new Exception("Failed to update booking.");
            }
        } catch (Exception $e) {
            error_log("Error updating booking: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error updating booking: " . $e->getMessage()]);
        }
    }

    public function deleteBooking() {
        try {
            $role = $_SESSION['userClass']['role'];
            if ($role != 'admin' && $role != 'staff') {
                throw new Exception("Failed to delete booking.");
            }

            $bookingMapper = new BookingMapper($this->db);
            $booking_id = $_POST["bid"];

            if ($bookingMapper->deleteBooking($booking_id)) {
                return $this->jsonResponse(201, ['message' => 'Booking Deleted']);
            } else {
                throw new Exception("Failed to delete booking.");
            }
        } catch (Exception $e) {
            error_log("Error deleting booking: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error deleting booking: " . $e->getMessage()]);
        }
    }

    public function getAvailableRooms() {
        try {
            $checkInDate = isset($_GET['checkInDate']) ? $_GET['checkInDate'] : '';
            $checkOutDate = isset($_GET['checkOutDate']) ? $_GET['checkOutDate'] : '';
    
            if (empty($checkInDate) || empty($checkOutDate)) {
                return $this->jsonResponse(400, ["error" => "Check-in and Check-out dates are required."]);
            }
    
            $bookingMapper = new BookingMapper($this->db);
            $rooms = $bookingMapper->getAvailableRooms($checkInDate, $checkOutDate);
    
            return $this->jsonResponse(200, ['rooms' => $rooms]);
        } catch (PDOException $e) {
            error_log("Error getting available rooms: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error getting available rooms: " . $e->getMessage()]);
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