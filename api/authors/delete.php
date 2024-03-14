<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Author.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate Author object
  $author = new Author($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  // Set ID to update
  $author->id = $data->id;
  $author->where = 'id = :id';
  // Get ID
  $result = $author->read_single();
  // Get row count
  $num = $result->rowCount();

  // Check if ID exists
  if($num == 0) {
    echo json_encode(
      array('message' => 'author_id Not Found')
    );
  } else {
    // Delete author
    if($author->delete()) {
      echo json_encode(
        array('id' => $data->id)
      );
    } else {
      echo json_encode(
        array('message' => 'Author Not Deleted')
      );
    }
  }