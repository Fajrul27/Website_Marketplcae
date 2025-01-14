<?php
// Koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "marketplace");

if (!$koneksi) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query untuk mengambil data dari database
$query = "SELECT * FROM products";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($koneksi));
}

// Mengubah data menjadi format JSON
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Memberikan response dengan data JSON
header('Content-Type: application/json');
echo json_encode($data);

mysqli_close($koneksi);
?>
