<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../models/Author.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate author object
  $author = new Author($db);

  // Get ID
  // $author->id = isset($_GET['id']) ? $_GET['id'] : die();
  if (isset($_GET['id'])) {
    $author->id = $_GET['id'];
    $author->where = 'id = :id';
  }

  // Get author
  $result = $author->read_single();
  // Get row count
  $num = $result->rowCount();

  // Check if author exists
  if ($num > 0) {
    $row = $result->fetch(PDO::FETCH_ASSOC);

    // Set properties
    $author->author = $row['author'];

    // Create array
    $author_arr = array(
      'id' => $author->id,
      'author' => $author->author,
    );
  
    // Make JSON
    print_r(json_encode($author_arr));
  } else {
    // No Author
    echo json_encode(
      array('message' => 'author_id Not Found')
    );
  }
