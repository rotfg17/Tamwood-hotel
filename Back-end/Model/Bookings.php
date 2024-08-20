<?php
class Booking {
    private $booking_id;
    private $user_id;
    private $room_id;
    private $check_in_date;
    private $check_out_date;
    private $total_price;
    private $status;
    private $created_at;

    // Constructor
    public function __construct($booking_id=null, $user_id=null, $room_id=null, $check_in_date=null, $check_out_date=null, $total_price=null, $status=null, $created_at=null) {
        $this->booking_id = $booking_id;
        $this->user_id = $user_id;
        $this->room_id = $room_id;
        $this->check_in_date = $check_in_date;
        $this->check_out_date = $check_out_date;
        $this->total_price = $total_price;
        $this->status = $status;
        $this->created_at = $created_at;
    }

    // Getters
    public function getBookingId() {
        return $this->booking_id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getRoomId() {
        return $this->room_id;
    }

    public function getCheckInDate() {
        return $this->check_in_date;
    }

    public function getCheckOutDate() {
        return $this->check_out_date;
    }

    public function getTotalPrice() {
        return $this->total_price;
    }

    public function getStatus() {
        return $this->status;
    }

    // Setters
    public function setBookingId($booking_id) {
        $this->booking_id = $booking_id;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setRoomId($room_id) {
        $this->room_id = $room_id;
    }

    public function setCheckInDate($check_in_date) {
        $this->check_in_date = $check_in_date;
    }

    public function setCheckOutDate($check_out_date) {
        $this->check_out_date = $check_out_date;
    }

    public function setTotalPrice($total_price) {
        $this->total_price = $total_price;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
}


?>