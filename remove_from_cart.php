<?php
// Mulai output buffering
ob_start();

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

// Ambil data dari request POST
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id']; // Mengambil id item dari data POST

// Validasi input
if (isset($id)) {
    // Query untuk menghapus data dari tabel keranjang berdasarkan id
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = :id");
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        $response = ['success' => true, 'message' => 'Product removed from cart successfully.'];
    } else {
        $response = ['success' => false, 'message' => 'Failed to remove product from cart.'];
    }
} else {
    $response = ['success' => false, 'message' => 'Invalid product ID.'];
}

// Bersihkan buffer output
ob_end_clean();

// Kirim respons dalam format JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
