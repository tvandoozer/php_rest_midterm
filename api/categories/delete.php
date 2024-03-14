<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE');
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

  // Set ID to update
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
    // Delete category
    if($category->delete()) {
      echo json_encode(
        array('id' => $data->id)
      );
    } else {
      echo json_encode(
        array('message' => 'Category Not Deleted')
      );
    }
  }