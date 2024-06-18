<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fagioli_reali";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Read and decode the input JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Validate the input data
if (!isset($data['customer']) || !isset($data['cart'])) {
  echo json_encode(['success' => false, 'message' => 'Invalid input']);
  $conn->close();
  exit;
}

$customer = $data['customer'];
$cart = $data['cart'];

// Calculate total amount
$total_amount = array_sum(array_column($cart, 'total'));

// Insert customer and purchase data
$sql = "INSERT INTO purchases (customer_name, customer_email, customer_phone, total_amount) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssd", $customer['name'], $customer['email'], $customer['phone'], $total_amount);

if ($stmt->execute()) {
  $purchase_id = $stmt->insert_id;

  // Insert each item in the cart
  $sql = "INSERT INTO purchase_items (purchase_id, product_id, product_name, quantity, total) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);

  foreach ($cart as $item) {
    $stmt->bind_param("iisid", $purchase_id, $item['id'], $item['name'], $item['quantity'], $item['total']);
    $stmt->execute();
  }

  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
