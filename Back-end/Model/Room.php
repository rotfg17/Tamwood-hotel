<?php 
class Room {
    private $room_id;
    private $room_number;
    private $room_type;
    private $price_per_night;
    private $description;
    private $image_url;
    private $status;

    // Constructor
    public function __construct($room_id, $room_number, $room_type, $price_per_night, $description, $image_url, $status) {
        $this->room_id = $room_id;
        $this->room_number = $room_number;
        $this->room_type = $room_type;
        $this->price_per_night = $price_per_night;
        $this->description = $description;
        $this->image_url = $image_url;
        $this->status = $status;
    }

    // Getters
    public function getRoomId() {
        return $this->room_id;
    }

    public function getRoomNumber() {
        return $this->room_number;
    }

    public function getRoomType() {
        return $this->room_type;
    }

    public function getPricePerNight() {
        return $this->price_per_night;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getImageUrl() {
        return $this->image_url;
    }

    public function getStatus() {
        return $this->status;
    }

    // Setters
    public function setRoomId($room_id) {
        $this->room_id = $room_id;
    }

    public function setRoomNumber($room_number) {
        $this->room_number = $room_number;
    }

    public function setRoomType($room_type) {
        $this->room_type = $room_type;
    }

    public function setPricePerNight($price_per_night) {
        $this->price_per_night = $price_per_night;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setImageUrl($image_url) {
        $this->image_url = $image_url;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
}

?>