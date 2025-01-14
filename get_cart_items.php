<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'marketplace';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

// Query untuk mengambil data keranjang belanja dari tabel cart
$stmt = $pdo->query("SELECT * FROM cart");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Format respons dalam bentuk JSON
$response = ['success' => true, 'items' => $items];

// Kembalikan respons dalam format JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
