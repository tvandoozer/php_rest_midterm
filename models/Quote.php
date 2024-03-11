<?php
  class Quote {
    // DB Stuff
    private $conn;
    private $table = 'quotes';

    // Properties
    public $id;
    public $quote;
    public $author_id;
    public $category_id;
    public $where;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get quotes
    public function read() {
      // Create query
      $query = '
        SELECT quotes.id, quote, author, category
        FROM quotes
        INNER JOIN authors
        ON quotes.author_id = authors.id
        INNER JOIN categories
        ON quotes.category_id = categories.id
        ORDER BY quotes.id';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    // Get Single quote
    public function read_single() {
      // Create query
      $query = ' 
        SELECT quotes.id, quote, author, category
        FROM quotes
        INNER JOIN authors
        ON quotes.author_id = authors.id
        INNER JOIN categories
        ON quotes.category_id = categories.id
        WHERE ' . $this->where . '
        ORDER BY quotes.id';
      
      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind ID
      if ($this->where === 'quotes.id = :id') {
        $stmt->bindParam(':id', $this->id);
      } elseif ($this->where === 'author_id = :author_id') {
        $stmt->bindParam(':author_id', $this->author_id);
      } elseif ($this->where === 'category_id = :category_id') {
        $stmt->bindParam(':category_id', $this->category_id);
      } else {
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
      }

      // Execute query
      $stmt->execute();

      return $stmt;
    } 

    // Create Quote
    public function create() {
      // Create query
      $query = 'INSERT INTO ' . $this->table . ' (quote, author_id, category_id)
      VALUES (:quote, :author_id, :category_id)';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->quote = htmlspecialchars(strip_tags($this->quote));

      // Bind data
      $stmt->bindParam(':quote', $this->quote);
      $stmt->bindParam(':author_id', $this->author_id);
      $stmt->bindParam(':category_id', $this->category_id);

      // Execute query
      if($stmt->execute()) {
        return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }

    // Update Quote
    public function update() {
      // Create query
      $query = 'UPDATE ' . $this->table . '
        SET
          quote = :quote,
          author_id = :author_id,
          category_id = :category_id
        WHERE
          id = :id';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->quote = htmlspecialchars(strip_tags($this->quote));
      $this->author_id = htmlspecialchars(strip_tags($this->author_id));
      $this->category_id = htmlspecialchars(strip_tags($this->category_id));
      $this->id = htmlspecialchars(strip_tags($this->id));

      // Bind data
      $stmt->bindParam(':quote', $this->quote);
      $stmt->bindParam(':author_id', $this->author_id);
      $stmt->bindParam(':category_id', $this->category_id);
      $stmt->bindParam(':id', $this->id);

      // Execute query
      if($stmt->execute()) {
        return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }

    // Delete Quote
    public function delete() {
      //Create query
      $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->id = htmlspecialchars(strip_tags($this->id));

      // Bind data
      $stmt->bindParam(':id', $this->id);

      // Execute query
      if($stmt->execute()) {
        return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }
  }