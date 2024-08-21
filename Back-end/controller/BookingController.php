<?php
require_once __DIR__ . '/../model/Bookings.php';
require_once __DIR__ . '/../mapper/BookingMapper.php';
require_once __DIR__ . '/../Utils/Paging.php';

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
            case 'update-booking-status':
                $response = $this->updateBookingStatus();
                break;
            case 'delete-booking':
                $response = $this->deleteBooking();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        
        return $response;
    }

    public function getBookingList() {
        try {
            //$current_page, $searchString
            $currPage = isset($_GET['currentPage'])? $_GET['currentPage'] : 1;
            $searchString = isset($_GET['searchString'])? $_GET['searchString'] : ""; 
            $searchType = isset($_GET['searchType'])? $_GET['searchType'] : "";

            $bookingMapper = new BookingMapper($this->db);

            $booking_count = $bookingMapper->getBookingTotalCount();
            $pageObject = new Paging($currPage, $booking_count, 20);
            $result = $bookingMapper->getBookingList($pageObject, $searchString, $searchType);
            return print_r($this->jsonResponse(200, $result));
        } catch (PDOException $e) {
            error_log("Error getting users: " . $e->getMessage()); // error log
            return $this->jsonResponse(500, ["error" => "Error getting users: " . $e->getMessage()]);
        }
    }
    public function getBookings() {
        try {
            $bookingMapper = new BookingMapper($this->db);
            $result = $bookingMapper->getBookings();
            print_r($result);
            return print_r($this->jsonResponse(200, $result));
        } catch (PDOException $e) {
            error_log("Error getting users: " . $e->getMessage()); // error log
            return $this->jsonResponse(500, ["error" => "Error getting users: " . $e->getMessage()]);
        }
    }
    
    //createBooking
    public function updateBookingStatus() {
        try {
            $bookingMapper = new BookingMapper($this->db);
            $input = $_POST;

            $booking = new Booking();
            $booking -> setBookingId($input['bid']);
            $booking -> setStatus($input['status']);

            if ($bookingMapper -> updateBookingStatus($booking)) {
                return print_r($this->jsonResponse(201, ['message' => 'User Updated']));
            } else {
                throw new Exception("Failed to update user.");
            }
        } catch (Exception $e) {
            error_log("Error updating user: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error updating user: " . $e->getMessage()]);
        }
    }

    public function deleteBooking() {
        try {
            $bookingMapper = new BookingMapper($this->db);
            $booking_id = $_POST["bid"];

            if ($bookingMapper -> deleteBooking($booking_id)) {
                return print_r($this->jsonResponse(201, ['message' => 'User Updated']));
            } else {
                throw new Exception("Failed to delete user.");
            }
        } catch (Exception $e) {
            error_log("Error deleting user: " . $e->getMessage());
            print_r($this->jsonResponse(500, ["error" => "Error deleting user: " . $e->getMessage()]));
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
