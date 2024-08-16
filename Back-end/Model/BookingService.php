<?php
class BookingService {
    private $booking_service_id;
    private $booking_id;
    private $service_id;
    private $quantity;
    private $total_price;

    // Constructor
    public function __construct($booking_service_id, $booking_id, $service_id, $quantity, $total_price) {
        $this->booking_service_id = $booking_service_id;
        $this->booking_id = $booking_id;
        $this->service_id = $service_id;
        $this->quantity = $quantity;
        $this->total_price = $total_price;
    }

    // Getters
    public function getBookingServiceId() {
        return $this->booking_service_id;
    }

    public function getBookingId() {
        return $this->booking_id;
    }

    public function getServiceId() {
        return $this->service_id;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getTotalPrice() {
        return $this->total_price;
    }

    // Setters
    public function setBookingServiceId($booking_service_id) {
        $this->booking_service_id = $booking_service_id;
    }

    public function setBookingId($booking_id) {
        $this->booking_id = $booking_id;
    }

    public function setServiceId($service_id) {
        $this->service_id = $service_id;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    public function setTotalPrice($total_price) {
        $this->total_price = $total_price;
    }
}

?>