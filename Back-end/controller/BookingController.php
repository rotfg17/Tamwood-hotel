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
            
            $data = ["result"=>$bookingMapper->getBookingList($pageObject, $searchString, $searchType),
            "pagination" => $pageObject->getPaginationLinks($_SERVER['REDIRECT_URL'])];

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
            $bookingId = isset($_GET['bookingId'])? $_GET['bookingId'] : "";
            $userId = isset($_GET['userId']) ? $_GET['userId'] : "";

            $bookingMapper = new BookingMapper($this->db);
            $result = $bookingMapper->getBookingInfo($bookingId, $userId);
            
            return $this->jsonResponse(200, $result);

        } catch (PDOException $e) {
            error_log("Error getting bookings: " . $e->getMessage()); // error log
            return $this->jsonResponse(500, ["error" => "Error getting bookings: " . $e->getMessage()]);
        }
    }

    //createBooking
    public function createBooking() {
        try {
            $booking = new Booking();
            $bookingMapper = new BookingMapper($this->db);
            $roomMapper = new RoomMapper($this->db);
            $serviceMapper = new ServiceMapper($this->db);
            $util = new Util();

            $input = json_decode(file_get_contents('php://input'), true);
            
            // booking: user_id, room_id, check_in_date, check_out_date return booking_id
            $booking -> setUserId($input['user_id']);
            $booking -> setRoomId($input['room_id']);
            $booking -> setCheckInDate($input['check_in_date']);
            $booking -> setCheckOutDate($input['check_out_date']);
            $serviceArr = $input['service'];

            //calculate date between in-out date
            $interval = $util -> getDays($booking -> getCheckInDate(), $booking -> getCheckOutDate());

            //bring room_price
            $roomPrice = $roomMapper -> getRoomPrice($booking -> getRoomId(), $interval);
            //get service price
            $serviceTotalArray = $serviceMapper -> getServicePrice($serviceArr);
            $serviceTotalPrice = array_sum($serviceTotalArray);

            //calc total price
            $totalPrice = $roomPrice + $serviceTotalPrice;
            $booking -> setTotalPrice($totalPrice);

            //insert booking table
            $lastBookingIdx = $bookingMapper -> createBooking($booking);

            // booking_service: booing_id, service_id, quantity
            $bsArray = [];
            for($i=0; $i < count($serviceArr['service_id']); $i++){
                $bookingService = new BookingService($lastBookingIdx, 
                                                     $serviceArr['service_id'][$i], 
                                                     $serviceArr['quantity'][$i], 
                                                     $serviceTotalArray[$i]);
                array_push($bsArray,$bookingService);
            }
            //insert booking-service table
            $result = $bookingMapper -> createBookingService($bsArray);

            $util -> Audit_Gen($_SERVER, true, unserialize($_SESSION['userClass']) -> getEmail()." successfully booking");
            return $this->jsonResponse(200, $result);
        }catch(Exception $e) {
            error_log("Error creating booking: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error creating booking: " . $e->getMessage()]);
        }
    }

    public function updateBookingStatus() {
        try {

            // $role = unserialize($_SESSION['userClass']) -> getRole();
            // if($role!='admin' && $role != 'staff') throw new Exception("Failed to update booking.");

            $bookingMapper = new BookingMapper($this->db);
            $input = $_POST;

            $booking = new Booking();
            $booking -> setBookingId($input['bid']);
            $booking -> setStatus($input['status']);

            if ($bookingMapper -> updateBookingStatus($booking)) {
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
            if($role!='admin' && $role != 'staff') throw new Exception("Failed to delete booking.");

            $bookingMapper = new BookingMapper($this->db);
            $booking_id = $_POST["bid"];

            if ($bookingMapper -> deleteBooking($booking_id)) {
                return $this->jsonResponse(201, ['message' => 'Booking Updated']);
            } else {
                throw new Exception("Failed to delete booking.");
            }
        } catch (Exception $e) {
            error_log("Error deleting booking: " . $e->getMessage());
            $this->jsonResponse(500, ["error" => "Error deleting booking: " . $e->getMessage()]);
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