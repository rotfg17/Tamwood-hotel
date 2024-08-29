<?php
require_once __DIR__ . '/../model/Bookings.php';
require_once __DIR__ . '/../Utils/Paging.php';
class BookingMapper{
    private $conn;
    private $table_name = 'bookings';

    public function __construct($conn=null) {
        $this->conn = $conn->getConnection();
    }
    public function getBookingTotalCount():int {
        try {//need paging util
            $query = "SELECT COUNT(*) as count
                        FROM " . $this->table_name ."";
        // biding parameter
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
    
            $count = $stmt -> fetch(PDO::FETCH_ASSOC);

            return $count["count"];
        } catch (PDOException $e) {
            error_log("Error in getBookings: " . $e->getMessage());
            return 0;
        }
    }
    public function getBookingList(Paging $paging, string $searchString="", string $searchType ="") {
        // Page per row
        $records_per_page = $paging -> getItemsPerPage();
        // cal OFFSET 
        $offset = $paging -> getOffset();

        try {//need paging util
            $query = "SELECT b.booking_id, 
                             u.username, 
                             r.room_number, 
                             b.check_in_date, 
                             b.check_out_date, 
                             b.total_price,
                             b.status
                        FROM " . $this->table_name. " as b
                                LEFT JOIN users as u
                                ON b.user_id = u.user_id
                                LEFT JOIN rooms as r
                                ON b.room_id = r.room_id";
        if ($searchType=="username")
            $query .= " WHERE u.username LIKE '%".$searchString."%'";
        else if ($searchType=="room_number")
            $query .= " WHERE r.room_number LIKE '%".$searchString."%'";
            $query .= " ORDER BY booking_id DESC 
                        LIMIT :limit OFFSET :offset";
        // biding <paramete></paramete>r
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':limit', $records_per_page, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
    
            $bookings = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $bookings[] = $row;
            }
            return $bookings;
        } catch (PDOException $e) {
            error_log("Error in getBookings: " . $e->getMessage());
            return [];
        }
    }

    public function getBookings() {
        try {
            $query = "SELECT * 
                        FROM " . $this->table_name. 
                        " ORDER BY booking_id DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
    
            $bookings = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $bookings[] = $row;
            }
            return $bookings;
        } catch (PDOException $e) {
            error_log("Error in getBookings: " . $e->getMessage());
            return [];
        }
    }
    
    public function getBookingInfo(mixed $bookingId, mixed $userId) {
        try {
            $query = "SELECT * FROM ".$this->table_name;

            if ($bookingId) {
                $query .= " WHERE booking_id = :bookingId";
            } else if ($userId) {
                if ($bookingId) {
                    $query .= " AND user_id = :userId";
                }

                $query .= " WHERE user_id = :userId";
            }

            $stmt = $this->conn->prepare($query);
            $bookingId && $stmt->bindParam(":bookingId", $bookingId);
            $userId && $stmt->bindParam(":userId", $userId);

            $stmt->execute();
    
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getBookings: " . $e->getMessage());
            return [];
        }
    }

    // create booking with service and user
    public function createBooking(Booking $booking) {
        $query = "INSERT INTO ". $this->table_name. "
                (`user_id`, `room_id`, `check_in_date`, `check_out_date`, `total_price`, `status`) 
                VALUES (:uid, :rid, :checkIn, :checkOut, :totalPrice, 'pending')";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":uid", $booking->getUserId());
        $stmt->bindParam(":rid", $booking->getRoomId());
        $stmt->bindParam(":checkIn", $booking->getCheckInDate());
        $stmt->bindParam(":checkOut", $booking->getCheckOutDate());
        $stmt->bindParam(":totalPrice", $booking->getTotalPrice());

        if ($stmt->execute()) {
            return $this->conn->lastInsertId(); // get Last booking idx
        }

        return false;

    }
    //create booking-service with createBooking
    public function createBookingService(array $serviceArr) {
        $query = "INSERT INTO Booking_Services 
                    (`booking_id`, `service_id`, `quantity`, `total_price`) 
                    VALUES (:bookingId, :serviceId, :quantity, :totalPrice)";
    
        for($idx=0; $idx < count($serviceArr); $idx++) {
            $service = $serviceArr[$idx];

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':bookingId', $service->getBookingId());
            $stmt->bindParam(':serviceId', $service->getServiceId());
            $stmt->bindParam(':quantity', $service->getQuantity());
            $stmt->bindParam(':totalPrice', $service->getTotalPrice());
            if (!$stmt->execute()) {
                return false;
            }
        }
        return true;

    }
    public function updateBookingStatus(Booking $booking){
        $query = "UPDATE " . $this->table_name . " 
                        SET 
                            status = :status
                        WHERE 
                            booking_id = :id
                            ";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':status', $booking->getStatus());
        $stmt->bindParam(':id', $booking->getBookingId());

        if ($stmt->execute()) {
        return true;
        }
        return false;
    }

    public function deleteBooking(int $booking_id) {
        $query = "DELETE FROM " . $this -> table_name . "
                    WHERE booking_id = :id
                ";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id",$booking_id);

        $stmt->execute();
        if ($stmt->execute()) {
            return true;
            }
            return false;
    }
}

?>