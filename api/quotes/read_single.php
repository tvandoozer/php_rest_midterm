<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate quote object
  $quote = new Quote($db);

  // Get Params and set WHERE clause
  if (isset($_GET['author_id']) && isset($_GET['category_id'])) {
    $quote->author_id = $_GET['author_id'];
    $quote->category_id = $_GET['category_id'];
    $quote->where = '(author_id = :author_id AND category_id = :category_id)';
  } elseif (isset($_GET['id'])) {
    $quote->id = $_GET['id'];
    $quote->where = 'quotes.id = :id';
  } elseif (isset($_GET['author_id'])) {
    $quote->author_id = $_GET['author_id'];
    $quote->where = 'author_id = :author_id';
  } elseif (isset($_GET['category_id'])) {
    $quote->category_id = $_GET['category_id'];
    $quote->where = 'category_id = :category_id';
  } else {
    die();
  }

  // Get quote
  $result = $quote->read_single();
  // Get row count
  $num = $result->rowCount();

  // Check if any quotes
  if($num > 0) {
    // quote array
    $quote_arr = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      $quote_item = array(
        'id' => $id,
        'quote' => $quote,
        'author' => $author,
        'category' => $category,
      );

      // Push to "data"
      if ($num > 1) {
        array_push($quote_arr, $quote_item);    
      }
    }

    // Turn to JSON & output
    if ($num > 1) {
      echo json_encode($quote_arr);
    } else {
      echo json_encode($quote_item);
    }

  } else {
    // No Quotes
    echo json_encode(
      array('message' => 'No Quotes Found')
    );
  }