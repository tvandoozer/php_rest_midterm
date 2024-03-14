<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
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
  if (isset($data->author)) {
  
    $author->author = $data->author;
    $author->where = 'author = :author';
  
    // Create author
    if($author->create()) {
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
        array('message' => 'Author Not Created')
      );
    }
  } else {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
  }
