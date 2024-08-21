<?php
class Comment {
    private $comment_id;
    private $user_id;
    private $room_id;
    private $comment_text;
    private $rating;
    private $created_at;

    // Constructor
    public function __construct(int $comment_id=0, int $user_id=0, int $room_id=0, string $comment_text=null, int $rating=0, string $created_at=null) {
        $this->comment_id = $comment_id;
        $this->user_id = $user_id;
        $this->room_id = $room_id;
        $this->comment_text = $comment_text;
        $this->rating = $rating;
        $this->created_at = $created_at;
    }

    // Getters
    public function getCommentId() {
        return $this->comment_id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getRoomId() {
        return $this->room_id;
    }

    public function getCommentText() {
        return $this->comment_text;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    // Setters
    public function setCommentId($comment_id) {
        $this->comment_id = $comment_id;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setRoomId($room_id) {
        $this->room_id = $room_id;
    }

    public function setCommentText($comment_text) {
        $this->comment_text = $comment_text;
    }

    public function setRating($rating) {
        $this->rating = $rating;
    }

    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }
}

?>