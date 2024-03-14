<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
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

  // Check if missing any parameters
  if (isset($data->quote) && isset($data->author_id) && isset($data->category_id)) {
  
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->where = 'author_id = :author_id';
    // Get author
    $result = $quote->read_single();
    // Get row count
    $num = $result->rowCount();

    // Check if any authors
    if($num == 0) {
      echo json_encode(
        array('message' => 'author_id Not Found')
      );
    } else {
      $quote->category_id = $data->category_id;
      $quote->where = 'category_id = :category_id';
      // Get category
      $result = $quote->read_single();
      // Get row count
      $num = $result->rowCount();

      // Check if any categories
      if($num == 0) {
        echo json_encode(
          array('message' => 'category_id Not Found')
        );
      } else {
        $quote->quote = $data->quote;
        $quote->author_id = $data->author_id;
        $quote->category_id = $data->category_id;
        $quote->where = '(quote = :quote AND author_id = :author_id AND category_id = :category_id)';

        // Create quote
        if($quote->create()) {
          $new_quote_result = $quote->read_single();

          // quote array
          while($row = $new_quote_result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $new_quote = array(
              'id' => $id,
              'quote' => $quote,
              'author_id' => $data->author_id,
              'category_id' => $data->category_id,
            );    
          }
          // Turn to JSON & output
            echo json_encode($new_quote);          
        } else {
          echo json_encode(
            array('message' => 'Quote Not Created')
          );
        }
      }
    }      
  } else {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
  }