<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate Quote object
  $quote = new Quote($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  // Set ID to update
  $quote->id = $data->id;
  $quote->where = 'quotes.id = :id';
  // Get ID
  $result = $quote->read_single();
  // Get row count
  $num = $result->rowCount();

  // Check if ID exists
  if($num == 0) {
    echo json_encode(
      array('message' => 'No Quotes Found')
    );
  } else {
    // Delete quote
    if($quote->delete()) {
      echo json_encode(
        array('id' => $data->id)
      );
    } else {
      echo json_encode(
        array('message' => 'Quote Not Deleted')
      );
    }
  }