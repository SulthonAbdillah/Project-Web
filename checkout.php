<?php
header('Content-Type: application/json');

// Konfigurasi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fagioli_reali";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mendapatkan data dari JSON yang dikirim
$data = json_decode(file_get_contents('php://input'), true);
$customer = $data['customer'];
$cart = $data['cart'];

$name = $customer['name'];
$email = $customer['email'];
$phone = $customer['phone'];
$total_amount = array_reduce($cart, function($carry, $item) {
    return $carry + ($item['price'] * $item['quantity']);
}, 0);

$user_id = $customer['user_id'];

// Menyimpan data ke tabel orders
$sql = "INSERT INTO orders (user_id, order_date, total_amount) VALUES ('$user_id', NOW(), '$total_amount')";

if ($conn->query($sql) === TRUE) {
    $order_id = $conn->insert_id;

    // Menyimpan data ke tabel order_items
    foreach ($cart as $item) {
        $product_id = $item['id'];
        $quantity = $item['quantity'];
        $price = $item['price'];

        $sql_item = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ('$order_id', '$product_id', '$quantity', '$price')";
        $conn->query($sql_item);
    }

    echo json_encode(["success" => true, "message" => "Order has been placed successfully!"]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $sql . "<br>" . $conn->error]);
}

$conn->close();
?>
