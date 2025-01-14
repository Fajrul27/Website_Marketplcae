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

// Ambil data dari request POST
$data = json_decode(file_get_contents('php://input'), true);

// Validasi data
if (isset($data['id']) && isset($data['jumlah'])) {
    $id = $data['id'];
    $jumlah = $data['jumlah'];

    // Ambil data detail produk dari database
    $stmt_product = $pdo->prepare("SELECT id, name, price, image_url FROM products WHERE id = :id");
    $stmt_product->bindParam(':id', $id);
    $stmt_product->execute();
    $product = $stmt_product->fetch(PDO::FETCH_ASSOC);

    // Validasi produk
    if ($product) {
        $name = $product['name'];
        $price = $product['price'];
        $image_url = $product['image_url'];

        // Query untuk memasukkan data ke tabel keranjang
        $stmt = $pdo->prepare("INSERT INTO cart (id, name, jumlah, price, image_url) VALUES (:id, :name, :jumlah, :price, :image_url)");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':jumlah', $jumlah);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image_url', $image_url);

        if ($stmt->execute()) {
            $response = ['success' => true, 'message' => 'Product added to cart successfully.'];
        } else {
            $errorInfo = $stmt->errorInfo();
            $errorMessage = 'Failed to add product to cart. Error: ' . $errorInfo[2];
            error_log($errorMessage); // Cetak pesan error ke file log
            $response = ['success' => false, 'message' => $errorMessage];
        }
    } else {
        $response = ['success' => false, 'message' => 'Product not found.'];
    }
} else {
    $response = ['success' => false, 'message' => 'Invalid input.'];
}

// Kembalikan response dalam format JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
