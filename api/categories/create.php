<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Category.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate Category object
  $category = new Category($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));
  
  // Check if missing category parameter
  if (isset($data->category)) {
  
    $category->category = $data->category;
    $category->where = 'category = :category';
  
    // Create category
    if($category->create()) {
      // Get category
      $result = $category->read_single();

      while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        // Create array
        $cat_arr = array(
          'id' => $id,
          'category' => $category
        );
      }   
      // Make JSON
      echo json_encode($cat_arr);      
    } else {
      echo json_encode(
        array('message' => 'Category Not Created')
      );
    }
  } else {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
  }
