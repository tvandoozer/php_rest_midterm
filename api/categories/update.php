<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
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
  if (isset($data->id) && isset($data->category)) {
    // Set ID to update
    $category->category = $data->category;
    $category->id = $data->id;
    $category->where = 'id = :id';
    // Get ID
    $result = $category->read_single();
    // Get row count
    $num = $result->rowCount();

    // Check if ID exists
    if($num == 0) {
      echo json_encode(
        array('message' => 'category_id Not Found')
      );
    } else {
      // Update category
      if($category->update()) {
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
          array('message' => 'Category Not Updated')
        );
      }
    }
  
  } else {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
  }