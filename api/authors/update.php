<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
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

  // Check if missing author parameter
  if (isset($data->id) && isset($data->author)) {
    // Set ID to update
    $author->author = $data->author;
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
      // Update author
      if($author->update()) {
        // Get author
        $result = $author->read_single();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          extract($row);

          // Create array
          $author_arr = array(
            'id' => $id,
            'author' => $author
          );
        }   
        // Make JSON
        echo json_encode($author_arr);
      } else {
        echo json_encode(
          array('message' => 'Author Not Updated')
        );
      }
    }
  
  } else {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
  }