<?php
require_once __DIR__ . '/../model/Comment.php';
require_once __DIR__ . '/../mapper/CommentMapper.php';
require_once __DIR__ . '/../Utils/Paging.php';

class CommentController
{
  private $db;
  private $requestMethod;

  public function __construct($db, $requestMethod)
  {
    $this->db = $db;
    $this->requestMethod = $requestMethod;
  }

  public function processRequest($param)
  {
    switch ($param) {
      case 'comment-list':
        $response = $this->getCommentist();
        break;
      case 'comments':
        $response = $this->getComments();
        break;
      case 'add-comment':
        $response = $this->createComment();
        break;
      case 'update-comment':
        $response = $this->updateComment();
        break;
      case 'delete-comment':
        $response = $this->deleteComment();
        break;
      default:
        $response = $this->notFoundResponse();
        break;
    }
    
    return $response;
    // header($response['status_code_header']);
    // if ($response['body']) {
    //     echo $response['body'];
    // }
  }

  public function getCommentist()
  {
    try {
      //$current_page, $searchString
      $currPage = isset($_GET['currentPage']) ? $_GET['currentPage'] : 1;
      $searchString = isset($_GET['searchString']) ? $_GET['searchString'] : "";
      $searchType = isset($_GET['searchType']) ? $_GET['searchType'] : "";

      $mapper = new CommentMapper($this->db);

      $comments_count = $mapper->getCommentTotalCount();
      $pageObject = new Paging($currPage, $comments_count, 20);
      $result = $mapper->getCommentList($pageObject, $searchString, $searchType);

      return print_r($this->jsonResponse(200, $result));
    } catch (PDOException $e) {
      error_log("Error getting comments: " . $e->getMessage()); // error log
      return $this->jsonResponse(500, ["error" => "Error getting comments: " . $e->getMessage()]);
    }
  }
  public function getComments()
  {
    try {
      $commentMapper = new CommentMapper($this->db);
      $result = $commentMapper->getComments();
      return print_r($this->jsonResponse(200, $result));
    } catch (PDOException $e) {
      error_log("Error getting comments: " . $e->getMessage()); // error log
      return $this->jsonResponse(500, ["error" => "Error getting comments: " . $e->getMessage()]);
    }
  }

  public function createComment()
  {
    try {
      $commentMapper = new CommentMapper($this->db);
      $input = $_POST;

      $comments = new Comment();
      $comments->setUserId($input['comment_id']);
      $comments->setRoomId($input['room_id']);
      $comments->setCommentText($input['comment_text']);
      $comments->setRating($input['rating']);


      if ($commentMapper->createComment($comments)) {
        return print_r($this->jsonResponse(201, ['message' => 'Comment Created']));
      } else {
        throw new Exception("Failed to create comment.");
      }

    } catch (Exception $e) {
      error_log("Error creating comment: " . $e->getMessage());
      return $this->jsonResponse(500, ["error" => "Error creating comment: " . $e->getMessage()]);
    }
  }

  public function updateComment()
  {
    try {
      $commentMapper = new CommentMapper($this->db);
      $input = $_POST;

      $comments = new Comment();
      $comments->setCommentText($input['comment_text']);
      $comments->setRating($input['rating']);
      $comments->setCommentId($input['comment_id']);

      if ($commentMapper->updateComment($comments)) {
        return print_r($this->jsonResponse(201, ['message' => 'Comment Updated']));
      } else {
        throw new Exception("Failed to update comment.");
      }
    } catch (Exception $e) {
      error_log("Error updating comment: " . $e->getMessage());
      return $this->jsonResponse(500, ["error" => "Error updating comment: " . $e->getMessage()]);
    }
  }

  public function deleteComment()
  {
    try {
      $commentMapper = new CommentMapper($this->db);
      $comments_id = $_POST["comment_id"];

      if ($commentMapper -> getCommentbyId($comments_id) > 0) {
        $commentMapper->deleteComment($comments_id);
        return print_r($this->jsonResponse(201, ['message' => 'Comment Deleted']));
      } else {
        throw new Exception("Failed to delete comment.");
      }
    } catch (Exception $e) {
      error_log("Error deleting comment: " . $e->getMessage());
      return $this->jsonResponse(500, ["error" => "Error deleting comment: " . $e->getMessage()]);
    }
  }

  private function jsonResponse($statusCode, $data)
  {
    header("Content-Type: application/json");
    http_response_code($statusCode);
    return json_encode($data);
  }

  private function notFoundResponse()
  {
    $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
    $response['body'] = json_encode(['message' => 'Not Found']);
    return $response;
  }
}

?>