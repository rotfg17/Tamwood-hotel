<?php 
$uploadFileDir = __DIR__ . '/../uploads/';

class Room {
    private $room_id;
    private $room_number;
    private $room_type;
    private $price_per_night;
    private $description;
    private $image_url;
    private $status;

    // Constructor
    public function __construct($room_id = null, $room_number = null, $room_type = null, $price_per_night = 0.0, $description = null, $image_url = null, $status = null, $created_at = null, $updated_at = null) {
        $this->room_id = $room_id;
        $this->room_number = $room_number;
        $this->room_type = $room_type;
        $this->price_per_night = $price_per_night;
        $this->description = $description;
        $this->image_url = $image_url;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
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

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function getUpdatedAt() {
        return $this->updated_at;
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

    public function setCreatedAt() {
        $this->created_at = $created_at;
    }

    public function setUpdatedAt() {
        $this->updated_at = $updated_at;
    }

    function uploadFile(array $file): string {
        global $uploadFileDir;

        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            $fileName = $file['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
        
            $allowedFileExtensions = ['jpg', 'gif', 'png', 'webp'];
            if (in_array($fileExtension, $allowedFileExtensions)) {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $dest_path = $uploadFileDir . $newFileName;
    
                if(move_uploaded_file($file['tmp_name'], $dest_path)) {
                    return $newFileName;
                } else {
                    throw new Exception("There was an error moving the uploaded file.", 406);
                }
            } else {
                throw new Exception("Upload failed. Allowed file types: " . implode(',', $allowedFileExtensions), 406);
            }
        } else {
            throw new Exception("No file uploaded or there was an upload error.", 406);
        }
    }
}

?>