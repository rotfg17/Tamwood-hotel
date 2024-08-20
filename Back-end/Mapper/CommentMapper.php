<?php
require_once __DIR__ . '/../model/Comment.php';
require_once __DIR__ . '/../Utils/Paging.php';
class CommentMapper{
    private $conn;
    private $table_name = 'comments';

    public function __construct($conn=null) {
        $this->conn = $conn->getConnection();
    }
    public function getCommentTotalCount():int {
        try {//need paging util
            $query = "SELECT COUNT(*) as count
                        FROM " . $this->table_name ."";
        // biding parameter
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            $count = $stmt -> fetch(PDO::FETCH_ASSOC);

            return $count["count"];
        } catch (PDOException $e) {
            error_log("Error in getComments: " . $e->getMessage());
            return 0;
        }
    }
    public function getCommentList(Paging $paging, string $searchString="", string $searchType ="") {
        // Page per row
        $records_per_page = $paging -> getItemsPerPage();
        // cal OFFSET 
        $offset = $paging -> getOffset();

        try {//need paging util
            $query = "SELECT c.comment_id, 
                             u.username, 
                             r.room_number, 
                             c.comment_text, 
                             c.rating, 
                             c.created_at
                        FROM " . $this->table_name. " as c
                                LEFT JOIN users as u
                                ON c.user_id = u.user_id
                                LEFT JOIN rooms as r
                                ON c.room_id = r.room_id";
        if ($searchType=="username")
            $query .= " WHERE u.username LIKE '%".$searchString."%'";
        else if ($searchType=="room_number")
            $query .= " WHERE r.room_number LIKE '%".$searchString."%'";
            $query .= " ORDER BY comment_id DESC 
                        LIMIT :limit OFFSET :offset";
        
        // biding <paramete></paramete>r
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':limit', $records_per_page, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
    
            $comments = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $comments[] = $row;
            }
            return $comments;
        } catch (PDOException $e) {
            error_log("Error in getComments: " . $e->getMessage());
            return [];
        }
    }

    public function getComments() {
        try {//need paging util
            $query = "SELECT * 
                        FROM " . $this->table_name. 
                        " ORDER BY comment_id DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
    
            $comments = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $comments[] = $row;
            }
            return $comments;
        } catch (PDOException $e) {
            error_log("Error in getUsers: " . $e->getMessage());
            return [];
        }
    }
    
    

    public function createComment(Comment $comment) {
        $query = "INSERT INTO " . $this->table_name . "(
                                                            user_id,
                                                            room_id,
                                                            comment_text,
                                                            rating
                                                        ) 
                                                values (
                                                            :user_id, 
                                                            'room_id',
                                                            :comment_text,
                                                            :rating
                                                        )";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $comment->getUserId());
        $stmt->bindParam(':room_id', $comment-> getRoomId());
        $stmt->bindParam(':comment_text', $comment->getCommentText());
        $stmt->bindParam(':rating', $comment->getRating());

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function updateComment(Comment $comment){
        $query = "UPDATE " . $this->table_name . " 
                        SET 
                            comment_text = :comment_text,
                            rating = :rating
                        WHERE 
                            comment_id = :id
                            ";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':comment_text', $comment->getCommentText());
        $stmt->bindParam(':rating', $comment->getRating());
        $stmt->bindParam(':id', $comment->getCommentId());
        
        if ($stmt->execute()) {
        return true;
        }
        return false;
    }

    public function deleteComment(int $comment_id) {
        $query = "DELETE FROM " . $this -> table_name . "
                    WHERE comment_id = :id
                ";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id",$comment_id);

        $stmt->execute();
        if ($stmt->execute()) {
            return true;
            }
            return false;
    }
}

?>