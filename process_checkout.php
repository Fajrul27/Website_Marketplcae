<?php
// Assume you have a connection to your database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $payment_method = $_POST['payment_method'];
    $shipping_method = $_POST['shipping_method'];
    // Handle the rest of the product details and total calculation

    // Insert order details into the database
    $sql = "INSERT INTO orders (name, address, phone, payment_method, shipping_method) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $address, $phone, $payment_method, $shipping_method);

    if ($stmt->execute()) {
        echo "Order placed successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
