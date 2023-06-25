<?php
// Connect to the database
$conn = new mysqli('localhost', 'pharmat1_orto', 'pharmat1_orto', 'pharmat1_orto');

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

  


// Retrieve the list of products
$sql = "SELECT * FROM price_item_tbl WHERE price_id=(SELECT max(id) FROM price_tbl)";
$result = $conn->query($sql);

// Build an array of products
$products = array();
while ($row = $result->fetch_assoc()) {
  $products[] = $row;
}

// Return the list of products as JSON
header('Content-Type: application/json');
echo json_encode($products);
?>